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
use Politizr\Model\PMEmailing;
use Politizr\Model\PNEmail;
use Politizr\Model\PNEmailPeer;
use Politizr\Model\PNEmailQuery;
use Politizr\Model\PUSubscribePNE;
use Politizr\Model\PUser;

/**
 * @method PNEmailQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PNEmailQuery orderByUuid($order = Criteria::ASC) Order by the uuid column
 * @method PNEmailQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method PNEmailQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method PNEmailQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PNEmailQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method PNEmailQuery groupById() Group by the id column
 * @method PNEmailQuery groupByUuid() Group by the uuid column
 * @method PNEmailQuery groupByTitle() Group by the title column
 * @method PNEmailQuery groupByDescription() Group by the description column
 * @method PNEmailQuery groupByCreatedAt() Group by the created_at column
 * @method PNEmailQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method PNEmailQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PNEmailQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PNEmailQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PNEmailQuery leftJoinPUSubscribePNE($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUSubscribePNE relation
 * @method PNEmailQuery rightJoinPUSubscribePNE($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUSubscribePNE relation
 * @method PNEmailQuery innerJoinPUSubscribePNE($relationAlias = null) Adds a INNER JOIN clause to the query using the PUSubscribePNE relation
 *
 * @method PNEmailQuery leftJoinPMEmailing($relationAlias = null) Adds a LEFT JOIN clause to the query using the PMEmailing relation
 * @method PNEmailQuery rightJoinPMEmailing($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PMEmailing relation
 * @method PNEmailQuery innerJoinPMEmailing($relationAlias = null) Adds a INNER JOIN clause to the query using the PMEmailing relation
 *
 * @method PNEmail findOne(PropelPDO $con = null) Return the first PNEmail matching the query
 * @method PNEmail findOneOrCreate(PropelPDO $con = null) Return the first PNEmail matching the query, or a new PNEmail object populated from the query conditions when no match is found
 *
 * @method PNEmail findOneByUuid(string $uuid) Return the first PNEmail filtered by the uuid column
 * @method PNEmail findOneByTitle(string $title) Return the first PNEmail filtered by the title column
 * @method PNEmail findOneByDescription(string $description) Return the first PNEmail filtered by the description column
 * @method PNEmail findOneByCreatedAt(string $created_at) Return the first PNEmail filtered by the created_at column
 * @method PNEmail findOneByUpdatedAt(string $updated_at) Return the first PNEmail filtered by the updated_at column
 *
 * @method array findById(int $id) Return PNEmail objects filtered by the id column
 * @method array findByUuid(string $uuid) Return PNEmail objects filtered by the uuid column
 * @method array findByTitle(string $title) Return PNEmail objects filtered by the title column
 * @method array findByDescription(string $description) Return PNEmail objects filtered by the description column
 * @method array findByCreatedAt(string $created_at) Return PNEmail objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PNEmail objects filtered by the updated_at column
 */
