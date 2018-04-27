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
use Politizr\Model\PCGroupLC;
use Politizr\Model\PCircle;
use Politizr\Model\PDDebate;
use Politizr\Model\PDReaction;
use Politizr\Model\PEOScopePLC;
use Politizr\Model\PEOperation;
use Politizr\Model\PLCity;
use Politizr\Model\PLCityPeer;
use Politizr\Model\PLCityQuery;
use Politizr\Model\PLDepartment;
use Politizr\Model\PUser;

/**
 * @method PLCityQuery orderById($order = Criteria::ASC) Order by the id column
 * @method PLCityQuery orderByPLDepartmentId($order = Criteria::ASC) Order by the p_l_department_id column
 * @method PLCityQuery orderByName($order = Criteria::ASC) Order by the name column
 * @method PLCityQuery orderByNameSimple($order = Criteria::ASC) Order by the name_simple column
 * @method PLCityQuery orderByNameReal($order = Criteria::ASC) Order by the name_real column
 * @method PLCityQuery orderByNameSoundex($order = Criteria::ASC) Order by the name_soundex column
 * @method PLCityQuery orderByNameMetaphone($order = Criteria::ASC) Order by the name_metaphone column
 * @method PLCityQuery orderByZipcode($order = Criteria::ASC) Order by the zipcode column
 * @method PLCityQuery orderByMunicipality($order = Criteria::ASC) Order by the municipality column
 * @method PLCityQuery orderByMunicipalityCode($order = Criteria::ASC) Order by the municipality_code column
 * @method PLCityQuery orderByDistrict($order = Criteria::ASC) Order by the district column
 * @method PLCityQuery orderByCanton($order = Criteria::ASC) Order by the canton column
 * @method PLCityQuery orderByAmdi($order = Criteria::ASC) Order by the amdi column
 * @method PLCityQuery orderByNbPeople2010($order = Criteria::ASC) Order by the nb_people_2010 column
 * @method PLCityQuery orderByNbPeople1999($order = Criteria::ASC) Order by the nb_people_1999 column
 * @method PLCityQuery orderByNbPeople2012($order = Criteria::ASC) Order by the nb_people_2012 column
 * @method PLCityQuery orderByDensity2010($order = Criteria::ASC) Order by the density_2010 column
 * @method PLCityQuery orderBySurface($order = Criteria::ASC) Order by the surface column
 * @method PLCityQuery orderByLongitudeDeg($order = Criteria::ASC) Order by the longitude_deg column
 * @method PLCityQuery orderByLatitudeDeg($order = Criteria::ASC) Order by the latitude_deg column
 * @method PLCityQuery orderByLongitudeGrd($order = Criteria::ASC) Order by the longitude_grd column
 * @method PLCityQuery orderByLatitudeGrd($order = Criteria::ASC) Order by the latitude_grd column
 * @method PLCityQuery orderByLongitudeDms($order = Criteria::ASC) Order by the longitude_dms column
 * @method PLCityQuery orderByLatitudeDms($order = Criteria::ASC) Order by the latitude_dms column
 * @method PLCityQuery orderByZmin($order = Criteria::ASC) Order by the zmin column
 * @method PLCityQuery orderByZmax($order = Criteria::ASC) Order by the zmax column
 * @method PLCityQuery orderByUuid($order = Criteria::ASC) Order by the uuid column
 * @method PLCityQuery orderByCreatedAt($order = Criteria::ASC) Order by the created_at column
 * @method PLCityQuery orderByUpdatedAt($order = Criteria::ASC) Order by the updated_at column
 * @method PLCityQuery orderBySlug($order = Criteria::ASC) Order by the slug column
 *
 * @method PLCityQuery groupById() Group by the id column
 * @method PLCityQuery groupByPLDepartmentId() Group by the p_l_department_id column
 * @method PLCityQuery groupByName() Group by the name column
 * @method PLCityQuery groupByNameSimple() Group by the name_simple column
 * @method PLCityQuery groupByNameReal() Group by the name_real column
 * @method PLCityQuery groupByNameSoundex() Group by the name_soundex column
 * @method PLCityQuery groupByNameMetaphone() Group by the name_metaphone column
 * @method PLCityQuery groupByZipcode() Group by the zipcode column
 * @method PLCityQuery groupByMunicipality() Group by the municipality column
 * @method PLCityQuery groupByMunicipalityCode() Group by the municipality_code column
 * @method PLCityQuery groupByDistrict() Group by the district column
 * @method PLCityQuery groupByCanton() Group by the canton column
 * @method PLCityQuery groupByAmdi() Group by the amdi column
 * @method PLCityQuery groupByNbPeople2010() Group by the nb_people_2010 column
 * @method PLCityQuery groupByNbPeople1999() Group by the nb_people_1999 column
 * @method PLCityQuery groupByNbPeople2012() Group by the nb_people_2012 column
 * @method PLCityQuery groupByDensity2010() Group by the density_2010 column
 * @method PLCityQuery groupBySurface() Group by the surface column
 * @method PLCityQuery groupByLongitudeDeg() Group by the longitude_deg column
 * @method PLCityQuery groupByLatitudeDeg() Group by the latitude_deg column
 * @method PLCityQuery groupByLongitudeGrd() Group by the longitude_grd column
 * @method PLCityQuery groupByLatitudeGrd() Group by the latitude_grd column
 * @method PLCityQuery groupByLongitudeDms() Group by the longitude_dms column
 * @method PLCityQuery groupByLatitudeDms() Group by the latitude_dms column
 * @method PLCityQuery groupByZmin() Group by the zmin column
 * @method PLCityQuery groupByZmax() Group by the zmax column
 * @method PLCityQuery groupByUuid() Group by the uuid column
 * @method PLCityQuery groupByCreatedAt() Group by the created_at column
 * @method PLCityQuery groupByUpdatedAt() Group by the updated_at column
 * @method PLCityQuery groupBySlug() Group by the slug column
 *
 * @method PLCityQuery leftJoin($relation) Adds a LEFT JOIN clause to the query
 * @method PLCityQuery rightJoin($relation) Adds a RIGHT JOIN clause to the query
 * @method PLCityQuery innerJoin($relation) Adds a INNER JOIN clause to the query
 *
 * @method PLCityQuery leftJoinPLDepartment($relationAlias = null) Adds a LEFT JOIN clause to the query using the PLDepartment relation
 * @method PLCityQuery rightJoinPLDepartment($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PLDepartment relation
 * @method PLCityQuery innerJoinPLDepartment($relationAlias = null) Adds a INNER JOIN clause to the query using the PLDepartment relation
 *
 * @method PLCityQuery leftJoinPEOScopePLC($relationAlias = null) Adds a LEFT JOIN clause to the query using the PEOScopePLC relation
 * @method PLCityQuery rightJoinPEOScopePLC($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PEOScopePLC relation
 * @method PLCityQuery innerJoinPEOScopePLC($relationAlias = null) Adds a INNER JOIN clause to the query using the PEOScopePLC relation
 *
 * @method PLCityQuery leftJoinPUser($relationAlias = null) Adds a LEFT JOIN clause to the query using the PUser relation
 * @method PLCityQuery rightJoinPUser($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PUser relation
 * @method PLCityQuery innerJoinPUser($relationAlias = null) Adds a INNER JOIN clause to the query using the PUser relation
 *
 * @method PLCityQuery leftJoinPDDebate($relationAlias = null) Adds a LEFT JOIN clause to the query using the PDDebate relation
 * @method PLCityQuery rightJoinPDDebate($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PDDebate relation
 * @method PLCityQuery innerJoinPDDebate($relationAlias = null) Adds a INNER JOIN clause to the query using the PDDebate relation
 *
 * @method PLCityQuery leftJoinPDReaction($relationAlias = null) Adds a LEFT JOIN clause to the query using the PDReaction relation
 * @method PLCityQuery rightJoinPDReaction($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PDReaction relation
 * @method PLCityQuery innerJoinPDReaction($relationAlias = null) Adds a INNER JOIN clause to the query using the PDReaction relation
 *
 * @method PLCityQuery leftJoinPCGroupLC($relationAlias = null) Adds a LEFT JOIN clause to the query using the PCGroupLC relation
 * @method PLCityQuery rightJoinPCGroupLC($relationAlias = null) Adds a RIGHT JOIN clause to the query using the PCGroupLC relation
 * @method PLCityQuery innerJoinPCGroupLC($relationAlias = null) Adds a INNER JOIN clause to the query using the PCGroupLC relation
 *
 * @method PLCity findOne(PropelPDO $con = null) Return the first PLCity matching the query
 * @method PLCity findOneOrCreate(PropelPDO $con = null) Return the first PLCity matching the query, or a new PLCity object populated from the query conditions when no match is found
 *
 * @method PLCity findOneByPLDepartmentId(int $p_l_department_id) Return the first PLCity filtered by the p_l_department_id column
 * @method PLCity findOneByName(string $name) Return the first PLCity filtered by the name column
 * @method PLCity findOneByNameSimple(string $name_simple) Return the first PLCity filtered by the name_simple column
 * @method PLCity findOneByNameReal(string $name_real) Return the first PLCity filtered by the name_real column
 * @method PLCity findOneByNameSoundex(string $name_soundex) Return the first PLCity filtered by the name_soundex column
 * @method PLCity findOneByNameMetaphone(string $name_metaphone) Return the first PLCity filtered by the name_metaphone column
 * @method PLCity findOneByZipcode(string $zipcode) Return the first PLCity filtered by the zipcode column
 * @method PLCity findOneByMunicipality(string $municipality) Return the first PLCity filtered by the municipality column
 * @method PLCity findOneByMunicipalityCode(string $municipality_code) Return the first PLCity filtered by the municipality_code column
 * @method PLCity findOneByDistrict(int $district) Return the first PLCity filtered by the district column
 * @method PLCity findOneByCanton(string $canton) Return the first PLCity filtered by the canton column
 * @method PLCity findOneByAmdi(int $amdi) Return the first PLCity filtered by the amdi column
 * @method PLCity findOneByNbPeople2010(int $nb_people_2010) Return the first PLCity filtered by the nb_people_2010 column
 * @method PLCity findOneByNbPeople1999(int $nb_people_1999) Return the first PLCity filtered by the nb_people_1999 column
 * @method PLCity findOneByNbPeople2012(int $nb_people_2012) Return the first PLCity filtered by the nb_people_2012 column
 * @method PLCity findOneByDensity2010(int $density_2010) Return the first PLCity filtered by the density_2010 column
 * @method PLCity findOneBySurface(double $surface) Return the first PLCity filtered by the surface column
 * @method PLCity findOneByLongitudeDeg(double $longitude_deg) Return the first PLCity filtered by the longitude_deg column
 * @method PLCity findOneByLatitudeDeg(double $latitude_deg) Return the first PLCity filtered by the latitude_deg column
 * @method PLCity findOneByLongitudeGrd(string $longitude_grd) Return the first PLCity filtered by the longitude_grd column
 * @method PLCity findOneByLatitudeGrd(string $latitude_grd) Return the first PLCity filtered by the latitude_grd column
 * @method PLCity findOneByLongitudeDms(string $longitude_dms) Return the first PLCity filtered by the longitude_dms column
 * @method PLCity findOneByLatitudeDms(string $latitude_dms) Return the first PLCity filtered by the latitude_dms column
 * @method PLCity findOneByZmin(int $zmin) Return the first PLCity filtered by the zmin column
 * @method PLCity findOneByZmax(int $zmax) Return the first PLCity filtered by the zmax column
 * @method PLCity findOneByUuid(string $uuid) Return the first PLCity filtered by the uuid column
 * @method PLCity findOneByCreatedAt(string $created_at) Return the first PLCity filtered by the created_at column
 * @method PLCity findOneByUpdatedAt(string $updated_at) Return the first PLCity filtered by the updated_at column
 * @method PLCity findOneBySlug(string $slug) Return the first PLCity filtered by the slug column
 *
 * @method array findById(int $id) Return PLCity objects filtered by the id column
 * @method array findByPLDepartmentId(int $p_l_department_id) Return PLCity objects filtered by the p_l_department_id column
 * @method array findByName(string $name) Return PLCity objects filtered by the name column
 * @method array findByNameSimple(string $name_simple) Return PLCity objects filtered by the name_simple column
 * @method array findByNameReal(string $name_real) Return PLCity objects filtered by the name_real column
 * @method array findByNameSoundex(string $name_soundex) Return PLCity objects filtered by the name_soundex column
 * @method array findByNameMetaphone(string $name_metaphone) Return PLCity objects filtered by the name_metaphone column
 * @method array findByZipcode(string $zipcode) Return PLCity objects filtered by the zipcode column
 * @method array findByMunicipality(string $municipality) Return PLCity objects filtered by the municipality column
 * @method array findByMunicipalityCode(string $municipality_code) Return PLCity objects filtered by the municipality_code column
 * @method array findByDistrict(int $district) Return PLCity objects filtered by the district column
 * @method array findByCanton(string $canton) Return PLCity objects filtered by the canton column
 * @method array findByAmdi(int $amdi) Return PLCity objects filtered by the amdi column
 * @method array findByNbPeople2010(int $nb_people_2010) Return PLCity objects filtered by the nb_people_2010 column
 * @method array findByNbPeople1999(int $nb_people_1999) Return PLCity objects filtered by the nb_people_1999 column
 * @method array findByNbPeople2012(int $nb_people_2012) Return PLCity objects filtered by the nb_people_2012 column
 * @method array findByDensity2010(int $density_2010) Return PLCity objects filtered by the density_2010 column
 * @method array findBySurface(double $surface) Return PLCity objects filtered by the surface column
 * @method array findByLongitudeDeg(double $longitude_deg) Return PLCity objects filtered by the longitude_deg column
 * @method array findByLatitudeDeg(double $latitude_deg) Return PLCity objects filtered by the latitude_deg column
 * @method array findByLongitudeGrd(string $longitude_grd) Return PLCity objects filtered by the longitude_grd column
 * @method array findByLatitudeGrd(string $latitude_grd) Return PLCity objects filtered by the latitude_grd column
 * @method array findByLongitudeDms(string $longitude_dms) Return PLCity objects filtered by the longitude_dms column
 * @method array findByLatitudeDms(string $latitude_dms) Return PLCity objects filtered by the latitude_dms column
 * @method array findByZmin(int $zmin) Return PLCity objects filtered by the zmin column
 * @method array findByZmax(int $zmax) Return PLCity objects filtered by the zmax column
 * @method array findByUuid(string $uuid) Return PLCity objects filtered by the uuid column
 * @method array findByCreatedAt(string $created_at) Return PLCity objects filtered by the created_at column
 * @method array findByUpdatedAt(string $updated_at) Return PLCity objects filtered by the updated_at column
 * @method array findBySlug(string $slug) Return PLCity objects filtered by the slug column
 */
