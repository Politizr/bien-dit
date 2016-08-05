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
use Politizr\Model\PDDTaggedT;
use Politizr\Model\PDDebate;
use Politizr\Model\PDDebatePeer;
use Politizr\Model\PDDebateQuery;
use Politizr\Model\PDReaction;
use Politizr\Model\PMDebateHistoric;
use Politizr\Model\PTag;
use Politizr\Model\PUBookmarkDD;
use Politizr\Model\PUFollowDD;
use Politizr\Model\PUTrackDD;
use Politizr\Model\PUser;

/**
 * @method PDDebateQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PDDebateQuery orderByUuid($order = Criteria::ASC) Order by the uuid column
 * @method PDDebateQuery orderByPUserId($order = Criteria::ASC) Order by the p_user_id column
 * @method PDDebateQuery orderByTitle($order = Criteria::ASC) Order by the title column
 * @method PDDebateQuery orderByFileName($order = Criteria::ASC) Order by the file_name column
 * @method PDDebateQuery orderByCopyright($order = Criteria::ASC) Order by the copyright column
 * @method PDDebateQuery orderByDescription($order = Criteria::ASC) Order by the description column
 * @method PDDebateQuery orderByNotePos($order = Criteria::ASC) Order by the note_pos column
 * @method PDDebateQuery orderByNoteNeg($order = Criteria::ASC) Order by the note_neg column
 * @method PDDebateQuery orderByNbViews($order = Criteria::ASC) Order by the nb_views column
 * @method PDDebateQuery orderByPublished($order = Criteria::ASC) Order by the published column
 * @method PDDebateQuery orderByPublishedAt($order = Criteria::ASC) Order by the published_at column
 * @method PDDebateQuery orderByPublishedBy($order = Criteria::ASC) Order by the published_by column
 * @method PDDebateQuery orderByFavorite($order = Criteria::ASC) Order by the favorite column
 * @method PDDebateQuery orderByOnline($order = Criteria::ASC) Order by the online column
 * @method PDDebateQuery orderByHomepage($order = Criteria::ASC) Order by the homepage column
 * @method PDDebateQuery orderByModerated($order = Criteria::ASC) Order by the moderated column
 * @method PDDebateQuery orderByModeratedPartial($order = Criteria::ASC) Order by the moderated_partial column
 * @method PDDebateQuery orderByModeratedAt($order = Criteria::ASC) Order by the moderated_at column
 * @method PDDebateQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PDDebateQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PDDebateQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 *
 * @method PDDebateQuery groupById() Group by the id column
 * @method PDDebateQuery groupByUuid() Group by the uuid column
 * @method PDDebateQuery groupByPUserId() Group by the p_user_id column
 * @method PDDebateQuery groupByTitle() Group by the title column
 * @method PDDebateQuery groupByFileName() Group by the file_name column
 * @method PDDebateQuery groupByCopyright() Group by the copyright column
 * @method PDDebateQuery groupByDescription() Group by the description column
 * @method PDDebateQuery groupByNotePos() Group by the note_pos column
 * @method PDDebateQuery groupByNoteNeg() Group by the note_neg column
 * @method PDDebateQuery groupByNbViews() Group by the nb_views column
 * @method PDDebateQuery groupByPublished() Group by the published column
 * @method PDDebateQuery groupByPublishedAt() Group by the published_at column
 * @method PDDebateQuery groupByPublishedBy() Group by the published_by column
 * @method PDDebateQuery groupByFavorite() Group by the favorite column
 * @method PDDebateQuery groupByOnline() Group by the online column
 * @method PDDebateQuery groupByHomepage() Group by the homepage column
 * @method PDDebateQuery groupByModerated() Group by the moderated column
 * @method PDDebateQuery groupByModeratedPartial() Group by the moderated_partial column
 * @method PDDebateQuery groupByModeratedAt() Group by the moderated_at column
 * @method PDDebateQuery groupByCreatedAt() Group by the created_at column
 * @method PDDebateQuery groupByUpdatedAt() Group by the updated_at column
 * @method PDDebateQuery groupBySlug() Group by the slug column
 *
 * @method PDDebateQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PDDebateQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PDDebateQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PDDebateQuery leftJoinPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUser relation
 * @method PDDebateQuery rightJoinPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUser relation
 * @method PDDebateQuery innerJoinPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PUser relation
 *
 * @method PDDebateQuery leftJoinPuFollowDdPDDebate($relationAlias = null) Adds a LEFT JOIN clause to the query using the PuFollowDdPDDebate relation
 * @method PDDebateQuery rightJoinPuFollowDdPDDebate($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PuFollowDdPDDebate relation
 * @method PDDebateQuery innerJoinPuFollowDdPDDebate($relationAlias = null) Adds a INNER JOIN clause to the query using the PuFollowDdPDDebate relation
 *
 * @method PDDebateQuery leftJoinPuBookmarkDdPDDebate($relationAlias = null) Adds a LEFT JOIN clause to the query using the PuBookmarkDdPDDebate relation
 * @method PDDebateQuery rightJoinPuBookmarkDdPDDebate($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PuBookmarkDdPDDebate relation
 * @method PDDebateQuery innerJoinPuBookmarkDdPDDebate($relationAlias = null) Adds a INNER JOIN clause to the query using the PuBookmarkDdPDDebate relation
 *
 * @method PDDebateQuery leftJoinPuTrackDdPDDebate($relationAlias = null) Adds a LEFT JOIN clause to the query using the PuTrackDdPDDebate relation
 * @method PDDebateQuery rightJoinPuTrackDdPDDebate($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PuTrackDdPDDebate relation
 * @method PDDebateQuery innerJoinPuTrackDdPDDebate($relationAlias = null) Adds a INNER JOIN clause to the query using the PuTrackDdPDDebate relation
 *
 * @method PDDebateQuery leftJoinPDReaction($relationAlias = null) Adds a LEFT JOIN clause to the query using the PDReaction relation
 * @method PDDebateQuery rightJoinPDReaction($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PDReaction relation
 * @method PDDebateQuery innerJoinPDReaction($relationAlias = null) Adds a INNER JOIN clause to the query using the PDReaction relation
 *
 * @method PDDebateQuery leftJoinPDDComment($relationAlias = null) Adds a LEFT JOIN clause to the query using the PDDComment relation
 * @method PDDebateQuery rightJoinPDDComment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PDDComment relation
 * @method PDDebateQuery innerJoinPDDComment($relationAlias = null) Adds a INNER JOIN clause to the query using the PDDComment relation
 *
 * @method PDDebateQuery leftJoinPDDTaggedT($relationAlias = null) Adds a LEFT JOIN clause to the query using the PDDTaggedT relation
 * @method PDDebateQuery rightJoinPDDTaggedT($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PDDTaggedT relation
 * @method PDDebateQuery innerJoinPDDTaggedT($relationAlias = null) Adds a INNER JOIN clause to the query using the PDDTaggedT relation
 *
 * @method PDDebateQuery leftJoinPMDebateHistoric($relationAlias = null) Adds a LEFT JOIN clause to the query using the PMDebateHistoric relation
 * @method PDDebateQuery rightJoinPMDebateHistoric($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PMDebateHistoric relation
 * @method PDDebateQuery innerJoinPMDebateHistoric($relationAlias = null) Adds a INNER JOIN clause to the query using the PMDebateHistoric relation
 *
 * @method PDDebate findOne(PropelPDO $con = null) Return the first PDDebate matching the query
 * @method PDDebate findOneOrCreate(PropelPDO $con = null) Return the first PDDebate matching the query, or a new PDDebate object populated from the query conditions when no match is found
 *
 * @method PDDebate findOneByUuid(string $uuid) Return the first PDDebate filtered by the uuid column
 * @method PDDebate findOneByPUserId(int $p_user_id) Return the first PDDebate filtered by the p_user_id column
 * @method PDDebate findOneByTitle(string $title) Return the first PDDebate filtered by the title column
 * @method PDDebate findOneByFileName(string $file_name) Return the first PDDebate filtered by the file_name column
 * @method PDDebate findOneByCopyright(string $copyright) Return the first PDDebate filtered by the copyright column
 * @method PDDebate findOneByDescription(string $description) Return the first PDDebate filtered by the description column
 * @method PDDebate findOneByNotePos(int $note_pos) Return the first PDDebate filtered by the note_pos column
 * @method PDDebate findOneByNoteNeg(int $note_neg) Return the first PDDebate filtered by the note_neg column
 * @method PDDebate findOneByNbViews(int $nb_views) Return the first PDDebate filtered by the nb_views column
 * @method PDDebate findOneByPublished(boolean $published) Return the first PDDebate filtered by the published column
 * @method PDDebate findOneByPublishedAt(string $published_at) Return the first PDDebate filtered by the published_at column
 * @method PDDebate findOneByPublishedBy(string $published_by) Return the first PDDebate filtered by the published_by column
 * @method PDDebate findOneByFavorite(boolean $favorite) Return the first PDDebate filtered by the favorite column
 * @method PDDebate findOneByOnline(boolean $online) Return the first PDDebate filtered by the online column
 * @method PDDebate findOneByHomepage(boolean $homepage) Return the first PDDebate filtered by the homepage column
 * @method PDDebate findOneByModerated(boolean $moderated) Return the first PDDebate filtered by the moderated column
 * @method PDDebate findOneByModeratedPartial(boolean $moderated_partial) Return the first PDDebate filtered by the moderated_partial column
 * @method PDDebate findOneByModeratedAt(string $moderated_at) Return the first PDDebate filtered by the moderated_at column
 * @method PDDebate findOneByCreatedAt(string $created_at) Return the first PDDebate filtered by the created_at column
 * @method PDDebate findOneByUpdatedAt(string $updated_at) Return the first PDDebate filtered by the updated_at column
 * @method PDDebate findOneBySlug(string $slug) Return the first PDDebate filtered by the slug column
 *
 * @method array findById(int $id) Return PDDebate objects filtered by the id column
 * @method array findByUuid(string $uuid) Return PDDebate objects filtered by the uuid column
 * @method array findByPUserId(int $p_user_id) Return PDDebate objects filtered by the p_user_id column
 * @method array findByTitle(string $title) Return PDDebate objects filtered by the title column
 * @method array findByFileName(string $file_name) Return PDDebate objects filtered by the file_name column
 * @method array findByCopyright(string $copyright) Return PDDebate objects filtered by the copyright column
 * @method array findByDescription(string $description) Return PDDebate objects filtered by the description column
 * @method array findByNotePos(int $note_pos) Return PDDebate objects filtered by the note_pos column
 * @method array findByNoteNeg(int $note_neg) Return PDDebate objects filtered by the note_neg column
 * @method array findByNbViews(int $nb_views) Return PDDebate objects filtered by the nb_views column
 * @method array findByPublished(boolean $published) Return PDDebate objects filtered by the published column
 * @method array findByPublishedAt(string $published_at) Return PDDebate objects filtered by the published_at column
 * @method array findByPublishedBy(string $published_by) Return PDDebate objects filtered by the published_by column
 * @method array findByFavorite(boolean $favorite) Return PDDebate objects filtered by the favorite column
 * @method array findByOnline(boolean $online) Return PDDebate objects filtered by the online column
 * @method array findByHomepage(boolean $homepage) Return PDDebate objects filtered by the homepage column
 * @method array findByModerated(boolean $moderated) Return PDDebate objects filtered by the moderated column
 * @method array findByModeratedPartial(boolean $moderated_partial) Return PDDebate objects filtered by the moderated_partial column
 * @method array findByModeratedAt(string $moderated_at) Return PDDebate objects filtered by the moderated_at column
 * @method array findByCreatedAt(string $created_at) Return PDDebate objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PDDebate objects filtered by the updated_at column
 * @method array findBySlug(string $slug) Return PDDebate objects filtered by the slug column
 */
