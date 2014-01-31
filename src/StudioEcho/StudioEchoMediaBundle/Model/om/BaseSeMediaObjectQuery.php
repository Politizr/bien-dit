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
use StudioEcho\StudioEchoMediaBundle\Model\SeMediaObjectPeer;
use StudioEcho\StudioEchoMediaBundle\Model\SeMediaObjectQuery;
use StudioEcho\StudioEchoMediaBundle\Model\SeObjectHasFile;

/**
 * @method SeMediaObjectQuery orderById($order = Criteria::ASC) Order by the id column
 * @method SeMediaObjectQuery orderByObjectId($order = Criteria::ASC) Order by the object_id column
 * @method SeMediaObjectQuery orderByObjectClassname($order = Criteria::ASC) Order by the object_classname column
 * @method SeMediaObjectQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method SeMediaObjectQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method SeMediaObjectQuery groupById() Group by the id column
 * @method SeMediaObjectQuery groupByObjectId() Group by the object_id column
 * @method SeMediaObjectQuery groupByObjectClassname() Group by the object_classname column
 * @method SeMediaObjectQuery groupByCreatedAt() Group by the created_at column
 * @method SeMediaObjectQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method SeMediaObjectQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method SeMediaObjectQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method SeMediaObjectQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method SeMediaObjectQuery leftJoinSeObjectHasFile($relationAlias = null) Adds a LEFT JOIN clause to the query using the SeObjectHasFile relation
 * @method SeMediaObjectQuery rightJoinSeObjectHasFile($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SeObjectHasFile relation
 * @method SeMediaObjectQuery innerJoinSeObjectHasFile($relationAlias = null) Adds a INNER JOIN clause to the query using the SeObjectHasFile relation
 *
 * @method SeMediaObject findOne(PropelPDO $con = null) Return the first SeMediaObject matching the query
 * @method SeMediaObject findOneOrCreate(PropelPDO $con = null) Return the first SeMediaObject matching the query, or a new SeMediaObject object populated from the query conditions when no match is found
 *
 * @method SeMediaObject findOneByObjectId(int $object_id) Return the first SeMediaObject filtered by the object_id column
 * @method SeMediaObject findOneByObjectClassname(string $object_classname) Return the first SeMediaObject filtered by the object_classname column
 * @method SeMediaObject findOneByCreatedAt(string $created_at) Return the first SeMediaObject filtered by the created_at column
 * @method SeMediaObject findOneByUpdatedAt(string $updated_at) Return the first SeMediaObject filtered by the updated_at column
 *
 * @method array findById(int $id) Return SeMediaObject objects filtered by the id column
 * @method array findByObjectId(int $object_id) Return SeMediaObject objects filtered by the object_id column
 * @method array findByObjectClassname(string $object_classname) Return SeMediaObject objects filtered by the object_classname column
 * @method array findByCreatedAt(string $created_at) Return SeMediaObject objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return SeMediaObject objects filtered by the updated_at column
 */
