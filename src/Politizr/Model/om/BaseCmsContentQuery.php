<?php

namespace Politizr\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \ModelJoin;
use \PDO;
use \Propel;
use \PropelCollection;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Politizr\Model\CmsCategory;
use Politizr\Model\CmsContent;
use Politizr\Model\CmsContentPeer;
use Politizr\Model\CmsContentQuery;

/**
 * @method CmsContentQuery orderById($order = Criteria::ASC) Order by the id column
 * @method CmsContentQuery orderByCmsCategoryId($order = Criteria::ASC) Order by the cms_category_id column
 * @method CmsContentQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method CmsContentQuery orderBySummary($order = Criteria::ASC) Order by the summary column
 * @method CmsContentQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method CmsContentQuery orderByMoreInfoTitle($order = Criteria::ASC) Order by the more_info_title column
 * @method CmsContentQuery orderByMoreInfoDescription($order = Criteria::ASC) Order by the more_info_description column
 * @method CmsContentQuery orderByUrlEmbedVideo($order = Criteria::ASC) Order by the url_embed_video column
 * @method CmsContentQuery orderByOnline($order = Criteria::ASC) Order by the online column
 * @method CmsContentQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 * @method CmsContentQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method CmsContentQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method CmsContentQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 *
 * @method CmsContentQuery groupById() Group by the id column
 * @method CmsContentQuery groupByCmsCategoryId() Group by the cms_category_id column
 * @method CmsContentQuery groupByTitle() Group by the title column
 * @method CmsContentQuery groupBySummary() Group by the summary column
 * @method CmsContentQuery groupByDescription() Group by the description column
 * @method CmsContentQuery groupByMoreInfoTitle() Group by the more_info_title column
 * @method CmsContentQuery groupByMoreInfoDescription() Group by the more_info_description column
 * @method CmsContentQuery groupByUrlEmbedVideo() Group by the url_embed_video column
 * @method CmsContentQuery groupByOnline() Group by the online column
 * @method CmsContentQuery groupBySortableRank() Group by the sortable_rank column
 * @method CmsContentQuery groupByCreatedAt() Group by the created_at column
 * @method CmsContentQuery groupByUpdatedAt() Group by the updated_at column
 * @method CmsContentQuery groupBySlug() Group by the slug column
 *
 * @method CmsContentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method CmsContentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method CmsContentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method CmsContentQuery leftJoinCmsCategory($relationAlias = null) Adds a LEFT JOIN clause to the query using the CmsCategory relation
 * @method CmsContentQuery rightJoinCmsCategory($relationAlias = null) Adds a RIGHT JOIN clause to the query using the CmsCategory relation
 * @method CmsContentQuery innerJoinCmsCategory($relationAlias = null) Adds a INNER JOIN clause to the query using the CmsCategory relation
 *
 * @method CmsContent findOne(PropelPDO $con = null) Return the first CmsContent matching the query
 * @method CmsContent findOneOrCreate(PropelPDO $con = null) Return the first CmsContent matching the query, or a new CmsContent object populated from the query conditions when no match is found
 *
 * @method CmsContent findOneByCmsCategoryId(int $cms_category_id) Return the first CmsContent filtered by the cms_category_id column
 * @method CmsContent findOneByTitle(string $title) Return the first CmsContent filtered by the title column
 * @method CmsContent findOneBySummary(string $summary) Return the first CmsContent filtered by the summary column
 * @method CmsContent findOneByDescription(string $description) Return the first CmsContent filtered by the description column
 * @method CmsContent findOneByMoreInfoTitle(string $more_info_title) Return the first CmsContent filtered by the more_info_title column
 * @method CmsContent findOneByMoreInfoDescription(string $more_info_description) Return the first CmsContent filtered by the more_info_description column
 * @method CmsContent findOneByUrlEmbedVideo(string $url_embed_video) Return the first CmsContent filtered by the url_embed_video column
 * @method CmsContent findOneByOnline(boolean $online) Return the first CmsContent filtered by the online column
 * @method CmsContent findOneBySortableRank(int $sortable_rank) Return the first CmsContent filtered by the sortable_rank column
 * @method CmsContent findOneByCreatedAt(string $created_at) Return the first CmsContent filtered by the created_at column
 * @method CmsContent findOneByUpdatedAt(string $updated_at) Return the first CmsContent filtered by the updated_at column
 * @method CmsContent findOneBySlug(string $slug) Return the first CmsContent filtered by the slug column
 *
 * @method array findById(int $id) Return CmsContent objects filtered by the id column
 * @method array findByCmsCategoryId(int $cms_category_id) Return CmsContent objects filtered by the cms_category_id column
 * @method array findByTitle(string $title) Return CmsContent objects filtered by the title column
 * @method array findBySummary(string $summary) Return CmsContent objects filtered by the summary column
 * @method array findByDescription(string $description) Return CmsContent objects filtered by the description column
 * @method array findByMoreInfoTitle(string $more_info_title) Return CmsContent objects filtered by the more_info_title column
 * @method array findByMoreInfoDescription(string $more_info_description) Return CmsContent objects filtered by the more_info_description column
 * @method array findByUrlEmbedVideo(string $url_embed_video) Return CmsContent objects filtered by the url_embed_video column
 * @method array findByOnline(boolean $online) Return CmsContent objects filtered by the online column
 * @method array findBySortableRank(int $sortable_rank) Return CmsContent objects filtered by the sortable_rank column
 * @method array findByCreatedAt(string $created_at) Return CmsContent objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return CmsContent objects filtered by the updated_at column
 * @method array findBySlug(string $slug) Return CmsContent objects filtered by the slug column
 */
