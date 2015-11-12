<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Symfony\Component\EventDispatcher\GenericEvent;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\Constant\ObjectTypeConstants;

use Politizr\FrontBundle\Lib\Manager\DocumentManager;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDRCommentQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PRBadgeQuery;

/**
 * Functional service for document management.
 *
 * @author Lionel Bouzonville
 */
class DocumentService
{
    private $securityTokenStorage;
    private $securityAuthorizationChecker;
    
    private $documentManager;

    private $router;
    
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @security.authorization_checker
     * @param @politizr.manager.document
     * @param @router
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $documentManager,
        $router,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->documentManager = $documentManager;

        $this->router = $router;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                               PRIVATE FUNCTIONS                                          */
    /* ######################################################################################################## */

   /**
     * My documents listing
     *
     * @param integer $offset
     * @param integer $count
     * @return string
     */
    private function generateMyDocumentsListingRawSql($published, $offset, $count = 10)
    {
        $this->logger->info('*** getSql');

        // Function process
        $user = $this->securityTokenStorage->getToken()->getUser();

        $sql = $this->documentManager->createMyDocumentsRawSql(
            $user->getId(),
            $published,
            $offset,
            $count
        );

        return $sql;
    }

    /*
     * Execute SQL and hydrate PDDebate|PDReaction model
     *
     * @param string $sql
     * @return PropelCollection[PDDebate|PDReaction]
     */
    private function hydrateDocumentRows($sql)
    {
        $this->logger->info('*** hydrateDocumentRows');

        $documents = new \PropelCollection();

        if ($sql) {
            $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);

            // dump($sql);

            $stmt = $con->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetchAll();

            // dump($result);

            foreach ($result as $row) {
                $type = $row['type'];

                if ($type == ObjectTypeConstants::TYPE_DEBATE) {
                    $document = PDDebateQuery::create()->findPk($row['id']);
                } elseif ($type == ObjectTypeConstants::TYPE_REACTION) {
                    $document = PDReactionQuery::create()->findPk($row['id']);
                } else {
                    throw new InconsistentDataException(sprintf('Object type %s unknown.', $type));
                }
                
                $documents->set($document->getId(), $document);
            }
        }

