<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\TagConstants;
use Politizr\Constant\ListingConstants;

use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PTag;

use Politizr\Model\PTagQuery;
use Politizr\Model\PDDTaggedTQuery;
use Politizr\Model\PDRTaggedTQuery;

use \PropelCollection;

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
    /*                                              PUBLIC FUNCTIONS                                            */
    /* ######################################################################################################## */
    
    /**
     * Depending of tag id, compute relative ids ie. ids of tags where parent_id is set to given id
     *
     * @param int $id Tag ID
     * @param boolean $withFranceChildren
     * @param boolean $withRegionChildren
     * @param boolean $withDepartmentChildren
     *
     * @return array
     */
    public function computeGeotagExtendedIds(
        $id,
        $withFranceChildren = true,
        $withRegionChildren = true,
        $withDepartmentChildren = true
    ) {
        // $this->logger->info('*** computeGeotagExtendedIds');

        $ids = array();
        $tag = PTagQuery::create()->findPk($id);

        if ($withFranceChildren
            && $tag->getPTTagTypeId() == TagConstants::TAG_TYPE_GEO
            && $id == TagConstants::TAG_GEO_FRANCE_ID) {
            // get region & departements & cities under france
            $countryIds = array();
            $regionIds = array();
            $departmentIds = array();
            $cityIds = array();

            $countryIds[] = TagConstants::TAG_GEO_FRANCE_ID;

            $regionIds = TagConstants::getGeoRegionIds();
            foreach ($regionIds as $regionId) {
                $departmentIds = array_merge($departmentIds, $this->getDepartmentsIds($regionId));
            }
            
            $cityIds = array();
            foreach ($departmentIds as $departmentId) {
                $cityIds = array_merge($cityIds, $this->getCityIds($departmentId));
            }

            $ids = array_merge($countryIds, $regionIds, $departmentIds, $cityIds);
        } elseif ($withRegionChildren
            && $tag->getPTTagTypeId() == TagConstants::TAG_TYPE_GEO
            && in_array($id, TagConstants::getGeoRegionIds())) {
            // get departements & cities under region
            $regionIds = array();
            $departmentIds = array();
            $cityIds = array();

            $regionIds[] = $id;

            $departmentIds = $this->getDepartmentsIds($id);
            
            $cityIds = array();
            foreach ($departmentIds as $departmentId) {
                $cityIds = array_merge($cityIds, $this->getCityIds($departmentId));
            }

            $ids = array_merge($regionIds, $departmentIds, $cityIds);
        } elseif ($withDepartmentChildren
            && $tag->getPTTagTypeId() == TagConstants::TAG_TYPE_GEO
            && in_array($id, TagConstants::getGeoDepartmentIds())) {
            // get cities under department
            $departmentIds[] = $id;
            
            $cityIds = $this->getCityIds($id);

            $ids = array_merge($departmentIds, $cityIds);
        } else {
            $ids[] = $id;
        }

        return $ids;
    }

    /**
     * Array of key indexed regions uuids
     *
     * @return array
     */
    public function getRegionUuids()
    {
        // $this->logger->info('*** getRegionUuids');

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
     * Array departments ids by region id
     *
     * @param $id
     * @return array
     */
    public function getDepartmentsIds($regionId)
    {
        // $this->logger->info('*** getDepartmentsIds');

        $departmentIds = array();

        if ($regionId == TagConstants::TAG_GEO_REGION_ID_ACAL) {
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_MARNE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_ARDENNES;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_AUBE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_HAUTE_MARNE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_BAS_RHIN;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_MEURTHE_ET_MOSELLE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_HAUT_RHIN;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_MEUSE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_MOSELLE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_VOSGES;
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_ALPC) {
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_PYRENEES_ATLANTIQUES;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_LANDES;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_GIRONDE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_DORDOGNE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_LOT_ET_GARONNE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_CHARENTE_MARITIME;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_CORREZE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_CREUSE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_HAUTE_VIENNE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_VIENNE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_CHARENTE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_DEUX_SEVRES;
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_ARA) {
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_ALLIER;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_CANTAL;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_HAUTE_LOIRE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_PUY_DE_DOME;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_AIN;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_ISERE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_HAUTE_SAVOIE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_LOIRE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_RHONE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_SAVOIE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_ARDECHE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_DROME;
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_B) {
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_COTES_D_ARMOR;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_MORBIHAN;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_FINISTERE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_ILLE_ET_VILAINE;
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_BFC) {
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_YONNE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_HAUTE_SAONE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_COTE_D_OR;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_NIEVRE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_SAONE_ET_LOIRE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_JURA;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_DOUBS;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_TERRITOIRE_DE_BELFORT;
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_C) {
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_CORSE_DU_SUD;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_HAUTE_CORSE;
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_CVDL) {
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_EURE_ET_LOIR;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_INDRE_ET_LOIRE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_LOIRET;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_INDRE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_LOIR_ET_CHER;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_CHER;
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_IDF) {
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_SEINE_ET_MARNE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_ESSONNE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_YVELINES;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_VAL_D_OISE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_SEINE_SAINT_DENIS;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_PARIS;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_HAUTS_DE_SEINE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_VAL_DE_MARNE;
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_LRMP) {
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_LOZERE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_GARD;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_AUDE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_HERAULT;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_PYRENEES_ORIENTALES;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_HAUTES_PYRENEES;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_LOT;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_ARIEGE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_GERS;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_HAUTE_GARONNE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_TARN_ET_GARONNE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_AVEYRON;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_TARN;
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_N) {
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_MANCHE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_CALVADOS;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_ORNE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_SEINE_MARITIME;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_EURE;
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_NPDCP) {
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_OISE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_PAS_DE_CALAIS;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_NORD;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_AISNE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_SOMME;
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_PACA) {
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_BOUCHES_DU_RHONE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_VAUCLUSE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_VAR;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_ALPES_DE_HAUTE_PROVENCE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_ALPES_MARITIMES;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_HAUTES_ALPES;
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_PDLL) {
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_VENDEE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_LOIRE_ATLANTIQUE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_MAINE_ET_LOIRE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_SARTHE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_MAYENNE;
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_FOM) {
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_GUADELOUPE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_MARTINIQUE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_GUYANE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_LA_REUNION;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_MAYOTTE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_POLYNESIE_FRANCAISE;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_SAINT_BARTHELEMY;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_SAINT_MARTIN;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_SAINT_PIERRE_ET_MIQUELON;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_WALLIS_ET_FUTUNA;
            $departmentIds[] = TagConstants::TAG_GEO_DEPARTMENT_ID_NOUVELLE_CALEDONIE;
        } else {
            throw new InconsistentDataException(sprintf('Region id %s does not match', $regionId));
        }

        return $departmentIds;
    }

    /**
     * Array of key indexed departments uuids
     *
     * @param $id
     * @return array
     */
    public function getDepartmentsUuids($regionId)
    {
        // $this->logger->info('*** getDepartmentsUuids');

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
        } elseif ($regionId == TagConstants::TAG_GEO_REGION_ID_FOM) {
            $mapTags['guadeloupe'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_GUADELOUPE)->getUuid();
            $mapTags['martinique'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_MARTINIQUE)->getUuid();
            $mapTags['guyane'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_GUYANE)->getUuid();
            $mapTags['laReunion'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_LA_REUNION)->getUuid();
            $mapTags['mayotte'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_MAYOTTE)->getUuid();
            $mapTags['polynesieFrancaise'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_POLYNESIE_FRANCAISE)->getUuid();
            $mapTags['saintBarthelemy'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_SAINT_BARTHELEMY)->getUuid();
            $mapTags['saintMartin'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_SAINT_MARTIN)->getUuid();
            $mapTags['saintPierreEtMiquelon'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_SAINT_PIERRE_ET_MIQUELON)->getUuid();
            $mapTags['wallisEtFutuna'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_WALLIS_ET_FUTUNA)->getUuid();
            $mapTags['nouvelleCaledonie'] = PTagQuery::create()->findPk(TagConstants::TAG_GEO_DEPARTMENT_ID_NOUVELLE_CALEDONIE)->getUuid();
        } else {
            throw new InconsistentDataException(sprintf('Region id %s does not match', $regionId));
        }

        return $mapTags;
    }

    /**
     * Get city ids of a department
     *
     * @param integer $departmentId
     * @return array
     */
    public function getCityIds($departmentId)
    {
        // $this->logger->info('*** getCityIds');
        // $this->logger->info('$departmentId = '.print_r($departmentId, true));

        $cityIds = PTagQuery::create()
            ->select('Id')
            ->filterByPTParentId($departmentId)
            ->find()
            ->toArray();

        return $cityIds;
    }

    /**
     * Get the most popular tags
     *
     * @param string $keywords
     * @param int $tagTypeId
     * @return array[PTag]
     */
    public function getMostPopularTags($keywords = null, $tagTypeId = null)
    {
        // $this->logger->info('*** getMostPopularTags');
        // $this->logger->info('$keywords = '.print_r($keywords, true));
        // $this->logger->info('$tagTypeId = '.print_r($tagTypeId, true));

        $interval = null;
        if ($keywords && (in_array(ListingConstants::FILTER_KEYWORD_LAST_DAY, $keywords))) {
            $interval = 1;
        } elseif ($keywords && (in_array(ListingConstants::FILTER_KEYWORD_LAST_WEEK, $keywords))) {
            $interval = 7;
        } elseif ($keywords && (in_array(ListingConstants::FILTER_KEYWORD_LAST_MONTH, $keywords))) {
            $interval = 30;
        }

        $tagIds = $this->tagManager->generateMostPopularTagIds($tagTypeId, $interval);

        // @todo 1 query with "ORDER BY FIELD(id, 3, 11, 7, 1)"
        // cf. http://stackoverflow.com/questions/34036279/propel-orm-order-by-field
        // $tags = PTagQuery::create()
        //     ->filterById($tagIds)
        //     ->orderBy($tagIds)
        //     ->find();

        $tags = [];
        $counter = 1;
        foreach ($tagIds as $tagId) {
            $tags[] = PTagQuery::create()->findPk($tagId);
        
            if ($counter == ListingConstants::LISTING_TOP_TAGS_LIMIT) {
                break;
            }
            $counter++;
        }

        return $tags;
    }

    /**
     * Update debate tags
     *
     * @param PDDebate $debate
     * @param PropelCollection(PTag) $tags
     * @param int $tagTypeId
     */
    public function updateDebateTags(PDDebate $debate, PropelCollection $tags, $tagTypeId) {
        // remove existing tags
        $existingTaggedTags = PDDTaggedTQuery::create()
            ->_if($tagTypeId)
                ->usePTagQuery()
                    ->filterByPTTagTypeId($tagTypeId)
                ->endUse()
            ->_endif()
            ->filterByPDDebateId($debate->getId())
            ->find();

        foreach ($existingTaggedTags as $taggedTag) {
            $taggedTag->delete();
        }

        // add new ones
        foreach ($tags as $tag) {
            $this->addDebateTag($debate, $tag);
        }

        return true;
    }

    /**
     * Add a tag to a debate (if not already exist)
     *
     * @param PDDebate $debate
     * @param PTag $tag
     */
    public function addDebateTag(PDDebate $debate, PTag $tag)
    {
        // associate tag to debate
        $pddTaggedT = PDDTaggedTQuery::create()
            ->filterByPDDebateId($debate->getId())
            ->filterByPTagId($tag->getId())
            ->findOne();

        if (!$pddTaggedT) {
            return $this->tagManager->createDebateTag($debate->getId(), $tag->getId());
        }

        return null;
    }


    /**
     * Update reaction tags
     *
     * @param PDReaction $reaction
     * @param PropelCollection(PTag) $tags
     * @param int $tagTypeId
     */
    public function updateReactionTags(PDReaction $reaction, PropelCollection $tags, $tagTypeId) {
        // remove existing tags
        $existingTaggedTags = PDRTaggedTQuery::create()
            ->_if($tagTypeId)
                ->usePTagQuery()
                    ->filterByPTTagTypeId($tagTypeId)
                ->endUse()
            ->_endif()
            ->filterByPDReactionId($reaction->getId())
            ->find();

        foreach ($existingTaggedTags as $taggedTag) {
            $taggedTag->delete();
        }

        // add new ones
        foreach ($tags as $tag) {
            $this->addReactionTag($reaction, $tag);
        }

        return true;
    }

    /**
     * Add a tag to a reaction (if not already exist)
     *
     * @param PDReaction $reaction
     * @param PTag $tag
     */
    public function addReactionTag(PDReaction $reaction, PTag $tag)
    {
        // associate tag to reaction
        $pddTaggedT = PDRTaggedTQuery::create()
            ->filterByPDReactionId($reaction->getId())
            ->filterByPTagId($tag->getId())
            ->findOne();

        if (!$pddTaggedT) {
            return $this->tagManager->createReactionTag($reaction->getId(), $tag->getId());
        }

        return null;
    }
}
