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
use Politizr\Model\PDReaction;
use Politizr\Model\PDReactionPeer;
use Politizr\Model\PDReactionQuery;
use Politizr\Model\PDocument;

/**
 * @method PDReactionQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PDReactionQuery orderByPDDebateId($order = Criteria::ASC) Order by the p_d_debate_id column
 * @method PDReactionQuery orderByPDocumentId($order = Criteria::ASC) Order by the p_document_id column
 * @method PDReactionQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PDReactionQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PDReactionQuery orderByTreeLeft($order = Criteria::ASC) Order by the tree_left column
 * @method PDReactionQuery orderByTreeRight($order = Criteria::ASC) Order by the tree_right column
 * @method PDReactionQuery orderByTreeLevel($order = Criteria::ASC) Order by the tree_level column
 *
 * @method PDReactionQuery groupById() Group by the id column
 * @method PDReactionQuery groupByPDDebateId() Group by the p_d_debate_id column
 * @method PDReactionQuery groupByPDocumentId() Group by the p_document_id column
 * @method PDReactionQuery groupByCreatedAt() Group by the created_at column
 * @method PDReactionQuery groupByUpdatedAt() Group by the updated_at column
 * @method PDReactionQuery groupByTreeLeft() Group by the tree_left column
 * @method PDReactionQuery groupByTreeRight() Group by the tree_right column
 * @method PDReactionQuery groupByTreeLevel() Group by the tree_level column
 *
 * @method PDReactionQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PDReactionQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PDReactionQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PDReactionQuery leftJoinPDDebate($relationAlias = null) Adds a LEFT JOIN clause to the query using the PDDebate relation
 * @method PDReactionQuery rightJoinPDDebate($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PDDebate relation
 * @method PDReactionQuery innerJoinPDDebate($relationAlias = null) Adds a INNER JOIN clause to the query using the PDDebate relation
 *
 * @method PDReactionQuery leftJoinPDocument($relationAlias = null) Adds a LEFT JOIN clause to the query using the PDocument relation
 * @method PDReactionQuery rightJoinPDocument($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PDocument relation
 * @method PDReactionQuery innerJoinPDocument($relationAlias = null) Adds a INNER JOIN clause to the query using the PDocument relation
 *
 * @method PDReaction findOne(PropelPDO $con = null) Return the first PDReaction matching the query
 * @method PDReaction findOneOrCreate(PropelPDO $con = null) Return the first PDReaction matching the query, or a new PDReaction object populated from the query conditions when no match is found
 *
 * @method PDReaction findOneByPDDebateId(int $p_d_debate_id) Return the first PDReaction filtered by the p_d_debate_id column
 * @method PDReaction findOneByPDocumentId(int $p_document_id) Return the first PDReaction filtered by the p_document_id column
 * @method PDReaction findOneByCreatedAt(string $created_at) Return the first PDReaction filtered by the created_at column
 * @method PDReaction findOneByUpdatedAt(string $updated_at) Return the first PDReaction filtered by the updated_at column
 * @method PDReaction findOneByTreeLeft(int $tree_left) Return the first PDReaction filtered by the tree_left column
 * @method PDReaction findOneByTreeRight(int $tree_right) Return the first PDReaction filtered by the tree_right column
 * @method PDReaction findOneByTreeLevel(int $tree_level) Return the first PDReaction filtered by the tree_level column
 *
 * @method array findById(int $id) Return PDReaction objects filtered by the id column
 * @method array findByPDDebateId(int $p_d_debate_id) Return PDReaction objects filtered by the p_d_debate_id column
 * @method array findByPDocumentId(int $p_document_id) Return PDReaction objects filtered by the p_document_id column
 * @method array findByCreatedAt(string $created_at) Return PDReaction objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PDReaction objects filtered by the updated_at column
 * @method array findByTreeLeft(int $tree_left) Return PDReaction objects filtered by the tree_left column
 * @method array findByTreeRight(int $tree_right) Return PDReaction objects filtered by the tree_right column
 * @method array findByTreeLevel(int $tree_level) Return PDReaction objects filtered by the tree_level column
 */