abstract class BasePDDebateQuery extends ModelCriteria
{
    // query_cache behavior
    protected $queryKey = '';

    // archivable behavior
    protected $archiveOnDelete = true;

    /**
     * Initializes internal state of BasePDDebateQuery object.
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
            $modelName = 'Politizr\\Model\\PDDebate';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PDDebateQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PDDebateQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PDDebateQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PDDebateQuery) {
            return $criteria;
        }
        $query = new PDDebateQuery(null, null, $modelAlias);

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
     * @return   PDDebate|PDDebate[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PDDebatePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PDDebatePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PDDebate A model object, or null if the key is not found
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
     * @return                 PDDebate A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `uuid`, `p_user_id`, `title`, `file_name`, `copyright`, `description`, `note_pos`, `note_neg`, `nb_views`, `published`, `published_at`, `published_by`, `favorite`, `online`, `homepage`, `moderated`, `moderated_partial`, `moderated_at`, `created_at`, `updated_at`, `slug` FROM `p_d_debate` WHERE `id` = :p0';
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
            $obj = new PDDebate();
            $obj->hydrate($row);
            PDDebatePeer::addInstanceToPool($obj, (string) $key);
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
     * @return PDDebate|PDDebate[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PDDebate[]|mixed the list of results, formatted by the current formatter
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
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PDDebatePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PDDebatePeer::ID, $keys, Criteria::IN);
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
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PDDebatePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PDDebatePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDebatePeer::ID, $id, $comparison);
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
     * @return PDDebateQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PDDebatePeer::UUID, $uuid, $comparison);
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
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function filterByPUserId($pUserId = null, $comparison = null)
    {
        if (is_array($pUserId)) {
            $useMinMax = false;
            if (isset($pUserId['min'])) {
                $this->addUsingAlias(PDDebatePeer::P_USER_ID, $pUserId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pUserId['max'])) {
                $this->addUsingAlias(PDDebatePeer::P_USER_ID, $pUserId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDebatePeer::P_USER_ID, $pUserId, $comparison);
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
     * @return PDDebateQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PDDebatePeer::TITLE, $title, $comparison);
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
     * @return PDDebateQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PDDebatePeer::FILE_NAME, $fileName, $comparison);
    }

    /**
     * Filter the query on the copyright column
     *
     * Example usage:
     * <code>
     * $query->filterByCopyright('fooValue');   // WHERE copyright = 'fooValue'
     * $query->filterByCopyright('%fooValue%'); // WHERE copyright LIKE '%fooValue%'
     * </code>
     *
     * @param     string $copyright The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function filterByCopyright($copyright = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($copyright)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $copyright)) {
                $copyright = str_replace('*', '%', $copyright);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PDDebatePeer::COPYRIGHT, $copyright, $comparison);
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
     * @return PDDebateQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PDDebatePeer::DESCRIPTION, $description, $comparison);
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
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function filterByNotePos($notePos = null, $comparison = null)
    {
        if (is_array($notePos)) {
            $useMinMax = false;
            if (isset($notePos['min'])) {
                $this->addUsingAlias(PDDebatePeer::NOTE_POS, $notePos['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($notePos['max'])) {
                $this->addUsingAlias(PDDebatePeer::NOTE_POS, $notePos['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDebatePeer::NOTE_POS, $notePos, $comparison);
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
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function filterByNoteNeg($noteNeg = null, $comparison = null)
    {
        if (is_array($noteNeg)) {
            $useMinMax = false;
            if (isset($noteNeg['min'])) {
                $this->addUsingAlias(PDDebatePeer::NOTE_NEG, $noteNeg['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($noteNeg['max'])) {
                $this->addUsingAlias(PDDebatePeer::NOTE_NEG, $noteNeg['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDebatePeer::NOTE_NEG, $noteNeg, $comparison);
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
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function filterByNbViews($nbViews = null, $comparison = null)
    {
        if (is_array($nbViews)) {
            $useMinMax = false;
            if (isset($nbViews['min'])) {
                $this->addUsingAlias(PDDebatePeer::NB_VIEWS, $nbViews['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nbViews['max'])) {
                $this->addUsingAlias(PDDebatePeer::NB_VIEWS, $nbViews['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDebatePeer::NB_VIEWS, $nbViews, $comparison);
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
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function filterByPublished($published = null, $comparison = null)
    {
        if (is_string($published)) {
            $published = in_array(strtolower($published), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PDDebatePeer::PUBLISHED, $published, $comparison);
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
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function filterByPublishedAt($publishedAt = null, $comparison = null)
    {
        if (is_array($publishedAt)) {
            $useMinMax = false;
            if (isset($publishedAt['min'])) {
                $this->addUsingAlias(PDDebatePeer::PUBLISHED_AT, $publishedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($publishedAt['max'])) {
                $this->addUsingAlias(PDDebatePeer::PUBLISHED_AT, $publishedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDebatePeer::PUBLISHED_AT, $publishedAt, $comparison);
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
     * @return PDDebateQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PDDebatePeer::PUBLISHED_BY, $publishedBy, $comparison);
    }

    /**
     * Filter the query on the favorite column
     *
     * Example usage:
     * <code>
     * $query->filterByFavorite(true); // WHERE favorite = true
     * $query->filterByFavorite('yes'); // WHERE favorite = true
     * </code>
     *
     * @param     boolean|string $favorite The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function filterByFavorite($favorite = null, $comparison = null)
    {
        if (is_string($favorite)) {
            $favorite = in_array(strtolower($favorite), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PDDebatePeer::FAVORITE, $favorite, $comparison);
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
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function filterByOnline($online = null, $comparison = null)
    {
        if (is_string($online)) {
            $online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PDDebatePeer::ONLINE, $online, $comparison);
    }

    /**
     * Filter the query on the homepage column
     *
     * Example usage:
     * <code>
     * $query->filterByHomepage(true); // WHERE homepage = true
     * $query->filterByHomepage('yes'); // WHERE homepage = true
     * </code>
     *
     * @param     boolean|string $homepage The value to use as filter.
     *              Non-boolean arguments are converted using the following rules:
     *                * 1, '1', 'true',  'on',  and 'yes' are converted to boolean true
     *                * 0, '0', 'false', 'off', and 'no'  are converted to boolean false
     *              Check on string values is case insensitive (so 'FaLsE' is seen as 'false').
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function filterByHomepage($homepage = null, $comparison = null)
    {
        if (is_string($homepage)) {
            $homepage = in_array(strtolower($homepage), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PDDebatePeer::HOMEPAGE, $homepage, $comparison);
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
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function filterByModerated($moderated = null, $comparison = null)
    {
        if (is_string($moderated)) {
            $moderated = in_array(strtolower($moderated), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PDDebatePeer::MODERATED, $moderated, $comparison);
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
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function filterByModeratedPartial($moderatedPartial = null, $comparison = null)
    {
        if (is_string($moderatedPartial)) {
            $moderatedPartial = in_array(strtolower($moderatedPartial), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PDDebatePeer::MODERATED_PARTIAL, $moderatedPartial, $comparison);
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
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function filterByModeratedAt($moderatedAt = null, $comparison = null)
    {
        if (is_array($moderatedAt)) {
            $useMinMax = false;
            if (isset($moderatedAt['min'])) {
                $this->addUsingAlias(PDDebatePeer::MODERATED_AT, $moderatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($moderatedAt['max'])) {
                $this->addUsingAlias(PDDebatePeer::MODERATED_AT, $moderatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDebatePeer::MODERATED_AT, $moderatedAt, $comparison);
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
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PDDebatePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PDDebatePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDebatePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PDDebatePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PDDebatePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PDDebatePeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return PDDebateQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PDDebatePeer::SLUG, $slug, $comparison);
    }

    /**
     * Filter the query by a related PUser object
     *
     * @param   PUser|PropelObjectCollection $pUser The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDDebateQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUser($pUser, $comparison = null)
    {
        if ($pUser instanceof PUser) {
            return $this
                ->addUsingAlias(PDDebatePeer::P_USER_ID, $pUser->getId(), $comparison);
        } elseif ($pUser instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PDDebatePeer::P_USER_ID, $pUser->toKeyValue('PrimaryKey', 'Id'), $comparison);
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
     * @return PDDebateQuery The current query, for fluid interface
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
     * Filter the query by a related PUFollowDD object
     *
     * @param   PUFollowDD|PropelObjectCollection $pUFollowDD  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDDebateQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPuFollowDdPDDebate($pUFollowDD, $comparison = null)
    {
        if ($pUFollowDD instanceof PUFollowDD) {
            return $this
                ->addUsingAlias(PDDebatePeer::ID, $pUFollowDD->getPDDebateId(), $comparison);
        } elseif ($pUFollowDD instanceof PropelObjectCollection) {
            return $this
                ->usePuFollowDdPDDebateQuery()
                ->filterByPrimaryKeys($pUFollowDD->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPuFollowDdPDDebate() only accepts arguments of type PUFollowDD or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PuFollowDdPDDebate relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function joinPuFollowDdPDDebate($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PuFollowDdPDDebate');

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
            $this->addJoinObject($join, 'PuFollowDdPDDebate');
        }

        return $this;
    }

    /**
     * Use the PuFollowDdPDDebate relation PUFollowDD object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUFollowDDQuery A secondary query class using the current class as primary query
     */
    public function usePuFollowDdPDDebateQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPuFollowDdPDDebate($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PuFollowDdPDDebate', '\Politizr\Model\PUFollowDDQuery');
    }

