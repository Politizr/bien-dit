<?php

namespace Politizr\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Politizr\Model\PUQualificationArchive;
use Politizr\Model\PUQualificationArchivePeer;
use Politizr\Model\map\PUQualificationArchiveTableMap;

abstract class BasePUQualificationArchivePeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'p_u_qualification_archive';

    /** the related Propel class for this table */
    const OM_CLASS = 'Politizr\\Model\\PUQualificationArchive';

    /** the related TableMap class for this table */
    const TM_CLASS = 'Politizr\\Model\\map\\PUQualificationArchiveTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 10;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 10;

    /** the column name for the id field */
    const ID = 'p_u_qualification_archive.id';

    /** the column name for the p_user_id field */
    const P_USER_ID = 'p_u_qualification_archive.p_user_id';

    /** the column name for the p_u_political_party_id field */
    const P_U_POLITICAL_PARTY_ID = 'p_u_qualification_archive.p_u_political_party_id';

    /** the column name for the p_u_mandate_type_id field */
    const P_U_MANDATE_TYPE_ID = 'p_u_qualification_archive.p_u_mandate_type_id';

    /** the column name for the description field */
    const DESCRIPTION = 'p_u_qualification_archive.description';

    /** the column name for the begin_at field */
    const BEGIN_AT = 'p_u_qualification_archive.begin_at';

    /** the column name for the end_at field */
    const END_AT = 'p_u_qualification_archive.end_at';

    /** the column name for the created_at field */
    const CREATED_AT = 'p_u_qualification_archive.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'p_u_qualification_archive.updated_at';

    /** the column name for the archived_at field */
    const ARCHIVED_AT = 'p_u_qualification_archive.archived_at';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of PUQualificationArchive objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array PUQualificationArchive[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. PUQualificationArchivePeer::$fieldNames[PUQualificationArchivePeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'PUserId', 'PUPoliticalPartyId', 'PUMandateTypeId', 'Description', 'BeginAt', 'EndAt', 'CreatedAt', 'UpdatedAt', 'ArchivedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'pUserId', 'pUPoliticalPartyId', 'pUMandateTypeId', 'description', 'beginAt', 'endAt', 'createdAt', 'updatedAt', 'archivedAt', ),
        BasePeer::TYPE_COLNAME => array (PUQualificationArchivePeer::ID, PUQualificationArchivePeer::P_USER_ID, PUQualificationArchivePeer::P_U_POLITICAL_PARTY_ID, PUQualificationArchivePeer::P_U_MANDATE_TYPE_ID, PUQualificationArchivePeer::DESCRIPTION, PUQualificationArchivePeer::BEGIN_AT, PUQualificationArchivePeer::END_AT, PUQualificationArchivePeer::CREATED_AT, PUQualificationArchivePeer::UPDATED_AT, PUQualificationArchivePeer::ARCHIVED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'P_USER_ID', 'P_U_POLITICAL_PARTY_ID', 'P_U_MANDATE_TYPE_ID', 'DESCRIPTION', 'BEGIN_AT', 'END_AT', 'CREATED_AT', 'UPDATED_AT', 'ARCHIVED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'p_user_id', 'p_u_political_party_id', 'p_u_mandate_type_id', 'description', 'begin_at', 'end_at', 'created_at', 'updated_at', 'archived_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. PUQualificationArchivePeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'PUserId' => 1, 'PUPoliticalPartyId' => 2, 'PUMandateTypeId' => 3, 'Description' => 4, 'BeginAt' => 5, 'EndAt' => 6, 'CreatedAt' => 7, 'UpdatedAt' => 8, 'ArchivedAt' => 9, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'pUserId' => 1, 'pUPoliticalPartyId' => 2, 'pUMandateTypeId' => 3, 'description' => 4, 'beginAt' => 5, 'endAt' => 6, 'createdAt' => 7, 'updatedAt' => 8, 'archivedAt' => 9, ),
        BasePeer::TYPE_COLNAME => array (PUQualificationArchivePeer::ID => 0, PUQualificationArchivePeer::P_USER_ID => 1, PUQualificationArchivePeer::P_U_POLITICAL_PARTY_ID => 2, PUQualificationArchivePeer::P_U_MANDATE_TYPE_ID => 3, PUQualificationArchivePeer::DESCRIPTION => 4, PUQualificationArchivePeer::BEGIN_AT => 5, PUQualificationArchivePeer::END_AT => 6, PUQualificationArchivePeer::CREATED_AT => 7, PUQualificationArchivePeer::UPDATED_AT => 8, PUQualificationArchivePeer::ARCHIVED_AT => 9, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'P_USER_ID' => 1, 'P_U_POLITICAL_PARTY_ID' => 2, 'P_U_MANDATE_TYPE_ID' => 3, 'DESCRIPTION' => 4, 'BEGIN_AT' => 5, 'END_AT' => 6, 'CREATED_AT' => 7, 'UPDATED_AT' => 8, 'ARCHIVED_AT' => 9, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'p_user_id' => 1, 'p_u_political_party_id' => 2, 'p_u_mandate_type_id' => 3, 'description' => 4, 'begin_at' => 5, 'end_at' => 6, 'created_at' => 7, 'updated_at' => 8, 'archived_at' => 9, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, )
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
        $toNames = PUQualificationArchivePeer::getFieldNames($toType);
        $key = isset(PUQualificationArchivePeer::$fieldKeys[$fromType][$name]) ? PUQualificationArchivePeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(PUQualificationArchivePeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, PUQualificationArchivePeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return PUQualificationArchivePeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. PUQualificationArchivePeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(PUQualificationArchivePeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(PUQualificationArchivePeer::ID);
            $criteria->addSelectColumn(PUQualificationArchivePeer::P_USER_ID);
            $criteria->addSelectColumn(PUQualificationArchivePeer::P_U_POLITICAL_PARTY_ID);
            $criteria->addSelectColumn(PUQualificationArchivePeer::P_U_MANDATE_TYPE_ID);
            $criteria->addSelectColumn(PUQualificationArchivePeer::DESCRIPTION);
            $criteria->addSelectColumn(PUQualificationArchivePeer::BEGIN_AT);
            $criteria->addSelectColumn(PUQualificationArchivePeer::END_AT);
            $criteria->addSelectColumn(PUQualificationArchivePeer::CREATED_AT);
            $criteria->addSelectColumn(PUQualificationArchivePeer::UPDATED_AT);
            $criteria->addSelectColumn(PUQualificationArchivePeer::ARCHIVED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.p_user_id');
            $criteria->addSelectColumn($alias . '.p_u_political_party_id');
            $criteria->addSelectColumn($alias . '.p_u_mandate_type_id');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.begin_at');
            $criteria->addSelectColumn($alias . '.end_at');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
            $criteria->addSelectColumn($alias . '.archived_at');
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
        $criteria->setPrimaryTableName(PUQualificationArchivePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PUQualificationArchivePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(PUQualificationArchivePeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(PUQualificationArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return PUQualificationArchive
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = PUQualificationArchivePeer::doSelect($critcopy, $con);
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
        return PUQualificationArchivePeer::populateObjects(PUQualificationArchivePeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(PUQualificationArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            PUQualificationArchivePeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(PUQualificationArchivePeer::DATABASE_NAME);

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
     * @param PUQualificationArchive $obj A PUQualificationArchive object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            PUQualificationArchivePeer::$instances[$key] = $obj;
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
     * @param      mixed $value A PUQualificationArchive object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof PUQualificationArchive) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or PUQualificationArchive object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(PUQualificationArchivePeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return PUQualificationArchive Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(PUQualificationArchivePeer::$instances[$key])) {
                return PUQualificationArchivePeer::$instances[$key];
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
        foreach (PUQualificationArchivePeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        PUQualificationArchivePeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to p_u_qualification_archive
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
        $cls = PUQualificationArchivePeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = PUQualificationArchivePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = PUQualificationArchivePeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PUQualificationArchivePeer::addInstanceToPool($obj, $key);
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
     * @return array (PUQualificationArchive object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = PUQualificationArchivePeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = PUQualificationArchivePeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + PUQualificationArchivePeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PUQualificationArchivePeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            PUQualificationArchivePeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
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
        return Propel::getDatabaseMap(PUQualificationArchivePeer::DATABASE_NAME)->getTable(PUQualificationArchivePeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BasePUQualificationArchivePeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BasePUQualificationArchivePeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \Politizr\Model\map\PUQualificationArchiveTableMap());
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
        return PUQualificationArchivePeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a PUQualificationArchive or Criteria object.
     *
     * @param      mixed $values Criteria or PUQualificationArchive object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PUQualificationArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from PUQualificationArchive object
        }


        // Set the correct dbName
        $criteria->setDbName(PUQualificationArchivePeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a PUQualificationArchive or Criteria object.
     *
     * @param      mixed $values Criteria or PUQualificationArchive object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PUQualificationArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(PUQualificationArchivePeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(PUQualificationArchivePeer::ID);
            $value = $criteria->remove(PUQualificationArchivePeer::ID);
            if ($value) {
                $selectCriteria->add(PUQualificationArchivePeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(PUQualificationArchivePeer::TABLE_NAME);
            }

        } else { // $values is PUQualificationArchive object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(PUQualificationArchivePeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the p_u_qualification_archive table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PUQualificationArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(PUQualificationArchivePeer::TABLE_NAME, $con, PUQualificationArchivePeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PUQualificationArchivePeer::clearInstancePool();
            PUQualificationArchivePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a PUQualificationArchive or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or PUQualificationArchive object or primary key or array of primary keys
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
            $con = Propel::getConnection(PUQualificationArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            PUQualificationArchivePeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof PUQualificationArchive) { // it's a model object
            // invalidate the cache for this single object
            PUQualificationArchivePeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PUQualificationArchivePeer::DATABASE_NAME);
            $criteria->add(PUQualificationArchivePeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                PUQualificationArchivePeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(PUQualificationArchivePeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            PUQualificationArchivePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given PUQualificationArchive object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param PUQualificationArchive $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(PUQualificationArchivePeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(PUQualificationArchivePeer::TABLE_NAME);

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

        return BasePeer::doValidate(PUQualificationArchivePeer::DATABASE_NAME, PUQualificationArchivePeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return PUQualificationArchive
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = PUQualificationArchivePeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(PUQualificationArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(PUQualificationArchivePeer::DATABASE_NAME);
        $criteria->add(PUQualificationArchivePeer::ID, $pk);

        $v = PUQualificationArchivePeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return PUQualificationArchive[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PUQualificationArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(PUQualificationArchivePeer::DATABASE_NAME);
            $criteria->add(PUQualificationArchivePeer::ID, $pks, Criteria::IN);
            $objs = PUQualificationArchivePeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BasePUQualificationArchivePeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BasePUQualificationArchivePeer::buildTableMap();

