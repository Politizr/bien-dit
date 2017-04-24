<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\ReputationConstants;
use Politizr\Constant\ListingConstants;
use Politizr\Constant\LocalizationConstants;
use Politizr\Constant\TagConstants;

use Politizr\Model\PDocumentInterface;
use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PUser;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDRCommentQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PLCityQuery;
use Politizr\Model\PRBadgeQuery;
use Politizr\Model\PUReputationQuery;
use Politizr\Model\PTagQuery;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PUFollowUQuery;

/**
 * Functional service for document management.
 * beta
 *
 * @author Lionel Bouzonville
 */
class DocumentService
{
    private $securityTokenStorage;
    private $securityAuthorizationChecker;
    
    private $documentManager;

    private $tagService;
    private $localizationService;

    private $router;

    private $globalTools;
    
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @security.authorization_checker
     * @param @politizr.manager.document
     * @param @politizr.functional.tag
     * @param @politizr.functional.localization
     * @param @router
     * @param @politizr.tools.global
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $documentManager,
        $tagService,
        $localizationService,
        $router,
        $globalTools,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->documentManager = $documentManager;

        $this->tagService = $tagService;
        $this->localizationService = $localizationService;

        $this->router = $router;

        $this->globalTools = $globalTools;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                               PRIVATE FUNCTIONS                                          */
    /* ######################################################################################################## */

    /**
     * Get array of user's PUFollowDD's ids
     * @todo refactoring duplicate w. TimelineService
     *
     * @param integer $userId
     * @return array
     */
    private function getFollowedDebatesIdsArray($userId)
    {
        $debateIds = PUFollowDDQuery::create()
            ->select('PDDebateId')
            ->filterByPUserId($userId)
            ->find()
            ->toArray();

        return $debateIds;
    }

    /**
     * Get array of user's PUFollowU's ids
     * @todo refactoring duplicate w. TimelineService
     *
     * @param integer $userId
     * @return array
     */
    private function getFollowedUsersIdsArray($userId)
    {
        $userIds = PUFollowUQuery::create()
            ->select('PUserId')
            ->filterByPUserFollowerId($userId)
            ->find()
            ->toArray();

        return $userIds;
    }


    /* ######################################################################################################## */
    /*                                              SPECIFIC LISTING                                            */
    /* ######################################################################################################## */

    /**
     * Get "homepage publications" listing
     * beta
     * @param înteger $count
     * @return PropelCollection[Publication]
     */
    public function getHomepagePublicationsListing($count = ListingConstants::LISTING_HOMEPAGE_DOCUMENTS_LIMIT)
    {
        $documents = $this->documentManager->generateHomepagePublications($count);

        return $documents;
    }

    /**
     * Get filtered paginated documents
     * beta
     *
     * @param string $geoUuid
     * @param string $type
     * @param string $filterPublication
     * @param string $filterProfile
     * @param string $filterActivity
     * @param string $filterDate
     * @param integer $offset
     * @param înteger $count
     * @return PropelCollection[Publication]
     */
    public function getPublicationsByFilters(
        $geoUuid,
        $type,
        $filterPublication = ListingConstants::FILTER_KEYWORD_ALL_PUBLICATIONS,
        $filterProfile = ListingConstants::FILTER_KEYWORD_ALL_USERS,
        $filterActivity = ListingConstants::ORDER_BY_KEYWORD_LAST,
        $filterDate = ListingConstants::FILTER_KEYWORD_ALL_DATE,
        $offset = 0,
        $count = ListingConstants::LISTING_CLASSIC_PAGINATION
    ) {
        $documents = new \PropelCollection();

        $inQueryCityIds = null;
        $inQueryDepartmentIds = null;
        $cityIds = null;
        $departmentIds = null;
        $regionId = null;
        $countryId = null;
        if ($geoUuid) {
            $this->localizationService->fillExtendedChildrenGeoIdsFromGeoUuid(
                $geoUuid,
                $type,
                $cityIds,
                $departmentIds,
                $regionId,
                $countryId
            );

            $inQueryCityIds = $this->globalTools->getInQuery($cityIds);
            $inQueryDepartmentIds = $this->globalTools->getInQuery($departmentIds);
        }

        // "most views" activity filters only applied to debates and/or reactions:
        // - if publications type selected is all > return only debates & reactions
        // - if publications type selected is comment > return "no result"
        if ($filterActivity == ListingConstants::ORDER_BY_KEYWORD_MOST_VIEWS) {
            if ($filterPublication == ListingConstants::FILTER_KEYWORD_COMMENTS) {
                return $documents;
            } elseif ($filterPublication == ListingConstants::FILTER_KEYWORD_ALL_PUBLICATIONS) {
                $filterPublication = ListingConstants::FILTER_KEYWORD_DEBATES_AND_REACTIONS;
            }
        }

        // "most followed" activity filters only applied to debates:
        // - if publications type selected is comment or reactions > return "no result"
        if ($filterActivity == ListingConstants::ORDER_BY_KEYWORD_MOST_FOLLOWED) {
            if ($filterPublication == ListingConstants::FILTER_KEYWORD_COMMENTS || $filterPublication == ListingConstants::FILTER_KEYWORD_REACTIONS) {
                return $documents;
            }
        }

        // "most reactions" activity filters only applied to debates and/or reactions:
        // - if publications type selected is all > return only debates
        // - if publications type selected is comment > return "no result"
        if ($filterActivity == ListingConstants::ORDER_BY_KEYWORD_MOST_REACTIONS) {
            if ($filterPublication == ListingConstants::FILTER_KEYWORD_COMMENTS) {
                return $documents;
            } elseif ($filterPublication == ListingConstants::FILTER_KEYWORD_ALL_PUBLICATIONS) {
                $filterPublication = ListingConstants::FILTER_KEYWORD_DEBATES;
            }
        }

        // "most comments" activity filters only applied to debates and/or reactions:
        // - if publications type selected is all > return only debates & reactions
        // - if publications type selected is comment > return "no result"
        if ($filterActivity == ListingConstants::ORDER_BY_KEYWORD_MOST_COMMENTS) {
            if ($filterPublication == ListingConstants::FILTER_KEYWORD_COMMENTS) {
                return $documents;
            } elseif ($filterPublication == ListingConstants::FILTER_KEYWORD_ALL_PUBLICATIONS) {
                $filterPublication = ListingConstants::FILTER_KEYWORD_DEBATES_AND_REACTIONS;
            }
        }

        $documents = $this->documentManager->generatePublicationsByFiltersPaginated(
            $inQueryCityIds,
            $inQueryDepartmentIds,
            $regionId,
            $countryId,
            $filterPublication,
            $filterProfile,
            $filterActivity,
            $filterDate,
            $offset,
            $count
        );

        return $documents;
    }

