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
use Politizr\Model\PDocumentArchive;
use Politizr\Model\PDocumentArchivePeer;
use Politizr\Model\PDocumentArchiveQuery;

/**
 * @method PDocumentArchiveQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PDocumentArchiveQuery orderByPUserId($order = Criteria::ASC) Order by the p_user_id column
 * @method PDocumentArchiveQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method PDocumentArchiveQuery orderBySummary($order = Criteria::ASC) Order by the summary column
 * @method PDocumentArchiveQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method PDocumentArchiveQuery orderByMoreInfo($order = Criteria::ASC) Order by the more_info column
 * @method PDocumentArchiveQuery orderByNotePos($order = Criteria::ASC) Order by the note_pos column
 * @method PDocumentArchiveQuery orderByNoteNeg($order = Criteria::ASC) Order by the note_neg column
 * @method PDocumentArchiveQuery orderByNbViews($order = Criteria::ASC) Order by the nb_views column
 * @method PDocumentArchiveQuery orderByPublished($order = Criteria::ASC) Order by the published column
 * @method PDocumentArchiveQuery orderByPublishedAt($order = Criteria::ASC) Order by the published_at column
 * @method PDocumentArchiveQuery orderByPublishedBy($order = Criteria::ASC) Order by the published_by column
 * @method PDocumentArchiveQuery orderByOnline($order = Criteria::ASC) Order by the online column
 * @method PDocumentArchiveQuery orderByBroadcast($order = Criteria::ASC) Order by the broadcast column
 * @method PDocumentArchiveQuery orderByArchivedAt($order = Criteria::ASC) Order by the archived_at column
 *
 * @method PDocumentArchiveQuery groupById() Group by the id column
 * @method PDocumentArchiveQuery groupByPUserId() Group by the p_user_id column
 * @method PDocumentArchiveQuery groupByTitle() Group by the title column
 * @method PDocumentArchiveQuery groupBySummary() Group by the summary column
 * @method PDocumentArchiveQuery groupByDescription() Group by the description column
 * @method PDocumentArchiveQuery groupByMoreInfo() Group by the more_info column
 * @method PDocumentArchiveQuery groupByNotePos() Group by the note_pos column
 * @method PDocumentArchiveQuery groupByNoteNeg() Group by the note_neg column
 * @method PDocumentArchiveQuery groupByNbViews() Group by the nb_views column
 * @method PDocumentArchiveQuery groupByPublished() Group by the published column
 * @method PDocumentArchiveQuery groupByPublishedAt() Group by the published_at column
 * @method PDocumentArchiveQuery groupByPublishedBy() Group by the published_by column
 * @method PDocumentArchiveQuery groupByOnline() Group by the online column
 * @method PDocumentArchiveQuery groupByBroadcast() Group by the broadcast column
 * @method PDocumentArchiveQuery groupByArchivedAt() Group by the archived_at column
 *
 * @method PDocumentArchiveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PDocumentArchiveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PDocumentArchiveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PDocumentArchive findOne(PropelPDO $con = null) Return the first PDocumentArchive matching the query
 * @method PDocumentArchive findOneOrCreate(PropelPDO $con = null) Return the first PDocumentArchive matching the query, or a new PDocumentArchive object populated from the query conditions when no match is found
 *
 * @method PDocumentArchive findOneByPUserId(int $p_user_id) Return the first PDocumentArchive filtered by the p_user_id column
 * @method PDocumentArchive findOneByTitle(string $title) Return the first PDocumentArchive filtered by the title column
 * @method PDocumentArchive findOneBySummary(string $summary) Return the first PDocumentArchive filtered by the summary column
 * @method PDocumentArchive findOneByDescription(string $description) Return the first PDocumentArchive filtered by the description column
 * @method PDocumentArchive findOneByMoreInfo(string $more_info) Return the first PDocumentArchive filtered by the more_info column
 * @method PDocumentArchive findOneByNotePos(int $note_pos) Return the first PDocumentArchive filtered by the note_pos column
 * @method PDocumentArchive findOneByNoteNeg(int $note_neg) Return the first PDocumentArchive filtered by the note_neg column
 * @method PDocumentArchive findOneByNbViews(int $nb_views) Return the first PDocumentArchive filtered by the nb_views column
 * @method PDocumentArchive findOneByPublished(boolean $published) Return the first PDocumentArchive filtered by the published column
 * @method PDocumentArchive findOneByPublishedAt(string $published_at) Return the first PDocumentArchive filtered by the published_at column
 * @method PDocumentArchive findOneByPublishedBy(string $published_by) Return the first PDocumentArchive filtered by the published_by column
 * @method PDocumentArchive findOneByOnline(boolean $online) Return the first PDocumentArchive filtered by the online column
 * @method PDocumentArchive findOneByBroadcast(boolean $broadcast) Return the first PDocumentArchive filtered by the broadcast column
 * @method PDocumentArchive findOneByArchivedAt(string $archived_at) Return the first PDocumentArchive filtered by the archived_at column
 *
 * @method array findById(int $id) Return PDocumentArchive objects filtered by the id column
 * @method array findByPUserId(int $p_user_id) Return PDocumentArchive objects filtered by the p_user_id column
 * @method array findByTitle(string $title) Return PDocumentArchive objects filtered by the title column
 * @method array findBySummary(string $summary) Return PDocumentArchive objects filtered by the summary column
 * @method array findByDescription(string $description) Return PDocumentArchive objects filtered by the description column
 * @method array findByMoreInfo(string $more_info) Return PDocumentArchive objects filtered by the more_info column
 * @method array findByNotePos(int $note_pos) Return PDocumentArchive objects filtered by the note_pos column
 * @method array findByNoteNeg(int $note_neg) Return PDocumentArchive objects filtered by the note_neg column
 * @method array findByNbViews(int $nb_views) Return PDocumentArchive objects filtered by the nb_views column
 * @method array findByPublished(boolean $published) Return PDocumentArchive objects filtered by the published column
 * @method array findByPublishedAt(string $published_at) Return PDocumentArchive objects filtered by the published_at column
 * @method array findByPublishedBy(string $published_by) Return PDocumentArchive objects filtered by the published_by column
 * @method array findByOnline(boolean $online) Return PDocumentArchive objects filtered by the online column
 * @method array findByBroadcast(boolean $broadcast) Return PDocumentArchive objects filtered by the broadcast column
 * @method array findByArchivedAt(string $archived_at) Return PDocumentArchive objects filtered by the archived_at column
 */
