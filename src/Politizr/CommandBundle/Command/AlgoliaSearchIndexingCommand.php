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

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PUserQuery;

use Politizr\Constant\ObjectTypeConstants;

use Politizr\Exception\PolitizrException;

/**
 * Politizr db indexing to Algolia
 *
 * @author Lionel Bouzonville
 */
class AlgoliaSearchIndexingCommand extends ContainerAwareCommand
{
    private $router;
    private $logger;

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
                'If set, the task will force the add objects in the index by resetting indexed_at attributes'
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $isVerbose = (OutputInterface::VERBOSITY_VERBOSE <= $output->getVerbosity());

        // services
        $this->router = $this->getContainer()->get('router');
        $this->logger = $this->getContainer()->get('logger');

        // Algolia client init
        $client = new AlgoliaSearchClient("PCH7L1BPQO", "858b1170e1d7ff7bf59cad9fbacbc71c");

        $index = $client->initIndex('dev_POLITIZR');

        // get option
        $userId = $input->getOption('userId');
        $debateId = $input->getOption('debateId');
        $reactionId = $input->getOption('reactionId');

        $forceInitIndex = $input->getOption('forceInitIndex');

        if ($forceInitIndex) {
            $nbReinitRows = $this->initIndexedAt($userId, $debateId, $reactionId);
            $output->writeln(sprintf('<info>%s rows "indexed_at" attributes have been reinitialized.</info>', $nbReinitRows));
        }

        // @todo: +tags, ?

        // New objects
        $indexedNewObjects = array();
        $nbNewIndexed = 0;

        $indexedPUsers = $this->getPUserObjectsToIndex($userId, $nbNewIndexed, true, $output);
        $indexedPDDebates = $this->getPDDebateObjectsToIndex($debateId, $nbNewIndexed, true, $output);
        $indexedPDReactions = $this->getPDReactionObjectsToIndex($reactionId, $nbNewIndexed, true, $output);

        $indexedNewObjects = array_merge($indexedPUsers, $indexedPDDebates, $indexedPDReactions);
        $index->addObjects($indexedNewObjects);

        // Updated objects
        $indexedUpdatedObjects = array();
        $nbUpdateIndexed = 0;

        $indexedPUsers = $this->getPUserObjectsToIndex($userId, $nbUpdateIndexed, false, $output);
        $indexedPDDebates = $this->getPDDebateObjectsToIndex($debateId, $nbUpdateIndexed, false, $output);
        $indexedPDReactions = $this->getPDReactionObjectsToIndex($reactionId, $nbUpdateIndexed, false, $output);

        $indexedUpdatedObjects = array_merge($indexedPUsers, $indexedPDDebates, $indexedPDReactions);
        $index->saveObjects($indexedUpdatedObjects);

        // @todo Deleted objects

        // update db indexation info
        $nbIndexedPUsers = $this->createIndexedAtForPUserObjects($userId);
        $nbIndexedPDDebates = $this->createIndexedAtForPDDebateObjects($userId);
        $nbIndexedPDReactions = $this->createIndexedAtForPDReactionObjects($userId);

        $now = new \DateTime('now');
        $output->writeln('');
        $output->writeln(sprintf('<info>%s - Algolia indexing completed!</info>', $now->format('Y-m-d H:i:s')));
        $output->writeln(sprintf('<info>%s new records and %s updated records have been indexed!</info>', $nbNewIndexed, $nbUpdateIndexed));
        $output->writeln(sprintf('<info>%s users, %s debates and %s reactions local db records have been updated!</info>', $nbIndexedPUsers, $nbIndexedPDDebates, $nbIndexedPDReactions));
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
    private function getPUserObjectsToIndex($userId, & $nbIndexed = 0, $onlyNew = false, $output)
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
            ->_else()
                ->where("p_user.updated_at < p_user.indexed_at")
            ->_endif()
            ->find();

        $indexedObjects = array();
        foreach ($users as $user) {
            $output->write('.');

            $indexedObjects[] = [
                'objectID' => $user->getUuid(),
                'type' => $user->getType(),
                'id' => $user->getId(),
                'image' => $user->getPathFileName(),
                'title' => $user->getFullname(),
                'description' => strip_tags($user->getBiography()),
                'url' => $this->router->generate('UserDetail', array('slug' => $user->getSlug()), true),
            ];

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
    private function getPDDebateObjectsToIndex($debateId, & $nbIndexed = 0, $onlyNew = false, $output)
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
            ->_else()
                ->where("p_d_debate.updated_at < p_d_debate.indexed_at")
            ->_endif()
            ->find();

        $indexedObjects = array();
        foreach ($debates as $debate) {
            $output->write('.');

            $indexedObjects[] = [
                'objectID' => $debate->getUuid(),
                'type' => $debate->getType(),
                'id' => $debate->getId(),
                'image' => $debate->getPathFileName(),
                'title' => $debate->getTitle(),
                'description' => strip_tags($debate->getDescription()),
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
    private function getPDReactionObjectsToIndex($reactionId, & $nbIndexed = 0, $onlyNew = false, $output)
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
            ->_else()
                ->where("p_d_reaction.updated_at < p_d_reaction.indexed_at")
            ->_endif()
            ->find();

        $indexedObjects = array();
        foreach ($reactions as $reaction) {
            $output->write('.');

            $indexedObjects[] = [
                'objectID' => $reaction->getUuid(),
                'type' => $reaction->getType(),
                'id' => $reaction->getId(),
                'image' => $reaction->getPathFileName(),
                'title' => $reaction->getTitle(),
                'description' => strip_tags($reaction->getDescription()),
                'url' => $this->router->generate('DebateDetail', array('slug' => $reaction->getSlug()), true),
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
    private function createIndexedAtForPUserObjects($userId)
    {
        $now = new \DateTime();

        $nbUpdatedRows = PUserQuery::create()
            ->_if($userId)
                ->filterById($userId)
            ->_endif()
            ->distinct()
            ->online()
            ->filterByIndexedAt()
            ->_or()
            ->where("p_user.updated_at < p_user.indexed_at")
            ->update(array('IndexedAt' => $now, 'UpdatedAt' => $now));

        return $nbUpdatedRows;
    }

    /**
     * Update db value of indexed_at column
     *
     * @param int $debateId
     * @return int
     */
    private function createIndexedAtForPDDebateObjects($debateId)
    {
        $now = new \DateTime();

        $nbUpdatedRows = PDDebateQuery::create()
            ->_if($debateId)
                ->filterById($debateId)
            ->_endif()
            ->distinct()
            ->online()
            ->filterByIndexedAt()
            ->_or()
            ->where("p_d_debate.updated_at < p_d_debate.indexed_at")
            ->update(array('IndexedAt' => $now, 'UpdatedAt' => $now));

        return $nbUpdatedRows;
    }

    /**
     * Update db value of indexed_at column
     *
     * @param int $reactionId
     * @return int
     */
    private function createIndexedAtForPDReactionObjects($reactionId)
    {
        $now = new \DateTime();

        $nbUpdatedRows = PDReactionQuery::create()
            ->_if($reactionId)
                ->filterById($reactionId)
            ->_endif()
            ->distinct()
            ->online()
            ->filterByIndexedAt()
            ->_or()
            ->where("p_d_reaction.updated_at < p_d_reaction.indexed_at")
            ->update(array('IndexedAt' => $now, 'UpdatedAt' => $now));

        return $nbUpdatedRows;
    }
}