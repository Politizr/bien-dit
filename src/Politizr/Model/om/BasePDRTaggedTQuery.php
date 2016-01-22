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
use Politizr\Model\PDRTaggedT;
use Politizr\Model\PDRTaggedTPeer;
use Politizr\Model\PDRTaggedTQuery;
use Politizr\Model\PDReaction;
use Politizr\Model\PTag;

/**
 * @method PDRTaggedTQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PDRTaggedTQuery orderByPDReactionId($order = Criteria::ASC) Order by the p_d_reaction_id column
 * @method PDRTaggedTQuery orderByPTagId($order = Criteria::ASC) Order by the p_tag_id column
 * @method PDRTaggedTQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PDRTaggedTQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method PDRTaggedTQuery groupById() Group by the id column
 * @method PDRTaggedTQuery groupByPDReactionId() Group by the p_d_reaction_id column
 * @method PDRTaggedTQuery groupByPTagId() Group by the p_tag_id column
 * @method PDRTaggedTQuery groupByCreatedAt() Group by the created_at column
 * @method PDRTaggedTQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method PDRTaggedTQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PDRTaggedTQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PDRTaggedTQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PDRTaggedTQuery leftJoinPDReaction($relationAlias = null) Adds a LEFT JOIN clause to the query using the PDReaction relation
 * @method PDRTaggedTQuery rightJoinPDReaction($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PDReaction relation
 * @method PDRTaggedTQuery innerJoinPDReaction($relationAlias = null) Adds a INNER JOIN clause to the query using the PDReaction relation
 *
 * @method PDRTaggedTQuery leftJoinPTag($relationAlias = null) Adds a LEFT JOIN clause to the query using the PTag relation
 * @method PDRTaggedTQuery rightJoinPTag($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PTag relation
 * @method PDRTaggedTQuery innerJoinPTag($relationAlias = null) Adds a INNER JOIN clause to the query using the PTag relation
 *
 * @method PDRTaggedT findOne(PropelPDO $con = null) Return the first PDRTaggedT matching the query
 * @method PDRTaggedT findOneOrCreate(PropelPDO $con = null) Return the first PDRTaggedT matching the query, or a new PDRTaggedT object populated from the query conditions when no match is found
 *
 * @method PDRTaggedT findOneByPDReactionId(int $p_d_reaction_id) Return the first PDRTaggedT filtered by the p_d_reaction_id column
 * @method PDRTaggedT findOneByPTagId(int $p_tag_id) Return the first PDRTaggedT filtered by the p_tag_id column
 * @method PDRTaggedT findOneByCreatedAt(string $created_at) Return the first PDRTaggedT filtered by the created_at column
 * @method PDRTaggedT findOneByUpdatedAt(string $updated_at) Return the first PDRTaggedT filtered by the updated_at column
 *
 * @method array findById(int $id) Return PDRTaggedT objects filtered by the id column
 * @method array findByPDReactionId(int $p_d_reaction_id) Return PDRTaggedT objects filtered by the p_d_reaction_id column
 * @method array findByPTagId(int $p_tag_id) Return PDRTaggedT objects filtered by the p_tag_id column
 * @method array findByCreatedAt(string $created_at) Return PDRTaggedT objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PDRTaggedT objects filtered by the updated_at column
 */
abstract class BasePDRTaggedTQuery extends ModelCriteria
{
    // query_cache behavior
    protected $queryKey = '';

    // archivable behavior
    protected $archiveOnDelete = true;