abstract class BaseCmsContentQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseCmsContentQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = null, $modelName = null, $modelAlias = null)
    {
        if (null === $dbName) {
            $dbName = 'default';
        }
        if (null === $modelName) {
            $modelName = 'Politizr\\Model\\CmsContent';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new CmsContentQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   CmsContentQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return CmsContentQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof CmsContentQuery) {
            return $criteria;
        }
        $query = new CmsContentQuery(null, null, $modelAlias);

        if ($criteria instanceof Criteria) {
            $query->mergeWith($criteria);
        }

        return $query;
    }

    /**
     * Find object by primary key.
     * Propel uses the instance pool to skip the database if the object exists.
     * Go fast if the query is untouched.
     *
     * <code>
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   CmsContent|CmsContent[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = CmsContentPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(CmsContentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        if ($this->formatter || $this->modelAlias || $this->with || $this->select
         || $this->selectColumns || $this->asColumns || $this->selectModifiers
         || $this->map || $this->having || $this->joins) {
            return $this->findPkComplex($key, $con);
        } else {
            return $this->findPkSimple($key, $con);
        }
    }

    /**
     * Alias of findPk to use instance pooling
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 CmsContent A model object, or null if the key is not found
     * @throws PropelException
     */
     public function findOneById($key, $con = null)
     {
        return $this->findPk($key, $con);
     }

    /**
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 CmsContent A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `cms_category_id`, `title`, `summary`, `description`, `more_info_title`, `more_info_description`, `url_embed_video`, `online`, `sortable_rank`, `created_at`, `updated_at`, `slug` FROM `cms_content` WHERE `id` = :p0';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key, PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new CmsContent();
            $obj->hydrate($row);
            CmsContentPeer::addInstanceToPool($obj, (string) $key);
        }
        $stmt->closeCursor();

        return $obj;
    }

    /**
     * Find object by primary key.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return CmsContent|CmsContent[]|mixed the result, formatted by the current formatter
     */
    protected function findPkComplex($key, $con)
    {
        // As the query uses a PK condition, no limit(1) is necessary.
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKey($key)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->formatOne($stmt);
    }

    /**
     * Find objects by primary key
     * <code>
     * $objs = $c->findPks(array(12, 56, 832), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|CmsContent[]|mixed the list of results, formatted by the current formatter
     */
    public function findPks($keys, $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection($this->getDbName(), Propel::CONNECTION_READ);
        }
        $this->basePreSelect($con);
        $criteria = $this->isKeepQuery() ? clone $this : $this;
        $stmt = $criteria
            ->filterByPrimaryKeys($keys)
            ->doSelect($con);

        return $criteria->getFormatter()->init($criteria)->format($stmt);
    }

    /**
     * Filter the query by primary key
     *
     * @param     mixed $key Primary key to use for the query
     *
     * @return CmsContentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(CmsContentPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return CmsContentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(CmsContentPeer::ID, $keys, Criteria::IN);
    }

    /**
     * Filter the query on the id column
     *
     * Example usage:
     * <code>
     * $query->filterById(1234); // WHERE id = 1234
     * $query->filterById(array(12, 34)); // WHERE id IN (12, 34)
     * $query->filterById(array('min' => 12)); // WHERE id >= 12
     * $query->filterById(array('max' => 12)); // WHERE id <= 12
     * </code>
     *
     * @param     mixed $id The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CmsContentQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(CmsContentPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(CmsContentPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CmsContentPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the cms_category_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCmsCategoryId(1234); // WHERE cms_category_id = 1234
     * $query->filterByCmsCategoryId(array(12, 34)); // WHERE cms_category_id IN (12, 34)
     * $query->filterByCmsCategoryId(array('min' => 12)); // WHERE cms_category_id >= 12
     * $query->filterByCmsCategoryId(array('max' => 12)); // WHERE cms_category_id <= 12
     * </code>
     *
     * @see       filterByCmsCategory()
     *
     * @param     mixed $cmsCategoryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CmsContentQuery The current query, for fluid interface
     */
    public function filterByCmsCategoryId($cmsCategoryId = null, $comparison = null)
    {
        if (is_array($cmsCategoryId)) {
            $useMinMax = false;
            if (isset($cmsCategoryId['min'])) {
                $this->addUsingAlias(CmsContentPeer::CMS_CATEGORY_ID, $cmsCategoryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($cmsCategoryId['max'])) {
                $this->addUsingAlias(CmsContentPeer::CMS_CATEGORY_ID, $cmsCategoryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CmsContentPeer::CMS_CATEGORY_ID, $cmsCategoryId, $comparison);
    }

    /**
     * Filter the query on the title column
     *
     * Example usage:
     * <code>
     * $query->filterByTitle('fooValue');   // WHERE title = 'fooValue'
     * $query->filterByTitle('%fooValue%'); // WHERE title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $title The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CmsContentQuery The current query, for fluid interface
     */
    public function filterByTitle($title = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($title)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $title)) {
                $title = str_replace('*', '%', $title);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CmsContentPeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the summary column
     *
     * Example usage:
     * <code>
     * $query->filterBySummary('fooValue');   // WHERE summary = 'fooValue'
     * $query->filterBySummary('%fooValue%'); // WHERE summary LIKE '%fooValue%'
     * </code>
     *
     * @param     string $summary The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CmsContentQuery The current query, for fluid interface
     */
    public function filterBySummary($summary = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($summary)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $summary)) {
                $summary = str_replace('*', '%', $summary);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CmsContentPeer::SUMMARY, $summary, $comparison);
    }

    /**
     * Filter the query on the description column
     *
     * Example usage:
     * <code>
     * $query->filterByDescription('fooValue');   // WHERE description = 'fooValue'
     * $query->filterByDescription('%fooValue%'); // WHERE description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $description The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CmsContentQuery The current query, for fluid interface
     */
    public function filterByDescription($description = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($description)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $description)) {
                $description = str_replace('*', '%', $description);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CmsContentPeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the more_info_title column
     *
     * Example usage:
     * <code>
     * $query->filterByMoreInfoTitle('fooValue');   // WHERE more_info_title = 'fooValue'
     * $query->filterByMoreInfoTitle('%fooValue%'); // WHERE more_info_title LIKE '%fooValue%'
     * </code>
     *
     * @param     string $moreInfoTitle The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CmsContentQuery The current query, for fluid interface
     */
    public function filterByMoreInfoTitle($moreInfoTitle = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($moreInfoTitle)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $moreInfoTitle)) {
                $moreInfoTitle = str_replace('*', '%', $moreInfoTitle);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CmsContentPeer::MORE_INFO_TITLE, $moreInfoTitle, $comparison);
    }

    /**
     * Filter the query on the more_info_description column
     *
     * Example usage:
     * <code>
     * $query->filterByMoreInfoDescription('fooValue');   // WHERE more_info_description = 'fooValue'
     * $query->filterByMoreInfoDescription('%fooValue%'); // WHERE more_info_description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $moreInfoDescription The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CmsContentQuery The current query, for fluid interface
     */
    public function filterByMoreInfoDescription($moreInfoDescription = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($moreInfoDescription)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $moreInfoDescription)) {
                $moreInfoDescription = str_replace('*', '%', $moreInfoDescription);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CmsContentPeer::MORE_INFO_DESCRIPTION, $moreInfoDescription, $comparison);
    }

    /**
     * Filter the query on the url_embed_video column
     *
     * Example usage:
     * <code>
     * $query->filterByUrlEmbedVideo('fooValue');   // WHERE url_embed_video = 'fooValue'
     * $query->filterByUrlEmbedVideo('%fooValue%'); // WHERE url_embed_video LIKE '%fooValue%'
     * </code>
     *
     * @param     string $urlEmbedVideo The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CmsContentQuery The current query, for fluid interface
     */
    public function filterByUrlEmbedVideo($urlEmbedVideo = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($urlEmbedVideo)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $urlEmbedVideo)) {
                $urlEmbedVideo = str_replace('*', '%', $urlEmbedVideo);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CmsContentPeer::URL_EMBED_VIDEO, $urlEmbedVideo, $comparison);
    }

    /**
     * Filter the query on the online column
     *
     * Example usage:
     * <code>
     * $query->filterByOnline(true); // WHERE online = true
     * $query->filterByOnline('yes'); // WHERE online = true
     * </code>
     *
     * @param     boolean|string $online The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CmsContentQuery The current query, for fluid interface
     */
    public function filterByOnline($online = null, $comparison = null)
    {
        if (is_string($online)) {
            $online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(CmsContentPeer::ONLINE, $online, $comparison);
    }

    /**
     * Filter the query on the sortable_rank column
     *
     * Example usage:
     * <code>
     * $query->filterBySortableRank(1234); // WHERE sortable_rank = 1234
     * $query->filterBySortableRank(array(12, 34)); // WHERE sortable_rank IN (12, 34)
     * $query->filterBySortableRank(array('min' => 12)); // WHERE sortable_rank >= 12
     * $query->filterBySortableRank(array('max' => 12)); // WHERE sortable_rank <= 12
     * </code>
     *
     * @param     mixed $sortableRank The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CmsContentQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(CmsContentPeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(CmsContentPeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CmsContentPeer::SORTABLE_RANK, $sortableRank, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $createdAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CmsContentQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(CmsContentPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(CmsContentPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CmsContentPeer::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $updatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CmsContentQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(CmsContentPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(CmsContentPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(CmsContentPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the slug column
     *
     * Example usage:
     * <code>
     * $query->filterBySlug('fooValue');   // WHERE slug = 'fooValue'
     * $query->filterBySlug('%fooValue%'); // WHERE slug LIKE '%fooValue%'
     * </code>
     *
     * @param     string $slug The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return CmsContentQuery The current query, for fluid interface
     */
    public function filterBySlug($slug = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($slug)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $slug)) {
                $slug = str_replace('*', '%', $slug);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(CmsContentPeer::SLUG, $slug, $comparison);
    }

    /**
     * Filter the query by a related CmsCategory object
     *
     * @param   CmsCategory|PropelObjectCollection $cmsCategory The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 CmsContentQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByCmsCategory($cmsCategory, $comparison = null)
    {
        if ($cmsCategory instanceof CmsCategory) {
            return $this
                ->addUsingAlias(CmsContentPeer::CMS_CATEGORY_ID, $cmsCategory->getId(), $comparison);
        } elseif ($cmsCategory instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(CmsContentPeer::CMS_CATEGORY_ID, $cmsCategory->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByCmsCategory() only accepts arguments of type CmsCategory or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the CmsCategory relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return CmsContentQuery The current query, for fluid interface
     */
    public function joinCmsCategory($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('CmsCategory');

        // create a ModelJoin object for this join
        $join = new ModelJoin();
        $join->setJoinType($joinType);
        $join->setRelationMap($relationMap, $this->useAliasInSQL ? $this->getModelAlias() : null, $relationAlias);
        if ($previousJoin = $this->getPreviousJoin()) {
            $join->setPreviousJoin($previousJoin);
        }

        // add the ModelJoin to the current object
        if ($relationAlias) {
            $this->addAlias($relationAlias, $relationMap->getRightTable()->getName());
            $this->addJoinObject($join, $relationAlias);
        } else {
            $this->addJoinObject($join, 'CmsCategory');
        }

        return $this;
    }

    /**
     * Use the CmsCategory relation CmsCategory object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\CmsCategoryQuery A secondary query class using the current class as primary query
     */
    public function useCmsCategoryQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinCmsCategory($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'CmsCategory', '\Politizr\Model\CmsCategoryQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   CmsContent $cmsContent Object to remove from the list of results
     *
     * @return CmsContentQuery The current query, for fluid interface
     */
    public function prune($cmsContent = null)
    {
        if ($cmsContent) {
            $this->addUsingAlias(CmsContentPeer::ID, $cmsContent->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // sortable behavior

    /**
     * Returns the objects in a certain list, from the list scope
     *
     * @param int $scope Scope to determine which objects node to return
     *
     * @return CmsContentQuery The current query, for fluid interface
     */
    public function inList($scope = null)
    {

        CmsContentPeer::sortableApplyScopeCriteria($this, $scope, 'addUsingAlias');

        return $this;
    }

    /**
     * Filter the query based on a rank in the list
     *
     * @param     integer   $rank rank
     * @param int $scope Scope to determine which objects node to return

     *
     * @return    CmsContentQuery The current query, for fluid interface
     */
    public function filterByRank($rank, $scope = null)
    {


        return $this
            ->inList($scope)
            ->addUsingAlias(CmsContentPeer::RANK_COL, $rank, Criteria::EQUAL);
    }

    /**
     * Order the query based on the rank in the list.
     * Using the default $order, returns the item with the lowest rank first
     *
     * @param     string $order either Criteria::ASC (default) or Criteria::DESC
     *
     * @return    CmsContentQuery The current query, for fluid interface
     */
    public function orderByRank($order = Criteria::ASC)
    {
        $order = strtoupper($order);
        switch ($order) {
            case Criteria::ASC:
                return $this->addAscendingOrderByColumn($this->getAliasedColName(CmsContentPeer::RANK_COL));
                break;
            case Criteria::DESC:
                return $this->addDescendingOrderByColumn($this->getAliasedColName(CmsContentPeer::RANK_COL));
                break;
            default:
                throw new PropelException('CmsContentQuery::orderBy() only accepts "asc" or "desc" as argument');
        }
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param int $scope Scope to determine which objects node to return
     * @param     PropelPDO $con optional connection
     *
     * @return    CmsContent
     */
    public function findOneByRank($rank, $scope = null, PropelPDO $con = null)
    {

        return $this
            ->filterByRank($rank, $scope)
            ->findOne($con);
    }

    /**
     * Returns a list of objects
     *
     * @param int $scope Scope to determine which objects node to return

     * @param      PropelPDO $con	Connection to use.
     *
     * @return     mixed the list of results, formatted by the current formatter
     */
    public function findList($scope = null, $con = null)
    {


        return $this
            ->inList($scope)
            ->orderByRank()
            ->find($con);
    }

    /**
     * Get the highest rank
     *
     * @param int $scope Scope to determine which objects node to return

     * @param     PropelPDO optional connection
     *
     * @return    integer highest position
     */
    public function getMaxRank($scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(CmsContentPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . CmsContentPeer::RANK_COL . ')');

        CmsContentPeer::sortableApplyScopeCriteria($this, $scope);
        $stmt = $this->doSelect($con);

        return $stmt->fetchColumn();
    }

    /**
     * Get the highest rank by a scope with a array format.
     *
     * @param     int $scope		The scope value as scalar type or array($value1, ...).

     * @param     PropelPDO optional connection
     *
     * @return    integer highest position
     */
    public function getMaxRankArray($scope, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(CmsContentPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . CmsContentPeer::RANK_COL . ')');
        CmsContentPeer::sortableApplyScopeCriteria($this, $scope);
        $stmt = $this->doSelect($con);

        return $stmt->fetchColumn();
    }

    /**
     * Reorder a set of sortable objects based on a list of id/position
     * Beware that there is no check made on the positions passed
     * So incoherent positions will result in an incoherent list
     *
     * @param     array     $order id => rank pairs
     * @param     PropelPDO $con   optional connection
     *
     * @return    boolean true if the reordering took place, false if a database problem prevented it
     */
    public function reorder(array $order, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(CmsContentPeer::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $ids = array_keys($order);
            $objects = $this->findPks($ids, $con);
            foreach ($objects as $object) {
                $pk = $object->getPrimaryKey();
                if ($object->getSortableRank() != $order[$pk]) {
                    $object->setSortableRank($order[$pk]);
                    $object->save($con);
                }
            }
            $con->commit();

            return true;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     CmsContentQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(CmsContentPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     CmsContentQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(CmsContentPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     CmsContentQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(CmsContentPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     CmsContentQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(CmsContentPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     CmsContentQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(CmsContentPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     CmsContentQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(CmsContentPeer::CREATED_AT);
    }
}
