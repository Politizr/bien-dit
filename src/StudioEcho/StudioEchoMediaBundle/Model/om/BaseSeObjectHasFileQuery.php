<?php

namespace StudioEcho\StudioEchoMediaBundle\Model\om;

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
use StudioEcho\StudioEchoMediaBundle\Model\SeMediaFile;
use StudioEcho\StudioEchoMediaBundle\Model\SeMediaObject;
use StudioEcho\StudioEchoMediaBundle\Model\SeObjectHasFile;
use StudioEcho\StudioEchoMediaBundle\Model\SeObjectHasFilePeer;
use StudioEcho\StudioEchoMediaBundle\Model\SeObjectHasFileQuery;

/**
 * @method SeObjectHasFileQuery orderBySeMediaObjectId($order = Criteria::ASC) Order by the se_media_object_id column
 * @method SeObjectHasFileQuery orderBySeMediaFileId($order = Criteria::ASC) Order by the se_media_file_id column
 * @method SeObjectHasFileQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 * @method SeObjectHasFileQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method SeObjectHasFileQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method SeObjectHasFileQuery groupBySeMediaObjectId() Group by the se_media_object_id column
 * @method SeObjectHasFileQuery groupBySeMediaFileId() Group by the se_media_file_id column
 * @method SeObjectHasFileQuery groupBySortableRank() Group by the sortable_rank column
 * @method SeObjectHasFileQuery groupByCreatedAt() Group by the created_at column
 * @method SeObjectHasFileQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method SeObjectHasFileQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method SeObjectHasFileQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method SeObjectHasFileQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method SeObjectHasFileQuery leftJoinSeMediaObject($relationAlias = null) Adds a LEFT JOIN clause to the query using the SeMediaObject relation
 * @method SeObjectHasFileQuery rightJoinSeMediaObject($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SeMediaObject relation
 * @method SeObjectHasFileQuery innerJoinSeMediaObject($relationAlias = null) Adds a INNER JOIN clause to the query using the SeMediaObject relation
 *
 * @method SeObjectHasFileQuery leftJoinSeMediaFile($relationAlias = null) Adds a LEFT JOIN clause to the query using the SeMediaFile relation
 * @method SeObjectHasFileQuery rightJoinSeMediaFile($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SeMediaFile relation
 * @method SeObjectHasFileQuery innerJoinSeMediaFile($relationAlias = null) Adds a INNER JOIN clause to the query using the SeMediaFile relation
 *
 * @method SeObjectHasFile findOne(PropelPDO $con = null) Return the first SeObjectHasFile matching the query
 * @method SeObjectHasFile findOneOrCreate(PropelPDO $con = null) Return the first SeObjectHasFile matching the query, or a new SeObjectHasFile object populated from the query conditions when no match is found
 *
 * @method SeObjectHasFile findOneBySeMediaObjectId(int $se_media_object_id) Return the first SeObjectHasFile filtered by the se_media_object_id column
 * @method SeObjectHasFile findOneBySeMediaFileId(int $se_media_file_id) Return the first SeObjectHasFile filtered by the se_media_file_id column
 * @method SeObjectHasFile findOneBySortableRank(int $sortable_rank) Return the first SeObjectHasFile filtered by the sortable_rank column
 * @method SeObjectHasFile findOneByCreatedAt(string $created_at) Return the first SeObjectHasFile filtered by the created_at column
 * @method SeObjectHasFile findOneByUpdatedAt(string $updated_at) Return the first SeObjectHasFile filtered by the updated_at column
 *
 * @method array findBySeMediaObjectId(int $se_media_object_id) Return SeObjectHasFile objects filtered by the se_media_object_id column
 * @method array findBySeMediaFileId(int $se_media_file_id) Return SeObjectHasFile objects filtered by the se_media_file_id column
 * @method array findBySortableRank(int $sortable_rank) Return SeObjectHasFile objects filtered by the sortable_rank column
 * @method array findByCreatedAt(string $created_at) Return SeObjectHasFile objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return SeObjectHasFile objects filtered by the updated_at column
 */
abstract class BaseSeObjectHasFileQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseSeObjectHasFileQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'StudioEcho\\StudioEchoMediaBundle\\Model\\SeObjectHasFile', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new SeObjectHasFileQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   SeObjectHasFileQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return SeObjectHasFileQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof SeObjectHasFileQuery) {
            return $criteria;
        }
        $query = new SeObjectHasFileQuery();
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
                         A Primary key composition: [$se_media_object_id, $se_media_file_id]
     * @param     PropelPDO $con an optional connection object
     *
     * @return   SeObjectHasFile|SeObjectHasFile[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SeObjectHasFilePeer::getInstanceFromPool(serialize(array((string) $key[0], (string) $key[1]))))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(SeObjectHasFilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 SeObjectHasFile A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `se_media_object_id`, `se_media_file_id`, `sortable_rank`, `created_at`, `updated_at` FROM `se_object_has_file` WHERE `se_media_object_id` = :p0 AND `se_media_file_id` = :p1';
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
            $obj = new SeObjectHasFile();
            $obj->hydrate($row);
            SeObjectHasFilePeer::addInstanceToPool($obj, serialize(array((string) $key[0], (string) $key[1])));
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
     * @return SeObjectHasFile|SeObjectHasFile[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|SeObjectHasFile[]|mixed the list of results, formatted by the current formatter
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
     * @return SeObjectHasFileQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {
        $this->addUsingAlias(SeObjectHasFilePeer::SE_MEDIA_OBJECT_ID, $key[0], Criteria::EQUAL);
        $this->addUsingAlias(SeObjectHasFilePeer::SE_MEDIA_FILE_ID, $key[1], Criteria::EQUAL);

        return $this;
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return SeObjectHasFileQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {
        if (empty($keys)) {
            return $this->add(null, '1<>1', Criteria::CUSTOM);
        }
        foreach ($keys as $key) {
            $cton0 = $this->getNewCriterion(SeObjectHasFilePeer::SE_MEDIA_OBJECT_ID, $key[0], Criteria::EQUAL);
            $cton1 = $this->getNewCriterion(SeObjectHasFilePeer::SE_MEDIA_FILE_ID, $key[1], Criteria::EQUAL);
            $cton0->addAnd($cton1);
            $this->addOr($cton0);
        }

        return $this;
    }

    /**
     * Filter the query on the se_media_object_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySeMediaObjectId(1234); // WHERE se_media_object_id = 1234
     * $query->filterBySeMediaObjectId(array(12, 34)); // WHERE se_media_object_id IN (12, 34)
     * $query->filterBySeMediaObjectId(array('min' => 12)); // WHERE se_media_object_id >= 12
     * $query->filterBySeMediaObjectId(array('max' => 12)); // WHERE se_media_object_id <= 12
     * </code>
     *
     * @see       filterBySeMediaObject()
     *
     * @param     mixed $seMediaObjectId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SeObjectHasFileQuery The current query, for fluid interface
     */
    public function filterBySeMediaObjectId($seMediaObjectId = null, $comparison = null)
    {
        if (is_array($seMediaObjectId)) {
            $useMinMax = false;
            if (isset($seMediaObjectId['min'])) {
                $this->addUsingAlias(SeObjectHasFilePeer::SE_MEDIA_OBJECT_ID, $seMediaObjectId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($seMediaObjectId['max'])) {
                $this->addUsingAlias(SeObjectHasFilePeer::SE_MEDIA_OBJECT_ID, $seMediaObjectId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeObjectHasFilePeer::SE_MEDIA_OBJECT_ID, $seMediaObjectId, $comparison);
    }

    /**
     * Filter the query on the se_media_file_id column
     *
     * Example usage:
     * <code>
     * $query->filterBySeMediaFileId(1234); // WHERE se_media_file_id = 1234
     * $query->filterBySeMediaFileId(array(12, 34)); // WHERE se_media_file_id IN (12, 34)
     * $query->filterBySeMediaFileId(array('min' => 12)); // WHERE se_media_file_id >= 12
     * $query->filterBySeMediaFileId(array('max' => 12)); // WHERE se_media_file_id <= 12
     * </code>
     *
     * @see       filterBySeMediaFile()
     *
     * @param     mixed $seMediaFileId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SeObjectHasFileQuery The current query, for fluid interface
     */
    public function filterBySeMediaFileId($seMediaFileId = null, $comparison = null)
    {
        if (is_array($seMediaFileId)) {
            $useMinMax = false;
            if (isset($seMediaFileId['min'])) {
                $this->addUsingAlias(SeObjectHasFilePeer::SE_MEDIA_FILE_ID, $seMediaFileId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($seMediaFileId['max'])) {
                $this->addUsingAlias(SeObjectHasFilePeer::SE_MEDIA_FILE_ID, $seMediaFileId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeObjectHasFilePeer::SE_MEDIA_FILE_ID, $seMediaFileId, $comparison);
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
     * @return SeObjectHasFileQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(SeObjectHasFilePeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(SeObjectHasFilePeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeObjectHasFilePeer::SORTABLE_RANK, $sortableRank, $comparison);
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
     * @return SeObjectHasFileQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(SeObjectHasFilePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(SeObjectHasFilePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeObjectHasFilePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return SeObjectHasFileQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(SeObjectHasFilePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(SeObjectHasFilePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeObjectHasFilePeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related SeMediaObject object
     *
     * @param   SeMediaObject|PropelObjectCollection $seMediaObject The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SeObjectHasFileQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySeMediaObject($seMediaObject, $comparison = null)
    {
        if ($seMediaObject instanceof SeMediaObject) {
            return $this
                ->addUsingAlias(SeObjectHasFilePeer::SE_MEDIA_OBJECT_ID, $seMediaObject->getId(), $comparison);
        } elseif ($seMediaObject instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SeObjectHasFilePeer::SE_MEDIA_OBJECT_ID, $seMediaObject->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySeMediaObject() only accepts arguments of type SeMediaObject or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SeMediaObject relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SeObjectHasFileQuery The current query, for fluid interface
     */
    public function joinSeMediaObject($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SeMediaObject');

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
            $this->addJoinObject($join, 'SeMediaObject');
        }

        return $this;
    }

    /**
     * Use the SeMediaObject relation SeMediaObject object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \StudioEcho\StudioEchoMediaBundle\Model\SeMediaObjectQuery A secondary query class using the current class as primary query
     */
    public function useSeMediaObjectQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSeMediaObject($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SeMediaObject', '\StudioEcho\StudioEchoMediaBundle\Model\SeMediaObjectQuery');
    }

    /**
     * Filter the query by a related SeMediaFile object
     *
     * @param   SeMediaFile|PropelObjectCollection $seMediaFile The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SeObjectHasFileQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySeMediaFile($seMediaFile, $comparison = null)
    {
        if ($seMediaFile instanceof SeMediaFile) {
            return $this
                ->addUsingAlias(SeObjectHasFilePeer::SE_MEDIA_FILE_ID, $seMediaFile->getId(), $comparison);
        } elseif ($seMediaFile instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(SeObjectHasFilePeer::SE_MEDIA_FILE_ID, $seMediaFile->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterBySeMediaFile() only accepts arguments of type SeMediaFile or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SeMediaFile relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SeObjectHasFileQuery The current query, for fluid interface
     */
    public function joinSeMediaFile($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SeMediaFile');

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
            $this->addJoinObject($join, 'SeMediaFile');
        }

        return $this;
    }

    /**
     * Use the SeMediaFile relation SeMediaFile object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \StudioEcho\StudioEchoMediaBundle\Model\SeMediaFileQuery A secondary query class using the current class as primary query
     */
    public function useSeMediaFileQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSeMediaFile($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SeMediaFile', '\StudioEcho\StudioEchoMediaBundle\Model\SeMediaFileQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   SeObjectHasFile $seObjectHasFile Object to remove from the list of results
     *
     * @return SeObjectHasFileQuery The current query, for fluid interface
     */
    public function prune($seObjectHasFile = null)
    {
        if ($seObjectHasFile) {
            $this->addCond('pruneCond0', $this->getAliasedColName(SeObjectHasFilePeer::SE_MEDIA_OBJECT_ID), $seObjectHasFile->getSeMediaObjectId(), Criteria::NOT_EQUAL);
            $this->addCond('pruneCond1', $this->getAliasedColName(SeObjectHasFilePeer::SE_MEDIA_FILE_ID), $seObjectHasFile->getSeMediaFileId(), Criteria::NOT_EQUAL);
            $this->combine(array('pruneCond0', 'pruneCond1'), Criteria::LOGICAL_OR);
        }

        return $this;
    }

    // sortable behavior

    /**
     * Returns the objects in a certain list, from the list scope
     *
     * @param     int $scope		Scope to determine which objects node to return
     *
     * @return    SeObjectHasFileQuery The current query, for fluid interface
     */
    public function inList($scope = null)
    {
        return $this->addUsingAlias(SeObjectHasFilePeer::SCOPE_COL, $scope, Criteria::EQUAL);
    }

    /**
     * Filter the query based on a rank in the list
     *
     * @param     integer   $rank rank
     * @param     int $scope		Scope to determine which suite to consider
     *
     * @return    SeObjectHasFileQuery The current query, for fluid interface
     */
    public function filterByRank($rank, $scope = null)
    {
        return $this
            ->inList($scope)
            ->addUsingAlias(SeObjectHasFilePeer::RANK_COL, $rank, Criteria::EQUAL);
    }

    /**
     * Order the query based on the rank in the list.
     * Using the default $order, returns the item with the lowest rank first
     *
     * @param     string $order either Criteria::ASC (default) or Criteria::DESC
     *
     * @return    SeObjectHasFileQuery The current query, for fluid interface
     */
    public function orderByRank($order = Criteria::ASC)
    {
        $order = strtoupper($order);
        switch ($order) {
            case Criteria::ASC:
                return $this->addAscendingOrderByColumn($this->getAliasedColName(SeObjectHasFilePeer::RANK_COL));
                break;
            case Criteria::DESC:
                return $this->addDescendingOrderByColumn($this->getAliasedColName(SeObjectHasFilePeer::RANK_COL));
                break;
            default:
                throw new PropelException('SeObjectHasFileQuery::orderBy() only accepts "asc" or "desc" as argument');
        }
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param     int $scope		Scope to determine which suite to consider
     * @param     PropelPDO $con optional connection
     *
     * @return    SeObjectHasFile
     */
    public function findOneByRank($rank, $scope = null, PropelPDO $con = null)
    {
        return $this
            ->filterByRank($rank, $scope)
            ->findOne($con);
    }

    /**
     * Returns a list of objects
     *
     * @param      int $scope		Scope to determine which list to return
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     mixed the list of results, formatted by the current formatter
     */
    public function findList($scope = null, $con = null)
    {
        return $this
            ->inList($scope)
            ->orderByRank()
            ->find($con);
    }

    /**
     * Get the highest rank
     *
     * @param      int $scope		Scope to determine which suite to consider
     * @param     PropelPDO optional connection
     *
     * @return    integer highest position
     */
    public function getMaxRank($scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SeObjectHasFilePeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . SeObjectHasFilePeer::RANK_COL . ')');
        $this->add(SeObjectHasFilePeer::SCOPE_COL, $scope, Criteria::EQUAL);
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
            $con = Propel::getConnection(SeObjectHasFilePeer::DATABASE_NAME);
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
        } catch (PropelException $e) {
            $con->rollback();
            throw $e;
        }
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     SeObjectHasFileQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(SeObjectHasFilePeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     SeObjectHasFileQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(SeObjectHasFilePeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     SeObjectHasFileQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(SeObjectHasFilePeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     SeObjectHasFileQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(SeObjectHasFilePeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     SeObjectHasFileQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(SeObjectHasFilePeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     SeObjectHasFileQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(SeObjectHasFilePeer::CREATED_AT);
    }
}
