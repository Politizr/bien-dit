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
use Politizr\Model\PCircleArchive;
use Politizr\Model\PCircleArchivePeer;
use Politizr\Model\PCircleArchiveQuery;

/**
 * @method PCircleArchiveQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PCircleArchiveQuery orderByUuid($order = Criteria::ASC) Order by the uuid column
 * @method PCircleArchiveQuery orderByPCOwnerId($order = Criteria::ASC) Order by the p_c_owner_id column
 * @method PCircleArchiveQuery orderByPCircleTypeId($order = Criteria::ASC) Order by the p_circle_type_id column
 * @method PCircleArchiveQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method PCircleArchiveQuery orderBySummary($order = Criteria::ASC) Order by the summary column
 * @method PCircleArchiveQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method PCircleArchiveQuery orderByLogoFileName($order = Criteria::ASC) Order by the logo_file_name column
 * @method PCircleArchiveQuery orderByUrl($order = Criteria::ASC) Order by the url column
 * @method PCircleArchiveQuery orderByOnline($order = Criteria::ASC) Order by the online column
 * @method PCircleArchiveQuery orderByReadOnly($order = Criteria::ASC) Order by the read_only column
 * @method PCircleArchiveQuery orderByPrivateAccess($order = Criteria::ASC) Order by the private_access column
 * @method PCircleArchiveQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PCircleArchiveQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PCircleArchiveQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 * @method PCircleArchiveQuery orderBySortableRank($order = Criteria::ASC) Order by the sortable_rank column
 * @method PCircleArchiveQuery orderByArchivedAt($order = Criteria::ASC) Order by the archived_at column
 *
 * @method PCircleArchiveQuery groupById() Group by the id column
 * @method PCircleArchiveQuery groupByUuid() Group by the uuid column
 * @method PCircleArchiveQuery groupByPCOwnerId() Group by the p_c_owner_id column
 * @method PCircleArchiveQuery groupByPCircleTypeId() Group by the p_circle_type_id column
 * @method PCircleArchiveQuery groupByTitle() Group by the title column
 * @method PCircleArchiveQuery groupBySummary() Group by the summary column
 * @method PCircleArchiveQuery groupByDescription() Group by the description column
 * @method PCircleArchiveQuery groupByLogoFileName() Group by the logo_file_name column
 * @method PCircleArchiveQuery groupByUrl() Group by the url column
 * @method PCircleArchiveQuery groupByOnline() Group by the online column
 * @method PCircleArchiveQuery groupByReadOnly() Group by the read_only column
 * @method PCircleArchiveQuery groupByPrivateAccess() Group by the private_access column
 * @method PCircleArchiveQuery groupByCreatedAt() Group by the created_at column
 * @method PCircleArchiveQuery groupByUpdatedAt() Group by the updated_at column
 * @method PCircleArchiveQuery groupBySlug() Group by the slug column
 * @method PCircleArchiveQuery groupBySortableRank() Group by the sortable_rank column
 * @method PCircleArchiveQuery groupByArchivedAt() Group by the archived_at column
 *
 * @method PCircleArchiveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PCircleArchiveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PCircleArchiveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PCircleArchive findOne(PropelPDO $con = null) Return the first PCircleArchive matching the query
 * @method PCircleArchive findOneOrCreate(PropelPDO $con = null) Return the first PCircleArchive matching the query, or a new PCircleArchive object populated from the query conditions when no match is found
 *
 * @method PCircleArchive findOneByUuid(string $uuid) Return the first PCircleArchive filtered by the uuid column
 * @method PCircleArchive findOneByPCOwnerId(int $p_c_owner_id) Return the first PCircleArchive filtered by the p_c_owner_id column
 * @method PCircleArchive findOneByPCircleTypeId(int $p_circle_type_id) Return the first PCircleArchive filtered by the p_circle_type_id column
 * @method PCircleArchive findOneByTitle(string $title) Return the first PCircleArchive filtered by the title column
 * @method PCircleArchive findOneBySummary(string $summary) Return the first PCircleArchive filtered by the summary column
 * @method PCircleArchive findOneByDescription(string $description) Return the first PCircleArchive filtered by the description column
 * @method PCircleArchive findOneByLogoFileName(string $logo_file_name) Return the first PCircleArchive filtered by the logo_file_name column
 * @method PCircleArchive findOneByUrl(string $url) Return the first PCircleArchive filtered by the url column
 * @method PCircleArchive findOneByOnline(boolean $online) Return the first PCircleArchive filtered by the online column
 * @method PCircleArchive findOneByReadOnly(boolean $read_only) Return the first PCircleArchive filtered by the read_only column
 * @method PCircleArchive findOneByPrivateAccess(boolean $private_access) Return the first PCircleArchive filtered by the private_access column
 * @method PCircleArchive findOneByCreatedAt(string $created_at) Return the first PCircleArchive filtered by the created_at column
 * @method PCircleArchive findOneByUpdatedAt(string $updated_at) Return the first PCircleArchive filtered by the updated_at column
 * @method PCircleArchive findOneBySlug(string $slug) Return the first PCircleArchive filtered by the slug column
 * @method PCircleArchive findOneBySortableRank(int $sortable_rank) Return the first PCircleArchive filtered by the sortable_rank column
 * @method PCircleArchive findOneByArchivedAt(string $archived_at) Return the first PCircleArchive filtered by the archived_at column
 *
 * @method array findById(int $id) Return PCircleArchive objects filtered by the id column
 * @method array findByUuid(string $uuid) Return PCircleArchive objects filtered by the uuid column
 * @method array findByPCOwnerId(int $p_c_owner_id) Return PCircleArchive objects filtered by the p_c_owner_id column
 * @method array findByPCircleTypeId(int $p_circle_type_id) Return PCircleArchive objects filtered by the p_circle_type_id column
 * @method array findByTitle(string $title) Return PCircleArchive objects filtered by the title column
 * @method array findBySummary(string $summary) Return PCircleArchive objects filtered by the summary column
 * @method array findByDescription(string $description) Return PCircleArchive objects filtered by the description column
 * @method array findByLogoFileName(string $logo_file_name) Return PCircleArchive objects filtered by the logo_file_name column
 * @method array findByUrl(string $url) Return PCircleArchive objects filtered by the url column
 * @method array findByOnline(boolean $online) Return PCircleArchive objects filtered by the online column
 * @method array findByReadOnly(boolean $read_only) Return PCircleArchive objects filtered by the read_only column
 * @method array findByPrivateAccess(boolean $private_access) Return PCircleArchive objects filtered by the private_access column
 * @method array findByCreatedAt(string $created_at) Return PCircleArchive objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PCircleArchive objects filtered by the updated_at column
 * @method array findBySlug(string $slug) Return PCircleArchive objects filtered by the slug column
 * @method array findBySortableRank(int $sortable_rank) Return PCircleArchive objects filtered by the sortable_rank column
 * @method array findByArchivedAt(string $archived_at) Return PCircleArchive objects filtered by the archived_at column
 */
