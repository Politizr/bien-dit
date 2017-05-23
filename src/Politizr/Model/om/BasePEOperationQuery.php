<?php

namespace Politizr\Model\om;

use \BasePeer;
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
use Politizr\Model\PDDebate;
use Politizr\Model\PEOPresetPT;
use Politizr\Model\PEOScopePLC;
use Politizr\Model\PEOperation;
use Politizr\Model\PEOperationPeer;
use Politizr\Model\PEOperationQuery;
use Politizr\Model\PLCity;
use Politizr\Model\PTag;
use Politizr\Model\PUser;

/**
 * @method PEOperationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PEOperationQuery orderByUuid($order = Criteria::ASC) Order by the uuid column
 * @method PEOperationQuery orderByPUserId($order = Criteria::ASC) Order by the p_user_id column
 * @method PEOperationQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method PEOperationQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method PEOperationQuery orderByEditingDescription($order = Criteria::ASC) Order by the editing_description column
 * @method PEOperationQuery orderByFileName($order = Criteria::ASC) Order by the file_name column
 * @method PEOperationQuery orderByGeoScoped($order = Criteria::ASC) Order by the geo_scoped column
 * @method PEOperationQuery orderByOnline($order = Criteria::ASC) Order by the online column
 * @method PEOperationQuery orderByTimeline($order = Criteria::ASC) Order by the timeline column
 * @method PEOperationQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PEOperationQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PEOperationQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 *
 * @method PEOperationQuery groupById() Group by the id column
 * @method PEOperationQuery groupByUuid() Group by the uuid column
 * @method PEOperationQuery groupByPUserId() Group by the p_user_id column
 * @method PEOperationQuery groupByTitle() Group by the title column
 * @method PEOperationQuery groupByDescription() Group by the description column
 * @method PEOperationQuery groupByEditingDescription() Group by the editing_description column
 * @method PEOperationQuery groupByFileName() Group by the file_name column
 * @method PEOperationQuery groupByGeoScoped() Group by the geo_scoped column
 * @method PEOperationQuery groupByOnline() Group by the online column
 * @method PEOperationQuery groupByTimeline() Group by the timeline column
 * @method PEOperationQuery groupByCreatedAt() Group by the created_at column
 * @method PEOperationQuery groupByUpdatedAt() Group by the updated_at column
 * @method PEOperationQuery groupBySlug() Group by the slug column
 *
 * @method PEOperationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PEOperationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PEOperationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PEOperationQuery leftJoinPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUser relation
 * @method PEOperationQuery rightJoinPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUser relation
 * @method PEOperationQuery innerJoinPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PUser relation
 *
 * @method PEOperationQuery leftJoinPEOScopePLC($relationAlias = null) Adds a LEFT JOIN clause to the query using the PEOScopePLC relation
 * @method PEOperationQuery rightJoinPEOScopePLC($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PEOScopePLC relation
 * @method PEOperationQuery innerJoinPEOScopePLC($relationAlias = null) Adds a INNER JOIN clause to the query using the PEOScopePLC relation
 *
 * @method PEOperationQuery leftJoinPEOPresetPT($relationAlias = null) Adds a LEFT JOIN clause to the query using the PEOPresetPT relation
 * @method PEOperationQuery rightJoinPEOPresetPT($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PEOPresetPT relation
 * @method PEOperationQuery innerJoinPEOPresetPT($relationAlias = null) Adds a INNER JOIN clause to the query using the PEOPresetPT relation
 *
 * @method PEOperationQuery leftJoinPDDebate($relationAlias = null) Adds a LEFT JOIN clause to the query using the PDDebate relation
 * @method PEOperationQuery rightJoinPDDebate($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PDDebate relation
 * @method PEOperationQuery innerJoinPDDebate($relationAlias = null) Adds a INNER JOIN clause to the query using the PDDebate relation
 *
 * @method PEOperation findOne(PropelPDO $con = null) Return the first PEOperation matching the query
 * @method PEOperation findOneOrCreate(PropelPDO $con = null) Return the first PEOperation matching the query, or a new PEOperation object populated from the query conditions when no match is found
 *
 * @method PEOperation findOneByUuid(string $uuid) Return the first PEOperation filtered by the uuid column
 * @method PEOperation findOneByPUserId(int $p_user_id) Return the first PEOperation filtered by the p_user_id column
 * @method PEOperation findOneByTitle(string $title) Return the first PEOperation filtered by the title column
 * @method PEOperation findOneByDescription(string $description) Return the first PEOperation filtered by the description column
 * @method PEOperation findOneByEditingDescription(string $editing_description) Return the first PEOperation filtered by the editing_description column
 * @method PEOperation findOneByFileName(string $file_name) Return the first PEOperation filtered by the file_name column
 * @method PEOperation findOneByGeoScoped(boolean $geo_scoped) Return the first PEOperation filtered by the geo_scoped column
 * @method PEOperation findOneByOnline(boolean $online) Return the first PEOperation filtered by the online column
 * @method PEOperation findOneByTimeline(boolean $timeline) Return the first PEOperation filtered by the timeline column
 * @method PEOperation findOneByCreatedAt(string $created_at) Return the first PEOperation filtered by the created_at column
 * @method PEOperation findOneByUpdatedAt(string $updated_at) Return the first PEOperation filtered by the updated_at column
 * @method PEOperation findOneBySlug(string $slug) Return the first PEOperation filtered by the slug column
 *
 * @method array findById(int $id) Return PEOperation objects filtered by the id column
 * @method array findByUuid(string $uuid) Return PEOperation objects filtered by the uuid column
 * @method array findByPUserId(int $p_user_id) Return PEOperation objects filtered by the p_user_id column
 * @method array findByTitle(string $title) Return PEOperation objects filtered by the title column
 * @method array findByDescription(string $description) Return PEOperation objects filtered by the description column
 * @method array findByEditingDescription(string $editing_description) Return PEOperation objects filtered by the editing_description column
 * @method array findByFileName(string $file_name) Return PEOperation objects filtered by the file_name column
 * @method array findByGeoScoped(boolean $geo_scoped) Return PEOperation objects filtered by the geo_scoped column
 * @method array findByOnline(boolean $online) Return PEOperation objects filtered by the online column
 * @method array findByTimeline(boolean $timeline) Return PEOperation objects filtered by the timeline column
 * @method array findByCreatedAt(string $created_at) Return PEOperation objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PEOperation objects filtered by the updated_at column
 * @method array findBySlug(string $slug) Return PEOperation objects filtered by the slug column
 */
