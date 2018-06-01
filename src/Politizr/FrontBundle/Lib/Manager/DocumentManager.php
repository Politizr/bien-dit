<?php
namespace Politizr\FrontBundle\Lib\Manager;

use Politizr\Exception\InconsistentDataException;

use Politizr\Constant\ObjectTypeConstants;
use Politizr\Constant\ReputationConstants;
use Politizr\Constant\ListingConstants;
use Politizr\Constant\TagConstants;
use Politizr\Constant\PathConstants;

use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PDDComment;
use Politizr\Model\PDRComment;
use Politizr\Model\PDMedia;
use Politizr\Model\PMDebateHistoric;
use Politizr\Model\PMReactionHistoric;
use Politizr\Model\PMDCommentHistoric;
use Politizr\Model\PMRCommentHistoric;

use Politizr\Model\PDRTaggedT;

use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReactionQuery;

/**
 * DB manager service for document.
 *
 * @author Lionel Bouzonville
 */
class DocumentManager
{
    private $kernel;

    private $tagManager;

    private $globalTools;

    private $logger;

    /**
     *
     * @param @kernel
     * @param @politizr.manager.tag
     * @param @politizr.tools.global
     * @param @logger
     */
    public function __construct(
        $kernel,
        $tagManager,
        $globalTools,
        $logger
    ) {
        $this->kernel = $kernel;

        $this->tagManager = $tagManager;

        $this->globalTools = $globalTools;
        
        $this->logger = $logger;
    }

    /* ######################################################################################################## */
    /*                                                  RAW SQL                                                 */
    /* ######################################################################################################## */

    /**
     * Homepage publications
     *
     * @see app/sql/homeDocuments.sql
     *
     * @return string
     */
    private function createHomepagePublicationsRawSql()
    {
        // Requête SQL
        $sql = "
#  Débats publiés
( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.published_at as published_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1 
    AND p_d_debate.p_c_topic_id is NULL
    AND p_d_debate.homepage = 1 
)

UNION DISTINCT

#  Réactions publiés
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.p_c_topic_id is NULL
    AND p_d_reaction.homepage = 1
    AND p_d_reaction.tree_level > 0
)

ORDER BY rand()
LIMIT :offset, :limit
";

