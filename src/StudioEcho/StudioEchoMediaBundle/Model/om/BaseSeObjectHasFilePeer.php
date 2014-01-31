<?php

namespace StudioEcho\StudioEchoMediaBundle\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use StudioEcho\StudioEchoMediaBundle\Model\SeMediaFilePeer;
use StudioEcho\StudioEchoMediaBundle\Model\SeMediaObjectPeer;
use StudioEcho\StudioEchoMediaBundle\Model\SeObjectHasFile;
use StudioEcho\StudioEchoMediaBundle\Model\SeObjectHasFilePeer;
use StudioEcho\StudioEchoMediaBundle\Model\SeObjectHasFileQuery;
use StudioEcho\StudioEchoMediaBundle\Model\map\SeObjectHasFileTableMap;

abstract class BaseSeObjectHasFilePeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'se_object_has_file';

    /** the related Propel class for this table */
    const OM_CLASS = 'StudioEcho\\StudioEchoMediaBundle\\Model\\SeObjectHasFile';

    /** the related TableMap class for this table */
    const TM_CLASS = 'SeObjectHasFileTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 5;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 5;

    /** the column name for the se_media_object_id field */
    const SE_MEDIA_OBJECT_ID = 'se_object_has_file.se_media_object_id';

    /** the column name for the se_media_file_id field */
    const SE_MEDIA_FILE_ID = 'se_object_has_file.se_media_file_id';

    /** the column name for the sortable_rank field */
    const SORTABLE_RANK = 'se_object_has_file.sortable_rank';

    /** the column name for the created_at field */
    const CREATED_AT = 'se_object_has_file.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'se_object_has_file.updated_at';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identiy map to hold any loaded instances of SeObjectHasFile objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array SeObjectHasFile[]
     */
    public static $instances = array();


    // sortable behavior

    /**
     * rank column
     */
    const RANK_COL = 'se_object_has_file.sortable_rank';

    /**
     * Scope column for the set
     */
    const SCOPE_COL = 'se_object_has_file.se_media_object_id';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. SeObjectHasFilePeer::$fieldNames[SeObjectHasFilePeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('SeMediaObjectId', 'SeMediaFileId', 'SortableRank', 'CreatedAt', 'UpdatedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('seMediaObjectId', 'seMediaFileId', 'sortableRank', 'createdAt', 'updatedAt', ),
        BasePeer::TYPE_COLNAME => array (SeObjectHasFilePeer::SE_MEDIA_OBJECT_ID, SeObjectHasFilePeer::SE_MEDIA_FILE_ID, SeObjectHasFilePeer::SORTABLE_RANK, SeObjectHasFilePeer::CREATED_AT, SeObjectHasFilePeer::UPDATED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('SE_MEDIA_OBJECT_ID', 'SE_MEDIA_FILE_ID', 'SORTABLE_RANK', 'CREATED_AT', 'UPDATED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('se_media_object_id', 'se_media_file_id', 'sortable_rank', 'created_at', 'updated_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. SeObjectHasFilePeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('SeMediaObjectId' => 0, 'SeMediaFileId' => 1, 'SortableRank' => 2, 'CreatedAt' => 3, 'UpdatedAt' => 4, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('seMediaObjectId' => 0, 'seMediaFileId' => 1, 'sortableRank' => 2, 'createdAt' => 3, 'updatedAt' => 4, ),
        BasePeer::TYPE_COLNAME => array (SeObjectHasFilePeer::SE_MEDIA_OBJECT_ID => 0, SeObjectHasFilePeer::SE_MEDIA_FILE_ID => 1, SeObjectHasFilePeer::SORTABLE_RANK => 2, SeObjectHasFilePeer::CREATED_AT => 3, SeObjectHasFilePeer::UPDATED_AT => 4, ),
        BasePeer::TYPE_RAW_COLNAME => array ('SE_MEDIA_OBJECT_ID' => 0, 'SE_MEDIA_FILE_ID' => 1, 'SORTABLE_RANK' => 2, 'CREATED_AT' => 3, 'UPDATED_AT' => 4, ),
        BasePeer::TYPE_FIELDNAME => array ('se_media_object_id' => 0, 'se_media_file_id' => 1, 'sortable_rank' => 2, 'created_at' => 3, 'updated_at' => 4, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, )
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
        $toNames = SeObjectHasFilePeer::getFieldNames($toType);
        $key = isset(SeObjectHasFilePeer::$fieldKeys[$fromType][$name]) ? SeObjectHasFilePeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(SeObjectHasFilePeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, SeObjectHasFilePeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return SeObjectHasFilePeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. SeObjectHasFilePeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(SeObjectHasFilePeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(SeObjectHasFilePeer::SE_MEDIA_OBJECT_ID);
            $criteria->addSelectColumn(SeObjectHasFilePeer::SE_MEDIA_FILE_ID);
            $criteria->addSelectColumn(SeObjectHasFilePeer::SORTABLE_RANK);
            $criteria->addSelectColumn(SeObjectHasFilePeer::CREATED_AT);
            $criteria->addSelectColumn(SeObjectHasFilePeer::UPDATED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.se_media_object_id');
            $criteria->addSelectColumn($alias . '.se_media_file_id');
            $criteria->addSelectColumn($alias . '.sortable_rank');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
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
        $criteria->setPrimaryTableName(SeObjectHasFilePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeObjectHasFilePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(SeObjectHasFilePeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(SeObjectHasFilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 SeObjectHasFile
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = SeObjectHasFilePeer::doSelect($critcopy, $con);
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
        return SeObjectHasFilePeer::populateObjects(SeObjectHasFilePeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(SeObjectHasFilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            SeObjectHasFilePeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(SeObjectHasFilePeer::DATABASE_NAME);

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
     * @param      SeObjectHasFile $obj A SeObjectHasFile object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = serialize(array((string) $obj->getSeMediaObjectId(), (string) $obj->getSeMediaFileId()));
            } // if key === null
            SeObjectHasFilePeer::$instances[$key] = $obj;
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
     * @param      mixed $value A SeObjectHasFile object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof SeObjectHasFile) {
                $key = serialize(array((string) $value->getSeMediaObjectId(), (string) $value->getSeMediaFileId()));
            } elseif (is_array($value) && count($value) === 2) {
                // assume we've been passed a primary key
                $key = serialize(array((string) $value[0], (string) $value[1]));
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or SeObjectHasFile object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(SeObjectHasFilePeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return   SeObjectHasFile Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(SeObjectHasFilePeer::$instances[$key])) {
                return SeObjectHasFilePeer::$instances[$key];
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
      if ($and_clear_all_references)
      {
        foreach (SeObjectHasFilePeer::$instances as $instance)
        {
          $instance->clearAllReferences(true);
        }
      }
        SeObjectHasFilePeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to se_object_has_file
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
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
        if ($row[$startcol] === null && $row[$startcol + 1] === null) {
            return null;
        }

        return serialize(array((string) $row[$startcol], (string) $row[$startcol + 1]));
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

        return array((int) $row[$startcol], (int) $row[$startcol + 1]);
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
        $cls = SeObjectHasFilePeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = SeObjectHasFilePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = SeObjectHasFilePeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                SeObjectHasFilePeer::addInstanceToPool($obj, $key);
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
     * @return array (SeObjectHasFile object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = SeObjectHasFilePeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = SeObjectHasFilePeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + SeObjectHasFilePeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = SeObjectHasFilePeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            SeObjectHasFilePeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related SeMediaObject table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinSeMediaObject(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SeObjectHasFilePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeObjectHasFilePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SeObjectHasFilePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeObjectHasFilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeObjectHasFilePeer::SE_MEDIA_OBJECT_ID, SeMediaObjectPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related SeMediaFile table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinSeMediaFile(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SeObjectHasFilePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeObjectHasFilePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SeObjectHasFilePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeObjectHasFilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeObjectHasFilePeer::SE_MEDIA_FILE_ID, SeMediaFilePeer::ID, $join_behavior);

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
     * Selects a collection of SeObjectHasFile objects pre-filled with their SeMediaObject objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of SeObjectHasFile objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinSeMediaObject(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SeObjectHasFilePeer::DATABASE_NAME);
        }

        SeObjectHasFilePeer::addSelectColumns($criteria);
        $startcol = SeObjectHasFilePeer::NUM_HYDRATE_COLUMNS;
        SeMediaObjectPeer::addSelectColumns($criteria);

        $criteria->addJoin(SeObjectHasFilePeer::SE_MEDIA_OBJECT_ID, SeMediaObjectPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeObjectHasFilePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeObjectHasFilePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SeObjectHasFilePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeObjectHasFilePeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = SeMediaObjectPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = SeMediaObjectPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = SeMediaObjectPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    SeMediaObjectPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (SeObjectHasFile) to $obj2 (SeMediaObject)
                $obj2->addSeObjectHasFile($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of SeObjectHasFile objects pre-filled with their SeMediaFile objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of SeObjectHasFile objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinSeMediaFile(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SeObjectHasFilePeer::DATABASE_NAME);
        }

        SeObjectHasFilePeer::addSelectColumns($criteria);
        $startcol = SeObjectHasFilePeer::NUM_HYDRATE_COLUMNS;
        SeMediaFilePeer::addSelectColumns($criteria);

        $criteria->addJoin(SeObjectHasFilePeer::SE_MEDIA_FILE_ID, SeMediaFilePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeObjectHasFilePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeObjectHasFilePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = SeObjectHasFilePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeObjectHasFilePeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = SeMediaFilePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = SeMediaFilePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = SeMediaFilePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    SeMediaFilePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (SeObjectHasFile) to $obj2 (SeMediaFile)
                $obj2->addSeObjectHasFile($obj1);

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
        $criteria->setPrimaryTableName(SeObjectHasFilePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeObjectHasFilePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(SeObjectHasFilePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeObjectHasFilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeObjectHasFilePeer::SE_MEDIA_OBJECT_ID, SeMediaObjectPeer::ID, $join_behavior);

        $criteria->addJoin(SeObjectHasFilePeer::SE_MEDIA_FILE_ID, SeMediaFilePeer::ID, $join_behavior);

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
     * Selects a collection of SeObjectHasFile objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of SeObjectHasFile objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SeObjectHasFilePeer::DATABASE_NAME);
        }

        SeObjectHasFilePeer::addSelectColumns($criteria);
        $startcol2 = SeObjectHasFilePeer::NUM_HYDRATE_COLUMNS;

        SeMediaObjectPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + SeMediaObjectPeer::NUM_HYDRATE_COLUMNS;

        SeMediaFilePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + SeMediaFilePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SeObjectHasFilePeer::SE_MEDIA_OBJECT_ID, SeMediaObjectPeer::ID, $join_behavior);

        $criteria->addJoin(SeObjectHasFilePeer::SE_MEDIA_FILE_ID, SeMediaFilePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeObjectHasFilePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeObjectHasFilePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SeObjectHasFilePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeObjectHasFilePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined SeMediaObject rows

            $key2 = SeMediaObjectPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = SeMediaObjectPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = SeMediaObjectPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    SeMediaObjectPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (SeObjectHasFile) to the collection in $obj2 (SeMediaObject)
                $obj2->addSeObjectHasFile($obj1);
            } // if joined row not null

            // Add objects for joined SeMediaFile rows

            $key3 = SeMediaFilePeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = SeMediaFilePeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = SeMediaFilePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    SeMediaFilePeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (SeObjectHasFile) to the collection in $obj3 (SeMediaFile)
                $obj3->addSeObjectHasFile($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Returns the number of rows matching criteria, joining the related SeMediaObject table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptSeMediaObject(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SeObjectHasFilePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeObjectHasFilePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(SeObjectHasFilePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeObjectHasFilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeObjectHasFilePeer::SE_MEDIA_FILE_ID, SeMediaFilePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related SeMediaFile table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptSeMediaFile(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(SeObjectHasFilePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            SeObjectHasFilePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(SeObjectHasFilePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(SeObjectHasFilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(SeObjectHasFilePeer::SE_MEDIA_OBJECT_ID, SeMediaObjectPeer::ID, $join_behavior);

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
     * Selects a collection of SeObjectHasFile objects pre-filled with all related objects except SeMediaObject.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of SeObjectHasFile objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptSeMediaObject(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SeObjectHasFilePeer::DATABASE_NAME);
        }

        SeObjectHasFilePeer::addSelectColumns($criteria);
        $startcol2 = SeObjectHasFilePeer::NUM_HYDRATE_COLUMNS;

        SeMediaFilePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + SeMediaFilePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SeObjectHasFilePeer::SE_MEDIA_FILE_ID, SeMediaFilePeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeObjectHasFilePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeObjectHasFilePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SeObjectHasFilePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeObjectHasFilePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined SeMediaFile rows

                $key2 = SeMediaFilePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = SeMediaFilePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = SeMediaFilePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    SeMediaFilePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (SeObjectHasFile) to the collection in $obj2 (SeMediaFile)
                $obj2->addSeObjectHasFile($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of SeObjectHasFile objects pre-filled with all related objects except SeMediaFile.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of SeObjectHasFile objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptSeMediaFile(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(SeObjectHasFilePeer::DATABASE_NAME);
        }

        SeObjectHasFilePeer::addSelectColumns($criteria);
        $startcol2 = SeObjectHasFilePeer::NUM_HYDRATE_COLUMNS;

        SeMediaObjectPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + SeMediaObjectPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(SeObjectHasFilePeer::SE_MEDIA_OBJECT_ID, SeMediaObjectPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = SeObjectHasFilePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = SeObjectHasFilePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = SeObjectHasFilePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                SeObjectHasFilePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined SeMediaObject rows

                $key2 = SeMediaObjectPeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = SeMediaObjectPeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = SeMediaObjectPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    SeMediaObjectPeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (SeObjectHasFile) to the collection in $obj2 (SeMediaObject)
                $obj2->addSeObjectHasFile($obj1);

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
        return Propel::getDatabaseMap(SeObjectHasFilePeer::DATABASE_NAME)->getTable(SeObjectHasFilePeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BaseSeObjectHasFilePeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BaseSeObjectHasFilePeer::TABLE_NAME)) {
        $dbMap->addTableObject(new SeObjectHasFileTableMap());
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
        return SeObjectHasFilePeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a SeObjectHasFile or Criteria object.
     *
     * @param      mixed $values Criteria or SeObjectHasFile object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SeObjectHasFilePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from SeObjectHasFile object
        }


        // Set the correct dbName
        $criteria->setDbName(SeObjectHasFilePeer::DATABASE_NAME);

        try {
            // use transaction because $criteria could contain info
            // for more than one table (I guess, conceivably)
            $con->beginTransaction();
            $pk = BasePeer::doInsert($criteria, $con);
            $con->commit();
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }

        return $pk;
    }

    /**
     * Performs an UPDATE on the database, given a SeObjectHasFile or Criteria object.
     *
     * @param      mixed $values Criteria or SeObjectHasFile object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SeObjectHasFilePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(SeObjectHasFilePeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(SeObjectHasFilePeer::SE_MEDIA_OBJECT_ID);
            $value = $criteria->remove(SeObjectHasFilePeer::SE_MEDIA_OBJECT_ID);
            if ($value) {
                $selectCriteria->add(SeObjectHasFilePeer::SE_MEDIA_OBJECT_ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(SeObjectHasFilePeer::TABLE_NAME);
            }

            $comparison = $criteria->getComparison(SeObjectHasFilePeer::SE_MEDIA_FILE_ID);
            $value = $criteria->remove(SeObjectHasFilePeer::SE_MEDIA_FILE_ID);
            if ($value) {
                $selectCriteria->add(SeObjectHasFilePeer::SE_MEDIA_FILE_ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(SeObjectHasFilePeer::TABLE_NAME);
            }

        } else { // $values is SeObjectHasFile object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(SeObjectHasFilePeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the se_object_has_file table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SeObjectHasFilePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(SeObjectHasFilePeer::TABLE_NAME, $con, SeObjectHasFilePeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            SeObjectHasFilePeer::clearInstancePool();
            SeObjectHasFilePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a SeObjectHasFile or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or SeObjectHasFile object or primary key or array of primary keys
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
            $con = Propel::getConnection(SeObjectHasFilePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            SeObjectHasFilePeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof SeObjectHasFile) { // it's a model object
            // invalidate the cache for this single object
            SeObjectHasFilePeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(SeObjectHasFilePeer::DATABASE_NAME);
            // primary key is composite; we therefore, expect
            // the primary key passed to be an array of pkey values
            if (count($values) == count($values, COUNT_RECURSIVE)) {
                // array is not multi-dimensional
                $values = array($values);
            }
            foreach ($values as $value) {
                $criterion = $criteria->getNewCriterion(SeObjectHasFilePeer::SE_MEDIA_OBJECT_ID, $value[0]);
                $criterion->addAnd($criteria->getNewCriterion(SeObjectHasFilePeer::SE_MEDIA_FILE_ID, $value[1]));
                $criteria->addOr($criterion);
                // we can invalidate the cache for this single PK
                SeObjectHasFilePeer::removeInstanceFromPool($value);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(SeObjectHasFilePeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            SeObjectHasFilePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given SeObjectHasFile object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      SeObjectHasFile $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(SeObjectHasFilePeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(SeObjectHasFilePeer::TABLE_NAME);

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

        return BasePeer::doValidate(SeObjectHasFilePeer::DATABASE_NAME, SeObjectHasFilePeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve object using using composite pkey values.
     * @param   int $se_media_object_id
     * @param   int $se_media_file_id
     * @param      PropelPDO $con
     * @return   SeObjectHasFile
     */
    public static function retrieveByPK($se_media_object_id, $se_media_file_id, PropelPDO $con = null) {
        $_instancePoolKey = serialize(array((string) $se_media_object_id, (string) $se_media_file_id));
         if (null !== ($obj = SeObjectHasFilePeer::getInstanceFromPool($_instancePoolKey))) {
             return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(SeObjectHasFilePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }
        $criteria = new Criteria(SeObjectHasFilePeer::DATABASE_NAME);
        $criteria->add(SeObjectHasFilePeer::SE_MEDIA_OBJECT_ID, $se_media_object_id);
        $criteria->add(SeObjectHasFilePeer::SE_MEDIA_FILE_ID, $se_media_file_id);
        $v = SeObjectHasFilePeer::doSelect($criteria, $con);

        return !empty($v) ? $v[0] : null;
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
            $con = Propel::getConnection(SeObjectHasFilePeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $c = new Criteria();
        $c->addSelectColumn('MAX(' . SeObjectHasFilePeer::RANK_COL . ')');
        $c->add(SeObjectHasFilePeer::SCOPE_COL, $scope, Criteria::EQUAL);
        $stmt = SeObjectHasFilePeer::doSelectStmt($c, $con);

        return $stmt->fetchColumn();
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param      int $scope		Scope to determine which suite to consider
     * @param     PropelPDO $con optional connection
     *
     * @return SeObjectHasFile
     */
    public static function retrieveByRank($rank, $scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SeObjectHasFilePeer::DATABASE_NAME);
        }

        $c = new Criteria;
        $c->add(SeObjectHasFilePeer::RANK_COL, $rank);
        $c->add(SeObjectHasFilePeer::SCOPE_COL, $scope, Criteria::EQUAL);

        return SeObjectHasFilePeer::doSelectOne($c, $con);
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
            $con = Propel::getConnection(SeObjectHasFilePeer::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $ids = array_keys($order);
            $objects = SeObjectHasFilePeer::retrieveByPKs($ids);
            foreach ($objects as $object) {
                $pk = $object->getPrimaryKey();
                if ($object->getSortableRank() != $order[$pk]) {
                    $object->setSortableRank($order[$pk]);
                    $object->save($con);
                }
            }
            $con->commit();

            return true;
        } catch (PropelException $e) {
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
            $con = Propel::getConnection(SeObjectHasFilePeer::DATABASE_NAME);
        }

        if ($criteria === null) {
            $criteria = new Criteria();
        } elseif ($criteria instanceof Criteria) {
            $criteria = clone $criteria;
        }

        $criteria->clearOrderByColumns();

        if ($order == Criteria::ASC) {
            $criteria->addAscendingOrderByColumn(SeObjectHasFilePeer::RANK_COL);
        } else {
            $criteria->addDescendingOrderByColumn(SeObjectHasFilePeer::RANK_COL);
        }

        return SeObjectHasFilePeer::doSelect($criteria, $con);
    }

    /**
     * Return an array of sortable objects in the given scope ordered by position
     *
     * @param     int       $scope  the scope of the list
     * @param     string    $order  sorting order, to be chosen between Criteria::ASC (default) and Criteria::DESC
     * @param     PropelPDO $con    optional connection
     *
     * @return    array list of sortable objects
     */
    public static function retrieveList($scope, $order = Criteria::ASC, PropelPDO $con = null)
    {
        $c = new Criteria();
        $c->add(SeObjectHasFilePeer::SCOPE_COL, $scope);

        return SeObjectHasFilePeer::doSelectOrderByRank($c, $order, $con);
    }

    /**
     * Return the number of sortable objects in the given scope
     *
     * @param     int       $scope  the scope of the list
     * @param     PropelPDO $con    optional connection
     *
     * @return    array list of sortable objects
     */
    public static function countList($scope, PropelPDO $con = null)
    {
        $c = new Criteria();
        $c->add(SeObjectHasFilePeer::SCOPE_COL, $scope);

        return SeObjectHasFilePeer::doCount($c, $con);
    }

    /**
     * Deletes the sortable objects in the given scope
     *
     * @param     int       $scope  the scope of the list
     * @param     PropelPDO $con    optional connection
     *
     * @return    int number of deleted objects
     */
    public static function deleteList($scope, PropelPDO $con = null)
    {
        $c = new Criteria();
        $c->add(SeObjectHasFilePeer::SCOPE_COL, $scope);

        return SeObjectHasFilePeer::doDelete($c, $con);
    }

    /**
     * Adds $delta to all Rank values that are >= $first and <= $last.
     * '$delta' can also be negative.
     *
     * @param      int $delta Value to be shifted by, can be negative
     * @param      int $first First node to be shifted
     * @param      int $last  Last node to be shifted
     * @param      int $scope Scope to use for the shift
     * @param      PropelPDO $con Connection to use.
     */
    public static function shiftRank($delta, $first = null, $last = null, $scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(SeObjectHasFilePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $whereCriteria = SeObjectHasFileQuery::create();
        if (null !== $first) {
            $whereCriteria->add(SeObjectHasFilePeer::RANK_COL, $first, Criteria::GREATER_EQUAL);
        }
        if (null !== $last) {
            $whereCriteria->addAnd(SeObjectHasFilePeer::RANK_COL, $last, Criteria::LESS_EQUAL);
        }
        $whereCriteria->add(SeObjectHasFilePeer::SCOPE_COL, $scope, Criteria::EQUAL);

        $valuesCriteria = new Criteria(SeObjectHasFilePeer::DATABASE_NAME);
        $valuesCriteria->add(SeObjectHasFilePeer::RANK_COL, array('raw' => SeObjectHasFilePeer::RANK_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);
        SeObjectHasFilePeer::clearInstancePool();
    }

} // BaseSeObjectHasFilePeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BaseSeObjectHasFilePeer::buildTableMap();

