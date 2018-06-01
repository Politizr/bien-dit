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
use Politizr\Model\PQOrganization;
use Politizr\Model\PQOrganizationPeer;
use Politizr\Model\PQOrganizationQuery;
use Politizr\Model\PQType;
use Politizr\Model\PUCurrentQO;
use Politizr\Model\PUMandate;
use Politizr\Model\PUser;

/**
 * @method PQOrganizationQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PQOrganizationQuery orderByUuid($order = Criteria::ASC) Order by the uuid column
 * @method PQOrganizationQuery orderByPQTypeId($order = Criteria::ASC) Order by the p_q_type_id column
 * @method PQOrganizationQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method PQOrganizationQuery orderByInitials($order = Criteria::ASC) Order by the initials column
 * @method PQOrganizationQuery orderByFileName($order = Criteria::ASC) Order by the file_name column
 * @method PQOrganizationQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method PQOrganizationQuery orderByUrl($order = Criteria::ASC) Order by the url column
 * @method PQOrganizationQuery orderByOnline($order = Criteria::ASC) Order by the online column
 * @method PQOrganizationQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PQOrganizationQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PQOrganizationQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 * @method PQOrganizationQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 *
 * @method PQOrganizationQuery groupById() Group by the id column
 * @method PQOrganizationQuery groupByUuid() Group by the uuid column
 * @method PQOrganizationQuery groupByPQTypeId() Group by the p_q_type_id column
 * @method PQOrganizationQuery groupByTitle() Group by the title column
 * @method PQOrganizationQuery groupByInitials() Group by the initials column
 * @method PQOrganizationQuery groupByFileName() Group by the file_name column
 * @method PQOrganizationQuery groupByDescription() Group by the description column
 * @method PQOrganizationQuery groupByUrl() Group by the url column
 * @method PQOrganizationQuery groupByOnline() Group by the online column
 * @method PQOrganizationQuery groupByCreatedAt() Group by the created_at column
 * @method PQOrganizationQuery groupByUpdatedAt() Group by the updated_at column
 * @method PQOrganizationQuery groupBySlug() Group by the slug column
 * @method PQOrganizationQuery groupBySortableRank() Group by the sortable_rank column
 *
 * @method PQOrganizationQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PQOrganizationQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PQOrganizationQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PQOrganizationQuery leftJoinPQType($relationAlias = null) Adds a LEFT JOIN clause to the query using the PQType relation
 * @method PQOrganizationQuery rightJoinPQType($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PQType relation
 * @method PQOrganizationQuery innerJoinPQType($relationAlias = null) Adds a INNER JOIN clause to the query using the PQType relation
 *
 * @method PQOrganizationQuery leftJoinPUMandate($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUMandate relation
 * @method PQOrganizationQuery rightJoinPUMandate($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUMandate relation
 * @method PQOrganizationQuery innerJoinPUMandate($relationAlias = null) Adds a INNER JOIN clause to the query using the PUMandate relation
 *
 * @method PQOrganizationQuery leftJoinPUCurrentQOPQOrganization($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUCurrentQOPQOrganization relation
 * @method PQOrganizationQuery rightJoinPUCurrentQOPQOrganization($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUCurrentQOPQOrganization relation
 * @method PQOrganizationQuery innerJoinPUCurrentQOPQOrganization($relationAlias = null) Adds a INNER JOIN clause to the query using the PUCurrentQOPQOrganization relation
 *
 * @method PQOrganization findOne(PropelPDO $con = null) Return the first PQOrganization matching the query
 * @method PQOrganization findOneOrCreate(PropelPDO $con = null) Return the first PQOrganization matching the query, or a new PQOrganization object populated from the query conditions when no match is found
 *
 * @method PQOrganization findOneByUuid(string $uuid) Return the first PQOrganization filtered by the uuid column
 * @method PQOrganization findOneByPQTypeId(int $p_q_type_id) Return the first PQOrganization filtered by the p_q_type_id column
 * @method PQOrganization findOneByTitle(string $title) Return the first PQOrganization filtered by the title column
 * @method PQOrganization findOneByInitials(string $initials) Return the first PQOrganization filtered by the initials column
 * @method PQOrganization findOneByFileName(string $file_name) Return the first PQOrganization filtered by the file_name column
 * @method PQOrganization findOneByDescription(string $description) Return the first PQOrganization filtered by the description column
 * @method PQOrganization findOneByUrl(string $url) Return the first PQOrganization filtered by the url column
 * @method PQOrganization findOneByOnline(boolean $online) Return the first PQOrganization filtered by the online column
 * @method PQOrganization findOneByCreatedAt(string $created_at) Return the first PQOrganization filtered by the created_at column
 * @method PQOrganization findOneByUpdatedAt(string $updated_at) Return the first PQOrganization filtered by the updated_at column
 * @method PQOrganization findOneBySlug(string $slug) Return the first PQOrganization filtered by the slug column
 * @method PQOrganization findOneBySortableRank(int $sortable_rank) Return the first PQOrganization filtered by the sortable_rank column
 *
 * @method array findById(int $id) Return PQOrganization objects filtered by the id column
 * @method array findByUuid(string $uuid) Return PQOrganization objects filtered by the uuid column
 * @method array findByPQTypeId(int $p_q_type_id) Return PQOrganization objects filtered by the p_q_type_id column
 * @method array findByTitle(string $title) Return PQOrganization objects filtered by the title column
 * @method array findByInitials(string $initials) Return PQOrganization objects filtered by the initials column
 * @method array findByFileName(string $file_name) Return PQOrganization objects filtered by the file_name column
 * @method array findByDescription(string $description) Return PQOrganization objects filtered by the description column
 * @method array findByUrl(string $url) Return PQOrganization objects filtered by the url column
 * @method array findByOnline(boolean $online) Return PQOrganization objects filtered by the online column
 * @method array findByCreatedAt(string $created_at) Return PQOrganization objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PQOrganization objects filtered by the updated_at column
 * @method array findBySlug(string $slug) Return PQOrganization objects filtered by the slug column
 * @method array findBySortableRank(int $sortable_rank) Return PQOrganization objects filtered by the sortable_rank column
 */
