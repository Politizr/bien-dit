<?php

namespace Politizr\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Politizr\Model\PCircleArchive;
use Politizr\Model\PCircleArchivePeer;
use Politizr\Model\map\PCircleArchiveTableMap;

abstract class BasePCircleArchivePeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'p_circle_archive';

    /** the related Propel class for this table */
    const OM_CLASS = 'Politizr\\Model\\PCircleArchive';

    /** the related TableMap class for this table */
    const TM_CLASS = 'Politizr\\Model\\map\\PCircleArchiveTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 21;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 21;

    /** the column name for the id field */
    const ID = 'p_circle_archive.id';

    /** the column name for the uuid field */
    const UUID = 'p_circle_archive.uuid';

    /** the column name for the p_c_owner_id field */
    const P_C_OWNER_ID = 'p_circle_archive.p_c_owner_id';

    /** the column name for the p_circle_type_id field */
    const P_CIRCLE_TYPE_ID = 'p_circle_archive.p_circle_type_id';

    /** the column name for the title field */
    const TITLE = 'p_circle_archive.title';

    /** the column name for the summary field */
    const SUMMARY = 'p_circle_archive.summary';

    /** the column name for the description field */
    const DESCRIPTION = 'p_circle_archive.description';

    /** the column name for the embed_code field */
    const EMBED_CODE = 'p_circle_archive.embed_code';

    /** the column name for the logo_file_name field */
    const LOGO_FILE_NAME = 'p_circle_archive.logo_file_name';

    /** the column name for the url field */
    const URL = 'p_circle_archive.url';

    /** the column name for the online field */
    const ONLINE = 'p_circle_archive.online';

    /** the column name for the read_only field */
    const READ_ONLY = 'p_circle_archive.read_only';

    /** the column name for the private_access field */
    const PRIVATE_ACCESS = 'p_circle_archive.private_access';

    /** the column name for the public_circle field */
    const PUBLIC_CIRCLE = 'p_circle_archive.public_circle';

    /** the column name for the open_reaction field */
    const OPEN_REACTION = 'p_circle_archive.open_reaction';

    /** the column name for the step field */
    const STEP = 'p_circle_archive.step';

    /** the column name for the created_at field */
    const CREATED_AT = 'p_circle_archive.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'p_circle_archive.updated_at';

    /** the column name for the slug field */
    const SLUG = 'p_circle_archive.slug';

    /** the column name for the sortable_rank field */
    const SORTABLE_RANK = 'p_circle_archive.sortable_rank';

    /** the column name for the archived_at field */
    const ARCHIVED_AT = 'p_circle_archive.archived_at';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of PCircleArchive objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array PCircleArchive[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. PCircleArchivePeer::$fieldNames[PCircleArchivePeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Uuid', 'PCOwnerId', 'PCircleTypeId', 'Title', 'Summary', 'Description', 'EmbedCode', 'LogoFileName', 'Url', 'Online', 'ReadOnly', 'PrivateAccess', 'PublicCircle', 'OpenReaction', 'Step', 'CreatedAt', 'UpdatedAt', 'Slug', 'SortableRank', 'ArchivedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'uuid', 'pCOwnerId', 'pCircleTypeId', 'title', 'summary', 'description', 'embedCode', 'logoFileName', 'url', 'online', 'readOnly', 'privateAccess', 'publicCircle', 'openReaction', 'step', 'createdAt', 'updatedAt', 'slug', 'sortableRank', 'archivedAt', ),
        BasePeer::TYPE_COLNAME => array (PCircleArchivePeer::ID, PCircleArchivePeer::UUID, PCircleArchivePeer::P_C_OWNER_ID, PCircleArchivePeer::P_CIRCLE_TYPE_ID, PCircleArchivePeer::TITLE, PCircleArchivePeer::SUMMARY, PCircleArchivePeer::DESCRIPTION, PCircleArchivePeer::EMBED_CODE, PCircleArchivePeer::LOGO_FILE_NAME, PCircleArchivePeer::URL, PCircleArchivePeer::ONLINE, PCircleArchivePeer::READ_ONLY, PCircleArchivePeer::PRIVATE_ACCESS, PCircleArchivePeer::PUBLIC_CIRCLE, PCircleArchivePeer::OPEN_REACTION, PCircleArchivePeer::STEP, PCircleArchivePeer::CREATED_AT, PCircleArchivePeer::UPDATED_AT, PCircleArchivePeer::SLUG, PCircleArchivePeer::SORTABLE_RANK, PCircleArchivePeer::ARCHIVED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'UUID', 'P_C_OWNER_ID', 'P_CIRCLE_TYPE_ID', 'TITLE', 'SUMMARY', 'DESCRIPTION', 'EMBED_CODE', 'LOGO_FILE_NAME', 'URL', 'ONLINE', 'READ_ONLY', 'PRIVATE_ACCESS', 'PUBLIC_CIRCLE', 'OPEN_REACTION', 'STEP', 'CREATED_AT', 'UPDATED_AT', 'SLUG', 'SORTABLE_RANK', 'ARCHIVED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'uuid', 'p_c_owner_id', 'p_circle_type_id', 'title', 'summary', 'description', 'embed_code', 'logo_file_name', 'url', 'online', 'read_only', 'private_access', 'public_circle', 'open_reaction', 'step', 'created_at', 'updated_at', 'slug', 'sortable_rank', 'archived_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. PCircleArchivePeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Uuid' => 1, 'PCOwnerId' => 2, 'PCircleTypeId' => 3, 'Title' => 4, 'Summary' => 5, 'Description' => 6, 'EmbedCode' => 7, 'LogoFileName' => 8, 'Url' => 9, 'Online' => 10, 'ReadOnly' => 11, 'PrivateAccess' => 12, 'PublicCircle' => 13, 'OpenReaction' => 14, 'Step' => 15, 'CreatedAt' => 16, 'UpdatedAt' => 17, 'Slug' => 18, 'SortableRank' => 19, 'ArchivedAt' => 20, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'uuid' => 1, 'pCOwnerId' => 2, 'pCircleTypeId' => 3, 'title' => 4, 'summary' => 5, 'description' => 6, 'embedCode' => 7, 'logoFileName' => 8, 'url' => 9, 'online' => 10, 'readOnly' => 11, 'privateAccess' => 12, 'publicCircle' => 13, 'openReaction' => 14, 'step' => 15, 'createdAt' => 16, 'updatedAt' => 17, 'slug' => 18, 'sortableRank' => 19, 'archivedAt' => 20, ),
        BasePeer::TYPE_COLNAME => array (PCircleArchivePeer::ID => 0, PCircleArchivePeer::UUID => 1, PCircleArchivePeer::P_C_OWNER_ID => 2, PCircleArchivePeer::P_CIRCLE_TYPE_ID => 3, PCircleArchivePeer::TITLE => 4, PCircleArchivePeer::SUMMARY => 5, PCircleArchivePeer::DESCRIPTION => 6, PCircleArchivePeer::EMBED_CODE => 7, PCircleArchivePeer::LOGO_FILE_NAME => 8, PCircleArchivePeer::URL => 9, PCircleArchivePeer::ONLINE => 10, PCircleArchivePeer::READ_ONLY => 11, PCircleArchivePeer::PRIVATE_ACCESS => 12, PCircleArchivePeer::PUBLIC_CIRCLE => 13, PCircleArchivePeer::OPEN_REACTION => 14, PCircleArchivePeer::STEP => 15, PCircleArchivePeer::CREATED_AT => 16, PCircleArchivePeer::UPDATED_AT => 17, PCircleArchivePeer::SLUG => 18, PCircleArchivePeer::SORTABLE_RANK => 19, PCircleArchivePeer::ARCHIVED_AT => 20, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'UUID' => 1, 'P_C_OWNER_ID' => 2, 'P_CIRCLE_TYPE_ID' => 3, 'TITLE' => 4, 'SUMMARY' => 5, 'DESCRIPTION' => 6, 'EMBED_CODE' => 7, 'LOGO_FILE_NAME' => 8, 'URL' => 9, 'ONLINE' => 10, 'READ_ONLY' => 11, 'PRIVATE_ACCESS' => 12, 'PUBLIC_CIRCLE' => 13, 'OPEN_REACTION' => 14, 'STEP' => 15, 'CREATED_AT' => 16, 'UPDATED_AT' => 17, 'SLUG' => 18, 'SORTABLE_RANK' => 19, 'ARCHIVED_AT' => 20, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'uuid' => 1, 'p_c_owner_id' => 2, 'p_circle_type_id' => 3, 'title' => 4, 'summary' => 5, 'description' => 6, 'embed_code' => 7, 'logo_file_name' => 8, 'url' => 9, 'online' => 10, 'read_only' => 11, 'private_access' => 12, 'public_circle' => 13, 'open_reaction' => 14, 'step' => 15, 'created_at' => 16, 'updated_at' => 17, 'slug' => 18, 'sortable_rank' => 19, 'archived_at' => 20, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, )
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
        $toNames = PCircleArchivePeer::getFieldNames($toType);
        $key = isset(PCircleArchivePeer::$fieldKeys[$fromType][$name]) ? PCircleArchivePeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(PCircleArchivePeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, PCircleArchivePeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return PCircleArchivePeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. PCircleArchivePeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(PCircleArchivePeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(PCircleArchivePeer::ID);
            $criteria->addSelectColumn(PCircleArchivePeer::UUID);
            $criteria->addSelectColumn(PCircleArchivePeer::P_C_OWNER_ID);
            $criteria->addSelectColumn(PCircleArchivePeer::P_CIRCLE_TYPE_ID);
            $criteria->addSelectColumn(PCircleArchivePeer::TITLE);
            $criteria->addSelectColumn(PCircleArchivePeer::SUMMARY);
            $criteria->addSelectColumn(PCircleArchivePeer::DESCRIPTION);
            $criteria->addSelectColumn(PCircleArchivePeer::EMBED_CODE);
            $criteria->addSelectColumn(PCircleArchivePeer::LOGO_FILE_NAME);
            $criteria->addSelectColumn(PCircleArchivePeer::URL);
            $criteria->addSelectColumn(PCircleArchivePeer::ONLINE);
            $criteria->addSelectColumn(PCircleArchivePeer::READ_ONLY);
            $criteria->addSelectColumn(PCircleArchivePeer::PRIVATE_ACCESS);
            $criteria->addSelectColumn(PCircleArchivePeer::PUBLIC_CIRCLE);
            $criteria->addSelectColumn(PCircleArchivePeer::OPEN_REACTION);
            $criteria->addSelectColumn(PCircleArchivePeer::STEP);
            $criteria->addSelectColumn(PCircleArchivePeer::CREATED_AT);
            $criteria->addSelectColumn(PCircleArchivePeer::UPDATED_AT);
            $criteria->addSelectColumn(PCircleArchivePeer::SLUG);
            $criteria->addSelectColumn(PCircleArchivePeer::SORTABLE_RANK);
            $criteria->addSelectColumn(PCircleArchivePeer::ARCHIVED_AT);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.uuid');
            $criteria->addSelectColumn($alias . '.p_c_owner_id');
            $criteria->addSelectColumn($alias . '.p_circle_type_id');
            $criteria->addSelectColumn($alias . '.title');
            $criteria->addSelectColumn($alias . '.summary');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.embed_code');
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
        $criteria->setPrimaryTableName(PCircleArchivePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PCircleArchivePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(PCircleArchivePeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(PCircleArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return PCircleArchive
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = PCircleArchivePeer::doSelect($critcopy, $con);
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
        return PCircleArchivePeer::populateObjects(PCircleArchivePeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(PCircleArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            PCircleArchivePeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(PCircleArchivePeer::DATABASE_NAME);

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
     * @param PCircleArchive $obj A PCircleArchive object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            PCircleArchivePeer::$instances[$key] = $obj;
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
     * @param      mixed $value A PCircleArchive object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof PCircleArchive) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or PCircleArchive object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(PCircleArchivePeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return PCircleArchive Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(PCircleArchivePeer::$instances[$key])) {
                return PCircleArchivePeer::$instances[$key];
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
        foreach (PCircleArchivePeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        PCircleArchivePeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to p_circle_archive
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
        $cls = PCircleArchivePeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = PCircleArchivePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = PCircleArchivePeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PCircleArchivePeer::addInstanceToPool($obj, $key);
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
     * @return array (PCircleArchive object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = PCircleArchivePeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = PCircleArchivePeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + PCircleArchivePeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PCircleArchivePeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            PCircleArchivePeer::addInstanceToPool($obj, $key);
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
        return Propel::getDatabaseMap(PCircleArchivePeer::DATABASE_NAME)->getTable(PCircleArchivePeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BasePCircleArchivePeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BasePCircleArchivePeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \Politizr\Model\map\PCircleArchiveTableMap());
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
        return PCircleArchivePeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a PCircleArchive or Criteria object.
     *
     * @param      mixed $values Criteria or PCircleArchive object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PCircleArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from PCircleArchive object
        }


        // Set the correct dbName
        $criteria->setDbName(PCircleArchivePeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a PCircleArchive or Criteria object.
     *
     * @param      mixed $values Criteria or PCircleArchive object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PCircleArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(PCircleArchivePeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(PCircleArchivePeer::ID);
            $value = $criteria->remove(PCircleArchivePeer::ID);
            if ($value) {
                $selectCriteria->add(PCircleArchivePeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(PCircleArchivePeer::TABLE_NAME);
            }

        } else { // $values is PCircleArchive object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(PCircleArchivePeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the p_circle_archive table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PCircleArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(PCircleArchivePeer::TABLE_NAME, $con, PCircleArchivePeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PCircleArchivePeer::clearInstancePool();
            PCircleArchivePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a PCircleArchive or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or PCircleArchive object or primary key or array of primary keys
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
            $con = Propel::getConnection(PCircleArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            PCircleArchivePeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof PCircleArchive) { // it's a model object
            // invalidate the cache for this single object
            PCircleArchivePeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PCircleArchivePeer::DATABASE_NAME);
            $criteria->add(PCircleArchivePeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                PCircleArchivePeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(PCircleArchivePeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            PCircleArchivePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given PCircleArchive object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param PCircleArchive $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(PCircleArchivePeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(PCircleArchivePeer::TABLE_NAME);

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

        return BasePeer::doValidate(PCircleArchivePeer::DATABASE_NAME, PCircleArchivePeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return PCircleArchive
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = PCircleArchivePeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(PCircleArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(PCircleArchivePeer::DATABASE_NAME);
        $criteria->add(PCircleArchivePeer::ID, $pk);

        $v = PCircleArchivePeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return PCircleArchive[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PCircleArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(PCircleArchivePeer::DATABASE_NAME);
            $criteria->add(PCircleArchivePeer::ID, $pks, Criteria::IN);
            $objs = PCircleArchivePeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BasePCircleArchivePeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BasePCircleArchivePeer::buildTableMap();

