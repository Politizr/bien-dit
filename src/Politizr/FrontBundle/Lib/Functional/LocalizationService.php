<?php
namespace Politizr\FrontBundle\Lib\Functional;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\LocalizationConstants;

use Politizr\Model\PUser;

use Politizr\Model\PLCityQuery;
use Politizr\Model\PLDepartmentQuery;
use Politizr\Model\PLRegionQuery;
use Politizr\Model\PLCountryQuery;

/**
 * Functional service for localization management.
 *
 * @author Lionel Bouzonville
 */
class LocalizationService
{
    private $securityTokenStorage;
    private $securityAuthorizationChecker;
    
    private $localizationManager;
    
    private $logger;

    /**
     *
     * @param @security.token_storage
     * @param @security.authorization_checker
     * @param @politizr.manager.localization
     * @param @logger
     */
    public function __construct(
        $securityTokenStorage,
        $securityAuthorizationChecker,
        $localizationManager,
        $logger
    ) {
        $this->securityTokenStorage = $securityTokenStorage;
        $this->securityAuthorizationChecker =$securityAuthorizationChecker;

        $this->localizationManager = $localizationManager;

        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                              PUBLIC FUNCTIONS                                            */
    /* ######################################################################################################## */
    
    /**
     * Array of key indexed regions uuids
     *
     * @return array
     */
    public function getRegionUuids()
    {
        // $this->logger->info('*** getRegionUuids');

        $regionACAL = PLRegionQuery::create()->findPk(LocalizationConstants::REGION_ID_ACAL);
        $regionALPC = PLRegionQuery::create()->findPk(LocalizationConstants::REGION_ID_ALPC);
        $regionARA = PLRegionQuery::create()->findPk(LocalizationConstants::REGION_ID_ARA);
        $regionBFC = PLRegionQuery::create()->findPk(LocalizationConstants::REGION_ID_BFC);
        $regionB = PLRegionQuery::create()->findPk(LocalizationConstants::REGION_ID_B);
        $regionCVL = PLRegionQuery::create()->findPk(LocalizationConstants::REGION_ID_CVDL);
        $regionC = PLRegionQuery::create()->findPk(LocalizationConstants::REGION_ID_C);
        $regionIDF = PLRegionQuery::create()->findPk(LocalizationConstants::REGION_ID_IDF);
        $regionLRMP = PLRegionQuery::create()->findPk(LocalizationConstants::REGION_ID_LRMP);
        $regionNPDCP = PLRegionQuery::create()->findPk(LocalizationConstants::REGION_ID_NPDCP);
        $regionN = PLRegionQuery::create()->findPk(LocalizationConstants::REGION_ID_N);
        $regionPDLL = PLRegionQuery::create()->findPk(LocalizationConstants::REGION_ID_PDLL);
        $regionPACA = PLRegionQuery::create()->findPk(LocalizationConstants::REGION_ID_PACA);

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

        if ($regionId == LocalizationConstants::REGION_ID_ACAL) {
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_MARNE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_ARDENNES;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_AUBE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_HAUTE_MARNE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_BAS_RHIN;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_MEURTHE_ET_MOSELLE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_HAUT_RHIN;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_MEUSE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_MOSELLE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_VOSGES;
        } elseif ($regionId == LocalizationConstants::REGION_ID_ALPC) {
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_PYRENEES_ATLANTIQUES;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_LANDES;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_GIRONDE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_DORDOGNE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_LOT_ET_GARONNE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_CHARENTE_MARITIME;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_CORREZE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_CREUSE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_HAUTE_VIENNE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_VIENNE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_CHARENTE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_DEUX_SEVRES;
        } elseif ($regionId == LocalizationConstants::REGION_ID_ARA) {
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_ALLIER;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_CANTAL;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_HAUTE_LOIRE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_PUY_DE_DOME;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_AIN;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_ISERE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_HAUTE_SAVOIE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_LOIRE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_RHONE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_SAVOIE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_ARDECHE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_DROME;
        } elseif ($regionId == LocalizationConstants::REGION_ID_B) {
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_COTES_D_ARMOR;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_MORBIHAN;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_FINISTERE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_ILLE_ET_VILAINE;
        } elseif ($regionId == LocalizationConstants::REGION_ID_BFC) {
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_YONNE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_HAUTE_SAONE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_COTE_D_OR;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_NIEVRE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_SAONE_ET_LOIRE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_JURA;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_DOUBS;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_TERRITOIRE_DE_BELFORT;
        } elseif ($regionId == LocalizationConstants::REGION_ID_C) {
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_CORSE_DU_SUD;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_HAUTE_CORSE;
        } elseif ($regionId == LocalizationConstants::REGION_ID_CVDL) {
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_EURE_ET_LOIR;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_INDRE_ET_LOIRE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_LOIRET;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_INDRE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_LOIR_ET_CHER;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_CHER;
        } elseif ($regionId == LocalizationConstants::REGION_ID_IDF) {
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_SEINE_ET_MARNE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_ESSONNE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_YVELINES;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_VAL_D_OISE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_SEINE_SAINT_DENIS;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_PARIS;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_HAUTS_DE_SEINE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_VAL_DE_MARNE;
        } elseif ($regionId == LocalizationConstants::REGION_ID_LRMP) {
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_LOZERE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_GARD;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_AUDE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_HERAULT;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_PYRENEES_ORIENTALES;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_HAUTES_PYRENEES;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_LOT;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_ARIEGE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_GERS;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_HAUTE_GARONNE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_TARN_ET_GARONNE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_AVEYRON;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_TARN;
        } elseif ($regionId == LocalizationConstants::REGION_ID_N) {
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_MANCHE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_CALVADOS;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_ORNE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_SEINE_MARITIME;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_EURE;
        } elseif ($regionId == LocalizationConstants::REGION_ID_NPDCP) {
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_OISE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_PAS_DE_CALAIS;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_NORD;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_AISNE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_SOMME;
        } elseif ($regionId == LocalizationConstants::REGION_ID_PACA) {
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_BOUCHES_DU_RHONE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_VAUCLUSE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_VAR;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_ALPES_DE_HAUTE_PROVENCE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_ALPES_MARITIMES;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_HAUTES_ALPES;
        } elseif ($regionId == LocalizationConstants::REGION_ID_PDLL) {
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_VENDEE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_LOIRE_ATLANTIQUE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_MAINE_ET_LOIRE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_SARTHE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_MAYENNE;
        } elseif ($regionId == LocalizationConstants::REGION_ID_FOM) {
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_GUADELOUPE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_MARTINIQUE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_GUYANE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_LA_REUNION;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_MAYOTTE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_POLYNESIE_FRANCAISE;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_SAINT_BARTHELEMY;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_SAINT_MARTIN;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_SAINT_PIERRE_ET_MIQUELON;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_WALLIS_ET_FUTUNA;
            $departmentIds[] = LocalizationConstants::DEPARTMENT_ID_NOUVELLE_CALEDONIE;
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

        $mapUuids = array();

        if ($regionId == LocalizationConstants::REGION_ID_ACAL) {
            $mapUuids['marne'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_MARNE)->getUuid();
            $mapUuids['ardennes'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_ARDENNES)->getUuid();
            $mapUuids['aube'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_AUBE)->getUuid();
            $mapUuids['hauteMarne'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_HAUTE_MARNE)->getUuid();
            $mapUuids['basRhin'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_BAS_RHIN)->getUuid();
            $mapUuids['meurtheEtMoselle'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_MEURTHE_ET_MOSELLE)->getUuid();
            $mapUuids['hautRhin'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_HAUT_RHIN)->getUuid();
            $mapUuids['meuse'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_MEUSE)->getUuid();
            $mapUuids['moselle'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_MOSELLE)->getUuid();
            $mapUuids['vosges'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_VOSGES)->getUuid();
        } elseif ($regionId == LocalizationConstants::REGION_ID_ALPC) {
            $mapUuids['pyreneesAtlantiques'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_PYRENEES_ATLANTIQUES)->getUuid();
            $mapUuids['landes'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_LANDES)->getUuid();
            $mapUuids['gironde'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_GIRONDE)->getUuid();
            $mapUuids['dordogne'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_DORDOGNE)->getUuid();
            $mapUuids['lotEtGaronne'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_LOT_ET_GARONNE)->getUuid();
            $mapUuids['charenteMaritime'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_CHARENTE_MARITIME)->getUuid();
            $mapUuids['correze'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_CORREZE)->getUuid();
            $mapUuids['creuse'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_CREUSE)->getUuid();
            $mapUuids['hauteVienne'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_HAUTE_VIENNE)->getUuid();
            $mapUuids['vienne'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_VIENNE)->getUuid();
            $mapUuids['charente'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_CHARENTE)->getUuid();
            $mapUuids['deuxSevres'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_DEUX_SEVRES)->getUuid();
        } elseif ($regionId == LocalizationConstants::REGION_ID_ARA) {
            $mapUuids['allier'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_ALLIER)->getUuid();
            $mapUuids['cantal'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_CANTAL)->getUuid();
            $mapUuids['hauteLoire'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_HAUTE_LOIRE)->getUuid();
            $mapUuids['puyDeDome'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_PUY_DE_DOME)->getUuid();
            $mapUuids['ain'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_AIN)->getUuid();
            $mapUuids['isere'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_ISERE)->getUuid();
            $mapUuids['hauteSavoie'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_HAUTE_SAVOIE)->getUuid();
            $mapUuids['loire'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_LOIRE)->getUuid();
            $mapUuids['rhone'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_RHONE)->getUuid();
            $mapUuids['savoie'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_SAVOIE)->getUuid();
            $mapUuids['ardeche'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_ARDECHE)->getUuid();
            $mapUuids['drome'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_DROME)->getUuid();
        } elseif ($regionId == LocalizationConstants::REGION_ID_B) {
            $mapUuids['cotesDArmor'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_COTES_D_ARMOR)->getUuid();
            $mapUuids['morbihan'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_MORBIHAN)->getUuid();
            $mapUuids['finistere'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_FINISTERE)->getUuid();
            $mapUuids['illeEtVilaine'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_ILLE_ET_VILAINE)->getUuid();
        } elseif ($regionId == LocalizationConstants::REGION_ID_BFC) {
            $mapUuids['yonne'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_YONNE)->getUuid();
            $mapUuids['hauteSaone'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_HAUTE_SAONE)->getUuid();
            $mapUuids['coteDOr'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_COTE_D_OR)->getUuid();
            $mapUuids['nievre'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_NIEVRE)->getUuid();
            $mapUuids['saoneEtLoire'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_SAONE_ET_LOIRE)->getUuid();
            $mapUuids['jura'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_JURA)->getUuid();
            $mapUuids['doubs'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_DOUBS)->getUuid();
            $mapUuids['territoireDeBelfort'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_TERRITOIRE_DE_BELFORT)->getUuid();
        } elseif ($regionId == LocalizationConstants::REGION_ID_C) {
            $mapUuids['corseDuSud'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_CORSE_DU_SUD)->getUuid();
            $mapUuids['hauteCorse'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_HAUTE_CORSE)->getUuid();
        } elseif ($regionId == LocalizationConstants::REGION_ID_CVDL) {
            $mapUuids['eureEtLoir'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_EURE_ET_LOIR)->getUuid();
            $mapUuids['indreEtLoire'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_INDRE_ET_LOIRE)->getUuid();
            $mapUuids['loiret'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_LOIRET)->getUuid();
            $mapUuids['indre'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_INDRE)->getUuid();
            $mapUuids['loirEtCher'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_LOIR_ET_CHER)->getUuid();
            $mapUuids['cher'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_CHER)->getUuid();
        } elseif ($regionId == LocalizationConstants::REGION_ID_IDF) {
            $mapUuids['seineEtMarne'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_SEINE_ET_MARNE)->getUuid();
            $mapUuids['essonne'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_ESSONNE)->getUuid();
            $mapUuids['yvelines'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_YVELINES)->getUuid();
            $mapUuids['valDOise'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_VAL_D_OISE)->getUuid();
            $mapUuids['seineSaintDenis'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_SEINE_SAINT_DENIS)->getUuid();
            $mapUuids['paris'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_PARIS)->getUuid();
            $mapUuids['hautsDeSeine'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_HAUTS_DE_SEINE)->getUuid();
            $mapUuids['valDeMarne'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_VAL_DE_MARNE)->getUuid();
        } elseif ($regionId == LocalizationConstants::REGION_ID_LRMP) {
            $mapUuids['lozere'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_LOZERE)->getUuid();
            $mapUuids['gard'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_GARD)->getUuid();
            $mapUuids['aude'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_AUDE)->getUuid();
            $mapUuids['herault'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_HERAULT)->getUuid();
            $mapUuids['pyreneesOrientales'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_PYRENEES_ORIENTALES)->getUuid();
            $mapUuids['hautesPyrenees'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_HAUTES_PYRENEES)->getUuid();
            $mapUuids['lot'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_LOT)->getUuid();
            $mapUuids['ariege'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_ARIEGE)->getUuid();
            $mapUuids['gers'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_GERS)->getUuid();
            $mapUuids['hauteGaronne'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_HAUTE_GARONNE)->getUuid();
            $mapUuids['tarnEtGaronne'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_TARN_ET_GARONNE)->getUuid();
            $mapUuids['aveyron'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_AVEYRON)->getUuid();
            $mapUuids['tarn'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_TARN)->getUuid();
        } elseif ($regionId == LocalizationConstants::REGION_ID_N) {
            $mapUuids['manche'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_MANCHE)->getUuid();
            $mapUuids['calvados'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_CALVADOS)->getUuid();
            $mapUuids['orne'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_ORNE)->getUuid();
            $mapUuids['seineMaritime'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_SEINE_MARITIME)->getUuid();
            $mapUuids['eure'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_EURE)->getUuid();
        } elseif ($regionId == LocalizationConstants::REGION_ID_NPDCP) {
            $mapUuids['oise'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_OISE)->getUuid();
            $mapUuids['pasDeCalais'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_PAS_DE_CALAIS)->getUuid();
            $mapUuids['nord'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_NORD)->getUuid();
            $mapUuids['aisne'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_AISNE)->getUuid();
            $mapUuids['somme'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_SOMME)->getUuid();
        } elseif ($regionId == LocalizationConstants::REGION_ID_PACA) {
            $mapUuids['bouchesDuRhone'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_BOUCHES_DU_RHONE)->getUuid();
            $mapUuids['vaucluse'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_VAUCLUSE)->getUuid();
            $mapUuids['var'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_VAR)->getUuid();
            $mapUuids['alpesDeHauteProvence'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_ALPES_DE_HAUTE_PROVENCE)->getUuid();
            $mapUuids['alpesMaritimes'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_ALPES_MARITIMES)->getUuid();
            $mapUuids['hautesAlpes'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_HAUTES_ALPES)->getUuid();
        } elseif ($regionId == LocalizationConstants::REGION_ID_PDLL) {
            $mapUuids['vendee'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_VENDEE)->getUuid();
            $mapUuids['loireAtlantique'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_LOIRE_ATLANTIQUE)->getUuid();
            $mapUuids['maineEtLoire'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_MAINE_ET_LOIRE)->getUuid();
            $mapUuids['sarthe'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_SARTHE)->getUuid();
            $mapUuids['mayenne'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_MAYENNE)->getUuid();
        } elseif ($regionId == LocalizationConstants::REGION_ID_FOM) {
            $mapUuids['guadeloupe'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_GUADELOUPE)->getUuid();
            $mapUuids['martinique'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_MARTINIQUE)->getUuid();
            $mapUuids['guyane'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_GUYANE)->getUuid();
            $mapUuids['laReunion'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_LA_REUNION)->getUuid();
            $mapUuids['mayotte'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_MAYOTTE)->getUuid();
            $mapUuids['polynesieFrancaise'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_POLYNESIE_FRANCAISE)->getUuid();
            $mapUuids['saintBarthelemy'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_SAINT_BARTHELEMY)->getUuid();
            $mapUuids['saintMartin'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_SAINT_MARTIN)->getUuid();
            $mapUuids['saintPierreEtMiquelon'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_SAINT_PIERRE_ET_MIQUELON)->getUuid();
            $mapUuids['wallisEtFutuna'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_WALLIS_ET_FUTUNA)->getUuid();
            $mapUuids['nouvelleCaledonie'] = PLDepartmentQuery::create()->findPk(LocalizationConstants::DEPARTMENT_ID_NOUVELLE_CALEDONIE)->getUuid();
        } else {
            throw new InconsistentDataException(sprintf('Region id %s does not match', $regionId));
        }

        return $mapUuids;
    }


    /**
     * Get region id from uuid
     *
     * @param string $regionUuid
     * @return int
     */
    public function getRegionIdFromRegionUuid($regionUuid)
    {
        // $this->logger->info('*** getRegionIdFromRegionUuid');
        // $this->logger->info('$regionUuid = '.print_r($regionUuid, true));

        $region = PLRegionQuery::create()->filterByUuid($regionUuid)->findOne();

        if ($region) {
            return $region->getId();
        }

        return null;
    }

    /**
     * Get department id from uuid
     *
     * @param string $departmentUuid
     * @return int
     */
    public function getDepartmentIdFromDepartmentUuid($departmentUuid)
    {
        // $this->logger->info('*** getDepartmentIdFromDepartmentUuid');
        // $this->logger->info('$departmentUuid = '.print_r($departmentUuid, true));

        $department = PLDepartmentQuery::create()->filterByUuid($departmentUuid)->findOne();

        if ($department) {
            return $department->getId();
        }

        return null;
    }

    /**
     * Get city id from uuid
     *
     * @param string $cityUuid
     * @return int
     */
    public function getCityIdFromCityUuid($cityUuid)
    {
        // $this->logger->info('*** getCityIdFromCityUuid');
        // $this->logger->info('$cityUuid = '.print_r($cityUuid, true));

        $city = PLCityQuery::create()->filterByUuid($cityUuid)->findOne();

        if ($city) {
            return $city->getId();
        }

        return null;
    }

    /**
     * Get city ids of a department
     *
     * @param integer $departmentId
     * @return array
     */
    public function getCityIdsFromDepartmentId($departmentId)
    {
        // $this->logger->info('*** getCityIdsFromDepartmentId');
        // $this->logger->info('$departmentId = '.print_r($departmentId, true));

        $cityIds = PLCityQuery::create()
            ->select('Id')
            ->filterByPLDepartmentId($departmentId)
            ->find()
            ->toArray();

        return $cityIds;
    }

    /**
     * Get city ids of a region
     *
     * @param integer $regionId
     * @return array
     */
    public function getCityIdsFromRegionId($regionId)
    {
        // $this->logger->info('*** getCityIdsFromRegionId');
        // $this->logger->info('$regionId = '.print_r($regionId, true));

        $cityIds = PLCityQuery::create()
            ->select('Id')
            ->usePLDepartmentQuery()
                ->filterByPLRegionId($regionId)
            ->endUse()
            ->find()
            ->toArray();

        return $cityIds;
    }

    /**
     * Get department ids of a region
     *
     * @param integer $regionId
     * @return array
     */
    public function getDepartmentIdsFromRegionId($regionId)
    {
        // $this->logger->info('*** getDepartmentIdsFromRegionId');
        // $this->logger->info('$regionId = '.print_r($regionId, true));

        $departmentIds = PLDepartmentQuery::create()
            ->select('Id')
            ->filterByPLRegionId($regionId)
            ->find()
            ->toArray();

        return $departmentIds;
    }

    /**
     * Compute array of city ids included in geo tag ids
     *
     * @param string $geoUuid
     * @param string $type
     * @return array
     */
    public function computeCityIdsFromGeoUuid($geoUuid, $type)
    {
        $cityIds = array();

        if ($type == LocalizationConstants::TYPE_REGION) {
            $region = PLRegionQuery::create()->filterByUuid($geoUuid)->findOne();
            if ($region) {
                $cityIds = $this->getCityIdsFromRegionId($region->getId());    
            }
        } elseif ($type == LocalizationConstants::TYPE_DEPARTMENT) {
            $department = PLDepartmentQuery::create()->filterByUuid($geoUuid)->findOne();
            if ($department) {
                $cityIds = $this->getCityIdsFromDepartmentId($department->getId());    
            }
        }

        return $cityIds;
    }

    /**
     * Compute array of department ids included in geo tag ids
     *
     * @param string $geoUuid
     * @param string $type
     * @return array
     */
    public function computeDepartmentIdsFromGeoUuid($geoUuid, $type)
    {
        $departmentIds = array();

        if ($type == LocalizationConstants::TYPE_REGION) {
            $region = PLRegionQuery::create()->filterByUuid($geoUuid)->findOne();
            if ($region) {
                $departmentIds = $this->getDepartmentIdsFromRegionId($region->getId());    
            }
        }

        return $departmentIds;
    }

    /**
     * Fill variables for city/dep/region/country with ids depending of geo uuid & type
     * Rules:
     * - country > only country
     * - region > region + departments of region + cities of region
     * - department > departement + cities of departement
     * - city > city
     *
     * @param integer $geoUuid
     * @param string $type
     * @param array|int $cityIds
     * @param array|int $departmentIds
     * @param int $regionId
     * @param int $countryId
     * @return array
     */
    public function fillExtendedChildrenGeoIdsFromGeoUuid($geoUuid, $type, &$cityIds, &$departmentIds, &$regionId, &$countryId)
    {
        if ($type == LocalizationConstants::TYPE_COUNTRY) {
            $countryId = LocalizationConstants::FRANCE_ID;
        } elseif ($type == LocalizationConstants::TYPE_REGION) {
            $regionId = $this->getRegionIdFromRegionUuid($geoUuid);
            $cityIds = $this->computeCityIdsFromGeoUuid($geoUuid, $type);
            $departmentIds = $this->computeDepartmentIdsFromGeoUuid($geoUuid, $type);
        } elseif ($type == LocalizationConstants::TYPE_DEPARTMENT) {
            $departmentIds = $this->getDepartmentIdFromDepartmentUuid($geoUuid);
            $cityIds = $this->computeCityIdsFromGeoUuid($geoUuid, $type);
        } elseif ($type == LocalizationConstants::TYPE_CITY) {
            $cityIds = $this->getCityIdFromCityUuid($geoUuid);
        }
    }

    /**
     * Return object implementing PLocalization from uuid & type
     *
     * @param string $geoUuid
     * @param string $type
     * @return PLocalization
     */
    public function getPLocalizationFromGeoUuid($geoUuid, $type)
    {
        if ($type == LocalizationConstants::TYPE_COUNTRY) {
            $localization = PLCountryQuery::create()->filterByUuid($geoUuid)->findOne();
            return $localization;
        } elseif ($type == LocalizationConstants::TYPE_REGION) {
            $localization = PLRegionQuery::create()->filterByUuid($geoUuid)->findOne();
            return $localization;
        } elseif ($type == LocalizationConstants::TYPE_DEPARTMENT) {
            $localization = PLDepartmentQuery::create()->filterByUuid($geoUuid)->findOne();
            return $localization;
        } elseif ($type == LocalizationConstants::TYPE_CITY) {
            $localization = PLCityQuery::create()->filterByUuid($geoUuid)->findOne();
            return $localization;
        }

        return null;
    }

    /**
     * Return object implementing PLocalization from slug
     *
     * @param string $slug
     * @return PLocalization
     */
    public function getPLocalizationFromSlug($slug)
    {
        if ($slug) {
            $localization = PLCountryQuery::create()->filterBySlug($slug)->findOne();

            if (!$localization) {
                $localization = PLRegionQuery::create()->filterBySlug($slug)->findOne();
            
                if (!$localization) {
                    $localization = PLDepartmentQuery::create()->filterBySlug($slug)->findOne();

                    if (!$localization) {
                        $localization = PLCityQuery::create()->filterBySlug($slug)->findOne();

                        if (!$localization) {
                            return null;
                        }
                    }
                }
            }
        } else {
            return null;
        }

        return $localization;
    }
}