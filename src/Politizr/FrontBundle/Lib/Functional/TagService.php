<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Symfony\Component\EventDispatcher\GenericEvent;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

use Politizr\Exception\InconsistentDataException;
use Politizr\Exception\FormValidationException;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\TagConstants;
use Politizr\Constant\ListingConstants;

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
    
    private $tagManager;
    
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @security.authorization_checker
     * @param @politizr.manager.tag
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $tagManager,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->tagManager = $tagManager;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                             PRIVATE FUNCTIONS                                            */
    /* ######################################################################################################## */

    /**
     * Execute SQL and hydrate TimelineRow model
     *
     * @param string $sql
     * @return array[id]
     */
    private function hydrateTagIdRows($sql)
    {
        $this->logger->info('*** hydrateTagIdRows');

        $timeline = array();

        if ($sql) {
            $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);

            // dump($sql);

            $stmt = $con->prepare($sql);
            $stmt->execute();

            $result = $stmt->fetchAll();

            // dump($result);

            $tagIds = [];
            foreach ($result as $row) {
                $tagIds[] = $row['p_tag_id'];
            }
        }

        return $tagIds;
    }


    /* ######################################################################################################## */
    /*                                              PUBLIC FUNCTIONS                                            */
    /* ######################################################################################################## */
    
    /**
     * Depending of tag id, compute relative ids ie. ids of tags where parent_id is set to given id
     *
     * region = region + departements
     *
     * @param int $id Tag ID
     * @return array
     */
    public function computePublicationGeotagRelativeIds($id)
    {
        $this->logger->info('*** computePublicationGeotagRelativeIds');

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
     * Depending of tag id, compute relative ids ie. ids of tags where parent_id is set to given id
     *
     * france = france + region + departements
     * region = region + departements
     *
     * @param int $id Tag ID
     * @return array
     */
    public function computeUserGeotagRelativeIds($id)
    {
        $this->logger->info('*** computeUserGeotagRelativeIds');

        $ids = array();
        $tag = PTagQuery::create()->findPk($id);

        if ($tag->getPTTagTypeId() == TagConstants::TAG_TYPE_GEO
            && $id == TagConstants::TAG_GEO_FRANCE_ID) {
            // get regions under france
            $regionIds = PTagQuery::create()
                ->select('Id')
                ->filterByPTParentId($id)
                ->find()
                ->toArray();

            $ids = array_merge($ids, $regionIds);

            foreach ($regionIds as $regionId) {
                $departmentIds = PTagQuery::create()
                ->select('Id')
                ->filterByPTParentId($regionId)
                ->find()
                ->toArray();
                
                $ids = array_merge($ids, $departmentIds);
            }
        } elseif ($tag->getPTTagTypeId() == TagConstants::TAG_TYPE_GEO
            && in_array($id, TagConstants::getGeoRegionIds())) {
            // get departements under region
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

        if ($regionId == TagConstants::TAG_GEO_REGION_ID_ACAL) {
            $mapTags['marne'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_MARNE)->getUuid();
            $mapTags['ardennes'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_ARDENNES)->getUuid();
            $mapTags['aube'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_AUBE)->getUuid();
            $mapTags['hauteMarne'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_HAUTE_MARNE)->getUuid();
            $mapTags['basRhin'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_BAS_RHIN)->getUuid();
            $mapTags['meurtheEtMoselle'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_MEURTHE_ET_MOSELLE)->getUuid();
            $mapTags['hautRhin'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_HAUT_RHIN)->getUuid();
            $mapTags['meuse'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_MEUSE)->getUuid();
            $mapTags['moselle'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_MOSELLE)->getUuid();
            $mapTags['vosges'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_VOSGES)->getUuid();
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_ALPC) {
            $mapTags['pyreneesAtlantiques'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_PYRENEES_ATLANTIQUES)->getUuid();
            $mapTags['landes'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_LANDES)->getUuid();
            $mapTags['gironde'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_GIRONDE)->getUuid();
            $mapTags['dordogne'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_DORDOGNE)->getUuid();
            $mapTags['lotEtGaronne'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_LOT_ET_GARONNE)->getUuid();
            $mapTags['charenteMaritime'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_CHARENTE_MARITIME)->getUuid();
            $mapTags['correze'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_CORREZE)->getUuid();
            $mapTags['creuse'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_CREUSE)->getUuid();
            $mapTags['hauteVienne'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_HAUTE_VIENNE)->getUuid();
            $mapTags['vienne'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_VIENNE)->getUuid();
            $mapTags['charente'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_CHARENTE)->getUuid();
            $mapTags['deuxSevres'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_DEUX_SEVRES)->getUuid();
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_ARA) {
            $mapTags['allier'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_ALLIER)->getUuid();
            $mapTags['cantal'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_CANTAL)->getUuid();
            $mapTags['hauteLoire'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_HAUTE_LOIRE)->getUuid();
            $mapTags['puyDeDome'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_PUY_DE_DOME)->getUuid();
            $mapTags['ain'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_AIN)->getUuid();
            $mapTags['isere'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_ISERE)->getUuid();
            $mapTags['hauteSavoie'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_HAUTE_SAVOIE)->getUuid();
            $mapTags['loire'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_LOIRE)->getUuid();
            $mapTags['rhone'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_RHONE)->getUuid();
            $mapTags['savoie'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_SAVOIE)->getUuid();
            $mapTags['ardeche'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_ARDECHE)->getUuid();
            $mapTags['drome'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_DROME)->getUuid();
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_B) {
            $mapTags['cotesDArmor'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_COTES_D_ARMOR)->getUuid();
            $mapTags['morbihan'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_MORBIHAN)->getUuid();
            $mapTags['finistere'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_FINISTERE)->getUuid();
            $mapTags['illeEtVilaine'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_ILLE_ET_VILAINE)->getUuid();
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_BFC) {
            $mapTags['yonne'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_YONNE)->getUuid();
            $mapTags['hauteSaone'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_HAUTE_SAONE)->getUuid();
            $mapTags['coteDOr'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_COTE_D_OR)->getUuid();
            $mapTags['nievre'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_NIEVRE)->getUuid();
            $mapTags['saoneEtLoire'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_SAONE_ET_LOIRE)->getUuid();
            $mapTags['jura'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_JURA)->getUuid();
            $mapTags['doubs'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_DOUBS)->getUuid();
            $mapTags['territoireDeBelfort'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_TERRITOIRE_DE_BELFORT)->getUuid();
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_C) {
            $mapTags['corseDuSud'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_CORSE_DU_SUD)->getUuid();
            $mapTags['hauteCorse'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_HAUTE_CORSE)->getUuid();
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_CVDL) {
            $mapTags['eureEtLoir'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_EURE_ET_LOIR)->getUuid();
            $mapTags['indreEtLoire'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_INDRE_ET_LOIRE)->getUuid();
            $mapTags['loiret'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_LOIRET)->getUuid();
            $mapTags['indre'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_INDRE)->getUuid();
            $mapTags['loirEtCher'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_LOIR_ET_CHER)->getUuid();
            $mapTags['cher'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_CHER)->getUuid();
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_IDF) {
            $mapTags['seineEtMarne'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_SEINE_ET_MARNE)->getUuid();
            $mapTags['essonne'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_ESSONNE)->getUuid();
            $mapTags['yvelines'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_YVELINES)->getUuid();
            $mapTags['valDOise'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_VAL_D_OISE)->getUuid();
            $mapTags['seineSaintDenis'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_SEINE_SAINT_DENIS)->getUuid();
            $mapTags['paris'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_PARIS)->getUuid();
            $mapTags['hautsDeSeine'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_HAUTS_DE_SEINE)->getUuid();
            $mapTags['valDeMarne'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_VAL_DE_MARNE)->getUuid();
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_LRMP) {
            $mapTags['lozere'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_LOZERE)->getUuid();
            $mapTags['gard'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_GARD)->getUuid();
            $mapTags['aude'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_AUDE)->getUuid();
            $mapTags['herault'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_HERAULT)->getUuid();
            $mapTags['pyreneesOrientales'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_PYRENEES_ORIENTALES)->getUuid();
            $mapTags['hautesPyrenees'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_HAUTES_PYRENEES)->getUuid();
            $mapTags['lot'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_LOT)->getUuid();
            $mapTags['ariege'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_ARIEGE)->getUuid();
            $mapTags['gers'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_GERS)->getUuid();
            $mapTags['hauteGaronne'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_HAUTE_GARONNE)->getUuid();
            $mapTags['tarnEtGaronne'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_TARN_ET_GARONNE)->getUuid();
            $mapTags['aveyron'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_AVEYRON)->getUuid();
            $mapTags['tarn'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_TARN)->getUuid();
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_N) {
            $mapTags['manche'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_MANCHE)->getUuid();
            $mapTags['calvados'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_CALVADOS)->getUuid();
            $mapTags['orne'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_ORNE)->getUuid();
            $mapTags['seineMaritime'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_SEINE_MARITIME)->getUuid();
            $mapTags['eure'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_EURE)->getUuid();
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_NPDCP) {
            $mapTags['oise'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_OISE)->getUuid();
            $mapTags['pasDeCalais'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_PAS_DE_CALAIS)->getUuid();
            $mapTags['nord'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_NORD)->getUuid();
            $mapTags['aisne'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_AISNE)->getUuid();
            $mapTags['somme'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_SOMME)->getUuid();
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_PACA) {
            $mapTags['bouchesDuRhone'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_BOUCHES_DU_RHONE)->getUuid();
            $mapTags['vaucluse'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_VAUCLUSE)->getUuid();
            $mapTags['var'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_VAR)->getUuid();
            $mapTags['alpesDeHauteProvence'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_ALPES_DE_HAUTE_PROVENCE)->getUuid();
            $mapTags['alpesMaritimes'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_ALPES_MARITIMES)->getUuid();
            $mapTags['hautesAlpes'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_HAUTES_ALPES)->getUuid();
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_PDLL) {
            $mapTags['vendee'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_VENDEE)->getUuid();
            $mapTags['loireAtlantique'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_LOIRE_ATLANTIQUE)->getUuid();
            $mapTags['maineEtLoire'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_MAINE_ET_LOIRE)->getUuid();
            $mapTags['sarthe'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_SARTHE)->getUuid();
            $mapTags['mayenne'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_MAYENNE)->getUuid();
        } else {
            throw new InconsistentDataException(sprintf('Region id %s does not match', $regionId));
        }

        return $mapTags;
    }

    /**
     * Get the most popular tags
     *
     * @param string $keywords
     * @return array[PTag]
     */
    public function getMostPopularTags($keywords = null)
    {
        $this->logger->info('*** getMostPopularTags');
        $this->logger->info('$keywords = '.print_r($keywords, true));

        $interval = null;
        if ($keywords && (in_array('lastDay', $keywords))) {
            $interval = 1;
        } elseif ($keywords && (in_array('lastWeek', $keywords))) {
            $interval = 7;
        } elseif ($keywords && (in_array('lastMonth', $keywords))) {
            $interval = 30;
        }

        $sql = $this->tagManager->createMostPopularTagsRawSql($interval);
        $tagIds = $this->hydrateTagIdRows($sql);

        $tags = [];
        $counter = 1;
        foreach ($tagIds as $tagId) {
            $tags[] = PTagQuery::create()->findPk($tagId);

            if ($counter == ListingConstants::DASHBOARD_TAG_LIMIT) {
                break;
            }
            $counter++;
        }

        return $tags;
    }
}
