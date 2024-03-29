<?php

namespace Politizr\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Politizr\Model\PRBadgeFamily;
use Politizr\Model\PRBadgeFamilyPeer;
use Politizr\Model\PRBadgeFamilyQuery;
use Politizr\Model\PRBadgePeer;
use Politizr\Model\PRBadgeTypePeer;
use Politizr\Model\map\PRBadgeFamilyTableMap;

abstract class BasePRBadgeFamilyPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'p_r_badge_family';

    /** the related Propel class for this table */
    const OM_CLASS = 'Politizr\\Model\\PRBadgeFamily';

    /** the related TableMap class for this table */
    const TM_CLASS = 'Politizr\\Model\\map\\PRBadgeFamilyTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 7;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 7;

    /** the column name for the p_r_badge_type_id field */
    const P_R_BADGE_TYPE_ID = 'p_r_badge_family.p_r_badge_type_id';

    /** the column name for the id field */
    const ID = 'p_r_badge_family.id';

    /** the column name for the title field */
    const TITLE = 'p_r_badge_family.title';

    /** the column name for the description field */
    const DESCRIPTION = 'p_r_badge_family.description';

    /** the column name for the created_at field */
    const CREATED_AT = 'p_r_badge_family.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'p_r_badge_family.updated_at';

    /** the column name for the sortable_rank field */
    const SORTABLE_RANK = 'p_r_badge_family.sortable_rank';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of PRBadgeFamily objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array PRBadgeFamily[]
     */
    public static $instances = array();


    // sortable behavior

    /**
     * rank column
     */
    const RANK_COL = 'p_r_badge_family.sortable_rank';

    /**
     * Scope column for the set
     */
    const SCOPE_COL = 'p_r_badge_family.p_r_badge_type_id';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. PRBadgeFamilyPeer::$fieldNames[PRBadgeFamilyPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('PRBadgeTypeId', 'Id', 'Title', 'Description', 'CreatedAt', 'UpdatedAt', 'SortableRank', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('pRBadgeTypeId', 'id', 'title', 'description', 'createdAt', 'updatedAt', 'sortableRank', ),
        BasePeer::TYPE_COLNAME => array (PRBadgeFamilyPeer::P_R_BADGE_TYPE_ID, PRBadgeFamilyPeer::ID, PRBadgeFamilyPeer::TITLE, PRBadgeFamilyPeer::DESCRIPTION, PRBadgeFamilyPeer::CREATED_AT, PRBadgeFamilyPeer::UPDATED_AT, PRBadgeFamilyPeer::SORTABLE_RANK, ),
        BasePeer::TYPE_RAW_COLNAME => array ('P_R_BADGE_TYPE_ID', 'ID', 'TITLE', 'DESCRIPTION', 'CREATED_AT', 'UPDATED_AT', 'SORTABLE_RANK', ),
        BasePeer::TYPE_FIELDNAME => array ('p_r_badge_type_id', 'id', 'title', 'description', 'created_at', 'updated_at', 'sortable_rank', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. PRBadgeFamilyPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('PRBadgeTypeId' => 0, 'Id' => 1, 'Title' => 2, 'Description' => 3, 'CreatedAt' => 4, 'UpdatedAt' => 5, 'SortableRank' => 6, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('pRBadgeTypeId' => 0, 'id' => 1, 'title' => 2, 'description' => 3, 'createdAt' => 4, 'updatedAt' => 5, 'sortableRank' => 6, ),
        BasePeer::TYPE_COLNAME => array (PRBadgeFamilyPeer::P_R_BADGE_TYPE_ID => 0, PRBadgeFamilyPeer::ID => 1, PRBadgeFamilyPeer::TITLE => 2, PRBadgeFamilyPeer::DESCRIPTION => 3, PRBadgeFamilyPeer::CREATED_AT => 4, PRBadgeFamilyPeer::UPDATED_AT => 5, PRBadgeFamilyPeer::SORTABLE_RANK => 6, ),
        BasePeer::TYPE_RAW_COLNAME => array ('P_R_BADGE_TYPE_ID' => 0, 'ID' => 1, 'TITLE' => 2, 'DESCRIPTION' => 3, 'CREATED_AT' => 4, 'UPDATED_AT' => 5, 'SORTABLE_RANK' => 6, ),
        BasePeer::TYPE_FIELDNAME => array ('p_r_badge_type_id' => 0, 'id' => 1, 'title' => 2, 'description' => 3, 'created_at' => 4, 'updated_at' => 5, 'sortable_rank' => 6, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, )
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
        $toNames = PRBadgeFamilyPeer::getFieldNames($toType);
        $key = isset(PRBadgeFamilyPeer::$fieldKeys[$fromType][$name]) ? PRBadgeFamilyPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(PRBadgeFamilyPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, PRBadgeFamilyPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return PRBadgeFamilyPeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. PRBadgeFamilyPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(PRBadgeFamilyPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(PRBadgeFamilyPeer::P_R_BADGE_TYPE_ID);
            $criteria->addSelectColumn(PRBadgeFamilyPeer::ID);
            $criteria->addSelectColumn(PRBadgeFamilyPeer::TITLE);
            $criteria->addSelectColumn(PRBadgeFamilyPeer::DESCRIPTION);
            $criteria->addSelectColumn(PRBadgeFamilyPeer::CREATED_AT);
            $criteria->addSelectColumn(PRBadgeFamilyPeer::UPDATED_AT);
            $criteria->addSelectColumn(PRBadgeFamilyPeer::SORTABLE_RANK);
        } else {
            $criteria->addSelectColumn($alias . '.p_r_badge_type_id');
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.title');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
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
        $criteria->setPrimaryTableName(PRBadgeFamilyPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PRBadgeFamilyPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(PRBadgeFamilyPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(PRBadgeFamilyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return PRBadgeFamily
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = PRBadgeFamilyPeer::doSelect($critcopy, $con);
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
        return PRBadgeFamilyPeer::populateObjects(PRBadgeFamilyPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(PRBadgeFamilyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            PRBadgeFamilyPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(PRBadgeFamilyPeer::DATABASE_NAME);

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
     * @param PRBadgeFamily $obj A PRBadgeFamily object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            PRBadgeFamilyPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A PRBadgeFamily object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof PRBadgeFamily) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or PRBadgeFamily object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(PRBadgeFamilyPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return PRBadgeFamily Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(PRBadgeFamilyPeer::$instances[$key])) {
                return PRBadgeFamilyPeer::$instances[$key];
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
        foreach (PRBadgeFamilyPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        PRBadgeFamilyPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to p_r_badge_family
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in PRBadgePeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PRBadgePeer::clearInstancePool();
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
        if ($row[$startcol + 1] === null) {
            return null;
        }

        return (string) $row[$startcol + 1];
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

        return (int) $row[$startcol + 1];
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
        $cls = PRBadgeFamilyPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = PRBadgeFamilyPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = PRBadgeFamilyPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PRBadgeFamilyPeer::addInstanceToPool($obj, $key);
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
     * @return array (PRBadgeFamily object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = PRBadgeFamilyPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = PRBadgeFamilyPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + PRBadgeFamilyPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PRBadgeFamilyPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            PRBadgeFamilyPeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related PRBadgeType table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinPRBadgeType(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PRBadgeFamilyPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PRBadgeFamilyPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PRBadgeFamilyPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PRBadgeFamilyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PRBadgeFamilyPeer::P_R_BADGE_TYPE_ID, PRBadgeTypePeer::ID, $join_behavior);

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
     * Selects a collection of PRBadgeFamily objects pre-filled with their PRBadgeType objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PRBadgeFamily objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPRBadgeType(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PRBadgeFamilyPeer::DATABASE_NAME);
        }

        PRBadgeFamilyPeer::addSelectColumns($criteria);
        $startcol = PRBadgeFamilyPeer::NUM_HYDRATE_COLUMNS;
        PRBadgeTypePeer::addSelectColumns($criteria);

        $criteria->addJoin(PRBadgeFamilyPeer::P_R_BADGE_TYPE_ID, PRBadgeTypePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PRBadgeFamilyPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PRBadgeFamilyPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = PRBadgeFamilyPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PRBadgeFamilyPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = PRBadgeTypePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = PRBadgeTypePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PRBadgeTypePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    PRBadgeTypePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (PRBadgeFamily) to $obj2 (PRBadgeType)
                $obj2->addPRBadgeFamily($obj1);

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
        $criteria->setPrimaryTableName(PRBadgeFamilyPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PRBadgeFamilyPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PRBadgeFamilyPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PRBadgeFamilyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PRBadgeFamilyPeer::P_R_BADGE_TYPE_ID, PRBadgeTypePeer::ID, $join_behavior);

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
     * Selects a collection of PRBadgeFamily objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PRBadgeFamily objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PRBadgeFamilyPeer::DATABASE_NAME);
        }

        PRBadgeFamilyPeer::addSelectColumns($criteria);
        $startcol2 = PRBadgeFamilyPeer::NUM_HYDRATE_COLUMNS;

        PRBadgeTypePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PRBadgeTypePeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(PRBadgeFamilyPeer::P_R_BADGE_TYPE_ID, PRBadgeTypePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PRBadgeFamilyPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PRBadgeFamilyPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = PRBadgeFamilyPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PRBadgeFamilyPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined PRBadgeType rows

            $key2 = PRBadgeTypePeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = PRBadgeTypePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PRBadgeTypePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    PRBadgeTypePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (PRBadgeFamily) to the collection in $obj2 (PRBadgeType)
                $obj2->addPRBadgeFamily($obj1);
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
        return Propel::getDatabaseMap(PRBadgeFamilyPeer::DATABASE_NAME)->getTable(PRBadgeFamilyPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BasePRBadgeFamilyPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BasePRBadgeFamilyPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \Politizr\Model\map\PRBadgeFamilyTableMap());
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
        return PRBadgeFamilyPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a PRBadgeFamily or Criteria object.
     *
     * @param      mixed $values Criteria or PRBadgeFamily object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PRBadgeFamilyPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from PRBadgeFamily object
        }

        if ($criteria->containsKey(PRBadgeFamilyPeer::ID) && $criteria->keyContainsValue(PRBadgeFamilyPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PRBadgeFamilyPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(PRBadgeFamilyPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a PRBadgeFamily or Criteria object.
     *
     * @param      mixed $values Criteria or PRBadgeFamily object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PRBadgeFamilyPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(PRBadgeFamilyPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(PRBadgeFamilyPeer::ID);
            $value = $criteria->remove(PRBadgeFamilyPeer::ID);
            if ($value) {
                $selectCriteria->add(PRBadgeFamilyPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(PRBadgeFamilyPeer::TABLE_NAME);
            }

        } else { // $values is PRBadgeFamily object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(PRBadgeFamilyPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the p_r_badge_family table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PRBadgeFamilyPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(PRBadgeFamilyPeer::TABLE_NAME, $con, PRBadgeFamilyPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PRBadgeFamilyPeer::clearInstancePool();
            PRBadgeFamilyPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a PRBadgeFamily or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or PRBadgeFamily object or primary key or array of primary keys
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
            $con = Propel::getConnection(PRBadgeFamilyPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            PRBadgeFamilyPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof PRBadgeFamily) { // it's a model object
            // invalidate the cache for this single object
            PRBadgeFamilyPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PRBadgeFamilyPeer::DATABASE_NAME);
            $criteria->add(PRBadgeFamilyPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                PRBadgeFamilyPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(PRBadgeFamilyPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            PRBadgeFamilyPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given PRBadgeFamily object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param PRBadgeFamily $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(PRBadgeFamilyPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(PRBadgeFamilyPeer::TABLE_NAME);

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

        return BasePeer::doValidate(PRBadgeFamilyPeer::DATABASE_NAME, PRBadgeFamilyPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return PRBadgeFamily
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = PRBadgeFamilyPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(PRBadgeFamilyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(PRBadgeFamilyPeer::DATABASE_NAME);
        $criteria->add(PRBadgeFamilyPeer::ID, $pk);

        $v = PRBadgeFamilyPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return PRBadgeFamily[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PRBadgeFamilyPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(PRBadgeFamilyPeer::DATABASE_NAME);
            $criteria->add(PRBadgeFamilyPeer::ID, $pks, Criteria::IN);
            $objs = PRBadgeFamilyPeer::doSelect($criteria, $con);
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
            $con = Propel::getConnection(PRBadgeFamilyPeer::DATABASE_NAME);
        }
        // shift the objects with a position lower than the one of object
        $c = new Criteria();
        $c->addSelectColumn('MAX(' . PRBadgeFamilyPeer::RANK_COL . ')');
        PRBadgeFamilyPeer::sortableApplyScopeCriteria($c, $scope);
        $stmt = PRBadgeFamilyPeer::doSelectStmt($c, $con);

        return $stmt->fetchColumn();
    }

    /**
     * Get an item from the list based on its rank
     *
     * @param     integer   $rank rank
     * @param      int $scope		Scope to determine which suite to consider
     * @param     PropelPDO $con optional connection
     *
     * @return PRBadgeFamily
     */
    public static function retrieveByRank($rank, $scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PRBadgeFamilyPeer::DATABASE_NAME);
        }

        $c = new Criteria;
        $c->add(PRBadgeFamilyPeer::RANK_COL, $rank);
        PRBadgeFamilyPeer::sortableApplyScopeCriteria($c, $scope);

        return PRBadgeFamilyPeer::doSelectOne($c, $con);
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
            $con = Propel::getConnection(PRBadgeFamilyPeer::DATABASE_NAME);
        }

        $con->beginTransaction();
        try {
            $ids = array_keys($order);
            $objects = PRBadgeFamilyPeer::retrieveByPKs($ids);
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
            $con = Propel::getConnection(PRBadgeFamilyPeer::DATABASE_NAME);
        }

        if ($criteria === null) {
            $criteria = new Criteria();
        } elseif ($criteria instanceof Criteria) {
            $criteria = clone $criteria;
        }

        $criteria->clearOrderByColumns();

        if ($order == Criteria::ASC) {
            $criteria->addAscendingOrderByColumn(PRBadgeFamilyPeer::RANK_COL);
        } else {
            $criteria->addDescendingOrderByColumn(PRBadgeFamilyPeer::RANK_COL);
        }

        return PRBadgeFamilyPeer::doSelect($criteria, $con);
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
        PRBadgeFamilyPeer::sortableApplyScopeCriteria($c, $scope);

        return PRBadgeFamilyPeer::doSelectOrderByRank($c, $order, $con);
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
        PRBadgeFamilyPeer::sortableApplyScopeCriteria($c, $scope);

        return PRBadgeFamilyPeer::doCount($c, $con);
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
        PRBadgeFamilyPeer::sortableApplyScopeCriteria($c, $scope);

        return PRBadgeFamilyPeer::doDelete($c, $con);
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

        $criteria->$method(PRBadgeFamilyPeer::P_R_BADGE_TYPE_ID, $scope, Criteria::EQUAL);

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
            $con = Propel::getConnection(PRBadgeFamilyPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $whereCriteria = PRBadgeFamilyQuery::create();
        if (null !== $first) {
            $whereCriteria->add(PRBadgeFamilyPeer::RANK_COL, $first, Criteria::GREATER_EQUAL);
        }
        if (null !== $last) {
            $whereCriteria->addAnd(PRBadgeFamilyPeer::RANK_COL, $last, Criteria::LESS_EQUAL);
        }
        PRBadgeFamilyPeer::sortableApplyScopeCriteria($whereCriteria, $scope);

        $valuesCriteria = new Criteria(PRBadgeFamilyPeer::DATABASE_NAME);
        $valuesCriteria->add(PRBadgeFamilyPeer::RANK_COL, array('raw' => PRBadgeFamilyPeer::RANK_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);
        PRBadgeFamilyPeer::clearInstancePool();
    }

} // BasePRBadgeFamilyPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BasePRBadgeFamilyPeer::buildTableMap();