        return $documents;
    }


    /* ######################################################################################################## */
    /*                                              CRUD OPERATIONS                                             */
    /* ######################################################################################################## */
    
    /**
     * Create new debate
     *
     * @return PDDebate
     */
    public function createDebate()
    {
        $this->logger->info('*** createDebate');

        $user = $this->securityTokenStorage->getToken()->getUser();
        $debate = $this->documentManager->createDebate($user->getId());

        return $debate;
    }

    /**
     * Create new reaction
     *
     * @param int $debateId Associated debate id
     * @param int $parentId Associated parent reaction id
     * @return PDReaction
     */
    public function createReaction($debateId, $parentId)
    {
        $this->logger->info('*** createReaction');

        $user = $this->securityTokenStorage->getToken()->getUser();

        // get reaction's associated debate
        $debate = PDDebateQuery::create()->findPk($debateId);
        if (!$debate) {
            throw new InconsistentDataException(sprintf('Debate "%s" not found', $debateId));
        }

        // get parent's reaction
        $parent = null;
        if ($parentId) {
            $parent = PDReactionQuery::create()->findPk($parentId);
            if (!$parent) {
                throw new InconsistentDataException(sprintf('Parent reaction "%s" not found', $parentId));
            }
        }

        // Create reaction for user
        $reaction = $this->documentManager->createReaction($user->getId(), $debate->getId(), $parentId);

        // Init default reaction's tagged tags
        $this->documentManager->initReactionTaggedTags($reaction);

        return $reaction;
    }

    /* ######################################################################################################## */
    /*                                              DOCUMENTS LISTING                                           */
    /* ######################################################################################################## */
    
    /**
     * Get the drafts paginated listing of documents (debate + reaction)
     *
     * @param integer $offset
     * @return PropelCollection[PDDebate|PDReaction]
     */
    public function generateDraftsListing($offset = 0)
    {
        $this->logger->info('*** generateDraftsPaginatedListing');
        
        $sql = $this->generateMyDocumentsListingRawSql(false, $offset);
        $documents = $this->hydrateDocumentRows($sql);

        return $documents;
    }
    
    /**
     * Get the publication paginated listing of documents (debate + reaction)
     *
     * @param integer $offset
     * @return PropelCollection[PDDebate|PDReaction]
     */
    public function generatePublicationsListing($offset = 0)
    {
        $this->logger->info('*** generatePublicationsListing');
        
        $sql = $this->generateMyDocumentsListingRawSql(true, $offset);
        $documents = $this->hydrateDocumentRows($sql);

        return $documents;
    }

    /* ######################################################################################################## */
    /*                                  CONTEXT BY DOCUMENT TYPE                                                */
    /* ######################################################################################################## */
    
    /**
     * Compute various attributes depending of the document context
     *
     * @param string $objectName
     * @param int $objectId
     * @param string $profileSuffix
     * @param boolean $absolute URL
     * @return array [subject,title,url,document,documentUrl]
     */
    public function computeDocumentContextAttributes($objectName, $objectId, $profileSuffix, $absolute = true)
    {
        $this->logger->info('*** computeDocumentContextAttributes');
        $this->logger->info('$objectName = '.print_r($objectName, true));
        $this->logger->info('$objectId = '.print_r($objectId, true));
        $this->logger->info('$profileSuffix = '.print_r($profileSuffix, true));
        $this->logger->info('$absolute = '.print_r($absolute, true));

        $subject = null;
        $title = '';
        $url = '#';
        $document = null;
        $documentUrl = '#';
        switch ($objectName) {
            case ObjectTypeConstants::TYPE_DEBATE:
                $subject = PDDebateQuery::create()->findPk($objectId);

                if ($subject) {
                    $title = $subject->getTitle();
                    $url = $this->router->generate('DebateDetail'.$profileSuffix, array('slug' => $subject->getSlug()), $absolute);
                }
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                $subject = PDReactionQuery::create()->findPk($objectId);
                
                if ($subject) {
                    $title = $subject->getTitle();
                    $url = $this->router->generate('ReactionDetail'.$profileSuffix, array('slug' => $subject->getSlug()), $absolute);

                    // Document parent associée à la réaction
                    if ($subject->getTreeLevel() > 1) {
                        // Réaction parente
                        $document = $subject->getParent();
                        $documentUrl = $this->router->generate('ReactionDetail'.$profileSuffix, array('slug' => $document->getSlug()), $absolute);
                    } else {
                        // Débat
                        $document = $subject->getDebate();
                        $documentUrl = $this->router->generate('DebateDetail'.$profileSuffix, array('slug' => $document->getSlug()), $absolute);
                    }
                }

                break;
            case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
                $subject = PDDCommentQuery::create()->findPk($objectId);
                
                if ($subject) {
                    $document = $subject->getPDocument();
                    $title = $subject->getDescription();
                    $url = $this->router->generate('DebateDetail'.$profileSuffix, array('slug' => $document->getSlug()), $absolute) . '#p-' . $subject->getParagraphNo();
                    $documentUrl = $this->router->generate('DebateDetail'.$profileSuffix, array('slug' => $document->getSlug()), $absolute);
                }
                break;
            case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                $subject = PDRCommentQuery::create()->findPk($objectId);
                
                if ($subject) {
                    $document = $subject->getPDocument();
                    $title = $subject->getDescription();
                    $url = $this->router->generate('ReactionDetail'.$profileSuffix, array('slug' => $document->getSlug()), $absolute) . '#p-' . $subject->getParagraphNo();
                    $documentUrl = $this->router->generate('ReactionDetail'.$profileSuffix, array('slug' => $document->getSlug()), $absolute);
                }
                break;
            case ObjectTypeConstants::TYPE_USER:
                $subject = PUserQuery::create()->findPk($objectId);

                if ($subject) {
                    $title = $subject->getFirstname().' '.$subject->getName();
                    $url = $this->router->generate('UserDetail'.$profileSuffix, array('slug' => $subject->getSlug()), $absolute);
                }
                break;
            case ObjectTypeConstants::TYPE_BADGE:
                $subject = PRBadgeQuery::create()->findPk($objectId);

                if ($subject) {
                    $title = $subject->getTitle();
                }
                
                break;
        }

        return array(
            'subject' => $subject,
            'title' => $title,
            'url' => $url,
            'document' => $document,
            'documentUrl' => $documentUrl,
        );
    }
}
