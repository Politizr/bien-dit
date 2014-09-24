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
use Politizr\Model\PRBadgeArchive;
use Politizr\Model\PRBadgeArchivePeer;
use Politizr\Model\PRBadgeArchiveQuery;

/**
 * @method PRBadgeArchiveQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PRBadgeArchiveQuery orderByPRBadgeTypeId($order = Criteria::ASC) Order by the p_r_badge_type_id column
 * @method PRBadgeArchiveQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method PRBadgeArchiveQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method PRBadgeArchiveQuery orderByGrade($order = Criteria::ASC) Order by the grade column
 * @method PRBadgeArchiveQuery orderByOnline($order = Criteria::ASC) Order by the online column
 * @method PRBadgeArchiveQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PRBadgeArchiveQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PRBadgeArchiveQuery orderByArchivedAt($order = Criteria::ASC) Order by the archived_at column
 *
 * @method PRBadgeArchiveQuery groupById() Group by the id column
 * @method PRBadgeArchiveQuery groupByPRBadgeTypeId() Group by the p_r_badge_type_id column
 * @method PRBadgeArchiveQuery groupByTitle() Group by the title column
 * @method PRBadgeArchiveQuery groupByDescription() Group by the description column
 * @method PRBadgeArchiveQuery groupByGrade() Group by the grade column
 * @method PRBadgeArchiveQuery groupByOnline() Group by the online column
 * @method PRBadgeArchiveQuery groupByCreatedAt() Group by the created_at column
 * @method PRBadgeArchiveQuery groupByUpdatedAt() Group by the updated_at column
 * @method PRBadgeArchiveQuery groupByArchivedAt() Group by the archived_at column
 *
 * @method PRBadgeArchiveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PRBadgeArchiveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PRBadgeArchiveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PRBadgeArchive findOne(PropelPDO $con = null) Return the first PRBadgeArchive matching the query
 * @method PRBadgeArchive findOneOrCreate(PropelPDO $con = null) Return the first PRBadgeArchive matching the query, or a new PRBadgeArchive object populated from the query conditions when no match is found
 *
 * @method PRBadgeArchive findOneByPRBadgeTypeId(int $p_r_badge_type_id) Return the first PRBadgeArchive filtered by the p_r_badge_type_id column
 * @method PRBadgeArchive findOneByTitle(string $title) Return the first PRBadgeArchive filtered by the title column
 * @method PRBadgeArchive findOneByDescription(string $description) Return the first PRBadgeArchive filtered by the description column
 * @method PRBadgeArchive findOneByGrade(int $grade) Return the first PRBadgeArchive filtered by the grade column
 * @method PRBadgeArchive findOneByOnline(boolean $online) Return the first PRBadgeArchive filtered by the online column
 * @method PRBadgeArchive findOneByCreatedAt(string $created_at) Return the first PRBadgeArchive filtered by the created_at column
 * @method PRBadgeArchive findOneByUpdatedAt(string $updated_at) Return the first PRBadgeArchive filtered by the updated_at column
 * @method PRBadgeArchive findOneByArchivedAt(string $archived_at) Return the first PRBadgeArchive filtered by the archived_at column
 *
 * @method array findById(int $id) Return PRBadgeArchive objects filtered by the id column
 * @method array findByPRBadgeTypeId(int $p_r_badge_type_id) Return PRBadgeArchive objects filtered by the p_r_badge_type_id column
 * @method array findByTitle(string $title) Return PRBadgeArchive objects filtered by the title column
 * @method array findByDescription(string $description) Return PRBadgeArchive objects filtered by the description column
 * @method array findByGrade(int $grade) Return PRBadgeArchive objects filtered by the grade column
 * @method array findByOnline(boolean $online) Return PRBadgeArchive objects filtered by the online column
 * @method array findByCreatedAt(string $created_at) Return PRBadgeArchive objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PRBadgeArchive objects filtered by the updated_at column
 * @method array findByArchivedAt(string $archived_at) Return PRBadgeArchive objects filtered by the archived_at column
 */
abstract class BasePRBadgeArchiveQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePRBadgeArchiveQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Politizr\\Model\\PRBadgeArchive', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PRBadgeArchiveQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PRBadgeArchiveQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PRBadgeArchiveQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PRBadgeArchiveQuery) {
            return $criteria;
        }
        $query = new PRBadgeArchiveQuery();
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
     * @return   PRBadgeArchive|PRBadgeArchive[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PRBadgeArchivePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PRBadgeArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PRBadgeArchive A model object, or null if the key is not found
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
     * @return                 PRBadgeArchive A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `p_r_badge_type_id`, `title`, `description`, `grade`, `online`, `created_at`, `updated_at`, `archived_at` FROM `p_r_badge_archive` WHERE `id` = :p0';
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
            $obj = new PRBadgeArchive();
            $obj->hydrate($row);
            PRBadgeArchivePeer::addInstanceToPool($obj, (string) $key);
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
     * @return PRBadgeArchive|PRBadgeArchive[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PRBadgeArchive[]|mixed the list of results, formatted by the current formatter
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
     * @return PRBadgeArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PRBadgeArchivePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PRBadgeArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PRBadgeArchivePeer::ID, $keys, Criteria::IN);
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
     * @return PRBadgeArchiveQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PRBadgeArchivePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the p_r_badge_type_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPRBadgeTypeId(1234); // WHERE p_r_badge_type_id = 1234
     * $query->filterByPRBadgeTypeId(array(12, 34)); // WHERE p_r_badge_type_id IN (12, 34)
     * $query->filterByPRBadgeTypeId(array('min' => 12)); // WHERE p_r_badge_type_id >= 12
     * $query->filterByPRBadgeTypeId(array('max' => 12)); // WHERE p_r_badge_type_id <= 12
     * </code>
     *
     * @param     mixed $pRBadgeTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PRBadgeArchiveQuery The current query, for fluid interface
     */
    public function filterByPRBadgeTypeId($pRBadgeTypeId = null, $comparison = null)
    {
        if (is_array($pRBadgeTypeId)) {
            $useMinMax = false;
            if (isset($pRBadgeTypeId['min'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::P_R_BADGE_TYPE_ID, $pRBadgeTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pRBadgeTypeId['max'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::P_R_BADGE_TYPE_ID, $pRBadgeTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PRBadgeArchivePeer::P_R_BADGE_TYPE_ID, $pRBadgeTypeId, $comparison);
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
     * @return PRBadgeArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PRBadgeArchivePeer::TITLE, $title, $comparison);
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
     * @return PRBadgeArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PRBadgeArchivePeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the grade column
     *
     * @param     mixed $grade The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PRBadgeArchiveQuery The current query, for fluid interface
     * @throws PropelException - if the value is not accepted by the enum.
     */
    public function filterByGrade($grade = null, $comparison = null)
    {
        if (is_scalar($grade)) {
            $grade = PRBadgeArchivePeer::getSqlValueForEnum(PRBadgeArchivePeer::GRADE, $grade);
        } elseif (is_array($grade)) {
            $convertedValues = array();
            foreach ($grade as $value) {
                $convertedValues[] = PRBadgeArchivePeer::getSqlValueForEnum(PRBadgeArchivePeer::GRADE, $value);
            }
            $grade = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PRBadgeArchivePeer::GRADE, $grade, $comparison);
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
     * @return PRBadgeArchiveQuery The current query, for fluid interface
     */
    public function filterByOnline($online = null, $comparison = null)
    {
        if (is_string($online)) {
            $online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PRBadgeArchivePeer::ONLINE, $online, $comparison);
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
     * @return PRBadgeArchiveQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PRBadgeArchivePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PRBadgeArchiveQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PRBadgeArchivePeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return PRBadgeArchiveQuery The current query, for fluid interface
     */
    public function filterByArchivedAt($archivedAt = null, $comparison = null)
    {
        if (is_array($archivedAt)) {
            $useMinMax = false;
            if (isset($archivedAt['min'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::ARCHIVED_AT, $archivedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($archivedAt['max'])) {
                $this->addUsingAlias(PRBadgeArchivePeer::ARCHIVED_AT, $archivedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PRBadgeArchivePeer::ARCHIVED_AT, $archivedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   PRBadgeArchive $pRBadgeArchive Object to remove from the list of results
     *
     * @return PRBadgeArchiveQuery The current query, for fluid interface
     */
    public function prune($pRBadgeArchive = null)
    {
        if ($pRBadgeArchive) {
            $this->addUsingAlias(PRBadgeArchivePeer::ID, $pRBadgeArchive->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
