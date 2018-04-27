<?php

namespace Politizr\Model\om;

use \Criteria;
use \Exception;
use \ModelCriteria;
use \PDO;
use \Propel;
use \PropelException;
use \PropelObjectCollection;
use \PropelPDO;
use Politizr\Model\PCTopicArchive;
use Politizr\Model\PCTopicArchivePeer;
use Politizr\Model\PCTopicArchiveQuery;

/**
 * @method PCTopicArchiveQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PCTopicArchiveQuery orderByUuid($order = Criteria::ASC) Order by the uuid column
 * @method PCTopicArchiveQuery orderByPCircleId($order = Criteria::ASC) Order by the p_circle_id column
 * @method PCTopicArchiveQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method PCTopicArchiveQuery orderBySummary($order = Criteria::ASC) Order by the summary column
 * @method PCTopicArchiveQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method PCTopicArchiveQuery orderByFileName($order = Criteria::ASC) Order by the file_name column
 * @method PCTopicArchiveQuery orderByOnline($order = Criteria::ASC) Order by the online column
 * @method PCTopicArchiveQuery orderByForceGeolocType($order = Criteria::ASC) Order by the force_geoloc_type column
 * @method PCTopicArchiveQuery orderByForceGeolocId($order = Criteria::ASC) Order by the force_geoloc_id column
 * @method PCTopicArchiveQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PCTopicArchiveQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PCTopicArchiveQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 * @method PCTopicArchiveQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 * @method PCTopicArchiveQuery orderByArchivedAt($order = Criteria::ASC) Order by the archived_at column
 *
 * @method PCTopicArchiveQuery groupById() Group by the id column
 * @method PCTopicArchiveQuery groupByUuid() Group by the uuid column
 * @method PCTopicArchiveQuery groupByPCircleId() Group by the p_circle_id column
 * @method PCTopicArchiveQuery groupByTitle() Group by the title column
 * @method PCTopicArchiveQuery groupBySummary() Group by the summary column
 * @method PCTopicArchiveQuery groupByDescription() Group by the description column
 * @method PCTopicArchiveQuery groupByFileName() Group by the file_name column
 * @method PCTopicArchiveQuery groupByOnline() Group by the online column
 * @method PCTopicArchiveQuery groupByForceGeolocType() Group by the force_geoloc_type column
 * @method PCTopicArchiveQuery groupByForceGeolocId() Group by the force_geoloc_id column
 * @method PCTopicArchiveQuery groupByCreatedAt() Group by the created_at column
 * @method PCTopicArchiveQuery groupByUpdatedAt() Group by the updated_at column
 * @method PCTopicArchiveQuery groupBySlug() Group by the slug column
 * @method PCTopicArchiveQuery groupBySortableRank() Group by the sortable_rank column
 * @method PCTopicArchiveQuery groupByArchivedAt() Group by the archived_at column
 *
 * @method PCTopicArchiveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PCTopicArchiveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PCTopicArchiveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PCTopicArchive findOne(PropelPDO $con = null) Return the first PCTopicArchive matching the query
 * @method PCTopicArchive findOneOrCreate(PropelPDO $con = null) Return the first PCTopicArchive matching the query, or a new PCTopicArchive object populated from the query conditions when no match is found
 *
 * @method PCTopicArchive findOneByUuid(string $uuid) Return the first PCTopicArchive filtered by the uuid column
 * @method PCTopicArchive findOneByPCircleId(int $p_circle_id) Return the first PCTopicArchive filtered by the p_circle_id column
 * @method PCTopicArchive findOneByTitle(string $title) Return the first PCTopicArchive filtered by the title column
 * @method PCTopicArchive findOneBySummary(string $summary) Return the first PCTopicArchive filtered by the summary column
 * @method PCTopicArchive findOneByDescription(string $description) Return the first PCTopicArchive filtered by the description column
 * @method PCTopicArchive findOneByFileName(string $file_name) Return the first PCTopicArchive filtered by the file_name column
 * @method PCTopicArchive findOneByOnline(boolean $online) Return the first PCTopicArchive filtered by the online column
 * @method PCTopicArchive findOneByForceGeolocType(string $force_geoloc_type) Return the first PCTopicArchive filtered by the force_geoloc_type column
 * @method PCTopicArchive findOneByForceGeolocId(int $force_geoloc_id) Return the first PCTopicArchive filtered by the force_geoloc_id column
 * @method PCTopicArchive findOneByCreatedAt(string $created_at) Return the first PCTopicArchive filtered by the created_at column
 * @method PCTopicArchive findOneByUpdatedAt(string $updated_at) Return the first PCTopicArchive filtered by the updated_at column
 * @method PCTopicArchive findOneBySlug(string $slug) Return the first PCTopicArchive filtered by the slug column
 * @method PCTopicArchive findOneBySortableRank(int $sortable_rank) Return the first PCTopicArchive filtered by the sortable_rank column
 * @method PCTopicArchive findOneByArchivedAt(string $archived_at) Return the first PCTopicArchive filtered by the archived_at column
 *
 * @method array findById(int $id) Return PCTopicArchive objects filtered by the id column
 * @method array findByUuid(string $uuid) Return PCTopicArchive objects filtered by the uuid column
 * @method array findByPCircleId(int $p_circle_id) Return PCTopicArchive objects filtered by the p_circle_id column
 * @method array findByTitle(string $title) Return PCTopicArchive objects filtered by the title column
 * @method array findBySummary(string $summary) Return PCTopicArchive objects filtered by the summary column
 * @method array findByDescription(string $description) Return PCTopicArchive objects filtered by the description column
 * @method array findByFileName(string $file_name) Return PCTopicArchive objects filtered by the file_name column
 * @method array findByOnline(boolean $online) Return PCTopicArchive objects filtered by the online column
 * @method array findByForceGeolocType(string $force_geoloc_type) Return PCTopicArchive objects filtered by the force_geoloc_type column
 * @method array findByForceGeolocId(int $force_geoloc_id) Return PCTopicArchive objects filtered by the force_geoloc_id column
 * @method array findByCreatedAt(string $created_at) Return PCTopicArchive objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PCTopicArchive objects filtered by the updated_at column
 * @method array findBySlug(string $slug) Return PCTopicArchive objects filtered by the slug column
 * @method array findBySortableRank(int $sortable_rank) Return PCTopicArchive objects filtered by the sortable_rank column
 * @method array findByArchivedAt(string $archived_at) Return PCTopicArchive objects filtered by the archived_at column
 */