abstract class BasePDReactionQuery extends ModelCriteria
{
    // query_cache behavior
    protected $queryKey = '';

    // archivable behavior
    protected $archiveOnDelete = true;

    /**
     * Initializes internal state of BasePDReactionQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Politizr\\Model\\PDReaction', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PDReactionQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PDReactionQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PDReactionQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PDReactionQuery) {
            return $criteria;
        }
        $query = new PDReactionQuery();
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
     * $obj  = $c->findPk(12, $con);
     * </code>
     *
     * @param mixed $key Primary key to use for the query
     * @param     PropelPDO $con an optional connection object
     *
     * @return   PDReaction|PDReaction[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PDReactionPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PDReaction A model object, or null if the key is not found
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
     * @return                 PDReaction A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `p_d_debate_id`, `p_document_id`, `created_at`, `updated_at`, `tree_left`, `tree_right`, `tree_level` FROM `p_d_reaction` WHERE `id` = :p0';
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
            $obj = new PDReaction();
            $obj->hydrate($row);
            PDReactionPeer::addInstanceToPool($obj, (string) $key);
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
     * @return PDReaction|PDReaction[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PDReaction[]|mixed the list of results, formatted by the current formatter
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
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PDReactionPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PDReactionPeer::ID, $keys, Criteria::IN);
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
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PDReactionPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PDReactionPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the p_d_debate_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPDDebateId(1234); // WHERE p_d_debate_id = 1234
     * $query->filterByPDDebateId(array(12, 34)); // WHERE p_d_debate_id IN (12, 34)
     * $query->filterByPDDebateId(array('min' => 12)); // WHERE p_d_debate_id >= 12
     * $query->filterByPDDebateId(array('max' => 12)); // WHERE p_d_debate_id <= 12
     * </code>
     *
     * @see       filterByPDDebate()
     *
     * @param     mixed $pDDebateId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByPDDebateId($pDDebateId = null, $comparison = null)
    {
        if (is_array($pDDebateId)) {
            $useMinMax = false;
            if (isset($pDDebateId['min'])) {
                $this->addUsingAlias(PDReactionPeer::P_D_DEBATE_ID, $pDDebateId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pDDebateId['max'])) {
                $this->addUsingAlias(PDReactionPeer::P_D_DEBATE_ID, $pDDebateId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::P_D_DEBATE_ID, $pDDebateId, $comparison);
    }

    /**
     * Filter the query on the p_document_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPDocumentId(1234); // WHERE p_document_id = 1234
     * $query->filterByPDocumentId(array(12, 34)); // WHERE p_document_id IN (12, 34)
     * $query->filterByPDocumentId(array('min' => 12)); // WHERE p_document_id >= 12
     * $query->filterByPDocumentId(array('max' => 12)); // WHERE p_document_id <= 12
     * </code>
     *
     * @see       filterByPDocument()
     *
     * @param     mixed $pDocumentId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByPDocumentId($pDocumentId = null, $comparison = null)
    {
        if (is_array($pDocumentId)) {
            $useMinMax = false;
            if (isset($pDocumentId['min'])) {
                $this->addUsingAlias(PDReactionPeer::P_DOCUMENT_ID, $pDocumentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pDocumentId['max'])) {
                $this->addUsingAlias(PDReactionPeer::P_DOCUMENT_ID, $pDocumentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::P_DOCUMENT_ID, $pDocumentId, $comparison);
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
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PDReactionPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PDReactionPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PDReactionPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PDReactionPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the tree_left column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeLeft(1234); // WHERE tree_left = 1234
     * $query->filterByTreeLeft(array(12, 34)); // WHERE tree_left IN (12, 34)
     * $query->filterByTreeLeft(array('min' => 12)); // WHERE tree_left >= 12
     * $query->filterByTreeLeft(array('max' => 12)); // WHERE tree_left <= 12
     * </code>
     *
     * @param     mixed $treeLeft The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByTreeLeft($treeLeft = null, $comparison = null)
    {
        if (is_array($treeLeft)) {
            $useMinMax = false;
            if (isset($treeLeft['min'])) {
                $this->addUsingAlias(PDReactionPeer::TREE_LEFT, $treeLeft['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeLeft['max'])) {
                $this->addUsingAlias(PDReactionPeer::TREE_LEFT, $treeLeft['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::TREE_LEFT, $treeLeft, $comparison);
    }

    /**
     * Filter the query on the tree_right column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeRight(1234); // WHERE tree_right = 1234
     * $query->filterByTreeRight(array(12, 34)); // WHERE tree_right IN (12, 34)
     * $query->filterByTreeRight(array('min' => 12)); // WHERE tree_right >= 12
     * $query->filterByTreeRight(array('max' => 12)); // WHERE tree_right <= 12
     * </code>
     *
     * @param     mixed $treeRight The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByTreeRight($treeRight = null, $comparison = null)
    {
        if (is_array($treeRight)) {
            $useMinMax = false;
            if (isset($treeRight['min'])) {
                $this->addUsingAlias(PDReactionPeer::TREE_RIGHT, $treeRight['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeRight['max'])) {
                $this->addUsingAlias(PDReactionPeer::TREE_RIGHT, $treeRight['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::TREE_RIGHT, $treeRight, $comparison);
    }

    /**
     * Filter the query on the tree_level column
     *
     * Example usage:
     * <code>
     * $query->filterByTreeLevel(1234); // WHERE tree_level = 1234
     * $query->filterByTreeLevel(array(12, 34)); // WHERE tree_level IN (12, 34)
     * $query->filterByTreeLevel(array('min' => 12)); // WHERE tree_level >= 12
     * $query->filterByTreeLevel(array('max' => 12)); // WHERE tree_level <= 12
     * </code>
     *
     * @param     mixed $treeLevel The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function filterByTreeLevel($treeLevel = null, $comparison = null)
    {
        if (is_array($treeLevel)) {
            $useMinMax = false;
            if (isset($treeLevel['min'])) {
                $this->addUsingAlias(PDReactionPeer::TREE_LEVEL, $treeLevel['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($treeLevel['max'])) {
                $this->addUsingAlias(PDReactionPeer::TREE_LEVEL, $treeLevel['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionPeer::TREE_LEVEL, $treeLevel, $comparison);
    }

    /**
     * Filter the query by a related PDDebate object
     *
     * @param   PDDebate|PropelObjectCollection $pDDebate The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDReactionQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPDDebate($pDDebate, $comparison = null)
    {
        if ($pDDebate instanceof PDDebate) {
            return $this
                ->addUsingAlias(PDReactionPeer::P_D_DEBATE_ID, $pDDebate->getId(), $comparison);
        } elseif ($pDDebate instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PDReactionPeer::P_D_DEBATE_ID, $pDDebate->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function joinPDDebate($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function usePDDebateQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPDDebate($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PDDebate', '\Politizr\Model\PDDebateQuery');
    }

    /**
     * Filter the query by a related PDocument object
     *
     * @param   PDocument|PropelObjectCollection $pDocument The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDReactionQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPDocument($pDocument, $comparison = null)
    {
        if ($pDocument instanceof PDocument) {
            return $this
                ->addUsingAlias(PDReactionPeer::P_DOCUMENT_ID, $pDocument->getId(), $comparison);
        } elseif ($pDocument instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PDReactionPeer::P_DOCUMENT_ID, $pDocument->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPDocument() only accepts arguments of type PDocument or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PDocument relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function joinPDocument($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PDocument');

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
            $this->addJoinObject($join, 'PDocument');
        }

        return $this;
    }

    /**
     * Use the PDocument relation PDocument object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PDocumentQuery A secondary query class using the current class as primary query
     */
    public function usePDocumentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPDocument($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PDocument', '\Politizr\Model\PDocumentQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   PDReaction $pDReaction Object to remove from the list of results
     *
     * @return PDReactionQuery The current query, for fluid interface
     */
    public function prune($pDReaction = null)
    {
        if ($pDReaction) {
            $this->addUsingAlias(PDReactionPeer::ID, $pDReaction->getId(), Criteria::NOT_EQUAL);
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
     * @return     PDReactionQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PDReactionPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PDReactionQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PDReactionPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PDReactionQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PDReactionPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PDReactionQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PDReactionPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PDReactionQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PDReactionPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PDReactionQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PDReactionPeer::CREATED_AT);
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

        $dbMap = Propel::getDatabaseMap(PDReactionPeer::DATABASE_NAME);
        $db = Propel::getDB(PDReactionPeer::DATABASE_NAME);

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
     * Copy the data of the objects satisfying the query into PDReactionArchive archive objects.
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
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $con->beginTransaction();
        try {
            // archive all results one by one
            foreach ($criteria->find($con) as $object) {
                $object->archive($con);
                $totalArchivedObjects++;
            }
            $con->commit();
        } catch (PropelException $e) {
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

    // nested_set behavior

    /**
     * Filter the query to restrict the result to root objects
     *
     * @return    PDReactionQuery The current query, for fluid interface
     */
    public function treeRoots()
    {
        return $this->addUsingAlias(PDReactionPeer::LEFT_COL, 1, Criteria::EQUAL);
    }

    /**
     * Returns the objects in a certain tree, from the tree scope
     *
     * @param     int $scope		Scope to determine which objects node to return
     *
     * @return    PDReactionQuery The current query, for fluid interface
     */
    public function inTree($scope = null)
    {
        return $this->addUsingAlias(PDReactionPeer::SCOPE_COL, $scope, Criteria::EQUAL);
    }

    /**
     * Filter the query to restrict the result to descendants of an object
     *
     * @param     PDReaction $pDReaction The object to use for descendant search
     *
     * @return    PDReactionQuery The current query, for fluid interface
     */
    public function descendantsOf($pDReaction)
    {
        return $this
            ->inTree($pDReaction->getScopeValue())
            ->addUsingAlias(PDReactionPeer::LEFT_COL, $pDReaction->getLeftValue(), Criteria::GREATER_THAN)
            ->addUsingAlias(PDReactionPeer::LEFT_COL, $pDReaction->getRightValue(), Criteria::LESS_THAN);
    }

    /**
     * Filter the query to restrict the result to the branch of an object.
     * Same as descendantsOf(), except that it includes the object passed as parameter in the result
     *
     * @param     PDReaction $pDReaction The object to use for branch search
     *
     * @return    PDReactionQuery The current query, for fluid interface
     */
    public function branchOf($pDReaction)
    {
        return $this
            ->inTree($pDReaction->getScopeValue())
            ->addUsingAlias(PDReactionPeer::LEFT_COL, $pDReaction->getLeftValue(), Criteria::GREATER_EQUAL)
            ->addUsingAlias(PDReactionPeer::LEFT_COL, $pDReaction->getRightValue(), Criteria::LESS_EQUAL);
    }

    /**
     * Filter the query to restrict the result to children of an object
     *
     * @param     PDReaction $pDReaction The object to use for child search
     *
     * @return    PDReactionQuery The current query, for fluid interface
     */
    public function childrenOf($pDReaction)
    {
        return $this
            ->descendantsOf($pDReaction)
            ->addUsingAlias(PDReactionPeer::LEVEL_COL, $pDReaction->getLevel() + 1, Criteria::EQUAL);
    }

    /**
     * Filter the query to restrict the result to siblings of an object.
     * The result does not include the object passed as parameter.
     *
     * @param     PDReaction $pDReaction The object to use for sibling search
     * @param      PropelPDO $con Connection to use.
     *
     * @return    PDReactionQuery The current query, for fluid interface
     */
    public function siblingsOf($pDReaction, PropelPDO $con = null)
    {
        if ($pDReaction->isRoot()) {
            return $this->
                add(PDReactionPeer::LEVEL_COL, '1<>1', Criteria::CUSTOM);
        } else {
            return $this
                ->childrenOf($pDReaction->getParent($con))
                ->prune($pDReaction);
        }
    }

    /**
     * Filter the query to restrict the result to ancestors of an object
     *
     * @param     PDReaction $pDReaction The object to use for ancestors search
     *
     * @return    PDReactionQuery The current query, for fluid interface
     */
    public function ancestorsOf($pDReaction)
    {
        return $this
            ->inTree($pDReaction->getScopeValue())
            ->addUsingAlias(PDReactionPeer::LEFT_COL, $pDReaction->getLeftValue(), Criteria::LESS_THAN)
            ->addUsingAlias(PDReactionPeer::RIGHT_COL, $pDReaction->getRightValue(), Criteria::GREATER_THAN);
    }

    /**
     * Filter the query to restrict the result to roots of an object.
     * Same as ancestorsOf(), except that it includes the object passed as parameter in the result
     *
     * @param     PDReaction $pDReaction The object to use for roots search
     *
     * @return    PDReactionQuery The current query, for fluid interface
     */
    public function rootsOf($pDReaction)
    {
        return $this
            ->inTree($pDReaction->getScopeValue())
            ->addUsingAlias(PDReactionPeer::LEFT_COL, $pDReaction->getLeftValue(), Criteria::LESS_EQUAL)
            ->addUsingAlias(PDReactionPeer::RIGHT_COL, $pDReaction->getRightValue(), Criteria::GREATER_EQUAL);
    }

    /**
     * Order the result by branch, i.e. natural tree order
     *
     * @param     bool $reverse if true, reverses the order
     *
     * @return    PDReactionQuery The current query, for fluid interface
     */
    public function orderByBranch($reverse = false)
    {
        if ($reverse) {
            return $this
                ->addDescendingOrderByColumn(PDReactionPeer::LEFT_COL);
        } else {
            return $this
                ->addAscendingOrderByColumn(PDReactionPeer::LEFT_COL);
        }
    }

    /**
     * Order the result by level, the closer to the root first
     *
     * @param     bool $reverse if true, reverses the order
     *
     * @return    PDReactionQuery The current query, for fluid interface
     */
    public function orderByLevel($reverse = false)
    {
        if ($reverse) {
            return $this
                ->addAscendingOrderByColumn(PDReactionPeer::RIGHT_COL);
        } else {
            return $this
                ->addDescendingOrderByColumn(PDReactionPeer::RIGHT_COL);
        }
    }

    /**
     * Returns a root node for the tree
     *
     * @param      int $scope		Scope to determine which root node to return
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     PDReaction The tree root object
     */
    public function findRoot($scope = null, $con = null)
    {
        return $this
            ->addUsingAlias(PDReactionPeer::LEFT_COL, 1, Criteria::EQUAL)
            ->inTree($scope)
            ->findOne($con);
    }

    /**
     * Returns the root objects for all trees.
     *
     * @param      PropelPDO $con	Connection to use.
     *
     * @return    mixed the list of results, formatted by the current formatter
     */
    public function findRoots($con = null)
    {
        return $this
            ->treeRoots()
            ->find($con);
    }

    /**
     * Returns a tree of objects
     *
     * @param      int $scope		Scope to determine which tree node to return
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     mixed the list of results, formatted by the current formatter
     */
    public function findTree($scope = null, $con = null)
    {
        return $this
            ->inTree($scope)
            ->orderByBranch()
            ->find($con);
    }

}
