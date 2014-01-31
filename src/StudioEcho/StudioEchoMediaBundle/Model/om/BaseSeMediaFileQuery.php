<?php

namespace StudioEcho\StudioEchoMediaBundle\Model\om;

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
use StudioEcho\StudioEchoMediaBundle\Model\SeMediaFile;
use StudioEcho\StudioEchoMediaBundle\Model\SeMediaFileI18n;
use StudioEcho\StudioEchoMediaBundle\Model\SeMediaFilePeer;
use StudioEcho\StudioEchoMediaBundle\Model\SeMediaFileQuery;
use StudioEcho\StudioEchoMediaBundle\Model\SeMediaObject;
use StudioEcho\StudioEchoMediaBundle\Model\SeObjectHasFile;

/**
 * @method SeMediaFileQuery orderById($order = Criteria::ASC) Order by the id column
 * @method SeMediaFileQuery orderByCategoryId($order = Criteria::ASC) Order by the category_id column
 * @method SeMediaFileQuery orderByExtension($order = Criteria::ASC) Order by the extension column
 * @method SeMediaFileQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method SeMediaFileQuery orderByMimeType($order = Criteria::ASC) Order by the mime_type column
 * @method SeMediaFileQuery orderBySize($order = Criteria::ASC) Order by the size column
 * @method SeMediaFileQuery orderByHeight($order = Criteria::ASC) Order by the height column
 * @method SeMediaFileQuery orderByWidth($order = Criteria::ASC) Order by the width column
 * @method SeMediaFileQuery orderByOnline($order = Criteria::ASC) Order by the online column
 * @method SeMediaFileQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method SeMediaFileQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 *
 * @method SeMediaFileQuery groupById() Group by the id column
 * @method SeMediaFileQuery groupByCategoryId() Group by the category_id column
 * @method SeMediaFileQuery groupByExtension() Group by the extension column
 * @method SeMediaFileQuery groupByType() Group by the type column
 * @method SeMediaFileQuery groupByMimeType() Group by the mime_type column
 * @method SeMediaFileQuery groupBySize() Group by the size column
 * @method SeMediaFileQuery groupByHeight() Group by the height column
 * @method SeMediaFileQuery groupByWidth() Group by the width column
 * @method SeMediaFileQuery groupByOnline() Group by the online column
 * @method SeMediaFileQuery groupByCreatedAt() Group by the created_at column
 * @method SeMediaFileQuery groupByUpdatedAt() Group by the updated_at column
 *
 * @method SeMediaFileQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method SeMediaFileQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method SeMediaFileQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method SeMediaFileQuery leftJoinSeObjectHasFile($relationAlias = null) Adds a LEFT JOIN clause to the query using the SeObjectHasFile relation
 * @method SeMediaFileQuery rightJoinSeObjectHasFile($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SeObjectHasFile relation
 * @method SeMediaFileQuery innerJoinSeObjectHasFile($relationAlias = null) Adds a INNER JOIN clause to the query using the SeObjectHasFile relation
 *
 * @method SeMediaFileQuery leftJoinSeMediaFileI18n($relationAlias = null) Adds a LEFT JOIN clause to the query using the SeMediaFileI18n relation
 * @method SeMediaFileQuery rightJoinSeMediaFileI18n($relationAlias = null) Adds a RIGHT JOIN clause to the query using the SeMediaFileI18n relation
 * @method SeMediaFileQuery innerJoinSeMediaFileI18n($relationAlias = null) Adds a INNER JOIN clause to the query using the SeMediaFileI18n relation
 *
 * @method SeMediaFile findOne(PropelPDO $con = null) Return the first SeMediaFile matching the query
 * @method SeMediaFile findOneOrCreate(PropelPDO $con = null) Return the first SeMediaFile matching the query, or a new SeMediaFile object populated from the query conditions when no match is found
 *
 * @method SeMediaFile findOneByCategoryId(int $category_id) Return the first SeMediaFile filtered by the category_id column
 * @method SeMediaFile findOneByExtension(string $extension) Return the first SeMediaFile filtered by the extension column
 * @method SeMediaFile findOneByType(string $type) Return the first SeMediaFile filtered by the type column
 * @method SeMediaFile findOneByMimeType(string $mime_type) Return the first SeMediaFile filtered by the mime_type column
 * @method SeMediaFile findOneBySize(int $size) Return the first SeMediaFile filtered by the size column
 * @method SeMediaFile findOneByHeight(int $height) Return the first SeMediaFile filtered by the height column
 * @method SeMediaFile findOneByWidth(int $width) Return the first SeMediaFile filtered by the width column
 * @method SeMediaFile findOneByOnline(boolean $online) Return the first SeMediaFile filtered by the online column
 * @method SeMediaFile findOneByCreatedAt(string $created_at) Return the first SeMediaFile filtered by the created_at column
 * @method SeMediaFile findOneByUpdatedAt(string $updated_at) Return the first SeMediaFile filtered by the updated_at column
 *
 * @method array findById(int $id) Return SeMediaFile objects filtered by the id column
 * @method array findByCategoryId(int $category_id) Return SeMediaFile objects filtered by the category_id column
 * @method array findByExtension(string $extension) Return SeMediaFile objects filtered by the extension column
 * @method array findByType(string $type) Return SeMediaFile objects filtered by the type column
 * @method array findByMimeType(string $mime_type) Return SeMediaFile objects filtered by the mime_type column
 * @method array findBySize(int $size) Return SeMediaFile objects filtered by the size column
 * @method array findByHeight(int $height) Return SeMediaFile objects filtered by the height column
 * @method array findByWidth(int $width) Return SeMediaFile objects filtered by the width column
 * @method array findByOnline(boolean $online) Return SeMediaFile objects filtered by the online column
 * @method array findByCreatedAt(string $created_at) Return SeMediaFile objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return SeMediaFile objects filtered by the updated_at column
 */
