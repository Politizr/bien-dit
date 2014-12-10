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
use Politizr\Model\PTagArchive;
use Politizr\Model\PTagArchivePeer;
use Politizr\Model\PTagArchiveQuery;

/**
 * @method PTagArchiveQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PTagArchiveQuery orderByPTTagTypeId($order = Criteria::ASC) Order by the p_t_tag_type_id column
 * @method PTagArchiveQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method PTagArchiveQuery orderByOnline($order = Criteria::ASC) Order by the online column
 * @method PTagArchiveQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PTagArchiveQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PTagArchiveQuery orderByArchivedAt($order = Criteria::ASC) Order by the archived_at column
 *
 * @method PTagArchiveQuery groupById() Group by the id column
 * @method PTagArchiveQuery groupByPTTagTypeId() Group by the p_t_tag_type_id column
 * @method PTagArchiveQuery groupByTitle() Group by the title column
 * @method PTagArchiveQuery groupByOnline() Group by the online column
 * @method PTagArchiveQuery groupByCreatedAt() Group by the created_at column
 * @method PTagArchiveQuery groupByUpdatedAt() Group by the updated_at column
 * @method PTagArchiveQuery groupByArchivedAt() Group by the archived_at column
 *
 * @method PTagArchiveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PTagArchiveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PTagArchiveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PTagArchive findOne(PropelPDO $con = null) Return the first PTagArchive matching the query
 * @method PTagArchive findOneOrCreate(PropelPDO $con = null) Return the first PTagArchive matching the query, or a new PTagArchive object populated from the query conditions when no match is found
 *
 * @method PTagArchive findOneByPTTagTypeId(int $p_t_tag_type_id) Return the first PTagArchive filtered by the p_t_tag_type_id column
 * @method PTagArchive findOneByTitle(string $title) Return the first PTagArchive filtered by the title column
 * @method PTagArchive findOneByOnline(boolean $online) Return the first PTagArchive filtered by the online column
 * @method PTagArchive findOneByCreatedAt(string $created_at) Return the first PTagArchive filtered by the created_at column
 * @method PTagArchive findOneByUpdatedAt(string $updated_at) Return the first PTagArchive filtered by the updated_at column
 * @method PTagArchive findOneByArchivedAt(string $archived_at) Return the first PTagArchive filtered by the archived_at column
 *
 * @method array findById(int $id) Return PTagArchive objects filtered by the id column
 * @method array findByPTTagTypeId(int $p_t_tag_type_id) Return PTagArchive objects filtered by the p_t_tag_type_id column
 * @method array findByTitle(string $title) Return PTagArchive objects filtered by the title column
 * @method array findByOnline(boolean $online) Return PTagArchive objects filtered by the online column
 * @method array findByCreatedAt(string $created_at) Return PTagArchive objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PTagArchive objects filtered by the updated_at column
 * @method array findByArchivedAt(string $archived_at) Return PTagArchive objects filtered by the archived_at column
 */
abstract class BasePTagArchiveQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePTagArchiveQuery object.
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
            $modelName = 'Politizr\\Model\\PTagArchive';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PTagArchiveQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PTagArchiveQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PTagArchiveQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PTagArchiveQuery) {
            return $criteria;
        }
        $query = new PTagArchiveQuery(null, null, $modelAlias);

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
     * @return   PTagArchive|PTagArchive[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PTagArchivePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PTagArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PTagArchive A model object, or null if the key is not found
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
     * @return                 PTagArchive A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `p_t_tag_type_id`, `title`, `online`, `created_at`, `updated_at`, `archived_at` FROM `p_tag_archive` WHERE `id` = :p0';
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
            $obj = new PTagArchive();
            $obj->hydrate($row);
            PTagArchivePeer::addInstanceToPool($obj, (string) $key);
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
     * @return PTagArchive|PTagArchive[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PTagArchive[]|mixed the list of results, formatted by the current formatter
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
     * @return PTagArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PTagArchivePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PTagArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PTagArchivePeer::ID, $keys, Criteria::IN);
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
     * @return PTagArchiveQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PTagArchivePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PTagArchivePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PTagArchivePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the p_t_tag_type_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPTTagTypeId(1234); // WHERE p_t_tag_type_id = 1234
     * $query->filterByPTTagTypeId(array(12, 34)); // WHERE p_t_tag_type_id IN (12, 34)
     * $query->filterByPTTagTypeId(array('min' => 12)); // WHERE p_t_tag_type_id >= 12
     * $query->filterByPTTagTypeId(array('max' => 12)); // WHERE p_t_tag_type_id <= 12
     * </code>
     *
     * @param     mixed $pTTagTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PTagArchiveQuery The current query, for fluid interface
     */
    public function filterByPTTagTypeId($pTTagTypeId = null, $comparison = null)
    {
        if (is_array($pTTagTypeId)) {
            $useMinMax = false;
            if (isset($pTTagTypeId['min'])) {
                $this->addUsingAlias(PTagArchivePeer::P_T_TAG_TYPE_ID, $pTTagTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pTTagTypeId['max'])) {
                $this->addUsingAlias(PTagArchivePeer::P_T_TAG_TYPE_ID, $pTTagTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PTagArchivePeer::P_T_TAG_TYPE_ID, $pTTagTypeId, $comparison);
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
     * @return PTagArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PTagArchivePeer::TITLE, $title, $comparison);
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
     * @return PTagArchiveQuery The current query, for fluid interface
     */
    public function filterByOnline($online = null, $comparison = null)
    {
        if (is_string($online)) {
            $online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PTagArchivePeer::ONLINE, $online, $comparison);
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
     * @return PTagArchiveQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PTagArchivePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PTagArchivePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PTagArchivePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PTagArchiveQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PTagArchivePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PTagArchivePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PTagArchivePeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return PTagArchiveQuery The current query, for fluid interface
     */
    public function filterByArchivedAt($archivedAt = null, $comparison = null)
    {
        if (is_array($archivedAt)) {
            $useMinMax = false;
            if (isset($archivedAt['min'])) {
                $this->addUsingAlias(PTagArchivePeer::ARCHIVED_AT, $archivedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($archivedAt['max'])) {
                $this->addUsingAlias(PTagArchivePeer::ARCHIVED_AT, $archivedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PTagArchivePeer::ARCHIVED_AT, $archivedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   PTagArchive $pTagArchive Object to remove from the list of results
     *
     * @return PTagArchiveQuery The current query, for fluid interface
     */
    public function prune($pTagArchive = null)
    {
        if ($pTagArchive) {
            $this->addUsingAlias(PTagArchivePeer::ID, $pTagArchive->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
