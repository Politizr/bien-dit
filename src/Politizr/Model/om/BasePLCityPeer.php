<?php

namespace Politizr\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Politizr\Model\PDDebatePeer;
use Politizr\Model\PDReactionPeer;
use Politizr\Model\PLCity;
use Politizr\Model\PLCityPeer;
use Politizr\Model\PLDepartmentPeer;
use Politizr\Model\PTScopePLCPeer;
use Politizr\Model\PUserPeer;
use Politizr\Model\map\PLCityTableMap;

abstract class BasePLCityPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'p_l_city';

    /** the related Propel class for this table */
    const OM_CLASS = 'Politizr\\Model\\PLCity';

    /** the related TableMap class for this table */
    const TM_CLASS = 'Politizr\\Model\\map\\PLCityTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 30;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 30;

    /** the column name for the id field */
    const ID = 'p_l_city.id';

    /** the column name for the p_l_department_id field */
    const P_L_DEPARTMENT_ID = 'p_l_city.p_l_department_id';

    /** the column name for the name field */
    const NAME = 'p_l_city.name';

    /** the column name for the name_simple field */
    const NAME_SIMPLE = 'p_l_city.name_simple';

    /** the column name for the name_real field */
    const NAME_REAL = 'p_l_city.name_real';

    /** the column name for the name_soundex field */
    const NAME_SOUNDEX = 'p_l_city.name_soundex';

    /** the column name for the name_metaphone field */
    const NAME_METAPHONE = 'p_l_city.name_metaphone';

    /** the column name for the zipcode field */
    const ZIPCODE = 'p_l_city.zipcode';

    /** the column name for the municipality field */
    const MUNICIPALITY = 'p_l_city.municipality';

    /** the column name for the municipality_code field */
    const MUNICIPALITY_CODE = 'p_l_city.municipality_code';

    /** the column name for the district field */
    const DISTRICT = 'p_l_city.district';

    /** the column name for the canton field */
    const CANTON = 'p_l_city.canton';

    /** the column name for the amdi field */
    const AMDI = 'p_l_city.amdi';

    /** the column name for the nb_people_2010 field */
    const NB_PEOPLE_2010 = 'p_l_city.nb_people_2010';

    /** the column name for the nb_people_1999 field */
    const NB_PEOPLE_1999 = 'p_l_city.nb_people_1999';

    /** the column name for the nb_people_2012 field */
    const NB_PEOPLE_2012 = 'p_l_city.nb_people_2012';

    /** the column name for the density_2010 field */
    const DENSITY_2010 = 'p_l_city.density_2010';

    /** the column name for the surface field */
    const SURFACE = 'p_l_city.surface';

    /** the column name for the longitude_deg field */
    const LONGITUDE_DEG = 'p_l_city.longitude_deg';

    /** the column name for the latitude_deg field */
    const LATITUDE_DEG = 'p_l_city.latitude_deg';

    /** the column name for the longitude_grd field */
    const LONGITUDE_GRD = 'p_l_city.longitude_grd';

    /** the column name for the latitude_grd field */
    const LATITUDE_GRD = 'p_l_city.latitude_grd';

    /** the column name for the longitude_dms field */
    const LONGITUDE_DMS = 'p_l_city.longitude_dms';

    /** the column name for the latitude_dms field */
    const LATITUDE_DMS = 'p_l_city.latitude_dms';

    /** the column name for the zmin field */
    const ZMIN = 'p_l_city.zmin';

    /** the column name for the zmax field */
    const ZMAX = 'p_l_city.zmax';

    /** the column name for the uuid field */
    const UUID = 'p_l_city.uuid';

    /** the column name for the created_at field */
    const CREATED_AT = 'p_l_city.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'p_l_city.updated_at';

    /** the column name for the slug field */
    const SLUG = 'p_l_city.slug';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of PLCity objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array PLCity[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. PLCityPeer::$fieldNames[PLCityPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'PLDepartmentId', 'Name', 'NameSimple', 'NameReal', 'NameSoundex', 'NameMetaphone', 'Zipcode', 'Municipality', 'MunicipalityCode', 'District', 'Canton', 'Amdi', 'NbPeople2010', 'NbPeople1999', 'NbPeople2012', 'Density2010', 'Surface', 'LongitudeDeg', 'LatitudeDeg', 'LongitudeGrd', 'LatitudeGrd', 'LongitudeDms', 'LatitudeDms', 'Zmin', 'Zmax', 'Uuid', 'CreatedAt', 'UpdatedAt', 'Slug', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'pLDepartmentId', 'name', 'nameSimple', 'nameReal', 'nameSoundex', 'nameMetaphone', 'zipcode', 'municipality', 'municipalityCode', 'district', 'canton', 'amdi', 'nbPeople2010', 'nbPeople1999', 'nbPeople2012', 'density2010', 'surface', 'longitudeDeg', 'latitudeDeg', 'longitudeGrd', 'latitudeGrd', 'longitudeDms', 'latitudeDms', 'zmin', 'zmax', 'uuid', 'createdAt', 'updatedAt', 'slug', ),
        BasePeer::TYPE_COLNAME => array (PLCityPeer::ID, PLCityPeer::P_L_DEPARTMENT_ID, PLCityPeer::NAME, PLCityPeer::NAME_SIMPLE, PLCityPeer::NAME_REAL, PLCityPeer::NAME_SOUNDEX, PLCityPeer::NAME_METAPHONE, PLCityPeer::ZIPCODE, PLCityPeer::MUNICIPALITY, PLCityPeer::MUNICIPALITY_CODE, PLCityPeer::DISTRICT, PLCityPeer::CANTON, PLCityPeer::AMDI, PLCityPeer::NB_PEOPLE_2010, PLCityPeer::NB_PEOPLE_1999, PLCityPeer::NB_PEOPLE_2012, PLCityPeer::DENSITY_2010, PLCityPeer::SURFACE, PLCityPeer::LONGITUDE_DEG, PLCityPeer::LATITUDE_DEG, PLCityPeer::LONGITUDE_GRD, PLCityPeer::LATITUDE_GRD, PLCityPeer::LONGITUDE_DMS, PLCityPeer::LATITUDE_DMS, PLCityPeer::ZMIN, PLCityPeer::ZMAX, PLCityPeer::UUID, PLCityPeer::CREATED_AT, PLCityPeer::UPDATED_AT, PLCityPeer::SLUG, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'P_L_DEPARTMENT_ID', 'NAME', 'NAME_SIMPLE', 'NAME_REAL', 'NAME_SOUNDEX', 'NAME_METAPHONE', 'ZIPCODE', 'MUNICIPALITY', 'MUNICIPALITY_CODE', 'DISTRICT', 'CANTON', 'AMDI', 'NB_PEOPLE_2010', 'NB_PEOPLE_1999', 'NB_PEOPLE_2012', 'DENSITY_2010', 'SURFACE', 'LONGITUDE_DEG', 'LATITUDE_DEG', 'LONGITUDE_GRD', 'LATITUDE_GRD', 'LONGITUDE_DMS', 'LATITUDE_DMS', 'ZMIN', 'ZMAX', 'UUID', 'CREATED_AT', 'UPDATED_AT', 'SLUG', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'p_l_department_id', 'name', 'name_simple', 'name_real', 'name_soundex', 'name_metaphone', 'zipcode', 'municipality', 'municipality_code', 'district', 'canton', 'amdi', 'nb_people_2010', 'nb_people_1999', 'nb_people_2012', 'density_2010', 'surface', 'longitude_deg', 'latitude_deg', 'longitude_grd', 'latitude_grd', 'longitude_dms', 'latitude_dms', 'zmin', 'zmax', 'uuid', 'created_at', 'updated_at', 'slug', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. PLCityPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'PLDepartmentId' => 1, 'Name' => 2, 'NameSimple' => 3, 'NameReal' => 4, 'NameSoundex' => 5, 'NameMetaphone' => 6, 'Zipcode' => 7, 'Municipality' => 8, 'MunicipalityCode' => 9, 'District' => 10, 'Canton' => 11, 'Amdi' => 12, 'NbPeople2010' => 13, 'NbPeople1999' => 14, 'NbPeople2012' => 15, 'Density2010' => 16, 'Surface' => 17, 'LongitudeDeg' => 18, 'LatitudeDeg' => 19, 'LongitudeGrd' => 20, 'LatitudeGrd' => 21, 'LongitudeDms' => 22, 'LatitudeDms' => 23, 'Zmin' => 24, 'Zmax' => 25, 'Uuid' => 26, 'CreatedAt' => 27, 'UpdatedAt' => 28, 'Slug' => 29, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'pLDepartmentId' => 1, 'name' => 2, 'nameSimple' => 3, 'nameReal' => 4, 'nameSoundex' => 5, 'nameMetaphone' => 6, 'zipcode' => 7, 'municipality' => 8, 'municipalityCode' => 9, 'district' => 10, 'canton' => 11, 'amdi' => 12, 'nbPeople2010' => 13, 'nbPeople1999' => 14, 'nbPeople2012' => 15, 'density2010' => 16, 'surface' => 17, 'longitudeDeg' => 18, 'latitudeDeg' => 19, 'longitudeGrd' => 20, 'latitudeGrd' => 21, 'longitudeDms' => 22, 'latitudeDms' => 23, 'zmin' => 24, 'zmax' => 25, 'uuid' => 26, 'createdAt' => 27, 'updatedAt' => 28, 'slug' => 29, ),
        BasePeer::TYPE_COLNAME => array (PLCityPeer::ID => 0, PLCityPeer::P_L_DEPARTMENT_ID => 1, PLCityPeer::NAME => 2, PLCityPeer::NAME_SIMPLE => 3, PLCityPeer::NAME_REAL => 4, PLCityPeer::NAME_SOUNDEX => 5, PLCityPeer::NAME_METAPHONE => 6, PLCityPeer::ZIPCODE => 7, PLCityPeer::MUNICIPALITY => 8, PLCityPeer::MUNICIPALITY_CODE => 9, PLCityPeer::DISTRICT => 10, PLCityPeer::CANTON => 11, PLCityPeer::AMDI => 12, PLCityPeer::NB_PEOPLE_2010 => 13, PLCityPeer::NB_PEOPLE_1999 => 14, PLCityPeer::NB_PEOPLE_2012 => 15, PLCityPeer::DENSITY_2010 => 16, PLCityPeer::SURFACE => 17, PLCityPeer::LONGITUDE_DEG => 18, PLCityPeer::LATITUDE_DEG => 19, PLCityPeer::LONGITUDE_GRD => 20, PLCityPeer::LATITUDE_GRD => 21, PLCityPeer::LONGITUDE_DMS => 22, PLCityPeer::LATITUDE_DMS => 23, PLCityPeer::ZMIN => 24, PLCityPeer::ZMAX => 25, PLCityPeer::UUID => 26, PLCityPeer::CREATED_AT => 27, PLCityPeer::UPDATED_AT => 28, PLCityPeer::SLUG => 29, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'P_L_DEPARTMENT_ID' => 1, 'NAME' => 2, 'NAME_SIMPLE' => 3, 'NAME_REAL' => 4, 'NAME_SOUNDEX' => 5, 'NAME_METAPHONE' => 6, 'ZIPCODE' => 7, 'MUNICIPALITY' => 8, 'MUNICIPALITY_CODE' => 9, 'DISTRICT' => 10, 'CANTON' => 11, 'AMDI' => 12, 'NB_PEOPLE_2010' => 13, 'NB_PEOPLE_1999' => 14, 'NB_PEOPLE_2012' => 15, 'DENSITY_2010' => 16, 'SURFACE' => 17, 'LONGITUDE_DEG' => 18, 'LATITUDE_DEG' => 19, 'LONGITUDE_GRD' => 20, 'LATITUDE_GRD' => 21, 'LONGITUDE_DMS' => 22, 'LATITUDE_DMS' => 23, 'ZMIN' => 24, 'ZMAX' => 25, 'UUID' => 26, 'CREATED_AT' => 27, 'UPDATED_AT' => 28, 'SLUG' => 29, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'p_l_department_id' => 1, 'name' => 2, 'name_simple' => 3, 'name_real' => 4, 'name_soundex' => 5, 'name_metaphone' => 6, 'zipcode' => 7, 'municipality' => 8, 'municipality_code' => 9, 'district' => 10, 'canton' => 11, 'amdi' => 12, 'nb_people_2010' => 13, 'nb_people_1999' => 14, 'nb_people_2012' => 15, 'density_2010' => 16, 'surface' => 17, 'longitude_deg' => 18, 'latitude_deg' => 19, 'longitude_grd' => 20, 'latitude_grd' => 21, 'longitude_dms' => 22, 'latitude_dms' => 23, 'zmin' => 24, 'zmax' => 25, 'uuid' => 26, 'created_at' => 27, 'updated_at' => 28, 'slug' => 29, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, )
    );

    /**
     * Translates a fieldname to another type
     *
     * @param      string $name field name
     * @param      string $fromType One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                         BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @param      string $toType   One of the class type constants
     * @return string          translated name of the field.
     * @throws PropelException - if the specified name could not be found in the fieldname mappings.
     */
    public static function translateFieldName($name, $fromType, $toType)
    {
        $toNames = PLCityPeer::getFieldNames($toType);
        $key = isset(PLCityPeer::$fieldKeys[$fromType][$name]) ? PLCityPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(PLCityPeer::$fieldKeys[$fromType], true));
        }

        return $toNames[$key];
    }

    /**
     * Returns an array of field names.
     *
     * @param      string $type The type of fieldnames to return:
     *                      One of the class type constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME
     *                      BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM
     * @return array           A list of field names
     * @throws PropelException - if the type is not valid.
     */
    public static function getFieldNames($type = BasePeer::TYPE_PHPNAME)
    {
        if (!array_key_exists($type, PLCityPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return PLCityPeer::$fieldNames[$type];
    }

    /**
     * Convenience method which changes table.column to alias.column.
     *
     * Using this method you can maintain SQL abstraction while using column aliases.
     * <code>
     *		$c->addAlias("alias1", TablePeer::TABLE_NAME);
     *		$c->addJoin(TablePeer::alias("alias1", TablePeer::PRIMARY_KEY_COLUMN), TablePeer::PRIMARY_KEY_COLUMN);
     * </code>
     * @param      string $alias The alias for the current table.
     * @param      string $column The column name for current table. (i.e. PLCityPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(PLCityPeer::TABLE_NAME.'.', $alias.'.', $column);
    }

    /**
     * Add all the columns needed to create a new object.
     *
     * Note: any columns that were marked with lazyLoad="true" in the
     * XML schema will not be added to the select list and only loaded
     * on demand.
     *
     * @param      Criteria $criteria object containing the columns to add.
     * @param      string   $alias    optional table alias
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function addSelectColumns(Criteria $criteria, $alias = null)
    {
        if (null === $alias) {
            $criteria->addSelectColumn(PLCityPeer::ID);
            $criteria->addSelectColumn(PLCityPeer::P_L_DEPARTMENT_ID);
            $criteria->addSelectColumn(PLCityPeer::NAME);
            $criteria->addSelectColumn(PLCityPeer::NAME_SIMPLE);
            $criteria->addSelectColumn(PLCityPeer::NAME_REAL);
            $criteria->addSelectColumn(PLCityPeer::NAME_SOUNDEX);
            $criteria->addSelectColumn(PLCityPeer::NAME_METAPHONE);
            $criteria->addSelectColumn(PLCityPeer::ZIPCODE);
            $criteria->addSelectColumn(PLCityPeer::MUNICIPALITY);
            $criteria->addSelectColumn(PLCityPeer::MUNICIPALITY_CODE);
            $criteria->addSelectColumn(PLCityPeer::DISTRICT);
            $criteria->addSelectColumn(PLCityPeer::CANTON);
            $criteria->addSelectColumn(PLCityPeer::AMDI);
            $criteria->addSelectColumn(PLCityPeer::NB_PEOPLE_2010);
            $criteria->addSelectColumn(PLCityPeer::NB_PEOPLE_1999);
            $criteria->addSelectColumn(PLCityPeer::NB_PEOPLE_2012);
            $criteria->addSelectColumn(PLCityPeer::DENSITY_2010);
            $criteria->addSelectColumn(PLCityPeer::SURFACE);
            $criteria->addSelectColumn(PLCityPeer::LONGITUDE_DEG);
            $criteria->addSelectColumn(PLCityPeer::LATITUDE_DEG);
            $criteria->addSelectColumn(PLCityPeer::LONGITUDE_GRD);
            $criteria->addSelectColumn(PLCityPeer::LATITUDE_GRD);
            $criteria->addSelectColumn(PLCityPeer::LONGITUDE_DMS);
            $criteria->addSelectColumn(PLCityPeer::LATITUDE_DMS);
            $criteria->addSelectColumn(PLCityPeer::ZMIN);
            $criteria->addSelectColumn(PLCityPeer::ZMAX);
            $criteria->addSelectColumn(PLCityPeer::UUID);
            $criteria->addSelectColumn(PLCityPeer::CREATED_AT);
            $criteria->addSelectColumn(PLCityPeer::UPDATED_AT);
            $criteria->addSelectColumn(PLCityPeer::SLUG);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.p_l_department_id');
            $criteria->addSelectColumn($alias . '.name');
            $criteria->addSelectColumn($alias . '.name_simple');
            $criteria->addSelectColumn($alias . '.name_real');
            $criteria->addSelectColumn($alias . '.name_soundex');
            $criteria->addSelectColumn($alias . '.name_metaphone');
            $criteria->addSelectColumn($alias . '.zipcode');
            $criteria->addSelectColumn($alias . '.municipality');
            $criteria->addSelectColumn($alias . '.municipality_code');
            $criteria->addSelectColumn($alias . '.district');
            $criteria->addSelectColumn($alias . '.canton');
            $criteria->addSelectColumn($alias . '.amdi');
            $criteria->addSelectColumn($alias . '.nb_people_2010');
            $criteria->addSelectColumn($alias . '.nb_people_1999');
            $criteria->addSelectColumn($alias . '.nb_people_2012');
            $criteria->addSelectColumn($alias . '.density_2010');
            $criteria->addSelectColumn($alias . '.surface');
            $criteria->addSelectColumn($alias . '.longitude_deg');
            $criteria->addSelectColumn($alias . '.latitude_deg');
            $criteria->addSelectColumn($alias . '.longitude_grd');
            $criteria->addSelectColumn($alias . '.latitude_grd');
            $criteria->addSelectColumn($alias . '.longitude_dms');
            $criteria->addSelectColumn($alias . '.latitude_dms');
            $criteria->addSelectColumn($alias . '.zmin');
            $criteria->addSelectColumn($alias . '.zmax');
            $criteria->addSelectColumn($alias . '.uuid');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
            $criteria->addSelectColumn($alias . '.slug');
        }
    }

    /**
     * Returns the number of rows matching criteria.
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @return int Number of matching rows.
     */
    public static function doCount(Criteria $criteria, $distinct = false, PropelPDO $con = null)
    {
        // we may modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PLCityPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PLCityPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(PLCityPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(PLCityPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        // BasePeer returns a PDOStatement
        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }
    /**
     * Selects one object from the DB.
     *
     * @param      Criteria $criteria object used to create the SELECT statement.
     * @param      PropelPDO $con
     * @return PLCity
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = PLCityPeer::doSelect($critcopy, $con);
        if ($objects) {
            return $objects[0];
        }

        return null;
    }
    /**
     * Selects several row from the DB.
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con
     * @return array           Array of selected Objects
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelect(Criteria $criteria, PropelPDO $con = null)
    {
        return PLCityPeer::populateObjects(PLCityPeer::doSelectStmt($criteria, $con));
    }
    /**
     * Prepares the Criteria object and uses the parent doSelect() method to execute a PDOStatement.
     *
     * Use this method directly if you want to work with an executed statement directly (for example
     * to perform your own object hydration).
     *
     * @param      Criteria $criteria The Criteria object used to build the SELECT statement.
     * @param      PropelPDO $con The connection to use
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return PDOStatement The executed PDOStatement object.
     * @see        BasePeer::doSelect()
     */
    public static function doSelectStmt(Criteria $criteria, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PLCityPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            PLCityPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(PLCityPeer::DATABASE_NAME);

        // BasePeer returns a PDOStatement
        return BasePeer::doSelect($criteria, $con);
    }
    /**
     * Adds an object to the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doSelect*()
     * methods in your stub classes -- you may need to explicitly add objects
     * to the cache in order to ensure that the same objects are always returned by doSelect*()
     * and retrieveByPK*() calls.
     *
     * @param PLCity $obj A PLCity object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            PLCityPeer::$instances[$key] = $obj;
        }
    }

    /**
     * Removes an object from the instance pool.
     *
     * Propel keeps cached copies of objects in an instance pool when they are retrieved
     * from the database.  In some cases -- especially when you override doDelete
     * methods in your stub classes -- you may need to explicitly remove objects
     * from the cache in order to prevent returning objects that no longer exist.
     *
     * @param      mixed $value A PLCity object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof PLCity) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or PLCity object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(PLCityPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return PLCity Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(PLCityPeer::$instances[$key])) {
                return PLCityPeer::$instances[$key];
            }
        }

        return null; // just to be explicit
    }

    /**
     * Clear the instance pool.
     *
     * @return void
     */
    public static function clearInstancePool($and_clear_all_references = false)
    {
      if ($and_clear_all_references) {
        foreach (PLCityPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        PLCityPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to p_l_city
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in PTScopePLCPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PTScopePLCPeer::clearInstancePool();
        // Invalidate objects in PUserPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PUserPeer::clearInstancePool();
        // Invalidate objects in PDDebatePeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PDDebatePeer::clearInstancePool();
        // Invalidate objects in PDReactionPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PDReactionPeer::clearInstancePool();
    }

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return string A string version of PK or null if the components of primary key in result array are all null.
     */
    public static function getPrimaryKeyHashFromRow($row, $startcol = 0)
    {
        // If the PK cannot be derived from the row, return null.
        if ($row[$startcol] === null) {
            return null;
        }

        return (string) $row[$startcol];
    }

    /**
     * Retrieves the primary key from the DB resultset row
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, an array of the primary key columns will be returned.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @return mixed The primary key of the row
     */
    public static function getPrimaryKeyFromRow($row, $startcol = 0)
    {

        return (int) $row[$startcol];
    }

    /**
     * The returned array will contain objects of the default type or
     * objects that inherit from the default.
     *
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function populateObjects(PDOStatement $stmt)
    {
        $results = array();

        // set the class once to avoid overhead in the loop
        $cls = PLCityPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = PLCityPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = PLCityPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PLCityPeer::addInstanceToPool($obj, $key);
            } // if key exists
        }
        $stmt->closeCursor();

        return $results;
    }
    /**
     * Populates an object of the default type or an object that inherit from the default.
     *
     * @param      array $row PropelPDO resultset row.
     * @param      int $startcol The 0-based offset for reading from the resultset row.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     * @return array (PLCity object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = PLCityPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = PLCityPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + PLCityPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PLCityPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            PLCityPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related PLDepartment table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinPLDepartment(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PLCityPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PLCityPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PLCityPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PLCityPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PLCityPeer::P_L_DEPARTMENT_ID, PLDepartmentPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }


    /**
     * Selects a collection of PLCity objects pre-filled with their PLDepartment objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PLCity objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPLDepartment(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PLCityPeer::DATABASE_NAME);
        }

        PLCityPeer::addSelectColumns($criteria);
        $startcol = PLCityPeer::NUM_HYDRATE_COLUMNS;
        PLDepartmentPeer::addSelectColumns($criteria);

        $criteria->addJoin(PLCityPeer::P_L_DEPARTMENT_ID, PLDepartmentPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PLCityPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PLCityPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = PLCityPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PLCityPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = PLDepartmentPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = PLDepartmentPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PLDepartmentPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    PLDepartmentPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (PLCity) to $obj2 (PLDepartment)
                $obj2->addPLCity($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining all related tables
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAll(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PLCityPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PLCityPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PLCityPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PLCityPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PLCityPeer::P_L_DEPARTMENT_ID, PLDepartmentPeer::ID, $join_behavior);

        $stmt = BasePeer::doCount($criteria, $con);

        if ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $count = (int) $row[0];
        } else {
            $count = 0; // no rows returned; we infer that means 0 matches.
        }
        $stmt->closeCursor();

        return $count;
    }

    /**
     * Selects a collection of PLCity objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PLCity objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PLCityPeer::DATABASE_NAME);
        }

        PLCityPeer::addSelectColumns($criteria);
        $startcol2 = PLCityPeer::NUM_HYDRATE_COLUMNS;

        PLDepartmentPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PLDepartmentPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(PLCityPeer::P_L_DEPARTMENT_ID, PLDepartmentPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PLCityPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PLCityPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = PLCityPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PLCityPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined PLDepartment rows

            $key2 = PLDepartmentPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = PLDepartmentPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PLDepartmentPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    PLDepartmentPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (PLCity) to the collection in $obj2 (PLDepartment)
                $obj2->addPLCity($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }

    /**
     * Returns the TableMap related to this peer.
     * This method is not needed for general use but a specific application could have a need.
     * @return TableMap
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function getTableMap()
    {
        return Propel::getDatabaseMap(PLCityPeer::DATABASE_NAME)->getTable(PLCityPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BasePLCityPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BasePLCityPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \Politizr\Model\map\PLCityTableMap());
      }
    }

    /**
     * The class that the Peer will make instances of.
     *
     *
     * @return string ClassName
     */
    public static function getOMClass($row = 0, $colnum = 0)
    {
        return PLCityPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a PLCity or Criteria object.
     *
     * @param      mixed $values Criteria or PLCity object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PLCityPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from PLCity object
        }

        if ($criteria->containsKey(PLCityPeer::ID) && $criteria->keyContainsValue(PLCityPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PLCityPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(PLCityPeer::DATABASE_NAME);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = BasePeer::doInsert($criteria, $con);
            $con->commit();
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

    /**
     * Performs an UPDATE on the database, given a PLCity or Criteria object.
     *
     * @param      mixed $values Criteria or PLCity object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PLCityPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(PLCityPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(PLCityPeer::ID);
            $value = $criteria->remove(PLCityPeer::ID);
            if ($value) {
                $selectCriteria->add(PLCityPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(PLCityPeer::TABLE_NAME);
            }

        } else { // $values is PLCity object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(PLCityPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the p_l_city table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PLCityPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(PLCityPeer::TABLE_NAME, $con, PLCityPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PLCityPeer::clearInstancePool();
            PLCityPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a PLCity or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or PLCity object or primary key or array of primary keys
     *              which is used to create the DELETE statement
     * @param      PropelPDO $con the connection to use
     * @return int The number of affected rows (if supported by underlying database driver).  This includes CASCADE-related rows
     *				if supported by native driver or if emulated using Propel.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
     public static function doDelete($values, PropelPDO $con = null)
     {
        if ($con === null) {
            $con = Propel::getConnection(PLCityPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            PLCityPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof PLCity) { // it's a model object
            // invalidate the cache for this single object
            PLCityPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PLCityPeer::DATABASE_NAME);
            $criteria->add(PLCityPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                PLCityPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(PLCityPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            PLCityPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given PLCity object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param PLCity $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(PLCityPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(PLCityPeer::TABLE_NAME);

            if (! is_array($cols)) {
                $cols = array($cols);
            }

            foreach ($cols as $colName) {
                if ($tableMap->hasColumn($colName)) {
                    $get = 'get' . $tableMap->getColumn($colName)->getPhpName();
                    $columns[$colName] = $obj->$get();
                }
            }
        } else {

        }

        return BasePeer::doValidate(PLCityPeer::DATABASE_NAME, PLCityPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return PLCity
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = PLCityPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(PLCityPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(PLCityPeer::DATABASE_NAME);
        $criteria->add(PLCityPeer::ID, $pk);

        $v = PLCityPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return PLCity[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PLCityPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(PLCityPeer::DATABASE_NAME);
            $criteria->add(PLCityPeer::ID, $pks, Criteria::IN);
            $objs = PLCityPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BasePLCityPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BasePLCityPeer::buildTableMap();