abstract class BasePQOrganizationQuery extends ModelCriteria
{
    // query_cache behavior
    protected $queryKey = '';

    /**
     * Initializes internal state of BasePQOrganizationQuery object.
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
            $modelName = 'Politizr\\Model\\PQOrganization';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PQOrganizationQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PQOrganizationQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PQOrganizationQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PQOrganizationQuery) {
            return $criteria;
        }
        $query = new PQOrganizationQuery(null, null, $modelAlias);

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
     * @return   PQOrganization|PQOrganization[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PQOrganizationPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PQOrganizationPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PQOrganization A model object, or null if the key is not found
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
     * @return                 PQOrganization A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `uuid`, `p_q_type_id`, `title`, `initials`, `file_name`, `description`, `url`, `online`, `created_at`, `updated_at`, `slug`, `sortable_rank` FROM `p_q_organization` WHERE `id` = :p0';
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
            $obj = new PQOrganization();
            $obj->hydrate($row);
            PQOrganizationPeer::addInstanceToPool($obj, (string) $key);
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
     * @return PQOrganization|PQOrganization[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PQOrganization[]|mixed the list of results, formatted by the current formatter
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
     * @return PQOrganizationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PQOrganizationPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PQOrganizationQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PQOrganizationPeer::ID, $keys, Criteria::IN);
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
     * @return PQOrganizationQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PQOrganizationPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PQOrganizationPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PQOrganizationPeer::ID, $id, $comparison);
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
     * @return PQOrganizationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PQOrganizationPeer::UUID, $uuid, $comparison);
    }

    /**
     * Filter the query on the p_q_type_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPQTypeId(1234); // WHERE p_q_type_id = 1234
     * $query->filterByPQTypeId(array(12, 34)); // WHERE p_q_type_id IN (12, 34)
     * $query->filterByPQTypeId(array('min' => 12)); // WHERE p_q_type_id >= 12
     * $query->filterByPQTypeId(array('max' => 12)); // WHERE p_q_type_id <= 12
     * </code>
     *
     * @see       filterByPQType()
     *
     * @param     mixed $pQTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PQOrganizationQuery The current query, for fluid interface
     */
    public function filterByPQTypeId($pQTypeId = null, $comparison = null)
    {
        if (is_array($pQTypeId)) {
            $useMinMax = false;
            if (isset($pQTypeId['min'])) {
                $this->addUsingAlias(PQOrganizationPeer::P_Q_TYPE_ID, $pQTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pQTypeId['max'])) {
                $this->addUsingAlias(PQOrganizationPeer::P_Q_TYPE_ID, $pQTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PQOrganizationPeer::P_Q_TYPE_ID, $pQTypeId, $comparison);
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
     * @return PQOrganizationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PQOrganizationPeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the initials column
     *
     * Example usage:
     * <code>
     * $query->filterByInitials('fooValue');   // WHERE initials = 'fooValue'
     * $query->filterByInitials('%fooValue%'); // WHERE initials LIKE '%fooValue%'
     * </code>
     *
     * @param     string $initials The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PQOrganizationQuery The current query, for fluid interface
     */
    public function filterByInitials($initials = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($initials)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $initials)) {
                $initials = str_replace('*', '%', $initials);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PQOrganizationPeer::INITIALS, $initials, $comparison);
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
     * @return PQOrganizationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PQOrganizationPeer::FILE_NAME, $fileName, $comparison);
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
     * @return PQOrganizationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PQOrganizationPeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE url = 'fooValue'
     * $query->filterByUrl('%fooValue%'); // WHERE url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $url The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PQOrganizationQuery The current query, for fluid interface
     */
    public function filterByUrl($url = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $url)) {
                $url = str_replace('*', '%', $url);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PQOrganizationPeer::URL, $url, $comparison);
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
     * @return PQOrganizationQuery The current query, for fluid interface
     */
    public function filterByOnline($online = null, $comparison = null)
    {
        if (is_string($online)) {
            $online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PQOrganizationPeer::ONLINE, $online, $comparison);
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
     * @return PQOrganizationQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PQOrganizationPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PQOrganizationPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PQOrganizationPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PQOrganizationQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PQOrganizationPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PQOrganizationPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PQOrganizationPeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return PQOrganizationQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PQOrganizationPeer::SLUG, $slug, $comparison);
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
     * @return PQOrganizationQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(PQOrganizationPeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(PQOrganizationPeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PQOrganizationPeer::SORTABLE_RANK, $sortableRank, $comparison);
    }