        return $sql;
    }

    /**
     * Compute subrequest for geo ids
     *
     * @param string $inQueryCityIds
     * @param string $inQueryDepartmentIds
     * @param int $regionId
     * @param int $countryId
     */
    private function computeSubRequestGeoIds(
        $inQueryCityIds, $inQueryDepartmentIds, $regionId, $countryId,
        & $subRequestCityIds1,
        & $subRequestDepartmentIds1,
        & $subRequestRegionIds1,
        & $subRequestCountryIds1,
        & $subRequestCityIds2,
        & $subRequestDepartmentIds2,
        & $subRequestRegionIds2,
        & $subRequestCountryIds2
    ) {
        if ($inQueryCityIds) {
            $subRequestCityIds1 = "AND (p_d_debate.p_l_city_id IN ($inQueryCityIds)";
            $subRequestCityIds2 = "AND (p_d_reaction.p_l_city_id IN ($inQueryCityIds)";
        }
        if ($inQueryDepartmentIds) {
            if ($inQueryCityIds) {
                $prefix = "OR ";
            } else {
                $prefix = "AND (";
            }
            $subRequestDepartmentIds1 = $prefix . "p_d_debate.p_l_department_id IN ($inQueryDepartmentIds)";
            $subRequestDepartmentIds2 = $prefix . "p_d_reaction.p_l_department_id IN ($inQueryDepartmentIds)";
        }
        if ($regionId) {
            if ($inQueryCityIds || $inQueryDepartmentIds) {
                $prefix = "OR ";
            } else {
                $prefix = "AND (";
            }
            $subRequestRegionIds1 = $prefix . "p_d_debate.p_l_region_id = $regionId";
            $subRequestRegionIds2 = $prefix . "p_d_reaction.p_l_region_id = $regionId";
        }

        // upd > default = all publications
        // if ($countryId) {
        //     if ($inQueryCityIds || $inQueryDepartmentIds || $regionId) {
        //         $prefix = "OR ";
        //     } else {
        //         $prefix = "AND (";
        //     }
        //     $subRequestCountryIds1 = $prefix . "p_d_debate.p_l_country_id = $countryId";
        //     $subRequestCountryIds2 = $prefix . "p_d_reaction.p_l_country_id = $countryId";
        // }

        if ($inQueryCityIds || $inQueryDepartmentIds || $regionId /* || $countryId */) {
            $subRequestCountryIds1 .= ")";
            $subRequestCountryIds2 .= ")";
        }
    }

    /**
     * Filtered publications
     *
     * @see app/sql/documentsByFilters.sql
     *
     * @param string $inQueryTopicIds
     * @param string $inQueryCityIds
     * @param string $inQueryDepartmentIds
     * @param int $regionId
     * @param int $countryId
     * @param int $topicId
     * @param string $filterPublication
     * @param string $filterProfile
     * @param string $filterActivity
     * @param string $filterDate
     * @return string
     */
    private function createPublicationsByFiltersRawSql($inQueryTopicIds, $inQueryCityIds, $inQueryDepartmentIds, $regionId, $countryId, $topicId, $filterPublication, $filterProfile, $filterActivity, $filterDate)
    {
        // Geo subrequest
        $subRequestCityIds1 = null;
        $subRequestDepartmentIds1 = null;
        $subRequestRegionIds1 = null;
        $subRequestCountryIds1 = null;
        $subRequestCityIds2 = null;
        $subRequestDepartmentIds2 = null;
        $subRequestRegionIds2 = null;
        $subRequestCountryIds2 = null;

        $this->computeSubRequestGeoIds(
            $inQueryCityIds, $inQueryDepartmentIds, $regionId, $countryId,
            $subRequestCityIds1,
            $subRequestDepartmentIds1,
            $subRequestRegionIds1,
            $subRequestCountryIds1,
            $subRequestCityIds2,
            $subRequestDepartmentIds2,
            $subRequestRegionIds2,
            $subRequestCountryIds2
        );

        // Topic subrequest
        $subRequestTopic1 = "AND p_d_debate.p_c_topic_id is NULL";
        $subRequestTopic2 = "AND p_d_reaction.p_c_topic_id is NULL";
        if ($topicId) {
            $subRequestTopic1 = sprintf("AND p_d_debate.p_c_topic_id = %s", $topicId);
            $subRequestTopic2 = sprintf("AND p_d_reaction.p_c_topic_id = %s", $topicId);
        } elseif ($inQueryTopicIds) {
            $subRequestTopic1 = "AND (p_d_debate.p_c_topic_id is NULL OR p_d_debate.p_c_topic_id IN ($inQueryTopicIds))";
            $subRequestTopic2 = "AND (p_d_reaction.p_c_topic_id is NULL OR p_d_reaction.p_c_topic_id IN ($inQueryTopicIds))";
        }

        // Profile subrequest
        $subRequestFilterProfile = null;
        if ($filterProfile == ListingConstants::FILTER_KEYWORD_QUALIFIED) {
            $subRequestFilterProfile = "AND p_user.qualified = 1";
        } elseif ($filterProfile == ListingConstants::FILTER_KEYWORD_CITIZEN) {
            $subRequestFilterProfile = "AND p_user.qualified = 0";
        }

        // Activity subrequest
        if ($filterActivity == ListingConstants::ORDER_BY_KEYWORD_LAST) {
            $orderBy = "ORDER BY published_at DESC";
        } elseif ($filterActivity == ListingConstants::ORDER_BY_KEYWORD_BEST_NOTE) {
            $orderBy = "ORDER BY note_pos DESC, note_neg ASC";
        } elseif ($filterActivity == ListingConstants::ORDER_BY_KEYWORD_MOST_VIEWS) {
            $orderBy = "ORDER BY nb_views DESC";
        } else {
            throw new InconsistentDataException(sprintf('Filter activity %s is not managed', $filterActivity));
        }

        // Date subrequest
        $subRequestFilterDate1 = null;
        $subRequestFilterDate2 = null;
        $subRequestFilterDate3 = null;
        $subRequestFilterDate4 = null;
        if ($filterDate == ListingConstants::FILTER_KEYWORD_LAST_MONTH) {
            $subRequestFilterDate1 = "AND p_d_debate.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() ";
            $subRequestFilterDate2 = "AND p_d_reaction.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() ";
            $subRequestFilterDate3 = "AND p_d_d_comment.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() ";
            $subRequestFilterDate4 = "AND p_d_r_comment.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() ";
        } elseif ($filterDate == ListingConstants::FILTER_KEYWORD_LAST_WEEK) {
            $subRequestFilterDate1 = "AND p_d_debate.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW() ";
            $subRequestFilterDate2 = "AND p_d_reaction.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW() ";
            $subRequestFilterDate3 = "AND p_d_d_comment.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW() ";
            $subRequestFilterDate4 = "AND p_d_r_comment.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW() ";
        } elseif ($filterDate == ListingConstants::FILTER_KEYWORD_LAST_DAY) {
            $subRequestFilterDate1 = "AND p_d_debate.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW() ";
            $subRequestFilterDate2 = "AND p_d_reaction.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW() ";
            $subRequestFilterDate3 = "AND p_d_d_comment.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW() ";
            $subRequestFilterDate4 = "AND p_d_r_comment.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW() ";
        }

        // Requête SQL
        $sqlPartDebates = "
#  Débats publiés
( SELECT DISTINCT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.file_name as fileName, p_d_debate.description as description, p_d_debate.slug as slug, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, p_d_debate.nb_views as nb_views, p_d_debate.published_at as published_at, p_d_debate.updated_at as updated_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
    LEFT JOIN p_user
        ON p_d_debate.p_user_id = p_user.id
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1 
    $subRequestTopic1
    $subRequestCityIds1
    $subRequestDepartmentIds1
    $subRequestRegionIds1
    $subRequestCountryIds1
    $subRequestFilterDate1
    $subRequestFilterProfile
)
";

        $sqlPartReactions = "
#  Réactions publiés
( SELECT DISTINCT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.file_name as fileName, p_d_reaction.description as description, p_d_reaction.slug as slug, p_d_reaction.note_pos as note_pos, p_d_reaction.note_neg as note_neg, p_d_reaction.nb_views as nb_views, p_d_reaction.published_at as published_at, p_d_reaction.updated_at as updated_at,'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
    LEFT JOIN p_user
        ON p_d_reaction.p_user_id = p_user.id
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.tree_level > 0
    $subRequestTopic2
    $subRequestCityIds2
    $subRequestDepartmentIds2
    $subRequestRegionIds2
    $subRequestCountryIds2
    $subRequestFilterDate2
    $subRequestFilterProfile
)
";

        $sqlPartComments = "
# Commentaires débats publiés
( SELECT DISTINCT p_d_d_comment.id as id, \"commentaire\" as title, \"image\" as fileName, p_d_d_comment.description as description, \"slug\" as slug, p_d_d_comment.note_pos as note_pos, p_d_d_comment.note_neg as note_neg, -1 as nb_views, p_d_d_comment.published_at as published_at, p_d_d_comment.updated_at as updated_at, 'Politizr\\\Model\\\PDDComment' as type
FROM p_d_d_comment
    LEFT JOIN p_d_debate
        ON p_d_d_comment.p_d_debate_id = p_d_debate.id
    LEFT JOIN p_user
        ON p_user.id = p_d_d_comment.p_user_id
WHERE
    p_d_d_comment.online = 1
    $subRequestTopic1
    $subRequestCityIds1
    $subRequestDepartmentIds1
    $subRequestRegionIds1
    $subRequestCountryIds1
    $subRequestFilterDate3
    $subRequestFilterProfile
)

UNION DISTINCT

# Commentaires réactions publiés
( SELECT DISTINCT p_d_r_comment.id as id, \"commentaire\" as title, \"image\" as fileName, p_d_r_comment.description as description, \"slug\" as slug, p_d_r_comment.note_pos as note_pos, p_d_r_comment.note_neg as note_neg, -1 as nb_views, p_d_r_comment.published_at as published_at, p_d_r_comment.updated_at as updated_at, 'Politizr\\\Model\\\PDRComment' as type
FROM p_d_r_comment
    LEFT JOIN p_d_reaction
        ON p_d_r_comment.p_d_reaction_id = p_d_reaction.id
    LEFT JOIN p_user
        ON p_user.id = p_d_r_comment.p_user_id
WHERE
    p_d_r_comment.online = 1
    $subRequestTopic2
    $subRequestCityIds2
    $subRequestDepartmentIds2
    $subRequestRegionIds2
    $subRequestCountryIds2
    $subRequestFilterDate4
    $subRequestFilterProfile
)
";

        $sqlOrderLimit = "
$orderBy

LIMIT :offset, :limit
";

        // Publication filter
        if ($filterPublication == ListingConstants::FILTER_KEYWORD_ALL_PUBLICATIONS) {
            $sql = $sqlPartDebates . " UNION DISTINCT " . $sqlPartReactions . " UNION DISTINCT " . $sqlPartComments . $sqlOrderLimit;
        } elseif ($filterPublication == ListingConstants::FILTER_KEYWORD_DEBATES_AND_REACTIONS) {
            $sql = $sqlPartDebates . " UNION DISTINCT " . $sqlPartReactions . $sqlOrderLimit;
        } elseif ($filterPublication == ListingConstants::FILTER_KEYWORD_DEBATES) {
            $sql = $sqlPartDebates . $sqlOrderLimit;
        } elseif ($filterPublication == ListingConstants::FILTER_KEYWORD_REACTIONS) {
            $sql = $sqlPartReactions . $sqlOrderLimit;
        } elseif ($filterPublication == ListingConstants::FILTER_KEYWORD_COMMENTS) {
            $sql = $sqlPartComments . $sqlOrderLimit;
        } else {
            throw new InconsistentDataException(sprintf('Filter publication %s is not managed', $filterPublication));
        }
        
        return $sql;
    }

    /**
     * Filtered publications w. filter "most followed" > manage group by + only debates in result
     *
     * @see app/sql/documentsByFiltersMostFollowed.sql
     *
     * @param string $inQueryTopicIds
     * @param string $inQueryCityIds
     * @param string $inQueryDepartmentIds
     * @param int $regionId
     * @param int $countryId
     * @param int $topicId
     * @param string $filterProfile
     * @param string $filterDate
     * @return string
     */
    private function createPublicationsByFiltersMostFollowedRawSql($inQueryTopicIds, $inQueryCityIds, $inQueryDepartmentIds, $regionId, $countryId, $topicId, $filterProfile, $filterDate)
    {
        // Geo subrequest
        $subRequestCityIds1 = null;
        $subRequestDepartmentIds1 = null;
        $subRequestRegionIds1 = null;
        $subRequestCountryIds1 = null;
        $subRequestCityIds2 = null;
        $subRequestDepartmentIds2 = null;
        $subRequestRegionIds2 = null;
        $subRequestCountryIds2 = null;

        $this->computeSubRequestGeoIds(
            $inQueryCityIds, $inQueryDepartmentIds, $regionId, $countryId,
            $subRequestCityIds1,
            $subRequestDepartmentIds1,
            $subRequestRegionIds1,
            $subRequestCountryIds1,
            $subRequestCityIds2,
            $subRequestDepartmentIds2,
            $subRequestRegionIds2,
            $subRequestCountryIds2
        );

        // Topic subrequest
        $subRequestTopic1 = "AND p_d_debate.p_c_topic_id is NULL";
        $subRequestTopic2 = "AND p_d_reaction.p_c_topic_id is NULL";
        if ($topicId) {
            $subRequestTopic1 = sprintf("AND p_d_debate.p_c_topic_id = %s", $topicId);
            $subRequestTopic2 = sprintf("AND p_d_reaction.p_c_topic_id = %s", $topicId);
        } elseif ($inQueryTopicIds) {
            $subRequestTopic1 = "AND (p_d_debate.p_c_topic_id is NULL OR p_d_debate.p_c_topic_id IN ($inQueryTopicIds))";
            $subRequestTopic2 = "AND (p_d_reaction.p_c_topic_id is NULL OR p_d_reaction.p_c_topic_id IN ($inQueryTopicIds))";
        }

        // Profile subrequest
        $subRequestFilterProfileLeftJoin = null;
        $subRequestFilterProfile = null;

        if ($filterProfile == ListingConstants::FILTER_KEYWORD_QUALIFIED || $filterProfile == ListingConstants::FILTER_KEYWORD_CITIZEN) {
            $subRequestFilterProfileLeftJoin = "
    LEFT JOIN p_user
        ON p_d_debate.p_user_id = p_user.id
";
        }
        if ($filterProfile == ListingConstants::FILTER_KEYWORD_QUALIFIED) {
            $subRequestFilterProfile = "AND p_user.qualified = 1";
        } elseif ($filterProfile == ListingConstants::FILTER_KEYWORD_CITIZEN) {
            $subRequestFilterProfile = "AND p_user.qualified = 0";
        }

        // Date subrequest
        $subRequestFilterDate = null;
        if ($filterDate == ListingConstants::FILTER_KEYWORD_LAST_MONTH) {
            $subRequestFilterDate = "AND p_d_debate.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() ";
        } elseif ($filterDate == ListingConstants::FILTER_KEYWORD_LAST_WEEK) {
            $subRequestFilterDate = "AND p_d_debate.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW() ";
        } elseif ($filterDate == ListingConstants::FILTER_KEYWORD_LAST_DAY) {
            $subRequestFilterDate = "AND p_d_debate.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW() ";
        }

        // Requête SQL
        $sql = "
SELECT DISTINCT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.file_name as fileName, p_d_debate.description as description, p_d_debate.slug as slug, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, COUNT(p_u_follow_d_d.id) as nb_followers, p_d_debate.published_at as published_at, p_d_debate.updated_at as updated_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
    LEFT JOIN p_u_follow_d_d
        ON p_d_debate.id = p_u_follow_d_d.p_d_debate_id
    $subRequestFilterProfileLeftJoin
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1 
    $subRequestTopic1
    $subRequestCityIds1
    $subRequestDepartmentIds1
    $subRequestRegionIds1
    $subRequestCountryIds1
    $subRequestFilterDate
    $subRequestFilterProfile

GROUP BY id

ORDER BY nb_followers DESC, note_pos DESC, note_neg ASC

LIMIT :offset, :limit
";
        
        return $sql;
    }

    /**
     * Filtered publications w. filter "most reactions" > manage group by + only debates and/or reactions in result
     *
     * @see app/sql/documentsByFiltersMostReactions.sql
     *
     * @param string $inQueryTopicIds
     * @param string $inQueryCityIds
     * @param string $inQueryDepartmentIds
     * @param int $regionId
     * @param int $countryId
     * @param int $topicId
     * @param string $filterPublication
     * @param string $filterProfile
     * @param string $filterDate
     * @return string
     */
    private function createPublicationsByFiltersMostReactionsRawSql($inQueryTopicIds, $inQueryCityIds, $inQueryDepartmentIds, $regionId, $countryId, $topicId, $filterPublication, $filterProfile, $filterDate)
    {
        // Geo subrequest
        $subRequestCityIds1 = null;
        $subRequestDepartmentIds1 = null;
        $subRequestRegionIds1 = null;
        $subRequestCountryIds1 = null;
        $subRequestCityIds2 = null;
        $subRequestDepartmentIds2 = null;
        $subRequestRegionIds2 = null;
        $subRequestCountryIds2 = null;

        $this->computeSubRequestGeoIds(
            $inQueryCityIds, $inQueryDepartmentIds, $regionId, $countryId,
            $subRequestCityIds1,
            $subRequestDepartmentIds1,
            $subRequestRegionIds1,
            $subRequestCountryIds1,
            $subRequestCityIds2,
            $subRequestDepartmentIds2,
            $subRequestRegionIds2,
            $subRequestCountryIds2
        );

        // Topic subrequest
        $subRequestTopic1 = "AND p_d_debate.p_c_topic_id is NULL";
        $subRequestTopic2 = "AND p_d_reaction.p_c_topic_id is NULL";
        if ($topicId) {
            $subRequestTopic1 = sprintf("AND p_d_debate.p_c_topic_id = %s", $topicId);
            $subRequestTopic2 = sprintf("AND p_d_reaction.p_c_topic_id = %s", $topicId);
        } elseif ($inQueryTopicIds) {
            $subRequestTopic1 = "AND (p_d_debate.p_c_topic_id is NULL OR p_d_debate.p_c_topic_id IN ($inQueryTopicIds))";
            $subRequestTopic2 = "AND (p_d_reaction.p_c_topic_id is NULL OR p_d_reaction.p_c_topic_id IN ($inQueryTopicIds))";
        }

        // Profile subrequest
        $subRequestFilterProfileLeftJoin1 = null;
        $subRequestFilterProfileLeftJoin2 = null;
        $subRequestFilterProfile = null;

        if ($filterProfile == ListingConstants::FILTER_KEYWORD_QUALIFIED || $filterProfile == ListingConstants::FILTER_KEYWORD_CITIZEN) {
            $subRequestFilterProfileLeftJoin1 = "
    LEFT JOIN p_user
        ON p_d_debate.p_user_id = p_user.id
";
            $subRequestFilterProfileLeftJoin2 = "
    LEFT JOIN p_user
        ON p_d_reaction.p_user_id = p_user.id
";
        }
        if ($filterProfile == ListingConstants::FILTER_KEYWORD_QUALIFIED) {
            $subRequestFilterProfile = "AND p_user.qualified = 1";
        } elseif ($filterProfile == ListingConstants::FILTER_KEYWORD_CITIZEN) {
            $subRequestFilterProfile = "AND p_user.qualified = 0";
        }

        // Date subrequest
        $subRequestFilterDate1 = null;
        $subRequestFilterDate2 = null;
        if ($filterDate == ListingConstants::FILTER_KEYWORD_LAST_MONTH) {
            $subRequestFilterDate1 = "AND p_d_debate.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() ";
            $subRequestFilterDate2 = "AND p_d_reaction.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() ";
        } elseif ($filterDate == ListingConstants::FILTER_KEYWORD_LAST_WEEK) {
            $subRequestFilterDate1 = "AND p_d_debate.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW() ";
            $subRequestFilterDate2 = "AND p_d_reaction.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW() ";
        } elseif ($filterDate == ListingConstants::FILTER_KEYWORD_LAST_DAY) {
            $subRequestFilterDate1 = "AND p_d_debate.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW() ";
            $subRequestFilterDate2 = "AND p_d_reaction.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW() ";
        }

        // Requête SQL
        $sqlPartDebates = "
( SELECT DISTINCT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.file_name as fileName, p_d_debate.description as description, p_d_debate.slug as slug, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, COUNT(DISTINCT p_d_reaction_child.id) as nb_reactions, p_d_debate.published_at as published_at, p_d_debate.updated_at as updated_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
    LEFT JOIN p_d_reaction as p_d_reaction_child
        ON p_d_debate.id = p_d_reaction_child.p_d_debate_id
    $subRequestFilterProfileLeftJoin1
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1
    $subRequestTopic1
    AND p_d_reaction_child.published = 1
    AND p_d_reaction_child.online = true
    $subRequestCityIds1
    $subRequestDepartmentIds1
    $subRequestRegionIds1
    $subRequestCountryIds1
    $subRequestFilterDate1
    $subRequestFilterProfile

GROUP BY id
)
";

        $sqlPartReactions = "
( SELECT DISTINCT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.file_name as fileName, p_d_reaction.description as description, p_d_reaction.slug as slug, p_d_reaction.note_pos as note_pos, p_d_reaction.note_neg as note_neg, COUNT(DISTINCT p_d_reaction_child.id) as nb_reactions, p_d_reaction.published_at as published_at, p_d_reaction.updated_at as updated_at,'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
    LEFT JOIN p_d_reaction as p_d_reaction_child
        ON p_d_reaction.id = p_d_reaction_child.parent_reaction_id
    $subRequestFilterProfileLeftJoin2
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    $subRequestTopic2
    AND p_d_reaction.tree_level > 0
    AND p_d_reaction_child.published = 1
    AND p_d_reaction_child.online = true
    $subRequestCityIds2
    $subRequestDepartmentIds2
    $subRequestRegionIds2
    $subRequestCountryIds2
    $subRequestFilterDate2
    $subRequestFilterProfile

GROUP BY id
)
";
    
        $sqlOrderLimit = "
ORDER BY nb_reactions DESC, note_pos DESC, note_neg ASC

LIMIT :offset, :limit
";
  
        // Publication filter
        if ($filterPublication == ListingConstants::FILTER_KEYWORD_DEBATES_AND_REACTIONS) {
            $sql = $sqlPartDebates . " UNION DISTINCT " . $sqlPartReactions . $sqlOrderLimit;
        } elseif ($filterPublication == ListingConstants::FILTER_KEYWORD_DEBATES) {
            $sql = $sqlPartDebates . $sqlOrderLimit;
        } elseif ($filterPublication == ListingConstants::FILTER_KEYWORD_REACTIONS) {
            $sql = $sqlPartReactions . $sqlOrderLimit;
        } else {
            throw new InconsistentDataException(sprintf('Filter publication %s is not managed', $filterPublication));
        }
        
        return $sql;
    }

    /**
     * Filtered publications w. filter "most comments" > manage group by + only debates and/or reactions in result
     *
     * @see app/sql/documentsByFiltersMostComments.sql
     *
     * @param string $inQueryTopicIds
     * @param string $inQueryCityIds
     * @param string $inQueryDepartmentIds
     * @param int $regionId
     * @param int $countryId
     * @param int $topicId
     * @param string $filterPublication
     * @param string $filterProfile
     * @param string $filterDate
     * @return string
     */
    private function createPublicationsByFiltersMostCommentsRawSql($inQueryTopicIds, $inQueryCityIds, $inQueryDepartmentIds, $regionId, $countryId, $topicId, $filterPublication, $filterProfile, $filterDate)
    {
        // Geo subrequest
        $subRequestCityIds1 = null;
        $subRequestDepartmentIds1 = null;
        $subRequestRegionIds1 = null;
        $subRequestCountryIds1 = null;
        $subRequestCityIds2 = null;
        $subRequestDepartmentIds2 = null;
        $subRequestRegionIds2 = null;
        $subRequestCountryIds2 = null;

        $this->computeSubRequestGeoIds(
            $inQueryCityIds, $inQueryDepartmentIds, $regionId, $countryId,
            $subRequestCityIds1,
            $subRequestDepartmentIds1,
            $subRequestRegionIds1,
            $subRequestCountryIds1,
            $subRequestCityIds2,
            $subRequestDepartmentIds2,
            $subRequestRegionIds2,
            $subRequestCountryIds2
        );

        // Topic subrequest
        $subRequestTopic1 = "AND p_d_debate.p_c_topic_id is NULL";
        $subRequestTopic2 = "AND p_d_reaction.p_c_topic_id is NULL";
        if ($topicId) {
            $subRequestTopic1 = sprintf("AND p_d_debate.p_c_topic_id = %s", $topicId);
            $subRequestTopic2 = sprintf("AND p_d_reaction.p_c_topic_id = %s", $topicId);
        } elseif ($inQueryTopicIds) {
            $subRequestTopic1 = "AND (p_d_debate.p_c_topic_id is NULL OR p_d_debate.p_c_topic_id IN ($inQueryTopicIds))";
            $subRequestTopic2 = "AND (p_d_reaction.p_c_topic_id is NULL OR p_d_reaction.p_c_topic_id IN ($inQueryTopicIds))";
        }

        // Profile subrequest
        $subRequestFilterProfileLeftJoin1 = null;
        $subRequestFilterProfileLeftJoin2 = null;
        $subRequestFilterProfile = null;

        if ($filterProfile == ListingConstants::FILTER_KEYWORD_QUALIFIED || $filterProfile == ListingConstants::FILTER_KEYWORD_CITIZEN) {
            $subRequestFilterProfileLeftJoin1 = "
    LEFT JOIN p_user
        ON p_d_debate.p_user_id = p_user.id
";
            $subRequestFilterProfileLeftJoin2 = "
    LEFT JOIN p_user
        ON p_d_reaction.p_user_id = p_user.id
";
        }
        if ($filterProfile == ListingConstants::FILTER_KEYWORD_QUALIFIED) {
            $subRequestFilterProfile = "AND p_user.qualified = 1";
        } elseif ($filterProfile == ListingConstants::FILTER_KEYWORD_CITIZEN) {
            $subRequestFilterProfile = "AND p_user.qualified = 0";
        }

        // Date subrequest
        $subRequestFilterDate = null;
        if ($filterDate == ListingConstants::FILTER_KEYWORD_LAST_MONTH) {
            $subRequestFilterDate = "AND p_d_debate.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() ";
        } elseif ($filterDate == ListingConstants::FILTER_KEYWORD_LAST_WEEK) {
            $subRequestFilterDate = "AND p_d_debate.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 7 DAY) AND NOW() ";
        } elseif ($filterDate == ListingConstants::FILTER_KEYWORD_LAST_DAY) {
            $subRequestFilterDate = "AND p_d_debate.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 1 DAY) AND NOW() ";
        }

        // Requête SQL
        $sqlPartDebates = "
( SELECT DISTINCT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.file_name as fileName, p_d_debate.description as description, p_d_debate.slug as slug, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, COUNT(p_d_d_comment.id) as nb_comments, p_d_debate.published_at as published_at, p_d_debate.updated_at as updated_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
    LEFT JOIN p_d_d_comment
        ON p_d_debate.id = p_d_d_comment.p_d_debate_id
    $subRequestFilterProfileLeftJoin1
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1
    $subRequestTopic1
    AND p_d_d_comment.online = true
    $subRequestCityIds1
    $subRequestDepartmentIds1
    $subRequestRegionIds1
    $subRequestCountryIds1
    $subRequestFilterDate
    $subRequestFilterProfile

GROUP BY id
)
";

        $sqlPartReactions = "
( SELECT DISTINCT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.file_name as fileName, p_d_reaction.description as description, p_d_reaction.slug as slug, p_d_reaction.note_pos as note_pos, p_d_reaction.note_neg as note_neg, COUNT(p_d_r_comment.id) as nb_comments, p_d_reaction.published_at as published_at, p_d_reaction.updated_at as updated_at,'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
    LEFT JOIN p_d_r_comment
        ON p_d_reaction.id = p_d_r_comment.p_d_reaction_id
    $subRequestFilterProfileLeftJoin2
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    $subRequestTopic2
    AND p_d_reaction.tree_level > 0
    AND p_d_r_comment.online = true
    $subRequestCityIds2
    $subRequestDepartmentIds2
    $subRequestRegionIds2
    $subRequestCountryIds2
    $subRequestFilterDate
    $subRequestFilterProfile

GROUP BY id
)
";

        $sqlOrderLimit = "
ORDER BY nb_comments DESC, note_pos DESC, note_neg ASC

LIMIT :offset, :limit
";
  
        // Publication filter
        if ($filterPublication == ListingConstants::FILTER_KEYWORD_DEBATES_AND_REACTIONS) {
            $sql = $sqlPartDebates . " UNION DISTINCT " . $sqlPartReactions . $sqlOrderLimit;
        } elseif ($filterPublication == ListingConstants::FILTER_KEYWORD_DEBATES) {
            $sql = $sqlPartDebates . $sqlOrderLimit;
        } elseif ($filterPublication == ListingConstants::FILTER_KEYWORD_REACTIONS) {
            $sql = $sqlPartReactions . $sqlOrderLimit;
        } else {
            throw new InconsistentDataException(sprintf('Filter publication %s is not managed', $filterPublication));
        }

        return $sql;
    }

    /**
     * Last publicated documents
     *
     * @see
     *
     * @return string
     */
    private function createDocumentsLastPublishedRawSql()
    {
        // Requête SQL
        $sql = "
( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.published_at as published_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1 
    )

UNION DISTINCT

( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.tree_level > 0
    )

ORDER BY published_at DESC

LIMIT :limit
";

        return $sql;
    }

   /**
     * User's publications
     *
     * @see app/sql/userDocuments.sql
     *
     * @param string $inQueryTopicIds
     * @param string $orderBy
     * @param integer $tagId
     * @return string
     */
    private function createPublicationsByUserRawSql($inQueryTopicIds, $orderBy = ListingConstants::ORDER_BY_KEYWORD_BEST_NOTE, $tagId = null)
    {
        $orderByReq = "ORDER BY note_pos DESC, note_neg ASC";
        if ($orderBy == ListingConstants::ORDER_BY_KEYWORD_LAST) {
            $orderByReq = "ORDER BY published_at DESC";
        }

        // Topic subrequest
        $subrequestTopic1 = "AND p_d_debate.p_c_topic_id is NULL";
        $subrequestTopic2 = "AND p_d_reaction.p_c_topic_id is NULL";
        if ($inQueryTopicIds) {
            $subrequestTopic1 = "AND (p_d_debate.p_c_topic_id is NULL OR p_d_debate.p_c_topic_id IN ($inQueryTopicIds))";
            $subrequestTopic2 = "AND (p_d_reaction.p_c_topic_id is NULL OR p_d_reaction.p_c_topic_id IN ($inQueryTopicIds))";
        }

        // Tag filtering subrequest
        $subRequestTagLeftJoin1 = null;
        $subRequestTagLeftJoin2 = null;
        $subRequestTagLeftJoin3 = null;
        $subRequestTagLeftJoin4 = null;
        $subRequestTagWhere1 = null;
        $subRequestTagWhere2 = null;
        $subRequestTagWhere3 = null;
        $subRequestTagWhere4 = null;
        if ($tagId) {
            $subRequestTagLeftJoin1 = "
    LEFT JOIN p_d_d_tagged_t
        ON p_d_debate.id = p_d_d_tagged_t.p_d_debate_id
";
            $subRequestTagLeftJoin2 = "
    LEFT JOIN p_d_r_tagged_t
        ON p_d_reaction.id = p_d_r_tagged_t.p_d_reaction_id
";
            $subRequestTagLeftJoin3 = "
    LEFT JOIN p_d_d_tagged_t
        ON p_d_d_comment.p_d_debate_id = p_d_d_tagged_t.p_d_debate_id
";
            $subRequestTagLeftJoin4 = "
    LEFT JOIN p_d_r_tagged_t
        ON p_d_r_comment.p_d_reaction_id = p_d_r_tagged_t.p_d_reaction_id
";
            $subRequestTagWhere1 = "AND p_d_d_tagged_t.p_tag_id = $tagId";
            $subRequestTagWhere2 = "AND p_d_r_tagged_t.p_tag_id = $tagId";
            $subRequestTagWhere3 = "AND p_d_d_tagged_t.p_tag_id = $tagId";
            $subRequestTagWhere4 = "AND p_d_r_tagged_t.p_tag_id = $tagId";
        }

        $sql = "
( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.file_name as fileName, p_d_debate.description as description, p_d_debate.slug as slug, p_d_debate.published_at as published_at, p_d_debate.updated_at as updated_at, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
$subRequestTagLeftJoin1
WHERE
    p_user_id = :p_user_id
    AND p_d_debate.published = 1
    AND p_d_debate.online = 1
    $subrequestTopic1
    $subRequestTagWhere1
)

UNION DISTINCT

( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.file_name as fileName, p_d_reaction.description as description, p_d_reaction.slug as slug, p_d_reaction.published_at as published_at, p_d_reaction.updated_at as updated_at, p_d_reaction.note_pos as note_pos, p_d_reaction.note_neg as note_neg, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
$subRequestTagLeftJoin2
WHERE
    p_user_id = :p_user_id2
    AND p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    $subrequestTopic2
    $subRequestTagWhere2
)

UNION DISTINCT

( SELECT p_d_d_comment.id as id, \"commentaire\" as title, \"image\" as fileName, p_d_d_comment.description as description, \"slug\" as slug, p_d_d_comment.published_at as published_at, p_d_d_comment.updated_at as updated_at, p_d_d_comment.note_pos as note_pos, p_d_d_comment.note_neg as note_neg, 'Politizr\\\Model\\\PDDComment' as type
FROM p_d_d_comment
    LEFT JOIN p_d_debate
        ON p_d_d_comment.p_d_debate_id = p_d_debate.id
$subRequestTagLeftJoin3
WHERE
    p_d_d_comment.online = 1
    AND p_d_d_comment.p_user_id = :p_user_id3
    AND p_d_debate.published = 1
    AND p_d_debate.online = 1
    $subrequestTopic1
    $subRequestTagWhere3
)

UNION DISTINCT

( SELECT p_d_r_comment.id as id, \"commentaire\" as title, \"image\" as fileName, p_d_r_comment.description as description, \"slug\" as slug, p_d_r_comment.published_at as published_at, p_d_r_comment.updated_at as updated_at, p_d_r_comment.note_pos as note_pos, p_d_r_comment.note_neg as note_neg, 'Politizr\\\Model\\\PDRComment' as type
FROM p_d_r_comment
    LEFT JOIN p_d_reaction
        ON p_d_r_comment.p_d_reaction_id = p_d_reaction.id
$subRequestTagLeftJoin4
WHERE
    p_d_r_comment.online = 1
    AND p_d_r_comment.p_user_id = :p_user_id4
    AND p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    $subrequestTopic2
    $subRequestTagWhere4
)

$orderByReq

LIMIT :offset, :limit
    ";

        return $sql;
    }


    /**
     * Documents by recommended
     *
     * @see app/sql/topDocuments.sql
     *
     * @return string
     */
    private function createDocumentsByRecommendRawSql($filterDate = null, $month = null, $year = null)
    {
        $subRequestCond1 = '';
        $subRequestCond2 = '';
        if ($filterDate == ListingConstants::FILTER_KEYWORD_LAST_MONTH) {
            $subRequestCond = "AND p_u_reputation.created_at BETWEEN DATE_SUB(NOW(), INTERVAL 1 MONTH) AND NOW()";
        } elseif ($filterDate == ListingConstants::FILTER_KEYWORD_EXACT_MONTH) {
            $subRequestCond = "AND p_u_reputation.created_at BETWEEN LAST_DAY(DATE_SUB('$year-$month-15', INTERVAL 1 MONTH)) AND LAST_DAY('$year-$month-15')";
        }
        // Requête SQL
        $sql = "
( SELECT COUNT(p_d_debate.id) as nb_note_pos, p_d_debate.id as id, p_d_debate.title as title, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, p_d_debate.published_at as published_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_u_reputation
    LEFT JOIN p_d_debate as p_d_debate
        ON p_u_reputation.p_object_id = p_d_debate.id
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1 
    AND p_d_debate.p_c_topic_id is NULL
    AND (p_d_debate.note_pos - p_d_debate.note_neg) > 0
    AND p_u_reputation.p_r_action_id = :id_author_debate_note_pos
    $subRequestCond

GROUP BY id )

UNION DISTINCT

( SELECT COUNT(p_d_reaction.id) as nb_note_pos, p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.note_pos as note_pos, p_d_reaction.note_neg as note_neg, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_u_reputation
    LEFT JOIN p_d_reaction as p_d_reaction
        ON p_u_reputation.p_object_id = p_d_reaction.id
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.p_c_topic_id is NULL
    AND p_d_reaction.tree_level > 0
    AND (p_d_reaction.note_pos - p_d_reaction.note_neg) > 0
    AND p_u_reputation.p_r_action_id = :id_author_reaction_note_pos
    $subRequestCond

GROUP BY id )

ORDER BY nb_note_pos DESC, note_pos DESC, note_neg ASC

LIMIT :offset, :limit
";

        return $sql;
    }
    
    /**
     * Documents by organization id
     *
     * @see app/sql/documentsByOrganization.sql
     *
     * @param integer $orderBy
     * @return string
     */
    private function createDocumentsByOrganizationRawSql($orderBy = null)
    {
        if ($orderBy == ListingConstants::ORDER_BY_KEYWORD_BEST_NOTE) {
            $orderBy = "ORDER BY note_pos DESC, note_neg ASC, published_at DESC";
        } elseif ($orderBy == ListingConstants::ORDER_BY_KEYWORD_LAST) {
            $orderBy = "ORDER BY published_at DESC, note_pos DESC, note_neg ASC";
        } else {
            throw new InconsistentDataException(sprintf('OrderBy keyword %s not found'), $keyword);
        }

        // Requête SQL
        $sql = "
( SELECT DISTINCT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.file_name as fileName, p_d_debate.description as description, p_d_debate.slug as slug, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, p_d_debate.nb_views as nb_views, p_d_debate.published_at as published_at, p_d_debate.updated_at as updated_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1 
    AND p_d_debate.p_c_topic_id is NULL
    AND p_d_debate.p_user_id IN (
        SELECT p_user.id
        FROM p_user
            LEFT JOIN p_u_current_q_o
                ON p_user.id = p_u_current_q_o.p_user_id
        WHERE
            p_user.qualified = 1
            AND p_u_current_q_o.p_q_organization_id = :p_q_organization_id
    )
)

UNION DISTINCT

( SELECT DISTINCT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.file_name as fileName, p_d_reaction.description as description, p_d_reaction.slug as slug, p_d_reaction.note_pos as note_pos, p_d_reaction.note_neg as note_neg, p_d_reaction.nb_views as nb_views, p_d_reaction.published_at as published_at, p_d_reaction.updated_at as updated_at,'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.p_c_topic_id is NULL
    AND p_d_reaction.tree_level > 0
    AND p_d_reaction.p_user_id IN (
        SELECT p_user.id
        FROM p_user
            LEFT JOIN p_u_current_q_o
                ON p_user.id = p_u_current_q_o.p_user_id
        WHERE
            p_user.qualified = 1
            AND p_u_current_q_o.p_q_organization_id = :p_q_organization_id2
    )
)

UNION DISTINCT

# Commentaires débats publiés
( SELECT DISTINCT p_d_d_comment.id as id, \"commentaire\" as title, \"image\" as fileName, p_d_d_comment.description as description, \"slug\" as slug, p_d_d_comment.note_pos as note_pos, p_d_d_comment.note_neg as note_neg, -1 as nb_views, p_d_d_comment.published_at as published_at, p_d_d_comment.updated_at as updated_at, 'Politizr\\\Model\\\PDDComment' as type
FROM p_d_d_comment
    LEFT JOIN p_d_debate
        ON p_d_d_comment.p_d_debate_id = p_d_debate.id
WHERE
    p_d_d_comment.online = 1
    AND p_d_debate.published = 1
    AND p_d_debate.online = 1
    AND p_d_debate.p_c_topic_id is NULL
    AND p_d_d_comment.p_user_id IN (
        SELECT p_user.id
        FROM p_user
            LEFT JOIN p_u_current_q_o
                ON p_user.id = p_u_current_q_o.p_user_id
        WHERE
            p_user.qualified = 1
            AND p_u_current_q_o.p_q_organization_id = :p_q_organization_id3
    )
)

UNION DISTINCT

# Commentaires réactions publiés
( SELECT DISTINCT p_d_r_comment.id as id, \"commentaire\" as title, \"image\" as fileName, p_d_r_comment.description as description, \"slug\" as slug, p_d_r_comment.note_pos as note_pos, p_d_r_comment.note_neg as note_neg, -1 as nb_views, p_d_r_comment.published_at as published_at, p_d_r_comment.updated_at as updated_at, 'Politizr\\\Model\\\PDRComment' as type
FROM p_d_r_comment
    LEFT JOIN p_d_reaction
        ON p_d_r_comment.p_d_reaction_id = p_d_reaction.id
WHERE
    p_d_r_comment.online = 1
    AND p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.p_c_topic_id is NULL
    AND p_d_r_comment.p_user_id IN (
        SELECT p_user.id
        FROM p_user
            LEFT JOIN p_u_current_q_o
                ON p_user.id = p_u_current_q_o.p_user_id
        WHERE
            p_user.qualified = 1
            AND p_u_current_q_o.p_q_organization_id = :p_q_organization_id4
    )
)

$orderBy

LIMIT :offset, :limit
";

        return $sql;
    }
    
    /**
     * Documents by tag ids
     *
     * @see app/sql/documentsByTags.sql
     *
     * @param string $inQueryTagIds
     * @param string $inQueryTopicIds
     * @param integer $orderBy
     * @return string
     */
    private function createDocumentsByTagsRawSql($inQueryTagIds, $inQueryTopicIds, $orderBy = null)
    {
        if ($orderBy == ListingConstants::ORDER_BY_KEYWORD_BEST_NOTE) {
            $orderBy = "ORDER BY note_pos DESC, note_neg ASC, published_at DESC";
        } elseif ($orderBy == ListingConstants::ORDER_BY_KEYWORD_LAST) {
            $orderBy = "ORDER BY published_at DESC, note_pos DESC, note_neg ASC";
        } else {
            throw new InconsistentDataException(sprintf('OrderBy keyword %s not found'), $keyword);
        }

        // Topic subrequest
        $subrequestTopic1 = "AND p_d_debate.p_c_topic_id is NULL";
        $subrequestTopic2 = "AND p_d_reaction.p_c_topic_id is NULL";
        if ($inQueryTopicIds) {
            $subrequestTopic1 = "AND (p_d_debate.p_c_topic_id is NULL OR p_d_debate.p_c_topic_id IN ($inQueryTopicIds))";
            $subrequestTopic2 = "AND (p_d_reaction.p_c_topic_id is NULL OR p_d_reaction.p_c_topic_id IN ($inQueryTopicIds))";
        }

        // Requête SQL
        $sql = "
( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, p_d_debate.published_at as published_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
    LEFT JOIN p_d_d_tagged_t
        ON p_d_debate.id = p_d_d_tagged_t.p_d_debate_id
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1 
    $subrequestTopic1
    AND p_d_d_tagged_t.p_tag_id IN ($inQueryTagIds)
    )

UNION DISTINCT

( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.note_pos as note_pos, p_d_reaction.note_neg as note_neg, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
    LEFT JOIN p_d_r_tagged_t
        ON p_d_reaction.id = p_d_r_tagged_t.p_d_reaction_id
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.tree_level > 0
    $subrequestTopic2
    AND p_d_r_tagged_t.p_tag_id IN ($inQueryTagIds)
    )

$orderBy

LIMIT :offset, :limit
";

        return $sql;
    }
    
    /**
     * Top documents
     *
     * @see app/sql/topDocuments.sql
     *
     * @return string
     */
    private function createTopDocumentsBestNoteRawSql()
    {
        // Requête SQL
        $sql = "
( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, p_d_debate.published_at as published_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1 
    AND p_d_debate.p_c_topic_id is NULL
    AND p_d_debate.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 90 DAY) AND NOW() 
    )

