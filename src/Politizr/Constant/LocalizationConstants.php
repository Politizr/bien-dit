<?php

namespace Politizr\Constant;

/**
 * Localization constants
 *
 * @author Lionel Bouzonville
 */
class LocalizationConstants
{
    // ************************************************************************************ //
    //                                LOCALIZATION OBJECT TYPE
    // ************************************************************************************ //
    const TYPE_COUNTRY = 'country';
    const TYPE_REGION = 'region';
    const TYPE_DEPARTMENT = 'department';
    const TYPE_CITY = 'city';

    // ************************************************************************************ //
    //                                GEO OBJECT IDS
    // ************************************************************************************ //

    // p_l_country
    const FRANCE_ID = 1;

    // p_l_region
    const REGION_ID_ACAL = 1;
    const REGION_ID_ALPC = 2;
    const REGION_ID_ARA = 3;
    const REGION_ID_BFC = 4;
    const REGION_ID_B = 5;
    const REGION_ID_CVDL = 6;
    const REGION_ID_C = 7;
    const REGION_ID_IDF = 8;
    const REGION_ID_LRMP = 9;
    const REGION_ID_NPDCP = 10;
    const REGION_ID_N = 11;
    const REGION_ID_PDLL = 12;
    const REGION_ID_PACA = 13;
    const REGION_ID_FOM = 14;

    // p_l_department
    const DEPARTMENT_ID_AIN = 1;
    const DEPARTMENT_ID_AISNE = 2;
    const DEPARTMENT_ID_ALLIER = 3;
    const DEPARTMENT_ID_ALPES_DE_HAUTE_PROVENCE = 4;
    const DEPARTMENT_ID_HAUTES_ALPES = 5;
    const DEPARTMENT_ID_ALPES_MARITIMES = 6;
    const DEPARTMENT_ID_ARDECHE = 7;
    const DEPARTMENT_ID_ARDENNES = 8;
    const DEPARTMENT_ID_ARIEGE = 9;
    const DEPARTMENT_ID_AUBE = 10;
    const DEPARTMENT_ID_AUDE = 11;
    const DEPARTMENT_ID_AVEYRON = 12;
    const DEPARTMENT_ID_BOUCHES_DU_RHONE = 13;
    const DEPARTMENT_ID_CALVADOS = 14;
    const DEPARTMENT_ID_CANTAL = 15;
    const DEPARTMENT_ID_CHARENTE = 16;
    const DEPARTMENT_ID_CHARENTE_MARITIME = 17;
    const DEPARTMENT_ID_CHER = 18;
    const DEPARTMENT_ID_CORREZE = 19;
    const DEPARTMENT_ID_CORSE_DU_SUD = 20;
    const DEPARTMENT_ID_HAUTE_CORSE = 21;
    const DEPARTMENT_ID_COTE_D_OR = 22;
    const DEPARTMENT_ID_COTES_D_ARMOR = 23;
    const DEPARTMENT_ID_CREUSE = 24;
    const DEPARTMENT_ID_DORDOGNE = 25;
    const DEPARTMENT_ID_DOUBS = 26;
    const DEPARTMENT_ID_DROME = 27;
    const DEPARTMENT_ID_EURE = 28;
    const DEPARTMENT_ID_EURE_ET_LOIR = 29;
    const DEPARTMENT_ID_FINISTERE = 30;
    const DEPARTMENT_ID_GARD = 31;
    const DEPARTMENT_ID_HAUTE_GARONNE = 32;
    const DEPARTMENT_ID_GERS = 33;
    const DEPARTMENT_ID_GIRONDE = 34;
    const DEPARTMENT_ID_HERAULT = 35;
    const DEPARTMENT_ID_ILLE_ET_VILAINE = 36;
    const DEPARTMENT_ID_INDRE = 37;
    const DEPARTMENT_ID_INDRE_ET_LOIRE = 38;
    const DEPARTMENT_ID_ISERE = 39;
    const DEPARTMENT_ID_JURA = 40;
    const DEPARTMENT_ID_LANDES = 41;
    const DEPARTMENT_ID_LOIR_ET_CHER = 42;
    const DEPARTMENT_ID_LOIRE = 43;
    const DEPARTMENT_ID_HAUTE_LOIRE = 44;
    const DEPARTMENT_ID_LOIRE_ATLANTIQUE = 45;
    const DEPARTMENT_ID_LOIRET = 46;
    const DEPARTMENT_ID_LOT = 47;
    const DEPARTMENT_ID_LOT_ET_GARONNE = 48;
    const DEPARTMENT_ID_LOZERE = 49;
    const DEPARTMENT_ID_MAINE_ET_LOIRE = 50;
    const DEPARTMENT_ID_MANCHE = 51;
    const DEPARTMENT_ID_MARNE = 52;
    const DEPARTMENT_ID_HAUTE_MARNE = 53;
    const DEPARTMENT_ID_MAYENNE = 54;
    const DEPARTMENT_ID_MEURTHE_ET_MOSELLE = 55;
    const DEPARTMENT_ID_MEUSE = 56;
    const DEPARTMENT_ID_MORBIHAN = 57;
    const DEPARTMENT_ID_MOSELLE = 58;
    const DEPARTMENT_ID_NIEVRE = 59;
    const DEPARTMENT_ID_NORD = 60;
    const DEPARTMENT_ID_OISE = 61;
    const DEPARTMENT_ID_ORNE = 62;
    const DEPARTMENT_ID_PAS_DE_CALAIS = 63;
    const DEPARTMENT_ID_PUY_DE_DOME = 64;
    const DEPARTMENT_ID_PYRENEES_ATLANTIQUES = 65;
    const DEPARTMENT_ID_HAUTES_PYRENEES = 66;
    const DEPARTMENT_ID_PYRENEES_ORIENTALES = 67;
    const DEPARTMENT_ID_BAS_RHIN = 68;
    const DEPARTMENT_ID_HAUT_RHIN = 69;
    const DEPARTMENT_ID_RHONE = 70;
    const DEPARTMENT_ID_HAUTE_SAONE = 71;
    const DEPARTMENT_ID_SAONE_ET_LOIRE = 72;
    const DEPARTMENT_ID_SARTHE = 73;
    const DEPARTMENT_ID_SAVOIE = 74;
    const DEPARTMENT_ID_HAUTE_SAVOIE = 75;
    const DEPARTMENT_ID_PARIS = 76;
    const DEPARTMENT_ID_SEINE_MARITIME = 77;
    const DEPARTMENT_ID_SEINE_ET_MARNE = 78;
    const DEPARTMENT_ID_YVELINES = 79;
    const DEPARTMENT_ID_DEUX_SEVRES = 80;
    const DEPARTMENT_ID_SOMME = 81;
    const DEPARTMENT_ID_TARN = 82;
    const DEPARTMENT_ID_TARN_ET_GARONNE = 83;
    const DEPARTMENT_ID_VAR = 84;
    const DEPARTMENT_ID_VAUCLUSE = 85;
    const DEPARTMENT_ID_VENDEE = 86;
    const DEPARTMENT_ID_VIENNE = 87;
    const DEPARTMENT_ID_HAUTE_VIENNE = 88;
    const DEPARTMENT_ID_VOSGES = 89;
    const DEPARTMENT_ID_YONNE = 90;
    const DEPARTMENT_ID_TERRITOIRE_DE_BELFORT = 91;
    const DEPARTMENT_ID_ESSONNE = 92;
    const DEPARTMENT_ID_HAUTS_DE_SEINE = 93;
    const DEPARTMENT_ID_SEINE_SAINT_DENIS = 94;
    const DEPARTMENT_ID_VAL_DE_MARNE = 95;
    const DEPARTMENT_ID_VAL_D_OISE = 96;

