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
use Politizr\Model\PUAffinityUPP;
use Politizr\Model\PUPoliticalParty;
use Politizr\Model\PUPoliticalPartyPeer;
use Politizr\Model\PUPoliticalPartyQuery;
use Politizr\Model\PUQualification;
use Politizr\Model\PUser;

/**
 * @method PUPoliticalPartyQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PUPoliticalPartyQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method PUPoliticalPartyQuery orderByInitials($order = Criteria::ASC) Order by the initials column
 * @method PUPoliticalPartyQuery orderByFileName($order = Criteria::ASC) Order by the file_name column
 * @method PUPoliticalPartyQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method PUPoliticalPartyQuery orderByOnline($order = Criteria::ASC) Order by the online column
 * @method PUPoliticalPartyQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PUPoliticalPartyQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PUPoliticalPartyQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 * @method PUPoliticalPartyQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 *
 * @method PUPoliticalPartyQuery groupById() Group by the id column
 * @method PUPoliticalPartyQuery groupByTitle() Group by the title column
 * @method PUPoliticalPartyQuery groupByInitials() Group by the initials column
 * @method PUPoliticalPartyQuery groupByFileName() Group by the file_name column
 * @method PUPoliticalPartyQuery groupByDescription() Group by the description column
 * @method PUPoliticalPartyQuery groupByOnline() Group by the online column
 * @method PUPoliticalPartyQuery groupByCreatedAt() Group by the created_at column
 * @method PUPoliticalPartyQuery groupByUpdatedAt() Group by the updated_at column
 * @method PUPoliticalPartyQuery groupBySlug() Group by the slug column
 * @method PUPoliticalPartyQuery groupBySortableRank() Group by the sortable_rank column
 *
 * @method PUPoliticalPartyQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PUPoliticalPartyQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PUPoliticalPartyQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PUPoliticalPartyQuery leftJoinPUQualification($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUQualification relation
 * @method PUPoliticalPartyQuery rightJoinPUQualification($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUQualification relation
 * @method PUPoliticalPartyQuery innerJoinPUQualification($relationAlias = null) Adds a INNER JOIN clause to the query using the PUQualification relation
 *
 * @method PUPoliticalPartyQuery leftJoinPuAffinityUppPUPoliticalParty($relationAlias = null) Adds a LEFT JOIN clause to the query using the PuAffinityUppPUPoliticalParty relation
 * @method PUPoliticalPartyQuery rightJoinPuAffinityUppPUPoliticalParty($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PuAffinityUppPUPoliticalParty relation
 * @method PUPoliticalPartyQuery innerJoinPuAffinityUppPUPoliticalParty($relationAlias = null) Adds a INNER JOIN clause to the query using the PuAffinityUppPUPoliticalParty relation
 *
 * @method PUPoliticalParty findOne(PropelPDO $con = null) Return the first PUPoliticalParty matching the query
 * @method PUPoliticalParty findOneOrCreate(PropelPDO $con = null) Return the first PUPoliticalParty matching the query, or a new PUPoliticalParty object populated from the query conditions when no match is found
 *
 * @method PUPoliticalParty findOneByTitle(string $title) Return the first PUPoliticalParty filtered by the title column
 * @method PUPoliticalParty findOneByInitials(string $initials) Return the first PUPoliticalParty filtered by the initials column
 * @method PUPoliticalParty findOneByFileName(string $file_name) Return the first PUPoliticalParty filtered by the file_name column
 * @method PUPoliticalParty findOneByDescription(string $description) Return the first PUPoliticalParty filtered by the description column
 * @method PUPoliticalParty findOneByOnline(boolean $online) Return the first PUPoliticalParty filtered by the online column
 * @method PUPoliticalParty findOneByCreatedAt(string $created_at) Return the first PUPoliticalParty filtered by the created_at column
 * @method PUPoliticalParty findOneByUpdatedAt(string $updated_at) Return the first PUPoliticalParty filtered by the updated_at column
 * @method PUPoliticalParty findOneBySlug(string $slug) Return the first PUPoliticalParty filtered by the slug column
 * @method PUPoliticalParty findOneBySortableRank(int $sortable_rank) Return the first PUPoliticalParty filtered by the sortable_rank column
 *
 * @method array findById(int $id) Return PUPoliticalParty objects filtered by the id column
 * @method array findByTitle(string $title) Return PUPoliticalParty objects filtered by the title column
 * @method array findByInitials(string $initials) Return PUPoliticalParty objects filtered by the initials column
 * @method array findByFileName(string $file_name) Return PUPoliticalParty objects filtered by the file_name column
 * @method array findByDescription(string $description) Return PUPoliticalParty objects filtered by the description column
 * @method array findByOnline(boolean $online) Return PUPoliticalParty objects filtered by the online column
 * @method array findByCreatedAt(string $created_at) Return PUPoliticalParty objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PUPoliticalParty objects filtered by the updated_at column
 * @method array findBySlug(string $slug) Return PUPoliticalParty objects filtered by the slug column
 * @method array findBySortableRank(int $sortable_rank) Return PUPoliticalParty objects filtered by the sortable_rank column
 */
