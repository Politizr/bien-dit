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
use Politizr\Model\PDDebateArchive;
use Politizr\Model\PDDebateArchivePeer;
use Politizr\Model\PDDebateArchiveQuery;

/**
 * @method PDDebateArchiveQuery orderByFileName($order = Criteria::ASC) Order by the file_name column
 * @method PDDebateArchiveQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PDDebateArchiveQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PDDebateArchiveQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 * @method PDDebateArchiveQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PDDebateArchiveQuery orderByPUserId($order = Criteria::ASC) Order by the p_user_id column
 * @method PDDebateArchiveQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method PDDebateArchiveQuery orderBySummary($order = Criteria::ASC) Order by the summary column
 * @method PDDebateArchiveQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method PDDebateArchiveQuery orderByMoreInfo($order = Criteria::ASC) Order by the more_info column
 * @method PDDebateArchiveQuery orderByNotePos($order = Criteria::ASC) Order by the note_pos column
 * @method PDDebateArchiveQuery orderByNoteNeg($order = Criteria::ASC) Order by the note_neg column
 * @method PDDebateArchiveQuery orderByNbViews($order = Criteria::ASC) Order by the nb_views column
 * @method PDDebateArchiveQuery orderByPublished($order = Criteria::ASC) Order by the published column
 * @method PDDebateArchiveQuery orderByPublishedAt($order = Criteria::ASC) Order by the published_at column
 * @method PDDebateArchiveQuery orderByPublishedBy($order = Criteria::ASC) Order by the published_by column
 * @method PDDebateArchiveQuery orderByOnline($order = Criteria::ASC) Order by the online column
 * @method PDDebateArchiveQuery orderByBroadcast($order = Criteria::ASC) Order by the broadcast column
 * @method PDDebateArchiveQuery orderByArchivedAt($order = Criteria::ASC) Order by the archived_at column
 *
 * @method PDDebateArchiveQuery groupByFileName() Group by the file_name column
 * @method PDDebateArchiveQuery groupByCreatedAt() Group by the created_at column
 * @method PDDebateArchiveQuery groupByUpdatedAt() Group by the updated_at column
 * @method PDDebateArchiveQuery groupBySlug() Group by the slug column
 * @method PDDebateArchiveQuery groupById() Group by the id column
 * @method PDDebateArchiveQuery groupByPUserId() Group by the p_user_id column
 * @method PDDebateArchiveQuery groupByTitle() Group by the title column
 * @method PDDebateArchiveQuery groupBySummary() Group by the summary column
 * @method PDDebateArchiveQuery groupByDescription() Group by the description column
 * @method PDDebateArchiveQuery groupByMoreInfo() Group by the more_info column
 * @method PDDebateArchiveQuery groupByNotePos() Group by the note_pos column
 * @method PDDebateArchiveQuery groupByNoteNeg() Group by the note_neg column
 * @method PDDebateArchiveQuery groupByNbViews() Group by the nb_views column
 * @method PDDebateArchiveQuery groupByPublished() Group by the published column
 * @method PDDebateArchiveQuery groupByPublishedAt() Group by the published_at column
 * @method PDDebateArchiveQuery groupByPublishedBy() Group by the published_by column
 * @method PDDebateArchiveQuery groupByOnline() Group by the online column
 * @method PDDebateArchiveQuery groupByBroadcast() Group by the broadcast column
 * @method PDDebateArchiveQuery groupByArchivedAt() Group by the archived_at column
 *
 * @method PDDebateArchiveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PDDebateArchiveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PDDebateArchiveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PDDebateArchive findOne(PropelPDO $con = null) Return the first PDDebateArchive matching the query
 * @method PDDebateArchive findOneOrCreate(PropelPDO $con = null) Return the first PDDebateArchive matching the query, or a new PDDebateArchive object populated from the query conditions when no match is found
 *
 * @method PDDebateArchive findOneByFileName(string $file_name) Return the first PDDebateArchive filtered by the file_name column
 * @method PDDebateArchive findOneByCreatedAt(string $created_at) Return the first PDDebateArchive filtered by the created_at column
 * @method PDDebateArchive findOneByUpdatedAt(string $updated_at) Return the first PDDebateArchive filtered by the updated_at column
 * @method PDDebateArchive findOneBySlug(string $slug) Return the first PDDebateArchive filtered by the slug column
 * @method PDDebateArchive findOneByPUserId(int $p_user_id) Return the first PDDebateArchive filtered by the p_user_id column
 * @method PDDebateArchive findOneByTitle(string $title) Return the first PDDebateArchive filtered by the title column
 * @method PDDebateArchive findOneBySummary(string $summary) Return the first PDDebateArchive filtered by the summary column
 * @method PDDebateArchive findOneByDescription(string $description) Return the first PDDebateArchive filtered by the description column
 * @method PDDebateArchive findOneByMoreInfo(string $more_info) Return the first PDDebateArchive filtered by the more_info column
 * @method PDDebateArchive findOneByNotePos(int $note_pos) Return the first PDDebateArchive filtered by the note_pos column
 * @method PDDebateArchive findOneByNoteNeg(int $note_neg) Return the first PDDebateArchive filtered by the note_neg column
 * @method PDDebateArchive findOneByNbViews(int $nb_views) Return the first PDDebateArchive filtered by the nb_views column
 * @method PDDebateArchive findOneByPublished(boolean $published) Return the first PDDebateArchive filtered by the published column
 * @method PDDebateArchive findOneByPublishedAt(string $published_at) Return the first PDDebateArchive filtered by the published_at column
 * @method PDDebateArchive findOneByPublishedBy(string $published_by) Return the first PDDebateArchive filtered by the published_by column
 * @method PDDebateArchive findOneByOnline(boolean $online) Return the first PDDebateArchive filtered by the online column
 * @method PDDebateArchive findOneByBroadcast(boolean $broadcast) Return the first PDDebateArchive filtered by the broadcast column
 * @method PDDebateArchive findOneByArchivedAt(string $archived_at) Return the first PDDebateArchive filtered by the archived_at column
 *
 * @method array findByFileName(string $file_name) Return PDDebateArchive objects filtered by the file_name column
 * @method array findByCreatedAt(string $created_at) Return PDDebateArchive objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PDDebateArchive objects filtered by the updated_at column
 * @method array findBySlug(string $slug) Return PDDebateArchive objects filtered by the slug column
 * @method array findById(int $id) Return PDDebateArchive objects filtered by the id column
 * @method array findByPUserId(int $p_user_id) Return PDDebateArchive objects filtered by the p_user_id column
 * @method array findByTitle(string $title) Return PDDebateArchive objects filtered by the title column
 * @method array findBySummary(string $summary) Return PDDebateArchive objects filtered by the summary column
 * @method array findByDescription(string $description) Return PDDebateArchive objects filtered by the description column
 * @method array findByMoreInfo(string $more_info) Return PDDebateArchive objects filtered by the more_info column
 * @method array findByNotePos(int $note_pos) Return PDDebateArchive objects filtered by the note_pos column
 * @method array findByNoteNeg(int $note_neg) Return PDDebateArchive objects filtered by the note_neg column
 * @method array findByNbViews(int $nb_views) Return PDDebateArchive objects filtered by the nb_views column
 * @method array findByPublished(boolean $published) Return PDDebateArchive objects filtered by the published column
 * @method array findByPublishedAt(string $published_at) Return PDDebateArchive objects filtered by the published_at column
 * @method array findByPublishedBy(string $published_by) Return PDDebateArchive objects filtered by the published_by column
 * @method array findByOnline(boolean $online) Return PDDebateArchive objects filtered by the online column
 * @method array findByBroadcast(boolean $broadcast) Return PDDebateArchive objects filtered by the broadcast column
 * @method array findByArchivedAt(string $archived_at) Return PDDebateArchive objects filtered by the archived_at column
 */