    const DEPARTMENT_ID_GUADELOUPE = 97;
    const DEPARTMENT_ID_MARTINIQUE = 98;
    const DEPARTMENT_ID_GUYANE = 99;
    const DEPARTMENT_ID_LA_REUNION = 100;
    const DEPARTMENT_ID_MAYOTTE = 101;
    const DEPARTMENT_ID_POLYNESIE_FRANCAISE = 102;
    const DEPARTMENT_ID_SAINT_BARTHELEMY = 103;
    const DEPARTMENT_ID_SAINT_MARTIN = 104;
    const DEPARTMENT_ID_SAINT_PIERRE_ET_MIQUELON = 105;
    const DEPARTMENT_ID_WALLIS_ET_FUTUNA = 106;
    const DEPARTMENT_ID_NOUVELLE_CALEDONIE = 107;


    /**
     * Return array of region ids
     *
     * @return array
     */
    public static function getGeoRegionIds()
    {
        return [
            LocalizationConstants::REGION_ID_ACAL,
            LocalizationConstants::REGION_ID_ALPC,
            LocalizationConstants::REGION_ID_ARA,
            LocalizationConstants::REGION_ID_BFC,
            LocalizationConstants::REGION_ID_B,
            LocalizationConstants::REGION_ID_CVDL,
            LocalizationConstants::REGION_ID_C,
            LocalizationConstants::REGION_ID_IDF,
            LocalizationConstants::REGION_ID_LRMP,
            LocalizationConstants::REGION_ID_NPDCP,
            LocalizationConstants::REGION_ID_N,
            LocalizationConstants::REGION_ID_PDLL,
            LocalizationConstants::REGION_ID_PACA,
            LocalizationConstants::REGION_ID_FOM
        ];
    }

