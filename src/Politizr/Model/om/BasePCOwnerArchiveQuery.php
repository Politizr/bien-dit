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
use Politizr\Model\PCOwnerArchive;
use Politizr\Model\PCOwnerArchivePeer;
use Politizr\Model\PCOwnerArchiveQuery;

/**
 * @method PCOwnerArchiveQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PCOwnerArchiveQuery orderByUuid($order = Criteria::ASC) Order by the uuid column
 * @method PCOwnerArchiveQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method PCOwnerArchiveQuery orderBySummary($order = Criteria::ASC) Order by the summary column
 * @method PCOwnerArchiveQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method PCOwnerArchiveQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PCOwnerArchiveQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PCOwnerArchiveQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 * @method PCOwnerArchiveQuery orderByArchivedAt($order = Criteria::ASC) Order by the archived_at column
 *
 * @method PCOwnerArchiveQuery groupById() Group by the id column
 * @method PCOwnerArchiveQuery groupByUuid() Group by the uuid column
 * @method PCOwnerArchiveQuery groupByTitle() Group by the title column
 * @method PCOwnerArchiveQuery groupBySummary() Group by the summary column
 * @method PCOwnerArchiveQuery groupByDescription() Group by the description column
 * @method PCOwnerArchiveQuery groupByCreatedAt() Group by the created_at column
 * @method PCOwnerArchiveQuery groupByUpdatedAt() Group by the updated_at column
 * @method PCOwnerArchiveQuery groupBySlug() Group by the slug column
 * @method PCOwnerArchiveQuery groupByArchivedAt() Group by the archived_at column
 *
 * @method PCOwnerArchiveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PCOwnerArchiveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PCOwnerArchiveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PCOwnerArchive findOne(PropelPDO $con = null) Return the first PCOwnerArchive matching the query
 * @method PCOwnerArchive findOneOrCreate(PropelPDO $con = null) Return the first PCOwnerArchive matching the query, or a new PCOwnerArchive object populated from the query conditions when no match is found
 *
 * @method PCOwnerArchive findOneByUuid(string $uuid) Return the first PCOwnerArchive filtered by the uuid column
 * @method PCOwnerArchive findOneByTitle(string $title) Return the first PCOwnerArchive filtered by the title column
 * @method PCOwnerArchive findOneBySummary(string $summary) Return the first PCOwnerArchive filtered by the summary column
 * @method PCOwnerArchive findOneByDescription(string $description) Return the first PCOwnerArchive filtered by the description column
 * @method PCOwnerArchive findOneByCreatedAt(string $created_at) Return the first PCOwnerArchive filtered by the created_at column
 * @method PCOwnerArchive findOneByUpdatedAt(string $updated_at) Return the first PCOwnerArchive filtered by the updated_at column
 * @method PCOwnerArchive findOneBySlug(string $slug) Return the first PCOwnerArchive filtered by the slug column
 * @method PCOwnerArchive findOneByArchivedAt(string $archived_at) Return the first PCOwnerArchive filtered by the archived_at column
 *
 * @method array findById(int $id) Return PCOwnerArchive objects filtered by the id column
 * @method array findByUuid(string $uuid) Return PCOwnerArchive objects filtered by the uuid column
 * @method array findByTitle(string $title) Return PCOwnerArchive objects filtered by the title column
 * @method array findBySummary(string $summary) Return PCOwnerArchive objects filtered by the summary column
 * @method array findByDescription(string $description) Return PCOwnerArchive objects filtered by the description column
 * @method array findByCreatedAt(string $created_at) Return PCOwnerArchive objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PCOwnerArchive objects filtered by the updated_at column
 * @method array findBySlug(string $slug) Return PCOwnerArchive objects filtered by the slug column
 * @method array findByArchivedAt(string $archived_at) Return PCOwnerArchive objects filtered by the archived_at column
 */
abstract class BasePCOwnerArchiveQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePCOwnerArchiveQuery object.
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
            $modelName = 'Politizr\\Model\\PCOwnerArchive';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PCOwnerArchiveQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PCOwnerArchiveQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PCOwnerArchiveQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PCOwnerArchiveQuery) {
            return $criteria;
        }
        $query = new PCOwnerArchiveQuery(null, null, $modelAlias);

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
     * @return   PCOwnerArchive|PCOwnerArchive[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PCOwnerArchivePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PCOwnerArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PCOwnerArchive A model object, or null if the key is not found
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
     * @return                 PCOwnerArchive A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `uuid`, `title`, `summary`, `description`, `created_at`, `updated_at`, `slug`, `archived_at` FROM `p_c_owner_archive` WHERE `id` = :p0';
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
            $obj = new PCOwnerArchive();
            $obj->hydrate($row);
            PCOwnerArchivePeer::addInstanceToPool($obj, (string) $key);
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
     * @return PCOwnerArchive|PCOwnerArchive[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PCOwnerArchive[]|mixed the list of results, formatted by the current formatter
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
     * @return PCOwnerArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PCOwnerArchivePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PCOwnerArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PCOwnerArchivePeer::ID, $keys, Criteria::IN);
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
     * @return PCOwnerArchiveQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PCOwnerArchivePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PCOwnerArchivePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCOwnerArchivePeer::ID, $id, $comparison);
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
     * @return PCOwnerArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PCOwnerArchivePeer::UUID, $uuid, $comparison);
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
     * @return PCOwnerArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PCOwnerArchivePeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the summary column
     *
     * Example usage:
     * <code>
     * $query->filterBySummary('fooValue');   // WHERE summary = 'fooValue'
     * $query->filterBySummary('%fooValue%'); // WHERE summary LIKE '%fooValue%'
     * </code>
     *
     * @param     string $summary The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCOwnerArchiveQuery The current query, for fluid interface
     */
    public function filterBySummary($summary = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($summary)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $summary)) {
                $summary = str_replace('*', '%', $summary);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PCOwnerArchivePeer::SUMMARY, $summary, $comparison);
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
     * @return PCOwnerArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PCOwnerArchivePeer::DESCRIPTION, $description, $comparison);
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
     * @return PCOwnerArchiveQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PCOwnerArchivePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PCOwnerArchivePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCOwnerArchivePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PCOwnerArchiveQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PCOwnerArchivePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PCOwnerArchivePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCOwnerArchivePeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query on the slug column
     *
     * Example usage:
     * <code>
     * $query->filterBySlug('fooValue');   // WHERE slug = 'fooValue'
     * $query->filterBySlug('%fooValue%'); // WHERE slug LIKE '%fooValue%'
     * </code>
     *
     * @param     string $slug The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCOwnerArchiveQuery The current query, for fluid interface
     */
    public function filterBySlug($slug = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($slug)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $slug)) {
                $slug = str_replace('*', '%', $slug);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PCOwnerArchivePeer::SLUG, $slug, $comparison);
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
     * @return PCOwnerArchiveQuery The current query, for fluid interface
     */
    public function filterByArchivedAt($archivedAt = null, $comparison = null)
    {
        if (is_array($archivedAt)) {
            $useMinMax = false;
            if (isset($archivedAt['min'])) {
                $this->addUsingAlias(PCOwnerArchivePeer::ARCHIVED_AT, $archivedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($archivedAt['max'])) {
                $this->addUsingAlias(PCOwnerArchivePeer::ARCHIVED_AT, $archivedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCOwnerArchivePeer::ARCHIVED_AT, $archivedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   PCOwnerArchive $pCOwnerArchive Object to remove from the list of results
     *
     * @return PCOwnerArchiveQuery The current query, for fluid interface
     */
    public function prune($pCOwnerArchive = null)
    {
        if ($pCOwnerArchive) {
            $this->addUsingAlias(PCOwnerArchivePeer::ID, $pCOwnerArchive->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
