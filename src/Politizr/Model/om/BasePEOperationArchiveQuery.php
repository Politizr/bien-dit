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
use Politizr\Model\PEOperationArchive;
use Politizr\Model\PEOperationArchivePeer;
use Politizr\Model\PEOperationArchiveQuery;

/**
 * @method PEOperationArchiveQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PEOperationArchiveQuery orderByUuid($order = Criteria::ASC) Order by the uuid column
 * @method PEOperationArchiveQuery orderByPUserId($order = Criteria::ASC) Order by the p_user_id column
 * @method PEOperationArchiveQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method PEOperationArchiveQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method PEOperationArchiveQuery orderByEditingDescription($order = Criteria::ASC) Order by the editing_description column
 * @method PEOperationArchiveQuery orderByFileName($order = Criteria::ASC) Order by the file_name column
 * @method PEOperationArchiveQuery orderByGeoScoped($order = Criteria::ASC) Order by the geo_scoped column
 * @method PEOperationArchiveQuery orderByOnline($order = Criteria::ASC) Order by the online column
 * @method PEOperationArchiveQuery orderByTimeline($order = Criteria::ASC) Order by the timeline column
 * @method PEOperationArchiveQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PEOperationArchiveQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PEOperationArchiveQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 * @method PEOperationArchiveQuery orderByArchivedAt($order = Criteria::ASC) Order by the archived_at column
 *
 * @method PEOperationArchiveQuery groupById() Group by the id column
 * @method PEOperationArchiveQuery groupByUuid() Group by the uuid column
 * @method PEOperationArchiveQuery groupByPUserId() Group by the p_user_id column
 * @method PEOperationArchiveQuery groupByTitle() Group by the title column
 * @method PEOperationArchiveQuery groupByDescription() Group by the description column
 * @method PEOperationArchiveQuery groupByEditingDescription() Group by the editing_description column
 * @method PEOperationArchiveQuery groupByFileName() Group by the file_name column
 * @method PEOperationArchiveQuery groupByGeoScoped() Group by the geo_scoped column
 * @method PEOperationArchiveQuery groupByOnline() Group by the online column
 * @method PEOperationArchiveQuery groupByTimeline() Group by the timeline column
 * @method PEOperationArchiveQuery groupByCreatedAt() Group by the created_at column
 * @method PEOperationArchiveQuery groupByUpdatedAt() Group by the updated_at column
 * @method PEOperationArchiveQuery groupBySlug() Group by the slug column
 * @method PEOperationArchiveQuery groupByArchivedAt() Group by the archived_at column
 *
 * @method PEOperationArchiveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PEOperationArchiveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PEOperationArchiveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PEOperationArchive findOne(PropelPDO $con = null) Return the first PEOperationArchive matching the query
 * @method PEOperationArchive findOneOrCreate(PropelPDO $con = null) Return the first PEOperationArchive matching the query, or a new PEOperationArchive object populated from the query conditions when no match is found
 *
 * @method PEOperationArchive findOneByUuid(string $uuid) Return the first PEOperationArchive filtered by the uuid column
 * @method PEOperationArchive findOneByPUserId(int $p_user_id) Return the first PEOperationArchive filtered by the p_user_id column
 * @method PEOperationArchive findOneByTitle(string $title) Return the first PEOperationArchive filtered by the title column
 * @method PEOperationArchive findOneByDescription(string $description) Return the first PEOperationArchive filtered by the description column
 * @method PEOperationArchive findOneByEditingDescription(string $editing_description) Return the first PEOperationArchive filtered by the editing_description column
 * @method PEOperationArchive findOneByFileName(string $file_name) Return the first PEOperationArchive filtered by the file_name column
 * @method PEOperationArchive findOneByGeoScoped(boolean $geo_scoped) Return the first PEOperationArchive filtered by the geo_scoped column
 * @method PEOperationArchive findOneByOnline(boolean $online) Return the first PEOperationArchive filtered by the online column
 * @method PEOperationArchive findOneByTimeline(boolean $timeline) Return the first PEOperationArchive filtered by the timeline column
 * @method PEOperationArchive findOneByCreatedAt(string $created_at) Return the first PEOperationArchive filtered by the created_at column
 * @method PEOperationArchive findOneByUpdatedAt(string $updated_at) Return the first PEOperationArchive filtered by the updated_at column
 * @method PEOperationArchive findOneBySlug(string $slug) Return the first PEOperationArchive filtered by the slug column
 * @method PEOperationArchive findOneByArchivedAt(string $archived_at) Return the first PEOperationArchive filtered by the archived_at column
 *
 * @method array findById(int $id) Return PEOperationArchive objects filtered by the id column
 * @method array findByUuid(string $uuid) Return PEOperationArchive objects filtered by the uuid column
 * @method array findByPUserId(int $p_user_id) Return PEOperationArchive objects filtered by the p_user_id column
 * @method array findByTitle(string $title) Return PEOperationArchive objects filtered by the title column
 * @method array findByDescription(string $description) Return PEOperationArchive objects filtered by the description column
 * @method array findByEditingDescription(string $editing_description) Return PEOperationArchive objects filtered by the editing_description column
 * @method array findByFileName(string $file_name) Return PEOperationArchive objects filtered by the file_name column
 * @method array findByGeoScoped(boolean $geo_scoped) Return PEOperationArchive objects filtered by the geo_scoped column
 * @method array findByOnline(boolean $online) Return PEOperationArchive objects filtered by the online column
 * @method array findByTimeline(boolean $timeline) Return PEOperationArchive objects filtered by the timeline column
 * @method array findByCreatedAt(string $created_at) Return PEOperationArchive objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PEOperationArchive objects filtered by the updated_at column
 * @method array findBySlug(string $slug) Return PEOperationArchive objects filtered by the slug column
 * @method array findByArchivedAt(string $archived_at) Return PEOperationArchive objects filtered by the archived_at column
 */
