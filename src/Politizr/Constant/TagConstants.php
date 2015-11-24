<?php

namespace Politizr\Constant;

/**
 * Tag constants
 *
 * @author Lionel Bouzonville
 */
class TagConstants
{
    // ************************************************************************************ //
    //                                PTTAGTYPE OBJECT ID
    // ************************************************************************************ //
    const TAG_TYPE_GEO = 1;
    const TAG_TYPE_THEME = 2;

    // ************************************************************************************ //
    //                                GEO OBJECT IDS
    // ************************************************************************************ //
    const TAG_GEO_WORLD_ID = 1;
    
    const TAG_GEO_EUROPE_ID = 3;

    const TAG_GEO_FRANCE_ID = 8;

    const TAG_GEO_REGION_ID_ACAL = 9;
    const TAG_GEO_REGION_ID_ALPC = 10;
    const TAG_GEO_REGION_ID_ARA = 11;
    const TAG_GEO_REGION_ID_BFC = 12;
    const TAG_GEO_REGION_ID_B = 13;
    const TAG_GEO_REGION_ID_CVDL = 14;
    const TAG_GEO_REGION_ID_C = 15;
    const TAG_GEO_REGION_ID_IDF = 16;
    const TAG_GEO_REGION_ID_LRMP = 17;
    const TAG_GEO_REGION_ID_NPDCP = 18;
    const TAG_GEO_REGION_ID_N = 19;
    const TAG_GEO_REGION_ID_PDLL = 20;
    const TAG_GEO_REGION_ID_PACA = 21;
    const TAG_GEO_REGION_ID_FOM = 22;


    const TAG_GEO_DEPARTMENT_LAST_ID = 123;


    /**
     * Return array of region ids
     *
     * @return array
     */
    public static function getGeoRegionIds()
    {
        return [
            TagConstants::TAG_GEO_REGION_ID_ACAL,
            TagConstants::TAG_GEO_REGION_ID_ALPC,
            TagConstants::TAG_GEO_REGION_ID_ARA,
            TagConstants::TAG_GEO_REGION_ID_BFC,
            TagConstants::TAG_GEO_REGION_ID_B,
            TagConstants::TAG_GEO_REGION_ID_CVDL,
            TagConstants::TAG_GEO_REGION_ID_C,
            TagConstants::TAG_GEO_REGION_ID_IDF,
            TagConstants::TAG_GEO_REGION_ID_LRMP,
            TagConstants::TAG_GEO_REGION_ID_NPDCP,
            TagConstants::TAG_GEO_REGION_ID_N,
            TagConstants::TAG_GEO_REGION_ID_PDLL,
            TagConstants::TAG_GEO_REGION_ID_PACA,
            TagConstants::TAG_GEO_REGION_ID_FOM,
        ];
    }
}