abstract class BasePUPoliticalPartyQuery extends ModelCriteria
{
    // query_cache behavior
    protected $queryKey = '';

    /**
     * Initializes internal state of BasePUPoliticalPartyQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Politizr\\Model\\PUPoliticalParty', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PUPoliticalPartyQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PUPoliticalPartyQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PUPoliticalPartyQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PUPoliticalPartyQuery) {
            return $criteria;
        }
        $query = new PUPoliticalPartyQuery();
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
     * @return   PUPoliticalParty|PUPoliticalParty[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PUPoliticalPartyPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PUPoliticalPartyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PUPoliticalParty A model object, or null if the key is not found
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
     * @return                 PUPoliticalParty A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `title`, `initials`, `file_name`, `description`, `online`, `created_at`, `updated_at`, `slug`, `sortable_rank` FROM `p_u_political_party` WHERE `id` = :p0';
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
            $obj = new PUPoliticalParty();
            $obj->hydrate($row);
            PUPoliticalPartyPeer::addInstanceToPool($obj, (string) $key);
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
     * @return PUPoliticalParty|PUPoliticalParty[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PUPoliticalParty[]|mixed the list of results, formatted by the current formatter
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
     * @return PUPoliticalPartyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PUPoliticalPartyPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PUPoliticalPartyQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PUPoliticalPartyPeer::ID, $keys, Criteria::IN);
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
     * @return PUPoliticalPartyQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PUPoliticalPartyPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PUPoliticalPartyPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUPoliticalPartyPeer::ID, $id, $comparison);
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
     * @return PUPoliticalPartyQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUPoliticalPartyPeer::TITLE, $title, $comparison);
    }

    /**
     * Filter the query on the initials column
     *
     * Example usage:
     * <code>
     * $query->filterByInitials('fooValue');   // WHERE initials = 'fooValue'
     * $query->filterByInitials('%fooValue%'); // WHERE initials LIKE '%fooValue%'
     * </code>
     *
     * @param     string $initials The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUPoliticalPartyQuery The current query, for fluid interface
     */
    public function filterByInitials($initials = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($initials)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $initials)) {
                $initials = str_replace('*', '%', $initials);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUPoliticalPartyPeer::INITIALS, $initials, $comparison);
    }

    /**
     * Filter the query on the file_name column
     *
     * Example usage:
     * <code>
     * $query->filterByFileName('fooValue');   // WHERE file_name = 'fooValue'
     * $query->filterByFileName('%fooValue%'); // WHERE file_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $fileName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUPoliticalPartyQuery The current query, for fluid interface
     */
    public function filterByFileName($fileName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($fileName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $fileName)) {
                $fileName = str_replace('*', '%', $fileName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUPoliticalPartyPeer::FILE_NAME, $fileName, $comparison);
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
     * @return PUPoliticalPartyQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUPoliticalPartyPeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the online column
     *
     * Example usage:
     * <code>
     * $query->filterByOnline(true); // WHERE online = true
     * $query->filterByOnline('yes'); // WHERE online = true
     * </code>
     *
     * @param     boolean|string $online The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUPoliticalPartyQuery The current query, for fluid interface
     */
    public function filterByOnline($online = null, $comparison = null)
    {
        if (is_string($online)) {
            $online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUPoliticalPartyPeer::ONLINE, $online, $comparison);
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
     * @return PUPoliticalPartyQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PUPoliticalPartyPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PUPoliticalPartyPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUPoliticalPartyPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PUPoliticalPartyQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PUPoliticalPartyPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PUPoliticalPartyPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUPoliticalPartyPeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return PUPoliticalPartyQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUPoliticalPartyPeer::SLUG, $slug, $comparison);
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
     * @return PUPoliticalPartyQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(PUPoliticalPartyPeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(PUPoliticalPartyPeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUPoliticalPartyPeer::SORTABLE_RANK, $sortableRank, $comparison);
    }

    /**
     * Filter the query by a related PUQualification object
     *
     * @param   PUQualification|PropelObjectCollection $pUQualification  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUPoliticalPartyQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUQualification($pUQualification, $comparison = null)
    {
        if ($pUQualification instanceof PUQualification) {
            return $this
                ->addUsingAlias(PUPoliticalPartyPeer::ID, $pUQualification->getPUPoliticalPartyId(), $comparison);
        } elseif ($pUQualification instanceof PropelObjectCollection) {
            return $this
                ->usePUQualificationQuery()
                ->filterByPrimaryKeys($pUQualification->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPUQualification() only accepts arguments of type PUQualification or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUQualification relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUPoliticalPartyQuery The current query, for fluid interface
     */
    public function joinPUQualification($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUQualification');

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
            $this->addJoinObject($join, 'PUQualification');
        }

        return $this;
    }

    /**
     * Use the PUQualification relation PUQualification object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUQualificationQuery A secondary query class using the current class as primary query
     */
    public function usePUQualificationQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPUQualification($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUQualification', '\Politizr\Model\PUQualificationQuery');
    }

    /**
     * Filter the query by a related PUAffinityUPP object
     *
     * @param   PUAffinityUPP|PropelObjectCollection $pUAffinityUPP  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUPoliticalPartyQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPuAffinityUppPUPoliticalParty($pUAffinityUPP, $comparison = null)
    {
        if ($pUAffinityUPP instanceof PUAffinityUPP) {
            return $this
                ->addUsingAlias(PUPoliticalPartyPeer::ID, $pUAffinityUPP->getPUPoliticalPartyId(), $comparison);
        } elseif ($pUAffinityUPP instanceof PropelObjectCollection) {
            return $this
                ->usePuAffinityUppPUPoliticalPartyQuery()
                ->filterByPrimaryKeys($pUAffinityUPP->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPuAffinityUppPUPoliticalParty() only accepts arguments of type PUAffinityUPP or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PuAffinityUppPUPoliticalParty relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUPoliticalPartyQuery The current query, for fluid interface
     */
    public function joinPuAffinityUppPUPoliticalParty($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PuAffinityUppPUPoliticalParty');

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
            $this->addJoinObject($join, 'PuAffinityUppPUPoliticalParty');
        }

        return $this;
    }

    /**
     * Use the PuAffinityUppPUPoliticalParty relation PUAffinityUPP object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUAffinityUPPQuery A secondary query class using the current class as primary query
     */
    public function usePuAffinityUppPUPoliticalPartyQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPuAffinityUppPUPoliticalParty($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PuAffinityUppPUPoliticalParty', '\Politizr\Model\PUAffinityUPPQuery');
    }

    /**
     * Filter the query by a related PUser object
     * using the p_u_affinity_u_p_p table as cross reference
     *
     * @param   PUser $pUser the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PUPoliticalPartyQuery The current query, for fluid interface
     */
    public function filterByPuAffinityUppPUser($pUser, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePuAffinityUppPUPoliticalPartyQuery()
            ->filterByPuAffinityUppPUser($pUser, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   PUPoliticalParty $pUPoliticalParty Object to remove from the list of results
     *
     * @return PUPoliticalPartyQuery The current query, for fluid interface
     */
    public function prune($pUPoliticalParty = null)
    {
        if ($pUPoliticalParty) {
            $this->addUsingAlias(PUPoliticalPartyPeer::ID, $pUPoliticalParty->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     PUPoliticalPartyQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PUPoliticalPartyPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PUPoliticalPartyQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUPoliticalPartyPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PUPoliticalPartyQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUPoliticalPartyPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PUPoliticalPartyQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PUPoliticalPartyPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PUPoliticalPartyQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUPoliticalPartyPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PUPoliticalPartyQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUPoliticalPartyPeer::CREATED_AT);
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

        $dbMap = Propel::getDatabaseMap(PUPoliticalPartyPeer::DATABASE_NAME);
        $db = Propel::getDB(PUPoliticalPartyPeer::DATABASE_NAME);

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

    // sluggable behavior

    /**
     * Find one object based on its slug
     *
     * @param     string $slug The value to use as filter.
     * @param     PropelPDO $con The optional connection object
     *
     * @return    PUPoliticalParty the result, formatted by the current formatter
     */
    public function findOneBySlug($slug, $con = null)
    {
        return $this->filterBySlug($slug)->findOne($con);
    }

    // sortable behavior

    /**
     * Filter the query based on a rank in the list
     *
     * @param     integer   $rank rank
     *
     * @return    PUPoliticalPartyQuery The current query, for fluid interface
     */
    public function filterByRank($rank)
    {
        return $this
            ->addUsingAlias(PUPoliticalPartyPeer::RANK_COL, $rank, Criteria::EQUAL);
    }

    /**
     * Order the query based on the rank in the list.
     * Using the default $order, returns the item with the lowest rank first
     *
     * @param     string $order either Criteria::ASC (default) or Criteria::DESC
     *
     * @return    PUPoliticalPartyQuery The current query, for fluid interface
     */
    public function orderByRank($order = Criteria::ASC)
    {
        $order = strtoupper($order);
        switch ($order) {
            case Criteria::ASC:
                return $this->addAscendingOrderByColumn($this->getAliasedColName(PUPoliticalPartyPeer::RANK_COL));
                break;
            case Criteria::DESC:
                return $this->addDescendingOrderByColumn($this->getAliasedColName(PUPoliticalPartyPeer::RANK_COL));
                break;
            default:
                throw new PropelException('PUPoliticalPartyQuery::orderBy() only accepts "asc" or "desc" as argument');
        }
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param     PropelPDO $con optional connection
     *
     * @return    PUPoliticalParty
     */
    public function findOneByRank($rank, PropelPDO $con = null)
    {
        return $this
            ->filterByRank($rank)
            ->findOne($con);
    }

    /**
     * Returns the list of objects
     *
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     mixed the list of results, formatted by the current formatter
     */
    public function findList($con = null)
    {
        return $this
            ->orderByRank()
            ->find($con);
    }

    /**
     * Get the highest rank
     *
     * @param     PropelPDO optional connection
     *
     * @return    integer highest position
     */
    public function getMaxRank(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PUPoliticalPartyPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $this->addSelectColumn('MAX(' . PUPoliticalPartyPeer::RANK_COL . ')');
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
            $con = Propel::getConnection(PUPoliticalPartyPeer::DATABASE_NAME);
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

}