    /**
     * Get "user publications" paginated listing
     * beta
     *
     * @param array $userId
     * @param string $orderBy
     * @param string $tagId
     * @param integer $offset
     * @param înteger $count
     * @return PropelCollection[Publication]
     */
    public function getUserPublicationsPaginatedListing($userId, $orderBy, $tagId = null, $offset = 0, $count = ListingConstants::LISTING_CLASSIC_PAGINATION)
    {
        $documents = $this->documentManager->generatePublicationsByUserPaginated($userId, $orderBy, $tagId, $offset, $count);

        return $documents;
    }

    /**
     * Get recommended paginated documents
     * beta
     *
     * @param integer $month
     * @param integer $year
     * @param integer $offset
     * @param înteger $count
     * @return PropelCollection PDocument
     */
    public function getDocumentsByRecommendPaginated($month, $year, $offset = 0, $count = ListingConstants::LISTING_CLASSIC_PAGINATION)
    {
        $now = new \DateTime();
        $currentMonth = $now->format('n');
        $currentYear = $now->format('Y');

        if ($currentMonth == $month && $currentYear == $year) {
            $filterDate = ListingConstants::FILTER_KEYWORD_LAST_MONTH;
        } else {
            $filterDate = ListingConstants::FILTER_KEYWORD_EXACT_MONTH;
        }

        $documents = $this->documentManager->generateDocumentsByRecommendPaginated($filterDate, $month, $year, $offset, $count);

        return $documents;
    }

    /**
     * Get paginated documents by organization
     * beta
     *
     * @param array $organizationId
     * @param string $orderBy
     * @param integer $offset
     * @param înteger $count
     * @return PropelCollection
     */
    public function getPublicationsByOrganizationPaginated($organizationId, $orderBy = null, $offset = 0, $count = ListingConstants::LISTING_CLASSIC_PAGINATION)
    {
        $publications = $this->documentManager->generatePublicationsByOrganizationPaginated($organizationId, $orderBy, $offset, $count);

        return $publications;
    }

    /**
     * Get paginated documents by tags
     * beta
     *
     * @param array $tagIds
     * @param string $orderBy
     * @param integer $offset
     * @param înteger $count
     * @return PropelCollection PDocument
     */
    public function getDocumentsByTagsPaginated($tagIds, $orderBy = null, $offset = 0, $count = ListingConstants::LISTING_CLASSIC_PAGINATION)
    {
        $inQueryTagIds = implode(',', $tagIds);
        if (empty($inQueryTagIds)) {
            $inQueryTagIds = 0;
        }

        $documents = $this->documentManager->generateDocumentsByTagsPaginated($inQueryTagIds, $orderBy, $offset, $count);

        return $documents;
    }

    /**
     * Get top documents best notes
     * beta
     *
     * @param înteger $count
     * @return PropelCollection PDocument
     */
    public function getTopDocumentsBestNote($count = ListingConstants::LISTING_TOP_DOCUMENTS_LIMIT)
    {
        $documents = $this->documentManager->generateTopDocumentsBestNote($count);

        return $documents;
    }