abstract class BaseSeMediaFileQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BaseSeMediaFileQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'StudioEcho\\StudioEchoMediaBundle\\Model\\SeMediaFile', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new SeMediaFileQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   SeMediaFileQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return SeMediaFileQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof SeMediaFileQuery) {
            return $criteria;
        }
        $query = new SeMediaFileQuery();
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
     * @return   SeMediaFile|SeMediaFile[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = SeMediaFilePeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(SeMediaFilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 SeMediaFile A model object, or null if the key is not found
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
     * @return                 SeMediaFile A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `category_id`, `extension`, `type`, `mime_type`, `size`, `height`, `width`, `online`, `created_at`, `updated_at` FROM `se_media_file` WHERE `id` = :p0';
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
            $obj = new SeMediaFile();
            $obj->hydrate($row);
            SeMediaFilePeer::addInstanceToPool($obj, (string) $key);
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
     * @return SeMediaFile|SeMediaFile[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|SeMediaFile[]|mixed the list of results, formatted by the current formatter
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
     * @return SeMediaFileQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(SeMediaFilePeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return SeMediaFileQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(SeMediaFilePeer::ID, $keys, Criteria::IN);
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
     * @return SeMediaFileQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(SeMediaFilePeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(SeMediaFilePeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeMediaFilePeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the category_id column
     *
     * Example usage:
     * <code>
     * $query->filterByCategoryId(1234); // WHERE category_id = 1234
     * $query->filterByCategoryId(array(12, 34)); // WHERE category_id IN (12, 34)
     * $query->filterByCategoryId(array('min' => 12)); // WHERE category_id >= 12
     * $query->filterByCategoryId(array('max' => 12)); // WHERE category_id <= 12
     * </code>
     *
     * @param     mixed $categoryId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SeMediaFileQuery The current query, for fluid interface
     */
    public function filterByCategoryId($categoryId = null, $comparison = null)
    {
        if (is_array($categoryId)) {
            $useMinMax = false;
            if (isset($categoryId['min'])) {
                $this->addUsingAlias(SeMediaFilePeer::CATEGORY_ID, $categoryId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($categoryId['max'])) {
                $this->addUsingAlias(SeMediaFilePeer::CATEGORY_ID, $categoryId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeMediaFilePeer::CATEGORY_ID, $categoryId, $comparison);
    }

    /**
     * Filter the query on the extension column
     *
     * Example usage:
     * <code>
     * $query->filterByExtension('fooValue');   // WHERE extension = 'fooValue'
     * $query->filterByExtension('%fooValue%'); // WHERE extension LIKE '%fooValue%'
     * </code>
     *
     * @param     string $extension The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SeMediaFileQuery The current query, for fluid interface
     */
    public function filterByExtension($extension = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($extension)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $extension)) {
                $extension = str_replace('*', '%', $extension);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SeMediaFilePeer::EXTENSION, $extension, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * Example usage:
     * <code>
     * $query->filterByType('fooValue');   // WHERE type = 'fooValue'
     * $query->filterByType('%fooValue%'); // WHERE type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $type The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SeMediaFileQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($type)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $type)) {
                $type = str_replace('*', '%', $type);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SeMediaFilePeer::TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the mime_type column
     *
     * Example usage:
     * <code>
     * $query->filterByMimeType('fooValue');   // WHERE mime_type = 'fooValue'
     * $query->filterByMimeType('%fooValue%'); // WHERE mime_type LIKE '%fooValue%'
     * </code>
     *
     * @param     string $mimeType The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SeMediaFileQuery The current query, for fluid interface
     */
    public function filterByMimeType($mimeType = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($mimeType)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $mimeType)) {
                $mimeType = str_replace('*', '%', $mimeType);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(SeMediaFilePeer::MIME_TYPE, $mimeType, $comparison);
    }

    /**
     * Filter the query on the size column
     *
     * Example usage:
     * <code>
     * $query->filterBySize(1234); // WHERE size = 1234
     * $query->filterBySize(array(12, 34)); // WHERE size IN (12, 34)
     * $query->filterBySize(array('min' => 12)); // WHERE size >= 12
     * $query->filterBySize(array('max' => 12)); // WHERE size <= 12
     * </code>
     *
     * @param     mixed $size The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SeMediaFileQuery The current query, for fluid interface
     */
    public function filterBySize($size = null, $comparison = null)
    {
        if (is_array($size)) {
            $useMinMax = false;
            if (isset($size['min'])) {
                $this->addUsingAlias(SeMediaFilePeer::SIZE, $size['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($size['max'])) {
                $this->addUsingAlias(SeMediaFilePeer::SIZE, $size['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeMediaFilePeer::SIZE, $size, $comparison);
    }

    /**
     * Filter the query on the height column
     *
     * Example usage:
     * <code>
     * $query->filterByHeight(1234); // WHERE height = 1234
     * $query->filterByHeight(array(12, 34)); // WHERE height IN (12, 34)
     * $query->filterByHeight(array('min' => 12)); // WHERE height >= 12
     * $query->filterByHeight(array('max' => 12)); // WHERE height <= 12
     * </code>
     *
     * @param     mixed $height The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SeMediaFileQuery The current query, for fluid interface
     */
    public function filterByHeight($height = null, $comparison = null)
    {
        if (is_array($height)) {
            $useMinMax = false;
            if (isset($height['min'])) {
                $this->addUsingAlias(SeMediaFilePeer::HEIGHT, $height['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($height['max'])) {
                $this->addUsingAlias(SeMediaFilePeer::HEIGHT, $height['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeMediaFilePeer::HEIGHT, $height, $comparison);
    }

    /**
     * Filter the query on the width column
     *
     * Example usage:
     * <code>
     * $query->filterByWidth(1234); // WHERE width = 1234
     * $query->filterByWidth(array(12, 34)); // WHERE width IN (12, 34)
     * $query->filterByWidth(array('min' => 12)); // WHERE width >= 12
     * $query->filterByWidth(array('max' => 12)); // WHERE width <= 12
     * </code>
     *
     * @param     mixed $width The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return SeMediaFileQuery The current query, for fluid interface
     */
    public function filterByWidth($width = null, $comparison = null)
    {
        if (is_array($width)) {
            $useMinMax = false;
            if (isset($width['min'])) {
                $this->addUsingAlias(SeMediaFilePeer::WIDTH, $width['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($width['max'])) {
                $this->addUsingAlias(SeMediaFilePeer::WIDTH, $width['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeMediaFilePeer::WIDTH, $width, $comparison);
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
     * @return SeMediaFileQuery The current query, for fluid interface
     */
    public function filterByOnline($online = null, $comparison = null)
    {
        if (is_string($online)) {
            $online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(SeMediaFilePeer::ONLINE, $online, $comparison);
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
     * @return SeMediaFileQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(SeMediaFilePeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(SeMediaFilePeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeMediaFilePeer::CREATED_AT, $createdAt, $comparison);
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
     * @return SeMediaFileQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(SeMediaFilePeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(SeMediaFilePeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(SeMediaFilePeer::UPDATED_AT, $updatedAt, $comparison);
    }

    /**
     * Filter the query by a related SeObjectHasFile object
     *
     * @param   SeObjectHasFile|PropelObjectCollection $seObjectHasFile  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SeMediaFileQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySeObjectHasFile($seObjectHasFile, $comparison = null)
    {
        if ($seObjectHasFile instanceof SeObjectHasFile) {
            return $this
                ->addUsingAlias(SeMediaFilePeer::ID, $seObjectHasFile->getSeMediaFileId(), $comparison);
        } elseif ($seObjectHasFile instanceof PropelObjectCollection) {
            return $this
                ->useSeObjectHasFileQuery()
                ->filterByPrimaryKeys($seObjectHasFile->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySeObjectHasFile() only accepts arguments of type SeObjectHasFile or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SeObjectHasFile relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SeMediaFileQuery The current query, for fluid interface
     */
    public function joinSeObjectHasFile($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SeObjectHasFile');

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
            $this->addJoinObject($join, 'SeObjectHasFile');
        }

        return $this;
    }

    /**
     * Use the SeObjectHasFile relation SeObjectHasFile object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \StudioEcho\StudioEchoMediaBundle\Model\SeObjectHasFileQuery A secondary query class using the current class as primary query
     */
    public function useSeObjectHasFileQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinSeObjectHasFile($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SeObjectHasFile', '\StudioEcho\StudioEchoMediaBundle\Model\SeObjectHasFileQuery');
    }

    /**
     * Filter the query by a related SeMediaFileI18n object
     *
     * @param   SeMediaFileI18n|PropelObjectCollection $seMediaFileI18n  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 SeMediaFileQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterBySeMediaFileI18n($seMediaFileI18n, $comparison = null)
    {
        if ($seMediaFileI18n instanceof SeMediaFileI18n) {
            return $this
                ->addUsingAlias(SeMediaFilePeer::ID, $seMediaFileI18n->getId(), $comparison);
        } elseif ($seMediaFileI18n instanceof PropelObjectCollection) {
            return $this
                ->useSeMediaFileI18nQuery()
                ->filterByPrimaryKeys($seMediaFileI18n->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterBySeMediaFileI18n() only accepts arguments of type SeMediaFileI18n or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the SeMediaFileI18n relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return SeMediaFileQuery The current query, for fluid interface
     */
    public function joinSeMediaFileI18n($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('SeMediaFileI18n');

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
            $this->addJoinObject($join, 'SeMediaFileI18n');
        }

        return $this;
    }

    /**
     * Use the SeMediaFileI18n relation SeMediaFileI18n object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \StudioEcho\StudioEchoMediaBundle\Model\SeMediaFileI18nQuery A secondary query class using the current class as primary query
     */
    public function useSeMediaFileI18nQuery($relationAlias = null, $joinType = 'LEFT JOIN')
    {
        return $this
            ->joinSeMediaFileI18n($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SeMediaFileI18n', '\StudioEcho\StudioEchoMediaBundle\Model\SeMediaFileI18nQuery');
    }

    /**
     * Filter the query by a related SeMediaObject object
     * using the se_object_has_file table as cross reference
     *
     * @param   SeMediaObject $seMediaObject the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   SeMediaFileQuery The current query, for fluid interface
     */
    public function filterBySeMediaObject($seMediaObject, $comparison = Criteria::EQUAL)
    {
        return $this
            ->useSeObjectHasFileQuery()
            ->filterBySeMediaObject($seMediaObject, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   SeMediaFile $seMediaFile Object to remove from the list of results
     *
     * @return SeMediaFileQuery The current query, for fluid interface
     */
    public function prune($seMediaFile = null)
    {
        if ($seMediaFile) {
            $this->addUsingAlias(SeMediaFilePeer::ID, $seMediaFile->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // i18n behavior

    /**
     * Adds a JOIN clause to the query using the i18n relation
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    SeMediaFileQuery The current query, for fluid interface
     */
    public function joinI18n($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $relationName = $relationAlias ? $relationAlias : 'SeMediaFileI18n';

        return $this
            ->joinSeMediaFileI18n($relationAlias, $joinType)
            ->addJoinCondition($relationName, $relationName . '.Locale = ?', $locale);
    }

    /**
     * Adds a JOIN clause to the query and hydrates the related I18n object.
     * Shortcut for $c->joinI18n($locale)->with()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    SeMediaFileQuery The current query, for fluid interface
     */
    public function joinWithI18n($locale = 'en_US', $joinType = Criteria::LEFT_JOIN)
    {
        $this
            ->joinI18n($locale, null, $joinType)
            ->with('SeMediaFileI18n');
        $this->with['SeMediaFileI18n']->setIsWithOneToMany(false);

        return $this;
    }

    /**
     * Use the I18n relation query object
     *
     * @see       useQuery()
     *
     * @param     string $locale Locale to use for the join condition, e.g. 'fr_FR'
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'. Defaults to left join.
     *
     * @return    SeMediaFileI18nQuery A secondary query class using the current class as primary query
     */
    public function useI18nQuery($locale = 'en_US', $relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinI18n($locale, $relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'SeMediaFileI18n', 'StudioEcho\StudioEchoMediaBundle\Model\SeMediaFileI18nQuery');
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     SeMediaFileQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(SeMediaFilePeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     SeMediaFileQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(SeMediaFilePeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     SeMediaFileQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(SeMediaFilePeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     SeMediaFileQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(SeMediaFilePeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     SeMediaFileQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(SeMediaFilePeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     SeMediaFileQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(SeMediaFilePeer::CREATED_AT);
    }
}