abstract class BasePNEmailQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePNEmailQuery object.
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
            $modelName = 'Politizr\\Model\\PNEmail';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PNEmailQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PNEmailQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PNEmailQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PNEmailQuery) {
            return $criteria;
        }
        $query = new PNEmailQuery(null, null, $modelAlias);

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
     * @return   PNEmail|PNEmail[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PNEmailPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PNEmailPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PNEmail A model object, or null if the key is not found
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
     * @return                 PNEmail A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `uuid`, `title`, `description`, `created_at`, `updated_at` FROM `p_n_email` WHERE `id` = :p0';
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
            $obj = new PNEmail();
            $obj->hydrate($row);
            PNEmailPeer::addInstanceToPool($obj, (string) $key);
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
     * @return PNEmail|PNEmail[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PNEmail[]|mixed the list of results, formatted by the current formatter
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
     * @return PNEmailQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PNEmailPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PNEmailQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PNEmailPeer::ID, $keys, Criteria::IN);
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
     * @return PNEmailQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PNEmailPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PNEmailPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PNEmailPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the uuid column
     *
     * Example usage:
     * <code>
     * $query->filterByUuid('fooValue');   // WHERE uuid = 'fooValue'
     * $query->filterByUuid('%fooValue%'); // WHERE uuid LIKE '%fooValue%'
     * </code>
     *
     * @param     string $uuid The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PNEmailQuery The current query, for fluid interface
     */
    public function filterByUuid($uuid = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($uuid)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $uuid)) {
                $uuid = str_replace('*', '%', $uuid);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PNEmailPeer::UUID, $uuid, $comparison);
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
     * @return PNEmailQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PNEmailPeer::TITLE, $title, $comparison);
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
     * @return PNEmailQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PNEmailPeer::DESCRIPTION, $description, $comparison);
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
     * @return PNEmailQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PNEmailPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PNEmailPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PNEmailPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PNEmailQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PNEmailPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PNEmailPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PNEmailPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related PUSubscribePNE object
     *
     * @param   PUSubscribePNE|PropelObjectCollection $pUSubscribePNE  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PNEmailQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUSubscribePNE($pUSubscribePNE, $comparison = null)
    {
        if ($pUSubscribePNE instanceof PUSubscribePNE) {
            return $this
                ->addUsingAlias(PNEmailPeer::ID, $pUSubscribePNE->getPNEmailId(), $comparison);
        } elseif ($pUSubscribePNE instanceof PropelObjectCollection) {
            return $this
                ->usePUSubscribePNEQuery()
                ->filterByPrimaryKeys($pUSubscribePNE->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPUSubscribePNE() only accepts arguments of type PUSubscribePNE or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUSubscribePNE relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PNEmailQuery The current query, for fluid interface
     */
    public function joinPUSubscribePNE($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUSubscribePNE');

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
            $this->addJoinObject($join, 'PUSubscribePNE');
        }

        return $this;
    }

    /**
     * Use the PUSubscribePNE relation PUSubscribePNE object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUSubscribePNEQuery A secondary query class using the current class as primary query
     */
    public function usePUSubscribePNEQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUSubscribePNE($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUSubscribePNE', '\Politizr\Model\PUSubscribePNEQuery');
    }

    /**
     * Filter the query by a related PMEmailing object
     *
     * @param   PMEmailing|PropelObjectCollection $pMEmailing  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PNEmailQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPMEmailing($pMEmailing, $comparison = null)
    {
        if ($pMEmailing instanceof PMEmailing) {
            return $this
                ->addUsingAlias(PNEmailPeer::ID, $pMEmailing->getPNEmailId(), $comparison);
        } elseif ($pMEmailing instanceof PropelObjectCollection) {
            return $this
                ->usePMEmailingQuery()
                ->filterByPrimaryKeys($pMEmailing->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPMEmailing() only accepts arguments of type PMEmailing or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PMEmailing relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PNEmailQuery The current query, for fluid interface
     */
    public function joinPMEmailing($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PMEmailing');

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
            $this->addJoinObject($join, 'PMEmailing');
        }

        return $this;
    }

    /**
     * Use the PMEmailing relation PMEmailing object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PMEmailingQuery A secondary query class using the current class as primary query
     */
    public function usePMEmailingQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPMEmailing($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PMEmailing', '\Politizr\Model\PMEmailingQuery');
    }

    /**
     * Filter the query by a related PUser object
     * using the p_u_subscribe_p_n_e table as cross reference
     *
     * @param   PUser $pUser the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PNEmailQuery The current query, for fluid interface
     */
    public function filterByPUser($pUser, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePUSubscribePNEQuery()
            ->filterByPUser($pUser, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   PNEmail $pNEmail Object to remove from the list of results
     *
     * @return PNEmailQuery The current query, for fluid interface
     */
    public function prune($pNEmail = null)
    {
        if ($pNEmail) {
            $this->addUsingAlias(PNEmailPeer::ID, $pNEmail->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     PNEmailQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PNEmailPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PNEmailQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PNEmailPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PNEmailQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PNEmailPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PNEmailQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PNEmailPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PNEmailQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PNEmailPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PNEmailQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PNEmailPeer::CREATED_AT);
    }
}
