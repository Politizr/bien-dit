<?php

namespace Politizr\Model\om;

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
use Politizr\Model\PDDebate;
use Politizr\Model\PDRComment;
use Politizr\Model\PDReaction;
use Politizr\Model\POrder;
use Politizr\Model\PRAction;
use Politizr\Model\PRBadge;
use Politizr\Model\PUFollowDD;
use Politizr\Model\PUFollowU;
use Politizr\Model\PUQualification;
use Politizr\Model\PUReputationRA;
use Politizr\Model\PUReputationRB;
use Politizr\Model\PUser;
use Politizr\Model\PUserPeer;
use Politizr\Model\PUserQuery;

/**
 * @method PUserQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PUserQuery orderByType($order = Criteria::ASC) Order by the type column
 * @method PUserQuery orderByStatus($order = Criteria::ASC) Order by the status column
 * @method PUserQuery orderByFileName($order = Criteria::ASC) Order by the file_name column
 * @method PUserQuery orderByGender($order = Criteria::ASC) Order by the gender column
 * @method PUserQuery orderByFirstname($order = Criteria::ASC) Order by the firstname column
 * @method PUserQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method PUserQuery orderBySummary($order = Criteria::ASC) Order by the summary column
 * @method PUserQuery orderByBiography($order = Criteria::ASC) Order by the biography column
 * @method PUserQuery orderByWebsite($order = Criteria::ASC) Order by the website column
 * @method PUserQuery orderByTwitter($order = Criteria::ASC) Order by the twitter column
 * @method PUserQuery orderByFacebook($order = Criteria::ASC) Order by the facebook column
 * @method PUserQuery orderByEmail($order = Criteria::ASC) Order by the email column
 * @method PUserQuery orderByPhone($order = Criteria::ASC) Order by the phone column
 * @method PUserQuery orderByLastConnect($order = Criteria::ASC) Order by the last_connect column
 * @method PUserQuery orderByOnline($order = Criteria::ASC) Order by the online column
 * @method PUserQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PUserQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PUserQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 *
 * @method PUserQuery groupById() Group by the id column
 * @method PUserQuery groupByType() Group by the type column
 * @method PUserQuery groupByStatus() Group by the status column
 * @method PUserQuery groupByFileName() Group by the file_name column
 * @method PUserQuery groupByGender() Group by the gender column
 * @method PUserQuery groupByFirstname() Group by the firstname column
 * @method PUserQuery groupByName() Group by the name column
 * @method PUserQuery groupBySummary() Group by the summary column
 * @method PUserQuery groupByBiography() Group by the biography column
 * @method PUserQuery groupByWebsite() Group by the website column
 * @method PUserQuery groupByTwitter() Group by the twitter column
 * @method PUserQuery groupByFacebook() Group by the facebook column
 * @method PUserQuery groupByEmail() Group by the email column
 * @method PUserQuery groupByPhone() Group by the phone column
 * @method PUserQuery groupByLastConnect() Group by the last_connect column
 * @method PUserQuery groupByOnline() Group by the online column
 * @method PUserQuery groupByCreatedAt() Group by the created_at column
 * @method PUserQuery groupByUpdatedAt() Group by the updated_at column
 * @method PUserQuery groupBySlug() Group by the slug column
 *
 * @method PUserQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PUserQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PUserQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PUserQuery leftJoinPOrder($relationAlias = null) Adds a LEFT JOIN clause to the query using the POrder relation
 * @method PUserQuery rightJoinPOrder($relationAlias = null) Adds a RIGHT JOIN clause to the query using the POrder relation
 * @method PUserQuery innerJoinPOrder($relationAlias = null) Adds a INNER JOIN clause to the query using the POrder relation
 *
 * @method PUserQuery leftJoinPUQualification($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUQualification relation
 * @method PUserQuery rightJoinPUQualification($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUQualification relation
 * @method PUserQuery innerJoinPUQualification($relationAlias = null) Adds a INNER JOIN clause to the query using the PUQualification relation
 *
 * @method PUserQuery leftJoinPuFollowDdPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PuFollowDdPUser relation
 * @method PUserQuery rightJoinPuFollowDdPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PuFollowDdPUser relation
 * @method PUserQuery innerJoinPuFollowDdPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PuFollowDdPUser relation
 *
 * @method PUserQuery leftJoinPuReputationRbPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PuReputationRbPUser relation
 * @method PUserQuery rightJoinPuReputationRbPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PuReputationRbPUser relation
 * @method PUserQuery innerJoinPuReputationRbPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PuReputationRbPUser relation
 *
 * @method PUserQuery leftJoinPuReputationRaPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PuReputationRaPUser relation
 * @method PUserQuery rightJoinPuReputationRaPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PuReputationRaPUser relation
 * @method PUserQuery innerJoinPuReputationRaPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PuReputationRaPUser relation
 *
 * @method PUserQuery leftJoinPDDebate($relationAlias = null) Adds a LEFT JOIN clause to the query using the PDDebate relation
 * @method PUserQuery rightJoinPDDebate($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PDDebate relation
 * @method PUserQuery innerJoinPDDebate($relationAlias = null) Adds a INNER JOIN clause to the query using the PDDebate relation
 *
 * @method PUserQuery leftJoinPDDComment($relationAlias = null) Adds a LEFT JOIN clause to the query using the PDDComment relation
 * @method PUserQuery rightJoinPDDComment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PDDComment relation
 * @method PUserQuery innerJoinPDDComment($relationAlias = null) Adds a INNER JOIN clause to the query using the PDDComment relation
 *
 * @method PUserQuery leftJoinPDReaction($relationAlias = null) Adds a LEFT JOIN clause to the query using the PDReaction relation
 * @method PUserQuery rightJoinPDReaction($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PDReaction relation
 * @method PUserQuery innerJoinPDReaction($relationAlias = null) Adds a INNER JOIN clause to the query using the PDReaction relation
 *
 * @method PUserQuery leftJoinPDRComment($relationAlias = null) Adds a LEFT JOIN clause to the query using the PDRComment relation
 * @method PUserQuery rightJoinPDRComment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PDRComment relation
 * @method PUserQuery innerJoinPDRComment($relationAlias = null) Adds a INNER JOIN clause to the query using the PDRComment relation
 *
 * @method PUserQuery leftJoinPUFollowURelatedByPUserId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUFollowURelatedByPUserId relation
 * @method PUserQuery rightJoinPUFollowURelatedByPUserId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUFollowURelatedByPUserId relation
 * @method PUserQuery innerJoinPUFollowURelatedByPUserId($relationAlias = null) Adds a INNER JOIN clause to the query using the PUFollowURelatedByPUserId relation
 *
 * @method PUserQuery leftJoinPUFollowURelatedByPUserFollowerId($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUFollowURelatedByPUserFollowerId relation
 * @method PUserQuery rightJoinPUFollowURelatedByPUserFollowerId($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUFollowURelatedByPUserFollowerId relation
 * @method PUserQuery innerJoinPUFollowURelatedByPUserFollowerId($relationAlias = null) Adds a INNER JOIN clause to the query using the PUFollowURelatedByPUserFollowerId relation
 *
 * @method PUser findOne(PropelPDO $con = null) Return the first PUser matching the query
 * @method PUser findOneOrCreate(PropelPDO $con = null) Return the first PUser matching the query, or a new PUser object populated from the query conditions when no match is found
 *
 * @method PUser findOneByType(int $type) Return the first PUser filtered by the type column
 * @method PUser findOneByStatus(int $status) Return the first PUser filtered by the status column
 * @method PUser findOneByFileName(string $file_name) Return the first PUser filtered by the file_name column
 * @method PUser findOneByGender(int $gender) Return the first PUser filtered by the gender column
 * @method PUser findOneByFirstname(string $firstname) Return the first PUser filtered by the firstname column
 * @method PUser findOneByName(string $name) Return the first PUser filtered by the name column
 * @method PUser findOneBySummary(string $summary) Return the first PUser filtered by the summary column
 * @method PUser findOneByBiography(string $biography) Return the first PUser filtered by the biography column
 * @method PUser findOneByWebsite(string $website) Return the first PUser filtered by the website column
 * @method PUser findOneByTwitter(string $twitter) Return the first PUser filtered by the twitter column
 * @method PUser findOneByFacebook(string $facebook) Return the first PUser filtered by the facebook column
 * @method PUser findOneByEmail(string $email) Return the first PUser filtered by the email column
 * @method PUser findOneByPhone(string $phone) Return the first PUser filtered by the phone column
 * @method PUser findOneByLastConnect(string $last_connect) Return the first PUser filtered by the last_connect column
 * @method PUser findOneByOnline(boolean $online) Return the first PUser filtered by the online column
 * @method PUser findOneByCreatedAt(string $created_at) Return the first PUser filtered by the created_at column
 * @method PUser findOneByUpdatedAt(string $updated_at) Return the first PUser filtered by the updated_at column
 * @method PUser findOneBySlug(string $slug) Return the first PUser filtered by the slug column
 *
 * @method array findById(int $id) Return PUser objects filtered by the id column
 * @method array findByType(int $type) Return PUser objects filtered by the type column
 * @method array findByStatus(int $status) Return PUser objects filtered by the status column
 * @method array findByFileName(string $file_name) Return PUser objects filtered by the file_name column
 * @method array findByGender(int $gender) Return PUser objects filtered by the gender column
 * @method array findByFirstname(string $firstname) Return PUser objects filtered by the firstname column
 * @method array findByName(string $name) Return PUser objects filtered by the name column
 * @method array findBySummary(string $summary) Return PUser objects filtered by the summary column
 * @method array findByBiography(string $biography) Return PUser objects filtered by the biography column
 * @method array findByWebsite(string $website) Return PUser objects filtered by the website column
 * @method array findByTwitter(string $twitter) Return PUser objects filtered by the twitter column
 * @method array findByFacebook(string $facebook) Return PUser objects filtered by the facebook column
 * @method array findByEmail(string $email) Return PUser objects filtered by the email column
 * @method array findByPhone(string $phone) Return PUser objects filtered by the phone column
 * @method array findByLastConnect(string $last_connect) Return PUser objects filtered by the last_connect column
 * @method array findByOnline(boolean $online) Return PUser objects filtered by the online column
 * @method array findByCreatedAt(string $created_at) Return PUser objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PUser objects filtered by the updated_at column
 * @method array findBySlug(string $slug) Return PUser objects filtered by the slug column
 */
