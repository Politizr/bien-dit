<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\ListingConstants;

/**
 * Functional service for user management.
 *
 * @author Lionel Bouzonville
 */
class UserService
{
    private $userManager;

    private $logger;

    /**
     *
     * @param @politizr.manager.user
     * @param @logger
     */
    public function __construct(
        $userManager,
        $logger
    ) {
        $this->userManager = $userManager;

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
    public function getUserSuggestedUsersPaginatedListing($userId, $offset = 0, $count = ListingConstants::MODAL_CLASSIC_PAGINATION)
    {
        $users = $this->userManager->generateUserSuggestedUsersPaginatedListing($userId, $offset, $count);

        return $users;
    }

    /* ######################################################################################################## */
    /*                                              CRUD OPERATIONS                                             */
    /* ######################################################################################################## */
}
