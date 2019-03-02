<?php
namespace Politizr\CommandBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

// use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

use AlgoliaSearch\Client as AlgoliaSearchClient;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PUserQuery;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;

use Politizr\Model\PUserArchiveQuery;
use Politizr\Model\PDDebateArchiveQuery;
use Politizr\Model\PDReactionArchiveQuery;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\PathConstants;
use Politizr\Constant\DocumentConstants;

use Politizr\Exception\PolitizrException;

/**
 * Politizr db indexing to Algolia
 *
 *
 * @author Lionel Bouzonville
 */
class AlgoliaSearchIndexingCommand extends ContainerAwareCommand
{
    private $documentService;
    private $router;
    private $logger;
    private $globalTools;
    private $maxLength;

    protected function configure()
    {
        $this
            ->setName('politizr:algolia:indexing')
            ->setDescription('Algolia indexing operation')
            ->addOption(
                'userId',
                null,
                InputOption::VALUE_OPTIONAL,
                'If set, the task will index only for this PUser id'
            )
            ->addOption(
                'debateId',
                null,
                InputOption::VALUE_OPTIONAL,
                'If set, the task will index only for this PDDebate id'
            )
            ->addOption(
                'reactionId',
                null,
                InputOption::VALUE_OPTIONAL,
                'If set, the task will index only for this PDReaction id'
            )
            ->addOption(
                'forceInitIndex',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will force resetting "indexed_at" local db attributes using "add" command to objects in the index'
            )
            ->addOption(
                'forceUpdateIndex',
                null,
                InputOption::VALUE_NONE,
                'If set, the task will force using "update" command to objects in the index'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isVerbose = (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity());

        // services
        $this->documentService = $this->getContainer()->get('politizr.functional.document');
        $this->router = $this->getContainer()->get('router');
        $this->logger = $this->getContainer()->get('logger');

        // $this->filterManager = $this->getContainer()->get('liip_imagine.filter.manager');
        // $this->cacheManager = $this->getContainer()->get('liip_imagine.cache.manager');
        // $this->dataManager = $this->getContainer()->get('liip_imagine.data.manager');
        $this->globalTools = $this->getContainer()->get('politizr.tools.global');

        $this->maxLength = 15000;


        $appId = $this->getContainer()->getParameter('algolia_app_id');
        $apiKey = $this->getContainer()->getParameter('algolia_admin_api_key');
        $indexName = $this->getContainer()->getParameter('algolia_index_name');

        // Algolia client init
        $client = new AlgoliaSearchClient($appId, $apiKey);
        $index = $client->initIndex($indexName);

        // get option
        $userId = $input->getOption('userId');
        $debateId = $input->getOption('debateId');
        $reactionId = $input->getOption('reactionId');

        $forceInitIndex = $input->getOption('forceInitIndex');
        $forceUpdateIndex = $input->getOption('forceUpdateIndex');

        if ($forceInitIndex) {
            $nbReinitRows = $this->initIndexedAt($userId, $debateId, $reactionId);
            $output->writeln(sprintf('<info>%s rows "indexed_at" attributes have been reinitialized.</info>', $nbReinitRows));
        }

        // New objects
        $indexedNewObjects = array();
        $nbNewIndexed = 0;

        $indexedPUsers = $this->getPUserObjectsToIndex($userId, $nbNewIndexed, true, false, $output);
        $indexedPDDebates = $this->getPDDebateObjectsToIndex($debateId, $nbNewIndexed, true, false, $output);
        $indexedPDReactions = $this->getPDReactionObjectsToIndex($reactionId, $nbNewIndexed, true, false, $output);

        $indexedNewObjects = array_merge($indexedPUsers, $indexedPDDebates, $indexedPDReactions);
        // $index->addObjects($indexedNewObjects);
        foreach ($indexedNewObjects as $indexedNewObject) {
            try {
                $index->addObject($indexedNewObject);
            } catch (\Exception $e) {
                $output->writeln(sprintf('<info>Exception in addObject with %s</info>', var_dump($indexedNewObject, true)));
            }
        }

        // Updated objects
        $indexedUpdatedObjects = array();
        $nbUpdateIndexed = 0;

        $indexedPUsers = $this->getPUserObjectsToIndex($userId, $nbUpdateIndexed, false, $forceUpdateIndex, $output);
        $indexedPDDebates = $this->getPDDebateObjectsToIndex($debateId, $nbUpdateIndexed, false, $forceUpdateIndex, $output);
        $indexedPDReactions = $this->getPDReactionObjectsToIndex($reactionId, $nbUpdateIndexed, false, $forceUpdateIndex, $output);

        $indexedUpdatedObjects = array_merge($indexedPUsers, $indexedPDDebates, $indexedPDReactions);
        // $index->saveObjects($indexedUpdatedObjects);
        foreach ($indexedUpdatedObjects as $indexedUpdatedObject) {
            try {
                $index->saveObject($indexedUpdatedObject);
            } catch (\Exception $e) {
                $output->writeln(sprintf('<info>Exception in addObject with %s</info>', var_dump($indexedUpdatedObject, true)));
            }
        }

        // Deleted objects (newly offlined & archived objects)
        $indexedDeletedObjects = array();
        $nbDeleteIndexed = 0;

        $indexedPUsers = $this->getPUserObjectsToDelete($userId, $nbDeleteIndexed);
        $indexedPDDebates = $this->getPDDebateObjectsToDelete($debateId, $nbDeleteIndexed);
        $indexedPDReactions = $this->getPDReactionObjectsToDelete($reactionId, $nbDeleteIndexed);

        $indexedDeletedObjects = array_merge($indexedPUsers, $indexedPDDebates, $indexedPDReactions);
        // $index->deleteObjects($indexedDeletedObjects);
        foreach ($indexedDeletedObjects as $indexedDeletedObject) {
            try {
                $index->deleteObject($indexedDeletedObject);
            } catch (\Exception $e) {
                $output->writeln(sprintf('<info>Exception in addObject with %s</info>', var_dump($indexedDeletedObject, true)));
            }
        }

        // update db indexation info
        $nbIndexedPUsers = $this->updateIndexedAtForPUserObjects($userId, $forceUpdateIndex);
        $nbIndexedPDDebates = $this->updateIndexedAtForPDDebateObjects($userId, $forceUpdateIndex);
        $nbIndexedPDReactions = $this->updateIndexedAtForPDReactionObjects($userId, $forceUpdateIndex);

        $now = new \DateTime('now');
        $output->writeln('');
        $output->writeln(sprintf('<info>%s - Algolia indexation completed - %s new, %s updated, %s deleted records - %s users, %s debates, %s reactions local db updated</info>', $now->format('Y-m-d H:i:s'), $nbNewIndexed, $nbUpdateIndexed, $nbDeleteIndexed, $nbIndexedPUsers, $nbIndexedPDDebates, $nbIndexedPDReactions));
    }

    /**
     * Reinit "indexed_at" attribute
     *
     * @param int $userId
     * @param int $debateId
     * @param int $reactionId
     */
    private function initIndexedAt($userId, $debateId, $reactionId)
    {
        $nbUpdatedRows = 0;
        $nbUpdatedRows += PUserQuery::create()
            ->_if($userId)
                ->filterById($userId)
            ->_endif()
            ->distinct()
            ->online()
            ->update(array('IndexedAt' => null));

        $nbUpdatedRows += PDDebateQuery::create()
            ->_if($debateId)
                ->filterById($debateId)
            ->_endif()
            ->distinct()
            ->online()
            ->update(array('IndexedAt' => null));

        $nbUpdatedRows += PDReactionQuery::create()
            ->_if($reactionId)
                ->filterById($reactionId)
            ->_endif()
            ->distinct()
            ->online()
            ->update(array('IndexedAt' => null));

        return $nbUpdatedRows;
    }

    /**
     * Find & return PUser objects to be created in index
     *
     * @param int $userId
     * @param int $nbIndexed
     * @param boolean $onlyNew
     * @return array
     */
    private function getPUserObjectsToIndex($userId, & $nbIndexed = 0, $onlyNew = false, $forceUpdateIndex = false, $output)
    {
        // get list of users
        $users = PUserQuery::create()
            ->_if($userId)
                ->filterById($userId)
            ->_endif()
            ->distinct()
            ->online()
            ->_if($onlyNew)
                ->filterByIndexedAt()
            ->_elseif($forceUpdateIndex == false)
                ->where("p_user.indexed_at < p_user.updated_at")
            ->_endif()
            ->find();

        $indexedObjects = array();
        foreach ($users as $user) {
            $imagePath = null;
            try  {
                $imagePath = $this->globalTools->filterImage($user->getPathFileName(), 'algolia_image_user');
            } catch (\Exception $e) {
                $output->writeln(sprintf('Exception for user id-%s - %s', $user->getId(), $e->getMessage()));
            }

            // @todo add circles member of filter (circleUuid) to manage user results in circle search

            $attributes = [
                'objectID' => $user->getUuid(),
                'type' => ObjectTypeConstants::TYPE_USER,
                'typeLabel' => 'Utilisateur',
                'circleUuid' => 0,
                'cssClass' => 'hitUser',
                'id' => $user->getId(),
                'image' => $imagePath,
                'title' => $user->getFullname(),
                'description' => html_entity_decode(strip_tags($user->getBiography())),
                'url' => $this->router->generate('UserDetail', array('slug' => $user->getSlug()), true),
            ];
            $indexedObjects[] = $attributes;

            // if ($geoloc = $user->getGeoloc()) {
            //     $output->writeln(sprintf('%s', print_r($geoloc, true)));
            //     $indexedObjects[] = array_merge($attributes, [
            //         '_geoloc' => [
            //             'lat' => $geoloc[0],
            //             'lng' => $geoloc[1],
            //         ]
            //     ]);
            // } else {
            //     $indexedObjects[] = $attributes;
            // }

            $nbIndexed++;
        }

        return $indexedObjects;
    }

    /**
     * Find & return PDDebate objects to be created in index
     *
     * @param int $debateId
     * @param int $nbIndexed
     * @param boolean $onlyNew
     * @return array
     */
    private function getPDDebateObjectsToIndex($debateId, & $nbIndexed = 0, $onlyNew = false, $forceUpdateIndex = false, $output)
    {
        // get list of debates
        $debates = PDDebateQuery::create()
            ->_if($debateId)
                ->filterById($debateId)
            ->_endif()
            ->distinct()
            ->online()
            ->_if($onlyNew)
                ->filterByIndexedAt()
            ->_elseif($forceUpdateIndex == false)
                ->where("p_d_debate.indexed_at < p_d_debate.updated_at")
            ->_endif()
            ->find();

        $indexedObjects = array();
        foreach ($debates as $debate) {
            $imagePath = null;
            try {
                $imagePath = $this->documentService->findMainImagePath($debate);
                if (!$imagePath) {
                    $imagePath = PathConstants::DEBATE_UPLOAD_WEB_PATH.DocumentConstants::DOC_DEFAULT_FILE_NAME;
                }
                $imagePath = $this->globalTools->filterImage($imagePath, 'algolia_image_document');
            } catch (\Exception $e) {
                $output->writeln(sprintf('Exception for debate id-%s - %s', $debate->getId(), $e->getMessage()));
            }

            // cut description to fit max indexing obj
            $description = html_entity_decode(strip_tags($debate->getDescription()));
            if (strlen($description) > $this->maxLength) {
                $output->writeln(sprintf('debate id-%s strlen %s !', $debate->getId(), strlen($description)));
                $description = $this->globalTools->tokenTruncate($description, $this->maxLength);
            }

            // debate in circle?
            $circleUuid = 0;
            $topic = $debate->getPCTopic();
            if ($topic) {
                $circle = $topic->getPCircle();
                $circleUuid = $circle->getUuid();
            }

            $indexedObjects[] = [
                'objectID' => $debate->getUuid(),
                'type' => ObjectTypeConstants::TYPE_DEBATE,
                'typeLabel' => 'Sujet',
                'circleUuid' => $circleUuid,
                'cssClass' => 'hitPublication',
                'id' => $debate->getId(),
                'image' => $imagePath,
                'title' => $debate->getTitle(),
                'description' => $description,
                'url' => $this->router->generate('DebateDetail', array('slug' => $debate->getSlug()), true),
            ];

            $nbIndexed++;
        }

        return $indexedObjects;
    }

    /**
     * Find & return PDReaction objects to be created in index
     *
     * @param int $reactionId
     * @param int $nbIndexed
     * @param boolean $onlyNew
     * @return array
     */
    private function getPDReactionObjectsToIndex($reactionId, & $nbIndexed = 0, $onlyNew = false, $forceUpdateIndex = false, $output)
    {
        // get list of reactions
        $reactions = PDReactionQuery::create()
            ->_if($reactionId)
                ->filterById($reactionId)
            ->_endif()
            ->distinct()
            ->online()
            ->_if($onlyNew)
                ->filterByIndexedAt()
            ->_elseif($forceUpdateIndex == false)
                ->where("p_d_reaction.indexed_at < p_d_reaction.updated_at")
            ->_endif()
            ->find();

        $indexedObjects = array();
        foreach ($reactions as $reaction) {
            $imagePath = null;
            try {
                $imagePath = $this->documentService->findMainImagePath($reaction);
                if (!$imagePath) {
                    $imagePath = PathConstants::REACTION_UPLOAD_WEB_PATH.DocumentConstants::DOC_DEFAULT_FILE_NAME;
                }
                $imagePath = $this->globalTools->filterImage($imagePath, 'algolia_image_document');
            } catch (\Exception $e) {
                $output->writeln(sprintf('Exception for reaction id-%s - %s', $reaction->getId(), $e->getMessage()));
            }

            // cut description to fit max indexing obj
            $description = html_entity_decode(strip_tags($reaction->getDescription()));
            if (strlen($description) > $this->maxLength) {
                $output->writeln(sprintf('reaction id-%s strlen %s !', $reaction->getId(), strlen($description)));
                $description = $this->globalTools->tokenTruncate($description, $this->maxLength);
            }

            // reaction in circle?
            $circleUuid = 0;
            $topic = $reaction->getPCTopic();
            if ($topic) {
                $circle = $topic->getPCircle();
                $circleUuid = $circle->getUuid();
            }

            $indexedObjects[] = [
                'objectID' => $reaction->getUuid(),
                'type' => ObjectTypeConstants::TYPE_REACTION,
                'typeLabel' => 'Réponse',
                'circleUuid' => $circleUuid,
                'cssClass' => 'hitPublication',
                'id' => $reaction->getId(),
                'image' => $imagePath,
                'title' => $reaction->getTitle(),
                'description' => $description,
                'url' => $this->router->generate('ReactionDetail', array('slug' => $reaction->getSlug()), true),
            ];

            $nbIndexed++;
        }

        return $indexedObjects;
    }

    /**
     * Update db value of indexed_at column
     *
     * @param int $userId
     * @return int
     */
    private function updateIndexedAtForPUserObjects($userId, $forceUpdateIndex = false)
    {
        $now = new \DateTime();

        $nbUpdatedRows = PUserQuery::create()
            ->_if($userId)
                ->filterById($userId)
            ->_endif()
            ->distinct()
            ->_if($forceUpdateIndex == false)
                ->filterByIndexedAt()
                ->_or()
                ->where("p_user.indexed_at < p_user.updated_at")
            ->_endif()
            ->update(array('IndexedAt' => $now, 'UpdatedAt' => $now));

        $nbUpdatedRows += PUserArchiveQuery::create()
            ->_if($userId)
                ->filterById($userId)
            ->_endif()
            ->distinct()
            ->filterByIndexedAt()
            ->_or()
            ->where("p_user_archive.indexed_at < p_user_archive.archived_at")
            ->update(array('IndexedAt' => $now, 'UpdatedAt' => $now));

        return $nbUpdatedRows;
    }

    /**
     * Update db value of indexed_at column
     *
     * @param int $debateId
     * @return int
     */
    private function updateIndexedAtForPDDebateObjects($debateId, $forceUpdateIndex = false)
    {
        $now = new \DateTime();

        $nbUpdatedRows = PDDebateQuery::create()
            ->_if($debateId)
                ->filterById($debateId)
            ->_endif()
            ->distinct()
            ->_if($forceUpdateIndex == false)
                ->filterByIndexedAt()
                ->_or()
                ->where("p_d_debate.indexed_at < p_d_debate.updated_at")
            ->_endif()
            ->update(array('IndexedAt' => $now, 'UpdatedAt' => $now));

        $nbUpdatedRows += PDDebateArchiveQuery::create()
            ->_if($debateId)
                ->filterById($debateId)
            ->_endif()
            ->distinct()
            ->filterByIndexedAt()
            ->_or()
            ->where("p_d_debate_archive.indexed_at < p_d_debate_archive.archived_at")
            ->update(array('IndexedAt' => $now, 'UpdatedAt' => $now));

        return $nbUpdatedRows;
    }

    /**
     * Update db value of indexed_at column
     *
     * @param int $reactionId
     * @return int
     */
    private function updateIndexedAtForPDReactionObjects($reactionId, $forceUpdateIndex = false)
    {
        $now = new \DateTime();

        $nbUpdatedRows = PDReactionQuery::create()
            ->_if($reactionId)
                ->filterById($reactionId)
            ->_endif()
            ->distinct()
            ->_if($forceUpdateIndex == false)
                ->filterByIndexedAt()
                ->_or()
                ->where("p_d_reaction.indexed_at < p_d_reaction.updated_at")
            ->_endif()
            ->update(array('IndexedAt' => $now, 'UpdatedAt' => $now));

        $nbUpdatedRows += PDReactionArchiveQuery::create()
            ->_if($reactionId)
                ->filterById($reactionId)
            ->_endif()
            ->distinct()
            ->filterByIndexedAt()
            ->_or()
            ->where("p_d_reaction_archive.indexed_at < p_d_reaction_archive.archived_at")
            ->update(array('IndexedAt' => $now, 'UpdatedAt' => $now));

        return $nbUpdatedRows;
    }

    /**
     * Find & return PUser objects to be deleted in index
     *
     * @param int $userId
     * @param int $nbDeleteIndexed
     * @return array
     */
    private function getPUserObjectsToDelete($userId, & $nbDeleteIndexed = 0)
    {
        // get new "offine" users
        $users = PUserQuery::create()
            ->_if($userId)
                ->filterById($userId)
            ->_endif()
            ->distinct()
            ->offline()
            ->where("p_user.indexed_at < p_user.updated_at")
            ->find();

        // get new archived users
        $archivedUsers = PUserArchiveQuery::create()
            ->_if($userId)
                ->filterById($userId)
            ->_endif()
            ->distinct()
            ->where("p_user_archive.indexed_at < p_user_archive.archived_at")
            ->find();

        $indexedObjects = array();
        foreach ($users as $user) {
            $indexedObjects[] = $user->getUuid();

            $nbDeleteIndexed++;
        }

        foreach ($archivedUsers as $user) {
            $indexedObjects[] = $user->getUuid();

            $nbDeleteIndexed++;
        }

        return $indexedObjects;
    }

    /**
     * Find & return PDDebate objects to be deleted in index
     *
     * @param int $debateId
     * @param int $nbDeleteIndexed
     * @return array
     */
    private function getPDDebateObjectsToDelete($debateId, & $nbDeleteIndexed = 0)
    {
        // get new "offine" debates
        $debates = PDDebateQuery::create()
            ->_if($debateId)
                ->filterById($debateId)
            ->_endif()
            ->distinct()
            ->offline()
            ->where("p_d_debate.indexed_at < p_d_debate.updated_at")
            ->find();

        // get new archived debates
        $archivedDebates = PDDebateArchiveQuery::create()
            ->_if($debateId)
                ->filterById($debateId)
            ->_endif()
            ->distinct()
            ->where("p_d_debate_archive.indexed_at < p_d_debate_archive.archived_at")
            ->find();

        $indexedObjects = array();
        foreach ($debates as $debate) {
            $indexedObjects[] = $debate->getUuid();

            $nbDeleteIndexed++;
        }

        foreach ($archivedDebates as $debate) {
            $indexedObjects[] = $debate->getUuid();

            $nbDeleteIndexed++;
        }

        return $indexedObjects;
    }

    /**
     * Find & return PDReaction objects to be deleted in index
     *
     * @param int $debateId
     * @param int $nbDeleteIndexed
     * @return array
     */
    private function getPDReactionObjectsToDelete($reactionId, & $nbDeleteIndexed = 0)
    {
        // get new "offine" reactions
        $reactions = PDReactionQuery::create()
            ->_if($reactionId)
                ->filterById($reactionId)
            ->_endif()
            ->distinct()
            ->offline()
            ->where("p_d_reaction.indexed_at < p_d_reaction.updated_at")
            ->find();

        // get new archived reactions
        $archivedReactions = PDReactionArchiveQuery::create()
            ->_if($reactionId)
                ->filterById($reactionId)
            ->_endif()
            ->distinct()
            ->where("p_d_reaction_archive.indexed_at < p_d_reaction_archive.archived_at")
            ->find();

        $indexedObjects = array();
        foreach ($reactions as $reaction) {
            $indexedObjects[] = $reaction->getUuid();

            $nbDeleteIndexed++;
        }

        foreach ($archivedReactions as $reaction) {
            $indexedObjects[] = $reaction->getUuid();

            $nbDeleteIndexed++;
        }

        return $indexedObjects;
    }
}