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

    
    /**
     * Array of key indexed regions uuids
     *
     * @return array
     */
    public function getRegionUuids()
    {
        $this->logger->info('*** getRegionUuids');

        $regionACAL = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_ACAL);
        $regionALPC = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_ALPC);
        $regionARA = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_ARA);
        $regionBFC = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_BFC);
        $regionB = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_B);
        $regionCVL = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_CVDL);
        $regionC = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_C);
        $regionIDF = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_IDF);
        $regionLRMP = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_LRMP);
        $regionNPDCP = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_NPDCP);
        $regionN = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_N);
        $regionPDLL = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_PDLL);
        $regionPACA = PTagQuery::create()->findPk(TagConstants::TAG_GEO_REGION_ID_PACA);

        return [
            'regionACAL' => $regionACAL->getUuid(),
            'regionALPC' => $regionALPC->getUuid(),
            'regionARA' => $regionARA->getUuid(),
            'regionBFC' => $regionBFC->getUuid(),
            'regionB' => $regionB->getUuid(),
            'regionCVL' => $regionCVL->getUuid(),
            'regionC' => $regionC->getUuid(),
            'regionIDF' => $regionIDF->getUuid(),
            'regionLRMP' => $regionLRMP->getUuid(),
            'regionNPDCP' => $regionNPDCP->getUuid(),
            'regionN' => $regionN->getUuid(),
            'regionPDLL' => $regionPDLL->getUuid(),
            'regionPACA' => $regionPACA->getUuid(),
        ];
    }
    
    /**
     * Array of key indexed departments uuids
     *
     * @param $id
     * @return array
     */
    public function getDepartmentsUuids($regionId)
    {
        $this->logger->info('*** getDepartmentsUuids');

        $mapTags = array();

        if ($regionId == TagConstants::TAG_GEO_REGION_ID_LRMP) {
            $mapTags['ariege'] = PTagQuery::create()->findPk(31)->getUuid();
            $mapTags['aude'] = PTagQuery::create()->findPk(33)->getUuid();
            $mapTags['aveyron'] = PTagQuery::create()->findPk(34)->getUuid();
            $mapTags['gard'] = PTagQuery::create()->findPk(53)->getUuid();
            $mapTags['hauteGaronne'] = PTagQuery::create()->findPk(54)->getUuid();
            $mapTags['gers'] = PTagQuery::create()->findPk(55)->getUuid();
            $mapTags['herault'] = PTagQuery::create()->findPk(57)->getUuid();
            $mapTags['lot'] = PTagQuery::create()->findPk(69)->getUuid();
            $mapTags['lozere'] = PTagQuery::create()->findPk(71)->getUuid();
            $mapTags['hautesPyrenees'] = PTagQuery::create()->findPk(88)->getUuid();
            $mapTags['pyreneesOrientales'] = PTagQuery::create()->findPk(89)->getUuid();
            $mapTags['tarn'] = PTagQuery::create()->findPk(104)->getUuid();
            $mapTags['tarnEtGaronne'] = PTagQuery::create()->findPk(105)->getUuid();
        }

        return $mapTags;
    }
}