    /**
     * Initializes internal state of BasePDRTaggedTQuery object.
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
            $modelName = 'Politizr\\Model\\PDRTaggedT';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PDRTaggedTQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PDRTaggedTQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PDRTaggedTQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PDRTaggedTQuery) {
            return $criteria;
        }
        $query = new PDRTaggedTQuery(null, null, $modelAlias);

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
     * @return   PDRTaggedT|PDRTaggedT[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PDRTaggedTPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PDRTaggedTPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PDRTaggedT A model object, or null if the key is not found
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
     * @return                 PDRTaggedT A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `p_d_reaction_id`, `p_tag_id`, `created_at`, `updated_at` FROM `p_d_r_tagged_t` WHERE `id` = :p0';
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
            $obj = new PDRTaggedT();
            $obj->hydrate($row);
            PDRTaggedTPeer::addInstanceToPool($obj, (string) $key);
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
     * @return PDRTaggedT|PDRTaggedT[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PDRTaggedT[]|mixed the list of results, formatted by the current formatter
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
     * @return PDRTaggedTQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PDRTaggedTPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PDRTaggedTQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PDRTaggedTPeer::ID, $keys, Criteria::IN);
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
     * @return PDRTaggedTQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PDRTaggedTPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PDRTaggedTPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDRTaggedTPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the p_d_reaction_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPDReactionId(1234); // WHERE p_d_reaction_id = 1234
     * $query->filterByPDReactionId(array(12, 34)); // WHERE p_d_reaction_id IN (12, 34)
     * $query->filterByPDReactionId(array('min' => 12)); // WHERE p_d_reaction_id >= 12
     * $query->filterByPDReactionId(array('max' => 12)); // WHERE p_d_reaction_id <= 12
     * </code>
     *
     * @see       filterByPDReaction()
     *
     * @param     mixed $pDReactionId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDRTaggedTQuery The current query, for fluid interface
     */
    public function filterByPDReactionId($pDReactionId = null, $comparison = null)
    {
        if (is_array($pDReactionId)) {
            $useMinMax = false;
            if (isset($pDReactionId['min'])) {
                $this->addUsingAlias(PDRTaggedTPeer::P_D_REACTION_ID, $pDReactionId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pDReactionId['max'])) {
                $this->addUsingAlias(PDRTaggedTPeer::P_D_REACTION_ID, $pDReactionId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDRTaggedTPeer::P_D_REACTION_ID, $pDReactionId, $comparison);
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
     * @see       filterByPTag()
     *
     * @param     mixed $pTagId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDRTaggedTQuery The current query, for fluid interface
     */
    public function filterByPTagId($pTagId = null, $comparison = null)
    {
        if (is_array($pTagId)) {
            $useMinMax = false;
            if (isset($pTagId['min'])) {
                $this->addUsingAlias(PDRTaggedTPeer::P_TAG_ID, $pTagId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pTagId['max'])) {
                $this->addUsingAlias(PDRTaggedTPeer::P_TAG_ID, $pTagId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDRTaggedTPeer::P_TAG_ID, $pTagId, $comparison);
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
     * @return PDRTaggedTQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PDRTaggedTPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PDRTaggedTPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDRTaggedTPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PDRTaggedTQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PDRTaggedTPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PDRTaggedTPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDRTaggedTPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related PDReaction object
     *
     * @param   PDReaction|PropelObjectCollection $pDReaction The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDRTaggedTQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPDReaction($pDReaction, $comparison = null)
    {
        if ($pDReaction instanceof PDReaction) {
            return $this
                ->addUsingAlias(PDRTaggedTPeer::P_D_REACTION_ID, $pDReaction->getId(), $comparison);
        } elseif ($pDReaction instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PDRTaggedTPeer::P_D_REACTION_ID, $pDReaction->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPDReaction() only accepts arguments of type PDReaction or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PDReaction relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDRTaggedTQuery The current query, for fluid interface
     */
    public function joinPDReaction($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PDReaction');

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
            $this->addJoinObject($join, 'PDReaction');
        }

        return $this;
    }

    /**
     * Use the PDReaction relation PDReaction object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PDReactionQuery A secondary query class using the current class as primary query
     */
    public function usePDReactionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPDReaction($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PDReaction', '\Politizr\Model\PDReactionQuery');
    }

    /**
     * Filter the query by a related PTag object
     *
     * @param   PTag|PropelObjectCollection $pTag The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDRTaggedTQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPTag($pTag, $comparison = null)
    {
        if ($pTag instanceof PTag) {
            return $this
                ->addUsingAlias(PDRTaggedTPeer::P_TAG_ID, $pTag->getId(), $comparison);
        } elseif ($pTag instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PDRTaggedTPeer::P_TAG_ID, $pTag->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPTag() only accepts arguments of type PTag or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PTag relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDRTaggedTQuery The current query, for fluid interface
     */
    public function joinPTag($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PTag');

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
            $this->addJoinObject($join, 'PTag');
        }

        return $this;
    }

    /**
     * Use the PTag relation PTag object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PTagQuery A secondary query class using the current class as primary query
     */
    public function usePTagQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPTag($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PTag', '\Politizr\Model\PTagQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   PDRTaggedT $pDRTaggedT Object to remove from the list of results
     *
     * @return PDRTaggedTQuery The current query, for fluid interface
     */
    public function prune($pDRTaggedT = null)
    {
        if ($pDRTaggedT) {
            $this->addUsingAlias(PDRTaggedTPeer::ID, $pDRTaggedT->getId(), Criteria::NOT_EQUAL);
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
     * @return     PDRTaggedTQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PDRTaggedTPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PDRTaggedTQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PDRTaggedTPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PDRTaggedTQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PDRTaggedTPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PDRTaggedTQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PDRTaggedTPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PDRTaggedTQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PDRTaggedTPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PDRTaggedTQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PDRTaggedTPeer::CREATED_AT);
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

        $dbMap = Propel::getDatabaseMap(PDRTaggedTPeer::DATABASE_NAME);
        $db = Propel::getDB(PDRTaggedTPeer::DATABASE_NAME);

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
     * Copy the data of the objects satisfying the query into PDRTaggedTArchive archive objects.
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
            $con = Propel::getConnection(PDRTaggedTPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
