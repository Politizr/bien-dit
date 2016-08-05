<?php

namespace Politizr\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Politizr\Model\PDDCommentPeer;
use Politizr\Model\PDDTaggedTPeer;
use Politizr\Model\PDDebate;
use Politizr\Model\PDDebatePeer;
use Politizr\Model\PDReactionPeer;
use Politizr\Model\PMDebateHistoricPeer;
use Politizr\Model\PUBookmarkDDPeer;
use Politizr\Model\PUFollowDDPeer;
use Politizr\Model\PUTrackDDPeer;
use Politizr\Model\PUserPeer;
use Politizr\Model\map\PDDebateTableMap;

abstract class BasePDDebatePeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'p_d_debate';

    /** the related Propel class for this table */
    const OM_CLASS = 'Politizr\\Model\\PDDebate';

    /** the related TableMap class for this table */
    const TM_CLASS = 'Politizr\\Model\\map\\PDDebateTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 22;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 22;

    /** the column name for the id field */
    const ID = 'p_d_debate.id';

    /** the column name for the uuid field */
    const UUID = 'p_d_debate.uuid';

    /** the column name for the p_user_id field */
    const P_USER_ID = 'p_d_debate.p_user_id';

    /** the column name for the title field */
    const TITLE = 'p_d_debate.title';

    /** the column name for the file_name field */
    const FILE_NAME = 'p_d_debate.file_name';

    /** the column name for the copyright field */
    const COPYRIGHT = 'p_d_debate.copyright';

    /** the column name for the description field */
    const DESCRIPTION = 'p_d_debate.description';

    /** the column name for the note_pos field */
    const NOTE_POS = 'p_d_debate.note_pos';

    /** the column name for the note_neg field */
    const NOTE_NEG = 'p_d_debate.note_neg';

    /** the column name for the nb_views field */
    const NB_VIEWS = 'p_d_debate.nb_views';

    /** the column name for the published field */
    const PUBLISHED = 'p_d_debate.published';

    /** the column name for the published_at field */
    const PUBLISHED_AT = 'p_d_debate.published_at';

    /** the column name for the published_by field */
    const PUBLISHED_BY = 'p_d_debate.published_by';

    /** the column name for the favorite field */
    const FAVORITE = 'p_d_debate.favorite';

    /** the column name for the online field */
    const ONLINE = 'p_d_debate.online';

    /** the column name for the homepage field */
    const HOMEPAGE = 'p_d_debate.homepage';

    /** the column name for the moderated field */
    const MODERATED = 'p_d_debate.moderated';

    /** the column name for the moderated_partial field */
    const MODERATED_PARTIAL = 'p_d_debate.moderated_partial';

    /** the column name for the moderated_at field */
    const MODERATED_AT = 'p_d_debate.moderated_at';

    /** the column name for the created_at field */
    const CREATED_AT = 'p_d_debate.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'p_d_debate.updated_at';

    /** the column name for the slug field */
    const SLUG = 'p_d_debate.slug';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of PDDebate objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array PDDebate[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. PDDebatePeer::$fieldNames[PDDebatePeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Uuid', 'PUserId', 'Title', 'FileName', 'Copyright', 'Description', 'NotePos', 'NoteNeg', 'NbViews', 'Published', 'PublishedAt', 'PublishedBy', 'Favorite', 'Online', 'Homepage', 'Moderated', 'ModeratedPartial', 'ModeratedAt', 'CreatedAt', 'UpdatedAt', 'Slug', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'uuid', 'pUserId', 'title', 'fileName', 'copyright', 'description', 'notePos', 'noteNeg', 'nbViews', 'published', 'publishedAt', 'publishedBy', 'favorite', 'online', 'homepage', 'moderated', 'moderatedPartial', 'moderatedAt', 'createdAt', 'updatedAt', 'slug', ),
        BasePeer::TYPE_COLNAME => array (PDDebatePeer::ID, PDDebatePeer::UUID, PDDebatePeer::P_USER_ID, PDDebatePeer::TITLE, PDDebatePeer::FILE_NAME, PDDebatePeer::COPYRIGHT, PDDebatePeer::DESCRIPTION, PDDebatePeer::NOTE_POS, PDDebatePeer::NOTE_NEG, PDDebatePeer::NB_VIEWS, PDDebatePeer::PUBLISHED, PDDebatePeer::PUBLISHED_AT, PDDebatePeer::PUBLISHED_BY, PDDebatePeer::FAVORITE, PDDebatePeer::ONLINE, PDDebatePeer::HOMEPAGE, PDDebatePeer::MODERATED, PDDebatePeer::MODERATED_PARTIAL, PDDebatePeer::MODERATED_AT, PDDebatePeer::CREATED_AT, PDDebatePeer::UPDATED_AT, PDDebatePeer::SLUG, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'UUID', 'P_USER_ID', 'TITLE', 'FILE_NAME', 'COPYRIGHT', 'DESCRIPTION', 'NOTE_POS', 'NOTE_NEG', 'NB_VIEWS', 'PUBLISHED', 'PUBLISHED_AT', 'PUBLISHED_BY', 'FAVORITE', 'ONLINE', 'HOMEPAGE', 'MODERATED', 'MODERATED_PARTIAL', 'MODERATED_AT', 'CREATED_AT', 'UPDATED_AT', 'SLUG', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'uuid', 'p_user_id', 'title', 'file_name', 'copyright', 'description', 'note_pos', 'note_neg', 'nb_views', 'published', 'published_at', 'published_by', 'favorite', 'online', 'homepage', 'moderated', 'moderated_partial', 'moderated_at', 'created_at', 'updated_at', 'slug', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. PDDebatePeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Uuid' => 1, 'PUserId' => 2, 'Title' => 3, 'FileName' => 4, 'Copyright' => 5, 'Description' => 6, 'NotePos' => 7, 'NoteNeg' => 8, 'NbViews' => 9, 'Published' => 10, 'PublishedAt' => 11, 'PublishedBy' => 12, 'Favorite' => 13, 'Online' => 14, 'Homepage' => 15, 'Moderated' => 16, 'ModeratedPartial' => 17, 'ModeratedAt' => 18, 'CreatedAt' => 19, 'UpdatedAt' => 20, 'Slug' => 21, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'uuid' => 1, 'pUserId' => 2, 'title' => 3, 'fileName' => 4, 'copyright' => 5, 'description' => 6, 'notePos' => 7, 'noteNeg' => 8, 'nbViews' => 9, 'published' => 10, 'publishedAt' => 11, 'publishedBy' => 12, 'favorite' => 13, 'online' => 14, 'homepage' => 15, 'moderated' => 16, 'moderatedPartial' => 17, 'moderatedAt' => 18, 'createdAt' => 19, 'updatedAt' => 20, 'slug' => 21, ),
        BasePeer::TYPE_COLNAME => array (PDDebatePeer::ID => 0, PDDebatePeer::UUID => 1, PDDebatePeer::P_USER_ID => 2, PDDebatePeer::TITLE => 3, PDDebatePeer::FILE_NAME => 4, PDDebatePeer::COPYRIGHT => 5, PDDebatePeer::DESCRIPTION => 6, PDDebatePeer::NOTE_POS => 7, PDDebatePeer::NOTE_NEG => 8, PDDebatePeer::NB_VIEWS => 9, PDDebatePeer::PUBLISHED => 10, PDDebatePeer::PUBLISHED_AT => 11, PDDebatePeer::PUBLISHED_BY => 12, PDDebatePeer::FAVORITE => 13, PDDebatePeer::ONLINE => 14, PDDebatePeer::HOMEPAGE => 15, PDDebatePeer::MODERATED => 16, PDDebatePeer::MODERATED_PARTIAL => 17, PDDebatePeer::MODERATED_AT => 18, PDDebatePeer::CREATED_AT => 19, PDDebatePeer::UPDATED_AT => 20, PDDebatePeer::SLUG => 21, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'UUID' => 1, 'P_USER_ID' => 2, 'TITLE' => 3, 'FILE_NAME' => 4, 'COPYRIGHT' => 5, 'DESCRIPTION' => 6, 'NOTE_POS' => 7, 'NOTE_NEG' => 8, 'NB_VIEWS' => 9, 'PUBLISHED' => 10, 'PUBLISHED_AT' => 11, 'PUBLISHED_BY' => 12, 'FAVORITE' => 13, 'ONLINE' => 14, 'HOMEPAGE' => 15, 'MODERATED' => 16, 'MODERATED_PARTIAL' => 17, 'MODERATED_AT' => 18, 'CREATED_AT' => 19, 'UPDATED_AT' => 20, 'SLUG' => 21, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'uuid' => 1, 'p_user_id' => 2, 'title' => 3, 'file_name' => 4, 'copyright' => 5, 'description' => 6, 'note_pos' => 7, 'note_neg' => 8, 'nb_views' => 9, 'published' => 10, 'published_at' => 11, 'published_by' => 12, 'favorite' => 13, 'online' => 14, 'homepage' => 15, 'moderated' => 16, 'moderated_partial' => 17, 'moderated_at' => 18, 'created_at' => 19, 'updated_at' => 20, 'slug' => 21, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, )
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
        $toNames = PDDebatePeer::getFieldNames($toType);
        $key = isset(PDDebatePeer::$fieldKeys[$fromType][$name]) ? PDDebatePeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(PDDebatePeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, PDDebatePeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return PDDebatePeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. PDDebatePeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(PDDebatePeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(PDDebatePeer::ID);
            $criteria->addSelectColumn(PDDebatePeer::UUID);
            $criteria->addSelectColumn(PDDebatePeer::P_USER_ID);
            $criteria->addSelectColumn(PDDebatePeer::TITLE);
            $criteria->addSelectColumn(PDDebatePeer::FILE_NAME);
            $criteria->addSelectColumn(PDDebatePeer::COPYRIGHT);
            $criteria->addSelectColumn(PDDebatePeer::DESCRIPTION);
            $criteria->addSelectColumn(PDDebatePeer::NOTE_POS);
            $criteria->addSelectColumn(PDDebatePeer::NOTE_NEG);
            $criteria->addSelectColumn(PDDebatePeer::NB_VIEWS);
            $criteria->addSelectColumn(PDDebatePeer::PUBLISHED);
            $criteria->addSelectColumn(PDDebatePeer::PUBLISHED_AT);
            $criteria->addSelectColumn(PDDebatePeer::PUBLISHED_BY);
            $criteria->addSelectColumn(PDDebatePeer::FAVORITE);
            $criteria->addSelectColumn(PDDebatePeer::ONLINE);
            $criteria->addSelectColumn(PDDebatePeer::HOMEPAGE);
            $criteria->addSelectColumn(PDDebatePeer::MODERATED);
            $criteria->addSelectColumn(PDDebatePeer::MODERATED_PARTIAL);
            $criteria->addSelectColumn(PDDebatePeer::MODERATED_AT);
            $criteria->addSelectColumn(PDDebatePeer::CREATED_AT);
            $criteria->addSelectColumn(PDDebatePeer::UPDATED_AT);
            $criteria->addSelectColumn(PDDebatePeer::SLUG);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.uuid');
            $criteria->addSelectColumn($alias . '.p_user_id');
            $criteria->addSelectColumn($alias . '.title');
            $criteria->addSelectColumn($alias . '.file_name');
            $criteria->addSelectColumn($alias . '.copyright');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.note_pos');
            $criteria->addSelectColumn($alias . '.note_neg');
            $criteria->addSelectColumn($alias . '.nb_views');
            $criteria->addSelectColumn($alias . '.published');
            $criteria->addSelectColumn($alias . '.published_at');
            $criteria->addSelectColumn($alias . '.published_by');
            $criteria->addSelectColumn($alias . '.favorite');
            $criteria->addSelectColumn($alias . '.online');
            $criteria->addSelectColumn($alias . '.homepage');
            $criteria->addSelectColumn($alias . '.moderated');
            $criteria->addSelectColumn($alias . '.moderated_partial');
            $criteria->addSelectColumn($alias . '.moderated_at');
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
        $criteria->setPrimaryTableName(PDDebatePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PDDebatePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(PDDebatePeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(PDDebatePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return PDDebate
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = PDDebatePeer::doSelect($critcopy, $con);
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
        return PDDebatePeer::populateObjects(PDDebatePeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(PDDebatePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            PDDebatePeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(PDDebatePeer::DATABASE_NAME);

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
     * @param PDDebate $obj A PDDebate object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            PDDebatePeer::$instances[$key] = $obj;
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
     * @param      mixed $value A PDDebate object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof PDDebate) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or PDDebate object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(PDDebatePeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return PDDebate Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(PDDebatePeer::$instances[$key])) {
                return PDDebatePeer::$instances[$key];
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
        foreach (PDDebatePeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        PDDebatePeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to p_d_debate
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in PUFollowDDPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PUFollowDDPeer::clearInstancePool();
        // Invalidate objects in PUBookmarkDDPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PUBookmarkDDPeer::clearInstancePool();
        // Invalidate objects in PUTrackDDPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PUTrackDDPeer::clearInstancePool();
        // Invalidate objects in PDReactionPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PDReactionPeer::clearInstancePool();
        // Invalidate objects in PDDCommentPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PDDCommentPeer::clearInstancePool();
        // Invalidate objects in PDDTaggedTPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PDDTaggedTPeer::clearInstancePool();
        // Invalidate objects in PMDebateHistoricPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PMDebateHistoricPeer::clearInstancePool();
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
        $cls = PDDebatePeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = PDDebatePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = PDDebatePeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PDDebatePeer::addInstanceToPool($obj, $key);
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
     * @return array (PDDebate object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = PDDebatePeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = PDDebatePeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + PDDebatePeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PDDebatePeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            PDDebatePeer::addInstanceToPool($obj, $key);
        }

        return array($obj, $col);
    }


    /**
     * Returns the number of rows matching criteria, joining the related PUser table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinPUser(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PDDebatePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PDDebatePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PDDebatePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PDDebatePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PDDebatePeer::P_USER_ID, PUserPeer::ID, $join_behavior);

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
     * Selects a collection of PDDebate objects pre-filled with their PUser objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PDDebate objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PDDebatePeer::DATABASE_NAME);
        }

        PDDebatePeer::addSelectColumns($criteria);
        $startcol = PDDebatePeer::NUM_HYDRATE_COLUMNS;
        PUserPeer::addSelectColumns($criteria);

        $criteria->addJoin(PDDebatePeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PDDebatePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PDDebatePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = PDDebatePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PDDebatePeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = PUserPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = PUserPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PUserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    PUserPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (PDDebate) to $obj2 (PUser)
                $obj2->addPDDebate($obj1);

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
        $criteria->setPrimaryTableName(PDDebatePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PDDebatePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PDDebatePeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PDDebatePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PDDebatePeer::P_USER_ID, PUserPeer::ID, $join_behavior);

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
     * Selects a collection of PDDebate objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PDDebate objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PDDebatePeer::DATABASE_NAME);
        }

        PDDebatePeer::addSelectColumns($criteria);
        $startcol2 = PDDebatePeer::NUM_HYDRATE_COLUMNS;

        PUserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PUserPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(PDDebatePeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PDDebatePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PDDebatePeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = PDDebatePeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PDDebatePeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

            // Add objects for joined PUser rows

            $key2 = PUserPeer::getPrimaryKeyHashFromRow($row, $startcol2);
            if ($key2 !== null) {
                $obj2 = PUserPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PUserPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    PUserPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 loaded

                // Add the $obj1 (PDDebate) to the collection in $obj2 (PUser)
                $obj2->addPDDebate($obj1);
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
        return Propel::getDatabaseMap(PDDebatePeer::DATABASE_NAME)->getTable(PDDebatePeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BasePDDebatePeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BasePDDebatePeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \Politizr\Model\map\PDDebateTableMap());
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
        return PDDebatePeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a PDDebate or Criteria object.
     *
     * @param      mixed $values Criteria or PDDebate object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PDDebatePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from PDDebate object
        }

        if ($criteria->containsKey(PDDebatePeer::ID) && $criteria->keyContainsValue(PDDebatePeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PDDebatePeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(PDDebatePeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a PDDebate or Criteria object.
     *
     * @param      mixed $values Criteria or PDDebate object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PDDebatePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(PDDebatePeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(PDDebatePeer::ID);
            $value = $criteria->remove(PDDebatePeer::ID);
            if ($value) {
                $selectCriteria->add(PDDebatePeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(PDDebatePeer::TABLE_NAME);
            }

        } else { // $values is PDDebate object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(PDDebatePeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the p_d_debate table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PDDebatePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(PDDebatePeer::TABLE_NAME, $con, PDDebatePeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PDDebatePeer::clearInstancePool();
            PDDebatePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a PDDebate or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or PDDebate object or primary key or array of primary keys
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
            $con = Propel::getConnection(PDDebatePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            PDDebatePeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof PDDebate) { // it's a model object
            // invalidate the cache for this single object
            PDDebatePeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PDDebatePeer::DATABASE_NAME);
            $criteria->add(PDDebatePeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                PDDebatePeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(PDDebatePeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            PDDebatePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given PDDebate object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param PDDebate $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(PDDebatePeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(PDDebatePeer::TABLE_NAME);

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

        return BasePeer::doValidate(PDDebatePeer::DATABASE_NAME, PDDebatePeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return PDDebate
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = PDDebatePeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(PDDebatePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(PDDebatePeer::DATABASE_NAME);
        $criteria->add(PDDebatePeer::ID, $pk);

        $v = PDDebatePeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return PDDebate[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PDDebatePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(PDDebatePeer::DATABASE_NAME);
            $criteria->add(PDDebatePeer::ID, $pks, Criteria::IN);
            $objs = PDDebatePeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BasePDDebatePeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BasePDDebatePeer::buildTableMap();