UNION DISTINCT

( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.note_pos as note_pos, p_d_reaction.note_neg as note_neg, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.p_c_topic_id is NULL
    AND p_d_reaction.tree_level > 0
    AND p_d_reaction.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 90 DAY) AND NOW() 
    )

ORDER BY note_pos DESC, note_neg ASC

LIMIT :limit
";

        return $sql;
    }
    
    /**
     * Debates' suggestion for user.
     *
     * @see app/sql/suggestions.sql
     *
     * @return string
     */
    private function createUserSuggestedDebatesRawSql($inQueryDebateIds, $inQueryUserIds)
    {
        // Requête SQL
        $sql = "
SELECT DISTINCT
".ObjectTypeConstants::SQL_P_D_DEBATE_COLUMNS.",
    nb_users
FROM (
( SELECT DISTINCT p_d_debate.*, 0 as nb_users, 1 as unionsorting
FROM p_d_debate
WHERE
    p_d_debate.p_l_city_id = :p_l_city_id
    AND p_d_debate.online = 1
    AND p_d_debate.published = 1
    AND p_d_debate.id NOT IN ($inQueryDebateIds)
    AND p_d_debate.p_user_id NOT IN ($inQueryUserIds)
    AND p_d_debate.p_user_id <> :p_user_id
)

UNION DISTINCT

( SELECT DISTINCT p_d_debate.*, 0 as nb_users, 2 as unionsorting
FROM p_d_debate
WHERE
    p_d_debate.p_l_department_id = :p_l_department_id
    AND p_d_debate.online = 1
    AND p_d_debate.published = 1
    AND p_d_debate.id NOT IN ($inQueryDebateIds)
    AND p_d_debate.p_user_id NOT IN ($inQueryUserIds)
    AND p_d_debate.p_user_id <> :p_user_id2
)

UNION DISTINCT

( SELECT DISTINCT p_d_debate.*, 0 as nb_users, 3 as unionsorting
FROM p_d_debate
WHERE
    p_d_debate.p_l_region_id = :p_l_region_id
    AND p_d_debate.online = 1
    AND p_d_debate.published = 1
    AND p_d_debate.id NOT IN ($inQueryDebateIds)
    AND p_d_debate.p_user_id NOT IN ($inQueryUserIds)
    AND p_d_debate.p_user_id <> :p_user_id3
)

UNION DISTINCT

( SELECT DISTINCT p_d_debate.*, COUNT(p_u_follow_d_d.p_d_debate_id) as nb_users, 4 as unionsorting
FROM p_d_debate
    LEFT JOIN p_u_follow_d_d
        ON p_d_debate.id = p_u_follow_d_d.p_d_debate_id
WHERE
    p_d_debate.online = 1
    AND p_d_debate.published = 1
    AND p_d_debate.id NOT IN ($inQueryDebateIds)
    AND p_d_debate.p_user_id NOT IN ($inQueryUserIds)
    AND p_d_debate.p_user_id <> :p_user_id4
GROUP BY p_d_debate.id
ORDER BY nb_users DESC
)

ORDER BY unionsorting ASC, nb_users DESC, published_at DESC
) unionsorting

LIMIT :limit
";

        return $sql;
    }

   /**
     * User's draft listing
     *
     * @see app/sql/userDocuments.sql
     *
     * @param string $orderBy
     * @return string
     */
    private function createMyDraftsRawSql()
    {
        $sql = "
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.published_at as published_at, p_d_reaction.updated_at as updated_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_user_id = :p_user_id
    AND p_d_reaction.published = 0
    AND p_d_reaction.online = 1
)