    /**
     * Filter the query by a related PUBookmarkDD object
     *
     * @param   PUBookmarkDD|PropelObjectCollection $pUBookmarkDD  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDDebateQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPuBookmarkDdPDDebate($pUBookmarkDD, $comparison = null)
    {
        if ($pUBookmarkDD instanceof PUBookmarkDD) {
            return $this
                ->addUsingAlias(PDDebatePeer::ID, $pUBookmarkDD->getPDDebateId(), $comparison);
        } elseif ($pUBookmarkDD instanceof PropelObjectCollection) {
            return $this
                ->usePuBookmarkDdPDDebateQuery()
                ->filterByPrimaryKeys($pUBookmarkDD->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPuBookmarkDdPDDebate() only accepts arguments of type PUBookmarkDD or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PuBookmarkDdPDDebate relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function joinPuBookmarkDdPDDebate($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PuBookmarkDdPDDebate');

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
            $this->addJoinObject($join, 'PuBookmarkDdPDDebate');
        }

        return $this;
    }

    /**
     * Use the PuBookmarkDdPDDebate relation PUBookmarkDD object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUBookmarkDDQuery A secondary query class using the current class as primary query
     */
    public function usePuBookmarkDdPDDebateQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPuBookmarkDdPDDebate($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PuBookmarkDdPDDebate', '\Politizr\Model\PUBookmarkDDQuery');
    }

