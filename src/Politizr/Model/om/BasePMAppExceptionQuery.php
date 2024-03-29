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
use Politizr\Model\PMAppException;
use Politizr\Model\PMAppExceptionPeer;
use Politizr\Model\PMAppExceptionQuery;
use Politizr\Model\PUser;

/**
 * @method PMAppExceptionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PMAppExceptionQuery orderByPUserId($order = Criteria::ASC) Order by the p_user_id column
 * @method PMAppExceptionQuery orderByFile($order = Criteria::ASC) Order by the file column
 * @method PMAppExceptionQuery orderByLine($order = Criteria::ASC) Order by the line column
 * @method PMAppExceptionQuery orderByCode($order = Criteria::ASC) Order by the code column
 * @method PMAppExceptionQuery orderByMessage($order = Criteria::ASC) Order by the message column
 * @method PMAppExceptionQuery orderByStackTrace($order = Criteria::ASC) Order by the stack_trace column
 * @method PMAppExceptionQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PMAppExceptionQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method PMAppExceptionQuery groupById() Group by the id column
 * @method PMAppExceptionQuery groupByPUserId() Group by the p_user_id column
 * @method PMAppExceptionQuery groupByFile() Group by the file column
 * @method PMAppExceptionQuery groupByLine() Group by the line column
 * @method PMAppExceptionQuery groupByCode() Group by the code column
 * @method PMAppExceptionQuery groupByMessage() Group by the message column
 * @method PMAppExceptionQuery groupByStackTrace() Group by the stack_trace column
 * @method PMAppExceptionQuery groupByCreatedAt() Group by the created_at column
 * @method PMAppExceptionQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method PMAppExceptionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PMAppExceptionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PMAppExceptionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PMAppExceptionQuery leftJoinPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUser relation
 * @method PMAppExceptionQuery rightJoinPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUser relation
 * @method PMAppExceptionQuery innerJoinPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PUser relation
 *
 * @method PMAppException findOne(PropelPDO $con = null) Return the first PMAppException matching the query
 * @method PMAppException findOneOrCreate(PropelPDO $con = null) Return the first PMAppException matching the query, or a new PMAppException object populated from the query conditions when no match is found
 *
 * @method PMAppException findOneByPUserId(int $p_user_id) Return the first PMAppException filtered by the p_user_id column
 * @method PMAppException findOneByFile(string $file) Return the first PMAppException filtered by the file column
 * @method PMAppException findOneByLine(int $line) Return the first PMAppException filtered by the line column
 * @method PMAppException findOneByCode(int $code) Return the first PMAppException filtered by the code column
 * @method PMAppException findOneByMessage(string $message) Return the first PMAppException filtered by the message column
 * @method PMAppException findOneByStackTrace(string $stack_trace) Return the first PMAppException filtered by the stack_trace column
 * @method PMAppException findOneByCreatedAt(string $created_at) Return the first PMAppException filtered by the created_at column
 * @method PMAppException findOneByUpdatedAt(string $updated_at) Return the first PMAppException filtered by the updated_at column
 *
 * @method array findById(int $id) Return PMAppException objects filtered by the id column
 * @method array findByPUserId(int $p_user_id) Return PMAppException objects filtered by the p_user_id column
 * @method array findByFile(string $file) Return PMAppException objects filtered by the file column
 * @method array findByLine(int $line) Return PMAppException objects filtered by the line column
 * @method array findByCode(int $code) Return PMAppException objects filtered by the code column
 * @method array findByMessage(string $message) Return PMAppException objects filtered by the message column
 * @method array findByStackTrace(string $stack_trace) Return PMAppException objects filtered by the stack_trace column
 * @method array findByCreatedAt(string $created_at) Return PMAppException objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PMAppException objects filtered by the updated_at column
 */
