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
use Politizr\Model\PMUserMessageArchive;
use Politizr\Model\PMUserMessageArchivePeer;
use Politizr\Model\PMUserMessageArchiveQuery;

/**
 * @method PMUserMessageArchiveQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PMUserMessageArchiveQuery orderByPUserId($order = Criteria::ASC) Order by the p_user_id column
 * @method PMUserMessageArchiveQuery orderByPObjectName($order = Criteria::ASC) Order by the p_object_name column
 * @method PMUserMessageArchiveQuery orderByPObjectId($order = Criteria::ASC) Order by the p_object_id column
 * @method PMUserMessageArchiveQuery orderByMessage($order = Criteria::ASC) Order by the message column
 * @method PMUserMessageArchiveQuery orderByEvolReput($order = Criteria::ASC) Order by the evol_reput column
 * @method PMUserMessageArchiveQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PMUserMessageArchiveQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PMUserMessageArchiveQuery orderByArchivedAt($order = Criteria::ASC) Order by the archived_at column
 *
 * @method PMUserMessageArchiveQuery groupById() Group by the id column
 * @method PMUserMessageArchiveQuery groupByPUserId() Group by the p_user_id column
 * @method PMUserMessageArchiveQuery groupByPObjectName() Group by the p_object_name column
 * @method PMUserMessageArchiveQuery groupByPObjectId() Group by the p_object_id column
 * @method PMUserMessageArchiveQuery groupByMessage() Group by the message column
 * @method PMUserMessageArchiveQuery groupByEvolReput() Group by the evol_reput column
 * @method PMUserMessageArchiveQuery groupByCreatedAt() Group by the created_at column
 * @method PMUserMessageArchiveQuery groupByUpdatedAt() Group by the updated_at column
 * @method PMUserMessageArchiveQuery groupByArchivedAt() Group by the archived_at column
 *
 * @method PMUserMessageArchiveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PMUserMessageArchiveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PMUserMessageArchiveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PMUserMessageArchive findOne(PropelPDO $con = null) Return the first PMUserMessageArchive matching the query
 * @method PMUserMessageArchive findOneOrCreate(PropelPDO $con = null) Return the first PMUserMessageArchive matching the query, or a new PMUserMessageArchive object populated from the query conditions when no match is found
 *
 * @method PMUserMessageArchive findOneByPUserId(int $p_user_id) Return the first PMUserMessageArchive filtered by the p_user_id column
 * @method PMUserMessageArchive findOneByPObjectName(string $p_object_name) Return the first PMUserMessageArchive filtered by the p_object_name column
 * @method PMUserMessageArchive findOneByPObjectId(int $p_object_id) Return the first PMUserMessageArchive filtered by the p_object_id column
 * @method PMUserMessageArchive findOneByMessage(string $message) Return the first PMUserMessageArchive filtered by the message column
 * @method PMUserMessageArchive findOneByEvolReput(int $evol_reput) Return the first PMUserMessageArchive filtered by the evol_reput column
 * @method PMUserMessageArchive findOneByCreatedAt(string $created_at) Return the first PMUserMessageArchive filtered by the created_at column
 * @method PMUserMessageArchive findOneByUpdatedAt(string $updated_at) Return the first PMUserMessageArchive filtered by the updated_at column
 * @method PMUserMessageArchive findOneByArchivedAt(string $archived_at) Return the first PMUserMessageArchive filtered by the archived_at column
 *
 * @method array findById(int $id) Return PMUserMessageArchive objects filtered by the id column
 * @method array findByPUserId(int $p_user_id) Return PMUserMessageArchive objects filtered by the p_user_id column
 * @method array findByPObjectName(string $p_object_name) Return PMUserMessageArchive objects filtered by the p_object_name column
 * @method array findByPObjectId(int $p_object_id) Return PMUserMessageArchive objects filtered by the p_object_id column
 * @method array findByMessage(string $message) Return PMUserMessageArchive objects filtered by the message column
 * @method array findByEvolReput(int $evol_reput) Return PMUserMessageArchive objects filtered by the evol_reput column
 * @method array findByCreatedAt(string $created_at) Return PMUserMessageArchive objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PMUserMessageArchive objects filtered by the updated_at column
 * @method array findByArchivedAt(string $archived_at) Return PMUserMessageArchive objects filtered by the archived_at column
 */
abstract class BasePMUserMessageArchiveQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePMUserMessageArchiveQuery object.
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
            $modelName = 'Politizr\\Model\\PMUserMessageArchive';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PMUserMessageArchiveQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PMUserMessageArchiveQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PMUserMessageArchiveQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PMUserMessageArchiveQuery) {
            return $criteria;
        }
        $query = new PMUserMessageArchiveQuery(null, null, $modelAlias);

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
     * @return   PMUserMessageArchive|PMUserMessageArchive[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PMUserMessageArchivePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PMUserMessageArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PMUserMessageArchive A model object, or null if the key is not found
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
     * @return                 PMUserMessageArchive A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `p_user_id`, `p_object_name`, `p_object_id`, `message`, `evol_reput`, `created_at`, `updated_at`, `archived_at` FROM `p_m_user_message_archive` WHERE `id` = :p0';
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
            $obj = new PMUserMessageArchive();
            $obj->hydrate($row);
            PMUserMessageArchivePeer::addInstanceToPool($obj, (string) $key);
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
     * @return PMUserMessageArchive|PMUserMessageArchive[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PMUserMessageArchive[]|mixed the list of results, formatted by the current formatter
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
     * @return PMUserMessageArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PMUserMessageArchivePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PMUserMessageArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PMUserMessageArchivePeer::ID, $keys, Criteria::IN);
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
     * @return PMUserMessageArchiveQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PMUserMessageArchivePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PMUserMessageArchivePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PMUserMessageArchivePeer::ID, $id, $comparison);
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
     * @return PMUserMessageArchiveQuery The current query, for fluid interface
     */
    public function filterByPUserId($pUserId = null, $comparison = null)
    {
        if (is_array($pUserId)) {
            $useMinMax = false;
            if (isset($pUserId['min'])) {
                $this->addUsingAlias(PMUserMessageArchivePeer::P_USER_ID, $pUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserId['max'])) {
                $this->addUsingAlias(PMUserMessageArchivePeer::P_USER_ID, $pUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PMUserMessageArchivePeer::P_USER_ID, $pUserId, $comparison);
    }

    /**
     * Filter the query on the p_object_name column
     *
     * Example usage:
     * <code>
     * $query->filterByPObjectName('fooValue');   // WHERE p_object_name = 'fooValue'
     * $query->filterByPObjectName('%fooValue%'); // WHERE p_object_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $pObjectName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PMUserMessageArchiveQuery The current query, for fluid interface
     */
    public function filterByPObjectName($pObjectName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($pObjectName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $pObjectName)) {
                $pObjectName = str_replace('*', '%', $pObjectName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PMUserMessageArchivePeer::P_OBJECT_NAME, $pObjectName, $comparison);
    }

    /**
     * Filter the query on the p_object_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPObjectId(1234); // WHERE p_object_id = 1234
     * $query->filterByPObjectId(array(12, 34)); // WHERE p_object_id IN (12, 34)
     * $query->filterByPObjectId(array('min' => 12)); // WHERE p_object_id >= 12
     * $query->filterByPObjectId(array('max' => 12)); // WHERE p_object_id <= 12
     * </code>
     *
     * @param     mixed $pObjectId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PMUserMessageArchiveQuery The current query, for fluid interface
     */
    public function filterByPObjectId($pObjectId = null, $comparison = null)
    {
        if (is_array($pObjectId)) {
            $useMinMax = false;
            if (isset($pObjectId['min'])) {
                $this->addUsingAlias(PMUserMessageArchivePeer::P_OBJECT_ID, $pObjectId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pObjectId['max'])) {
                $this->addUsingAlias(PMUserMessageArchivePeer::P_OBJECT_ID, $pObjectId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PMUserMessageArchivePeer::P_OBJECT_ID, $pObjectId, $comparison);
    }

    /**
     * Filter the query on the message column
     *
     * Example usage:
     * <code>
     * $query->filterByMessage('fooValue');   // WHERE message = 'fooValue'
     * $query->filterByMessage('%fooValue%'); // WHERE message LIKE '%fooValue%'
     * </code>
     *
     * @param     string $message The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PMUserMessageArchiveQuery The current query, for fluid interface
     */
    public function filterByMessage($message = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($message)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $message)) {
                $message = str_replace('*', '%', $message);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PMUserMessageArchivePeer::MESSAGE, $message, $comparison);
    }

    /**
     * Filter the query on the evol_reput column
     *
     * Example usage:
     * <code>
     * $query->filterByEvolReput(1234); // WHERE evol_reput = 1234
     * $query->filterByEvolReput(array(12, 34)); // WHERE evol_reput IN (12, 34)
     * $query->filterByEvolReput(array('min' => 12)); // WHERE evol_reput >= 12
     * $query->filterByEvolReput(array('max' => 12)); // WHERE evol_reput <= 12
     * </code>
     *
     * @param     mixed $evolReput The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PMUserMessageArchiveQuery The current query, for fluid interface
     */
    public function filterByEvolReput($evolReput = null, $comparison = null)
    {
        if (is_array($evolReput)) {
            $useMinMax = false;
            if (isset($evolReput['min'])) {
                $this->addUsingAlias(PMUserMessageArchivePeer::EVOL_REPUT, $evolReput['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($evolReput['max'])) {
                $this->addUsingAlias(PMUserMessageArchivePeer::EVOL_REPUT, $evolReput['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PMUserMessageArchivePeer::EVOL_REPUT, $evolReput, $comparison);
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
     * @return PMUserMessageArchiveQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PMUserMessageArchivePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PMUserMessageArchivePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PMUserMessageArchivePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PMUserMessageArchiveQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PMUserMessageArchivePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PMUserMessageArchivePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PMUserMessageArchivePeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return PMUserMessageArchiveQuery The current query, for fluid interface
     */
    public function filterByArchivedAt($archivedAt = null, $comparison = null)
    {
        if (is_array($archivedAt)) {
            $useMinMax = false;
            if (isset($archivedAt['min'])) {
                $this->addUsingAlias(PMUserMessageArchivePeer::ARCHIVED_AT, $archivedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($archivedAt['max'])) {
                $this->addUsingAlias(PMUserMessageArchivePeer::ARCHIVED_AT, $archivedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PMUserMessageArchivePeer::ARCHIVED_AT, $archivedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   PMUserMessageArchive $pMUserMessageArchive Object to remove from the list of results
     *
     * @return PMUserMessageArchiveQuery The current query, for fluid interface
     */
    public function prune($pMUserMessageArchive = null)
    {
        if ($pMUserMessageArchive) {
            $this->addUsingAlias(PMUserMessageArchivePeer::ID, $pMUserMessageArchive->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