abstract class BasePEOperationQuery extends ModelCriteria
{
    // query_cache behavior
    protected $queryKey = '';

    // archivable behavior
    protected $archiveOnDelete = true;

    /**
     * Initializes internal state of BasePEOperationQuery object.
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
            $modelName = 'Politizr\\Model\\PEOperation';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PEOperationQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PEOperationQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PEOperationQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PEOperationQuery) {
            return $criteria;
        }
        $query = new PEOperationQuery(null, null, $modelAlias);

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
     * @return   PEOperation|PEOperation[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PEOperationPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PEOperationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PEOperation A model object, or null if the key is not found
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
     * @return                 PEOperation A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `uuid`, `p_user_id`, `title`, `description`, `editing_description`, `file_name`, `geo_scoped`, `online`, `timeline`, `created_at`, `updated_at`, `slug` FROM `p_e_operation` WHERE `id` = :p0';
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
            $obj = new PEOperation();
            $obj->hydrate($row);
            PEOperationPeer::addInstanceToPool($obj, (string) $key);
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
     * @return PEOperation|PEOperation[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PEOperation[]|mixed the list of results, formatted by the current formatter
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
     * @return PEOperationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PEOperationPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PEOperationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PEOperationPeer::ID, $keys, Criteria::IN);
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
     * @return PEOperationQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PEOperationPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PEOperationPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PEOperationPeer::ID, $id, $comparison);
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
     * @return PEOperationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PEOperationPeer::UUID, $uuid, $comparison);
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
     * @see       filterByPUser()
     *
     * @param     mixed $pUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PEOperationQuery The current query, for fluid interface
     */
    public function filterByPUserId($pUserId = null, $comparison = null)
    {
        if (is_array($pUserId)) {
            $useMinMax = false;
            if (isset($pUserId['min'])) {
                $this->addUsingAlias(PEOperationPeer::P_USER_ID, $pUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserId['max'])) {
                $this->addUsingAlias(PEOperationPeer::P_USER_ID, $pUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PEOperationPeer::P_USER_ID, $pUserId, $comparison);
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
     * @return PEOperationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PEOperationPeer::TITLE, $title, $comparison);
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
     * @return PEOperationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PEOperationPeer::DESCRIPTION, $description, $comparison);
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
     * @return PEOperationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PEOperationPeer::EDITING_DESCRIPTION, $editingDescription, $comparison);
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
     * @return PEOperationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PEOperationPeer::FILE_NAME, $fileName, $comparison);
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
     * @return PEOperationQuery The current query, for fluid interface
     */
    public function filterByGeoScoped($geoScoped = null, $comparison = null)
    {
        if (is_string($geoScoped)) {
            $geoScoped = in_array(strtolower($geoScoped), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PEOperationPeer::GEO_SCOPED, $geoScoped, $comparison);
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
     * @return PEOperationQuery The current query, for fluid interface
     */
    public function filterByOnline($online = null, $comparison = null)
    {
        if (is_string($online)) {
            $online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PEOperationPeer::ONLINE, $online, $comparison);
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
     * @return PEOperationQuery The current query, for fluid interface
     */
    public function filterByTimeline($timeline = null, $comparison = null)
    {
        if (is_string($timeline)) {
            $timeline = in_array(strtolower($timeline), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PEOperationPeer::TIMELINE, $timeline, $comparison);
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
     * @return PEOperationQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PEOperationPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PEOperationPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PEOperationPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PEOperationQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PEOperationPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PEOperationPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PEOperationPeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return PEOperationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PEOperationPeer::SLUG, $slug, $comparison);
    }

    /**
     * Filter the query by a related PUser object
     *
     * @param   PUser|PropelObjectCollection $pUser The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PEOperationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUser($pUser, $comparison = null)
    {
        if ($pUser instanceof PUser) {
            return $this
                ->addUsingAlias(PEOperationPeer::P_USER_ID, $pUser->getId(), $comparison);
        } elseif ($pUser instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PEOperationPeer::P_USER_ID, $pUser->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPUser() only accepts arguments of type PUser or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PEOperationQuery The current query, for fluid interface
     */
    public function joinPUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUser');

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
            $this->addJoinObject($join, 'PUser');
        }

        return $this;
    }

    /**
     * Use the PUser relation PUser object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUserQuery A secondary query class using the current class as primary query
     */
    public function usePUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUser', '\Politizr\Model\PUserQuery');
    }

    /**
     * Filter the query by a related PEOScopePLC object
     *
     * @param   PEOScopePLC|PropelObjectCollection $pEOScopePLC  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PEOperationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPEOScopePLC($pEOScopePLC, $comparison = null)
    {
        if ($pEOScopePLC instanceof PEOScopePLC) {
            return $this
                ->addUsingAlias(PEOperationPeer::ID, $pEOScopePLC->getPEOperationId(), $comparison);
        } elseif ($pEOScopePLC instanceof PropelObjectCollection) {
            return $this
                ->usePEOScopePLCQuery()
                ->filterByPrimaryKeys($pEOScopePLC->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPEOScopePLC() only accepts arguments of type PEOScopePLC or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PEOScopePLC relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PEOperationQuery The current query, for fluid interface
     */
    public function joinPEOScopePLC($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PEOScopePLC');

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
            $this->addJoinObject($join, 'PEOScopePLC');
        }

        return $this;
    }

    /**
     * Use the PEOScopePLC relation PEOScopePLC object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PEOScopePLCQuery A secondary query class using the current class as primary query
     */
    public function usePEOScopePLCQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPEOScopePLC($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PEOScopePLC', '\Politizr\Model\PEOScopePLCQuery');
    }

    /**
     * Filter the query by a related PEOPresetPT object
     *
     * @param   PEOPresetPT|PropelObjectCollection $pEOPresetPT  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PEOperationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPEOPresetPT($pEOPresetPT, $comparison = null)
    {
        if ($pEOPresetPT instanceof PEOPresetPT) {
            return $this
                ->addUsingAlias(PEOperationPeer::ID, $pEOPresetPT->getPEOperationId(), $comparison);
        } elseif ($pEOPresetPT instanceof PropelObjectCollection) {
            return $this
                ->usePEOPresetPTQuery()
                ->filterByPrimaryKeys($pEOPresetPT->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPEOPresetPT() only accepts arguments of type PEOPresetPT or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PEOPresetPT relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PEOperationQuery The current query, for fluid interface
     */
    public function joinPEOPresetPT($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PEOPresetPT');

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
            $this->addJoinObject($join, 'PEOPresetPT');
        }

        return $this;
    }

    /**
     * Use the PEOPresetPT relation PEOPresetPT object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PEOPresetPTQuery A secondary query class using the current class as primary query
     */
    public function usePEOPresetPTQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPEOPresetPT($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PEOPresetPT', '\Politizr\Model\PEOPresetPTQuery');
    }

    /**
     * Filter the query by a related PDDebate object
     *
     * @param   PDDebate|PropelObjectCollection $pDDebate  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PEOperationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPDDebate($pDDebate, $comparison = null)
    {
        if ($pDDebate instanceof PDDebate) {
            return $this
                ->addUsingAlias(PEOperationPeer::ID, $pDDebate->getPEOperationId(), $comparison);
        } elseif ($pDDebate instanceof PropelObjectCollection) {
            return $this
                ->usePDDebateQuery()
                ->filterByPrimaryKeys($pDDebate->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPDDebate() only accepts arguments of type PDDebate or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PDDebate relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PEOperationQuery The current query, for fluid interface
     */
    public function joinPDDebate($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PDDebate');

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
            $this->addJoinObject($join, 'PDDebate');
        }

        return $this;
    }

    /**
     * Use the PDDebate relation PDDebate object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PDDebateQuery A secondary query class using the current class as primary query
     */
    public function usePDDebateQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPDDebate($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PDDebate', '\Politizr\Model\PDDebateQuery');
    }

    /**
     * Filter the query by a related PLCity object
     * using the p_e_o_scope_p_l_c table as cross reference
     *
     * @param   PLCity $pLCity the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PEOperationQuery The current query, for fluid interface
     */
    public function filterByPLCity($pLCity, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePEOScopePLCQuery()
            ->filterByPLCity($pLCity, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PTag object
     * using the p_e_o_preset_p_t table as cross reference
     *
     * @param   PTag $pTag the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PEOperationQuery The current query, for fluid interface
     */
    public function filterByPTag($pTag, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePEOPresetPTQuery()
            ->filterByPTag($pTag, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   PEOperation $pEOperation Object to remove from the list of results
     *
     * @return PEOperationQuery The current query, for fluid interface
     */
    public function prune($pEOperation = null)
    {
        if ($pEOperation) {
            $this->addUsingAlias(PEOperationPeer::ID, $pEOperation->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Code to execute before every DELETE statement
     *
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePreDelete(PropelPDO $con)
    {
        // archivable behavior

        if ($this->archiveOnDelete) {
            $this->archive($con);
        } else {
            $this->archiveOnDelete = true;
        }


        return $this->preDelete($con);
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     PEOperationQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PEOperationPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PEOperationQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PEOperationPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PEOperationQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PEOperationPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PEOperationQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PEOperationPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PEOperationQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PEOperationPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PEOperationQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PEOperationPeer::CREATED_AT);
    }
    // query_cache behavior

    public function setQueryKey($key)
    {
        $this->queryKey = $key;

        return $this;
    }

    public function getQueryKey()
    {
        return $this->queryKey;
    }

    public function cacheContains($key)
    {

        return apc_fetch($key);
    }

    public function cacheFetch($key)
    {

        return apc_fetch($key);
    }

    public function cacheStore($key, $value, $lifetime = 3600)
    {
        apc_store($key, $value, $lifetime);
    }

    protected function doSelect($con)
    {
        // check that the columns of the main class are already added (if this is the primary ModelCriteria)
        if (!$this->hasSelectClause() && !$this->getPrimaryCriteria()) {
            $this->addSelfSelectColumns();
        }
        $this->configureSelectColumns();

        $dbMap = Propel::getDatabaseMap(PEOperationPeer::DATABASE_NAME);
        $db = Propel::getDB(PEOperationPeer::DATABASE_NAME);

        $key = $this->getQueryKey();
        if ($key && $this->cacheContains($key)) {
            $params = $this->getParams();
            $sql = $this->cacheFetch($key);
        } else {
            $params = array();
            $sql = BasePeer::createSelectSql($this, $params);
            if ($key) {
                $this->cacheStore($key, $sql);
            }
        }

        try {
            $stmt = $con->prepare($sql);
            $db->bindValues($stmt, $params, $dbMap);
            $stmt->execute();
            } catch (Exception $e) {
                Propel::log($e->getMessage(), Propel::LOG_ERR);
                throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
            }

        return $stmt;
    }

    protected function doCount($con)
    {
        $dbMap = Propel::getDatabaseMap($this->getDbName());
        $db = Propel::getDB($this->getDbName());

        $key = $this->getQueryKey();
        if ($key && $this->cacheContains($key)) {
            $params = $this->getParams();
            $sql = $this->cacheFetch($key);
        } else {
            // check that the columns of the main class are already added (if this is the primary ModelCriteria)
            if (!$this->hasSelectClause() && !$this->getPrimaryCriteria()) {
                $this->addSelfSelectColumns();
            }

            $this->configureSelectColumns();

            $needsComplexCount = $this->getGroupByColumns()
                || $this->getOffset()
                || $this->getLimit()
                || $this->getHaving()
                || in_array(Criteria::DISTINCT, $this->getSelectModifiers());

            $params = array();
            if ($needsComplexCount) {
                if (BasePeer::needsSelectAliases($this)) {
                    if ($this->getHaving()) {
                        throw new PropelException('Propel cannot create a COUNT query when using HAVING and  duplicate column names in the SELECT part');
                    }
                    $db->turnSelectColumnsToAliases($this);
                }
                $selectSql = BasePeer::createSelectSql($this, $params);
                $sql = 'SELECT COUNT(*) FROM (' . $selectSql . ') propelmatch4cnt';
            } else {
                // Replace SELECT columns with COUNT(*)
                $this->clearSelectColumns()->addSelectColumn('COUNT(*)');
                $sql = BasePeer::createSelectSql($this, $params);
            }

            if ($key) {
                $this->cacheStore($key, $sql);
            }
        }

        try {
            $stmt = $con->prepare($sql);
            $db->bindValues($stmt, $params, $dbMap);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute COUNT statement [%s]', $sql), $e);
        }

        return $stmt;
    }

    // archivable behavior

    /**
     * Copy the data of the objects satisfying the query into PEOperationArchive archive objects.
     * The archived objects are then saved.
     * If any of the objects has already been archived, the archived object
     * is updated and not duplicated.
     * Warning: This termination methods issues 2n+1 queries.
     *
     * @param      PropelPDO $con	Connection to use.
     * @param      Boolean $useLittleMemory	Whether or not to use PropelOnDemandFormatter to retrieve objects.
     *               Set to false if the identity map matters.
     *               Set to true (default) to use less memory.
     *
     * @return     int the number of archived objects
     * @throws     PropelException
     */
    public function archive($con = null, $useLittleMemory = true)
    {
        $totalArchivedObjects = 0;
        $criteria = clone $this;
        // prepare the query
        $criteria->setWith(array());
        if ($useLittleMemory) {
            $criteria->setFormatter(ModelCriteria::FORMAT_ON_DEMAND);
        }
        if ($con === null) {
            $con = Propel::getConnection(PEOperationPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $con->beginTransaction();
        try {
            // archive all results one by one
            foreach ($criteria->find($con) as $object) {
                $object->archive($con);
                $totalArchivedObjects++;
            }
            $con->commit();
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }

        return $totalArchivedObjects;
    }

    /**
     * Enable/disable auto-archiving on delete for the next query.
     *
     * @param boolean $archiveOnDelete True if the query must archive deleted objects, false otherwise.
     */
    public function setArchiveOnDelete($archiveOnDelete)
    {
        $this->archiveOnDelete = $archiveOnDelete;
    }

    /**
     * Delete records matching the current query without archiving them.
     *
     * @param      PropelPDO $con	Connection to use.
     *
     * @return integer the number of deleted rows
     */
    public function deleteWithoutArchive($con = null)
    {
        $this->archiveOnDelete = false;

        return $this->delete($con);
    }

    /**
     * Delete all records without archiving them.
     *
     * @param      PropelPDO $con	Connection to use.
     *
     * @return integer the number of deleted rows
     */
    public function deleteAllWithoutArchive($con = null)
    {
        $this->archiveOnDelete = false;

        return $this->deleteAll($con);
    }

}
