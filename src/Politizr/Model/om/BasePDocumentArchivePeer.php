<?php

namespace Politizr\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Politizr\Model\PDocumentArchive;
use Politizr\Model\PDocumentArchivePeer;
use Politizr\Model\map\PDocumentArchiveTableMap;

abstract class BasePDocumentArchivePeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'p_document_archive';

    /** the related Propel class for this table */
    const OM_CLASS = 'Politizr\\Model\\PDocumentArchive';

    /** the related TableMap class for this table */
    const TM_CLASS = 'PDocumentArchiveTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 13;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 13;

    /** the column name for the id field */
    const ID = 'p_document_archive.id';

    /** the column name for the p_user_id field */
    const P_USER_ID = 'p_document_archive.p_user_id';

    /** the column name for the title field */
    const TITLE = 'p_document_archive.title';

    /** the column name for the summary field */
    const SUMMARY = 'p_document_archive.summary';

    /** the column name for the description field */
    const DESCRIPTION = 'p_document_archive.description';

    /** the column name for the more_info field */
    const MORE_INFO = 'p_document_archive.more_info';

    /** the column name for the note_pos field */
    const NOTE_POS = 'p_document_archive.note_pos';

    /** the column name for the note_neg field */
    const NOTE_NEG = 'p_document_archive.note_neg';

    /** the column name for the published field */
    const PUBLISHED = 'p_document_archive.published';

    /** the column name for the published_at field */
    const PUBLISHED_AT = 'p_document_archive.published_at';

    /** the column name for the published_by field */
    const PUBLISHED_BY = 'p_document_archive.published_by';

    /** the column name for the online field */
    const ONLINE = 'p_document_archive.online';

    /** the column name for the archived_at field */
    const ARCHIVED_AT = 'p_document_archive.archived_at';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identiy map to hold any loaded instances of PDocumentArchive objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array PDocumentArchive[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. PDocumentArchivePeer::$fieldNames[PDocumentArchivePeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'PUserId', 'Title', 'Summary', 'Description', 'MoreInfo', 'NotePos', 'NoteNeg', 'Published', 'PublishedAt', 'PublishedBy', 'Online', 'ArchivedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'pUserId', 'title', 'summary', 'description', 'moreInfo', 'notePos', 'noteNeg', 'published', 'publishedAt', 'publishedBy', 'online', 'archivedAt', ),
        BasePeer::TYPE_COLNAME => array (PDocumentArchivePeer::ID, PDocumentArchivePeer::P_USER_ID, PDocumentArchivePeer::TITLE, PDocumentArchivePeer::SUMMARY, PDocumentArchivePeer::DESCRIPTION, PDocumentArchivePeer::MORE_INFO, PDocumentArchivePeer::NOTE_POS, PDocumentArchivePeer::NOTE_NEG, PDocumentArchivePeer::PUBLISHED, PDocumentArchivePeer::PUBLISHED_AT, PDocumentArchivePeer::PUBLISHED_BY, PDocumentArchivePeer::ONLINE, PDocumentArchivePeer::ARCHIVED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'P_USER_ID', 'TITLE', 'SUMMARY', 'DESCRIPTION', 'MORE_INFO', 'NOTE_POS', 'NOTE_NEG', 'PUBLISHED', 'PUBLISHED_AT', 'PUBLISHED_BY', 'ONLINE', 'ARCHIVED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'p_user_id', 'title', 'summary', 'description', 'more_info', 'note_pos', 'note_neg', 'published', 'published_at', 'published_by', 'online', 'archived_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. PDocumentArchivePeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'PUserId' => 1, 'Title' => 2, 'Summary' => 3, 'Description' => 4, 'MoreInfo' => 5, 'NotePos' => 6, 'NoteNeg' => 7, 'Published' => 8, 'PublishedAt' => 9, 'PublishedBy' => 10, 'Online' => 11, 'ArchivedAt' => 12, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'pUserId' => 1, 'title' => 2, 'summary' => 3, 'description' => 4, 'moreInfo' => 5, 'notePos' => 6, 'noteNeg' => 7, 'published' => 8, 'publishedAt' => 9, 'publishedBy' => 10, 'online' => 11, 'archivedAt' => 12, ),
        BasePeer::TYPE_COLNAME => array (PDocumentArchivePeer::ID => 0, PDocumentArchivePeer::P_USER_ID => 1, PDocumentArchivePeer::TITLE => 2, PDocumentArchivePeer::SUMMARY => 3, PDocumentArchivePeer::DESCRIPTION => 4, PDocumentArchivePeer::MORE_INFO => 5, PDocumentArchivePeer::NOTE_POS => 6, PDocumentArchivePeer::NOTE_NEG => 7, PDocumentArchivePeer::PUBLISHED => 8, PDocumentArchivePeer::PUBLISHED_AT => 9, PDocumentArchivePeer::PUBLISHED_BY => 10, PDocumentArchivePeer::ONLINE => 11, PDocumentArchivePeer::ARCHIVED_AT => 12, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'P_USER_ID' => 1, 'TITLE' => 2, 'SUMMARY' => 3, 'DESCRIPTION' => 4, 'MORE_INFO' => 5, 'NOTE_POS' => 6, 'NOTE_NEG' => 7, 'PUBLISHED' => 8, 'PUBLISHED_AT' => 9, 'PUBLISHED_BY' => 10, 'ONLINE' => 11, 'ARCHIVED_AT' => 12, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'p_user_id' => 1, 'title' => 2, 'summary' => 3, 'description' => 4, 'more_info' => 5, 'note_pos' => 6, 'note_neg' => 7, 'published' => 8, 'published_at' => 9, 'published_by' => 10, 'online' => 11, 'archived_at' => 12, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, )
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
        $toNames = PDocumentArchivePeer::getFieldNames($toType);
        $key = isset(PDocumentArchivePeer::$fieldKeys[$fromType][$name]) ? PDocumentArchivePeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(PDocumentArchivePeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, PDocumentArchivePeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return PDocumentArchivePeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. PDocumentArchivePeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(PDocumentArchivePeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(PDocumentArchivePeer::ID);
            $criteria->addSelectColumn(PDocumentArchivePeer::P_USER_ID);
            $criteria->addSelectColumn(PDocumentArchivePeer::TITLE);
            $criteria->addSelectColumn(PDocumentArchivePeer::SUMMARY);
            $criteria->addSelectColumn(PDocumentArchivePeer::DESCRIPTION);
            $criteria->addSelectColumn(PDocumentArchivePeer::MORE_INFO);
            $criteria->addSelectColumn(PDocumentArchivePeer::NOTE_POS);
            $criteria->addSelectColumn(PDocumentArchivePeer::NOTE_NEG);
            $criteria->addSelectColumn(PDocumentArchivePeer::PUBLISHED);
            $criteria->addSelectColumn(PDocumentArchivePeer::PUBLISHED_AT);
            $criteria->addSelectColumn(PDocumentArchivePeer::PUBLISHED_BY);
            $criteria->addSelectColumn(PDocumentArchivePeer::ONLINE);
            $criteria->addSelectColumn(PDocumentArchivePeer::ARCHIVED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.p_user_id');
            $criteria->addSelectColumn($alias . '.title');
            $criteria->addSelectColumn($alias . '.summary');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.more_info');
            $criteria->addSelectColumn($alias . '.note_pos');
            $criteria->addSelectColumn($alias . '.note_neg');
            $criteria->addSelectColumn($alias . '.published');
            $criteria->addSelectColumn($alias . '.published_at');
            $criteria->addSelectColumn($alias . '.published_by');
            $criteria->addSelectColumn($alias . '.online');
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
        $criteria->setPrimaryTableName(PDocumentArchivePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PDocumentArchivePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(PDocumentArchivePeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(PDocumentArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return                 PDocumentArchive
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = PDocumentArchivePeer::doSelect($critcopy, $con);
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
        return PDocumentArchivePeer::populateObjects(PDocumentArchivePeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(PDocumentArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            PDocumentArchivePeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(PDocumentArchivePeer::DATABASE_NAME);

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
     * @param      PDocumentArchive $obj A PDocumentArchive object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            PDocumentArchivePeer::$instances[$key] = $obj;
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
     * @param      mixed $value A PDocumentArchive object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof PDocumentArchive) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or PDocumentArchive object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(PDocumentArchivePeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return   PDocumentArchive Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(PDocumentArchivePeer::$instances[$key])) {
                return PDocumentArchivePeer::$instances[$key];
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
        foreach (PDocumentArchivePeer::$instances as $instance)
        {
          $instance->clearAllReferences(true);
        }
      }
        PDocumentArchivePeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to p_document_archive
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
        $cls = PDocumentArchivePeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = PDocumentArchivePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = PDocumentArchivePeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PDocumentArchivePeer::addInstanceToPool($obj, $key);
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
     * @return array (PDocumentArchive object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = PDocumentArchivePeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = PDocumentArchivePeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + PDocumentArchivePeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PDocumentArchivePeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            PDocumentArchivePeer::addInstanceToPool($obj, $key);
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
        return Propel::getDatabaseMap(PDocumentArchivePeer::DATABASE_NAME)->getTable(PDocumentArchivePeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BasePDocumentArchivePeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BasePDocumentArchivePeer::TABLE_NAME)) {
        $dbMap->addTableObject(new PDocumentArchiveTableMap());
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
        return PDocumentArchivePeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a PDocumentArchive or Criteria object.
     *
     * @param      mixed $values Criteria or PDocumentArchive object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PDocumentArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from PDocumentArchive object
        }


        // Set the correct dbName
        $criteria->setDbName(PDocumentArchivePeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a PDocumentArchive or Criteria object.
     *
     * @param      mixed $values Criteria or PDocumentArchive object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PDocumentArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(PDocumentArchivePeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(PDocumentArchivePeer::ID);
            $value = $criteria->remove(PDocumentArchivePeer::ID);
            if ($value) {
                $selectCriteria->add(PDocumentArchivePeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(PDocumentArchivePeer::TABLE_NAME);
            }

        } else { // $values is PDocumentArchive object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(PDocumentArchivePeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the p_document_archive table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PDocumentArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(PDocumentArchivePeer::TABLE_NAME, $con, PDocumentArchivePeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PDocumentArchivePeer::clearInstancePool();
            PDocumentArchivePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a PDocumentArchive or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or PDocumentArchive object or primary key or array of primary keys
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
            $con = Propel::getConnection(PDocumentArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            PDocumentArchivePeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof PDocumentArchive) { // it's a model object
            // invalidate the cache for this single object
            PDocumentArchivePeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PDocumentArchivePeer::DATABASE_NAME);
            $criteria->add(PDocumentArchivePeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                PDocumentArchivePeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(PDocumentArchivePeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            PDocumentArchivePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (PropelException $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given PDocumentArchive object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param      PDocumentArchive $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(PDocumentArchivePeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(PDocumentArchivePeer::TABLE_NAME);

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

        return BasePeer::doValidate(PDocumentArchivePeer::DATABASE_NAME, PDocumentArchivePeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param      int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return PDocumentArchive
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = PDocumentArchivePeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(PDocumentArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(PDocumentArchivePeer::DATABASE_NAME);
        $criteria->add(PDocumentArchivePeer::ID, $pk);

        $v = PDocumentArchivePeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return PDocumentArchive[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PDocumentArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(PDocumentArchivePeer::DATABASE_NAME);
            $criteria->add(PDocumentArchivePeer::ID, $pks, Criteria::IN);
            $objs = PDocumentArchivePeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BasePDocumentArchivePeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BasePDocumentArchivePeer::buildTableMap();