abstract class BaseSeMediaObjectQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseSeMediaObjectQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'StudioEcho\\StudioEchoMediaBundle\\Model\\SeMediaObject', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new SeMediaObjectQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   SeMediaObjectQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return SeMediaObjectQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof SeMediaObjectQuery) {
            return $criteria;
        }
        $query = new SeMediaObjectQuery();
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
     * @return   SeMediaObject|SeMediaObject[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SeMediaObjectPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(SeMediaObjectPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 SeMediaObject A model object, or null if the key is not found
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
     * @return                 SeMediaObject A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `object_id`, `object_classname`, `created_at`, `updated_at` FROM `se_media_object` WHERE `id` = :p0';
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
            $obj = new SeMediaObject();
            $obj->hydrate($row);
            SeMediaObjectPeer::addInstanceToPool($obj, (string) $key);
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
     * @return SeMediaObject|SeMediaObject[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|SeMediaObject[]|mixed the list of results, formatted by the current formatter
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
     * @return SeMediaObjectQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SeMediaObjectPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return SeMediaObjectQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SeMediaObjectPeer::ID, $keys, Criteria::IN);
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
     * @return SeMediaObjectQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SeMediaObjectPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SeMediaObjectPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeMediaObjectPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the object_id column
     *
     * Example usage:
     * <code>
     * $query->filterByObjectId(1234); // WHERE object_id = 1234
     * $query->filterByObjectId(array(12, 34)); // WHERE object_id IN (12, 34)
     * $query->filterByObjectId(array('min' => 12)); // WHERE object_id >= 12
     * $query->filterByObjectId(array('max' => 12)); // WHERE object_id <= 12
     * </code>
     *
     * @param     mixed $objectId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SeMediaObjectQuery The current query, for fluid interface
     */
    public function filterByObjectId($objectId = null, $comparison = null)
    {
        if (is_array($objectId)) {
            $useMinMax = false;
            if (isset($objectId['min'])) {
                $this->addUsingAlias(SeMediaObjectPeer::OBJECT_ID, $objectId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($objectId['max'])) {
                $this->addUsingAlias(SeMediaObjectPeer::OBJECT_ID, $objectId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeMediaObjectPeer::OBJECT_ID, $objectId, $comparison);
    }

    /**
     * Filter the query on the object_classname column
     *
     * Example usage:
     * <code>
     * $query->filterByObjectClassname('fooValue');   // WHERE object_classname = 'fooValue'
     * $query->filterByObjectClassname('%fooValue%'); // WHERE object_classname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $objectClassname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SeMediaObjectQuery The current query, for fluid interface
     */
    public function filterByObjectClassname($objectClassname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($objectClassname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $objectClassname)) {
                $objectClassname = str_replace('*', '%', $objectClassname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SeMediaObjectPeer::OBJECT_CLASSNAME, $objectClassname, $comparison);
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
     * @return SeMediaObjectQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(SeMediaObjectPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(SeMediaObjectPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeMediaObjectPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return SeMediaObjectQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(SeMediaObjectPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(SeMediaObjectPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeMediaObjectPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related SeObjectHasFile object
     *
     * @param   SeObjectHasFile|PropelObjectCollection $seObjectHasFile  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SeMediaObjectQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySeObjectHasFile($seObjectHasFile, $comparison = null)
    {
        if ($seObjectHasFile instanceof SeObjectHasFile) {
            return $this
                ->addUsingAlias(SeMediaObjectPeer::ID, $seObjectHasFile->getSeMediaObjectId(), $comparison);
        } elseif ($seObjectHasFile instanceof PropelObjectCollection) {
            return $this
                ->useSeObjectHasFileQuery()
                ->filterByPrimaryKeys($seObjectHasFile->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySeObjectHasFile() only accepts arguments of type SeObjectHasFile or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SeObjectHasFile relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SeMediaObjectQuery The current query, for fluid interface
     */
    public function joinSeObjectHasFile($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SeObjectHasFile');

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
            $this->addJoinObject($join, 'SeObjectHasFile');
        }

        return $this;
    }

    /**
     * Use the SeObjectHasFile relation SeObjectHasFile object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \StudioEcho\StudioEchoMediaBundle\Model\SeObjectHasFileQuery A secondary query class using the current class as primary query
     */
    public function useSeObjectHasFileQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSeObjectHasFile($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SeObjectHasFile', '\StudioEcho\StudioEchoMediaBundle\Model\SeObjectHasFileQuery');
    }

    /**
     * Filter the query by a related SeMediaFile object
     * using the se_object_has_file table as cross reference
     *
     * @param   SeMediaFile $seMediaFile the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   SeMediaObjectQuery The current query, for fluid interface
     */
    public function filterBySeMediaFile($seMediaFile, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useSeObjectHasFileQuery()
            ->filterBySeMediaFile($seMediaFile, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   SeMediaObject $seMediaObject Object to remove from the list of results
     *
     * @return SeMediaObjectQuery The current query, for fluid interface
     */
    public function prune($seMediaObject = null)
    {
        if ($seMediaObject) {
            $this->addUsingAlias(SeMediaObjectPeer::ID, $seMediaObject->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     SeMediaObjectQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(SeMediaObjectPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     SeMediaObjectQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(SeMediaObjectPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     SeMediaObjectQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(SeMediaObjectPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     SeMediaObjectQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(SeMediaObjectPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     SeMediaObjectQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(SeMediaObjectPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     SeMediaObjectQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(SeMediaObjectPeer::CREATED_AT);
    }
}