UNION DISTINCT

( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.published_at as published_at, p_d_debate.updated_at as updated_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
WHERE
    p_user_id = :p_user_id2
    AND p_d_debate.published = 0
    AND p_d_debate.online = 1
)

ORDER BY updated_at DESC
LIMIT :offset, :limit
    ";

        return $sql;
    }

   /**
     * User's bookmarks listing
     *
     * @see app/sql/userDocuments.sql
     *
     * @param string $orderBy
     * @return string
     */
    private function createMyBookmarksRawSql()
    {
        $sql = "
( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.file_name as fileName, p_d_debate.published_at as published_at, 'Politizr\\\Model\\\PDDebate' as type, p_u_bookmark_d_d.created_at as bookmarked_at
FROM p_d_debate
INNER JOIN p_u_bookmark_d_d ON p_u_bookmark_d_d.p_d_debate_id = p_d_debate.id
WHERE
    p_u_bookmark_d_d.p_user_id = :p_user_id
    AND p_d_debate.published = 1
    AND p_d_debate.online = 1
)

UNION DISTINCT

#  Réactions
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.file_name as fileName, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type, p_u_bookmark_d_r.created_at as bookmarked_at
FROM p_d_reaction
INNER JOIN p_u_bookmark_d_r ON p_u_bookmark_d_r.p_d_reaction_id = p_d_reaction.id
WHERE
    p_u_bookmark_d_r.p_user_id = :p_user_id2
    AND p_d_reaction.published = 1
    AND p_d_reaction.online = 1
)