abstract class BasePDocumentArchiveQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePDocumentArchiveQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Politizr\\Model\\PDocumentArchive', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PDocumentArchiveQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PDocumentArchiveQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PDocumentArchiveQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PDocumentArchiveQuery) {
            return $criteria;
        }
        $query = new PDocumentArchiveQuery();
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
     * @return   PDocumentArchive|PDocumentArchive[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PDocumentArchivePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PDocumentArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PDocumentArchive A model object, or null if the key is not found
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
     * @return                 PDocumentArchive A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `p_user_id`, `title`, `summary`, `description`, `more_info`, `note_pos`, `note_neg`, `nb_views`, `published`, `published_at`, `published_by`, `online`, `broadcast`, `archived_at` FROM `p_document_archive` WHERE `id` = :p0';
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
            $obj = new PDocumentArchive();
            $obj->hydrate($row);
            PDocumentArchivePeer::addInstanceToPool($obj, (string) $key);
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
     * @return PDocumentArchive|PDocumentArchive[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PDocumentArchive[]|mixed the list of results, formatted by the current formatter
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
     * @return PDocumentArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PDocumentArchivePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PDocumentArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PDocumentArchivePeer::ID, $keys, Criteria::IN);
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
     * @return PDocumentArchiveQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PDocumentArchivePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PDocumentArchivePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDocumentArchivePeer::ID, $id, $comparison);
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
     * @return PDocumentArchiveQuery The current query, for fluid interface
     */
    public function filterByPUserId($pUserId = null, $comparison = null)
    {
        if (is_array($pUserId)) {
            $useMinMax = false;
            if (isset($pUserId['min'])) {
                $this->addUsingAlias(PDocumentArchivePeer::P_USER_ID, $pUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserId['max'])) {
                $this->addUsingAlias(PDocumentArchivePeer::P_USER_ID, $pUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDocumentArchivePeer::P_USER_ID, $pUserId, $comparison);
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
     * @return PDocumentArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PDocumentArchivePeer::TITLE, $title, $comparison);
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
     * @return PDocumentArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PDocumentArchivePeer::SUMMARY, $summary, $comparison);
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
     * @return PDocumentArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PDocumentArchivePeer::DESCRIPTION, $description, $comparison);
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
     * @return PDocumentArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PDocumentArchivePeer::MORE_INFO, $moreInfo, $comparison);
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
     * @return PDocumentArchiveQuery The current query, for fluid interface
     */
    public function filterByNotePos($notePos = null, $comparison = null)
    {
        if (is_array($notePos)) {
            $useMinMax = false;
            if (isset($notePos['min'])) {
                $this->addUsingAlias(PDocumentArchivePeer::NOTE_POS, $notePos['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($notePos['max'])) {
                $this->addUsingAlias(PDocumentArchivePeer::NOTE_POS, $notePos['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDocumentArchivePeer::NOTE_POS, $notePos, $comparison);
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
     * @return PDocumentArchiveQuery The current query, for fluid interface
     */
    public function filterByNoteNeg($noteNeg = null, $comparison = null)
    {
        if (is_array($noteNeg)) {
            $useMinMax = false;
            if (isset($noteNeg['min'])) {
                $this->addUsingAlias(PDocumentArchivePeer::NOTE_NEG, $noteNeg['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($noteNeg['max'])) {
                $this->addUsingAlias(PDocumentArchivePeer::NOTE_NEG, $noteNeg['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDocumentArchivePeer::NOTE_NEG, $noteNeg, $comparison);
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
     * @return PDocumentArchiveQuery The current query, for fluid interface
     */
    public function filterByNbViews($nbViews = null, $comparison = null)
    {
        if (is_array($nbViews)) {
            $useMinMax = false;
            if (isset($nbViews['min'])) {
                $this->addUsingAlias(PDocumentArchivePeer::NB_VIEWS, $nbViews['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nbViews['max'])) {
                $this->addUsingAlias(PDocumentArchivePeer::NB_VIEWS, $nbViews['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDocumentArchivePeer::NB_VIEWS, $nbViews, $comparison);
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
     * @return PDocumentArchiveQuery The current query, for fluid interface
     */
    public function filterByPublished($published = null, $comparison = null)
    {
        if (is_string($published)) {
            $published = in_array(strtolower($published), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PDocumentArchivePeer::PUBLISHED, $published, $comparison);
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
     * @return PDocumentArchiveQuery The current query, for fluid interface
     */
    public function filterByPublishedAt($publishedAt = null, $comparison = null)
    {
        if (is_array($publishedAt)) {
            $useMinMax = false;
            if (isset($publishedAt['min'])) {
                $this->addUsingAlias(PDocumentArchivePeer::PUBLISHED_AT, $publishedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publishedAt['max'])) {
                $this->addUsingAlias(PDocumentArchivePeer::PUBLISHED_AT, $publishedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDocumentArchivePeer::PUBLISHED_AT, $publishedAt, $comparison);
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
     * @return PDocumentArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PDocumentArchivePeer::PUBLISHED_BY, $publishedBy, $comparison);
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
     * @return PDocumentArchiveQuery The current query, for fluid interface
     */
    public function filterByOnline($online = null, $comparison = null)
    {
        if (is_string($online)) {
            $online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PDocumentArchivePeer::ONLINE, $online, $comparison);
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
     * @return PDocumentArchiveQuery The current query, for fluid interface
     */
    public function filterByBroadcast($broadcast = null, $comparison = null)
    {
        if (is_string($broadcast)) {
            $broadcast = in_array(strtolower($broadcast), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PDocumentArchivePeer::BROADCAST, $broadcast, $comparison);
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
     * @return PDocumentArchiveQuery The current query, for fluid interface
     */
    public function filterByArchivedAt($archivedAt = null, $comparison = null)
    {
        if (is_array($archivedAt)) {
            $useMinMax = false;
            if (isset($archivedAt['min'])) {
                $this->addUsingAlias(PDocumentArchivePeer::ARCHIVED_AT, $archivedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($archivedAt['max'])) {
                $this->addUsingAlias(PDocumentArchivePeer::ARCHIVED_AT, $archivedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDocumentArchivePeer::ARCHIVED_AT, $archivedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   PDocumentArchive $pDocumentArchive Object to remove from the list of results
     *
     * @return PDocumentArchiveQuery The current query, for fluid interface
     */
    public function prune($pDocumentArchive = null)
    {
        if ($pDocumentArchive) {
            $this->addUsingAlias(PDocumentArchivePeer::ID, $pDocumentArchive->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