abstract class BasePEOperationArchiveQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePEOperationArchiveQuery object.
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
            $modelName = 'Politizr\\Model\\PEOperationArchive';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PEOperationArchiveQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PEOperationArchiveQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PEOperationArchiveQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PEOperationArchiveQuery) {
            return $criteria;
        }
        $query = new PEOperationArchiveQuery(null, null, $modelAlias);

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
     * @return   PEOperationArchive|PEOperationArchive[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PEOperationArchivePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PEOperationArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PEOperationArchive A model object, or null if the key is not found
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
     * @return                 PEOperationArchive A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `uuid`, `p_user_id`, `title`, `description`, `editing_description`, `file_name`, `geo_scoped`, `online`, `timeline`, `created_at`, `updated_at`, `slug`, `archived_at` FROM `p_e_operation_archive` WHERE `id` = :p0';
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
            $obj = new PEOperationArchive();
            $obj->hydrate($row);
            PEOperationArchivePeer::addInstanceToPool($obj, (string) $key);
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
     * @return PEOperationArchive|PEOperationArchive[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PEOperationArchive[]|mixed the list of results, formatted by the current formatter
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
     * @return PEOperationArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PEOperationArchivePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PEOperationArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PEOperationArchivePeer::ID, $keys, Criteria::IN);
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
     * @return PEOperationArchiveQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PEOperationArchivePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PEOperationArchivePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PEOperationArchivePeer::ID, $id, $comparison);
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
     * @return PEOperationArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PEOperationArchivePeer::UUID, $uuid, $comparison);
    }

    /**
     * Filter the query on the p_user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPUserId(1234); // WHERE p_user_id = 1234
     * $query->filterByPUserId(array(12, 34)); // WHERE p_user_id IN (12, 34)
     * $query->filterByPUserId(array('min' => 12)); // WHERE p_user_id >= 12
     * $query->filterByPUserId(array('max' => 12)); // WHERE p_user_id <= 12
     * </code>
     *
     * @param     mixed $pUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PEOperationArchiveQuery The current query, for fluid interface
     */
    public function filterByPUserId($pUserId = null, $comparison = null)
    {
        if (is_array($pUserId)) {
            $useMinMax = false;
            if (isset($pUserId['min'])) {
                $this->addUsingAlias(PEOperationArchivePeer::P_USER_ID, $pUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserId['max'])) {
                $this->addUsingAlias(PEOperationArchivePeer::P_USER_ID, $pUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PEOperationArchivePeer::P_USER_ID, $pUserId, $comparison);
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
     * @return PEOperationArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PEOperationArchivePeer::TITLE, $title, $comparison);
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
     * @return PEOperationArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PEOperationArchivePeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the editing_description column
     *
     * Example usage:
     * <code>
     * $query->filterByEditingDescription('fooValue');   // WHERE editing_description = 'fooValue'
     * $query->filterByEditingDescription('%fooValue%'); // WHERE editing_description LIKE '%fooValue%'
     * </code>
     *
     * @param     string $editingDescription The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PEOperationArchiveQuery The current query, for fluid interface
     */
    public function filterByEditingDescription($editingDescription = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($editingDescription)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $editingDescription)) {
                $editingDescription = str_replace('*', '%', $editingDescription);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PEOperationArchivePeer::EDITING_DESCRIPTION, $editingDescription, $comparison);
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
     * @return PEOperationArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PEOperationArchivePeer::FILE_NAME, $fileName, $comparison);
    }

    /**
     * Filter the query on the geo_scoped column
     *
     * Example usage:
     * <code>
     * $query->filterByGeoScoped(true); // WHERE geo_scoped = true
     * $query->filterByGeoScoped('yes'); // WHERE geo_scoped = true
     * </code>
     *
     * @param     boolean|string $geoScoped The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PEOperationArchiveQuery The current query, for fluid interface
     */
    public function filterByGeoScoped($geoScoped = null, $comparison = null)
    {
        if (is_string($geoScoped)) {
            $geoScoped = in_array(strtolower($geoScoped), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PEOperationArchivePeer::GEO_SCOPED, $geoScoped, $comparison);
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
     * @return PEOperationArchiveQuery The current query, for fluid interface
     */
    public function filterByOnline($online = null, $comparison = null)
    {
        if (is_string($online)) {
            $online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PEOperationArchivePeer::ONLINE, $online, $comparison);
    }

    /**
     * Filter the query on the timeline column
     *
     * Example usage:
     * <code>
     * $query->filterByTimeline(true); // WHERE timeline = true
     * $query->filterByTimeline('yes'); // WHERE timeline = true
     * </code>
     *
     * @param     boolean|string $timeline The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PEOperationArchiveQuery The current query, for fluid interface
     */
    public function filterByTimeline($timeline = null, $comparison = null)
    {
        if (is_string($timeline)) {
            $timeline = in_array(strtolower($timeline), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PEOperationArchivePeer::TIMELINE, $timeline, $comparison);
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
     * @return PEOperationArchiveQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PEOperationArchivePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PEOperationArchivePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PEOperationArchivePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PEOperationArchiveQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PEOperationArchivePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PEOperationArchivePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PEOperationArchivePeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return PEOperationArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PEOperationArchivePeer::SLUG, $slug, $comparison);
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
     * @return PEOperationArchiveQuery The current query, for fluid interface
     */
    public function filterByArchivedAt($archivedAt = null, $comparison = null)
    {
        if (is_array($archivedAt)) {
            $useMinMax = false;
            if (isset($archivedAt['min'])) {
                $this->addUsingAlias(PEOperationArchivePeer::ARCHIVED_AT, $archivedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($archivedAt['max'])) {
                $this->addUsingAlias(PEOperationArchivePeer::ARCHIVED_AT, $archivedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PEOperationArchivePeer::ARCHIVED_AT, $archivedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   PEOperationArchive $pEOperationArchive Object to remove from the list of results
     *
     * @return PEOperationArchiveQuery The current query, for fluid interface
     */
    public function prune($pEOperationArchive = null)
    {
        if ($pEOperationArchive) {
            $this->addUsingAlias(PEOperationArchivePeer::ID, $pEOperationArchive->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
