<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Symfony\Component\DomCrawler\Crawler;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\PathConstants;
use Politizr\Constant\DocumentConstants;
use Politizr\Constant\ReputationConstants;
use Politizr\Constant\ListingConstants;
use Politizr\Constant\LocalizationConstants;
use Politizr\Constant\TagConstants;

use Politizr\Model\PDocumentInterface;
use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PUser;
use Politizr\Model\PDMedia;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDMediaQuery;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDRCommentQuery;
use Politizr\Model\PUserQuery;
use Politizr\Model\PLCityQuery;
use Politizr\Model\PRBadgeQuery;
use Politizr\Model\PUReputationQuery;
use Politizr\Model\PTagQuery;
use Politizr\Model\PUFollowDDQuery;
use Politizr\Model\PUFollowUQuery;
use Politizr\Model\PEOperationQuery;
use Politizr\Model\PCTopicQuery;

use Politizr\FrontBundle\Lib\SimpleImage;

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
    private $tagManager;

    private $tagService;
    private $localizationService;
    private $circleService;

    private $router;

    private $globalTools;
    
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @security.authorization_checker
     * @param @politizr.manager.document
     * @param @politizr.manager.tag
     * @param @politizr.functional.tag
     * @param @politizr.functional.localization
     * @param @politizr.functional.circle
     * @param @router
     * @param @politizr.tools.global
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $documentManager,
        $tagManager,
        $tagService,
        $localizationService,
        $circleService,
        $router,
        $globalTools,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->documentManager = $documentManager;
        $this->tagManager = $tagManager;

        $this->tagService = $tagService;
        $this->localizationService = $localizationService;
        $this->circleService = $circleService;

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
     * @param int $currentUserId
     * @param string $geoUuid
     * @param string $type
     * @param string $topicUuid
     * @param string $filterPublication
     * @param string $filterProfile
     * @param string $filterActivity
     * @param string $filterDate
     * @param integer $offset
     * @param înteger $count
     * @return PropelCollection[Publication]
     */
    public function getPublicationsByFilters(
        $currentUserId = null,
        $geoUuid = null,
        $type = null,
        $topicUuid = null,
        $filterPublication = ListingConstants::FILTER_KEYWORD_ALL_PUBLICATIONS,
        $filterProfile = ListingConstants::FILTER_KEYWORD_ALL_USERS,
        $filterActivity = ListingConstants::ORDER_BY_KEYWORD_LAST,
        $filterDate = ListingConstants::FILTER_KEYWORD_ALL_DATE,
        $offset = 0,
        $count = ListingConstants::LISTING_CLASSIC_PAGINATION
    ) {
        $documents = new \PropelCollection();

        $inQueryTopicIds = null;
        if ($currentUserId) {
            $topicIds = $this->circleService->getTopicIdsByUserId($currentUserId);
            $inQueryTopicIds = $this->globalTools->getInQuery($topicIds);
        }

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

        $topicId = null;
        if ($topicUuid) {
            $topic = PCTopicQuery::create()->filterByUuid($topicUuid)->findOne();
            if ($topic) {
                $topicId = $topic->getId();
            }
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
            $inQueryTopicIds,
            $inQueryCityIds,
            $inQueryDepartmentIds,
            $regionId,
            $countryId,
            $topicId,
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
     * @param int $userId
     * @param int $currentUserId
     * @param string $orderBy
     * @param string $tagId
     * @param integer $offset
     * @param înteger $count
     * @return PropelCollection[Publication]
     */
    public function getUserPublicationsPaginatedListing($userId, $currentUserId = null, $orderBy, $tagId = null, $offset = 0, $count = ListingConstants::LISTING_CLASSIC_PAGINATION)
    {
        $inQueryTopicIds = null;
        if ($currentUserId) {
            $topicIds = $this->circleService->getTopicIdsByUserId($currentUserId);
            $inQueryTopicIds = $this->globalTools->getInQuery($topicIds);
        }

        $documents = $this->documentManager->generatePublicationsByUserPaginated($userId, $inQueryTopicIds, $orderBy, $tagId, $offset, $count);

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
     * @param int $currentUserId
     * @param string $orderBy
     * @param integer $offset
     * @param înteger $count
     * @return PropelCollection PDocument
     */
    public function getDocumentsByTagsPaginated($tagIds, $currentUserId = null, $orderBy = null, $offset = 0, $count = ListingConstants::LISTING_CLASSIC_PAGINATION)
    {
        $inQueryTopicIds = null;
        if ($currentUserId) {
            $topicIds = $this->circleService->getTopicIdsByUserId($currentUserId);
            $inQueryTopicIds = $this->globalTools->getInQuery($topicIds);
        }

        $inQueryTagIds = implode(',', $tagIds);
        if (empty($inQueryTagIds)) {
            $inQueryTagIds = 0;
        }

        $documents = $this->documentManager->generateDocumentsByTagsPaginated($inQueryTagIds, $inQueryTopicIds, $orderBy, $offset, $count);

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
        $topicId = $debate->getPCTopicId();

        // Create reaction for user
        $reaction = $this->documentManager->createReaction($user->getId(), $debateId, $parentId, $topicId);

        // Init default reaction's tagged tags
        $this->documentManager->initReactionTaggedTags($reaction);

        return $reaction;
    }

    /**
     * Create new media
     *
     * @param SimpleImage $image
     * @param string $uuid  document uuid
     * @param string $type document type
     * @return PDMedia
     */
    public function createMediaFromSimpleImageByDocUuid(SimpleImage $image, $uuid, $type)
    {
        // get reaction's associated debate
        if (!$image || !$uuid) {
            throw new InconsistentDataException('File null');
        }

        if ($type == ObjectTypeConstants::TYPE_DEBATE) {
            $query = PDDebateQuery::create();
        } elseif ($type == ObjectTypeConstants::TYPE_REACTION) {
            $query = PDReactionQuery::create();
        } else {
            throw new InconsistentDataException(sprintf('Document of type "%s" not found', $type));
        }

        $document = $query->filterByUuid($uuid)->findOne();
        if (!$document) {
            throw new InconsistentDataException(sprintf('Document "%s" not found', $uuid));
        }

        $debateId = null;
        $reactionId = null;
        if ($type == ObjectTypeConstants::TYPE_DEBATE) {
            $debateId = $document->getId();
        } else {
            $reactionId = $document->getId();
        }

        $media = $this->documentManager->createMedia(
            $debateId,
            $reactionId,
            $image->getPath(),
            $image->getBasename(),
            $image->getExtension(),
            $image->getSize(),
            $image->getWidth(),
            $image->getHeight()
        );

        return $media;
    }

    /**
     * Remove a Media object by basename
     *
     * @param string $basename
     * @return boolean
     */
    public function removeMediaByFilename($basename)
    {
        $medias = PDMediaQuery::create()
            ->filterByFileName($basename)
            ->find();

        if (count($medias) > 1) {
            throw new InconsistentDataException(sprintf('Several medias with basename %s have been found, cancel deletion'), $basename);
        } elseif (count($medias) == 1) {
            $medias->delete();

            return true;
        }

        return false;
    }

    /**
     * Update a media object by updating relative file & attributes
     *
     * @param PDMedia $media
     * @param string $absolutePath
     * @return PDMedia
     */
    public function updateMediaFile(PDMedia $media, $absolutePath)
    {
        if (!$media || !$absolutePath) {
            throw new InconsistentDataException('Media and/or absolutePath null');
        }

        $image = new SimpleImage();
        $image->load($absolutePath);

        $media->setPath($image->getPath());
        $media->setFileName($image->getBasename());
        $media->setExtension($image->getExtension());
        $media->setSize($image->getSize());
        $media->setWidth($image->getWidth());
        $media->setHeight($image->getHeight());

        $media->save();

        return $media;
    }


    /* ######################################################################################################## */
    /*                                CONTEXT DOCUMENT COMPUTING                                                */
    /* ######################################################################################################## */
    
    /**
     * Compute and return the main image for a document
     * return fileName if it exists (BC), else get the image in the first div if it exists
     *
     * @return string $path
     */
    public function findMainImagePath(PDocumentInterface $document)
    {
        $fileName = null;
        if ($document->getFileName()) {
            // old behaviour
            $fileName = $document->getFileName();
        } else {
            // parse document to find 1st image
            // cf https://symfony.com/doc/2.8/components/dom_crawler.html
            $crawler = new Crawler($document->getDescription());
            $nbImg = $crawler->filter('img')->count();
            if ($nbImg > 0) {
                $imgSrc = $crawler->filter('img')->first()->attr('src');
                $uuid = $crawler->filter('img')->first()->attr('uuid');

                // extract relative path from src / apply only with local uploads
                if ($uuid) {
                    $fileName = basename($imgSrc);
                    // check image attribute
                    $media = PDMediaQuery::create()->filterByUuid($uuid)->findOne();
                    if ($media) {
                        // min width required to be used as post illustration
                        $width = $media->getWidth();
                        if ($width < DocumentConstants::DOC_MAIN_IMAGE_MIN_WIDTH) {
                            $fileName = null;
                        }
                    }
                }
            }
        }

        // file found
        if ($fileName) {
            switch ($document->getType()) {
                case ObjectTypeConstants::TYPE_DEBATE:
                    $uploadWebPath = PathConstants::DEBATE_UPLOAD_WEB_PATH;
                    $path = $uploadWebPath.$fileName;
                    break;
                case ObjectTypeConstants::TYPE_REACTION:
                    $uploadWebPath = PathConstants::REACTION_UPLOAD_WEB_PATH;
                    $path = $uploadWebPath.$fileName;
                    break;
                default:
                    throw new InconsistentDataException(sprintf('Object type %s not managed', $document->getType()));
            }
            return $path;
        }

        return null;
    }

    /**
     * Manage linked object depending of the context of edition
     *
     * @param
     * @param
     * @return
     */
    public function manageEditDocumentContext(PDDebate $debate, $opUuid = null, $topicUuid = null)
    {
        // manage OP context
        $operation = null;
        if ($opUuid && $debate->getType() == ObjectTypeConstants::TYPE_DEBATE) {
            $operation = PEOperationQuery::create()
                ->filterByUuid($opUuid)
                ->findOne();
            if (!$operation) {
                throw new InconsistentDataException(sprintf('Operation %s not found.', $opUuid));
            }

            $debate->setPEOperationId($operation->getId());
            $debate->save();

            // op preset tags
            $tags = $operation->getPTags();
            foreach ($tags as $tag) {
                $this->tagManager->createDebateTag($debate->getId(), $tag->getId());
            }

            return $debate;
        }

        // manage TOPIC context
        $topic = null;
        if ($topicUuid) {
            $topic = PCTopicQuery::create()
                ->filterByUuid($topicUuid)
                ->findOne();
            if (!$topic) {
                throw new InconsistentDataException(sprintf('Topic %s not found.', $topicUuid));
            }

            $debate->setPCTopicId($topic->getId());
            $debate->save();

            return $debate;
        }

        return $debate;
    }

    /**
     * Compute various attributes depending of the document context
     *
     * @param string $objectType
     * @param int $objectId
     * @param int $authorUserId
     * @param boolean $absolute URL
     * @return array [subject,title,url,document,documentUrl,author,authorUrl]
     */
    public function computeDocumentContextAttributes($objectType, $objectId, $authorUserId, $absolute = true)
    {
        // $this->logger->info('*** computeDocumentContextAttributes');
        // $this->logger->info('$objectType = '.print_r($objectType, true));
        // $this->logger->info('$objectId = '.print_r($objectId, true));
        // $this->logger->info('$authorUserId = '.print_r($authorUserId, true));
        // $this->logger->info('$absolute = '.print_r($absolute, true));

        $subject = null;
        $title = '';
        $url = '#';
        $document = null;
        $documentUrl = '#';
        $author = null;
        $authorUrl = '#';
        $initialDebate = null;

        switch ($objectType) {
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

                    $initialDebate = $subject->getDebate();
                }

                break;
            case ObjectTypeConstants::TYPE_DEBATE_COMMENT:
                $subject = PDDCommentQuery::create()->findPk($objectId);
                
                if ($subject) {
                    $document = $subject->getPDocument();
                    $title = $subject->getDescription();
                    $url = $this->router->generate('DebateDetail', array('slug' => $document->getSlug()), $absolute) . '#p-' . $subject->getParagraphNo();
                    $documentUrl = $this->router->generate('DebateDetail', array('slug' => $document->getSlug()), $absolute);
                    $initialDebate = $subject->getPDocument();
                }
                break;
            case ObjectTypeConstants::TYPE_REACTION_COMMENT:
                $subject = PDRCommentQuery::create()->findPk($objectId);
                
                if ($subject) {
                    $document = $subject->getPDocument();
                    $title = $subject->getDescription();
                    $url = $this->router->generate('ReactionDetail', array('slug' => $document->getSlug()), $absolute) . '#p-' . $subject->getParagraphNo();
                    $documentUrl = $this->router->generate('ReactionDetail', array('slug' => $document->getSlug()), $absolute);
                    if ($document) {
                        $initialDebate = $document->getDebate();
                    }
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
                throw new InconsistentDataException(sprintf('Object type %s not managed.', $objectType));
        }

        // Récupération de l'auteur de l'interaction
        $author = PUserQuery::create()
            ->online()
            ->findPk($authorUserId);
        if ($author) {
            $authorUrl = $this->router->generate('UserDetail', array('slug' => $author->getSlug()), $absolute);
        }

        return array(
            'subject' => $subject,
            'type' => $objectType,
            'title' => $title,
            'url' => $url,
            'document' => $document,
            'documentUrl' => $documentUrl,
            'author' => $author,
            'authorUrl' => $authorUrl,
            'initialDebate' => $initialDebate,
        );
    }
}