abstract class BasePDDebateArchiveQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePDDebateArchiveQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Politizr\\Model\\PDDebateArchive', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PDDebateArchiveQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PDDebateArchiveQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PDDebateArchiveQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PDDebateArchiveQuery) {
            return $criteria;
        }
        $query = new PDDebateArchiveQuery();
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
     * @return   PDDebateArchive|PDDebateArchive[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PDDebateArchivePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PDDebateArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PDDebateArchive A model object, or null if the key is not found
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
     * @return                 PDDebateArchive A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `file_name`, `created_at`, `updated_at`, `slug`, `id`, `p_user_id`, `title`, `summary`, `description`, `more_info`, `note_pos`, `note_neg`, `nb_views`, `published`, `published_at`, `published_by`, `online`, `broadcast`, `archived_at` FROM `p_d_debate_archive` WHERE `id` = :p0';
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
            $obj = new PDDebateArchive();
            $obj->hydrate($row);
            PDDebateArchivePeer::addInstanceToPool($obj, (string) $key);
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
     * @return PDDebateArchive|PDDebateArchive[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PDDebateArchive[]|mixed the list of results, formatted by the current formatter
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
     * @return PDDebateArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PDDebateArchivePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PDDebateArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PDDebateArchivePeer::ID, $keys, Criteria::IN);
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
     * @return PDDebateArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PDDebateArchivePeer::FILE_NAME, $fileName, $comparison);
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
     * @return PDDebateArchiveQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PDDebateArchivePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PDDebateArchivePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDebateArchivePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PDDebateArchiveQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PDDebateArchivePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PDDebateArchivePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDebateArchivePeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return PDDebateArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PDDebateArchivePeer::SLUG, $slug, $comparison);
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
     * @return PDDebateArchiveQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PDDebateArchivePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PDDebateArchivePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDebateArchivePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the p_user_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPUserId(1234); // WHERE p_user_id = 1234
     * $query->filterByPUserId(array(12, 34)); // WHERE p_user_id IN (12, 34)
     * $query->filterByPUserId(array('min' => 12)); // WHERE p_user_id >= 12
     * $query->filterByPUserId(array('max' => 12)); // WHERE p_user_id <= 12
     * </code>
     *
     * @param     mixed $pUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDDebateArchiveQuery The current query, for fluid interface
     */
    public function filterByPUserId($pUserId = null, $comparison = null)
    {
        if (is_array($pUserId)) {
            $useMinMax = false;
            if (isset($pUserId['min'])) {
                $this->addUsingAlias(PDDebateArchivePeer::P_USER_ID, $pUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserId['max'])) {
                $this->addUsingAlias(PDDebateArchivePeer::P_USER_ID, $pUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDebateArchivePeer::P_USER_ID, $pUserId, $comparison);
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
     * @return PDDebateArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PDDebateArchivePeer::TITLE, $title, $comparison);
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
     * @return PDDebateArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PDDebateArchivePeer::SUMMARY, $summary, $comparison);
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
     * @return PDDebateArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PDDebateArchivePeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the more_info column
     *
     * Example usage:
     * <code>
     * $query->filterByMoreInfo('fooValue');   // WHERE more_info = 'fooValue'
     * $query->filterByMoreInfo('%fooValue%'); // WHERE more_info LIKE '%fooValue%'
     * </code>
     *
     * @param     string $moreInfo The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDDebateArchiveQuery The current query, for fluid interface
     */
    public function filterByMoreInfo($moreInfo = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($moreInfo)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $moreInfo)) {
                $moreInfo = str_replace('*', '%', $moreInfo);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PDDebateArchivePeer::MORE_INFO, $moreInfo, $comparison);
    }

    /**
     * Filter the query on the note_pos column
     *
     * Example usage:
     * <code>
     * $query->filterByNotePos(1234); // WHERE note_pos = 1234
     * $query->filterByNotePos(array(12, 34)); // WHERE note_pos IN (12, 34)
     * $query->filterByNotePos(array('min' => 12)); // WHERE note_pos >= 12
     * $query->filterByNotePos(array('max' => 12)); // WHERE note_pos <= 12
     * </code>
     *
     * @param     mixed $notePos The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDDebateArchiveQuery The current query, for fluid interface
     */
    public function filterByNotePos($notePos = null, $comparison = null)
    {
        if (is_array($notePos)) {
            $useMinMax = false;
            if (isset($notePos['min'])) {
                $this->addUsingAlias(PDDebateArchivePeer::NOTE_POS, $notePos['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($notePos['max'])) {
                $this->addUsingAlias(PDDebateArchivePeer::NOTE_POS, $notePos['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDebateArchivePeer::NOTE_POS, $notePos, $comparison);
    }

    /**
     * Filter the query on the note_neg column
     *
     * Example usage:
     * <code>
     * $query->filterByNoteNeg(1234); // WHERE note_neg = 1234
     * $query->filterByNoteNeg(array(12, 34)); // WHERE note_neg IN (12, 34)
     * $query->filterByNoteNeg(array('min' => 12)); // WHERE note_neg >= 12
     * $query->filterByNoteNeg(array('max' => 12)); // WHERE note_neg <= 12
     * </code>
     *
     * @param     mixed $noteNeg The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDDebateArchiveQuery The current query, for fluid interface
     */
    public function filterByNoteNeg($noteNeg = null, $comparison = null)
    {
        if (is_array($noteNeg)) {
            $useMinMax = false;
            if (isset($noteNeg['min'])) {
                $this->addUsingAlias(PDDebateArchivePeer::NOTE_NEG, $noteNeg['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($noteNeg['max'])) {
                $this->addUsingAlias(PDDebateArchivePeer::NOTE_NEG, $noteNeg['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDebateArchivePeer::NOTE_NEG, $noteNeg, $comparison);
    }

    /**
     * Filter the query on the nb_views column
     *
     * Example usage:
     * <code>
     * $query->filterByNbViews(1234); // WHERE nb_views = 1234
     * $query->filterByNbViews(array(12, 34)); // WHERE nb_views IN (12, 34)
     * $query->filterByNbViews(array('min' => 12)); // WHERE nb_views >= 12
     * $query->filterByNbViews(array('max' => 12)); // WHERE nb_views <= 12
     * </code>
     *
     * @param     mixed $nbViews The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDDebateArchiveQuery The current query, for fluid interface
     */
    public function filterByNbViews($nbViews = null, $comparison = null)
    {
        if (is_array($nbViews)) {
            $useMinMax = false;
            if (isset($nbViews['min'])) {
                $this->addUsingAlias(PDDebateArchivePeer::NB_VIEWS, $nbViews['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nbViews['max'])) {
                $this->addUsingAlias(PDDebateArchivePeer::NB_VIEWS, $nbViews['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDebateArchivePeer::NB_VIEWS, $nbViews, $comparison);
    }

    /**
     * Filter the query on the published column
     *
     * Example usage:
     * <code>
     * $query->filterByPublished(true); // WHERE published = true
     * $query->filterByPublished('yes'); // WHERE published = true
     * </code>
     *
     * @param     boolean|string $published The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDDebateArchiveQuery The current query, for fluid interface
     */
    public function filterByPublished($published = null, $comparison = null)
    {
        if (is_string($published)) {
            $published = in_array(strtolower($published), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PDDebateArchivePeer::PUBLISHED, $published, $comparison);
    }

    /**
     * Filter the query on the published_at column
     *
     * Example usage:
     * <code>
     * $query->filterByPublishedAt('2011-03-14'); // WHERE published_at = '2011-03-14'
     * $query->filterByPublishedAt('now'); // WHERE published_at = '2011-03-14'
     * $query->filterByPublishedAt(array('max' => 'yesterday')); // WHERE published_at > '2011-03-13'
     * </code>
     *
     * @param     mixed $publishedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDDebateArchiveQuery The current query, for fluid interface
     */
    public function filterByPublishedAt($publishedAt = null, $comparison = null)
    {
        if (is_array($publishedAt)) {
            $useMinMax = false;
            if (isset($publishedAt['min'])) {
                $this->addUsingAlias(PDDebateArchivePeer::PUBLISHED_AT, $publishedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publishedAt['max'])) {
                $this->addUsingAlias(PDDebateArchivePeer::PUBLISHED_AT, $publishedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDebateArchivePeer::PUBLISHED_AT, $publishedAt, $comparison);
    }

    /**
     * Filter the query on the published_by column
     *
     * Example usage:
     * <code>
     * $query->filterByPublishedBy('fooValue');   // WHERE published_by = 'fooValue'
     * $query->filterByPublishedBy('%fooValue%'); // WHERE published_by LIKE '%fooValue%'
     * </code>
     *
     * @param     string $publishedBy The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDDebateArchiveQuery The current query, for fluid interface
     */
    public function filterByPublishedBy($publishedBy = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($publishedBy)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $publishedBy)) {
                $publishedBy = str_replace('*', '%', $publishedBy);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PDDebateArchivePeer::PUBLISHED_BY, $publishedBy, $comparison);
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
     * @return PDDebateArchiveQuery The current query, for fluid interface
     */
    public function filterByOnline($online = null, $comparison = null)
    {
        if (is_string($online)) {
            $online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PDDebateArchivePeer::ONLINE, $online, $comparison);
    }

    /**
     * Filter the query on the broadcast column
     *
     * Example usage:
     * <code>
     * $query->filterByBroadcast(true); // WHERE broadcast = true
     * $query->filterByBroadcast('yes'); // WHERE broadcast = true
     * </code>
     *
     * @param     boolean|string $broadcast The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDDebateArchiveQuery The current query, for fluid interface
     */
    public function filterByBroadcast($broadcast = null, $comparison = null)
    {
        if (is_string($broadcast)) {
            $broadcast = in_array(strtolower($broadcast), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PDDebateArchivePeer::BROADCAST, $broadcast, $comparison);
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
     * @return PDDebateArchiveQuery The current query, for fluid interface
     */
    public function filterByArchivedAt($archivedAt = null, $comparison = null)
    {
        if (is_array($archivedAt)) {
            $useMinMax = false;
            if (isset($archivedAt['min'])) {
                $this->addUsingAlias(PDDebateArchivePeer::ARCHIVED_AT, $archivedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($archivedAt['max'])) {
                $this->addUsingAlias(PDDebateArchivePeer::ARCHIVED_AT, $archivedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDebateArchivePeer::ARCHIVED_AT, $archivedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   PDDebateArchive $pDDebateArchive Object to remove from the list of results
     *
     * @return PDDebateArchiveQuery The current query, for fluid interface
     */
    public function prune($pDDebateArchive = null)
    {
        if ($pDDebateArchive) {
            $this->addUsingAlias(PDDebateArchivePeer::ID, $pDDebateArchive->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}