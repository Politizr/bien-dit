<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\ReputationConstants;
use Politizr\Constant\ListingConstants;

use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PUser;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDRCommentQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PRBadgeQuery;
use Politizr\Model\PUReputationQuery;

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
    /*                                              SPECIFIC LISTING                                            */
    /* ######################################################################################################## */

    /**
     * Get user's debates' suggestions paginated listing
     *
     * @param int $userId
     * @param int $offset
     * @param int $count
     * @return PropelCollection PDocument
     */
    public function getUserSuggestedDebatesPaginatedListing($userId, $offset = 0, $count = ListingConstants::MODAL_CLASSIC_PAGINATION)
    {
        $documents = $this->documentManager->generateUserSuggestedDebatesPaginatedListing($userId, $offset, $count);

        return $documents;
    }
    
    /**
     * Get user's reactions' suggestions paginated listing
     *
     * @param int $userId
     * @param int $offset
     * @param int $count
     * @return PropelCollection[PDocument]
     */
    public function getUserSuggestedReactionsPaginatedListing($userId, $offset = 0, $count = ListingConstants::MODAL_CLASSIC_PAGINATION)
    {
        $documents = $this->documentManager->generateUserSuggestedReactionsPaginatedListing($userId, $offset, $count);

        return $documents;
    }
    
    /**
     * Get "my publications" paginated listing
     *
     * @param int $userId
     * @param int $offset
     * @param int $count
     * @return PropelCollection[PDocument]
     */
    public function getMyPublicationsPaginatedListing($userId, $offset = 0, $count = ListingConstants::MODAL_CLASSIC_PAGINATION)
    {
        $documents = $this->documentManager->generateMyDocumentsPaginatedListing($userId, 1, 'published_at', $offset, $count);

        return $documents;
    }

    /**
     * Get "my drafts" paginated listing
     *
     * @param int $userId
     * @param int $offset
     * @param int $count
     * @return PropelCollection[PDocument]
     */
    public function getMyDraftsPaginatedListing($userId, $offset = 0, $count = ListingConstants::MODAL_CLASSIC_PAGINATION)
    {
        $documents = $this->documentManager->generateMyDocumentsPaginatedListing($userId, 0, 'updated_at', $offset, $count);

        return $documents;
    }

    /**
     * Return number of 1st level reactions for user publications
     *
     * @param int $userId
     * @return int
     */
    public function countNbUserDocumentReactionsLevel1($userId)
    {
        $count = $this->documentManager->generateNbUserDocumentReactionsLevel1($userId);

        return $count;
    }

    /**
     * Return number of debates' reactions written first by userId
     *
     * @param int $userId
     * @return int
     */
    public function countNbUserDebateFirstReaction($userId)
    {
        $count = $this->documentManager->generateNbUserDebateFirstReaction($userId);

        return $count;
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
     * @param PDDebate $debate Associated debate
     * @param PDReaction $parent Associated parent reaction
     * @return PDReaction
     */
    public function createReaction(PDDebate $debate, PDReaction $parent = null)
    {
        $this->logger->info('*** createReaction');

        $user = $this->securityTokenStorage->getToken()->getUser();

        // get reaction's associated debate
        if (!$debate) {
            throw new InconsistentDataException(sprintf('Debate "%s" not found', $debateId));
        }

        // get ids
        $debateId = $debate->getId();
        $parentId = null;
        if ($parent) {
            $parentId = $parent->getId();
        }

        // Create reaction for user
        $reaction = $this->documentManager->createReaction($user->getId(), $debateId, $parentId);

        // Init default reaction's tagged tags
        $this->documentManager->initReactionTaggedTags($reaction);

        return $reaction;
    }

    /* ######################################################################################################## */
    /*                                      SECURITY CONTROLS                                                   */
    /* ######################################################################################################## */
    
    /**
     * Controle if user can note document:
     *  - not his document
     *  - not already notate
     *  - has reputation to note down
     *
     * @param PUser $user
     * @param PDDebate|PDReaction|PDDComment|PDRComment $object
     * @param string up|down
     * @return boolean
     */
    public function canUserNoteDocument(PUser $user, $object, $way)
    {
        $this->logger->info('*** canUserNoteDocument');
        // $this->logger->info('$user = '.print_r($user, true));
        // $this->logger->info('$object = '.print_r($object, true));
        // $this->logger->info('$way = '.print_r($way, true));

        // check if current user is not author
        if ($object->getPUserId() == $user->getId()) {
            return false;
        }

        // check if user has already notate
        $query = PUReputationQuery::create()
                    ->filterByPObjectId($object->getId())
                    ->filterByPObjectName($object->getType())
                    ->filterByPRActionId(
                        ReputationConstants::getNotationPRActionsId()
                    );
        $nb = $user->countPUReputations($query);
        if ($nb > 0) {
            return false;
        }

        // check if user can note down
        if ($way == 'down') {
            $score = $user->getReputationScore();
            if ($score < ReputationConstants::ACTION_DEBATE_NOTE_NEG) {
                return false;
            }
        }

        return true;
    }

    /* ######################################################################################################## */
    /*                                  CONTEXT BY DOCUMENT TYPE                                                */
    /* ######################################################################################################## */
    
    /**
     * Compute various attributes depending of the document context
     *
     * @param string $objectName
     * @param int $objectId
     * @param boolean $absolute URL
     * @return array [subject,title,url,document,documentUrl]
     */
    public function computeDocumentContextAttributes($objectName, $objectId, $absolute = true)
    {
        $this->logger->info('*** computeDocumentContextAttributes');
        $this->logger->info('$objectName = '.print_r($objectName, true));
        $this->logger->info('$objectId = '.print_r($objectId, true));
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
                    $url = $this->router->generate('DebateDetail', array('slug' => $subject->getSlug()), $absolute);
                }
                break;
            case ObjectTypeConstants::TYPE_REACTION:
                $subject = PDReactionQuery::create()->findPk($objectId);
                
                if ($subject) {
                    $title = $subject->getTitle();
                    $url = $this->router->generate('ReactionDetail', array('slug' => $subject->getSlug()), $absolute);

                    // Document parent associée à la réaction
                    if ($subject->getTreeLevel() > 1) {
                        // Réaction parente
                        $document = $subject->getParent();
                        $documentUrl = $this->router->generate('ReactionDetail', array('slug' => $document->getSlug()), $absolute);
                    } else {
                        // Débat
                        $document = $subject->getDebate();
                        $documentUrl = $this->router->generate('DebateDetail', array('slug' => $document->getSlug()), $absolute);
                    }
                }

                break;
            case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
                $subject = PDDCommentQuery::create()->findPk($objectId);
                
                if ($subject) {
                    $document = $subject->getPDocument();
                    $title = $subject->getDescription();
                    $url = $this->router->generate('DebateDetail', array('slug' => $document->getSlug()), $absolute) . '#p-' . $subject->getParagraphNo();
                    $documentUrl = $this->router->generate('DebateDetail', array('slug' => $document->getSlug()), $absolute);
                }
                break;
            case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                $subject = PDRCommentQuery::create()->findPk($objectId);
                
                if ($subject) {
                    $document = $subject->getPDocument();
                    $title = $subject->getDescription();
                    $url = $this->router->generate('ReactionDetail', array('slug' => $document->getSlug()), $absolute) . '#p-' . $subject->getParagraphNo();
                    $documentUrl = $this->router->generate('ReactionDetail', array('slug' => $document->getSlug()), $absolute);
                }
                break;
            case ObjectTypeConstants::TYPE_USER:
                $subject = PUserQuery::create()->findPk($objectId);

                if ($subject) {
                    $title = $subject->getFirstname().' '.$subject->getName();
                    $url = $this->router->generate('UserDetail', array('slug' => $subject->getSlug()), $absolute);
                }
                break;
            case ObjectTypeConstants::TYPE_BADGE:
                $subject = PRBadgeQuery::create()->findPk($objectId);

                if ($subject) {
                    $title = $subject->getTitle();
                }
                
                break;
            default:
                throw new InconsistentDataException(sprintf('Object name %s not managed.'), $objectName);
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