abstract class BasePMAppExceptionQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePMAppExceptionQuery object.
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
            $modelName = 'Politizr\\Model\\PMAppException';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PMAppExceptionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PMAppExceptionQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PMAppExceptionQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PMAppExceptionQuery) {
            return $criteria;
        }
        $query = new PMAppExceptionQuery(null, null, $modelAlias);

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
     * @return   PMAppException|PMAppException[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PMAppExceptionPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PMAppExceptionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PMAppException A model object, or null if the key is not found
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
     * @return                 PMAppException A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `p_user_id`, `file`, `line`, `code`, `message`, `stack_trace`, `created_at`, `updated_at` FROM `p_m_app_exception` WHERE `id` = :p0';
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
            $obj = new PMAppException();
            $obj->hydrate($row);
            PMAppExceptionPeer::addInstanceToPool($obj, (string) $key);
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
     * @return PMAppException|PMAppException[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PMAppException[]|mixed the list of results, formatted by the current formatter
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
     * @return PMAppExceptionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PMAppExceptionPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PMAppExceptionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PMAppExceptionPeer::ID, $keys, Criteria::IN);
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
     * @return PMAppExceptionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PMAppExceptionPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PMAppExceptionPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PMAppExceptionPeer::ID, $id, $comparison);
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
     * @return PMAppExceptionQuery The current query, for fluid interface
     */
    public function filterByPUserId($pUserId = null, $comparison = null)
    {
        if (is_array($pUserId)) {
            $useMinMax = false;
            if (isset($pUserId['min'])) {
                $this->addUsingAlias(PMAppExceptionPeer::P_USER_ID, $pUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserId['max'])) {
                $this->addUsingAlias(PMAppExceptionPeer::P_USER_ID, $pUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PMAppExceptionPeer::P_USER_ID, $pUserId, $comparison);
    }

    /**
     * Filter the query on the file column
     *
     * Example usage:
     * <code>
     * $query->filterByFile('fooValue');   // WHERE file = 'fooValue'
     * $query->filterByFile('%fooValue%'); // WHERE file LIKE '%fooValue%'
     * </code>
     *
     * @param     string $file The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PMAppExceptionQuery The current query, for fluid interface
     */
    public function filterByFile($file = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($file)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $file)) {
                $file = str_replace('*', '%', $file);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PMAppExceptionPeer::FILE, $file, $comparison);
    }

    /**
     * Filter the query on the line column
     *
     * Example usage:
     * <code>
     * $query->filterByLine(1234); // WHERE line = 1234
     * $query->filterByLine(array(12, 34)); // WHERE line IN (12, 34)
     * $query->filterByLine(array('min' => 12)); // WHERE line >= 12
     * $query->filterByLine(array('max' => 12)); // WHERE line <= 12
     * </code>
     *
     * @param     mixed $line The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PMAppExceptionQuery The current query, for fluid interface
     */
    public function filterByLine($line = null, $comparison = null)
    {
        if (is_array($line)) {
            $useMinMax = false;
            if (isset($line['min'])) {
                $this->addUsingAlias(PMAppExceptionPeer::LINE, $line['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($line['max'])) {
                $this->addUsingAlias(PMAppExceptionPeer::LINE, $line['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PMAppExceptionPeer::LINE, $line, $comparison);
    }

    /**
     * Filter the query on the code column
     *
     * Example usage:
     * <code>
     * $query->filterByCode(1234); // WHERE code = 1234
     * $query->filterByCode(array(12, 34)); // WHERE code IN (12, 34)
     * $query->filterByCode(array('min' => 12)); // WHERE code >= 12
     * $query->filterByCode(array('max' => 12)); // WHERE code <= 12
     * </code>
     *
     * @param     mixed $code The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PMAppExceptionQuery The current query, for fluid interface
     */
    public function filterByCode($code = null, $comparison = null)
    {
        if (is_array($code)) {
            $useMinMax = false;
            if (isset($code['min'])) {
                $this->addUsingAlias(PMAppExceptionPeer::CODE, $code['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($code['max'])) {
                $this->addUsingAlias(PMAppExceptionPeer::CODE, $code['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PMAppExceptionPeer::CODE, $code, $comparison);
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
     * @return PMAppExceptionQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PMAppExceptionPeer::MESSAGE, $message, $comparison);
    }

    /**
     * Filter the query on the stack_trace column
     *
     * Example usage:
     * <code>
     * $query->filterByStackTrace('fooValue');   // WHERE stack_trace = 'fooValue'
     * $query->filterByStackTrace('%fooValue%'); // WHERE stack_trace LIKE '%fooValue%'
     * </code>
     *
     * @param     string $stackTrace The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PMAppExceptionQuery The current query, for fluid interface
     */
    public function filterByStackTrace($stackTrace = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($stackTrace)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $stackTrace)) {
                $stackTrace = str_replace('*', '%', $stackTrace);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PMAppExceptionPeer::STACK_TRACE, $stackTrace, $comparison);
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
     * @return PMAppExceptionQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PMAppExceptionPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PMAppExceptionPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PMAppExceptionPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PMAppExceptionQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PMAppExceptionPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PMAppExceptionPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PMAppExceptionPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related PUser object
     *
     * @param   PUser|PropelObjectCollection $pUser The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PMAppExceptionQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUser($pUser, $comparison = null)
    {
        if ($pUser instanceof PUser) {
            return $this
                ->addUsingAlias(PMAppExceptionPeer::P_USER_ID, $pUser->getId(), $comparison);
        } elseif ($pUser instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PMAppExceptionPeer::P_USER_ID, $pUser->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return PMAppExceptionQuery The current query, for fluid interface
     */
    public function joinPUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function usePUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUser', '\Politizr\Model\PUserQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   PMAppException $pMAppException Object to remove from the list of results
     *
     * @return PMAppExceptionQuery The current query, for fluid interface
     */
    public function prune($pMAppException = null)
    {
        if ($pMAppException) {
            $this->addUsingAlias(PMAppExceptionPeer::ID, $pMAppException->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     PMAppExceptionQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PMAppExceptionPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PMAppExceptionQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PMAppExceptionPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PMAppExceptionQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PMAppExceptionPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PMAppExceptionQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PMAppExceptionPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PMAppExceptionQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PMAppExceptionPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PMAppExceptionQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PMAppExceptionPeer::CREATED_AT);
    }
}