    /**
     * Filter the query by a related PUTrackDD object
     *
     * @param   PUTrackDD|PropelObjectCollection $pUTrackDD  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDDebateQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPuTrackDdPDDebate($pUTrackDD, $comparison = null)
    {
        if ($pUTrackDD instanceof PUTrackDD) {
            return $this
                ->addUsingAlias(PDDebatePeer::ID, $pUTrackDD->getPDDebateId(), $comparison);
        } elseif ($pUTrackDD instanceof PropelObjectCollection) {
            return $this
                ->usePuTrackDdPDDebateQuery()
                ->filterByPrimaryKeys($pUTrackDD->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPuTrackDdPDDebate() only accepts arguments of type PUTrackDD or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PuTrackDdPDDebate relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function joinPuTrackDdPDDebate($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PuTrackDdPDDebate');

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
            $this->addJoinObject($join, 'PuTrackDdPDDebate');
        }

        return $this;
    }

    /**
     * Use the PuTrackDdPDDebate relation PUTrackDD object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUTrackDDQuery A secondary query class using the current class as primary query
     */
    public function usePuTrackDdPDDebateQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPuTrackDdPDDebate($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PuTrackDdPDDebate', '\Politizr\Model\PUTrackDDQuery');
    }

    /**
     * Filter the query by a related PDReaction object
     *
     * @param   PDReaction|PropelObjectCollection $pDReaction  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDDebateQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPDReaction($pDReaction, $comparison = null)
    {
        if ($pDReaction instanceof PDReaction) {
            return $this
                ->addUsingAlias(PDDebatePeer::ID, $pDReaction->getPDDebateId(), $comparison);
        } elseif ($pDReaction instanceof PropelObjectCollection) {
            return $this
                ->usePDReactionQuery()
                ->filterByPrimaryKeys($pDReaction->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPDReaction() only accepts arguments of type PDReaction or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PDReaction relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function joinPDReaction($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PDReaction');

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
            $this->addJoinObject($join, 'PDReaction');
        }

        return $this;
    }

    /**
     * Use the PDReaction relation PDReaction object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PDReactionQuery A secondary query class using the current class as primary query
     */
    public function usePDReactionQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPDReaction($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PDReaction', '\Politizr\Model\PDReactionQuery');
    }

