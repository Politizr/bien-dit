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
use Politizr\Model\PDCommentArchive;
use Politizr\Model\PDCommentArchivePeer;
use Politizr\Model\PDCommentArchiveQuery;

/**
 * @method PDCommentArchiveQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PDCommentArchiveQuery orderByPUserId($order = Criteria::ASC) Order by the p_user_id column
 * @method PDCommentArchiveQuery orderByPDocumentId($order = Criteria::ASC) Order by the p_document_id column
 * @method PDCommentArchiveQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method PDCommentArchiveQuery orderByParagraphNo($order = Criteria::ASC) Order by the paragraph_no column
 * @method PDCommentArchiveQuery orderByNotePos($order = Criteria::ASC) Order by the note_pos column
 * @method PDCommentArchiveQuery orderByNoteNeg($order = Criteria::ASC) Order by the note_neg column
 * @method PDCommentArchiveQuery orderByPublishedAt($order = Criteria::ASC) Order by the published_at column
 * @method PDCommentArchiveQuery orderByPublishedBy($order = Criteria::ASC) Order by the published_by column
 * @method PDCommentArchiveQuery orderByOnline($order = Criteria::ASC) Order by the online column
 * @method PDCommentArchiveQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PDCommentArchiveQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PDCommentArchiveQuery orderByArchivedAt($order = Criteria::ASC) Order by the archived_at column
 *
 * @method PDCommentArchiveQuery groupById() Group by the id column
 * @method PDCommentArchiveQuery groupByPUserId() Group by the p_user_id column
 * @method PDCommentArchiveQuery groupByPDocumentId() Group by the p_document_id column
 * @method PDCommentArchiveQuery groupByDescription() Group by the description column
 * @method PDCommentArchiveQuery groupByParagraphNo() Group by the paragraph_no column
 * @method PDCommentArchiveQuery groupByNotePos() Group by the note_pos column
 * @method PDCommentArchiveQuery groupByNoteNeg() Group by the note_neg column
 * @method PDCommentArchiveQuery groupByPublishedAt() Group by the published_at column
 * @method PDCommentArchiveQuery groupByPublishedBy() Group by the published_by column
 * @method PDCommentArchiveQuery groupByOnline() Group by the online column
 * @method PDCommentArchiveQuery groupByCreatedAt() Group by the created_at column
 * @method PDCommentArchiveQuery groupByUpdatedAt() Group by the updated_at column
 * @method PDCommentArchiveQuery groupByArchivedAt() Group by the archived_at column
 *
 * @method PDCommentArchiveQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PDCommentArchiveQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PDCommentArchiveQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PDCommentArchive findOne(PropelPDO $con = null) Return the first PDCommentArchive matching the query
 * @method PDCommentArchive findOneOrCreate(PropelPDO $con = null) Return the first PDCommentArchive matching the query, or a new PDCommentArchive object populated from the query conditions when no match is found
 *
 * @method PDCommentArchive findOneByPUserId(int $p_user_id) Return the first PDCommentArchive filtered by the p_user_id column
 * @method PDCommentArchive findOneByPDocumentId(int $p_document_id) Return the first PDCommentArchive filtered by the p_document_id column
 * @method PDCommentArchive findOneByDescription(string $description) Return the first PDCommentArchive filtered by the description column
 * @method PDCommentArchive findOneByParagraphNo(int $paragraph_no) Return the first PDCommentArchive filtered by the paragraph_no column
 * @method PDCommentArchive findOneByNotePos(int $note_pos) Return the first PDCommentArchive filtered by the note_pos column
 * @method PDCommentArchive findOneByNoteNeg(int $note_neg) Return the first PDCommentArchive filtered by the note_neg column
 * @method PDCommentArchive findOneByPublishedAt(string $published_at) Return the first PDCommentArchive filtered by the published_at column
 * @method PDCommentArchive findOneByPublishedBy(string $published_by) Return the first PDCommentArchive filtered by the published_by column
 * @method PDCommentArchive findOneByOnline(boolean $online) Return the first PDCommentArchive filtered by the online column
 * @method PDCommentArchive findOneByCreatedAt(string $created_at) Return the first PDCommentArchive filtered by the created_at column
 * @method PDCommentArchive findOneByUpdatedAt(string $updated_at) Return the first PDCommentArchive filtered by the updated_at column
 * @method PDCommentArchive findOneByArchivedAt(string $archived_at) Return the first PDCommentArchive filtered by the archived_at column
 *
 * @method array findById(int $id) Return PDCommentArchive objects filtered by the id column
 * @method array findByPUserId(int $p_user_id) Return PDCommentArchive objects filtered by the p_user_id column
 * @method array findByPDocumentId(int $p_document_id) Return PDCommentArchive objects filtered by the p_document_id column
 * @method array findByDescription(string $description) Return PDCommentArchive objects filtered by the description column
 * @method array findByParagraphNo(int $paragraph_no) Return PDCommentArchive objects filtered by the paragraph_no column
 * @method array findByNotePos(int $note_pos) Return PDCommentArchive objects filtered by the note_pos column
 * @method array findByNoteNeg(int $note_neg) Return PDCommentArchive objects filtered by the note_neg column
 * @method array findByPublishedAt(string $published_at) Return PDCommentArchive objects filtered by the published_at column
 * @method array findByPublishedBy(string $published_by) Return PDCommentArchive objects filtered by the published_by column
 * @method array findByOnline(boolean $online) Return PDCommentArchive objects filtered by the online column
 * @method array findByCreatedAt(string $created_at) Return PDCommentArchive objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PDCommentArchive objects filtered by the updated_at column
 * @method array findByArchivedAt(string $archived_at) Return PDCommentArchive objects filtered by the archived_at column
 */