abstract class BasePLCityQuery extends ModelCriteria
{
    // query_cache behavior
    protected $queryKey = '';

    /**
     * Initializes internal state of BasePLCityQuery object.
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
            $modelName = 'Politizr\\Model\\PLCity';
        }
        parent::__construct($dbName, $modelName, $modelAlias);
    }

    /**
     * Returns a new PLCityQuery object.
     *
     * @param     string $modelAlias The alias of a model in the query
     * @param   PLCityQuery|Criteria $criteria Optional Criteria to build the query from
     *
     * @return PLCityQuery
     */
    public static function create($modelAlias = null, $criteria = null)
    {
        if ($criteria instanceof PLCityQuery) {
            return $criteria;
        }
        $query = new PLCityQuery(null, null, $modelAlias);

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
     * @return   PLCity|PLCity[]|mixed the result, formatted by the current formatter
     */
    public function findPk($key, $con = null)
    {
        if ($key === null) {
            return null;
        }
        if ((null !== ($obj = PLCityPeer::getInstanceFromPool((string) $key))) && !$this->formatter) {
            // the object is already in the instance pool
            return $obj;
        }
        if ($con === null) {
            $con = Propel::getConnection(PLCityPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PLCity A model object, or null if the key is not found
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
     * @return                 PLCity A model object, or null if the key is not found
     * @throws PropelException
     */
    protected function findPkSimple($key, $con)
    {
        $sql = 'SELECT `id`, `p_l_department_id`, `name`, `name_simple`, `name_real`, `name_soundex`, `name_metaphone`, `zipcode`, `municipality`, `municipality_code`, `district`, `canton`, `amdi`, `nb_people_2010`, `nb_people_1999`, `nb_people_2012`, `density_2010`, `surface`, `longitude_deg`, `latitude_deg`, `longitude_grd`, `latitude_grd`, `longitude_dms`, `latitude_dms`, `zmin`, `zmax`, `uuid`, `created_at`, `updated_at`, `slug` FROM `p_l_city` WHERE `id` = :p0';
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
            $obj = new PLCity();
            $obj->hydrate($row);
            PLCityPeer::addInstanceToPool($obj, (string) $key);
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
     * @return PLCity|PLCity[]|mixed the result, formatted by the current formatter
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
     * @return PropelObjectCollection|PLCity[]|mixed the list of results, formatted by the current formatter
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
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByPrimaryKey($key)
    {

        return $this->addUsingAlias(PLCityPeer::ID, $key, Criteria::EQUAL);
    }

    /**
     * Filter the query by a list of primary keys
     *
     * @param     array $keys The list of primary key to use for the query
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByPrimaryKeys($keys)
    {

        return $this->addUsingAlias(PLCityPeer::ID, $keys, Criteria::IN);
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
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterById($id = null, $comparison = null)
    {
        if (is_array($id)) {
            $useMinMax = false;
            if (isset($id['min'])) {
                $this->addUsingAlias(PLCityPeer::ID, $id['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($id['max'])) {
                $this->addUsingAlias(PLCityPeer::ID, $id['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PLCityPeer::ID, $id, $comparison);
    }

    /**
     * Filter the query on the p_l_department_id column
     *
     * Example usage:
     * <code>
     * $query->filterByPLDepartmentId(1234); // WHERE p_l_department_id = 1234
     * $query->filterByPLDepartmentId(array(12, 34)); // WHERE p_l_department_id IN (12, 34)
     * $query->filterByPLDepartmentId(array('min' => 12)); // WHERE p_l_department_id >= 12
     * $query->filterByPLDepartmentId(array('max' => 12)); // WHERE p_l_department_id <= 12
     * </code>
     *
     * @see       filterByPLDepartment()
     *
     * @param     mixed $pLDepartmentId The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByPLDepartmentId($pLDepartmentId = null, $comparison = null)
    {
        if (is_array($pLDepartmentId)) {
            $useMinMax = false;
            if (isset($pLDepartmentId['min'])) {
                $this->addUsingAlias(PLCityPeer::P_L_DEPARTMENT_ID, $pLDepartmentId['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($pLDepartmentId['max'])) {
                $this->addUsingAlias(PLCityPeer::P_L_DEPARTMENT_ID, $pLDepartmentId['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PLCityPeer::P_L_DEPARTMENT_ID, $pLDepartmentId, $comparison);
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
     * @return PLCityQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PLCityPeer::NAME, $name, $comparison);
    }

    /**
     * Filter the query on the name_simple column
     *
     * Example usage:
     * <code>
     * $query->filterByNameSimple('fooValue');   // WHERE name_simple = 'fooValue'
     * $query->filterByNameSimple('%fooValue%'); // WHERE name_simple LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nameSimple The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByNameSimple($nameSimple = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nameSimple)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $nameSimple)) {
                $nameSimple = str_replace('*', '%', $nameSimple);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PLCityPeer::NAME_SIMPLE, $nameSimple, $comparison);
    }

    /**
     * Filter the query on the name_real column
     *
     * Example usage:
     * <code>
     * $query->filterByNameReal('fooValue');   // WHERE name_real = 'fooValue'
     * $query->filterByNameReal('%fooValue%'); // WHERE name_real LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nameReal The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByNameReal($nameReal = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nameReal)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $nameReal)) {
                $nameReal = str_replace('*', '%', $nameReal);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PLCityPeer::NAME_REAL, $nameReal, $comparison);
    }

    /**
     * Filter the query on the name_soundex column
     *
     * Example usage:
     * <code>
     * $query->filterByNameSoundex('fooValue');   // WHERE name_soundex = 'fooValue'
     * $query->filterByNameSoundex('%fooValue%'); // WHERE name_soundex LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nameSoundex The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByNameSoundex($nameSoundex = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nameSoundex)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $nameSoundex)) {
                $nameSoundex = str_replace('*', '%', $nameSoundex);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PLCityPeer::NAME_SOUNDEX, $nameSoundex, $comparison);
    }

    /**
     * Filter the query on the name_metaphone column
     *
     * Example usage:
     * <code>
     * $query->filterByNameMetaphone('fooValue');   // WHERE name_metaphone = 'fooValue'
     * $query->filterByNameMetaphone('%fooValue%'); // WHERE name_metaphone LIKE '%fooValue%'
     * </code>
     *
     * @param     string $nameMetaphone The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByNameMetaphone($nameMetaphone = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($nameMetaphone)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $nameMetaphone)) {
                $nameMetaphone = str_replace('*', '%', $nameMetaphone);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PLCityPeer::NAME_METAPHONE, $nameMetaphone, $comparison);
    }

    /**
     * Filter the query on the zipcode column
     *
     * Example usage:
     * <code>
     * $query->filterByZipcode('fooValue');   // WHERE zipcode = 'fooValue'
     * $query->filterByZipcode('%fooValue%'); // WHERE zipcode LIKE '%fooValue%'
     * </code>
     *
     * @param     string $zipcode The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByZipcode($zipcode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($zipcode)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $zipcode)) {
                $zipcode = str_replace('*', '%', $zipcode);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PLCityPeer::ZIPCODE, $zipcode, $comparison);
    }

    /**
     * Filter the query on the municipality column
     *
     * Example usage:
     * <code>
     * $query->filterByMunicipality('fooValue');   // WHERE municipality = 'fooValue'
     * $query->filterByMunicipality('%fooValue%'); // WHERE municipality LIKE '%fooValue%'
     * </code>
     *
     * @param     string $municipality The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByMunicipality($municipality = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($municipality)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $municipality)) {
                $municipality = str_replace('*', '%', $municipality);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PLCityPeer::MUNICIPALITY, $municipality, $comparison);
    }

    /**
     * Filter the query on the municipality_code column
     *
     * Example usage:
     * <code>
     * $query->filterByMunicipalityCode('fooValue');   // WHERE municipality_code = 'fooValue'
     * $query->filterByMunicipalityCode('%fooValue%'); // WHERE municipality_code LIKE '%fooValue%'
     * </code>
     *
     * @param     string $municipalityCode The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByMunicipalityCode($municipalityCode = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($municipalityCode)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $municipalityCode)) {
                $municipalityCode = str_replace('*', '%', $municipalityCode);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PLCityPeer::MUNICIPALITY_CODE, $municipalityCode, $comparison);
    }

    /**
     * Filter the query on the district column
     *
     * Example usage:
     * <code>
     * $query->filterByDistrict(1234); // WHERE district = 1234
     * $query->filterByDistrict(array(12, 34)); // WHERE district IN (12, 34)
     * $query->filterByDistrict(array('min' => 12)); // WHERE district >= 12
     * $query->filterByDistrict(array('max' => 12)); // WHERE district <= 12
     * </code>
     *
     * @param     mixed $district The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByDistrict($district = null, $comparison = null)
    {
        if (is_array($district)) {
            $useMinMax = false;
            if (isset($district['min'])) {
                $this->addUsingAlias(PLCityPeer::DISTRICT, $district['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($district['max'])) {
                $this->addUsingAlias(PLCityPeer::DISTRICT, $district['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PLCityPeer::DISTRICT, $district, $comparison);
    }

    /**
     * Filter the query on the canton column
     *
     * Example usage:
     * <code>
     * $query->filterByCanton('fooValue');   // WHERE canton = 'fooValue'
     * $query->filterByCanton('%fooValue%'); // WHERE canton LIKE '%fooValue%'
     * </code>
     *
     * @param     string $canton The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByCanton($canton = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($canton)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $canton)) {
                $canton = str_replace('*', '%', $canton);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PLCityPeer::CANTON, $canton, $comparison);
    }

    /**
     * Filter the query on the amdi column
     *
     * Example usage:
     * <code>
     * $query->filterByAmdi(1234); // WHERE amdi = 1234
     * $query->filterByAmdi(array(12, 34)); // WHERE amdi IN (12, 34)
     * $query->filterByAmdi(array('min' => 12)); // WHERE amdi >= 12
     * $query->filterByAmdi(array('max' => 12)); // WHERE amdi <= 12
     * </code>
     *
     * @param     mixed $amdi The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByAmdi($amdi = null, $comparison = null)
    {
        if (is_array($amdi)) {
            $useMinMax = false;
            if (isset($amdi['min'])) {
                $this->addUsingAlias(PLCityPeer::AMDI, $amdi['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($amdi['max'])) {
                $this->addUsingAlias(PLCityPeer::AMDI, $amdi['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PLCityPeer::AMDI, $amdi, $comparison);
    }

    /**
     * Filter the query on the nb_people_2010 column
     *
     * Example usage:
     * <code>
     * $query->filterByNbPeople2010(1234); // WHERE nb_people_2010 = 1234
     * $query->filterByNbPeople2010(array(12, 34)); // WHERE nb_people_2010 IN (12, 34)
     * $query->filterByNbPeople2010(array('min' => 12)); // WHERE nb_people_2010 >= 12
     * $query->filterByNbPeople2010(array('max' => 12)); // WHERE nb_people_2010 <= 12
     * </code>
     *
     * @param     mixed $nbPeople2010 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByNbPeople2010($nbPeople2010 = null, $comparison = null)
    {
        if (is_array($nbPeople2010)) {
            $useMinMax = false;
            if (isset($nbPeople2010['min'])) {
                $this->addUsingAlias(PLCityPeer::NB_PEOPLE_2010, $nbPeople2010['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nbPeople2010['max'])) {
                $this->addUsingAlias(PLCityPeer::NB_PEOPLE_2010, $nbPeople2010['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PLCityPeer::NB_PEOPLE_2010, $nbPeople2010, $comparison);
    }

    /**
     * Filter the query on the nb_people_1999 column
     *
     * Example usage:
     * <code>
     * $query->filterByNbPeople1999(1234); // WHERE nb_people_1999 = 1234
     * $query->filterByNbPeople1999(array(12, 34)); // WHERE nb_people_1999 IN (12, 34)
     * $query->filterByNbPeople1999(array('min' => 12)); // WHERE nb_people_1999 >= 12
     * $query->filterByNbPeople1999(array('max' => 12)); // WHERE nb_people_1999 <= 12
     * </code>
     *
     * @param     mixed $nbPeople1999 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByNbPeople1999($nbPeople1999 = null, $comparison = null)
    {
        if (is_array($nbPeople1999)) {
            $useMinMax = false;
            if (isset($nbPeople1999['min'])) {
                $this->addUsingAlias(PLCityPeer::NB_PEOPLE_1999, $nbPeople1999['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nbPeople1999['max'])) {
                $this->addUsingAlias(PLCityPeer::NB_PEOPLE_1999, $nbPeople1999['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PLCityPeer::NB_PEOPLE_1999, $nbPeople1999, $comparison);
    }

    /**
     * Filter the query on the nb_people_2012 column
     *
     * Example usage:
     * <code>
     * $query->filterByNbPeople2012(1234); // WHERE nb_people_2012 = 1234
     * $query->filterByNbPeople2012(array(12, 34)); // WHERE nb_people_2012 IN (12, 34)
     * $query->filterByNbPeople2012(array('min' => 12)); // WHERE nb_people_2012 >= 12
     * $query->filterByNbPeople2012(array('max' => 12)); // WHERE nb_people_2012 <= 12
     * </code>
     *
     * @param     mixed $nbPeople2012 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByNbPeople2012($nbPeople2012 = null, $comparison = null)
    {
        if (is_array($nbPeople2012)) {
            $useMinMax = false;
            if (isset($nbPeople2012['min'])) {
                $this->addUsingAlias(PLCityPeer::NB_PEOPLE_2012, $nbPeople2012['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($nbPeople2012['max'])) {
                $this->addUsingAlias(PLCityPeer::NB_PEOPLE_2012, $nbPeople2012['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PLCityPeer::NB_PEOPLE_2012, $nbPeople2012, $comparison);
    }

    /**
     * Filter the query on the density_2010 column
     *
     * Example usage:
     * <code>
     * $query->filterByDensity2010(1234); // WHERE density_2010 = 1234
     * $query->filterByDensity2010(array(12, 34)); // WHERE density_2010 IN (12, 34)
     * $query->filterByDensity2010(array('min' => 12)); // WHERE density_2010 >= 12
     * $query->filterByDensity2010(array('max' => 12)); // WHERE density_2010 <= 12
     * </code>
     *
     * @param     mixed $density2010 The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByDensity2010($density2010 = null, $comparison = null)
    {
        if (is_array($density2010)) {
            $useMinMax = false;
            if (isset($density2010['min'])) {
                $this->addUsingAlias(PLCityPeer::DENSITY_2010, $density2010['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($density2010['max'])) {
                $this->addUsingAlias(PLCityPeer::DENSITY_2010, $density2010['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PLCityPeer::DENSITY_2010, $density2010, $comparison);
    }

    /**
     * Filter the query on the surface column
     *
     * Example usage:
     * <code>
     * $query->filterBySurface(1234); // WHERE surface = 1234
     * $query->filterBySurface(array(12, 34)); // WHERE surface IN (12, 34)
     * $query->filterBySurface(array('min' => 12)); // WHERE surface >= 12
     * $query->filterBySurface(array('max' => 12)); // WHERE surface <= 12
     * </code>
     *
     * @param     mixed $surface The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterBySurface($surface = null, $comparison = null)
    {
        if (is_array($surface)) {
            $useMinMax = false;
            if (isset($surface['min'])) {
                $this->addUsingAlias(PLCityPeer::SURFACE, $surface['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($surface['max'])) {
                $this->addUsingAlias(PLCityPeer::SURFACE, $surface['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PLCityPeer::SURFACE, $surface, $comparison);
    }

    /**
     * Filter the query on the longitude_deg column
     *
     * Example usage:
     * <code>
     * $query->filterByLongitudeDeg(1234); // WHERE longitude_deg = 1234
     * $query->filterByLongitudeDeg(array(12, 34)); // WHERE longitude_deg IN (12, 34)
     * $query->filterByLongitudeDeg(array('min' => 12)); // WHERE longitude_deg >= 12
     * $query->filterByLongitudeDeg(array('max' => 12)); // WHERE longitude_deg <= 12
     * </code>
     *
     * @param     mixed $longitudeDeg The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByLongitudeDeg($longitudeDeg = null, $comparison = null)
    {
        if (is_array($longitudeDeg)) {
            $useMinMax = false;
            if (isset($longitudeDeg['min'])) {
                $this->addUsingAlias(PLCityPeer::LONGITUDE_DEG, $longitudeDeg['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($longitudeDeg['max'])) {
                $this->addUsingAlias(PLCityPeer::LONGITUDE_DEG, $longitudeDeg['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PLCityPeer::LONGITUDE_DEG, $longitudeDeg, $comparison);
    }

    /**
     * Filter the query on the latitude_deg column
     *
     * Example usage:
     * <code>
     * $query->filterByLatitudeDeg(1234); // WHERE latitude_deg = 1234
     * $query->filterByLatitudeDeg(array(12, 34)); // WHERE latitude_deg IN (12, 34)
     * $query->filterByLatitudeDeg(array('min' => 12)); // WHERE latitude_deg >= 12
     * $query->filterByLatitudeDeg(array('max' => 12)); // WHERE latitude_deg <= 12
     * </code>
     *
     * @param     mixed $latitudeDeg The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByLatitudeDeg($latitudeDeg = null, $comparison = null)
    {
        if (is_array($latitudeDeg)) {
            $useMinMax = false;
            if (isset($latitudeDeg['min'])) {
                $this->addUsingAlias(PLCityPeer::LATITUDE_DEG, $latitudeDeg['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($latitudeDeg['max'])) {
                $this->addUsingAlias(PLCityPeer::LATITUDE_DEG, $latitudeDeg['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PLCityPeer::LATITUDE_DEG, $latitudeDeg, $comparison);
    }

    /**
     * Filter the query on the longitude_grd column
     *
     * Example usage:
     * <code>
     * $query->filterByLongitudeGrd('fooValue');   // WHERE longitude_grd = 'fooValue'
     * $query->filterByLongitudeGrd('%fooValue%'); // WHERE longitude_grd LIKE '%fooValue%'
     * </code>
     *
     * @param     string $longitudeGrd The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByLongitudeGrd($longitudeGrd = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($longitudeGrd)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $longitudeGrd)) {
                $longitudeGrd = str_replace('*', '%', $longitudeGrd);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PLCityPeer::LONGITUDE_GRD, $longitudeGrd, $comparison);
    }

    /**
     * Filter the query on the latitude_grd column
     *
     * Example usage:
     * <code>
     * $query->filterByLatitudeGrd('fooValue');   // WHERE latitude_grd = 'fooValue'
     * $query->filterByLatitudeGrd('%fooValue%'); // WHERE latitude_grd LIKE '%fooValue%'
     * </code>
     *
     * @param     string $latitudeGrd The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByLatitudeGrd($latitudeGrd = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($latitudeGrd)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $latitudeGrd)) {
                $latitudeGrd = str_replace('*', '%', $latitudeGrd);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PLCityPeer::LATITUDE_GRD, $latitudeGrd, $comparison);
    }

    /**
     * Filter the query on the longitude_dms column
     *
     * Example usage:
     * <code>
     * $query->filterByLongitudeDms('fooValue');   // WHERE longitude_dms = 'fooValue'
     * $query->filterByLongitudeDms('%fooValue%'); // WHERE longitude_dms LIKE '%fooValue%'
     * </code>
     *
     * @param     string $longitudeDms The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByLongitudeDms($longitudeDms = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($longitudeDms)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $longitudeDms)) {
                $longitudeDms = str_replace('*', '%', $longitudeDms);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PLCityPeer::LONGITUDE_DMS, $longitudeDms, $comparison);
    }

    /**
     * Filter the query on the latitude_dms column
     *
     * Example usage:
     * <code>
     * $query->filterByLatitudeDms('fooValue');   // WHERE latitude_dms = 'fooValue'
     * $query->filterByLatitudeDms('%fooValue%'); // WHERE latitude_dms LIKE '%fooValue%'
     * </code>
     *
     * @param     string $latitudeDms The value to use as filter.
     *              Accepts wildcards (* and % trigger a LIKE)
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByLatitudeDms($latitudeDms = null, $comparison = null)
    {
        if (null === $comparison) {
            if (is_array($latitudeDms)) {
                $comparison = Criteria::IN;
            } elseif (preg_match('/[\%\*]/', $latitudeDms)) {
                $latitudeDms = str_replace('*', '%', $latitudeDms);
                $comparison = Criteria::LIKE;
            }
        }

        return $this->addUsingAlias(PLCityPeer::LATITUDE_DMS, $latitudeDms, $comparison);
    }

    /**
     * Filter the query on the zmin column
     *
     * Example usage:
     * <code>
     * $query->filterByZmin(1234); // WHERE zmin = 1234
     * $query->filterByZmin(array(12, 34)); // WHERE zmin IN (12, 34)
     * $query->filterByZmin(array('min' => 12)); // WHERE zmin >= 12
     * $query->filterByZmin(array('max' => 12)); // WHERE zmin <= 12
     * </code>
     *
     * @param     mixed $zmin The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByZmin($zmin = null, $comparison = null)
    {
        if (is_array($zmin)) {
            $useMinMax = false;
            if (isset($zmin['min'])) {
                $this->addUsingAlias(PLCityPeer::ZMIN, $zmin['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($zmin['max'])) {
                $this->addUsingAlias(PLCityPeer::ZMIN, $zmin['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PLCityPeer::ZMIN, $zmin, $comparison);
    }

    /**
     * Filter the query on the zmax column
     *
     * Example usage:
     * <code>
     * $query->filterByZmax(1234); // WHERE zmax = 1234
     * $query->filterByZmax(array(12, 34)); // WHERE zmax IN (12, 34)
     * $query->filterByZmax(array('min' => 12)); // WHERE zmax >= 12
     * $query->filterByZmax(array('max' => 12)); // WHERE zmax <= 12
     * </code>
     *
     * @param     mixed $zmax The value to use as filter.
     *              Use scalar values for equality.
     *              Use array values for in_array() equivalent.
     *              Use associative array('min' => $minValue, 'max' => $maxValue) for intervals.
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByZmax($zmax = null, $comparison = null)
    {
        if (is_array($zmax)) {
            $useMinMax = false;
            if (isset($zmax['min'])) {
                $this->addUsingAlias(PLCityPeer::ZMAX, $zmax['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($zmax['max'])) {
                $this->addUsingAlias(PLCityPeer::ZMAX, $zmax['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PLCityPeer::ZMAX, $zmax, $comparison);
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
     * @return PLCityQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PLCityPeer::UUID, $uuid, $comparison);
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
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByCreatedAt($createdAt = null, $comparison = null)
    {
        if (is_array($createdAt)) {
            $useMinMax = false;
            if (isset($createdAt['min'])) {
                $this->addUsingAlias(PLCityPeer::CREATED_AT, $createdAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($createdAt['max'])) {
                $this->addUsingAlias(PLCityPeer::CREATED_AT, $createdAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PLCityPeer::CREATED_AT, $createdAt, $comparison);
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
     * @return PLCityQuery The current query, for fluid interface
     */
    public function filterByUpdatedAt($updatedAt = null, $comparison = null)
    {
        if (is_array($updatedAt)) {
            $useMinMax = false;
            if (isset($updatedAt['min'])) {
                $this->addUsingAlias(PLCityPeer::UPDATED_AT, $updatedAt['min'], Criteria::GREATER_EQUAL);
                $useMinMax = true;
            }
            if (isset($updatedAt['max'])) {
                $this->addUsingAlias(PLCityPeer::UPDATED_AT, $updatedAt['max'], Criteria::LESS_EQUAL);
                $useMinMax = true;
            }
            if ($useMinMax) {
                return $this;
            }
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }
        }

        return $this->addUsingAlias(PLCityPeer::UPDATED_AT, $updatedAt, $comparison);
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
     * @return PLCityQuery The current query, for fluid interface
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

        return $this->addUsingAlias(PLCityPeer::SLUG, $slug, $comparison);
    }

    /**
     * Filter the query by a related PLDepartment object
     *
     * @param   PLDepartment|PropelObjectCollection $pLDepartment The related object(s) to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PLCityQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPLDepartment($pLDepartment, $comparison = null)
    {
        if ($pLDepartment instanceof PLDepartment) {
            return $this
                ->addUsingAlias(PLCityPeer::P_L_DEPARTMENT_ID, $pLDepartment->getId(), $comparison);
        } elseif ($pLDepartment instanceof PropelObjectCollection) {
            if (null === $comparison) {
                $comparison = Criteria::IN;
            }

            return $this
                ->addUsingAlias(PLCityPeer::P_L_DEPARTMENT_ID, $pLDepartment->toKeyValue('PrimaryKey', 'Id'), $comparison);
        } else {
            throw new PropelException('filterByPLDepartment() only accepts arguments of type PLDepartment or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PLDepartment relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function joinPLDepartment($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PLDepartment');

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
            $this->addJoinObject($join, 'PLDepartment');
        }

        return $this;
    }

    /**
     * Use the PLDepartment relation PLDepartment object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PLDepartmentQuery A secondary query class using the current class as primary query
     */
    public function usePLDepartmentQuery($relationAlias = null, $joinType = Criteria::LEFT_JOIN)
    {
        return $this
            ->joinPLDepartment($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PLDepartment', '\Politizr\Model\PLDepartmentQuery');
    }

    /**
     * Filter the query by a related PEOScopePLC object
     *
     * @param   PEOScopePLC|PropelObjectCollection $pEOScopePLC  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PLCityQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPEOScopePLC($pEOScopePLC, $comparison = null)
    {
        if ($pEOScopePLC instanceof PEOScopePLC) {
            return $this
                ->addUsingAlias(PLCityPeer::ID, $pEOScopePLC->getPLCityId(), $comparison);
        } elseif ($pEOScopePLC instanceof PropelObjectCollection) {
            return $this
                ->usePEOScopePLCQuery()
                ->filterByPrimaryKeys($pEOScopePLC->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPEOScopePLC() only accepts arguments of type PEOScopePLC or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PEOScopePLC relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function joinPEOScopePLC($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PEOScopePLC');

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
            $this->addJoinObject($join, 'PEOScopePLC');
        }

        return $this;
    }

    /**
     * Use the PEOScopePLC relation PEOScopePLC object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PEOScopePLCQuery A secondary query class using the current class as primary query
     */
    public function usePEOScopePLCQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPEOScopePLC($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PEOScopePLC', '\Politizr\Model\PEOScopePLCQuery');
    }

    /**
     * Filter the query by a related PUser object
     *
     * @param   PUser|PropelObjectCollection $pUser  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PLCityQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPUser($pUser, $comparison = null)
    {
        if ($pUser instanceof PUser) {
            return $this
                ->addUsingAlias(PLCityPeer::ID, $pUser->getPLCityId(), $comparison);
        } elseif ($pUser instanceof PropelObjectCollection) {
            return $this
                ->usePUserQuery()
                ->filterByPrimaryKeys($pUser->getPrimaryKeys())
                ->endUse();
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
     * @return PLCityQuery The current query, for fluid interface
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
     * @param   PDDebate|PropelObjectCollection $pDDebate  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PLCityQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPDDebate($pDDebate, $comparison = null)
    {
        if ($pDDebate instanceof PDDebate) {
            return $this
                ->addUsingAlias(PLCityPeer::ID, $pDDebate->getPLCityId(), $comparison);
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
     * @return PLCityQuery The current query, for fluid interface
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
     * Filter the query by a related PDReaction object
     *
     * @param   PDReaction|PropelObjectCollection $pDReaction  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PLCityQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPDReaction($pDReaction, $comparison = null)
    {
        if ($pDReaction instanceof PDReaction) {
            return $this
                ->addUsingAlias(PLCityPeer::ID, $pDReaction->getPLCityId(), $comparison);
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
     * @return PLCityQuery The current query, for fluid interface
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
     * Filter the query by a related PCGroupLC object
     *
     * @param   PCGroupLC|PropelObjectCollection $pCGroupLC  the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return                 PLCityQuery The current query, for fluid interface
     * @throws PropelException - if the provided filter is invalid.
     */
    public function filterByPCGroupLC($pCGroupLC, $comparison = null)
    {
        if ($pCGroupLC instanceof PCGroupLC) {
            return $this
                ->addUsingAlias(PLCityPeer::ID, $pCGroupLC->getPLCityId(), $comparison);
        } elseif ($pCGroupLC instanceof PropelObjectCollection) {
            return $this
                ->usePCGroupLCQuery()
                ->filterByPrimaryKeys($pCGroupLC->getPrimaryKeys())
                ->endUse();
        } else {
            throw new PropelException('filterByPCGroupLC() only accepts arguments of type PCGroupLC or PropelCollection');
        }
    }

    /**
     * Adds a JOIN clause to the query using the PCGroupLC relation
     *
     * @param     string $relationAlias optional alias for the relation
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function joinPCGroupLC($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        $tableMap = $this->getTableMap();
        $relationMap = $tableMap->getRelation('PCGroupLC');

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
            $this->addJoinObject($join, 'PCGroupLC');
        }

        return $this;
    }

    /**
     * Use the PCGroupLC relation PCGroupLC object
     *
     * @see       useQuery()
     *
     * @param     string $relationAlias optional alias for the relation,
     *                                   to be used as main alias in the secondary query
     * @param     string $joinType Accepted values are null, 'left join', 'right join', 'inner join'
     *
     * @return   \Politizr\Model\PCGroupLCQuery A secondary query class using the current class as primary query
     */
    public function usePCGroupLCQuery($relationAlias = null, $joinType = Criteria::INNER_JOIN)
    {
        return $this
            ->joinPCGroupLC($relationAlias, $joinType)
            ->useQuery($relationAlias ? $relationAlias : 'PCGroupLC', '\Politizr\Model\PCGroupLCQuery');
    }

    /**
     * Filter the query by a related PEOperation object
     * using the p_e_o_scope_p_l_c table as cross reference
     *
     * @param   PEOperation $pEOperation the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PLCityQuery The current query, for fluid interface
     */
    public function filterByPEOperation($pEOperation, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePEOScopePLCQuery()
            ->filterByPEOperation($pEOperation, $comparison)
            ->endUse();
    }

    /**
     * Filter the query by a related PCircle object
     * using the p_c_group_l_c table as cross reference
     *
     * @param   PCircle $pCircle the related object to use as filter
     * @param     string $comparison Operator to use for the column comparison, defaults to Criteria::EQUAL
     *
     * @return   PLCityQuery The current query, for fluid interface
     */
    public function filterByPCircle($pCircle, $comparison = Criteria::EQUAL)
    {
        return $this
            ->usePCGroupLCQuery()
            ->filterByPCircle($pCircle, $comparison)
            ->endUse();
    }

    /**
     * Exclude object from result
     *
     * @param   PLCity $pLCity Object to remove from the list of results
     *
     * @return PLCityQuery The current query, for fluid interface
     */
    public function prune($pLCity = null)
    {
        if ($pLCity) {
            $this->addUsingAlias(PLCityPeer::ID, $pLCity->getId(), Criteria::NOT_EQUAL);
        }

        return $this;
    }

    // timestampable behavior

    /**
     * Filter by the latest updated
     *
     * @param      int $nbDays Maximum age of the latest update in days
     *
     * @return     PLCityQuery The current query, for fluid interface
     */
    public function recentlyUpdated($nbDays = 7)
    {
        return $this->addUsingAlias(PLCityPeer::UPDATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by update date desc
     *
     * @return     PLCityQuery The current query, for fluid interface
     */
    public function lastUpdatedFirst()
    {
        return $this->addDescendingOrderByColumn(PLCityPeer::UPDATED_AT);
    }

    /**
     * Order by update date asc
     *
     * @return     PLCityQuery The current query, for fluid interface
     */
    public function firstUpdatedFirst()
    {
        return $this->addAscendingOrderByColumn(PLCityPeer::UPDATED_AT);
    }

    /**
     * Filter by the latest created
     *
     * @param      int $nbDays Maximum age of in days
     *
     * @return     PLCityQuery The current query, for fluid interface
     */
    public function recentlyCreated($nbDays = 7)
    {
        return $this->addUsingAlias(PLCityPeer::CREATED_AT, time() - $nbDays * 24 * 60 * 60, Criteria::GREATER_EQUAL);
    }

    /**
     * Order by create date desc
     *
     * @return     PLCityQuery The current query, for fluid interface
     */
    public function lastCreatedFirst()
    {
        return $this->addDescendingOrderByColumn(PLCityPeer::CREATED_AT);
    }

    /**
     * Order by create date asc
     *
     * @return     PLCityQuery The current query, for fluid interface
     */
    public function firstCreatedFirst()
    {
        return $this->addAscendingOrderByColumn(PLCityPeer::CREATED_AT);
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

        $dbMap = Propel::getDatabaseMap(PLCityPeer::DATABASE_NAME);
        $db = Propel::getDB(PLCityPeer::DATABASE_NAME);

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

}