    /**
     * Filter the query by a related PDDComment object
     *
     * @param   PDDComment|PropelObjectCollection $pDDComment  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDDebateQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPDDComment($pDDComment, $comparison = null)
    {
        if ($pDDComment instanceof PDDComment) {
            return $this
                ->addUsingAlias(PDDebatePeer::ID, $pDDComment->getPDDebateId(), $comparison);
        } elseif ($pDDComment instanceof PropelObjectCollection) {
            return $this
                ->usePDDCommentQuery()
                ->filterByPrimaryKeys($pDDComment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPDDComment() only accepts arguments of type PDDComment or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PDDComment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function joinPDDComment($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PDDComment');

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
            $this->addJoinObject($join, 'PDDComment');
        }

        return $this;
    }

    /**
     * Use the PDDComment relation PDDComment object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PDDCommentQuery A secondary query class using the current class as primary query
     */
    public function usePDDCommentQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPDDComment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PDDComment', '\Politizr\Model\PDDCommentQuery');
    }

    /**
     * Filter the query by a related PDDTaggedT object
     *
     * @param   PDDTaggedT|PropelObjectCollection $pDDTaggedT  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDDebateQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPDDTaggedT($pDDTaggedT, $comparison = null)
    {
        if ($pDDTaggedT instanceof PDDTaggedT) {
            return $this
                ->addUsingAlias(PDDebatePeer::ID, $pDDTaggedT->getPDDebateId(), $comparison);
        } elseif ($pDDTaggedT instanceof PropelObjectCollection) {
            return $this
                ->usePDDTaggedTQuery()
                ->filterByPrimaryKeys($pDDTaggedT->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPDDTaggedT() only accepts arguments of type PDDTaggedT or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PDDTaggedT relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function joinPDDTaggedT($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PDDTaggedT');

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
            $this->addJoinObject($join, 'PDDTaggedT');
        }

        return $this;
    }

    /**
     * Use the PDDTaggedT relation PDDTaggedT object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PDDTaggedTQuery A secondary query class using the current class as primary query
     */
    public function usePDDTaggedTQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPDDTaggedT($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PDDTaggedT', '\Politizr\Model\PDDTaggedTQuery');
    }

    /**
     * Filter the query by a related PMDebateHistoric object
     *
     * @param   PMDebateHistoric|PropelObjectCollection $pMDebateHistoric  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PDDebateQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPMDebateHistoric($pMDebateHistoric, $comparison = null)
    {
        if ($pMDebateHistoric instanceof PMDebateHistoric) {
            return $this
                ->addUsingAlias(PDDebatePeer::ID, $pMDebateHistoric->getPDDebateId(), $comparison);
        } elseif ($pMDebateHistoric instanceof PropelObjectCollection) {
            return $this
                ->usePMDebateHistoricQuery()
                ->filterByPrimaryKeys($pMDebateHistoric->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPMDebateHistoric() only accepts arguments of type PMDebateHistoric or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PMDebateHistoric relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function joinPMDebateHistoric($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PMDebateHistoric');

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
            $this->addJoinObject($join, 'PMDebateHistoric');
        }

        return $this;
    }

    /**
     * Use the PMDebateHistoric relation PMDebateHistoric object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PMDebateHistoricQuery A secondary query class using the current class as primary query
     */
    public function usePMDebateHistoricQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPMDebateHistoric($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PMDebateHistoric', '\Politizr\Model\PMDebateHistoricQuery');
    }

    /**
     * Filter the query by a related PUser object
     * using the p_u_follow_d_d table as cross reference
     *
     * @param   PUser $pUser the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PDDebateQuery The current query, for fluid interface
     */
    public function filterByPuFollowDdPUser($pUser, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePuFollowDdPDDebateQuery()
            ->filterByPuFollowDdPUser($pUser, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PUser object
     * using the p_u_bookmark_d_d table as cross reference
     *
     * @param   PUser $pUser the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PDDebateQuery The current query, for fluid interface
     */
    public function filterByPuBookmarkDdPUser($pUser, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePuBookmarkDdPDDebateQuery()
            ->filterByPuBookmarkDdPUser($pUser, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PUser object
     * using the p_u_track_d_d table as cross reference
     *
     * @param   PUser $pUser the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PDDebateQuery The current query, for fluid interface
     */
    public function filterByPuTrackDdPUser($pUser, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePuTrackDdPDDebateQuery()
            ->filterByPuTrackDdPUser($pUser, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PTag object
     * using the p_d_d_tagged_t table as cross reference
     *
     * @param   PTag $pTag the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PDDebateQuery The current query, for fluid interface
     */
    public function filterByPTag($pTag, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePDDTaggedTQuery()
            ->filterByPTag($pTag, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   PDDebate $pDDebate Object to remove from the list of results
     *
     * @return PDDebateQuery The current query, for fluid interface
     */
    public function prune($pDDebate = null)
    {
        if ($pDDebate) {
            $this->addUsingAlias(PDDebatePeer::ID, $pDDebate->getId(), Criteria::NOT_EQUAL);
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
     * @return     PDDebateQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PDDebatePeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PDDebateQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PDDebatePeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PDDebateQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PDDebatePeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PDDebateQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PDDebatePeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PDDebateQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PDDebatePeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PDDebateQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PDDebatePeer::CREATED_AT);
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

        $dbMap = Propel::getDatabaseMap(PDDebatePeer::DATABASE_NAME);
        $db = Propel::getDB(PDDebatePeer::DATABASE_NAME);

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
     * Copy the data of the objects satisfying the query into PDDebateArchive archive objects.
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
            $con = Propel::getConnection(PDDebatePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
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