    /**
     * Return array of metropole departments ids
     *
     * @return array
     */
    public static function getGeoDepartmentMetroIds()
    {
        return [
            LocalizationConstants::DEPARTMENT_ID_AIN,
            LocalizationConstants::DEPARTMENT_ID_AISNE,
            LocalizationConstants::DEPARTMENT_ID_ALLIER,
            LocalizationConstants::DEPARTMENT_ID_ALPES_DE_HAUTE_PROVENCE,
            LocalizationConstants::DEPARTMENT_ID_HAUTES_ALPES,
            LocalizationConstants::DEPARTMENT_ID_ALPES_MARITIMES,
            LocalizationConstants::DEPARTMENT_ID_ARDECHE,
            LocalizationConstants::DEPARTMENT_ID_ARDENNES,
            LocalizationConstants::DEPARTMENT_ID_ARIEGE,
            LocalizationConstants::DEPARTMENT_ID_AUBE,
            LocalizationConstants::DEPARTMENT_ID_AUDE,
            LocalizationConstants::DEPARTMENT_ID_AVEYRON,
            LocalizationConstants::DEPARTMENT_ID_BOUCHES_DU_RHONE,
            LocalizationConstants::DEPARTMENT_ID_CALVADOS,
            LocalizationConstants::DEPARTMENT_ID_CANTAL,
            LocalizationConstants::DEPARTMENT_ID_CHARENTE,
            LocalizationConstants::DEPARTMENT_ID_CHARENTE_MARITIME,
            LocalizationConstants::DEPARTMENT_ID_CHER,
            LocalizationConstants::DEPARTMENT_ID_CORREZE,
            LocalizationConstants::DEPARTMENT_ID_CORSE_DU_SUD,
            LocalizationConstants::DEPARTMENT_ID_HAUTE_CORSE,
            LocalizationConstants::DEPARTMENT_ID_COTE_D_OR,
            LocalizationConstants::DEPARTMENT_ID_COTES_D_ARMOR,
            LocalizationConstants::DEPARTMENT_ID_CREUSE,
            LocalizationConstants::DEPARTMENT_ID_DORDOGNE,
            LocalizationConstants::DEPARTMENT_ID_DOUBS,
            LocalizationConstants::DEPARTMENT_ID_DROME,
            LocalizationConstants::DEPARTMENT_ID_EURE,
            LocalizationConstants::DEPARTMENT_ID_EURE_ET_LOIR,
            LocalizationConstants::DEPARTMENT_ID_FINISTERE,
            LocalizationConstants::DEPARTMENT_ID_GARD,
            LocalizationConstants::DEPARTMENT_ID_HAUTE_GARONNE,
            LocalizationConstants::DEPARTMENT_ID_GERS,
            LocalizationConstants::DEPARTMENT_ID_GIRONDE,
            LocalizationConstants::DEPARTMENT_ID_HERAULT,
            LocalizationConstants::DEPARTMENT_ID_ILLE_ET_VILAINE,
            LocalizationConstants::DEPARTMENT_ID_INDRE,
            LocalizationConstants::DEPARTMENT_ID_INDRE_ET_LOIRE,
            LocalizationConstants::DEPARTMENT_ID_ISERE,
            LocalizationConstants::DEPARTMENT_ID_JURA,
            LocalizationConstants::DEPARTMENT_ID_LANDES,
            LocalizationConstants::DEPARTMENT_ID_LOIR_ET_CHER,
            LocalizationConstants::DEPARTMENT_ID_LOIRE,
            LocalizationConstants::DEPARTMENT_ID_HAUTE_LOIRE,
            LocalizationConstants::DEPARTMENT_ID_LOIRE_ATLANTIQUE,
            LocalizationConstants::DEPARTMENT_ID_LOIRET,
            LocalizationConstants::DEPARTMENT_ID_LOT,
            LocalizationConstants::DEPARTMENT_ID_LOT_ET_GARONNE,
            LocalizationConstants::DEPARTMENT_ID_LOZERE,
            LocalizationConstants::DEPARTMENT_ID_MAINE_ET_LOIRE,
            LocalizationConstants::DEPARTMENT_ID_MANCHE,
            LocalizationConstants::DEPARTMENT_ID_MARNE,
            LocalizationConstants::DEPARTMENT_ID_HAUTE_MARNE,
            LocalizationConstants::DEPARTMENT_ID_MAYENNE,
            LocalizationConstants::DEPARTMENT_ID_MEURTHE_ET_MOSELLE,
            LocalizationConstants::DEPARTMENT_ID_MEUSE,
            LocalizationConstants::DEPARTMENT_ID_MORBIHAN,
            LocalizationConstants::DEPARTMENT_ID_MOSELLE,
            LocalizationConstants::DEPARTMENT_ID_NIEVRE,
            LocalizationConstants::DEPARTMENT_ID_NORD,
            LocalizationConstants::DEPARTMENT_ID_OISE,
            LocalizationConstants::DEPARTMENT_ID_ORNE,
            LocalizationConstants::DEPARTMENT_ID_PAS_DE_CALAIS,
            LocalizationConstants::DEPARTMENT_ID_PUY_DE_DOME,
            LocalizationConstants::DEPARTMENT_ID_PYRENEES_ATLANTIQUES,
            LocalizationConstants::DEPARTMENT_ID_HAUTES_PYRENEES,
            LocalizationConstants::DEPARTMENT_ID_PYRENEES_ORIENTALES,
            LocalizationConstants::DEPARTMENT_ID_BAS_RHIN,
            LocalizationConstants::DEPARTMENT_ID_HAUT_RHIN,
            LocalizationConstants::DEPARTMENT_ID_RHONE,
            LocalizationConstants::DEPARTMENT_ID_HAUTE_SAONE,
            LocalizationConstants::DEPARTMENT_ID_SAONE_ET_LOIRE,
            LocalizationConstants::DEPARTMENT_ID_SARTHE,
            LocalizationConstants::DEPARTMENT_ID_SAVOIE,
            LocalizationConstants::DEPARTMENT_ID_HAUTE_SAVOIE,
            LocalizationConstants::DEPARTMENT_ID_PARIS,
            LocalizationConstants::DEPARTMENT_ID_SEINE_MARITIME,
            LocalizationConstants::DEPARTMENT_ID_SEINE_ET_MARNE ,
            LocalizationConstants::DEPARTMENT_ID_YVELINES,
            LocalizationConstants::DEPARTMENT_ID_DEUX_SEVRES,
            LocalizationConstants::DEPARTMENT_ID_SOMME,
            LocalizationConstants::DEPARTMENT_ID_TARN,
            LocalizationConstants::DEPARTMENT_ID_TARN_ET_GARONNE,
            LocalizationConstants::DEPARTMENT_ID_VAR,
            LocalizationConstants::DEPARTMENT_ID_VAUCLUSE,
            LocalizationConstants::DEPARTMENT_ID_VENDEE,
            LocalizationConstants::DEPARTMENT_ID_VIENNE,
            LocalizationConstants::DEPARTMENT_ID_HAUTE_VIENNE,
            LocalizationConstants::DEPARTMENT_ID_VOSGES,
            LocalizationConstants::DEPARTMENT_ID_YONNE,
            LocalizationConstants::DEPARTMENT_ID_TERRITOIRE_DE_BELFORT,
            LocalizationConstants::DEPARTMENT_ID_ESSONNE,
            LocalizationConstants::DEPARTMENT_ID_HAUTS_DE_SEINE,
            LocalizationConstants::DEPARTMENT_ID_SEINE_SAINT_DENIS,
            LocalizationConstants::DEPARTMENT_ID_VAL_DE_MARNE,
            LocalizationConstants::DEPARTMENT_ID_VAL_D_OISE,
        ];
    }