abstract class BasePCTopicArchiveQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePCTopicArchiveQuery object.
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
            $modelName = 'Politizr\\Model\\PCTopicArchive';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PCTopicArchiveQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PCTopicArchiveQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PCTopicArchiveQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PCTopicArchiveQuery) {
            return $criteria;
        }
        $query = new PCTopicArchiveQuery(null, null, $modelAlias);

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
     * @return   PCTopicArchive|PCTopicArchive[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PCTopicArchivePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PCTopicArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PCTopicArchive A model object, or null if the key is not found
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
     * @return                 PCTopicArchive A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `uuid`, `p_circle_id`, `title`, `summary`, `description`, `file_name`, `online`, `force_geoloc_type`, `force_geoloc_id`, `created_at`, `updated_at`, `slug`, `sortable_rank`, `archived_at` FROM `p_c_topic_archive` WHERE `id` = :p0';
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
            $obj = new PCTopicArchive();
            $obj->hydrate($row);
            PCTopicArchivePeer::addInstanceToPool($obj, (string) $key);
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
     * @return PCTopicArchive|PCTopicArchive[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PCTopicArchive[]|mixed the list of results, formatted by the current formatter
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
     * @return PCTopicArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PCTopicArchivePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PCTopicArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PCTopicArchivePeer::ID, $keys, Criteria::IN);
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
     * @return PCTopicArchiveQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PCTopicArchivePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PCTopicArchivePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCTopicArchivePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the uuid column
     *
     * Example usage:
     * <code>
     * $query->filterByUuid('fooValue');   // WHERE uuid = 'fooValue'
     * $query->filterByUuid('%fooValue%'); // WHERE uuid LIKE '%fooValue%'
     * </code>
     *
     * @param     string $uuid The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCTopicArchiveQuery The current query, for fluid interface
     */
    public function filterByUuid($uuid = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($uuid)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $uuid)) {
                $uuid = str_replace('*', '%', $uuid);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PCTopicArchivePeer::UUID, $uuid, $comparison);
    }

    /**
     * Filter the query on the p_circle_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPCircleId(1234); // WHERE p_circle_id = 1234
     * $query->filterByPCircleId(array(12, 34)); // WHERE p_circle_id IN (12, 34)
     * $query->filterByPCircleId(array('min' => 12)); // WHERE p_circle_id >= 12
     * $query->filterByPCircleId(array('max' => 12)); // WHERE p_circle_id <= 12
     * </code>
     *
     * @param     mixed $pCircleId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCTopicArchiveQuery The current query, for fluid interface
     */
    public function filterByPCircleId($pCircleId = null, $comparison = null)
    {
        if (is_array($pCircleId)) {
            $useMinMax = false;
            if (isset($pCircleId['min'])) {
                $this->addUsingAlias(PCTopicArchivePeer::P_CIRCLE_ID, $pCircleId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pCircleId['max'])) {
                $this->addUsingAlias(PCTopicArchivePeer::P_CIRCLE_ID, $pCircleId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCTopicArchivePeer::P_CIRCLE_ID, $pCircleId, $comparison);
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
     * @return PCTopicArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PCTopicArchivePeer::TITLE, $title, $comparison);
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
     * @return PCTopicArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PCTopicArchivePeer::SUMMARY, $summary, $comparison);
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
     * @return PCTopicArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PCTopicArchivePeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the file_name column
     *
     * Example usage:
     * <code>
     * $query->filterByFileName('fooValue');   // WHERE file_name = 'fooValue'
     * $query->filterByFileName('%fooValue%'); // WHERE file_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $fileName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCTopicArchiveQuery The current query, for fluid interface
     */
    public function filterByFileName($fileName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fileName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $fileName)) {
                $fileName = str_replace('*', '%', $fileName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PCTopicArchivePeer::FILE_NAME, $fileName, $comparison);
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
     * @return PCTopicArchiveQuery The current query, for fluid interface
     */
    public function filterByOnline($online = null, $comparison = null)
    {
        if (is_string($online)) {
            $online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PCTopicArchivePeer::ONLINE, $online, $comparison);
    }

    /**
     * Filter the query on the force_geoloc_type column
     *
     * Example usage:
     * <code>
     * $query->filterByForceGeolocType('fooValue');   // WHERE force_geoloc_type = 'fooValue'
     * $query->filterByForceGeolocType('%fooValue%'); // WHERE force_geoloc_type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $forceGeolocType The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCTopicArchiveQuery The current query, for fluid interface
     */
    public function filterByForceGeolocType($forceGeolocType = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($forceGeolocType)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $forceGeolocType)) {
                $forceGeolocType = str_replace('*', '%', $forceGeolocType);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PCTopicArchivePeer::FORCE_GEOLOC_TYPE, $forceGeolocType, $comparison);
    }

    /**
     * Filter the query on the force_geoloc_id column
     *
     * Example usage:
     * <code>
     * $query->filterByForceGeolocId(1234); // WHERE force_geoloc_id = 1234
     * $query->filterByForceGeolocId(array(12, 34)); // WHERE force_geoloc_id IN (12, 34)
     * $query->filterByForceGeolocId(array('min' => 12)); // WHERE force_geoloc_id >= 12
     * $query->filterByForceGeolocId(array('max' => 12)); // WHERE force_geoloc_id <= 12
     * </code>
     *
     * @param     mixed $forceGeolocId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCTopicArchiveQuery The current query, for fluid interface
     */
    public function filterByForceGeolocId($forceGeolocId = null, $comparison = null)
    {
        if (is_array($forceGeolocId)) {
            $useMinMax = false;
            if (isset($forceGeolocId['min'])) {
                $this->addUsingAlias(PCTopicArchivePeer::FORCE_GEOLOC_ID, $forceGeolocId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($forceGeolocId['max'])) {
                $this->addUsingAlias(PCTopicArchivePeer::FORCE_GEOLOC_ID, $forceGeolocId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCTopicArchivePeer::FORCE_GEOLOC_ID, $forceGeolocId, $comparison);
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
     * @return PCTopicArchiveQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PCTopicArchivePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PCTopicArchivePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCTopicArchivePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PCTopicArchiveQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PCTopicArchivePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PCTopicArchivePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCTopicArchivePeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return PCTopicArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PCTopicArchivePeer::SLUG, $slug, $comparison);
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
     * @return PCTopicArchiveQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(PCTopicArchivePeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(PCTopicArchivePeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCTopicArchivePeer::SORTABLE_RANK, $sortableRank, $comparison);
    }

    /**
     * Filter the query on the archived_at column
     *
     * Example usage:
     * <code>
     * $query->filterByArchivedAt('2011-03-14'); // WHERE archived_at = '2011-03-14'
     * $query->filterByArchivedAt('now'); // WHERE archived_at = '2011-03-14'
     * $query->filterByArchivedAt(array('max' => 'yesterday')); // WHERE archived_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $archivedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCTopicArchiveQuery The current query, for fluid interface
     */
    public function filterByArchivedAt($archivedAt = null, $comparison = null)
    {
        if (is_array($archivedAt)) {
            $useMinMax = false;
            if (isset($archivedAt['min'])) {
                $this->addUsingAlias(PCTopicArchivePeer::ARCHIVED_AT, $archivedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($archivedAt['max'])) {
                $this->addUsingAlias(PCTopicArchivePeer::ARCHIVED_AT, $archivedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCTopicArchivePeer::ARCHIVED_AT, $archivedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   PCTopicArchive $pCTopicArchive Object to remove from the list of results
     *
     * @return PCTopicArchiveQuery The current query, for fluid interface
     */
    public function prune($pCTopicArchive = null)
    {
        if ($pCTopicArchive) {
            $this->addUsingAlias(PCTopicArchivePeer::ID, $pCTopicArchive->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