ORDER BY bookmarked_at DESC

LIMIT :offset, :limit
    ";

        return $sql;
    }

    /**
     * Return number of 1st level reactions for user publications
     *
     * cf sql/badges.sql
     *
     * @return string
     */
    private function createNbUserDocumentReactionsLevel1RawSql()
    {
        $sql = "
SELECT COUNT(*) as nb
FROM
(
# Liste des réactions filles de 1er niveau pour les réactions d un user
SELECT child.id
FROM p_d_reaction parent, p_d_reaction child
WHERE
    parent.p_user_id = :p_user_id
    AND child.p_user_id <> :p_user_id2
    AND child.p_d_debate_id = parent.p_d_debate_id
    AND child.tree_level = parent.tree_level + 1
    AND child.tree_left > parent.tree_left
    AND child.tree_right < parent.tree_right
GROUP BY child.p_d_debate_id

UNION

# Liste des réactions filles de 1er niveau pour les débats d un user
SELECT child.id
FROM p_d_debate parent, p_d_reaction child
WHERE
    parent.p_user_id = :p_user_id3
    AND child.p_user_id <> :p_user_id4
    AND child.p_d_debate_id = parent.id
    AND child.tree_level = 1
GROUP BY child.p_d_debate_id
) x
";

        return $sql;
    }

    /**
     * Return number of 1st level reactions for user publications
     *
     * @return string
     */
    public function createNbUserDebateFirstReaction()
    {
        $sql = "
SELECT id
FROM p_d_reaction 
WHERE 
    p_user_id = :p_user_id
    AND tree_level = 1
    AND tree_left = 2
GROUP BY p_d_debate_id
";

        return $sql;
    }

    /* ######################################################################################################## */
    /*                                            RAW SQL OPERATIONS                                            */
    /* ######################################################################################################## */

    /**
     * Documents homepage listing
     *
     * @param integer $limit
     * @return PropelCollection
     */
    public function generateHomepagePublications($limit)
    {
        // $this->logger->info('*** generateHomepagePublications');

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);

        $stmt = $con->prepare($this->createHomepagePublicationsRawSql($limit));
        $stmt->bindValue(':offset', 0, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $documents = new \PropelCollection();
        $i = 0;
        foreach ($result as $row) {
            $type = $row['type'];

            if ($type == ObjectTypeConstants::TYPE_DEBATE) {
                $document = PDDebateQuery::create()->findPk($row['id']);
            } elseif ($type == ObjectTypeConstants::TYPE_REACTION) {
                $document = PDReactionQuery::create()->findPk($row['id']);
            } else {
                throw new InconsistentDataException(sprintf('Object type %s unknown.', $type));
            }
            
            $documents->set($i, $document);
            $i++;
        }

        return $documents;
    }
    
    /**
     * Documents by filters paginated listing
     *
     * @param string $inQueryTopicIds
     * @param string $inQueryCityIds
     * @param string $inQueryDepartmentIds
     * @param int $regionId
     * @param int $countryId
     * @param int $topicId
     * @param string $filterPublication
     * @param string $filterProfile
     * @param string $filterActivity
     * @param string $filterDate
     * @param integer $offset
     * @param integer $limit
     * @return PropelCollection
     */
    public function generatePublicationsByFiltersPaginated(
        $inQueryTopicIds,
        $inQueryCityIds,
        $inQueryDepartmentIds,
        $regionId,
        $countryId,
        $topicId,
        $filterPublication,
        $filterProfile,
        $filterActivity,
        $filterDate,
        $offset,
        $limit
    ) {
        // $this->logger->info('*** generatePublicationsByFiltersPaginated');
        // $this->logger->info('$inQueryTopicIds = ' . print_r($inQueryTopicIds, true));
        // $this->logger->info('$inQueryCityIds = ' . print_r($inQueryCityIds, true));
        // $this->logger->info('$inQueryDepartmentIds = ' . print_r($inQueryDepartmentIds, true));
        // $this->logger->info('$regionId = ' . print_r($regionId, true));
        // $this->logger->info('$countryId = ' . print_r($countryId, true));
        // $this->logger->info('$topicId = ' . print_r($topicId, true));
        // $this->logger->info('$filterPublication = ' . print_r($filterPublication, true));
        // $this->logger->info('$filterProfile = ' . print_r($filterProfile, true));
        // $this->logger->info('$filterActivity = ' . print_r($filterActivity, true));
        // $this->logger->info('$filterDate = ' . print_r($filterDate, true));
        // $this->logger->info('$offset = ' . print_r($offset, true));
        // $this->logger->info('$limit = ' . print_r($limit, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);

        // special request construction for "most followed" / "most reactions" / "most comments" filters
        if ($filterActivity == ListingConstants::ORDER_BY_KEYWORD_MOST_FOLLOWED) {
            $stmt = $con->prepare($this->createPublicationsByFiltersMostFollowedRawSql($inQueryTopicIds, $inQueryCityIds, $inQueryDepartmentIds, $regionId, $countryId, $topicId, $filterProfile, $filterDate));
        } elseif ($filterActivity == ListingConstants::ORDER_BY_KEYWORD_MOST_REACTIONS) {
            $stmt = $con->prepare($this->createPublicationsByFiltersMostReactionsRawSql($inQueryTopicIds, $inQueryCityIds, $inQueryDepartmentIds, $regionId, $countryId, $topicId, $filterPublication, $filterProfile, $filterDate));
        } elseif ($filterActivity == ListingConstants::ORDER_BY_KEYWORD_MOST_COMMENTS) {
            $stmt = $con->prepare($this->createPublicationsByFiltersMostCommentsRawSql($inQueryTopicIds, $inQueryCityIds, $inQueryDepartmentIds, $regionId, $countryId, $topicId, $filterPublication, $filterProfile, $filterDate));
        } else {
            $stmt = $con->prepare($this->createPublicationsByFiltersRawSql($inQueryTopicIds, $inQueryCityIds, $inQueryDepartmentIds, $regionId, $countryId, $topicId, $filterPublication, $filterProfile, $filterActivity, $filterDate));
        }

        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $publications = $this->globalTools->hydratePublication($result);

        return $publications;
    }
    
    /**
     * My documents paginated listing
     *
     * @param integer $userId
     * @param string $inQueryTopicIds
     * @param integer $orderBy
     * @param integer $tagId
     * @param integer $offset
     * @param integer $limit
     * @return PropelCollection
     */
    public function generatePublicationsByUserPaginated($userId, $inQueryTopicIds, $orderBy, $tagId, $offset, $limit)
    {
        // $this->logger->info('*** generatePublicationsByUserPaginated');
        // $this->logger->info('$userId = ' . print_r($userId, true));
        // $this->logger->info('$inQueryTopicIds = ' . print_r($inQueryTopicIds, true));
        // $this->logger->info('$orderBy = ' . print_r($orderBy, true));
        // $this->logger->info('$tagId = ' . print_r($tagId, true));
        // $this->logger->info('$offset = ' . print_r($offset, true));
        // $this->logger->info('$limit = ' . print_r($limit, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createPublicationsByUserRawSql($inQueryTopicIds, $orderBy, $tagId));

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id2', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id3', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id4', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $publications = $this->globalTools->hydratePublication($result);

        return $publications;
    }
    

    /**
     * Documents by recommend
     *
     * @param string $filterDate
     * @param integer $month
     * @param integer $year
     * @param integer $offset
     * @param integer $limit
     * @return PropelCollection[PDDebate|PDReaction]
     */
    public function generateDocumentsByRecommendPaginated($filterDate, $month, $year, $offset, $limit)
    {
        $this->logger->info('*** generateDocumentsByRecommendPaginated');
        $this->logger->info('$filterDate = ' . print_r($filterDate, true));
        $this->logger->info('$month = ' . print_r($month, true));
        $this->logger->info('$year = ' . print_r($year, true));
        $this->logger->info('$offset = ' . print_r($offset, true));
        $this->logger->info('$limit = ' . print_r($limit, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createDocumentsByRecommendRawSql($filterDate, $month, $year));

        $stmt->bindValue(':id_author_debate_note_pos', ReputationConstants::ACTION_ID_D_AUTHOR_DEBATE_NOTE_POS, \PDO::PARAM_INT);
        $stmt->bindValue(':id_author_reaction_note_pos', ReputationConstants::ACTION_ID_D_AUTHOR_REACTION_NOTE_POS, \PDO::PARAM_INT);

        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $documents = new \PropelCollection();
        $i = 0;
        foreach ($result as $row) {
            $type = $row['type'];

            if ($type == ObjectTypeConstants::TYPE_DEBATE) {
                $document = PDDebateQuery::create()->findPk($row['id']);
            } elseif ($type == ObjectTypeConstants::TYPE_REACTION) {
                $document = PDReactionQuery::create()->findPk($row['id']);
            } else {
                throw new InconsistentDataException(sprintf('Object type %s unknown.', $type));
            }
            
            $documents->set($i, $document);
            $i++;
        }

        return $documents;
    }
    
    /**
     * Documents by organization
     *
     * @param integer $organizationId
     * @param integer $orderBy
     * @param integer $offset
     * @param integer $limit
     * @return PropelCollection
     */
    public function generatePublicationsByOrganizationPaginated($organizationId, $orderBy, $offset, $limit)
    {
        // $this->logger->info('*** generatePublicationsByOrganizationPaginated');
        // $this->logger->info('$organizationId = ' . print_r($organizationId, true));
        // $this->logger->info('$orderBy = ' . print_r($orderBy, true));
        // $this->logger->info('$offset = ' . print_r($offset, true));
        // $this->logger->info('$limit = ' . print_r($limit, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createDocumentsByOrganizationRawSql($orderBy));

        $stmt->bindValue(':p_q_organization_id', $organizationId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_q_organization_id2', $organizationId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_q_organization_id3', $organizationId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_q_organization_id4', $organizationId, \PDO::PARAM_INT);

        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $publications = $this->globalTools->hydratePublication($result);

        return $publications;
    }
    
    /**
     * Documents by tags
     *
     * @param string $inQueryTagIds
     * @param string $inQueryTopicIds
     * @param integer $orderBy
     * @param integer $offset
     * @param integer $limit
     * @return PropelCollection[PDDebate|PDReaction]
     */
    public function generateDocumentsByTagsPaginated($inQueryTagIds, $inQueryTopicIds, $orderBy, $offset, $limit)
    {
        // $this->logger->info('*** generateUserSuggestedDebatesPaginatedListing');
        // $this->logger->info('$inQueryTagIds = ' . print_r($inQueryTagIds, true));
        // $this->logger->info('$inQueryTopicIds = ' . print_r($inQueryTopicIds, true));
        // $this->logger->info('$orderBy = ' . print_r($orderBy, true));
        // $this->logger->info('$offset = ' . print_r($offset, true));
        // $this->logger->info('$limit = ' . print_r($limit, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createDocumentsByTagsRawSql($inQueryTagIds, $inQueryTopicIds, $orderBy));

        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $documents = new \PropelCollection();
        $i = 0;
        foreach ($result as $row) {
            $type = $row['type'];

            if ($type == ObjectTypeConstants::TYPE_DEBATE) {
                $document = PDDebateQuery::create()->findPk($row['id']);
            } elseif ($type == ObjectTypeConstants::TYPE_REACTION) {
                $document = PDReactionQuery::create()->findPk($row['id']);
            } else {
                throw new InconsistentDataException(sprintf('Object type %s unknown.', $type));
            }
            
            $documents->set($i, $document);
            $i++;
        }

        return $documents;
    }
    
    /**
     * Top documents best notes
     *
     * @param int $limit
     * @return PropelCollection[PDDebate|PDReaction]
     */
    public function generateTopDocumentsBestNote($limit)
    {
        // $this->logger->info('*** generateTopDocumentsBestNote');
        // $this->logger->info('$limit = ' . print_r($limit, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createTopDocumentsBestNoteRawSql());

        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $documents = new \PropelCollection();
        $i = 0;
        foreach ($result as $row) {
            $type = $row['type'];

            if ($type == ObjectTypeConstants::TYPE_DEBATE) {
                $document = PDDebateQuery::create()->findPk($row['id']);
            } elseif ($type == ObjectTypeConstants::TYPE_REACTION) {
                $document = PDReactionQuery::create()->findPk($row['id']);
            } else {
                throw new InconsistentDataException(sprintf('Object type %s unknown.', $type));
            }
            
            $documents->set($i, $document);
            $i++;
        }

        return $documents;
    }
    
    /**
     * User's debates' suggestions paginated listing
     *
     * @param integer $userId
     * @param string $cityId
     * @param string $departmentId
     * @param string $regionId
     * @param string $inQueryDebateIds
     * @param string $inQueryUserIds
     * @param int $limit
     * @return PropelCollection[PDDebate]
     */
    public function generateUserDocumentsSuggestion($userId, $cityId, $departmentId, $regionId, $inQueryDebateIds, $inQueryUserIds, $limit)
    {
        // $this->logger->info('*** generateUserDocumentsSuggestion');
        // $this->logger->info('$userId = ' . print_r($userId, true));
        // $this->logger->info('$cityId = ' . print_r($cityId, true));
        // $this->logger->info('$departmentId = ' . print_r($departmentId, true));
        // $this->logger->info('$regionId = ' . print_r($regionId, true));
        // $this->logger->info('$debateIds = ' . print_r($inQueryDebateIds, true));
        // $this->logger->info('$userIds = ' . print_r($inQueryUserIds, true));
        // $this->logger->info('$limit = ' . print_r($limit, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createUserSuggestedDebatesRawSql($inQueryDebateIds, $inQueryUserIds));

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id2', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id3', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id4', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_l_city_id', $cityId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_l_department_id', $departmentId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_l_region_id', $regionId, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $documents = new \PropelCollection();
        foreach ($result as $row) {
            $debate = new PDDebate();
            $debate->hydrate($row);

            $documents->append($debate);
        }

        return $documents;
    }
    
    /**
     * Last 10 publicated documents
     *
     * @param integer $userId
     * @param int $limit
     * @return PropelCollection[PDDebate]
     */
    public function generateDocumentsLastPublished($limit)
    {
        // $this->logger->info('*** generateDocumentsLastPublished');
        // $this->logger->info('$limit = ' . print_r($limit, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createDocumentsLastPublishedRawSql());

        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $documents = new \PropelCollection();
        $i = 0;
        foreach ($result as $row) {
            $type = $row['type'];

            if ($type == ObjectTypeConstants::TYPE_DEBATE) {
                $document = PDDebateQuery::create()->findPk($row['id']);
            } elseif ($type == ObjectTypeConstants::TYPE_REACTION) {
                $document = PDReactionQuery::create()->findPk($row['id']);
            } else {
                throw new InconsistentDataException(sprintf('Object type %s unknown.', $type));
            }
            
            $documents->set($i, $document);
            $i++;
        }

        return $documents;
    }
    
    /**
     * My documents paginated listing
     *
     * @param integer $userId
     * @param integer $published
     * @param integer $offset
     * @param integer $limit
     * @return PropelCollection
     */
    public function generateMyDraftsPaginatedListing($userId, $offset, $limit)
    {
        // $this->logger->info('*** generateMyDraftsPaginatedListing');
        // $this->logger->info('$userId = ' . print_r($userId, true));
        // $this->logger->info('$offset = ' . print_r($offset, true));
        // $this->logger->info('$limit = ' . print_r($limit, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createMyDraftsRawSql());

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id2', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $documents = new \PropelCollection();
        $i = 0;
        foreach ($result as $row) {
            $type = $row['type'];

            if ($type == ObjectTypeConstants::TYPE_DEBATE) {
                $document = PDDebateQuery::create()->findPk($row['id']);
            } elseif ($type == ObjectTypeConstants::TYPE_REACTION) {
                $document = PDReactionQuery::create()->findPk($row['id']);
            } else {
                throw new InconsistentDataException(sprintf('Object type %s unknown.', $type));
            }
            
            $documents->set($i, $document);
            $i++;
        }

        return $documents;
    }
    
    /**
     * My documents paginated listing
     *
     * @param integer $userId
     * @param integer $published
     * @param integer $offset
     * @param integer $limit
     * @return PropelCollection
     */
    public function generateMyBookmarksPaginatedListing($userId, $offset, $limit)
    {
        // $this->logger->info('*** generateMyBookmarksPaginatedListing');
        // $this->logger->info('$userId = ' . print_r($userId, true));
        // $this->logger->info('$offset = ' . print_r($offset, true));
        // $this->logger->info('$limit = ' . print_r($limit, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createMyBookmarksRawSql());

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id2', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, \PDO::PARAM_INT);
        $stmt->bindValue(':limit', $limit, \PDO::PARAM_INT);

        $stmt->execute();

        $result = $stmt->fetchAll();

        $documents = new \PropelCollection();
        $i = 0;
        foreach ($result as $row) {
            $type = $row['type'];

            if ($type == ObjectTypeConstants::TYPE_DEBATE) {
                $document = PDDebateQuery::create()->findPk($row['id']);
            } elseif ($type == ObjectTypeConstants::TYPE_REACTION) {
                $document = PDReactionQuery::create()->findPk($row['id']);
            } else {
                throw new InconsistentDataException(sprintf('Object type %s unknown.', $type));
            }
            
            $documents->set($i, $document);
            $i++;
        }

        return $documents;
    }
    
    /**
     * Return number of 1st level reactions for user publications
     *
     * @param int $userId
     * @return int
     */
    public function generateNbUserDocumentReactionsLevel1($userId)
    {
        // $this->logger->info('*** generateNbUserDocumentReactionsLevel1');
        // $this->logger->info('$userId = ' . print_r($userId, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createNbUserDocumentReactionsLevel1RawSql());

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id2', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id3', $userId, \PDO::PARAM_INT);
        $stmt->bindValue(':p_user_id4', $userId, \PDO::PARAM_INT);

        $stmt->execute();
        $result = $stmt->fetchAll();

        $count = 0;
        if (isset($result[0]['nb'])) {
            $count = $result[0]['nb'];
        }

        return $count;
    }

    /**
     * Return number of debates' reactions written first by userId
     *
     * @param int $userId
     * @return int
     */
    public function generateNbUserDebateFirstReaction($userId)
    {
        // $this->logger->info('*** generateNbUserDebateFirstReaction');
        // $this->logger->info('$userId = ' . print_r($userId, true));

        $con = \Propel::getConnection('default', \Propel::CONNECTION_READ);
        $stmt = $con->prepare($this->createNbUserDebateFirstReaction());

        $stmt->bindValue(':p_user_id', $userId, \PDO::PARAM_INT);

        $stmt->execute();
        $result = $stmt->fetchAll();

        $count = count($result);

        return $count;
    }
    
    /* ######################################################################################################## */
    /*                                            CRUD OPERATIONS                                               */
    /* ######################################################################################################## */

    /**
     * Create a new PDDebate associated with userId
     *
     * @param int $userId
     * @return PDDebate
     */
    public function createDebate($userId)
    {
        $debate = new PDDebate();
        
        $debate->setPUserId($userId);

        $debate->setNotePos(0);
        $debate->setNoteNeg(0);

        $debate->setOnline(true);
        $debate->setPublished(false);
        
        $debate->save();

        return $debate;
    }

    /**
     * Publish debate
     *
     * @param PDDebate $debate
     * @return PDDebate
     */
    public function publishDebate(PDDebate $debate)
    {
        if ($debate) {
            $description = $this->globalTools->removeEmptyParagraphs($debate->getDescription());

            $debate->setDescription($description);
            $debate->setPublished(true);
            $debate->setPublishedAt(time());

            $debate->save();
        }

        return $debate;
    }

    /**
     * Delete debate
     *
     * @param PDDebate $debate
     * @return integer
     */
    public function deleteDebate(PDDebate $debate)
    {
        $result = $debate->delete();

        return $result;
    }

    /**
     * Create a new PDReaction associated with userId, debateId and optionnaly parentId
     *
     * @param int $userId
     * @param int $debateId
     * @param int $parentId
     * @param int $topicId
     * @return PDReaction
     */
    public function createReaction($userId, $debateId, $parentId = null, $topicId = null)
    {
        $reaction = new PDReaction();

        $reaction->setPDDebateId($debateId);
        
        $reaction->setPUserId($userId);

        $reaction->setNotePos(0);
        $reaction->setNoteNeg(0);
        
        $reaction->setOnline(true);
        $reaction->setPublished(false);

        $reaction->setParentReactionId($parentId);

        $reaction->setPCTopicId($topicId);

        $reaction->save();

        return $reaction;
    }

    /**
     * Init reaction's tags by default = parent's family ones
     *
     * @param PDReaction $reaction
     * @return PDReaction
     */
    public function initReactionTaggedTags(PDReaction $reaction)
    {
        $parentReactionId = $reaction->getParentReactionId();
        if ($parentReactionId) {
            $parent = PDReactionQuery::create()->findPk($parentReactionId);
        } else {
            $parent = $reaction->getDebate();
        }

        // Init private & family tags
        $tags = $parent->getTags([TagConstants::TAG_TYPE_PRIVATE, TagConstants::TAG_TYPE_FAMILY]);
        foreach ($tags as $tag) {
            $this->tagManager->createReactionTag($reaction->getId(), $tag->getId());
        }

        return $reaction;
    }

    /**
     * Publish reaction w. relative nested set update
     *
     * @param PDReaction $reaction
     * @return PDReaction
     */
    public function publishReaction(PDReaction $reaction)
    {
        if ($reaction) {
            // get associated debate
            $debate = PDDebateQuery::create()->findPk($reaction->getPDDebateId());
            if (!$debate) {
                throw new InconsistentDataException('Debate n°'.$debateId.' not found.');
            }

            // nested set management
            if ($parentId = $reaction->getParentReactionId()) {
                $parent = PDReactionQuery::create()->findPk($parentId);
                if (!$parent) {
                    throw new InconsistentDataException('Parent reaction n°'.$parentId.' not found.');
                }
                $reaction->insertAsLastChildOf($parent);
            } else {
                $rootNode = PDReactionQuery::create()->findRoot($debate->getId());
                if (!$rootNode) {
                    $rootNode = $this->createReactionRootNode($debate->getId());
                }

                if ($nbReactions = $debate->countReactions() == 0) {
                    $reaction->insertAsFirstChildOf($rootNode);
                } else {
                    $reaction->insertAsNextSiblingOf($debate->getLastPublishedReaction(1));
                }
            }

            $description = $this->globalTools->removeEmptyParagraphs($reaction->getDescription());

            $reaction->setDescription($description);

            $reaction->setOnline(true);
            $reaction->setPublished(true);
            $reaction->setPublishedAt(time());
            $reaction->save();
        }

        return $reaction;
    }

    /**
     * Reaction nested set root node
     *
     * @param integer $debateId
     * @return PDReaction
     */
    public function createReactionRootNode($debateId)
    {
        $rootNode = new PDReaction();

        $rootNode->setPDDebateId($debateId);
        $rootNode->setTitle('ROOT NODE');
        $rootNode->setOnline(false);
        $rootNode->setPublished(false);

        $rootNode->makeRoot();
        $rootNode->save();

        return $rootNode;
    }

    /**
     * Delete reaction
     *
     * @param PDDebate $reaction
     * @return integer
     */
    public function deleteReaction(PDReaction $reaction)
    {
        $result = $reaction->delete();

        return $result;
    }

    /**
     * Create a new PDMedia
     *
     * @param int $debateId
     * @param int $reactionId
     * @param string $path
     * @param string $fileName
     * @param string $extension
     * @param int $size
     * @param int $width
     * @param int $height
     * @return PDMedia
     */
    public function createMedia($debateId, $reactionId, $path, $fileName, $extension, $size, $width, $height)
    {
        $media = new PDMedia();

        $media->setPDDebateId($debateId);
        $media->setPDReactionId($reactionId);
        $media->setPath($path);
        $media->setFileName($fileName);
        $media->setExtension($extension);
        $media->setSize($size);
        $media->setWidth($width);
        $media->setHeight($height);

        $media->save();

        return $media;
    }

    /**
     * Create an archive of input debate
     *
     * @param PDDebate $debate
     * @return PMDebateHistoric
     */
    public function createDebateArchive(PDDebate $debate) {
        if ($debate == null) {
            return null;
        }

        $mDebate = new PMDebateHistoric();

        $mDebate->setPUserId($debate->getPUserId());
        $mDebate->setPDDebateId($debate->getId());
        $mDebate->setPObjectId($debate->getId());
        $mDebate->setTitle($debate->getTitle());
        $mDebate->setDescription($debate->getDescription());
        $mDebate->setCopyright($debate->getCopyright());

        // File copy
        // @deprecated w. Media management
        if ($debate->getFileName()) {
            $destFileName = $this->globalTools->copyFile(
                $this->kernel->getRootDir() .
                PathConstants::KERNEL_PATH_TO_WEB .
                PathConstants::DEBATE_UPLOAD_WEB_PATH .
                $debate->getFileName()
            );
            $mDebate->setFileName($destFileName);
        }

        $mDebate->save();

        return $mDebate;
    }

    /**
     * Create an archive of input reaction
     *
     * @param PDReaction $reaction
     * @return PMReactionHistoric
     */
    public function createReactionArchive(PDReaction $reaction) {
        if ($reaction == null) {
            return null;
        }

        $mReaction = new PMReactionHistoric();

        $mReaction->setPUserId($reaction->getPUserId());
        $mReaction->setPDReactionId($reaction->getId());
        $mReaction->setPObjectId($reaction->getId());
        $mReaction->setTitle($reaction->getTitle());
        $mReaction->setDescription($reaction->getDescription());
        $mReaction->setCopyright($reaction->getCopyright());

        // File copy
        // @deprecated w. Media management
        if ($reaction->getFileName()) {
            $destFileName = $this->globalTools->copyFile(
                $this->kernel->getRootDir() .
                PathConstants::KERNEL_PATH_TO_WEB .
                PathConstants::REACTION_UPLOAD_WEB_PATH .
                $reaction->getFileName()
            );
            $mReaction->setFileName($destFileName);
        }

        $mReaction->save();

        return $mReaction;
    }

    /**
     * Create an archive of input comment
     *
     * @param PDDComment $comment
     * @return PMDCommentHistoric
     */
    public function createDCommentArchive(PDDComment $comment) {
        if ($comment == null) {
            return null;
        }

        $mComment = new PMDCommentHistoric();

        $mComment->setPUserId($comment->getPUserId());
        $mComment->setPDDCommentId($comment->getId());
        $mComment->setPObjectId($comment->getId());
        $mComment->setDescription($comment->getDescription());

        $mComment->save();

        return $mComment;
    }

    /**
     * Create an archive of input comment
     *
     * @param PDRComment $comment
     * @return PMDCommentHistoric
     */
    public function createRCommentArchive(PDRComment $comment) {
        if ($comment == null) {
            return null;
        }

        $mComment = new PMRCommentHistoric();

        $mComment->setPUserId($comment->getPUserId());
        $mComment->setPDRCommentId($comment->getId());
        $mComment->setPObjectId($comment->getId());
        $mComment->setDescription($comment->getDescription());

        $mComment->save();

        return $mComment;
    }
}