    /**
     * Return array of outre mer departments ids
     *
     * @return array
     */
    public static function getGeoDepartmentOMIds()
    {
        return [
            LocalizationConstants::DEPARTMENT_ID_GUADELOUPE,
            LocalizationConstants::DEPARTMENT_ID_MARTINIQUE,
            LocalizationConstants::DEPARTMENT_ID_GUYANE,
            LocalizationConstants::DEPARTMENT_ID_LA_REUNION,
            LocalizationConstants::DEPARTMENT_ID_MAYOTTE,
            LocalizationConstants::DEPARTMENT_ID_POLYNESIE_FRANCAISE,
            LocalizationConstants::DEPARTMENT_ID_SAINT_BARTHELEMY,
            LocalizationConstants::DEPARTMENT_ID_SAINT_MARTIN,
            LocalizationConstants::DEPARTMENT_ID_SAINT_PIERRE_ET_MIQUELON,
            LocalizationConstants::DEPARTMENT_ID_WALLIS_ET_FUTUNA,
            LocalizationConstants::DEPARTMENT_ID_NOUVELLE_CALEDONIE,
        ];
    }

    /**
     * Return array of all departments ids
     *
     * @return array
     */
    public static function getGeoDepartmentIds()
    {
        $metroIds = LocalizationConstants::getGeoDepartmentMetroIds();
        $omIds = LocalizationConstants::getGeoDepartmentOMIds();

        return array_merge($metroIds, $omIds);
    }
}
