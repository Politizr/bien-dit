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
use Politizr\Model\PTag;
use Politizr\Model\PUQTaggedT;
use Politizr\Model\PUQTaggedTPeer;
use Politizr\Model\PUQTaggedTQuery;
use Politizr\Model\PUQualification;

/**
 * @method PUQTaggedTQuery orderByPUQualificationId($order = Criteria::ASC) Order by the p_u_qualification_id column
 * @method PUQTaggedTQuery orderByPTagId($order = Criteria::ASC) Order by the p_tag_id column
 * @method PUQTaggedTQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PUQTaggedTQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method PUQTaggedTQuery groupByPUQualificationId() Group by the p_u_qualification_id column
 * @method PUQTaggedTQuery groupByPTagId() Group by the p_tag_id column
 * @method PUQTaggedTQuery groupByCreatedAt() Group by the created_at column
 * @method PUQTaggedTQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method PUQTaggedTQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PUQTaggedTQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PUQTaggedTQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PUQTaggedTQuery leftJoinPuqTaggedTPUQualification($relationAlias = null) Adds a LEFT JOIN clause to the query using the PuqTaggedTPUQualification relation
 * @method PUQTaggedTQuery rightJoinPuqTaggedTPUQualification($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PuqTaggedTPUQualification relation
 * @method PUQTaggedTQuery innerJoinPuqTaggedTPUQualification($relationAlias = null) Adds a INNER JOIN clause to the query using the PuqTaggedTPUQualification relation
 *
 * @method PUQTaggedTQuery leftJoinPuqTaggedTPTag($relationAlias = null) Adds a LEFT JOIN clause to the query using the PuqTaggedTPTag relation
 * @method PUQTaggedTQuery rightJoinPuqTaggedTPTag($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PuqTaggedTPTag relation
 * @method PUQTaggedTQuery innerJoinPuqTaggedTPTag($relationAlias = null) Adds a INNER JOIN clause to the query using the PuqTaggedTPTag relation
 *
 * @method PUQTaggedT findOne(PropelPDO $con = null) Return the first PUQTaggedT matching the query
 * @method PUQTaggedT findOneOrCreate(PropelPDO $con = null) Return the first PUQTaggedT matching the query, or a new PUQTaggedT object populated from the query conditions when no match is found
 *
 * @method PUQTaggedT findOneByPUQualificationId(int $p_u_qualification_id) Return the first PUQTaggedT filtered by the p_u_qualification_id column
 * @method PUQTaggedT findOneByPTagId(int $p_tag_id) Return the first PUQTaggedT filtered by the p_tag_id column
 * @method PUQTaggedT findOneByCreatedAt(string $created_at) Return the first PUQTaggedT filtered by the created_at column
 * @method PUQTaggedT findOneByUpdatedAt(string $updated_at) Return the first PUQTaggedT filtered by the updated_at column
 *
 * @method array findByPUQualificationId(int $p_u_qualification_id) Return PUQTaggedT objects filtered by the p_u_qualification_id column
 * @method array findByPTagId(int $p_tag_id) Return PUQTaggedT objects filtered by the p_tag_id column
 * @method array findByCreatedAt(string $created_at) Return PUQTaggedT objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PUQTaggedT objects filtered by the updated_at column
 */
