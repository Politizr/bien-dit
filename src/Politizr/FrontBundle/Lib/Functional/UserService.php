<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\ListingConstants;

use Politizr\Model\PUser;

use Politizr\Model\PUserQuery;
use Politizr\Model\PTagQuery;

/**
 * Functional service for document management.
 * beta
 *
 * @author Lionel Bouzonville
 */
class UserService
{
    private $securityTokenStorage;
    private $securityAuthorizationChecker;
    
    private $userManager;

    private $tagService;

    private $router;
    
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @security.authorization_checker
     * @param @politizr.manager.user
     * @param @politizr.functional.tag
     * @param @router
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $userManager,
        $tagService,
        $router,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->userManager = $userManager;

        $this->tagService = $tagService;

        $this->router = $router;

        $this->logger = $logger;
    }


    /* ######################################################################################################## */
    /*                                              SPECIFIC LISTING                                            */
    /* ######################################################################################################## */

    /**
     * Get filtered paginated documents
     * beta
     *
     * @param string $geoTagUuid
     * @param string $filterProfile
     * @param string $filterActivity
     * @param string $filterDate
     * @param integer $offset
     * @param înteger $count
     * @return PropelCollection[Publication]
     */
    public function getUsersByFilters(
        $geoTagUuid,
        $filterProfile = ListingConstants::FILTER_KEYWORD_ALL_USERS,
        $filterActivity = ListingConstants::ORDER_BY_KEYWORD_MOST_ACTIVE,
        $filterDate = ListingConstants::FILTER_KEYWORD_ALL_DATE,
        $offset = 0,
        $count = ListingConstants::LISTING_CLASSIC_PAGINATION
    ) {
        $users = new \PropelCollection();

        $tagIds = [];
        if ($geoTagUuid) {
            $tag = PTagQuery::create()
                ->filterByUuid($geoTagUuid)
                ->findOne();
            if (!$tag) {
                throw new InconsistentDataException(sprintf('Tag %s not found', $filters['map']));
            }
            $tagIds = $this->tagService->computePublicationGeotagRelativeIds($tag->getId());
        }

        $keywords = array($filterProfile, $filterDate);

        $users = PUserQuery::create()
            ->distinct()
            ->online()
            ->filterIfTags($tagIds)
            ->filterByKeywords($keywords)
            ->orderWithKeyword($filterActivity)
            ->paginate($offset, $count);

        return $users;
    }
}
