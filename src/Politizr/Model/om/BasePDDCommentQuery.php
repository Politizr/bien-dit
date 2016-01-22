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
use Politizr\Model\PDDComment;
use Politizr\Model\PDDCommentPeer;
use Politizr\Model\PDDCommentQuery;
use Politizr\Model\PDDebate;
use Politizr\Model\PMDCommentHistoric;
use Politizr\Model\PUser;

/**
 * @method PDDCommentQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PDDCommentQuery orderByUuid($order = Criteria::ASC) Order by the uuid column
 * @method PDDCommentQuery orderByPUserId($order = Criteria::ASC) Order by the p_user_id column
 * @method PDDCommentQuery orderByPDDebateId($order = Criteria::ASC) Order by the p_d_debate_id column
 * @method PDDCommentQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method PDDCommentQuery orderByParagraphNo($order = Criteria::ASC) Order by the paragraph_no column
 * @method PDDCommentQuery orderByNotePos($order = Criteria::ASC) Order by the note_pos column
 * @method PDDCommentQuery orderByNoteNeg($order = Criteria::ASC) Order by the note_neg column
 * @method PDDCommentQuery orderByPublishedAt($order = Criteria::ASC) Order by the published_at column
 * @method PDDCommentQuery orderByPublishedBy($order = Criteria::ASC) Order by the published_by column
 * @method PDDCommentQuery orderByOnline($order = Criteria::ASC) Order by the online column
 * @method PDDCommentQuery orderByModerated($order = Criteria::ASC) Order by the moderated column
 * @method PDDCommentQuery orderByModeratedPartial($order = Criteria::ASC) Order by the moderated_partial column
 * @method PDDCommentQuery orderByModeratedAt($order = Criteria::ASC) Order by the moderated_at column
 * @method PDDCommentQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PDDCommentQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method PDDCommentQuery groupById() Group by the id column
 * @method PDDCommentQuery groupByUuid() Group by the uuid column
 * @method PDDCommentQuery groupByPUserId() Group by the p_user_id column
 * @method PDDCommentQuery groupByPDDebateId() Group by the p_d_debate_id column
 * @method PDDCommentQuery groupByDescription() Group by the description column
 * @method PDDCommentQuery groupByParagraphNo() Group by the paragraph_no column
 * @method PDDCommentQuery groupByNotePos() Group by the note_pos column
 * @method PDDCommentQuery groupByNoteNeg() Group by the note_neg column
 * @method PDDCommentQuery groupByPublishedAt() Group by the published_at column
 * @method PDDCommentQuery groupByPublishedBy() Group by the published_by column
 * @method PDDCommentQuery groupByOnline() Group by the online column
 * @method PDDCommentQuery groupByModerated() Group by the moderated column
 * @method PDDCommentQuery groupByModeratedPartial() Group by the moderated_partial column
 * @method PDDCommentQuery groupByModeratedAt() Group by the moderated_at column
 * @method PDDCommentQuery groupByCreatedAt() Group by the created_at column
 * @method PDDCommentQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method PDDCommentQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PDDCommentQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PDDCommentQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PDDCommentQuery leftJoinPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUser relation
 * @method PDDCommentQuery rightJoinPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUser relation
 * @method PDDCommentQuery innerJoinPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PUser relation
 *
 * @method PDDCommentQuery leftJoinPDDebate($relationAlias = null) Adds a LEFT JOIN clause to the query using the PDDebate relation
 * @method PDDCommentQuery rightJoinPDDebate($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PDDebate relation
 * @method PDDCommentQuery innerJoinPDDebate($relationAlias = null) Adds a INNER JOIN clause to the query using the PDDebate relation
 *
 * @method PDDCommentQuery leftJoinPMDCommentHistoric($relationAlias = null) Adds a LEFT JOIN clause to the query using the PMDCommentHistoric relation
 * @method PDDCommentQuery rightJoinPMDCommentHistoric($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PMDCommentHistoric relation
 * @method PDDCommentQuery innerJoinPMDCommentHistoric($relationAlias = null) Adds a INNER JOIN clause to the query using the PMDCommentHistoric relation
 *
 * @method PDDComment findOne(PropelPDO $con = null) Return the first PDDComment matching the query
 * @method PDDComment findOneOrCreate(PropelPDO $con = null) Return the first PDDComment matching the query, or a new PDDComment object populated from the query conditions when no match is found
 *
 * @method PDDComment findOneByUuid(string $uuid) Return the first PDDComment filtered by the uuid column
 * @method PDDComment findOneByPUserId(int $p_user_id) Return the first PDDComment filtered by the p_user_id column
 * @method PDDComment findOneByPDDebateId(int $p_d_debate_id) Return the first PDDComment filtered by the p_d_debate_id column
 * @method PDDComment findOneByDescription(string $description) Return the first PDDComment filtered by the description column
 * @method PDDComment findOneByParagraphNo(int $paragraph_no) Return the first PDDComment filtered by the paragraph_no column
 * @method PDDComment findOneByNotePos(int $note_pos) Return the first PDDComment filtered by the note_pos column
 * @method PDDComment findOneByNoteNeg(int $note_neg) Return the first PDDComment filtered by the note_neg column
 * @method PDDComment findOneByPublishedAt(string $published_at) Return the first PDDComment filtered by the published_at column
 * @method PDDComment findOneByPublishedBy(string $published_by) Return the first PDDComment filtered by the published_by column
 * @method PDDComment findOneByOnline(boolean $online) Return the first PDDComment filtered by the online column
 * @method PDDComment findOneByModerated(boolean $moderated) Return the first PDDComment filtered by the moderated column
 * @method PDDComment findOneByModeratedPartial(boolean $moderated_partial) Return the first PDDComment filtered by the moderated_partial column
 * @method PDDComment findOneByModeratedAt(string $moderated_at) Return the first PDDComment filtered by the moderated_at column
 * @method PDDComment findOneByCreatedAt(string $created_at) Return the first PDDComment filtered by the created_at column
 * @method PDDComment findOneByUpdatedAt(string $updated_at) Return the first PDDComment filtered by the updated_at column
 *
 * @method array findById(int $id) Return PDDComment objects filtered by the id column
 * @method array findByUuid(string $uuid) Return PDDComment objects filtered by the uuid column
 * @method array findByPUserId(int $p_user_id) Return PDDComment objects filtered by the p_user_id column
 * @method array findByPDDebateId(int $p_d_debate_id) Return PDDComment objects filtered by the p_d_debate_id column
 * @method array findByDescription(string $description) Return PDDComment objects filtered by the description column
 * @method array findByParagraphNo(int $paragraph_no) Return PDDComment objects filtered by the paragraph_no column
 * @method array findByNotePos(int $note_pos) Return PDDComment objects filtered by the note_pos column
 * @method array findByNoteNeg(int $note_neg) Return PDDComment objects filtered by the note_neg column
 * @method array findByPublishedAt(string $published_at) Return PDDComment objects filtered by the published_at column
 * @method array findByPublishedBy(string $published_by) Return PDDComment objects filtered by the published_by column
 * @method array findByOnline(boolean $online) Return PDDComment objects filtered by the online column
 * @method array findByModerated(boolean $moderated) Return PDDComment objects filtered by the moderated column
 * @method array findByModeratedPartial(boolean $moderated_partial) Return PDDComment objects filtered by the moderated_partial column
 * @method array findByModeratedAt(string $moderated_at) Return PDDComment objects filtered by the moderated_at column
 * @method array findByCreatedAt(string $created_at) Return PDDComment objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PDDComment objects filtered by the updated_at column
 */