    /**
     * Get user's debates' suggestions paginated listing
     * beta
     *
     * @param int $userId
     * @param int $count
     * @return PropelCollection PDocument
     */
    public function getUserDocumentsSuggestion($userId, $count = ListingConstants::LISTING_SUGGESTION_DOCUMENTS_LIMIT)
    {
        // Récupération des ids city/dep/region du user courant
        $inQueryGeoTagIds = null;
        $user = PUserQuery::create()->findPk($userId);

        $cityId = $user->getPLCityId();
        $departmentId = null;
        $regionId = null;
        if ($cityId) {
            $city = PLCityQuery::create()->findPk($cityId);
            if ($city) {
                $department = $city->getPLDepartment();
                if ($department) {
                    $departmentId = $department->getId();
                    $region = $department->getPLRegion();
                    if ($region) {
                        $regionId = $region->getId();
                    }
                }
            }
        }

        // Récupération d'un tableau des ids des débats suivis
        $debateIds = $this->getFollowedDebatesIdsArray($userId);
        $inQueryDebateIds = $this->globalTools->getInQuery($debateIds);

        // Récupération d'un tableau des ids des users suivis
        $userIds = $this->getFollowedUsersIdsArray($userId);
        $inQueryUserIds = $this->globalTools->getInQuery($userIds);

        $documents = $this->documentManager->generateUserDocumentsSuggestion(
            $userId,
            $cityId,
            $departmentId,
            $regionId,
            $inQueryDebateIds,
            $inQueryUserIds,
            $count
        );

        return $documents;
    }
    
    /**
     * Get user's debates' last publication paginated listing
     * beta
     *
     * @param int $userId
     * @param int $count
     * @return PropelCollection PDocument
     */
    public function getDocumentsLastPublished($count = ListingConstants::LISTING_SUGGESTION_DOCUMENTS_LIMIT)
    {
        $documents = $this->documentManager->generateDocumentsLastPublished($count);

        return $documents;
    }
    
    /**
     * Get "my drafts" paginated listing
     * beta
     *
     * @param int $userId
     * @param int $offset
     * @param int $count
     * @return PropelCollection[PDocument]
     */
    public function getMyDraftsPaginatedListing($userId, $offset = 0, $count = ListingConstants::MODAL_CLASSIC_PAGINATION)
    {
        $documents = $this->documentManager->generateMyDraftsPaginatedListing($userId, $offset, $count);

        return $documents;
    }

    /**
     * Get "my favorites" paginated listing
     * beta
     *
     * @param int $userId
     * @param int $offset
     * @param int $count
     * @return PropelCollection[PDocument]
     */
    public function getMyBookmarksPaginatedListing($userId, $offset = 0, $count = ListingConstants::MODAL_CLASSIC_PAGINATION)
    {
        $documents = $this->documentManager->generateMyBookmarksPaginatedListing($userId, $offset, $count);

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

    /**
     * Return "similars" debates based on tags
     *
     * @param PDocumentInterface $document
     * @return PropelCollection[PDDebate]
     */
    public function getSimilarDebates(PDocumentInterface $document)
    {
        if (!$document) {
            return null;
        }

        $similars = PDDebateQuery::create()
            ->filterById($document->getDebateId(), \Criteria::NOT_EQUAL)
            ->usePDDTaggedTQuery()
                ->filterByPTag($document->getTags(TagConstants::TAG_TYPE_THEME))
            ->endUse()
            ->distinct()
            ->online()
            ->limit(ListingConstants::LISTING_DEBATE_SIMILARS)
            ->orderByNotePos('desc')
            ->orderByNoteNeg('asc')
            ->find();

        if (count($similars) == 0) {
            $similars = PDDebateQuery::create()
                ->filterById($document->getDebateId(), \Criteria::NOT_EQUAL)
                ->usePDDTaggedTQuery()
                    ->filterByPTag($document->getTags(TagConstants::TAG_TYPE_FAMILY))
                ->endUse()
                ->distinct()
                ->online()
                ->limit(ListingConstants::LISTING_DEBATE_SIMILARS)
                ->orderByNotePos('desc')
                ->orderByNoteNeg('asc')
                ->find();
        }

        return $similars;
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
        // $this->logger->info('*** createDebate');

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
        // $this->logger->info('*** createReaction');

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
        // $this->logger->info('*** canUserNoteDocument');
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
        // $this->logger->info('*** computeDocumentContextAttributes');
        // $this->logger->info('$objectName = '.print_r($objectName, true));
        // $this->logger->info('$objectId = '.print_r($objectId, true));
        // $this->logger->info('$absolute = '.print_r($absolute, true));

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
            case ObjectTypeConstants::TYPE_TAG:
                $subject = PTagQuery::create()->findPk($objectId);

                if ($subject) {
                    $title = $subject->getTitle();
                    $url = $this->router->generate('ListingByTag', array('slug' => $subject->getSlug()), $absolute);
                }
                
                break;
            default:
                throw new InconsistentDataException(sprintf('Object name %s not managed.', $objectName));
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
