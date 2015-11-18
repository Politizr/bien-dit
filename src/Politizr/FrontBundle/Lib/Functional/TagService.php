<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Symfony\Component\EventDispatcher\GenericEvent;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\TagConstants;

use Politizr\FrontBundle\Lib\Manager\DocumentManager;

use Politizr\Model\PTagQuery;

/**
 * Functional service for tag management.
 *
 * @author Lionel Bouzonville
 */
class TagService
{
    private $securityTokenStorage;
    private $securityAuthorizationChecker;
    
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @security.authorization_checker
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                              PUBLIC FUNCTIONS                                            */
    /* ######################################################################################################## */
    
    /**
     * Depending of tag id, compute relative ids ie. ids of tags where parent_id is set to given id
     *
     * @param $id
     * @return array
     */
    public function computeGeotagRelativeIds($id)
    {
        $this->logger->info('*** computeGeotagRelativeIds');

        $ids = array();

        $tag = PTagQuery::create()->findPk($id);

        // get departements under region
        if ($tag->getPTTagTypeId() == TagConstants::TAG_TYPE_GEO
            && in_array($id, TagConstants::getGeoRegionIds())) {
            $ids = PTagQuery::create()
                ->select('Id')
                ->filterByPTParentId($id)
                ->find()
                ->toArray();
        }

        $ids[] = $id;

        return $ids;
    }
}