abstract class BasePUserQuery extends ModelCriteria
{
    /**
     * Initializes internal state of BasePUserQuery object.
     *
     * @param     string $dbName The dabase name
     * @param     string $modelName The phpName of a model, e.g. 'Book'
     * @param     string $modelAlias The alias for the model in this query, e.g. 'b'
     */
    public function __construct($dbName = 'default', $modelName = 'Politizr\\Model\\PUser', $modelAlias = null)
    {
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PUserQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PUserQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PUserQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PUserQuery) {
            return $criteria;
        }
        $query = new PUserQuery();
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
     * @return   PUser|PUser[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PUserPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is alredy in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PUserPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PUser A model object, or null if the key is not found
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
     * @return                 PUser A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `type`, `status`, `file_name`, `gender`, `firstname`, `name`, `summary`, `biography`, `website`, `twitter`, `facebook`, `email`, `phone`, `last_connect`, `online`, `created_at`, `updated_at`, `slug` FROM `p_user` WHERE `id` = :p0';
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
            $obj = new PUser();
            $obj->hydrate($row);
            PUserPeer::addInstanceToPool($obj, (string) $key);
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
     * @return PUser|PUser[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PUser[]|mixed the list of results, formatted by the current formatter
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PUserPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PUserPeer::ID, $keys, Criteria::IN);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PUserPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PUserPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the type column
     *
     * Example usage:
     * <code>
     * $query->filterByType(1234); // WHERE type = 1234
     * $query->filterByType(array(12, 34)); // WHERE type IN (12, 34)
     * $query->filterByType(array('min' => 12)); // WHERE type >= 12
     * $query->filterByType(array('max' => 12)); // WHERE type <= 12
     * </code>
     *
     * @param     mixed $type The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByType($type = null, $comparison = null)
    {
        if (is_array($type)) {
            $useMinMax = false;
            if (isset($type['min'])) {
                $this->addUsingAlias(PUserPeer::TYPE, $type['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($type['max'])) {
                $this->addUsingAlias(PUserPeer::TYPE, $type['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserPeer::TYPE, $type, $comparison);
    }

    /**
     * Filter the query on the status column
     *
     * Example usage:
     * <code>
     * $query->filterByStatus(1234); // WHERE status = 1234
     * $query->filterByStatus(array(12, 34)); // WHERE status IN (12, 34)
     * $query->filterByStatus(array('min' => 12)); // WHERE status >= 12
     * $query->filterByStatus(array('max' => 12)); // WHERE status <= 12
     * </code>
     *
     * @param     mixed $status The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByStatus($status = null, $comparison = null)
    {
        if (is_array($status)) {
            $useMinMax = false;
            if (isset($status['min'])) {
                $this->addUsingAlias(PUserPeer::STATUS, $status['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($status['max'])) {
                $this->addUsingAlias(PUserPeer::STATUS, $status['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserPeer::STATUS, $status, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::FILE_NAME, $fileName, $comparison);
    }

    /**
     * Filter the query on the gender column
     *
     * @param     mixed $gender The value to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserQuery The current query, for fluid interface
     * @throws PropelException - if the value is not accepted by the enum.
     */
    public function filterByGender($gender = null, $comparison = null)
    {
        if (is_scalar($gender)) {
            $gender = PUserPeer::getSqlValueForEnum(PUserPeer::GENDER, $gender);
        } elseif (is_array($gender)) {
            $convertedValues = array();
            foreach ($gender as $value) {
                $convertedValues[] = PUserPeer::getSqlValueForEnum(PUserPeer::GENDER, $value);
            }
            $gender = $convertedValues;
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserPeer::GENDER, $gender, $comparison);
    }

    /**
     * Filter the query on the firstname column
     *
     * Example usage:
     * <code>
     * $query->filterByFirstname('fooValue');   // WHERE firstname = 'fooValue'
     * $query->filterByFirstname('%fooValue%'); // WHERE firstname LIKE '%fooValue%'
     * </code>
     *
     * @param     string $firstname The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByFirstname($firstname = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($firstname)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $firstname)) {
                $firstname = str_replace('*', '%', $firstname);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserPeer::FIRSTNAME, $firstname, $comparison);
    }

    /**
     * Filter the query on the name column
     *
     * Example usage:
     * <code>
     * $query->filterByName('fooValue');   // WHERE name = 'fooValue'
     * $query->filterByName('%fooValue%'); // WHERE name LIKE '%fooValue%'
     * </code>
     *
     * @param     string $name The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByName($name = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($name)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $name)) {
                $name = str_replace('*', '%', $name);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserPeer::NAME, $name, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::SUMMARY, $summary, $comparison);
    }

    /**
     * Filter the query on the biography column
     *
     * Example usage:
     * <code>
     * $query->filterByBiography('fooValue');   // WHERE biography = 'fooValue'
     * $query->filterByBiography('%fooValue%'); // WHERE biography LIKE '%fooValue%'
     * </code>
     *
     * @param     string $biography The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByBiography($biography = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($biography)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $biography)) {
                $biography = str_replace('*', '%', $biography);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserPeer::BIOGRAPHY, $biography, $comparison);
    }

    /**
     * Filter the query on the website column
     *
     * Example usage:
     * <code>
     * $query->filterByWebsite('fooValue');   // WHERE website = 'fooValue'
     * $query->filterByWebsite('%fooValue%'); // WHERE website LIKE '%fooValue%'
     * </code>
     *
     * @param     string $website The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByWebsite($website = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($website)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $website)) {
                $website = str_replace('*', '%', $website);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserPeer::WEBSITE, $website, $comparison);
    }

    /**
     * Filter the query on the twitter column
     *
     * Example usage:
     * <code>
     * $query->filterByTwitter('fooValue');   // WHERE twitter = 'fooValue'
     * $query->filterByTwitter('%fooValue%'); // WHERE twitter LIKE '%fooValue%'
     * </code>
     *
     * @param     string $twitter The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByTwitter($twitter = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($twitter)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $twitter)) {
                $twitter = str_replace('*', '%', $twitter);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserPeer::TWITTER, $twitter, $comparison);
    }

    /**
     * Filter the query on the facebook column
     *
     * Example usage:
     * <code>
     * $query->filterByFacebook('fooValue');   // WHERE facebook = 'fooValue'
     * $query->filterByFacebook('%fooValue%'); // WHERE facebook LIKE '%fooValue%'
     * </code>
     *
     * @param     string $facebook The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByFacebook($facebook = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($facebook)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $facebook)) {
                $facebook = str_replace('*', '%', $facebook);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserPeer::FACEBOOK, $facebook, $comparison);
    }

    /**
     * Filter the query on the email column
     *
     * Example usage:
     * <code>
     * $query->filterByEmail('fooValue');   // WHERE email = 'fooValue'
     * $query->filterByEmail('%fooValue%'); // WHERE email LIKE '%fooValue%'
     * </code>
     *
     * @param     string $email The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByEmail($email = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($email)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $email)) {
                $email = str_replace('*', '%', $email);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserPeer::EMAIL, $email, $comparison);
    }

    /**
     * Filter the query on the phone column
     *
     * Example usage:
     * <code>
     * $query->filterByPhone('fooValue');   // WHERE phone = 'fooValue'
     * $query->filterByPhone('%fooValue%'); // WHERE phone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $phone The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByPhone($phone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($phone)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $phone)) {
                $phone = str_replace('*', '%', $phone);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PUserPeer::PHONE, $phone, $comparison);
    }

    /**
     * Filter the query on the last_connect column
     *
     * Example usage:
     * <code>
     * $query->filterByLastConnect('2011-03-14'); // WHERE last_connect = '2011-03-14'
     * $query->filterByLastConnect('now'); // WHERE last_connect = '2011-03-14'
     * $query->filterByLastConnect(array('max' => 'yesterday')); // WHERE last_connect > '2011-03-13'
     * </code>
     *
     * @param     mixed $lastConnect The value to use as filter.
     *              Values can be integers (unix timestamps), DateTime objects, or strings.
     *              Empty strings are treated as NULL.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByLastConnect($lastConnect = null, $comparison = null)
    {
        if (is_array($lastConnect)) {
            $useMinMax = false;
            if (isset($lastConnect['min'])) {
                $this->addUsingAlias(PUserPeer::LAST_CONNECT, $lastConnect['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($lastConnect['max'])) {
                $this->addUsingAlias(PUserPeer::LAST_CONNECT, $lastConnect['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserPeer::LAST_CONNECT, $lastConnect, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByOnline($online = null, $comparison = null)
    {
        if (is_string($online)) {
            $online = in_array(strtolower($online), array('false', 'off', '-', 'no', 'n', '0', '')) ? false : true;
        }

        return $this->addUsingAlias(PUserPeer::ONLINE, $online, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PUserPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PUserPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PUserPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PUserPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PUserPeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return PUserQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PUserPeer::SLUG, $slug, $comparison);
    }

    /**
     * Filter the query by a related POrder object
     *
     * @param   POrder|PropelObjectCollection $pOrder  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPOrder($pOrder, $comparison = null)
    {
        if ($pOrder instanceof POrder) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pOrder->getPUserId(), $comparison);
        } elseif ($pOrder instanceof PropelObjectCollection) {
            return $this
                ->usePOrderQuery()
                ->filterByPrimaryKeys($pOrder->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPOrder() only accepts arguments of type POrder or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the POrder relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPOrder($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('POrder');

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
            $this->addJoinObject($join, 'POrder');
        }

        return $this;
    }

    /**
     * Use the POrder relation POrder object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\POrderQuery A secondary query class using the current class as primary query
     */
    public function usePOrderQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPOrder($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'POrder', '\Politizr\Model\POrderQuery');
    }

    /**
     * Filter the query by a related PUQualification object
     *
     * @param   PUQualification|PropelObjectCollection $pUQualification  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUQualification($pUQualification, $comparison = null)
    {
        if ($pUQualification instanceof PUQualification) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pUQualification->getPUserId(), $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPUQualification($relationAlias = null, $joinType = Criteria::INNER_JOIN)
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
    public function usePUQualificationQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUQualification($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUQualification', '\Politizr\Model\PUQualificationQuery');
    }

    /**
     * Filter the query by a related PUFollowDD object
     *
     * @param   PUFollowDD|PropelObjectCollection $pUFollowDD  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPuFollowDdPUser($pUFollowDD, $comparison = null)
    {
        if ($pUFollowDD instanceof PUFollowDD) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pUFollowDD->getPUserId(), $comparison);
        } elseif ($pUFollowDD instanceof PropelObjectCollection) {
            return $this
                ->usePuFollowDdPUserQuery()
                ->filterByPrimaryKeys($pUFollowDD->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPuFollowDdPUser() only accepts arguments of type PUFollowDD or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PuFollowDdPUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPuFollowDdPUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PuFollowDdPUser');

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
            $this->addJoinObject($join, 'PuFollowDdPUser');
        }

        return $this;
    }

    /**
     * Use the PuFollowDdPUser relation PUFollowDD object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUFollowDDQuery A secondary query class using the current class as primary query
     */
    public function usePuFollowDdPUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPuFollowDdPUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PuFollowDdPUser', '\Politizr\Model\PUFollowDDQuery');
    }

    /**
     * Filter the query by a related PUReputationRB object
     *
     * @param   PUReputationRB|PropelObjectCollection $pUReputationRB  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPuReputationRbPUser($pUReputationRB, $comparison = null)
    {
        if ($pUReputationRB instanceof PUReputationRB) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pUReputationRB->getPUserId(), $comparison);
        } elseif ($pUReputationRB instanceof PropelObjectCollection) {
            return $this
                ->usePuReputationRbPUserQuery()
                ->filterByPrimaryKeys($pUReputationRB->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPuReputationRbPUser() only accepts arguments of type PUReputationRB or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PuReputationRbPUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPuReputationRbPUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PuReputationRbPUser');

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
            $this->addJoinObject($join, 'PuReputationRbPUser');
        }

        return $this;
    }

    /**
     * Use the PuReputationRbPUser relation PUReputationRB object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUReputationRBQuery A secondary query class using the current class as primary query
     */
    public function usePuReputationRbPUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPuReputationRbPUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PuReputationRbPUser', '\Politizr\Model\PUReputationRBQuery');
    }

    /**
     * Filter the query by a related PUReputationRA object
     *
     * @param   PUReputationRA|PropelObjectCollection $pUReputationRA  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPuReputationRaPUser($pUReputationRA, $comparison = null)
    {
        if ($pUReputationRA instanceof PUReputationRA) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pUReputationRA->getPUserId(), $comparison);
        } elseif ($pUReputationRA instanceof PropelObjectCollection) {
            return $this
                ->usePuReputationRaPUserQuery()
                ->filterByPrimaryKeys($pUReputationRA->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPuReputationRaPUser() only accepts arguments of type PUReputationRA or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PuReputationRaPUser relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPuReputationRaPUser($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PuReputationRaPUser');

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
            $this->addJoinObject($join, 'PuReputationRaPUser');
        }

        return $this;
    }

    /**
     * Use the PuReputationRaPUser relation PUReputationRA object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUReputationRAQuery A secondary query class using the current class as primary query
     */
    public function usePuReputationRaPUserQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPuReputationRaPUser($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PuReputationRaPUser', '\Politizr\Model\PUReputationRAQuery');
    }

    /**
     * Filter the query by a related PDDebate object
     *
     * @param   PDDebate|PropelObjectCollection $pDDebate  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPDDebate($pDDebate, $comparison = null)
    {
        if ($pDDebate instanceof PDDebate) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pDDebate->getPUserId(), $comparison);
        } elseif ($pDDebate instanceof PropelObjectCollection) {
            return $this
                ->usePDDebateQuery()
                ->filterByPrimaryKeys($pDDebate->getPrimaryKeys())
                ->endUse();
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPDDebate($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function usePDDebateQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPDDebate($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PDDebate', '\Politizr\Model\PDDebateQuery');
    }

    /**
     * Filter the query by a related PDDComment object
     *
     * @param   PDDComment|PropelObjectCollection $pDDComment  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPDDComment($pDDComment, $comparison = null)
    {
        if ($pDDComment instanceof PDDComment) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pDDComment->getPUserId(), $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPDDComment($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function usePDDCommentQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPDDComment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PDDComment', '\Politizr\Model\PDDCommentQuery');
    }

    /**
     * Filter the query by a related PDReaction object
     *
     * @param   PDReaction|PropelObjectCollection $pDReaction  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPDReaction($pDReaction, $comparison = null)
    {
        if ($pDReaction instanceof PDReaction) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pDReaction->getPUserId(), $comparison);
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
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPDReaction($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
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
    public function usePDReactionQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPDReaction($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PDReaction', '\Politizr\Model\PDReactionQuery');
    }

    /**
     * Filter the query by a related PDRComment object
     *
     * @param   PDRComment|PropelObjectCollection $pDRComment  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPDRComment($pDRComment, $comparison = null)
    {
        if ($pDRComment instanceof PDRComment) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pDRComment->getPUserId(), $comparison);
        } elseif ($pDRComment instanceof PropelObjectCollection) {
            return $this
                ->usePDRCommentQuery()
                ->filterByPrimaryKeys($pDRComment->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPDRComment() only accepts arguments of type PDRComment or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PDRComment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPDRComment($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PDRComment');

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
            $this->addJoinObject($join, 'PDRComment');
        }

        return $this;
    }

    /**
     * Use the PDRComment relation PDRComment object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PDRCommentQuery A secondary query class using the current class as primary query
     */
    public function usePDRCommentQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPDRComment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PDRComment', '\Politizr\Model\PDRCommentQuery');
    }

    /**
     * Filter the query by a related PUFollowU object
     *
     * @param   PUFollowU|PropelObjectCollection $pUFollowU  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUFollowURelatedByPUserId($pUFollowU, $comparison = null)
    {
        if ($pUFollowU instanceof PUFollowU) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pUFollowU->getPUserId(), $comparison);
        } elseif ($pUFollowU instanceof PropelObjectCollection) {
            return $this
                ->usePUFollowURelatedByPUserIdQuery()
                ->filterByPrimaryKeys($pUFollowU->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPUFollowURelatedByPUserId() only accepts arguments of type PUFollowU or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUFollowURelatedByPUserId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPUFollowURelatedByPUserId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUFollowURelatedByPUserId');

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
            $this->addJoinObject($join, 'PUFollowURelatedByPUserId');
        }

        return $this;
    }

    /**
     * Use the PUFollowURelatedByPUserId relation PUFollowU object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUFollowUQuery A secondary query class using the current class as primary query
     */
    public function usePUFollowURelatedByPUserIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUFollowURelatedByPUserId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUFollowURelatedByPUserId', '\Politizr\Model\PUFollowUQuery');
    }

    /**
     * Filter the query by a related PUFollowU object
     *
     * @param   PUFollowU|PropelObjectCollection $pUFollowU  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PUserQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUFollowURelatedByPUserFollowerId($pUFollowU, $comparison = null)
    {
        if ($pUFollowU instanceof PUFollowU) {
            return $this
                ->addUsingAlias(PUserPeer::ID, $pUFollowU->getPUserFollowerId(), $comparison);
        } elseif ($pUFollowU instanceof PropelObjectCollection) {
            return $this
                ->usePUFollowURelatedByPUserFollowerIdQuery()
                ->filterByPrimaryKeys($pUFollowU->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPUFollowURelatedByPUserFollowerId() only accepts arguments of type PUFollowU or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PUFollowURelatedByPUserFollowerId relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function joinPUFollowURelatedByPUserFollowerId($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PUFollowURelatedByPUserFollowerId');

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
            $this->addJoinObject($join, 'PUFollowURelatedByPUserFollowerId');
        }

        return $this;
    }

    /**
     * Use the PUFollowURelatedByPUserFollowerId relation PUFollowU object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PUFollowUQuery A secondary query class using the current class as primary query
     */
    public function usePUFollowURelatedByPUserFollowerIdQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPUFollowURelatedByPUserFollowerId($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PUFollowURelatedByPUserFollowerId', '\Politizr\Model\PUFollowUQuery');
    }

    /**
     * Filter the query by a related PDDebate object
     * using the p_u_follow_d_d table as cross reference
     *
     * @param   PDDebate $pDDebate the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PUserQuery The current query, for fluid interface
     */
    public function filterByPuFollowDdPDDebate($pDDebate, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePuFollowDdPUserQuery()
            ->filterByPuFollowDdPDDebate($pDDebate, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PRBadge object
     * using the p_u_reputation_r_b table as cross reference
     *
     * @param   PRBadge $pRBadge the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PUserQuery The current query, for fluid interface
     */
    public function filterByPuReputationRbPRBadge($pRBadge, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePuReputationRbPUserQuery()
            ->filterByPuReputationRbPRBadge($pRBadge, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PRAction object
     * using the p_u_reputation_r_a table as cross reference
     *
     * @param   PRAction $pRAction the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PUserQuery The current query, for fluid interface
     */
    public function filterByPuReputationRaPRBadge($pRAction, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePuReputationRaPUserQuery()
            ->filterByPuReputationRaPRBadge($pRAction, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   PUser $pUser Object to remove from the list of results
     *
     * @return PUserQuery The current query, for fluid interface
     */
    public function prune($pUser = null)
    {
        if ($pUser) {
            $this->addUsingAlias(PUserPeer::ID, $pUser->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     PUserQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PUserPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PUserQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUserPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PUserQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUserPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PUserQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PUserPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PUserQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PUserPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PUserQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PUserPeer::CREATED_AT);
    }
    // sluggable behavior

    /**
     * Find one object based on its slug
     *
     * @param     string $slug The value to use as filter.
     * @param     PropelPDO $con The optional connection object
     *
     * @return    PUser the result, formatted by the current formatter
     */
    public function findOneBySlug($slug, $con = null)
    {
        return $this->filterBySlug($slug)->findOne($con);
    }

    // equal_nest_parent behavior

    /**
     * Find equal nest PUFollowUs of the supplied PUser object
     *
     * @param  PUser $pUser * @param  PropelPDO $con
     * @return PUser[]|PropelObjectCollection
     */
    public function findPUFollowUsOf(PUser $pUser, $con = null)
    {
        $obj = clone $pUser;
        $obj->clearListPUFollowUsPKs();
        $obj->clearPUFollowUs();

        return $obj->getPUFollowUs($this, $con);
    }

    /**
     * Count equal nest PUFollowUs of the supplied PUser object
     *
     * @param  PUser $pUser * @param  PropelPDO $con
     * @return integer
     */
    public function countPUFollowUsOf(PUser $pUser, PropelPDO $con = null)
    {
        return $pUser->getPUFollowUs()->count();
    }

}