abstract class BasePDCommentArchiveQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePDCommentArchiveQuery object.
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
            $modelName = 'Politizr\\Model\\PDCommentArchive';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PDCommentArchiveQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PDCommentArchiveQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PDCommentArchiveQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PDCommentArchiveQuery) {
            return $criteria;
        }
        $query = new PDCommentArchiveQuery(null, null, $modelAlias);

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
     * @return   PDCommentArchive|PDCommentArchive[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PDCommentArchivePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PDCommentArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PDCommentArchive A model object, or null if the key is not found
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
     * @return                 PDCommentArchive A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `p_user_id`, `p_document_id`, `description`, `paragraph_no`, `note_pos`, `note_neg`, `published_at`, `published_by`, `online`, `created_at`, `updated_at`, `archived_at` FROM `p_d_comment_archive` WHERE `id` = :p0';
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
            $obj = new PDCommentArchive();
            $obj->hydrate($row);
            PDCommentArchivePeer::addInstanceToPool($obj, (string) $key);
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
     * @return PDCommentArchive|PDCommentArchive[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PDCommentArchive[]|mixed the list of results, formatted by the current formatter
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
     * @return PDCommentArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PDCommentArchivePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PDCommentArchiveQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PDCommentArchivePeer::ID, $keys, Criteria::IN);
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
     * @return PDCommentArchiveQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PDCommentArchivePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PDCommentArchivePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDCommentArchivePeer::ID, $id, $comparison);
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
     * @return PDCommentArchiveQuery The current query, for fluid interface
     */
    public function filterByPUserId($pUserId = null, $comparison = null)
    {
        if (is_array($pUserId)) {
            $useMinMax = false;
            if (isset($pUserId['min'])) {
                $this->addUsingAlias(PDCommentArchivePeer::P_USER_ID, $pUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserId['max'])) {
                $this->addUsingAlias(PDCommentArchivePeer::P_USER_ID, $pUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDCommentArchivePeer::P_USER_ID, $pUserId, $comparison);
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
     * @return PDCommentArchiveQuery The current query, for fluid interface
     */
    public function filterByPDocumentId($pDocumentId = null, $comparison = null)
    {
        if (is_array($pDocumentId)) {
            $useMinMax = false;
            if (isset($pDocumentId['min'])) {
                $this->addUsingAlias(PDCommentArchivePeer::P_DOCUMENT_ID, $pDocumentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pDocumentId['max'])) {
                $this->addUsingAlias(PDCommentArchivePeer::P_DOCUMENT_ID, $pDocumentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDCommentArchivePeer::P_DOCUMENT_ID, $pDocumentId, $comparison);
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
     * @return PDCommentArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PDCommentArchivePeer::DESCRIPTION, $description, $comparison);
    }

    /**
     * Filter the query on the paragraph_no column
     *
     * Example usage:
     * <code>
     * $query->filterByParagraphNo(1234); // WHERE paragraph_no = 1234
     * $query->filterByParagraphNo(array(12, 34)); // WHERE paragraph_no IN (12, 34)
     * $query->filterByParagraphNo(array('min' => 12)); // WHERE paragraph_no >= 12
     * $query->filterByParagraphNo(array('max' => 12)); // WHERE paragraph_no <= 12
     * </code>
     *
     * @param     mixed $paragraphNo The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDCommentArchiveQuery The current query, for fluid interface
     */
    public function filterByParagraphNo($paragraphNo = null, $comparison = null)
    {
        if (is_array($paragraphNo)) {
            $useMinMax = false;
            if (isset($paragraphNo['min'])) {
                $this->addUsingAlias(PDCommentArchivePeer::PARAGRAPH_NO, $paragraphNo['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paragraphNo['max'])) {
                $this->addUsingAlias(PDCommentArchivePeer::PARAGRAPH_NO, $paragraphNo['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDCommentArchivePeer::PARAGRAPH_NO, $paragraphNo, $comparison);
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
     * @return PDCommentArchiveQuery The current query, for fluid interface
     */
    public function filterByNotePos($notePos = null, $comparison = null)
    {
        if (is_array($notePos)) {
            $useMinMax = false;
            if (isset($notePos['min'])) {
                $this->addUsingAlias(PDCommentArchivePeer::NOTE_POS, $notePos['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($notePos['max'])) {
                $this->addUsingAlias(PDCommentArchivePeer::NOTE_POS, $notePos['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDCommentArchivePeer::NOTE_POS, $notePos, $comparison);
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
     * @return PDCommentArchiveQuery The current query, for fluid interface
     */
    public function filterByNoteNeg($noteNeg = null, $comparison = null)
    {
        if (is_array($noteNeg)) {
            $useMinMax = false;
            if (isset($noteNeg['min'])) {
                $this->addUsingAlias(PDCommentArchivePeer::NOTE_NEG, $noteNeg['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($noteNeg['max'])) {
                $this->addUsingAlias(PDCommentArchivePeer::NOTE_NEG, $noteNeg['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDCommentArchivePeer::NOTE_NEG, $noteNeg, $comparison);
    }

    /**
     * Filter the query on the published_at column
     *
     * Example usage:
     * <code>
     * $query->filterByPublishedAt('2011-03-14'); // WHERE published_at = '2011-03-14'
     * $query->filterByPublishedAt('now'); // WHERE published_at = '2011-03-14'
     * $query->filterByPublishedAt(array('max' => 'yesterday')); // WHERE published_at < '2011-03-13'
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
     * @return PDCommentArchiveQuery The current query, for fluid interface
     */
    public function filterByPublishedAt($publishedAt = null, $comparison = null)
    {
        if (is_array($publishedAt)) {
            $useMinMax = false;
            if (isset($publishedAt['min'])) {
                $this->addUsingAlias(PDCommentArchivePeer::PUBLISHED_AT, $publishedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publishedAt['max'])) {
                $this->addUsingAlias(PDCommentArchivePeer::PUBLISHED_AT, $publishedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDCommentArchivePeer::PUBLISHED_AT, $publishedAt, $comparison);
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
     * @return PDCommentArchiveQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PDCommentArchivePeer::PUBLISHED_BY, $publishedBy, $comparison);
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
     * @return PDCommentArchiveQuery The current query, for fluid interface
     */
    public function filterByOnline($online = null, $comparison = null)
    {
        if (is_string($online)) {
            $online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PDCommentArchivePeer::ONLINE, $online, $comparison);
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
     * @return PDCommentArchiveQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PDCommentArchivePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PDCommentArchivePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDCommentArchivePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PDCommentArchiveQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PDCommentArchivePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PDCommentArchivePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDCommentArchivePeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return PDCommentArchiveQuery The current query, for fluid interface
     */
    public function filterByArchivedAt($archivedAt = null, $comparison = null)
    {
        if (is_array($archivedAt)) {
            $useMinMax = false;
            if (isset($archivedAt['min'])) {
                $this->addUsingAlias(PDCommentArchivePeer::ARCHIVED_AT, $archivedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($archivedAt['max'])) {
                $this->addUsingAlias(PDCommentArchivePeer::ARCHIVED_AT, $archivedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDCommentArchivePeer::ARCHIVED_AT, $archivedAt, $comparison);
    }

    /**
     * Exclude object from result
     *
     * @param   PDCommentArchive $pDCommentArchive Object to remove from the list of results
     *
     * @return PDCommentArchiveQuery The current query, for fluid interface
     */
    public function prune($pDCommentArchive = null)
    {
        if ($pDCommentArchive) {
            $this->addUsingAlias(PDCommentArchivePeer::ID, $pDCommentArchive->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

}
