<?php

namespace Politizr\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Politizr\Model\PCGroupLCPeer;
use Politizr\Model\PCOwnerPeer;
use Politizr\Model\PCTopicPeer;
use Politizr\Model\PCircle;
use Politizr\Model\PCirclePeer;
use Politizr\Model\PCircleQuery;
use Politizr\Model\PCircleTypePeer;
use Politizr\Model\PMChartePeer;
use Politizr\Model\PUInPCPeer;
use Politizr\Model\map\PCircleTableMap;

abstract class BasePCirclePeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'p_circle';

    /** the related Propel class for this table */
    const OM_CLASS = 'Politizr\\Model\\PCircle';

    /** the related TableMap class for this table */
    const TM_CLASS = 'Politizr\\Model\\map\\PCircleTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 19;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 19;

    /** the column name for the id field */
    const ID = 'p_circle.id';

    /** the column name for the uuid field */
    const UUID = 'p_circle.uuid';

    /** the column name for the p_c_owner_id field */
    const P_C_OWNER_ID = 'p_circle.p_c_owner_id';

    /** the column name for the p_circle_type_id field */
    const P_CIRCLE_TYPE_ID = 'p_circle.p_circle_type_id';

    /** the column name for the title field */
    const TITLE = 'p_circle.title';

    /** the column name for the summary field */
    const SUMMARY = 'p_circle.summary';

    /** the column name for the description field */
    const DESCRIPTION = 'p_circle.description';

    /** the column name for the logo_file_name field */
    const LOGO_FILE_NAME = 'p_circle.logo_file_name';

    /** the column name for the url field */
    const URL = 'p_circle.url';

    /** the column name for the online field */
    const ONLINE = 'p_circle.online';

    /** the column name for the read_only field */
    const READ_ONLY = 'p_circle.read_only';

    /** the column name for the private_access field */
    const PRIVATE_ACCESS = 'p_circle.private_access';

    /** the column name for the public_circle field */
    const PUBLIC_CIRCLE = 'p_circle.public_circle';

    /** the column name for the open_reaction field */
    const OPEN_REACTION = 'p_circle.open_reaction';

    /** the column name for the step field */
    const STEP = 'p_circle.step';

    /** the column name for the created_at field */
    const CREATED_AT = 'p_circle.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'p_circle.updated_at';

    /** the column name for the slug field */
    const SLUG = 'p_circle.slug';

    /** the column name for the sortable_rank field */
    const SORTABLE_RANK = 'p_circle.sortable_rank';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of PCircle objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array PCircle[]
     */
    public static $instances = array();


    // sortable behavior

    /**
     * rank column
     */
    const RANK_COL = 'p_circle.sortable_rank';

    /**
     * Scope column for the set
     */
    const SCOPE_COL = 'p_circle.p_c_owner_id';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. PCirclePeer::$fieldNames[PCirclePeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Uuid', 'PCOwnerId', 'PCircleTypeId', 'Title', 'Summary', 'Description', 'LogoFileName', 'Url', 'Online', 'ReadOnly', 'PrivateAccess', 'PublicCircle', 'OpenReaction', 'Step', 'CreatedAt', 'UpdatedAt', 'Slug', 'SortableRank', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'uuid', 'pCOwnerId', 'pCircleTypeId', 'title', 'summary', 'description', 'logoFileName', 'url', 'online', 'readOnly', 'privateAccess', 'publicCircle', 'openReaction', 'step', 'createdAt', 'updatedAt', 'slug', 'sortableRank', ),
        BasePeer::TYPE_COLNAME => array (PCirclePeer::ID, PCirclePeer::UUID, PCirclePeer::P_C_OWNER_ID, PCirclePeer::P_CIRCLE_TYPE_ID, PCirclePeer::TITLE, PCirclePeer::SUMMARY, PCirclePeer::DESCRIPTION, PCirclePeer::LOGO_FILE_NAME, PCirclePeer::URL, PCirclePeer::ONLINE, PCirclePeer::READ_ONLY, PCirclePeer::PRIVATE_ACCESS, PCirclePeer::PUBLIC_CIRCLE, PCirclePeer::OPEN_REACTION, PCirclePeer::STEP, PCirclePeer::CREATED_AT, PCirclePeer::UPDATED_AT, PCirclePeer::SLUG, PCirclePeer::SORTABLE_RANK, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'UUID', 'P_C_OWNER_ID', 'P_CIRCLE_TYPE_ID', 'TITLE', 'SUMMARY', 'DESCRIPTION', 'LOGO_FILE_NAME', 'URL', 'ONLINE', 'READ_ONLY', 'PRIVATE_ACCESS', 'PUBLIC_CIRCLE', 'OPEN_REACTION', 'STEP', 'CREATED_AT', 'UPDATED_AT', 'SLUG', 'SORTABLE_RANK', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'uuid', 'p_c_owner_id', 'p_circle_type_id', 'title', 'summary', 'description', 'logo_file_name', 'url', 'online', 'read_only', 'private_access', 'public_circle', 'open_reaction', 'step', 'created_at', 'updated_at', 'slug', 'sortable_rank', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. PCirclePeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Uuid' => 1, 'PCOwnerId' => 2, 'PCircleTypeId' => 3, 'Title' => 4, 'Summary' => 5, 'Description' => 6, 'LogoFileName' => 7, 'Url' => 8, 'Online' => 9, 'ReadOnly' => 10, 'PrivateAccess' => 11, 'PublicCircle' => 12, 'OpenReaction' => 13, 'Step' => 14, 'CreatedAt' => 15, 'UpdatedAt' => 16, 'Slug' => 17, 'SortableRank' => 18, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'uuid' => 1, 'pCOwnerId' => 2, 'pCircleTypeId' => 3, 'title' => 4, 'summary' => 5, 'description' => 6, 'logoFileName' => 7, 'url' => 8, 'online' => 9, 'readOnly' => 10, 'privateAccess' => 11, 'publicCircle' => 12, 'openReaction' => 13, 'step' => 14, 'createdAt' => 15, 'updatedAt' => 16, 'slug' => 17, 'sortableRank' => 18, ),
        BasePeer::TYPE_COLNAME => array (PCirclePeer::ID => 0, PCirclePeer::UUID => 1, PCirclePeer::P_C_OWNER_ID => 2, PCirclePeer::P_CIRCLE_TYPE_ID => 3, PCirclePeer::TITLE => 4, PCirclePeer::SUMMARY => 5, PCirclePeer::DESCRIPTION => 6, PCirclePeer::LOGO_FILE_NAME => 7, PCirclePeer::URL => 8, PCirclePeer::ONLINE => 9, PCirclePeer::READ_ONLY => 10, PCirclePeer::PRIVATE_ACCESS => 11, PCirclePeer::PUBLIC_CIRCLE => 12, PCirclePeer::OPEN_REACTION => 13, PCirclePeer::STEP => 14, PCirclePeer::CREATED_AT => 15, PCirclePeer::UPDATED_AT => 16, PCirclePeer::SLUG => 17, PCirclePeer::SORTABLE_RANK => 18, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'UUID' => 1, 'P_C_OWNER_ID' => 2, 'P_CIRCLE_TYPE_ID' => 3, 'TITLE' => 4, 'SUMMARY' => 5, 'DESCRIPTION' => 6, 'LOGO_FILE_NAME' => 7, 'URL' => 8, 'ONLINE' => 9, 'READ_ONLY' => 10, 'PRIVATE_ACCESS' => 11, 'PUBLIC_CIRCLE' => 12, 'OPEN_REACTION' => 13, 'STEP' => 14, 'CREATED_AT' => 15, 'UPDATED_AT' => 16, 'SLUG' => 17, 'SORTABLE_RANK' => 18, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'uuid' => 1, 'p_c_owner_id' => 2, 'p_circle_type_id' => 3, 'title' => 4, 'summary' => 5, 'description' => 6, 'logo_file_name' => 7, 'url' => 8, 'online' => 9, 'read_only' => 10, 'private_access' => 11, 'public_circle' => 12, 'open_reaction' => 13, 'step' => 14, 'created_at' => 15, 'updated_at' => 16, 'slug' => 17, 'sortable_rank' => 18, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, )
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
        $toNames = PCirclePeer::getFieldNames($toType);
        $key = isset(PCirclePeer::$fieldKeys[$fromType][$name]) ? PCirclePeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(PCirclePeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, PCirclePeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return PCirclePeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. PCirclePeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(PCirclePeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(PCirclePeer::ID);
            $criteria->addSelectColumn(PCirclePeer::UUID);
            $criteria->addSelectColumn(PCirclePeer::P_C_OWNER_ID);
            $criteria->addSelectColumn(PCirclePeer::P_CIRCLE_TYPE_ID);
            $criteria->addSelectColumn(PCirclePeer::TITLE);
            $criteria->addSelectColumn(PCirclePeer::SUMMARY);
            $criteria->addSelectColumn(PCirclePeer::DESCRIPTION);
            $criteria->addSelectColumn(PCirclePeer::LOGO_FILE_NAME);
            $criteria->addSelectColumn(PCirclePeer::URL);
            $criteria->addSelectColumn(PCirclePeer::ONLINE);
            $criteria->addSelectColumn(PCirclePeer::READ_ONLY);
            $criteria->addSelectColumn(PCirclePeer::PRIVATE_ACCESS);
            $criteria->addSelectColumn(PCirclePeer::PUBLIC_CIRCLE);
            $criteria->addSelectColumn(PCirclePeer::OPEN_REACTION);
            $criteria->addSelectColumn(PCirclePeer::STEP);
            $criteria->addSelectColumn(PCirclePeer::CREATED_AT);
            $criteria->addSelectColumn(PCirclePeer::UPDATED_AT);
            $criteria->addSelectColumn(PCirclePeer::SLUG);
            $criteria->addSelectColumn(PCirclePeer::SORTABLE_RANK);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.uuid');
            $criteria->addSelectColumn($alias . '.p_c_owner_id');
            $criteria->addSelectColumn($alias . '.p_circle_type_id');
            $criteria->addSelectColumn($alias . '.title');
            $criteria->addSelectColumn($alias . '.summary');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.logo_file_name');
            $criteria->addSelectColumn($alias . '.url');
            $criteria->addSelectColumn($alias . '.online');
            $criteria->addSelectColumn($alias . '.read_only');
            $criteria->addSelectColumn($alias . '.private_access');
            $criteria->addSelectColumn($alias . '.public_circle');
            $criteria->addSelectColumn($alias . '.open_reaction');
            $criteria->addSelectColumn($alias . '.step');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
            $criteria->addSelectColumn($alias . '.slug');
            $criteria->addSelectColumn($alias . '.sortable_rank');
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
        $criteria->setPrimaryTableName(PCirclePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PCirclePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(PCirclePeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return PCircle
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = PCirclePeer::doSelect($critcopy, $con);
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
        return PCirclePeer::populateObjects(PCirclePeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            PCirclePeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(PCirclePeer::DATABASE_NAME);

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
     * @param PCircle $obj A PCircle object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            PCirclePeer::$instances[$key] = $obj;
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
     * @param      mixed $value A PCircle object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof PCircle) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or PCircle object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(PCirclePeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return PCircle Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(PCirclePeer::$instances[$key])) {
                return PCirclePeer::$instances[$key];
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
        foreach (PCirclePeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        PCirclePeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to p_circle
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in PCTopicPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PCTopicPeer::clearInstancePool();
        // Invalidate objects in PCGroupLCPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PCGroupLCPeer::clearInstancePool();
        // Invalidate objects in PUInPCPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PUInPCPeer::clearInstancePool();
        // Invalidate objects in PMChartePeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PMChartePeer::clearInstancePool();
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
        $cls = PCirclePeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = PCirclePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = PCirclePeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PCirclePeer::addInstanceToPool($obj, $key);
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
     * @return array (PCircle object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = PCirclePeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = PCirclePeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + PCirclePeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PCirclePeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            PCirclePeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related PCOwner table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinPCOwner(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PCirclePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PCirclePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PCirclePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PCirclePeer::P_C_OWNER_ID, PCOwnerPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related PCircleType table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinPCircleType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PCirclePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PCirclePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PCirclePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PCirclePeer::P_CIRCLE_TYPE_ID, PCircleTypePeer::ID, $join_behavior);

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
     * Selects a collection of PCircle objects pre-filled with their PCOwner objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PCircle objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPCOwner(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PCirclePeer::DATABASE_NAME);
        }

        PCirclePeer::addSelectColumns($criteria);
        $startcol = PCirclePeer::NUM_HYDRATE_COLUMNS;
        PCOwnerPeer::addSelectColumns($criteria);

        $criteria->addJoin(PCirclePeer::P_C_OWNER_ID, PCOwnerPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PCirclePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PCirclePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = PCirclePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PCirclePeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = PCOwnerPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = PCOwnerPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PCOwnerPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    PCOwnerPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (PCircle) to $obj2 (PCOwner)
                $obj2->addPCircle($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of PCircle objects pre-filled with their PCircleType objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PCircle objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPCircleType(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PCirclePeer::DATABASE_NAME);
        }

        PCirclePeer::addSelectColumns($criteria);
        $startcol = PCirclePeer::NUM_HYDRATE_COLUMNS;
        PCircleTypePeer::addSelectColumns($criteria);

        $criteria->addJoin(PCirclePeer::P_CIRCLE_TYPE_ID, PCircleTypePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PCirclePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PCirclePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = PCirclePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PCirclePeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = PCircleTypePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = PCircleTypePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PCircleTypePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    PCircleTypePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (PCircle) to $obj2 (PCircleType)
                $obj2->addPCircle($obj1);

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
        $criteria->setPrimaryTableName(PCirclePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PCirclePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PCirclePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PCirclePeer::P_C_OWNER_ID, PCOwnerPeer::ID, $join_behavior);

        $criteria->addJoin(PCirclePeer::P_CIRCLE_TYPE_ID, PCircleTypePeer::ID, $join_behavior);

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
     * Selects a collection of PCircle objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PCircle objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PCirclePeer::DATABASE_NAME);
        }

        PCirclePeer::addSelectColumns($criteria);
        $startcol2 = PCirclePeer::NUM_HYDRATE_COLUMNS;

        PCOwnerPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PCOwnerPeer::NUM_HYDRATE_COLUMNS;

        PCircleTypePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PCircleTypePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(PCirclePeer::P_C_OWNER_ID, PCOwnerPeer::ID, $join_behavior);

        $criteria->addJoin(PCirclePeer::P_CIRCLE_TYPE_ID, PCircleTypePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PCirclePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PCirclePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = PCirclePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PCirclePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined PCOwner rows

            $key2 = PCOwnerPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = PCOwnerPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PCOwnerPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    PCOwnerPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (PCircle) to the collection in $obj2 (PCOwner)
                $obj2->addPCircle($obj1);
            } // if joined row not null

            // Add objects for joined PCircleType rows

            $key3 = PCircleTypePeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = PCircleTypePeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = PCircleTypePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PCircleTypePeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (PCircle) to the collection in $obj3 (PCircleType)
                $obj3->addPCircle($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related PCOwner table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptPCOwner(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PCirclePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PCirclePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(PCirclePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PCirclePeer::P_CIRCLE_TYPE_ID, PCircleTypePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related PCircleType table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptPCircleType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PCirclePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PCirclePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(PCirclePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PCirclePeer::P_C_OWNER_ID, PCOwnerPeer::ID, $join_behavior);

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
     * Selects a collection of PCircle objects pre-filled with all related objects except PCOwner.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PCircle objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptPCOwner(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PCirclePeer::DATABASE_NAME);
        }

        PCirclePeer::addSelectColumns($criteria);
        $startcol2 = PCirclePeer::NUM_HYDRATE_COLUMNS;

        PCircleTypePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PCircleTypePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(PCirclePeer::P_CIRCLE_TYPE_ID, PCircleTypePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PCirclePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PCirclePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = PCirclePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PCirclePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined PCircleType rows

                $key2 = PCircleTypePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = PCircleTypePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = PCircleTypePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    PCircleTypePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (PCircle) to the collection in $obj2 (PCircleType)
                $obj2->addPCircle($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of PCircle objects pre-filled with all related objects except PCircleType.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PCircle objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptPCircleType(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PCirclePeer::DATABASE_NAME);
        }

        PCirclePeer::addSelectColumns($criteria);
        $startcol2 = PCirclePeer::NUM_HYDRATE_COLUMNS;

        PCOwnerPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PCOwnerPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(PCirclePeer::P_C_OWNER_ID, PCOwnerPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PCirclePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PCirclePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = PCirclePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PCirclePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined PCOwner rows

                $key2 = PCOwnerPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = PCOwnerPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = PCOwnerPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    PCOwnerPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (PCircle) to the collection in $obj2 (PCOwner)
                $obj2->addPCircle($obj1);

            } // if joined row is not null

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
        return Propel::getDatabaseMap(PCirclePeer::DATABASE_NAME)->getTable(PCirclePeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BasePCirclePeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BasePCirclePeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \Politizr\Model\map\PCircleTableMap());
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
        return PCirclePeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a PCircle or Criteria object.
     *
     * @param      mixed $values Criteria or PCircle object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from PCircle object
        }

        if ($criteria->containsKey(PCirclePeer::ID) && $criteria->keyContainsValue(PCirclePeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PCirclePeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(PCirclePeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a PCircle or Criteria object.
     *
     * @param      mixed $values Criteria or PCircle object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(PCirclePeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(PCirclePeer::ID);
            $value = $criteria->remove(PCirclePeer::ID);
            if ($value) {
                $selectCriteria->add(PCirclePeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(PCirclePeer::TABLE_NAME);
            }

        } else { // $values is PCircle object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(PCirclePeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the p_circle table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(PCirclePeer::TABLE_NAME, $con, PCirclePeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PCirclePeer::clearInstancePool();
            PCirclePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a PCircle or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or PCircle object or primary key or array of primary keys
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
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            PCirclePeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof PCircle) { // it's a model object
            // invalidate the cache for this single object
            PCirclePeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PCirclePeer::DATABASE_NAME);
            $criteria->add(PCirclePeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                PCirclePeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(PCirclePeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            PCirclePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given PCircle object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param PCircle $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(PCirclePeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(PCirclePeer::TABLE_NAME);

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

        return BasePeer::doValidate(PCirclePeer::DATABASE_NAME, PCirclePeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return PCircle
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = PCirclePeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(PCirclePeer::DATABASE_NAME);
        $criteria->add(PCirclePeer::ID, $pk);

        $v = PCirclePeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return PCircle[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(PCirclePeer::DATABASE_NAME);
            $criteria->add(PCirclePeer::ID, $pks, Criteria::IN);
            $objs = PCirclePeer::doSelect($criteria, $con);
        }

        return $objs;
    }

    // sortable behavior

    /**
     * Get the highest rank
     *
     * @param      int $scope		Scope to determine which suite to consider
     * @param     PropelPDO optional connection
     *
     * @return    integer highest position
     */
    public static function getMaxRank($scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $c = new Criteria();
        $c->addSelectColumn('MAX(' . PCirclePeer::RANK_COL . ')');
        PCirclePeer::sortableApplyScopeCriteria($c, $scope);
        $stmt = PCirclePeer::doSelectStmt($c, $con);

        return $stmt->fetchColumn();
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param      int $scope		Scope to determine which suite to consider
     * @param     PropelPDO $con optional connection
     *
     * @return PCircle
     */
    public static function retrieveByRank($rank, $scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME);
        }

        $c = new Criteria;
        $c->add(PCirclePeer::RANK_COL, $rank);
        PCirclePeer::sortableApplyScopeCriteria($c, $scope);

        return PCirclePeer::doSelectOne($c, $con);
    }

    /**
     * Reorder a set of sortable objects based on a list of id/position
     * Beware that there is no check made on the positions passed
     * So incoherent positions will result in an incoherent list
     *
     * @param     array     $order id => rank pairs
     * @param     PropelPDO $con   optional connection
     *
     * @return    boolean true if the reordering took place, false if a database problem prevented it
     */
    public static function reorder(array $order, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $ids = array_keys($order);
            $objects = PCirclePeer::retrieveByPKs($ids);
            foreach ($objects as $object) {
                $pk = $object->getPrimaryKey();
                if ($object->getSortableRank() != $order[$pk]) {
                    $object->setSortableRank($order[$pk]);
                    $object->save($con);
                }
            }
            $con->commit();

            return true;
        } catch (Exception $e) {
            $con->rollback();
            throw $e;
        }
    }

    /**
     * Return an array of sortable objects ordered by position
     *
     * @param     Criteria  $criteria  optional criteria object
     * @param     string    $order     sorting order, to be chosen between Criteria::ASC (default) and Criteria::DESC
     * @param     PropelPDO $con       optional connection
     *
     * @return    array list of sortable objects
     */
    public static function doSelectOrderByRank(Criteria $criteria = null, $order = Criteria::ASC, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME);
        }

        if ($criteria === null) {
            $criteria = new Criteria();
        } elseif ($criteria instanceof Criteria) {
            $criteria = clone $criteria;
        }

        $criteria->clearOrderByColumns();

        if ($order == Criteria::ASC) {
            $criteria->addAscendingOrderByColumn(PCirclePeer::RANK_COL);
        } else {
            $criteria->addDescendingOrderByColumn(PCirclePeer::RANK_COL);
        }

        return PCirclePeer::doSelect($criteria, $con);
    }

    /**
     * Return an array of sortable objects in the given scope ordered by position
     *
     * @param     mixed     $scope  the scope of the list
     * @param     string    $order  sorting order, to be chosen between Criteria::ASC (default) and Criteria::DESC
     * @param     PropelPDO $con    optional connection
     *
     * @return    array list of sortable objects
     */
    public static function retrieveList($scope, $order = Criteria::ASC, PropelPDO $con = null)
    {
        $c = new Criteria();
        PCirclePeer::sortableApplyScopeCriteria($c, $scope);

        return PCirclePeer::doSelectOrderByRank($c, $order, $con);
    }

    /**
     * Return the number of sortable objects in the given scope
     *
     * @param     mixed     $scope  the scope of the list
     * @param     PropelPDO $con    optional connection
     *
     * @return    array list of sortable objects
     */
    public static function countList($scope, PropelPDO $con = null)
    {
        $c = new Criteria();
        PCirclePeer::sortableApplyScopeCriteria($c, $scope);

        return PCirclePeer::doCount($c, $con);
    }

    /**
     * Deletes the sortable objects in the given scope
     *
     * @param     mixed     $scope  the scope of the list
     * @param     PropelPDO $con    optional connection
     *
     * @return    int number of deleted objects
     */
    public static function deleteList($scope, PropelPDO $con = null)
    {
        $c = new Criteria();
        PCirclePeer::sortableApplyScopeCriteria($c, $scope);

        return PCirclePeer::doDelete($c, $con);
    }

    /**
     * Applies all scope fields to the given criteria.
     *
     * @param  Criteria $criteria Applies the values directly to this criteria.
     * @param  mixed    $scope    The scope value as scalar type or array($value1, ...).
     * @param  string   $method   The method we use to apply the values.
     *
     */
    public static function sortableApplyScopeCriteria(Criteria $criteria, $scope, $method = 'add')
    {

        $criteria->$method(PCirclePeer::P_C_OWNER_ID, $scope, Criteria::EQUAL);

    }

    /**
     * Adds $delta to all Rank values that are >= $first and <= $last.
     * '$delta' can also be negative.
     *
     * @param      int $delta Value to be shifted by, can be negative
     * @param      int $first First node to be shifted
     * @param      int $last  Last node to be shifted
     * @param      mixed $scope Scope to use for the shift. Scalar value (single scope) or array
     * @param      PropelPDO $con Connection to use.
     */
    public static function shiftRank($delta, $first = null, $last = null, $scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PCirclePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $whereCriteria = PCircleQuery::create();
        if (null !== $first) {
            $whereCriteria->add(PCirclePeer::RANK_COL, $first, Criteria::GREATER_EQUAL);
        }
        if (null !== $last) {
            $whereCriteria->addAnd(PCirclePeer::RANK_COL, $last, Criteria::LESS_EQUAL);
        }
        PCirclePeer::sortableApplyScopeCriteria($whereCriteria, $scope);

        $valuesCriteria = new Criteria(PCirclePeer::DATABASE_NAME);
        $valuesCriteria->add(PCirclePeer::RANK_COL, array('raw' => PCirclePeer::RANK_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);
        PCirclePeer::clearInstancePool();
    }

} // BasePCirclePeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BasePCirclePeer::buildTableMap();