abstract class BasePCircleArchiveQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePCircleArchiveQuery object.
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
            $modelName = 'Politizr\\Model\\PCircleArchive';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PCircleArchiveQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PCircleArchiveQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PCircleArchiveQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PCircleArchiveQuery) {
            return $criteria;
        }
        $query = new PCircleArchiveQuery(null, null, $modelAlias);

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
     * @return   PCircleArchive|PCircleArchive[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PCircleArchivePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PCircleArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PCircleArchive A model object, or null if the key is not found
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
     * @return                 PCircleArchive A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `uuid`, `p_c_owner_id`, `p_circle_type_id`, `title`, `summary`, `description`, `logo_file_name`, `url`, `online`, `read_only`, `private_access`, `created_at`, `updated_at`, `slug`, `sortable_rank`, `archived_at` FROM `p_circle_archive` WHERE `id` = :p0';
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
            $obj = new PCircleArchive();
            $obj->hydrate($row);
            PCircleArchivePeer::addInstanceToPool($obj, (string) $key);
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
     * @return PCircleArchive|PCircleArchive[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PCircleArchive[]|mixed the list of results, formatted by the current formatter
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
     * @return PCircleArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PCircleArchivePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PCircleArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PCircleArchivePeer::ID, $keys, Criteria::IN);
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
     * @return PCircleArchiveQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PCircleArchivePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PCircleArchivePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCircleArchivePeer::ID, $id, $comparison);
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
     * @return PCircleArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PCircleArchivePeer::UUID, $uuid, $comparison);
    }

    /**
     * Filter the query on the p_c_owner_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPCOwnerId(1234); // WHERE p_c_owner_id = 1234
     * $query->filterByPCOwnerId(array(12, 34)); // WHERE p_c_owner_id IN (12, 34)
     * $query->filterByPCOwnerId(array('min' => 12)); // WHERE p_c_owner_id >= 12
     * $query->filterByPCOwnerId(array('max' => 12)); // WHERE p_c_owner_id <= 12
     * </code>
     *
     * @param     mixed $pCOwnerId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCircleArchiveQuery The current query, for fluid interface
     */
    public function filterByPCOwnerId($pCOwnerId = null, $comparison = null)
    {
        if (is_array($pCOwnerId)) {
            $useMinMax = false;
            if (isset($pCOwnerId['min'])) {
                $this->addUsingAlias(PCircleArchivePeer::P_C_OWNER_ID, $pCOwnerId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pCOwnerId['max'])) {
                $this->addUsingAlias(PCircleArchivePeer::P_C_OWNER_ID, $pCOwnerId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCircleArchivePeer::P_C_OWNER_ID, $pCOwnerId, $comparison);
    }

    /**
     * Filter the query on the p_circle_type_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPCircleTypeId(1234); // WHERE p_circle_type_id = 1234
     * $query->filterByPCircleTypeId(array(12, 34)); // WHERE p_circle_type_id IN (12, 34)
     * $query->filterByPCircleTypeId(array('min' => 12)); // WHERE p_circle_type_id >= 12
     * $query->filterByPCircleTypeId(array('max' => 12)); // WHERE p_circle_type_id <= 12
     * </code>
     *
     * @param     mixed $pCircleTypeId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCircleArchiveQuery The current query, for fluid interface
     */
    public function filterByPCircleTypeId($pCircleTypeId = null, $comparison = null)
    {
        if (is_array($pCircleTypeId)) {
            $useMinMax = false;
            if (isset($pCircleTypeId['min'])) {
                $this->addUsingAlias(PCircleArchivePeer::P_CIRCLE_TYPE_ID, $pCircleTypeId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pCircleTypeId['max'])) {
                $this->addUsingAlias(PCircleArchivePeer::P_CIRCLE_TYPE_ID, $pCircleTypeId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCircleArchivePeer::P_CIRCLE_TYPE_ID, $pCircleTypeId, $comparison);
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
     * @return PCircleArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PCircleArchivePeer::TITLE, $title, $comparison);
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
     * @return PCircleArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PCircleArchivePeer::SUMMARY, $summary, $comparison);
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
     * @return PCircleArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PCircleArchivePeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the logo_file_name column
     *
     * Example usage:
     * <code>
     * $query->filterByLogoFileName('fooValue');   // WHERE logo_file_name = 'fooValue'
     * $query->filterByLogoFileName('%fooValue%'); // WHERE logo_file_name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $logoFileName The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCircleArchiveQuery The current query, for fluid interface
     */
    public function filterByLogoFileName($logoFileName = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($logoFileName)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $logoFileName)) {
                $logoFileName = str_replace('*', '%', $logoFileName);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PCircleArchivePeer::LOGO_FILE_NAME, $logoFileName, $comparison);
    }

    /**
     * Filter the query on the url column
     *
     * Example usage:
     * <code>
     * $query->filterByUrl('fooValue');   // WHERE url = 'fooValue'
     * $query->filterByUrl('%fooValue%'); // WHERE url LIKE '%fooValue%'
     * </code>
     *
     * @param     string $url The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCircleArchiveQuery The current query, for fluid interface
     */
    public function filterByUrl($url = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($url)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $url)) {
                $url = str_replace('*', '%', $url);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PCircleArchivePeer::URL, $url, $comparison);
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
     * @return PCircleArchiveQuery The current query, for fluid interface
     */
    public function filterByOnline($online = null, $comparison = null)
    {
        if (is_string($online)) {
            $online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PCircleArchivePeer::ONLINE, $online, $comparison);
    }

    /**
     * Filter the query on the read_only column
     *
     * Example usage:
     * <code>
     * $query->filterByReadOnly(true); // WHERE read_only = true
     * $query->filterByReadOnly('yes'); // WHERE read_only = true
     * </code>
     *
     * @param     boolean|string $readOnly The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCircleArchiveQuery The current query, for fluid interface
     */
    public function filterByReadOnly($readOnly = null, $comparison = null)
    {
        if (is_string($readOnly)) {
            $readOnly = in_array(strtolower($readOnly), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PCircleArchivePeer::READ_ONLY, $readOnly, $comparison);
    }

    /**
     * Filter the query on the private_access column
     *
     * Example usage:
     * <code>
     * $query->filterByPrivateAccess(true); // WHERE private_access = true
     * $query->filterByPrivateAccess('yes'); // WHERE private_access = true
     * </code>
     *
     * @param     boolean|string $privateAccess The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PCircleArchiveQuery The current query, for fluid interface
     */
    public function filterByPrivateAccess($privateAccess = null, $comparison = null)
    {
        if (is_string($privateAccess)) {
            $privateAccess = in_array(strtolower($privateAccess), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PCircleArchivePeer::PRIVATE_ACCESS, $privateAccess, $comparison);
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
     * @return PCircleArchiveQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PCircleArchivePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PCircleArchivePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCircleArchivePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PCircleArchiveQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PCircleArchivePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PCircleArchivePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCircleArchivePeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return PCircleArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PCircleArchivePeer::SLUG, $slug, $comparison);
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
     * @return PCircleArchiveQuery The current query, for fluid interface
     */
    public function filterBySortableRank($sortableRank = null, $comparison = null)
    {
        if (is_array($sortableRank)) {
            $useMinMax = false;
            if (isset($sortableRank['min'])) {
                $this->addUsingAlias(PCircleArchivePeer::SORTABLE_RANK, $sortableRank['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($sortableRank['max'])) {
                $this->addUsingAlias(PCircleArchivePeer::SORTABLE_RANK, $sortableRank['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCircleArchivePeer::SORTABLE_RANK, $sortableRank, $comparison);
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
     * @return PCircleArchiveQuery The current query, for fluid interface
     */
    public function filterByArchivedAt($archivedAt = null, $comparison = null)
    {
        if (is_array($archivedAt)) {
            $useMinMax = false;
            if (isset($archivedAt['min'])) {
                $this->addUsingAlias(PCircleArchivePeer::ARCHIVED_AT, $archivedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($archivedAt['max'])) {
                $this->addUsingAlias(PCircleArchivePeer::ARCHIVED_AT, $archivedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PCircleArchivePeer::ARCHIVED_AT, $archivedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   PCircleArchive $pCircleArchive Object to remove from the list of results
     *
     * @return PCircleArchiveQuery The current query, for fluid interface
     */
    public function prune($pCircleArchive = null)
    {
        if ($pCircleArchive) {
            $this->addUsingAlias(PCircleArchivePeer::ID, $pCircleArchive->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
