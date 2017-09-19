<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Politizr\Exception\InconsistentDataException;

use Politizr\Model\PUser;
use Politizr\Model\PCTopic;

use Politizr\Model\PCircleQuery;
use Politizr\Model\PCTopicQuery;
use Politizr\Model\PUInPCQuery;

use \PropelCollection;

/**
 * Functional service for circle management.
 *
 * @author Lionel Bouzonville
 */
class CircleService
{
    private $securityTokenStorage;
    private $securityAuthorizationChecker;
    
    private $circleManager;
    
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @security.authorization_checker
     * @param @politizr.manager.circle
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $circleManager,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->circleManager = $circleManager;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                              CIRCLE FUNCTIONS                                            */
    /* ######################################################################################################## */
    
    /**
     * Get authorized circles by user
     *
     * @param PUser $user
     * @return PropelCollection[PCircle]
     */
    public function getCirclesByUser(PUser $user = null)
    {
        // $this->logger->info('*** getCirclesByUserId');
        // $this->logger->info('$user = '.print_r($user, true));
        // $this->logger->info('$user->getPLCityId = '.print_r($user->getPLCityId(), true));

        if (!$user || !$user->getPLCityId()) {
            return null;
        }

        $circles = PCircleQuery::create()
                        ->filterByOnline(true)
                        ->usePCGroupLCQuery()
                            ->filterByPLCityId($user->getPLCityId())
                        ->endUse()
                        ->find();

        return $circles;
    }

    /**
     * Check if user is member of circle
     *
     * @param int $userId
     * @param int $circleId
     * @return boolean
     */
    public function isUserMemberOfCircle($userId = null, $circleId = null)
    {
        // $this->logger->info('*** isUserMemberOfCircle');
        // $this->logger->info('$userId = '.print_r($userId, true));
        // $this->logger->info('$circleId = '.print_r($circleId, true));

        $nb = PUInPCQuery::create()
            ->filterByPUserId($userId)
            ->filterByPCircleId($circleId)
            ->count();

        if ($nb > 0) {
            return true;
        }

        return false;
    }

    /* ######################################################################################################## */
    /*                                              TOPIC FUNCTIONS                                             */
    /* ######################################################################################################## */
    
    /**
     * Update form options if topic has geoloc preset values
     *
     * @param PCTopic $topic
     * @param array $options
     */
    public function updateDocumentLocalizationTypeOptions(PCTopic $topic = null, & $options)
    {
        if ($topic && $topic->getForceGeolocType() && $topic->getForceGeolocId()) {
            $options['force_geoloc'] = true;
            $options['force_geoloc_type'] = $topic->getForceGeolocType();
            $options['force_geoloc_id'] = $topic->getForceGeolocId();
        }
    }

    /**
     * Get topic id list by user id
     *
     * @param int $userId
     * @return array
     */
    public function getTopicIdsByUserId($userId)
    {
        $topicIds = PCTopicQuery::create()
            ->select('Id')
            ->usePCircleQuery()
                ->usePUInPCQuery()
                    ->filterByPUserId($userId)
                ->endUse()
            ->endUse()
            ->find()
            ->toArray();

        return $topicIds;
    }
}
