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

    const TAG_GEO_REGION_ID_1 = 9;
    const TAG_GEO_REGION_ID_2 = 10;
    const TAG_GEO_REGION_ID_3 = 11;
    const TAG_GEO_REGION_ID_4 = 12;
    const TAG_GEO_REGION_ID_5 = 13;
    const TAG_GEO_REGION_ID_6 = 14;
    const TAG_GEO_REGION_ID_7 = 15;
    const TAG_GEO_REGION_ID_8 = 16;
    const TAG_GEO_REGION_ID_9 = 17;
    const TAG_GEO_REGION_ID_10 = 18;
    const TAG_GEO_REGION_ID_11 = 19;
    const TAG_GEO_REGION_ID_12 = 20;
    const TAG_GEO_REGION_ID_13 = 21;
    const TAG_GEO_REGION_ID_14 = 22;


    const TAG_GEO_DEPARTMENT_LAST_ID = 123;


    /**
     * Return array of region ids
     *
     * @return array
     */
    public static function getGeoRegionIds()
    {
        return [
            TagConstants::TAG_GEO_REGION_ID_1,
            TagConstants::TAG_GEO_REGION_ID_2,
            TagConstants::TAG_GEO_REGION_ID_3,
            TagConstants::TAG_GEO_REGION_ID_4,
            TagConstants::TAG_GEO_REGION_ID_5,
            TagConstants::TAG_GEO_REGION_ID_6,
            TagConstants::TAG_GEO_REGION_ID_7,
            TagConstants::TAG_GEO_REGION_ID_8,
            TagConstants::TAG_GEO_REGION_ID_9,
            TagConstants::TAG_GEO_REGION_ID_10,
            TagConstants::TAG_GEO_REGION_ID_11,
            TagConstants::TAG_GEO_REGION_ID_12,
            TagConstants::TAG_GEO_REGION_ID_13,
            TagConstants::TAG_GEO_REGION_ID_14,
        ];
    }
}