abstract class BasePUQTaggedTQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePUQTaggedTQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Politizr\\Model\\PUQTaggedT', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PUQTaggedTQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PUQTaggedTQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PUQTaggedTQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PUQTaggedTQuery) {
            return $criteria;
        }
        $query = new PUQTaggedTQuery();
        if (null !== $modelAlias) {
            $query->setModelAlias($modelAlias);
        }
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
     * $obj = $c->findPk(array(12, 34), $con);
     * </code>
     *
     * @param array $key Primary key to use for the query
                         A Primary key composition: [$p_u_qualification_id, $p_tag_id]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   PUQTaggedT|PUQTaggedT[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PUQTaggedTPeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PUQTaggedTPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * Find object by primary key using raw SQL to go fast.
     * Bypass doSelect() and the object formatter by using generated code.
     *
     * @param     mixed $key Primary key to use for the query
     * @param     PropelPDO $con A connection object
     *
     * @return                 PUQTaggedT A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `p_u_qualification_id`, `p_tag_id`, `created_at`, `updated_at` FROM `p_u_q_tagged_t` WHERE `p_u_qualification_id` = :p0 AND `p_tag_id` = :p1';
        try {
            $stmt = $con->prepare($sql);
            $stmt->bindValue(':p0', $key[0], PDO::PARAM_INT);
            $stmt->bindValue(':p1', $key[1], PDO::PARAM_INT);
            $stmt->execute();
        } catch (Exception $e) {
            Propel::log($e->getMessage(), Propel::LOG_ERR);
            throw new PropelException(sprintf('Unable to execute SELECT statement [%s]', $sql), $e);
        }
        $obj = null;
        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $obj = new PUQTaggedT();
            $obj->hydrate($row);
            PUQTaggedTPeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return PUQTaggedT|PUQTaggedT[]|mixed the result, formatted by the current formatter
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
     * $objs = $c->findPks(array(array(12, 56), array(832, 123), array(123, 456)), $con);
     * </code>
     * @param     array $keys Primary keys to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return PropelObjectCollection|PUQTaggedT[]|mixed the list of results, formatted by the current formatter
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
     * @return PUQTaggedTQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(PUQTaggedTPeer::P_U_QUALIFICATION_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(PUQTaggedTPeer::P_TAG_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PUQTaggedTQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(PUQTaggedTPeer::P_U_QUALIFICATION_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(PUQTaggedTPeer::P_TAG_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the p_u_qualification_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPUQualificationId(1234); // WHERE p_u_qualification_id = 1234
     * $query->filterByPUQualificationId(array(12, 34)); // WHERE p_u_qualification_id IN (12, 34)
     * $query->filterByPUQualificationId(array('min' => 12)); // WHERE p_u_qualification_id >= 12
     * $query->filterByPUQualificationId(array('max' => 12)); // WHERE p_u_qualification_id <= 12
     * </code>
     *
     * @see       filterByPuqTaggedTPUQualification()
     *
     * @param     mixed $pUQualificationId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUQTaggedTQuery The current query, for fluid interface
     */
    public function filterByPUQualificationId($pUQualificationId = null, $comparison = null)
    {
        if (is_array($pUQualificationId)) {
            $useMinMax = false;
            if (isset($pUQualificationId['min'])) {
                $this->addUsingAlias(PUQTaggedTPeer::P_U_QUALIFICATION_ID, $pUQualificationId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUQualificationId['max'])) {
                $this->addUsingAlias(PUQTaggedTPeer::P_U_QUALIFICATION_ID, $pUQualificationId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUQTaggedTPeer::P_U_QUALIFICATION_ID, $pUQualificationId, $comparison);
    }

    /**
     * Filter the query on the p_tag_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPTagId(1234); // WHERE p_tag_id = 1234
     * $query->filterByPTagId(array(12, 34)); // WHERE p_tag_id IN (12, 34)
     * $query->filterByPTagId(array('min' => 12)); // WHERE p_tag_id >= 12
     * $query->filterByPTagId(array('max' => 12)); // WHERE p_tag_id <= 12
     * </code>
     *
     * @see       filterByPuqTaggedTPTag()
     *
     * @param     mixed $pTagId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUQTaggedTQuery The current query, for fluid interface
     */
    public function filterByPTagId($pTagId = null, $comparison = null)
    {
        if (is_array($pTagId)) {
            $useMinMax = false;
            if (isset($pTagId['min'])) {
                $this->addUsingAlias(PUQTaggedTPeer::P_TAG_ID, $pTagId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pTagId['max'])) {
                $this->addUsingAlias(PUQTaggedTPeer::P_TAG_ID, $pTagId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUQTaggedTPeer::P_TAG_ID, $pTagId, $comparison);
    }

    /**
     * Filter the query on the created_at column
     *
     * Example usage:
     * <code>
     * $query->filterByCreatedAt('2011-03-14'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt('now'); // WHERE created_at = '2011-03-14'
     * $query->filterByCreatedAt(array('max' => 'yesterday')); // WHERE created_at > '2011-03-13'
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
     * @return PUQTaggedTQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PUQTaggedTPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PUQTaggedTPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUQTaggedTPeer::CREATED_AT, $createdAt, $comparison);
    }

    /**
     * Filter the query on the updated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByUpdatedAt('2011-03-14'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt('now'); // WHERE updated_at = '2011-03-14'
     * $query->filterByUpdatedAt(array('max' => 'yesterday')); // WHERE updated_at > '2011-03-13'
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
     * @return PUQTaggedTQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PUQTaggedTPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PUQTaggedTPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUQTaggedTPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related PUQualification object
     *
     * @param   PUQualification|PropelObjectCollection $pUQualification The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUQTaggedTQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPuqTaggedTPUQualification($pUQualification, $comparison = null)
    {
        if ($pUQualification instanceof PUQualification) {
            return $this
                ->addUsingAlias(PUQTaggedTPeer::P_U_QUALIFICATION_ID, $pUQualification->getId(), $comparison);
        } elseif ($pUQualification instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PUQTaggedTPeer::P_U_QUALIFICATION_ID, $pUQualification->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPuqTaggedTPUQualification() only accepts arguments of type PUQualification or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PuqTaggedTPUQualification relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUQTaggedTQuery The current query, for fluid interface
     */
    public function joinPuqTaggedTPUQualification($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PuqTaggedTPUQualification');

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
            $this->addJoinObject($join, 'PuqTaggedTPUQualification');
        }

        return $this;
    }

    /**
     * Use the PuqTaggedTPUQualification relation PUQualification object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUQualificationQuery A secondary query class using the current class as primary query
     */
    public function usePuqTaggedTPUQualificationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPuqTaggedTPUQualification($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PuqTaggedTPUQualification', '\Politizr\Model\PUQualificationQuery');
    }

    /**
     * Filter the query by a related PTag object
     *
     * @param   PTag|PropelObjectCollection $pTag The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUQTaggedTQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPuqTaggedTPTag($pTag, $comparison = null)
    {
        if ($pTag instanceof PTag) {
            return $this
                ->addUsingAlias(PUQTaggedTPeer::P_TAG_ID, $pTag->getId(), $comparison);
        } elseif ($pTag instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PUQTaggedTPeer::P_TAG_ID, $pTag->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPuqTaggedTPTag() only accepts arguments of type PTag or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PuqTaggedTPTag relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUQTaggedTQuery The current query, for fluid interface
     */
    public function joinPuqTaggedTPTag($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PuqTaggedTPTag');

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
            $this->addJoinObject($join, 'PuqTaggedTPTag');
        }

        return $this;
    }

    /**
     * Use the PuqTaggedTPTag relation PTag object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PTagQuery A secondary query class using the current class as primary query
     */
    public function usePuqTaggedTPTagQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPuqTaggedTPTag($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PuqTaggedTPTag', '\Politizr\Model\PTagQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   PUQTaggedT $pUQTaggedT Object to remove from the list of results
     *
     * @return PUQTaggedTQuery The current query, for fluid interface
     */
    public function prune($pUQTaggedT = null)
    {
        if ($pUQTaggedT) {
            $this->addCond('pruneCond0', $this->getAliasedColName(PUQTaggedTPeer::P_U_QUALIFICATION_ID), $pUQTaggedT->getPUQualificationId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(PUQTaggedTPeer::P_TAG_ID), $pUQTaggedT->getPTagId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     PUQTaggedTQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PUQTaggedTPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PUQTaggedTQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUQTaggedTPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PUQTaggedTQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUQTaggedTPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PUQTaggedTQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PUQTaggedTPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PUQTaggedTQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUQTaggedTPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PUQTaggedTQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUQTaggedTPeer::CREATED_AT);
    }
}
