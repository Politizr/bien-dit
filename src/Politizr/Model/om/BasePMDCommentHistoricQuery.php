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
use Politizr\Model\PDDComment;
use Politizr\Model\PMDCommentHistoric;
use Politizr\Model\PMDCommentHistoricPeer;
use Politizr\Model\PMDCommentHistoricQuery;
use Politizr\Model\PUser;

/**
 * @method PMDCommentHistoricQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PMDCommentHistoricQuery orderByPDDCommentId($order = Criteria::ASC) Order by the p_d_d_comment_id column
 * @method PMDCommentHistoricQuery orderByPUserId($order = Criteria::ASC) Order by the p_user_id column
 * @method PMDCommentHistoricQuery orderByPObjectId($order = Criteria::ASC) Order by the p_object_id column
 * @method PMDCommentHistoricQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method PMDCommentHistoricQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PMDCommentHistoricQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method PMDCommentHistoricQuery groupById() Group by the id column
 * @method PMDCommentHistoricQuery groupByPDDCommentId() Group by the p_d_d_comment_id column
 * @method PMDCommentHistoricQuery groupByPUserId() Group by the p_user_id column
 * @method PMDCommentHistoricQuery groupByPObjectId() Group by the p_object_id column
 * @method PMDCommentHistoricQuery groupByDescription() Group by the description column
 * @method PMDCommentHistoricQuery groupByCreatedAt() Group by the created_at column
 * @method PMDCommentHistoricQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method PMDCommentHistoricQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PMDCommentHistoricQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PMDCommentHistoricQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PMDCommentHistoricQuery leftJoinPDDComment($relationAlias = null) Adds a LEFT JOIN clause to the query using the PDDComment relation
 * @method PMDCommentHistoricQuery rightJoinPDDComment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PDDComment relation
 * @method PMDCommentHistoricQuery innerJoinPDDComment($relationAlias = null) Adds a INNER JOIN clause to the query using the PDDComment relation
 *
 * @method PMDCommentHistoricQuery leftJoinPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUser relation
 * @method PMDCommentHistoricQuery rightJoinPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUser relation
 * @method PMDCommentHistoricQuery innerJoinPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PUser relation
 *
 * @method PMDCommentHistoric findOne(PropelPDO $con = null) Return the first PMDCommentHistoric matching the query
 * @method PMDCommentHistoric findOneOrCreate(PropelPDO $con = null) Return the first PMDCommentHistoric matching the query, or a new PMDCommentHistoric object populated from the query conditions when no match is found
 *
 * @method PMDCommentHistoric findOneByPDDCommentId(int $p_d_d_comment_id) Return the first PMDCommentHistoric filtered by the p_d_d_comment_id column
 * @method PMDCommentHistoric findOneByPUserId(int $p_user_id) Return the first PMDCommentHistoric filtered by the p_user_id column
 * @method PMDCommentHistoric findOneByPObjectId(int $p_object_id) Return the first PMDCommentHistoric filtered by the p_object_id column
 * @method PMDCommentHistoric findOneByDescription(string $description) Return the first PMDCommentHistoric filtered by the description column
 * @method PMDCommentHistoric findOneByCreatedAt(string $created_at) Return the first PMDCommentHistoric filtered by the created_at column
 * @method PMDCommentHistoric findOneByUpdatedAt(string $updated_at) Return the first PMDCommentHistoric filtered by the updated_at column
 *
 * @method array findById(int $id) Return PMDCommentHistoric objects filtered by the id column
 * @method array findByPDDCommentId(int $p_d_d_comment_id) Return PMDCommentHistoric objects filtered by the p_d_d_comment_id column
 * @method array findByPUserId(int $p_user_id) Return PMDCommentHistoric objects filtered by the p_user_id column
 * @method array findByPObjectId(int $p_object_id) Return PMDCommentHistoric objects filtered by the p_object_id column
 * @method array findByDescription(string $description) Return PMDCommentHistoric objects filtered by the description column
 * @method array findByCreatedAt(string $created_at) Return PMDCommentHistoric objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PMDCommentHistoric objects filtered by the updated_at column
 */
abstract class BasePMDCommentHistoricQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePMDCommentHistoricQuery object.
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
            $modelName = 'Politizr\\Model\\PMDCommentHistoric';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PMDCommentHistoricQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PMDCommentHistoricQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PMDCommentHistoricQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PMDCommentHistoricQuery) {
            return $criteria;
        }
        $query = new PMDCommentHistoricQuery(null, null, $modelAlias);

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
     * @return   PMDCommentHistoric|PMDCommentHistoric[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PMDCommentHistoricPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PMDCommentHistoricPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PMDCommentHistoric A model object, or null if the key is not found
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
     * @return                 PMDCommentHistoric A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `p_d_d_comment_id`, `p_user_id`, `p_object_id`, `description`, `created_at`, `updated_at` FROM `p_m_d_comment_historic` WHERE `id` = :p0';
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
            $obj = new PMDCommentHistoric();
            $obj->hydrate($row);
            PMDCommentHistoricPeer::addInstanceToPool($obj, (string) $key);
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
     * @return PMDCommentHistoric|PMDCommentHistoric[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PMDCommentHistoric[]|mixed the list of results, formatted by the current formatter
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
     * @return PMDCommentHistoricQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PMDCommentHistoricPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PMDCommentHistoricQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PMDCommentHistoricPeer::ID, $keys, Criteria::IN);
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
     * @return PMDCommentHistoricQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PMDCommentHistoricPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PMDCommentHistoricPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PMDCommentHistoricPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the p_d_d_comment_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPDDCommentId(1234); // WHERE p_d_d_comment_id = 1234
     * $query->filterByPDDCommentId(array(12, 34)); // WHERE p_d_d_comment_id IN (12, 34)
     * $query->filterByPDDCommentId(array('min' => 12)); // WHERE p_d_d_comment_id >= 12
     * $query->filterByPDDCommentId(array('max' => 12)); // WHERE p_d_d_comment_id <= 12
     * </code>
     *
     * @see       filterByPDDComment()
     *
     * @param     mixed $pDDCommentId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PMDCommentHistoricQuery The current query, for fluid interface
     */
    public function filterByPDDCommentId($pDDCommentId = null, $comparison = null)
    {
        if (is_array($pDDCommentId)) {
            $useMinMax = false;
            if (isset($pDDCommentId['min'])) {
                $this->addUsingAlias(PMDCommentHistoricPeer::P_D_D_COMMENT_ID, $pDDCommentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pDDCommentId['max'])) {
                $this->addUsingAlias(PMDCommentHistoricPeer::P_D_D_COMMENT_ID, $pDDCommentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PMDCommentHistoricPeer::P_D_D_COMMENT_ID, $pDDCommentId, $comparison);
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
     * @return PMDCommentHistoricQuery The current query, for fluid interface
     */
    public function filterByPUserId($pUserId = null, $comparison = null)
    {
        if (is_array($pUserId)) {
            $useMinMax = false;
            if (isset($pUserId['min'])) {
                $this->addUsingAlias(PMDCommentHistoricPeer::P_USER_ID, $pUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserId['max'])) {
                $this->addUsingAlias(PMDCommentHistoricPeer::P_USER_ID, $pUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PMDCommentHistoricPeer::P_USER_ID, $pUserId, $comparison);
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
     * @return PMDCommentHistoricQuery The current query, for fluid interface
     */
    public function filterByPObjectId($pObjectId = null, $comparison = null)
    {
        if (is_array($pObjectId)) {
            $useMinMax = false;
            if (isset($pObjectId['min'])) {
                $this->addUsingAlias(PMDCommentHistoricPeer::P_OBJECT_ID, $pObjectId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pObjectId['max'])) {
                $this->addUsingAlias(PMDCommentHistoricPeer::P_OBJECT_ID, $pObjectId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PMDCommentHistoricPeer::P_OBJECT_ID, $pObjectId, $comparison);
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
     * @return PMDCommentHistoricQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PMDCommentHistoricPeer::DESCRIPTION, $description, $comparison);
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
     * @return PMDCommentHistoricQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PMDCommentHistoricPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PMDCommentHistoricPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PMDCommentHistoricPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PMDCommentHistoricQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PMDCommentHistoricPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PMDCommentHistoricPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PMDCommentHistoricPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related PDDComment object
     *
     * @param   PDDComment|PropelObjectCollection $pDDComment The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PMDCommentHistoricQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPDDComment($pDDComment, $comparison = null)
    {
        if ($pDDComment instanceof PDDComment) {
            return $this
                ->addUsingAlias(PMDCommentHistoricPeer::P_D_D_COMMENT_ID, $pDDComment->getId(), $comparison);
        } elseif ($pDDComment instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PMDCommentHistoricPeer::P_D_D_COMMENT_ID, $pDDComment->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPDDComment() only accepts arguments of type PDDComment or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PDDComment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PMDCommentHistoricQuery The current query, for fluid interface
     */
    public function joinPDDComment($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PDDComment');

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
            $this->addJoinObject($join, 'PDDComment');
        }

        return $this;
    }

    /**
     * Use the PDDComment relation PDDComment object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PDDCommentQuery A secondary query class using the current class as primary query
     */
    public function usePDDCommentQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPDDComment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PDDComment', '\Politizr\Model\PDDCommentQuery');
    }

    /**
     * Filter the query by a related PUser object
     *
     * @param   PUser|PropelObjectCollection $pUser The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PMDCommentHistoricQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUser($pUser, $comparison = null)
    {
        if ($pUser instanceof PUser) {
            return $this
                ->addUsingAlias(PMDCommentHistoricPeer::P_USER_ID, $pUser->getId(), $comparison);
        } elseif ($pUser instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PMDCommentHistoricPeer::P_USER_ID, $pUser->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return PMDCommentHistoricQuery The current query, for fluid interface
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
     * @param   PMDCommentHistoric $pMDCommentHistoric Object to remove from the list of results
     *
     * @return PMDCommentHistoricQuery The current query, for fluid interface
     */
    public function prune($pMDCommentHistoric = null)
    {
        if ($pMDCommentHistoric) {
            $this->addUsingAlias(PMDCommentHistoricPeer::ID, $pMDCommentHistoric->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     PMDCommentHistoricQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PMDCommentHistoricPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PMDCommentHistoricQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PMDCommentHistoricPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PMDCommentHistoricQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PMDCommentHistoricPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PMDCommentHistoricQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PMDCommentHistoricPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PMDCommentHistoricQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PMDCommentHistoricPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PMDCommentHistoricQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PMDCommentHistoricPeer::CREATED_AT);
    }
}