abstract class BasePDDCommentQuery extends ModelCriteria
{
    // query_cache behavior
    protected $queryKey = '';

    // archivable behavior
    protected $archiveOnDelete = true;

    /**
     * Initializes internal state of BasePDDCommentQuery object.
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
            $modelName = 'Politizr\\Model\\PDDComment';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PDDCommentQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PDDCommentQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PDDCommentQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PDDCommentQuery) {
            return $criteria;
        }
        $query = new PDDCommentQuery(null, null, $modelAlias);

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
     * @return   PDDComment|PDDComment[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PDDCommentPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PDDCommentPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PDDComment A model object, or null if the key is not found
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
     * @return                 PDDComment A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `uuid`, `p_user_id`, `p_d_debate_id`, `description`, `paragraph_no`, `note_pos`, `note_neg`, `published_at`, `published_by`, `online`, `moderated`, `moderated_partial`, `moderated_at`, `created_at`, `updated_at` FROM `p_d_d_comment` WHERE `id` = :p0';
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
            $obj = new PDDComment();
            $obj->hydrate($row);
            PDDCommentPeer::addInstanceToPool($obj, (string) $key);
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
     * @return PDDComment|PDDComment[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PDDComment[]|mixed the list of results, formatted by the current formatter
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
     * @return PDDCommentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PDDCommentPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PDDCommentQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PDDCommentPeer::ID, $keys, Criteria::IN);
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
     * @return PDDCommentQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PDDCommentPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PDDCommentPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDCommentPeer::ID, $id, $comparison);
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
     * @return PDDCommentQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PDDCommentPeer::UUID, $uuid, $comparison);
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
     * @see       filterByPUser()
     *
     * @param     mixed $pUserId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDDCommentQuery The current query, for fluid interface
     */
    public function filterByPUserId($pUserId = null, $comparison = null)
    {
        if (is_array($pUserId)) {
            $useMinMax = false;
            if (isset($pUserId['min'])) {
                $this->addUsingAlias(PDDCommentPeer::P_USER_ID, $pUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserId['max'])) {
                $this->addUsingAlias(PDDCommentPeer::P_USER_ID, $pUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDCommentPeer::P_USER_ID, $pUserId, $comparison);
    }

    /**
     * Filter the query on the p_d_debate_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPDDebateId(1234); // WHERE p_d_debate_id = 1234
     * $query->filterByPDDebateId(array(12, 34)); // WHERE p_d_debate_id IN (12, 34)
     * $query->filterByPDDebateId(array('min' => 12)); // WHERE p_d_debate_id >= 12
     * $query->filterByPDDebateId(array('max' => 12)); // WHERE p_d_debate_id <= 12
     * </code>
     *
     * @see       filterByPDDebate()
     *
     * @param     mixed $pDDebateId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDDCommentQuery The current query, for fluid interface
     */
    public function filterByPDDebateId($pDDebateId = null, $comparison = null)
    {
        if (is_array($pDDebateId)) {
            $useMinMax = false;
            if (isset($pDDebateId['min'])) {
                $this->addUsingAlias(PDDCommentPeer::P_D_DEBATE_ID, $pDDebateId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pDDebateId['max'])) {
                $this->addUsingAlias(PDDCommentPeer::P_D_DEBATE_ID, $pDDebateId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDCommentPeer::P_D_DEBATE_ID, $pDDebateId, $comparison);
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
     * @return PDDCommentQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PDDCommentPeer::DESCRIPTION, $description, $comparison);
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
     * @return PDDCommentQuery The current query, for fluid interface
     */
    public function filterByParagraphNo($paragraphNo = null, $comparison = null)
    {
        if (is_array($paragraphNo)) {
            $useMinMax = false;
            if (isset($paragraphNo['min'])) {
                $this->addUsingAlias(PDDCommentPeer::PARAGRAPH_NO, $paragraphNo['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($paragraphNo['max'])) {
                $this->addUsingAlias(PDDCommentPeer::PARAGRAPH_NO, $paragraphNo['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDCommentPeer::PARAGRAPH_NO, $paragraphNo, $comparison);
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
     * @return PDDCommentQuery The current query, for fluid interface
     */
    public function filterByNotePos($notePos = null, $comparison = null)
    {
        if (is_array($notePos)) {
            $useMinMax = false;
            if (isset($notePos['min'])) {
                $this->addUsingAlias(PDDCommentPeer::NOTE_POS, $notePos['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($notePos['max'])) {
                $this->addUsingAlias(PDDCommentPeer::NOTE_POS, $notePos['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDCommentPeer::NOTE_POS, $notePos, $comparison);
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
     * @return PDDCommentQuery The current query, for fluid interface
     */
    public function filterByNoteNeg($noteNeg = null, $comparison = null)
    {
        if (is_array($noteNeg)) {
            $useMinMax = false;
            if (isset($noteNeg['min'])) {
                $this->addUsingAlias(PDDCommentPeer::NOTE_NEG, $noteNeg['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($noteNeg['max'])) {
                $this->addUsingAlias(PDDCommentPeer::NOTE_NEG, $noteNeg['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDCommentPeer::NOTE_NEG, $noteNeg, $comparison);
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
     * @return PDDCommentQuery The current query, for fluid interface
     */
    public function filterByPublishedAt($publishedAt = null, $comparison = null)
    {
        if (is_array($publishedAt)) {
            $useMinMax = false;
            if (isset($publishedAt['min'])) {
                $this->addUsingAlias(PDDCommentPeer::PUBLISHED_AT, $publishedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publishedAt['max'])) {
                $this->addUsingAlias(PDDCommentPeer::PUBLISHED_AT, $publishedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDCommentPeer::PUBLISHED_AT, $publishedAt, $comparison);
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
     * @return PDDCommentQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PDDCommentPeer::PUBLISHED_BY, $publishedBy, $comparison);
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
     * @return PDDCommentQuery The current query, for fluid interface
     */
    public function filterByOnline($online = null, $comparison = null)
    {
        if (is_string($online)) {
            $online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PDDCommentPeer::ONLINE, $online, $comparison);
    }

    /**
     * Filter the query on the moderated column
     *
     * Example usage:
     * <code>
     * $query->filterByModerated(true); // WHERE moderated = true
     * $query->filterByModerated('yes'); // WHERE moderated = true
     * </code>
     *
     * @param     boolean|string $moderated The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDDCommentQuery The current query, for fluid interface
     */
    public function filterByModerated($moderated = null, $comparison = null)
    {
        if (is_string($moderated)) {
            $moderated = in_array(strtolower($moderated), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PDDCommentPeer::MODERATED, $moderated, $comparison);
    }

    /**
     * Filter the query on the moderated_partial column
     *
     * Example usage:
     * <code>
     * $query->filterByModeratedPartial(true); // WHERE moderated_partial = true
     * $query->filterByModeratedPartial('yes'); // WHERE moderated_partial = true
     * </code>
     *
     * @param     boolean|string $moderatedPartial The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDDCommentQuery The current query, for fluid interface
     */
    public function filterByModeratedPartial($moderatedPartial = null, $comparison = null)
    {
        if (is_string($moderatedPartial)) {
            $moderatedPartial = in_array(strtolower($moderatedPartial), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PDDCommentPeer::MODERATED_PARTIAL, $moderatedPartial, $comparison);
    }

    /**
     * Filter the query on the moderated_at column
     *
     * Example usage:
     * <code>
     * $query->filterByModeratedAt('2011-03-14'); // WHERE moderated_at = '2011-03-14'
     * $query->filterByModeratedAt('now'); // WHERE moderated_at = '2011-03-14'
     * $query->filterByModeratedAt(array('max' => 'yesterday')); // WHERE moderated_at < '2011-03-13'
     * </code>
     *
     * @param     mixed $moderatedAt The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDDCommentQuery The current query, for fluid interface
     */
    public function filterByModeratedAt($moderatedAt = null, $comparison = null)
    {
        if (is_array($moderatedAt)) {
            $useMinMax = false;
            if (isset($moderatedAt['min'])) {
                $this->addUsingAlias(PDDCommentPeer::MODERATED_AT, $moderatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($moderatedAt['max'])) {
                $this->addUsingAlias(PDDCommentPeer::MODERATED_AT, $moderatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDCommentPeer::MODERATED_AT, $moderatedAt, $comparison);
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
     * @return PDDCommentQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PDDCommentPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PDDCommentPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDCommentPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PDDCommentQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PDDCommentPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PDDCommentPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDCommentPeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related PUser object
     *
     * @param   PUser|PropelObjectCollection $pUser The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDDCommentQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUser($pUser, $comparison = null)
    {
        if ($pUser instanceof PUser) {
            return $this
                ->addUsingAlias(PDDCommentPeer::P_USER_ID, $pUser->getId(), $comparison);
        } elseif ($pUser instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PDDCommentPeer::P_USER_ID, $pUser->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPUser() only accepts arguments of type PUser or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDDCommentQuery The current query, for fluid interface
     */
    public function joinPUser($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUser');

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
            $this->addJoinObject($join, 'PUser');
        }

        return $this;
    }

    /**
     * Use the PUser relation PUser object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUserQuery A secondary query class using the current class as primary query
     */
    public function usePUserQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUser', '\Politizr\Model\PUserQuery');
    }

    /**
     * Filter the query by a related PDDebate object
     *
     * @param   PDDebate|PropelObjectCollection $pDDebate The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDDCommentQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPDDebate($pDDebate, $comparison = null)
    {
        if ($pDDebate instanceof PDDebate) {
            return $this
                ->addUsingAlias(PDDCommentPeer::P_D_DEBATE_ID, $pDDebate->getId(), $comparison);
        } elseif ($pDDebate instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PDDCommentPeer::P_D_DEBATE_ID, $pDDebate->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPDDebate() only accepts arguments of type PDDebate or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PDDebate relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDDCommentQuery The current query, for fluid interface
     */
    public function joinPDDebate($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PDDebate');

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
            $this->addJoinObject($join, 'PDDebate');
        }

        return $this;
    }

    /**
     * Use the PDDebate relation PDDebate object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PDDebateQuery A secondary query class using the current class as primary query
     */
    public function usePDDebateQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPDDebate($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PDDebate', '\Politizr\Model\PDDebateQuery');
    }

    /**
     * Filter the query by a related PMDCommentHistoric object
     *
     * @param   PMDCommentHistoric|PropelObjectCollection $pMDCommentHistoric  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDDCommentQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPMDCommentHistoric($pMDCommentHistoric, $comparison = null)
    {
        if ($pMDCommentHistoric instanceof PMDCommentHistoric) {
            return $this
                ->addUsingAlias(PDDCommentPeer::ID, $pMDCommentHistoric->getPDDCommentId(), $comparison);
        } elseif ($pMDCommentHistoric instanceof PropelObjectCollection) {
            return $this
                ->usePMDCommentHistoricQuery()
                ->filterByPrimaryKeys($pMDCommentHistoric->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPMDCommentHistoric() only accepts arguments of type PMDCommentHistoric or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PMDCommentHistoric relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDDCommentQuery The current query, for fluid interface
     */
    public function joinPMDCommentHistoric($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PMDCommentHistoric');

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
            $this->addJoinObject($join, 'PMDCommentHistoric');
        }

        return $this;
    }

    /**
     * Use the PMDCommentHistoric relation PMDCommentHistoric object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PMDCommentHistoricQuery A secondary query class using the current class as primary query
     */
    public function usePMDCommentHistoricQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPMDCommentHistoric($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PMDCommentHistoric', '\Politizr\Model\PMDCommentHistoricQuery');
    }

    /**
     * Exclude object from result
     *
     * @param   PDDComment $pDDComment Object to remove from the list of results
     *
     * @return PDDCommentQuery The current query, for fluid interface
     */
    public function prune($pDDComment = null)
    {
        if ($pDDComment) {
            $this->addUsingAlias(PDDCommentPeer::ID, $pDDComment->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    /**
     * Code to execute before every DELETE statement
     *
     * @param     PropelPDO $con The connection object used by the query
     */
    protected function basePreDelete(PropelPDO $con)
    {
        // archivable behavior

        if ($this->archiveOnDelete) {
            $this->archive($con);
        } else {
            $this->archiveOnDelete = true;
        }


        return $this->preDelete($con);
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     PDDCommentQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PDDCommentPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PDDCommentQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PDDCommentPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PDDCommentQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PDDCommentPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PDDCommentQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PDDCommentPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PDDCommentQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PDDCommentPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PDDCommentQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PDDCommentPeer::CREATED_AT);
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

        $dbMap = Propel::getDatabaseMap(PDDCommentPeer::DATABASE_NAME);
        $db = Propel::getDB(PDDCommentPeer::DATABASE_NAME);

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

    // archivable behavior

    /**
     * Copy the data of the objects satisfying the query into PDDCommentArchive archive objects.
     * The archived objects are then saved.
     * If any of the objects has already been archived, the archived object
     * is updated and not duplicated.
     * Warning: This termination methods issues 2n+1 queries.
     *
     * @param      PropelPDO $con	Connection to use.
     * @param      Boolean $useLittleMemory	Whether or not to use PropelOnDemandFormatter to retrieve objects.
     *               Set to false if the identity map matters.
     *               Set to true (default) to use less memory.
     *
     * @return     int the number of archived objects
     * @throws     PropelException
     */
    public function archive($con = null, $useLittleMemory = true)
    {
        $totalArchivedObjects = 0;
        $criteria = clone $this;
        // prepare the query
        $criteria->setWith(array());
        if ($useLittleMemory) {
            $criteria->setFormatter(ModelCriteria::FORMAT_ON_DEMAND);
        }
        if ($con === null) {
            $con = Propel::getConnection(PDDCommentPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $con->beginTransaction();
        try {
            // archive all results one by one
            foreach ($criteria->find($con) as $object) {
                $object->archive($con);
                $totalArchivedObjects++;
            }
            $con->commit();
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }

        return $totalArchivedObjects;
    }

    /**
     * Enable/disable auto-archiving on delete for the next query.
     *
     * @param boolean $archiveOnDelete True if the query must archive deleted objects, false otherwise.
     */
    public function setArchiveOnDelete($archiveOnDelete)
    {
        $this->archiveOnDelete = $archiveOnDelete;
    }

    /**
     * Delete records matching the current query without archiving them.
     *
     * @param      PropelPDO $con	Connection to use.
     *
     * @return integer the number of deleted rows
     */
    public function deleteWithoutArchive($con = null)
    {
        $this->archiveOnDelete = false;

        return $this->delete($con);
    }

    /**
     * Delete all records without archiving them.
     *
     * @param      PropelPDO $con	Connection to use.
     *
     * @return integer the number of deleted rows
     */
    public function deleteAllWithoutArchive($con = null)
    {
        $this->archiveOnDelete = false;

        return $this->deleteAll($con);
    }

}