    /**
     * Filter the query by a related PQType object
     *
     * @param   PQType|PropelObjectCollection $pQType The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PQOrganizationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPQType($pQType, $comparison = null)
    {
        if ($pQType instanceof PQType) {
            return $this
                ->addUsingAlias(PQOrganizationPeer::P_Q_TYPE_ID, $pQType->getId(), $comparison);
        } elseif ($pQType instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PQOrganizationPeer::P_Q_TYPE_ID, $pQType->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPQType() only accepts arguments of type PQType or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PQType relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PQOrganizationQuery The current query, for fluid interface
     */
    public function joinPQType($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PQType');

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
            $this->addJoinObject($join, 'PQType');
        }

        return $this;
    }

    /**
     * Use the PQType relation PQType object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PQTypeQuery A secondary query class using the current class as primary query
     */
    public function usePQTypeQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPQType($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PQType', '\Politizr\Model\PQTypeQuery');
    }

    /**
     * Filter the query by a related PUMandate object
     *
     * @param   PUMandate|PropelObjectCollection $pUMandate  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PQOrganizationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUMandate($pUMandate, $comparison = null)
    {
        if ($pUMandate instanceof PUMandate) {
            return $this
                ->addUsingAlias(PQOrganizationPeer::ID, $pUMandate->getPQOrganizationId(), $comparison);
        } elseif ($pUMandate instanceof PropelObjectCollection) {
            return $this
                ->usePUMandateQuery()
                ->filterByPrimaryKeys($pUMandate->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPUMandate() only accepts arguments of type PUMandate or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUMandate relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PQOrganizationQuery The current query, for fluid interface
     */
    public function joinPUMandate($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUMandate');

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
            $this->addJoinObject($join, 'PUMandate');
        }

        return $this;
    }

    /**
     * Use the PUMandate relation PUMandate object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUMandateQuery A secondary query class using the current class as primary query
     */
    public function usePUMandateQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPUMandate($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUMandate', '\Politizr\Model\PUMandateQuery');
    }

    /**
     * Filter the query by a related PUCurrentQO object
     *
     * @param   PUCurrentQO|PropelObjectCollection $pUCurrentQO  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PQOrganizationQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUCurrentQOPQOrganization($pUCurrentQO, $comparison = null)
    {
        if ($pUCurrentQO instanceof PUCurrentQO) {
            return $this
                ->addUsingAlias(PQOrganizationPeer::ID, $pUCurrentQO->getPQOrganizationId(), $comparison);
        } elseif ($pUCurrentQO instanceof PropelObjectCollection) {
            return $this
                ->usePUCurrentQOPQOrganizationQuery()
                ->filterByPrimaryKeys($pUCurrentQO->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPUCurrentQOPQOrganization() only accepts arguments of type PUCurrentQO or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUCurrentQOPQOrganization relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PQOrganizationQuery The current query, for fluid interface
     */
    public function joinPUCurrentQOPQOrganization($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUCurrentQOPQOrganization');

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
            $this->addJoinObject($join, 'PUCurrentQOPQOrganization');
        }

        return $this;
    }

    /**
     * Use the PUCurrentQOPQOrganization relation PUCurrentQO object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUCurrentQOQuery A secondary query class using the current class as primary query
     */
    public function usePUCurrentQOPQOrganizationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUCurrentQOPQOrganization($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUCurrentQOPQOrganization', '\Politizr\Model\PUCurrentQOQuery');
    }

    /**
     * Filter the query by a related PUser object
     * using the p_u_current_q_o table as cross reference
     *
     * @param   PUser $pUser the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PQOrganizationQuery The current query, for fluid interface
     */
    public function filterByPUCurrentQOPUser($pUser, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePUCurrentQOPQOrganizationQuery()
            ->filterByPUCurrentQOPUser($pUser, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   PQOrganization $pQOrganization Object to remove from the list of results
     *
     * @return PQOrganizationQuery The current query, for fluid interface
     */
    public function prune($pQOrganization = null)
    {
        if ($pQOrganization) {
            $this->addUsingAlias(PQOrganizationPeer::ID, $pQOrganization->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     PQOrganizationQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PQOrganizationPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PQOrganizationQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PQOrganizationPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PQOrganizationQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PQOrganizationPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PQOrganizationQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PQOrganizationPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PQOrganizationQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PQOrganizationPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PQOrganizationQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PQOrganizationPeer::CREATED_AT);
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

        $dbMap = Propel::getDatabaseMap(PQOrganizationPeer::DATABASE_NAME);
        $db = Propel::getDB(PQOrganizationPeer::DATABASE_NAME);

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

    // sortable behavior

    /**
     * Returns the objects in a certain list, from the list scope
     *
     * @param int $scope Scope to determine which objects node to return
     *
     * @return PQOrganizationQuery The current query, for fluid interface
     */
    public function inList($scope)
    {

        PQOrganizationPeer::sortableApplyScopeCriteria($this, $scope, 'addUsingAlias');

        return $this;
    }

    /**
     * Filter the query based on a rank in the list
     *
     * @param     integer   $rank rank
     * @param int $scope Scope to determine which objects node to return

     *
     * @return    PQOrganizationQuery The current query, for fluid interface
     */
    public function filterByRank($rank, $scope)
    {


        return $this
            ->inList($scope)
            ->addUsingAlias(PQOrganizationPeer::RANK_COL, $rank, Criteria::EQUAL);
    }

    /**
     * Order the query based on the rank in the list.
     * Using the default $order, returns the item with the lowest rank first
     *
     * @param     string $order either Criteria::ASC (default) or Criteria::DESC
     *
     * @return    PQOrganizationQuery The current query, for fluid interface
     */
    public function orderByRank($order = Criteria::ASC)
    {
        $order = strtoupper($order);
        switch ($order) {
            case Criteria::ASC:
                return $this->addAscendingOrderByColumn($this->getAliasedColName(PQOrganizationPeer::RANK_COL));
                break;
            case Criteria::DESC:
                return $this->addDescendingOrderByColumn($this->getAliasedColName(PQOrganizationPeer::RANK_COL));
                break;
            default:
                throw new PropelException('PQOrganizationQuery::orderBy() only accepts "asc" or "desc" as argument');
        }
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param int $scope Scope to determine which objects node to return
     * @param     PropelPDO $con optional connection
     *
     * @return    PQOrganization
     */
    public function findOneByRank($rank, $scope, PropelPDO $con = null)
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
    public function findList($scope, $con = null)
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
    public function getMaxRank($scope, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PQOrganizationPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . PQOrganizationPeer::RANK_COL . ')');

        PQOrganizationPeer::sortableApplyScopeCriteria($this, $scope);
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
            $con = Propel::getConnection(PQOrganizationPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . PQOrganizationPeer::RANK_COL . ')');
        PQOrganizationPeer::sortableApplyScopeCriteria($this, $scope);
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
            $con = Propel::getConnection(PQOrganizationPeer::DATABASE_NAME);
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

}
