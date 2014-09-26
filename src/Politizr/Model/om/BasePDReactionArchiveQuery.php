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
use Politizr\Model\PDReactionArchive;
use Politizr\Model\PDReactionArchivePeer;
use Politizr\Model\PDReactionArchiveQuery;

/**
 * @method PDReactionArchiveQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PDReactionArchiveQuery orderByPDDebateId($order = Criteria::ASC) Order by the p_d_debate_id column
 * @method PDReactionArchiveQuery orderByPDocumentId($order = Criteria::ASC) Order by the p_document_id column
 * @method PDReactionArchiveQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PDReactionArchiveQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PDReactionArchiveQuery orderByArchivedAt($order = Criteria::ASC) Order by the archived_at column
 *
 * @method PDReactionArchiveQuery groupById() Group by the id column
 * @method PDReactionArchiveQuery groupByPDDebateId() Group by the p_d_debate_id column
 * @method PDReactionArchiveQuery groupByPDocumentId() Group by the p_document_id column
 * @method PDReactionArchiveQuery groupByCreatedAt() Group by the created_at column
 * @method PDReactionArchiveQuery groupByUpdatedAt() Group by the updated_at column
 * @method PDReactionArchiveQuery groupByArchivedAt() Group by the archived_at column
 *
 * @method PDReactionArchiveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PDReactionArchiveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PDReactionArchiveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PDReactionArchive findOne(PropelPDO $con = null) Return the first PDReactionArchive matching the query
 * @method PDReactionArchive findOneOrCreate(PropelPDO $con = null) Return the first PDReactionArchive matching the query, or a new PDReactionArchive object populated from the query conditions when no match is found
 *
 * @method PDReactionArchive findOneByPDDebateId(int $p_d_debate_id) Return the first PDReactionArchive filtered by the p_d_debate_id column
 * @method PDReactionArchive findOneByPDocumentId(int $p_document_id) Return the first PDReactionArchive filtered by the p_document_id column
 * @method PDReactionArchive findOneByCreatedAt(string $created_at) Return the first PDReactionArchive filtered by the created_at column
 * @method PDReactionArchive findOneByUpdatedAt(string $updated_at) Return the first PDReactionArchive filtered by the updated_at column
 * @method PDReactionArchive findOneByArchivedAt(string $archived_at) Return the first PDReactionArchive filtered by the archived_at column
 *
 * @method array findById(int $id) Return PDReactionArchive objects filtered by the id column
 * @method array findByPDDebateId(int $p_d_debate_id) Return PDReactionArchive objects filtered by the p_d_debate_id column
 * @method array findByPDocumentId(int $p_document_id) Return PDReactionArchive objects filtered by the p_document_id column
 * @method array findByCreatedAt(string $created_at) Return PDReactionArchive objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PDReactionArchive objects filtered by the updated_at column
 * @method array findByArchivedAt(string $archived_at) Return PDReactionArchive objects filtered by the archived_at column
 */
abstract class BasePDReactionArchiveQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePDReactionArchiveQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Politizr\\Model\\PDReactionArchive', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PDReactionArchiveQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PDReactionArchiveQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PDReactionArchiveQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PDReactionArchiveQuery) {
            return $criteria;
        }
        $query = new PDReactionArchiveQuery();
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
     * @return   PDReactionArchive|PDReactionArchive[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PDReactionArchivePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PDReactionArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PDReactionArchive A model object, or null if the key is not found
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
     * @return                 PDReactionArchive A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `p_d_debate_id`, `p_document_id`, `created_at`, `updated_at`, `archived_at` FROM `p_d_reaction_archive` WHERE `id` = :p0';
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
            $obj = new PDReactionArchive();
            $obj->hydrate($row);
            PDReactionArchivePeer::addInstanceToPool($obj, (string) $key);
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
     * @return PDReactionArchive|PDReactionArchive[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PDReactionArchive[]|mixed the list of results, formatted by the current formatter
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
     * @return PDReactionArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PDReactionArchivePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PDReactionArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PDReactionArchivePeer::ID, $keys, Criteria::IN);
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
     * @return PDReactionArchiveQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PDReactionArchivePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PDReactionArchivePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionArchivePeer::ID, $id, $comparison);
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
     * @param     mixed $pDDebateId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionArchiveQuery The current query, for fluid interface
     */
    public function filterByPDDebateId($pDDebateId = null, $comparison = null)
    {
        if (is_array($pDDebateId)) {
            $useMinMax = false;
            if (isset($pDDebateId['min'])) {
                $this->addUsingAlias(PDReactionArchivePeer::P_D_DEBATE_ID, $pDDebateId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pDDebateId['max'])) {
                $this->addUsingAlias(PDReactionArchivePeer::P_D_DEBATE_ID, $pDDebateId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionArchivePeer::P_D_DEBATE_ID, $pDDebateId, $comparison);
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
     * @param     mixed $pDocumentId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDReactionArchiveQuery The current query, for fluid interface
     */
    public function filterByPDocumentId($pDocumentId = null, $comparison = null)
    {
        if (is_array($pDocumentId)) {
            $useMinMax = false;
            if (isset($pDocumentId['min'])) {
                $this->addUsingAlias(PDReactionArchivePeer::P_DOCUMENT_ID, $pDocumentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pDocumentId['max'])) {
                $this->addUsingAlias(PDReactionArchivePeer::P_DOCUMENT_ID, $pDocumentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionArchivePeer::P_DOCUMENT_ID, $pDocumentId, $comparison);
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
     * @return PDReactionArchiveQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PDReactionArchivePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PDReactionArchivePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionArchivePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PDReactionArchiveQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PDReactionArchivePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PDReactionArchivePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionArchivePeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the archived_at column
     *
     * Example usage:
     * <code>
     * $query->filterByArchivedAt('2011-03-14'); // WHERE archived_at = '2011-03-14'
     * $query->filterByArchivedAt('now'); // WHERE archived_at = '2011-03-14'
     * $query->filterByArchivedAt(array('max' => 'yesterday')); // WHERE archived_at > '2011-03-13'
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
     * @return PDReactionArchiveQuery The current query, for fluid interface
     */
    public function filterByArchivedAt($archivedAt = null, $comparison = null)
    {
        if (is_array($archivedAt)) {
            $useMinMax = false;
            if (isset($archivedAt['min'])) {
                $this->addUsingAlias(PDReactionArchivePeer::ARCHIVED_AT, $archivedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($archivedAt['max'])) {
                $this->addUsingAlias(PDReactionArchivePeer::ARCHIVED_AT, $archivedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDReactionArchivePeer::ARCHIVED_AT, $archivedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   PDReactionArchive $pDReactionArchive Object to remove from the list of results
     *
     * @return PDReactionArchiveQuery The current query, for fluid interface
     */
    public function prune($pDReactionArchive = null)
    {
        if ($pDReactionArchive) {
            $this->addUsingAlias(PDReactionArchivePeer::ID, $pDReactionArchive->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
