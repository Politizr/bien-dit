<?php

namespace Politizr\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Politizr\Model\PCTopicPeer;
use Politizr\Model\PDDebatePeer;
use Politizr\Model\PDRCommentPeer;
use Politizr\Model\PDRTaggedTPeer;
use Politizr\Model\PDReaction;
use Politizr\Model\PDReactionPeer;
use Politizr\Model\PLCityPeer;
use Politizr\Model\PLCountryPeer;
use Politizr\Model\PLDepartmentPeer;
use Politizr\Model\PLRegionPeer;
use Politizr\Model\PMReactionHistoricPeer;
use Politizr\Model\PUBookmarkDRPeer;
use Politizr\Model\PUTrackDRPeer;
use Politizr\Model\PUserPeer;
use Politizr\Model\map\PDReactionTableMap;

abstract class BasePDReactionPeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'p_d_reaction';

    /** the related Propel class for this table */
    const OM_CLASS = 'Politizr\\Model\\PDReaction';

    /** the related TableMap class for this table */
    const TM_CLASS = 'Politizr\\Model\\map\\PDReactionTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 32;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 32;

    /** the column name for the id field */
    const ID = 'p_d_reaction.id';

    /** the column name for the uuid field */
    const UUID = 'p_d_reaction.uuid';

    /** the column name for the p_user_id field */
    const P_USER_ID = 'p_d_reaction.p_user_id';

    /** the column name for the p_d_debate_id field */
    const P_D_DEBATE_ID = 'p_d_reaction.p_d_debate_id';

    /** the column name for the parent_reaction_id field */
    const PARENT_REACTION_ID = 'p_d_reaction.parent_reaction_id';

    /** the column name for the p_l_city_id field */
    const P_L_CITY_ID = 'p_d_reaction.p_l_city_id';

    /** the column name for the p_l_department_id field */
    const P_L_DEPARTMENT_ID = 'p_d_reaction.p_l_department_id';

    /** the column name for the p_l_region_id field */
    const P_L_REGION_ID = 'p_d_reaction.p_l_region_id';

    /** the column name for the p_l_country_id field */
    const P_L_COUNTRY_ID = 'p_d_reaction.p_l_country_id';

    /** the column name for the p_c_topic_id field */
    const P_C_TOPIC_ID = 'p_d_reaction.p_c_topic_id';

    /** the column name for the title field */
    const TITLE = 'p_d_reaction.title';

    /** the column name for the file_name field */
    const FILE_NAME = 'p_d_reaction.file_name';

    /** the column name for the copyright field */
    const COPYRIGHT = 'p_d_reaction.copyright';

    /** the column name for the description field */
    const DESCRIPTION = 'p_d_reaction.description';

    /** the column name for the note_pos field */
    const NOTE_POS = 'p_d_reaction.note_pos';

    /** the column name for the note_neg field */
    const NOTE_NEG = 'p_d_reaction.note_neg';

    /** the column name for the nb_views field */
    const NB_VIEWS = 'p_d_reaction.nb_views';

    /** the column name for the published field */
    const PUBLISHED = 'p_d_reaction.published';

    /** the column name for the published_at field */
    const PUBLISHED_AT = 'p_d_reaction.published_at';

    /** the column name for the published_by field */
    const PUBLISHED_BY = 'p_d_reaction.published_by';

    /** the column name for the favorite field */
    const FAVORITE = 'p_d_reaction.favorite';

    /** the column name for the online field */
    const ONLINE = 'p_d_reaction.online';

    /** the column name for the homepage field */
    const HOMEPAGE = 'p_d_reaction.homepage';

    /** the column name for the moderated field */
    const MODERATED = 'p_d_reaction.moderated';

    /** the column name for the moderated_partial field */
    const MODERATED_PARTIAL = 'p_d_reaction.moderated_partial';

    /** the column name for the moderated_at field */
    const MODERATED_AT = 'p_d_reaction.moderated_at';

    /** the column name for the created_at field */
    const CREATED_AT = 'p_d_reaction.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'p_d_reaction.updated_at';

    /** the column name for the slug field */
    const SLUG = 'p_d_reaction.slug';

    /** the column name for the tree_left field */
    const TREE_LEFT = 'p_d_reaction.tree_left';

    /** the column name for the tree_right field */
    const TREE_RIGHT = 'p_d_reaction.tree_right';

    /** the column name for the tree_level field */
    const TREE_LEVEL = 'p_d_reaction.tree_level';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of PDReaction objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array PDReaction[]
     */
    public static $instances = array();


    // nested_set behavior

    /**
     * Left column for the set
     */
    const LEFT_COL = 'p_d_reaction.tree_left';

    /**
     * Right column for the set
     */
    const RIGHT_COL = 'p_d_reaction.tree_right';

    /**
     * Level column for the set
     */
    const LEVEL_COL = 'p_d_reaction.tree_level';

    /**
     * Scope column for the set
     */
    const SCOPE_COL = 'p_d_reaction.p_d_debate_id';

    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. PDReactionPeer::$fieldNames[PDReactionPeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Uuid', 'PUserId', 'PDDebateId', 'ParentReactionId', 'PLCityId', 'PLDepartmentId', 'PLRegionId', 'PLCountryId', 'PCTopicId', 'Title', 'FileName', 'Copyright', 'Description', 'NotePos', 'NoteNeg', 'NbViews', 'Published', 'PublishedAt', 'PublishedBy', 'Favorite', 'Online', 'Homepage', 'Moderated', 'ModeratedPartial', 'ModeratedAt', 'CreatedAt', 'UpdatedAt', 'Slug', 'TreeLeft', 'TreeRight', 'TreeLevel', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'uuid', 'pUserId', 'pDDebateId', 'parentReactionId', 'pLCityId', 'pLDepartmentId', 'pLRegionId', 'pLCountryId', 'pCTopicId', 'title', 'fileName', 'copyright', 'description', 'notePos', 'noteNeg', 'nbViews', 'published', 'publishedAt', 'publishedBy', 'favorite', 'online', 'homepage', 'moderated', 'moderatedPartial', 'moderatedAt', 'createdAt', 'updatedAt', 'slug', 'treeLeft', 'treeRight', 'treeLevel', ),
        BasePeer::TYPE_COLNAME => array (PDReactionPeer::ID, PDReactionPeer::UUID, PDReactionPeer::P_USER_ID, PDReactionPeer::P_D_DEBATE_ID, PDReactionPeer::PARENT_REACTION_ID, PDReactionPeer::P_L_CITY_ID, PDReactionPeer::P_L_DEPARTMENT_ID, PDReactionPeer::P_L_REGION_ID, PDReactionPeer::P_L_COUNTRY_ID, PDReactionPeer::P_C_TOPIC_ID, PDReactionPeer::TITLE, PDReactionPeer::FILE_NAME, PDReactionPeer::COPYRIGHT, PDReactionPeer::DESCRIPTION, PDReactionPeer::NOTE_POS, PDReactionPeer::NOTE_NEG, PDReactionPeer::NB_VIEWS, PDReactionPeer::PUBLISHED, PDReactionPeer::PUBLISHED_AT, PDReactionPeer::PUBLISHED_BY, PDReactionPeer::FAVORITE, PDReactionPeer::ONLINE, PDReactionPeer::HOMEPAGE, PDReactionPeer::MODERATED, PDReactionPeer::MODERATED_PARTIAL, PDReactionPeer::MODERATED_AT, PDReactionPeer::CREATED_AT, PDReactionPeer::UPDATED_AT, PDReactionPeer::SLUG, PDReactionPeer::TREE_LEFT, PDReactionPeer::TREE_RIGHT, PDReactionPeer::TREE_LEVEL, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'UUID', 'P_USER_ID', 'P_D_DEBATE_ID', 'PARENT_REACTION_ID', 'P_L_CITY_ID', 'P_L_DEPARTMENT_ID', 'P_L_REGION_ID', 'P_L_COUNTRY_ID', 'P_C_TOPIC_ID', 'TITLE', 'FILE_NAME', 'COPYRIGHT', 'DESCRIPTION', 'NOTE_POS', 'NOTE_NEG', 'NB_VIEWS', 'PUBLISHED', 'PUBLISHED_AT', 'PUBLISHED_BY', 'FAVORITE', 'ONLINE', 'HOMEPAGE', 'MODERATED', 'MODERATED_PARTIAL', 'MODERATED_AT', 'CREATED_AT', 'UPDATED_AT', 'SLUG', 'TREE_LEFT', 'TREE_RIGHT', 'TREE_LEVEL', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'uuid', 'p_user_id', 'p_d_debate_id', 'parent_reaction_id', 'p_l_city_id', 'p_l_department_id', 'p_l_region_id', 'p_l_country_id', 'p_c_topic_id', 'title', 'file_name', 'copyright', 'description', 'note_pos', 'note_neg', 'nb_views', 'published', 'published_at', 'published_by', 'favorite', 'online', 'homepage', 'moderated', 'moderated_partial', 'moderated_at', 'created_at', 'updated_at', 'slug', 'tree_left', 'tree_right', 'tree_level', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. PDReactionPeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Uuid' => 1, 'PUserId' => 2, 'PDDebateId' => 3, 'ParentReactionId' => 4, 'PLCityId' => 5, 'PLDepartmentId' => 6, 'PLRegionId' => 7, 'PLCountryId' => 8, 'PCTopicId' => 9, 'Title' => 10, 'FileName' => 11, 'Copyright' => 12, 'Description' => 13, 'NotePos' => 14, 'NoteNeg' => 15, 'NbViews' => 16, 'Published' => 17, 'PublishedAt' => 18, 'PublishedBy' => 19, 'Favorite' => 20, 'Online' => 21, 'Homepage' => 22, 'Moderated' => 23, 'ModeratedPartial' => 24, 'ModeratedAt' => 25, 'CreatedAt' => 26, 'UpdatedAt' => 27, 'Slug' => 28, 'TreeLeft' => 29, 'TreeRight' => 30, 'TreeLevel' => 31, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'uuid' => 1, 'pUserId' => 2, 'pDDebateId' => 3, 'parentReactionId' => 4, 'pLCityId' => 5, 'pLDepartmentId' => 6, 'pLRegionId' => 7, 'pLCountryId' => 8, 'pCTopicId' => 9, 'title' => 10, 'fileName' => 11, 'copyright' => 12, 'description' => 13, 'notePos' => 14, 'noteNeg' => 15, 'nbViews' => 16, 'published' => 17, 'publishedAt' => 18, 'publishedBy' => 19, 'favorite' => 20, 'online' => 21, 'homepage' => 22, 'moderated' => 23, 'moderatedPartial' => 24, 'moderatedAt' => 25, 'createdAt' => 26, 'updatedAt' => 27, 'slug' => 28, 'treeLeft' => 29, 'treeRight' => 30, 'treeLevel' => 31, ),
        BasePeer::TYPE_COLNAME => array (PDReactionPeer::ID => 0, PDReactionPeer::UUID => 1, PDReactionPeer::P_USER_ID => 2, PDReactionPeer::P_D_DEBATE_ID => 3, PDReactionPeer::PARENT_REACTION_ID => 4, PDReactionPeer::P_L_CITY_ID => 5, PDReactionPeer::P_L_DEPARTMENT_ID => 6, PDReactionPeer::P_L_REGION_ID => 7, PDReactionPeer::P_L_COUNTRY_ID => 8, PDReactionPeer::P_C_TOPIC_ID => 9, PDReactionPeer::TITLE => 10, PDReactionPeer::FILE_NAME => 11, PDReactionPeer::COPYRIGHT => 12, PDReactionPeer::DESCRIPTION => 13, PDReactionPeer::NOTE_POS => 14, PDReactionPeer::NOTE_NEG => 15, PDReactionPeer::NB_VIEWS => 16, PDReactionPeer::PUBLISHED => 17, PDReactionPeer::PUBLISHED_AT => 18, PDReactionPeer::PUBLISHED_BY => 19, PDReactionPeer::FAVORITE => 20, PDReactionPeer::ONLINE => 21, PDReactionPeer::HOMEPAGE => 22, PDReactionPeer::MODERATED => 23, PDReactionPeer::MODERATED_PARTIAL => 24, PDReactionPeer::MODERATED_AT => 25, PDReactionPeer::CREATED_AT => 26, PDReactionPeer::UPDATED_AT => 27, PDReactionPeer::SLUG => 28, PDReactionPeer::TREE_LEFT => 29, PDReactionPeer::TREE_RIGHT => 30, PDReactionPeer::TREE_LEVEL => 31, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'UUID' => 1, 'P_USER_ID' => 2, 'P_D_DEBATE_ID' => 3, 'PARENT_REACTION_ID' => 4, 'P_L_CITY_ID' => 5, 'P_L_DEPARTMENT_ID' => 6, 'P_L_REGION_ID' => 7, 'P_L_COUNTRY_ID' => 8, 'P_C_TOPIC_ID' => 9, 'TITLE' => 10, 'FILE_NAME' => 11, 'COPYRIGHT' => 12, 'DESCRIPTION' => 13, 'NOTE_POS' => 14, 'NOTE_NEG' => 15, 'NB_VIEWS' => 16, 'PUBLISHED' => 17, 'PUBLISHED_AT' => 18, 'PUBLISHED_BY' => 19, 'FAVORITE' => 20, 'ONLINE' => 21, 'HOMEPAGE' => 22, 'MODERATED' => 23, 'MODERATED_PARTIAL' => 24, 'MODERATED_AT' => 25, 'CREATED_AT' => 26, 'UPDATED_AT' => 27, 'SLUG' => 28, 'TREE_LEFT' => 29, 'TREE_RIGHT' => 30, 'TREE_LEVEL' => 31, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'uuid' => 1, 'p_user_id' => 2, 'p_d_debate_id' => 3, 'parent_reaction_id' => 4, 'p_l_city_id' => 5, 'p_l_department_id' => 6, 'p_l_region_id' => 7, 'p_l_country_id' => 8, 'p_c_topic_id' => 9, 'title' => 10, 'file_name' => 11, 'copyright' => 12, 'description' => 13, 'note_pos' => 14, 'note_neg' => 15, 'nb_views' => 16, 'published' => 17, 'published_at' => 18, 'published_by' => 19, 'favorite' => 20, 'online' => 21, 'homepage' => 22, 'moderated' => 23, 'moderated_partial' => 24, 'moderated_at' => 25, 'created_at' => 26, 'updated_at' => 27, 'slug' => 28, 'tree_left' => 29, 'tree_right' => 30, 'tree_level' => 31, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, )
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
        $toNames = PDReactionPeer::getFieldNames($toType);
        $key = isset(PDReactionPeer::$fieldKeys[$fromType][$name]) ? PDReactionPeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(PDReactionPeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, PDReactionPeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return PDReactionPeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. PDReactionPeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(PDReactionPeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(PDReactionPeer::ID);
            $criteria->addSelectColumn(PDReactionPeer::UUID);
            $criteria->addSelectColumn(PDReactionPeer::P_USER_ID);
            $criteria->addSelectColumn(PDReactionPeer::P_D_DEBATE_ID);
            $criteria->addSelectColumn(PDReactionPeer::PARENT_REACTION_ID);
            $criteria->addSelectColumn(PDReactionPeer::P_L_CITY_ID);
            $criteria->addSelectColumn(PDReactionPeer::P_L_DEPARTMENT_ID);
            $criteria->addSelectColumn(PDReactionPeer::P_L_REGION_ID);
            $criteria->addSelectColumn(PDReactionPeer::P_L_COUNTRY_ID);
            $criteria->addSelectColumn(PDReactionPeer::P_C_TOPIC_ID);
            $criteria->addSelectColumn(PDReactionPeer::TITLE);
            $criteria->addSelectColumn(PDReactionPeer::FILE_NAME);
            $criteria->addSelectColumn(PDReactionPeer::COPYRIGHT);
            $criteria->addSelectColumn(PDReactionPeer::DESCRIPTION);
            $criteria->addSelectColumn(PDReactionPeer::NOTE_POS);
            $criteria->addSelectColumn(PDReactionPeer::NOTE_NEG);
            $criteria->addSelectColumn(PDReactionPeer::NB_VIEWS);
            $criteria->addSelectColumn(PDReactionPeer::PUBLISHED);
            $criteria->addSelectColumn(PDReactionPeer::PUBLISHED_AT);
            $criteria->addSelectColumn(PDReactionPeer::PUBLISHED_BY);
            $criteria->addSelectColumn(PDReactionPeer::FAVORITE);
            $criteria->addSelectColumn(PDReactionPeer::ONLINE);
            $criteria->addSelectColumn(PDReactionPeer::HOMEPAGE);
            $criteria->addSelectColumn(PDReactionPeer::MODERATED);
            $criteria->addSelectColumn(PDReactionPeer::MODERATED_PARTIAL);
            $criteria->addSelectColumn(PDReactionPeer::MODERATED_AT);
            $criteria->addSelectColumn(PDReactionPeer::CREATED_AT);
            $criteria->addSelectColumn(PDReactionPeer::UPDATED_AT);
            $criteria->addSelectColumn(PDReactionPeer::SLUG);
            $criteria->addSelectColumn(PDReactionPeer::TREE_LEFT);
            $criteria->addSelectColumn(PDReactionPeer::TREE_RIGHT);
            $criteria->addSelectColumn(PDReactionPeer::TREE_LEVEL);
        } else {
            $criteria->addSelectColumn($alias . '.id');
            $criteria->addSelectColumn($alias . '.uuid');
            $criteria->addSelectColumn($alias . '.p_user_id');
            $criteria->addSelectColumn($alias . '.p_d_debate_id');
            $criteria->addSelectColumn($alias . '.parent_reaction_id');
            $criteria->addSelectColumn($alias . '.p_l_city_id');
            $criteria->addSelectColumn($alias . '.p_l_department_id');
            $criteria->addSelectColumn($alias . '.p_l_region_id');
            $criteria->addSelectColumn($alias . '.p_l_country_id');
            $criteria->addSelectColumn($alias . '.p_c_topic_id');
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
            $criteria->addSelectColumn($alias . '.tree_left');
            $criteria->addSelectColumn($alias . '.tree_right');
            $criteria->addSelectColumn($alias . '.tree_level');
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
        $criteria->setPrimaryTableName(PDReactionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PDReactionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(PDReactionPeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return PDReaction
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = PDReactionPeer::doSelect($critcopy, $con);
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
        return PDReactionPeer::populateObjects(PDReactionPeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            PDReactionPeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(PDReactionPeer::DATABASE_NAME);

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
     * @param PDReaction $obj A PDReaction object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            PDReactionPeer::$instances[$key] = $obj;
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
     * @param      mixed $value A PDReaction object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof PDReaction) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or PDReaction object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(PDReactionPeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return PDReaction Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(PDReactionPeer::$instances[$key])) {
                return PDReactionPeer::$instances[$key];
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
        foreach (PDReactionPeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        PDReactionPeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to p_d_reaction
     * by a foreign key with ON DELETE CASCADE
     */
    public static function clearRelatedInstancePool()
    {
        // Invalidate objects in PUBookmarkDRPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PUBookmarkDRPeer::clearInstancePool();
        // Invalidate objects in PUTrackDRPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PUTrackDRPeer::clearInstancePool();
        // Invalidate objects in PDRCommentPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PDRCommentPeer::clearInstancePool();
        // Invalidate objects in PDRTaggedTPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PDRTaggedTPeer::clearInstancePool();
        // Invalidate objects in PMReactionHistoricPeer instance pool,
        // since one or more of them may be deleted by ON DELETE CASCADE/SETNULL rule.
        PMReactionHistoricPeer::clearInstancePool();
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
        $cls = PDReactionPeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = PDReactionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = PDReactionPeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PDReactionPeer::addInstanceToPool($obj, $key);
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
     * @return array (PDReaction object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = PDReactionPeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = PDReactionPeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + PDReactionPeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PDReactionPeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            PDReactionPeer::addInstanceToPool($obj, $key);
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
        $criteria->setPrimaryTableName(PDReactionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PDReactionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PDReactionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PDReactionPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related PDDebate table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinPDDebate(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PDReactionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PDReactionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PDReactionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PDReactionPeer::P_D_DEBATE_ID, PDDebatePeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related PLCity table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinPLCity(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PDReactionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PDReactionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PDReactionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PDReactionPeer::P_L_CITY_ID, PLCityPeer::ID, $join_behavior);

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
        $criteria->setPrimaryTableName(PDReactionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PDReactionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PDReactionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PDReactionPeer::P_L_DEPARTMENT_ID, PLDepartmentPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related PLRegion table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinPLRegion(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PDReactionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PDReactionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PDReactionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PDReactionPeer::P_L_REGION_ID, PLRegionPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related PLCountry table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinPLCountry(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PDReactionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PDReactionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PDReactionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PDReactionPeer::P_L_COUNTRY_ID, PLCountryPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related PCTopic table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinPCTopic(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PDReactionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PDReactionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PDReactionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PDReactionPeer::P_C_TOPIC_ID, PCTopicPeer::ID, $join_behavior);

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
     * Selects a collection of PDReaction objects pre-filled with their PUser objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PDReaction objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PDReactionPeer::DATABASE_NAME);
        }

        PDReactionPeer::addSelectColumns($criteria);
        $startcol = PDReactionPeer::NUM_HYDRATE_COLUMNS;
        PUserPeer::addSelectColumns($criteria);

        $criteria->addJoin(PDReactionPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PDReactionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PDReactionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = PDReactionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PDReactionPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (PDReaction) to $obj2 (PUser)
                $obj2->addPDReaction($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of PDReaction objects pre-filled with their PDDebate objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PDReaction objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPDDebate(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PDReactionPeer::DATABASE_NAME);
        }

        PDReactionPeer::addSelectColumns($criteria);
        $startcol = PDReactionPeer::NUM_HYDRATE_COLUMNS;
        PDDebatePeer::addSelectColumns($criteria);

        $criteria->addJoin(PDReactionPeer::P_D_DEBATE_ID, PDDebatePeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PDReactionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PDReactionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = PDReactionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PDReactionPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = PDDebatePeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = PDDebatePeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PDDebatePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    PDDebatePeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (PDReaction) to $obj2 (PDDebate)
                $obj2->addPDReaction($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of PDReaction objects pre-filled with their PLCity objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PDReaction objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPLCity(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PDReactionPeer::DATABASE_NAME);
        }

        PDReactionPeer::addSelectColumns($criteria);
        $startcol = PDReactionPeer::NUM_HYDRATE_COLUMNS;
        PLCityPeer::addSelectColumns($criteria);

        $criteria->addJoin(PDReactionPeer::P_L_CITY_ID, PLCityPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PDReactionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PDReactionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = PDReactionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PDReactionPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = PLCityPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = PLCityPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PLCityPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    PLCityPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (PDReaction) to $obj2 (PLCity)
                $obj2->addPDReaction($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of PDReaction objects pre-filled with their PLDepartment objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PDReaction objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPLDepartment(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PDReactionPeer::DATABASE_NAME);
        }

        PDReactionPeer::addSelectColumns($criteria);
        $startcol = PDReactionPeer::NUM_HYDRATE_COLUMNS;
        PLDepartmentPeer::addSelectColumns($criteria);

        $criteria->addJoin(PDReactionPeer::P_L_DEPARTMENT_ID, PLDepartmentPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PDReactionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PDReactionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = PDReactionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PDReactionPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (PDReaction) to $obj2 (PLDepartment)
                $obj2->addPDReaction($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of PDReaction objects pre-filled with their PLRegion objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PDReaction objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPLRegion(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PDReactionPeer::DATABASE_NAME);
        }

        PDReactionPeer::addSelectColumns($criteria);
        $startcol = PDReactionPeer::NUM_HYDRATE_COLUMNS;
        PLRegionPeer::addSelectColumns($criteria);

        $criteria->addJoin(PDReactionPeer::P_L_REGION_ID, PLRegionPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PDReactionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PDReactionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = PDReactionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PDReactionPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = PLRegionPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = PLRegionPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PLRegionPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    PLRegionPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (PDReaction) to $obj2 (PLRegion)
                $obj2->addPDReaction($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of PDReaction objects pre-filled with their PLCountry objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PDReaction objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPLCountry(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PDReactionPeer::DATABASE_NAME);
        }

        PDReactionPeer::addSelectColumns($criteria);
        $startcol = PDReactionPeer::NUM_HYDRATE_COLUMNS;
        PLCountryPeer::addSelectColumns($criteria);

        $criteria->addJoin(PDReactionPeer::P_L_COUNTRY_ID, PLCountryPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PDReactionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PDReactionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = PDReactionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PDReactionPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = PLCountryPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = PLCountryPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PLCountryPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    PLCountryPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (PDReaction) to $obj2 (PLCountry)
                $obj2->addPDReaction($obj1);

            } // if joined row was not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of PDReaction objects pre-filled with their PCTopic objects.
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PDReaction objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinPCTopic(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PDReactionPeer::DATABASE_NAME);
        }

        PDReactionPeer::addSelectColumns($criteria);
        $startcol = PDReactionPeer::NUM_HYDRATE_COLUMNS;
        PCTopicPeer::addSelectColumns($criteria);

        $criteria->addJoin(PDReactionPeer::P_C_TOPIC_ID, PCTopicPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PDReactionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PDReactionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {

                $cls = PDReactionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PDReactionPeer::addInstanceToPool($obj1, $key1);
            } // if $obj1 already loaded

            $key2 = PCTopicPeer::getPrimaryKeyHashFromRow($row, $startcol);
            if ($key2 !== null) {
                $obj2 = PCTopicPeer::getInstanceFromPool($key2);
                if (!$obj2) {

                    $cls = PCTopicPeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol);
                    PCTopicPeer::addInstanceToPool($obj2, $key2);
                } // if obj2 already loaded

                // Add the $obj1 (PDReaction) to $obj2 (PCTopic)
                $obj2->addPDReaction($obj1);

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
        $criteria->setPrimaryTableName(PDReactionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PDReactionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count

        // Set the correct dbName
        $criteria->setDbName(PDReactionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PDReactionPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_D_DEBATE_ID, PDDebatePeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_CITY_ID, PLCityPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_DEPARTMENT_ID, PLDepartmentPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_REGION_ID, PLRegionPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_COUNTRY_ID, PLCountryPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_C_TOPIC_ID, PCTopicPeer::ID, $join_behavior);

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
     * Selects a collection of PDReaction objects pre-filled with all related objects.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PDReaction objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAll(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PDReactionPeer::DATABASE_NAME);
        }

        PDReactionPeer::addSelectColumns($criteria);
        $startcol2 = PDReactionPeer::NUM_HYDRATE_COLUMNS;

        PUserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PUserPeer::NUM_HYDRATE_COLUMNS;

        PDDebatePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PDDebatePeer::NUM_HYDRATE_COLUMNS;

        PLCityPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PLCityPeer::NUM_HYDRATE_COLUMNS;

        PLDepartmentPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + PLDepartmentPeer::NUM_HYDRATE_COLUMNS;

        PLRegionPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + PLRegionPeer::NUM_HYDRATE_COLUMNS;

        PLCountryPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + PLCountryPeer::NUM_HYDRATE_COLUMNS;

        PCTopicPeer::addSelectColumns($criteria);
        $startcol9 = $startcol8 + PCTopicPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(PDReactionPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_D_DEBATE_ID, PDDebatePeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_CITY_ID, PLCityPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_DEPARTMENT_ID, PLDepartmentPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_REGION_ID, PLRegionPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_COUNTRY_ID, PLCountryPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_C_TOPIC_ID, PCTopicPeer::ID, $join_behavior);

        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PDReactionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PDReactionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = PDReactionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PDReactionPeer::addInstanceToPool($obj1, $key1);
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

                // Add the $obj1 (PDReaction) to the collection in $obj2 (PUser)
                $obj2->addPDReaction($obj1);
            } // if joined row not null

            // Add objects for joined PDDebate rows

            $key3 = PDDebatePeer::getPrimaryKeyHashFromRow($row, $startcol3);
            if ($key3 !== null) {
                $obj3 = PDDebatePeer::getInstanceFromPool($key3);
                if (!$obj3) {

                    $cls = PDDebatePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PDDebatePeer::addInstanceToPool($obj3, $key3);
                } // if obj3 loaded

                // Add the $obj1 (PDReaction) to the collection in $obj3 (PDDebate)
                $obj3->addPDReaction($obj1);
            } // if joined row not null

            // Add objects for joined PLCity rows

            $key4 = PLCityPeer::getPrimaryKeyHashFromRow($row, $startcol4);
            if ($key4 !== null) {
                $obj4 = PLCityPeer::getInstanceFromPool($key4);
                if (!$obj4) {

                    $cls = PLCityPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PLCityPeer::addInstanceToPool($obj4, $key4);
                } // if obj4 loaded

                // Add the $obj1 (PDReaction) to the collection in $obj4 (PLCity)
                $obj4->addPDReaction($obj1);
            } // if joined row not null

            // Add objects for joined PLDepartment rows

            $key5 = PLDepartmentPeer::getPrimaryKeyHashFromRow($row, $startcol5);
            if ($key5 !== null) {
                $obj5 = PLDepartmentPeer::getInstanceFromPool($key5);
                if (!$obj5) {

                    $cls = PLDepartmentPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    PLDepartmentPeer::addInstanceToPool($obj5, $key5);
                } // if obj5 loaded

                // Add the $obj1 (PDReaction) to the collection in $obj5 (PLDepartment)
                $obj5->addPDReaction($obj1);
            } // if joined row not null

            // Add objects for joined PLRegion rows

            $key6 = PLRegionPeer::getPrimaryKeyHashFromRow($row, $startcol6);
            if ($key6 !== null) {
                $obj6 = PLRegionPeer::getInstanceFromPool($key6);
                if (!$obj6) {

                    $cls = PLRegionPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    PLRegionPeer::addInstanceToPool($obj6, $key6);
                } // if obj6 loaded

                // Add the $obj1 (PDReaction) to the collection in $obj6 (PLRegion)
                $obj6->addPDReaction($obj1);
            } // if joined row not null

            // Add objects for joined PLCountry rows

            $key7 = PLCountryPeer::getPrimaryKeyHashFromRow($row, $startcol7);
            if ($key7 !== null) {
                $obj7 = PLCountryPeer::getInstanceFromPool($key7);
                if (!$obj7) {

                    $cls = PLCountryPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    PLCountryPeer::addInstanceToPool($obj7, $key7);
                } // if obj7 loaded

                // Add the $obj1 (PDReaction) to the collection in $obj7 (PLCountry)
                $obj7->addPDReaction($obj1);
            } // if joined row not null

            // Add objects for joined PCTopic rows

            $key8 = PCTopicPeer::getPrimaryKeyHashFromRow($row, $startcol8);
            if ($key8 !== null) {
                $obj8 = PCTopicPeer::getInstanceFromPool($key8);
                if (!$obj8) {

                    $cls = PCTopicPeer::getOMClass();

                    $obj8 = new $cls();
                    $obj8->hydrate($row, $startcol8);
                    PCTopicPeer::addInstanceToPool($obj8, $key8);
                } // if obj8 loaded

                // Add the $obj1 (PDReaction) to the collection in $obj8 (PCTopic)
                $obj8->addPDReaction($obj1);
            } // if joined row not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
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
    public static function doCountJoinAllExceptPUser(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PDReactionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PDReactionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(PDReactionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PDReactionPeer::P_D_DEBATE_ID, PDDebatePeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_CITY_ID, PLCityPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_DEPARTMENT_ID, PLDepartmentPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_REGION_ID, PLRegionPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_COUNTRY_ID, PLCountryPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_C_TOPIC_ID, PCTopicPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related PDDebate table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptPDDebate(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PDReactionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PDReactionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(PDReactionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PDReactionPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_CITY_ID, PLCityPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_DEPARTMENT_ID, PLDepartmentPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_REGION_ID, PLRegionPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_COUNTRY_ID, PLCountryPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_C_TOPIC_ID, PCTopicPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related PLCity table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptPLCity(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PDReactionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PDReactionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(PDReactionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PDReactionPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_D_DEBATE_ID, PDDebatePeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_DEPARTMENT_ID, PLDepartmentPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_REGION_ID, PLRegionPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_COUNTRY_ID, PLCountryPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_C_TOPIC_ID, PCTopicPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related PLDepartment table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptPLDepartment(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PDReactionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PDReactionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(PDReactionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PDReactionPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_D_DEBATE_ID, PDDebatePeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_CITY_ID, PLCityPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_REGION_ID, PLRegionPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_COUNTRY_ID, PLCountryPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_C_TOPIC_ID, PCTopicPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related PLRegion table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptPLRegion(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PDReactionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PDReactionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(PDReactionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PDReactionPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_D_DEBATE_ID, PDDebatePeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_CITY_ID, PLCityPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_DEPARTMENT_ID, PLDepartmentPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_COUNTRY_ID, PLCountryPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_C_TOPIC_ID, PCTopicPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related PLCountry table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptPLCountry(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PDReactionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PDReactionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(PDReactionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PDReactionPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_D_DEBATE_ID, PDDebatePeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_CITY_ID, PLCityPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_DEPARTMENT_ID, PLDepartmentPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_REGION_ID, PLRegionPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_C_TOPIC_ID, PCTopicPeer::ID, $join_behavior);

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
     * Returns the number of rows matching criteria, joining the related PCTopic table
     *
     * @param      Criteria $criteria
     * @param      boolean $distinct Whether to select only distinct columns; deprecated: use Criteria->setDistinct() instead.
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return int Number of matching rows.
     */
    public static function doCountJoinAllExceptPCTopic(Criteria $criteria, $distinct = false, PropelPDO $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        // we're going to modify criteria, so copy it first
        $criteria = clone $criteria;

        // We need to set the primary table name, since in the case that there are no WHERE columns
        // it will be impossible for the BasePeer::createSelectSql() method to determine which
        // tables go into the FROM clause.
        $criteria->setPrimaryTableName(PDReactionPeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PDReactionPeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY should not affect count

        // Set the correct dbName
        $criteria->setDbName(PDReactionPeer::DATABASE_NAME);

        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria->addJoin(PDReactionPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_D_DEBATE_ID, PDDebatePeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_CITY_ID, PLCityPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_DEPARTMENT_ID, PLDepartmentPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_REGION_ID, PLRegionPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_COUNTRY_ID, PLCountryPeer::ID, $join_behavior);

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
     * Selects a collection of PDReaction objects pre-filled with all related objects except PUser.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PDReaction objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptPUser(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PDReactionPeer::DATABASE_NAME);
        }

        PDReactionPeer::addSelectColumns($criteria);
        $startcol2 = PDReactionPeer::NUM_HYDRATE_COLUMNS;

        PDDebatePeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PDDebatePeer::NUM_HYDRATE_COLUMNS;

        PLCityPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PLCityPeer::NUM_HYDRATE_COLUMNS;

        PLDepartmentPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PLDepartmentPeer::NUM_HYDRATE_COLUMNS;

        PLRegionPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + PLRegionPeer::NUM_HYDRATE_COLUMNS;

        PLCountryPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + PLCountryPeer::NUM_HYDRATE_COLUMNS;

        PCTopicPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + PCTopicPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(PDReactionPeer::P_D_DEBATE_ID, PDDebatePeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_CITY_ID, PLCityPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_DEPARTMENT_ID, PLDepartmentPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_REGION_ID, PLRegionPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_COUNTRY_ID, PLCountryPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_C_TOPIC_ID, PCTopicPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PDReactionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PDReactionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = PDReactionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PDReactionPeer::addInstanceToPool($obj1, $key1);
            } // if obj1 already loaded

                // Add objects for joined PDDebate rows

                $key2 = PDDebatePeer::getPrimaryKeyHashFromRow($row, $startcol2);
                if ($key2 !== null) {
                    $obj2 = PDDebatePeer::getInstanceFromPool($key2);
                    if (!$obj2) {

                        $cls = PDDebatePeer::getOMClass();

                    $obj2 = new $cls();
                    $obj2->hydrate($row, $startcol2);
                    PDDebatePeer::addInstanceToPool($obj2, $key2);
                } // if $obj2 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj2 (PDDebate)
                $obj2->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLCity rows

                $key3 = PLCityPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = PLCityPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = PLCityPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PLCityPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj3 (PLCity)
                $obj3->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLDepartment rows

                $key4 = PLDepartmentPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = PLDepartmentPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = PLDepartmentPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PLDepartmentPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj4 (PLDepartment)
                $obj4->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLRegion rows

                $key5 = PLRegionPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = PLRegionPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = PLRegionPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    PLRegionPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj5 (PLRegion)
                $obj5->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLCountry rows

                $key6 = PLCountryPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = PLCountryPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = PLCountryPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    PLCountryPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj6 (PLCountry)
                $obj6->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PCTopic rows

                $key7 = PCTopicPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = PCTopicPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = PCTopicPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    PCTopicPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj7 (PCTopic)
                $obj7->addPDReaction($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of PDReaction objects pre-filled with all related objects except PDDebate.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PDReaction objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptPDDebate(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PDReactionPeer::DATABASE_NAME);
        }

        PDReactionPeer::addSelectColumns($criteria);
        $startcol2 = PDReactionPeer::NUM_HYDRATE_COLUMNS;

        PUserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PUserPeer::NUM_HYDRATE_COLUMNS;

        PLCityPeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PLCityPeer::NUM_HYDRATE_COLUMNS;

        PLDepartmentPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PLDepartmentPeer::NUM_HYDRATE_COLUMNS;

        PLRegionPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + PLRegionPeer::NUM_HYDRATE_COLUMNS;

        PLCountryPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + PLCountryPeer::NUM_HYDRATE_COLUMNS;

        PCTopicPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + PCTopicPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(PDReactionPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_CITY_ID, PLCityPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_DEPARTMENT_ID, PLDepartmentPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_REGION_ID, PLRegionPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_COUNTRY_ID, PLCountryPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_C_TOPIC_ID, PCTopicPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PDReactionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PDReactionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = PDReactionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PDReactionPeer::addInstanceToPool($obj1, $key1);
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
                } // if $obj2 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj2 (PUser)
                $obj2->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLCity rows

                $key3 = PLCityPeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = PLCityPeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = PLCityPeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PLCityPeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj3 (PLCity)
                $obj3->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLDepartment rows

                $key4 = PLDepartmentPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = PLDepartmentPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = PLDepartmentPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PLDepartmentPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj4 (PLDepartment)
                $obj4->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLRegion rows

                $key5 = PLRegionPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = PLRegionPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = PLRegionPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    PLRegionPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj5 (PLRegion)
                $obj5->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLCountry rows

                $key6 = PLCountryPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = PLCountryPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = PLCountryPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    PLCountryPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj6 (PLCountry)
                $obj6->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PCTopic rows

                $key7 = PCTopicPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = PCTopicPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = PCTopicPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    PCTopicPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj7 (PCTopic)
                $obj7->addPDReaction($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of PDReaction objects pre-filled with all related objects except PLCity.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PDReaction objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptPLCity(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PDReactionPeer::DATABASE_NAME);
        }

        PDReactionPeer::addSelectColumns($criteria);
        $startcol2 = PDReactionPeer::NUM_HYDRATE_COLUMNS;

        PUserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PUserPeer::NUM_HYDRATE_COLUMNS;

        PDDebatePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PDDebatePeer::NUM_HYDRATE_COLUMNS;

        PLDepartmentPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PLDepartmentPeer::NUM_HYDRATE_COLUMNS;

        PLRegionPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + PLRegionPeer::NUM_HYDRATE_COLUMNS;

        PLCountryPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + PLCountryPeer::NUM_HYDRATE_COLUMNS;

        PCTopicPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + PCTopicPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(PDReactionPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_D_DEBATE_ID, PDDebatePeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_DEPARTMENT_ID, PLDepartmentPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_REGION_ID, PLRegionPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_COUNTRY_ID, PLCountryPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_C_TOPIC_ID, PCTopicPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PDReactionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PDReactionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = PDReactionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PDReactionPeer::addInstanceToPool($obj1, $key1);
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
                } // if $obj2 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj2 (PUser)
                $obj2->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PDDebate rows

                $key3 = PDDebatePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = PDDebatePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = PDDebatePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PDDebatePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj3 (PDDebate)
                $obj3->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLDepartment rows

                $key4 = PLDepartmentPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = PLDepartmentPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = PLDepartmentPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PLDepartmentPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj4 (PLDepartment)
                $obj4->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLRegion rows

                $key5 = PLRegionPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = PLRegionPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = PLRegionPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    PLRegionPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj5 (PLRegion)
                $obj5->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLCountry rows

                $key6 = PLCountryPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = PLCountryPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = PLCountryPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    PLCountryPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj6 (PLCountry)
                $obj6->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PCTopic rows

                $key7 = PCTopicPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = PCTopicPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = PCTopicPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    PCTopicPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj7 (PCTopic)
                $obj7->addPDReaction($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of PDReaction objects pre-filled with all related objects except PLDepartment.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PDReaction objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptPLDepartment(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PDReactionPeer::DATABASE_NAME);
        }

        PDReactionPeer::addSelectColumns($criteria);
        $startcol2 = PDReactionPeer::NUM_HYDRATE_COLUMNS;

        PUserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PUserPeer::NUM_HYDRATE_COLUMNS;

        PDDebatePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PDDebatePeer::NUM_HYDRATE_COLUMNS;

        PLCityPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PLCityPeer::NUM_HYDRATE_COLUMNS;

        PLRegionPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + PLRegionPeer::NUM_HYDRATE_COLUMNS;

        PLCountryPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + PLCountryPeer::NUM_HYDRATE_COLUMNS;

        PCTopicPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + PCTopicPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(PDReactionPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_D_DEBATE_ID, PDDebatePeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_CITY_ID, PLCityPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_REGION_ID, PLRegionPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_COUNTRY_ID, PLCountryPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_C_TOPIC_ID, PCTopicPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PDReactionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PDReactionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = PDReactionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PDReactionPeer::addInstanceToPool($obj1, $key1);
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
                } // if $obj2 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj2 (PUser)
                $obj2->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PDDebate rows

                $key3 = PDDebatePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = PDDebatePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = PDDebatePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PDDebatePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj3 (PDDebate)
                $obj3->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLCity rows

                $key4 = PLCityPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = PLCityPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = PLCityPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PLCityPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj4 (PLCity)
                $obj4->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLRegion rows

                $key5 = PLRegionPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = PLRegionPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = PLRegionPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    PLRegionPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj5 (PLRegion)
                $obj5->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLCountry rows

                $key6 = PLCountryPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = PLCountryPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = PLCountryPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    PLCountryPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj6 (PLCountry)
                $obj6->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PCTopic rows

                $key7 = PCTopicPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = PCTopicPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = PCTopicPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    PCTopicPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj7 (PCTopic)
                $obj7->addPDReaction($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of PDReaction objects pre-filled with all related objects except PLRegion.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PDReaction objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptPLRegion(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PDReactionPeer::DATABASE_NAME);
        }

        PDReactionPeer::addSelectColumns($criteria);
        $startcol2 = PDReactionPeer::NUM_HYDRATE_COLUMNS;

        PUserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PUserPeer::NUM_HYDRATE_COLUMNS;

        PDDebatePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PDDebatePeer::NUM_HYDRATE_COLUMNS;

        PLCityPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PLCityPeer::NUM_HYDRATE_COLUMNS;

        PLDepartmentPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + PLDepartmentPeer::NUM_HYDRATE_COLUMNS;

        PLCountryPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + PLCountryPeer::NUM_HYDRATE_COLUMNS;

        PCTopicPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + PCTopicPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(PDReactionPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_D_DEBATE_ID, PDDebatePeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_CITY_ID, PLCityPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_DEPARTMENT_ID, PLDepartmentPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_COUNTRY_ID, PLCountryPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_C_TOPIC_ID, PCTopicPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PDReactionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PDReactionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = PDReactionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PDReactionPeer::addInstanceToPool($obj1, $key1);
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
                } // if $obj2 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj2 (PUser)
                $obj2->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PDDebate rows

                $key3 = PDDebatePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = PDDebatePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = PDDebatePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PDDebatePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj3 (PDDebate)
                $obj3->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLCity rows

                $key4 = PLCityPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = PLCityPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = PLCityPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PLCityPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj4 (PLCity)
                $obj4->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLDepartment rows

                $key5 = PLDepartmentPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = PLDepartmentPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = PLDepartmentPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    PLDepartmentPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj5 (PLDepartment)
                $obj5->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLCountry rows

                $key6 = PLCountryPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = PLCountryPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = PLCountryPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    PLCountryPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj6 (PLCountry)
                $obj6->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PCTopic rows

                $key7 = PCTopicPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = PCTopicPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = PCTopicPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    PCTopicPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj7 (PCTopic)
                $obj7->addPDReaction($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of PDReaction objects pre-filled with all related objects except PLCountry.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PDReaction objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptPLCountry(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PDReactionPeer::DATABASE_NAME);
        }

        PDReactionPeer::addSelectColumns($criteria);
        $startcol2 = PDReactionPeer::NUM_HYDRATE_COLUMNS;

        PUserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PUserPeer::NUM_HYDRATE_COLUMNS;

        PDDebatePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PDDebatePeer::NUM_HYDRATE_COLUMNS;

        PLCityPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PLCityPeer::NUM_HYDRATE_COLUMNS;

        PLDepartmentPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + PLDepartmentPeer::NUM_HYDRATE_COLUMNS;

        PLRegionPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + PLRegionPeer::NUM_HYDRATE_COLUMNS;

        PCTopicPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + PCTopicPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(PDReactionPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_D_DEBATE_ID, PDDebatePeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_CITY_ID, PLCityPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_DEPARTMENT_ID, PLDepartmentPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_REGION_ID, PLRegionPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_C_TOPIC_ID, PCTopicPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PDReactionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PDReactionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = PDReactionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PDReactionPeer::addInstanceToPool($obj1, $key1);
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
                } // if $obj2 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj2 (PUser)
                $obj2->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PDDebate rows

                $key3 = PDDebatePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = PDDebatePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = PDDebatePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PDDebatePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj3 (PDDebate)
                $obj3->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLCity rows

                $key4 = PLCityPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = PLCityPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = PLCityPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PLCityPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj4 (PLCity)
                $obj4->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLDepartment rows

                $key5 = PLDepartmentPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = PLDepartmentPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = PLDepartmentPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    PLDepartmentPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj5 (PLDepartment)
                $obj5->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLRegion rows

                $key6 = PLRegionPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = PLRegionPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = PLRegionPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    PLRegionPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj6 (PLRegion)
                $obj6->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PCTopic rows

                $key7 = PCTopicPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = PCTopicPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = PCTopicPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    PCTopicPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj7 (PCTopic)
                $obj7->addPDReaction($obj1);

            } // if joined row is not null

            $results[] = $obj1;
        }
        $stmt->closeCursor();

        return $results;
    }


    /**
     * Selects a collection of PDReaction objects pre-filled with all related objects except PCTopic.
     *
     * @param      Criteria  $criteria
     * @param      PropelPDO $con
     * @param      String    $join_behavior the type of joins to use, defaults to Criteria::LEFT_JOIN
     * @return array           Array of PDReaction objects.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectJoinAllExceptPCTopic(Criteria $criteria, $con = null, $join_behavior = Criteria::LEFT_JOIN)
    {
        $criteria = clone $criteria;

        // Set the correct dbName if it has not been overridden
        // $criteria->getDbName() will return the same object if not set to another value
        // so == check is okay and faster
        if ($criteria->getDbName() == Propel::getDefaultDB()) {
            $criteria->setDbName(PDReactionPeer::DATABASE_NAME);
        }

        PDReactionPeer::addSelectColumns($criteria);
        $startcol2 = PDReactionPeer::NUM_HYDRATE_COLUMNS;

        PUserPeer::addSelectColumns($criteria);
        $startcol3 = $startcol2 + PUserPeer::NUM_HYDRATE_COLUMNS;

        PDDebatePeer::addSelectColumns($criteria);
        $startcol4 = $startcol3 + PDDebatePeer::NUM_HYDRATE_COLUMNS;

        PLCityPeer::addSelectColumns($criteria);
        $startcol5 = $startcol4 + PLCityPeer::NUM_HYDRATE_COLUMNS;

        PLDepartmentPeer::addSelectColumns($criteria);
        $startcol6 = $startcol5 + PLDepartmentPeer::NUM_HYDRATE_COLUMNS;

        PLRegionPeer::addSelectColumns($criteria);
        $startcol7 = $startcol6 + PLRegionPeer::NUM_HYDRATE_COLUMNS;

        PLCountryPeer::addSelectColumns($criteria);
        $startcol8 = $startcol7 + PLCountryPeer::NUM_HYDRATE_COLUMNS;

        $criteria->addJoin(PDReactionPeer::P_USER_ID, PUserPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_D_DEBATE_ID, PDDebatePeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_CITY_ID, PLCityPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_DEPARTMENT_ID, PLDepartmentPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_REGION_ID, PLRegionPeer::ID, $join_behavior);

        $criteria->addJoin(PDReactionPeer::P_L_COUNTRY_ID, PLCountryPeer::ID, $join_behavior);


        $stmt = BasePeer::doSelect($criteria, $con);
        $results = array();

        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key1 = PDReactionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj1 = PDReactionPeer::getInstanceFromPool($key1))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj1->hydrate($row, 0, true); // rehydrate
            } else {
                $cls = PDReactionPeer::getOMClass();

                $obj1 = new $cls();
                $obj1->hydrate($row);
                PDReactionPeer::addInstanceToPool($obj1, $key1);
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
                } // if $obj2 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj2 (PUser)
                $obj2->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PDDebate rows

                $key3 = PDDebatePeer::getPrimaryKeyHashFromRow($row, $startcol3);
                if ($key3 !== null) {
                    $obj3 = PDDebatePeer::getInstanceFromPool($key3);
                    if (!$obj3) {

                        $cls = PDDebatePeer::getOMClass();

                    $obj3 = new $cls();
                    $obj3->hydrate($row, $startcol3);
                    PDDebatePeer::addInstanceToPool($obj3, $key3);
                } // if $obj3 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj3 (PDDebate)
                $obj3->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLCity rows

                $key4 = PLCityPeer::getPrimaryKeyHashFromRow($row, $startcol4);
                if ($key4 !== null) {
                    $obj4 = PLCityPeer::getInstanceFromPool($key4);
                    if (!$obj4) {

                        $cls = PLCityPeer::getOMClass();

                    $obj4 = new $cls();
                    $obj4->hydrate($row, $startcol4);
                    PLCityPeer::addInstanceToPool($obj4, $key4);
                } // if $obj4 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj4 (PLCity)
                $obj4->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLDepartment rows

                $key5 = PLDepartmentPeer::getPrimaryKeyHashFromRow($row, $startcol5);
                if ($key5 !== null) {
                    $obj5 = PLDepartmentPeer::getInstanceFromPool($key5);
                    if (!$obj5) {

                        $cls = PLDepartmentPeer::getOMClass();

                    $obj5 = new $cls();
                    $obj5->hydrate($row, $startcol5);
                    PLDepartmentPeer::addInstanceToPool($obj5, $key5);
                } // if $obj5 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj5 (PLDepartment)
                $obj5->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLRegion rows

                $key6 = PLRegionPeer::getPrimaryKeyHashFromRow($row, $startcol6);
                if ($key6 !== null) {
                    $obj6 = PLRegionPeer::getInstanceFromPool($key6);
                    if (!$obj6) {

                        $cls = PLRegionPeer::getOMClass();

                    $obj6 = new $cls();
                    $obj6->hydrate($row, $startcol6);
                    PLRegionPeer::addInstanceToPool($obj6, $key6);
                } // if $obj6 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj6 (PLRegion)
                $obj6->addPDReaction($obj1);

            } // if joined row is not null

                // Add objects for joined PLCountry rows

                $key7 = PLCountryPeer::getPrimaryKeyHashFromRow($row, $startcol7);
                if ($key7 !== null) {
                    $obj7 = PLCountryPeer::getInstanceFromPool($key7);
                    if (!$obj7) {

                        $cls = PLCountryPeer::getOMClass();

                    $obj7 = new $cls();
                    $obj7->hydrate($row, $startcol7);
                    PLCountryPeer::addInstanceToPool($obj7, $key7);
                } // if $obj7 already loaded

                // Add the $obj1 (PDReaction) to the collection in $obj7 (PLCountry)
                $obj7->addPDReaction($obj1);

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
        return Propel::getDatabaseMap(PDReactionPeer::DATABASE_NAME)->getTable(PDReactionPeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BasePDReactionPeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BasePDReactionPeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \Politizr\Model\map\PDReactionTableMap());
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
        return PDReactionPeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a PDReaction or Criteria object.
     *
     * @param      mixed $values Criteria or PDReaction object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from PDReaction object
        }

        if ($criteria->containsKey(PDReactionPeer::ID) && $criteria->keyContainsValue(PDReactionPeer::ID) ) {
            throw new PropelException('Cannot insert a value for auto-increment primary key ('.PDReactionPeer::ID.')');
        }


        // Set the correct dbName
        $criteria->setDbName(PDReactionPeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a PDReaction or Criteria object.
     *
     * @param      mixed $values Criteria or PDReaction object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(PDReactionPeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(PDReactionPeer::ID);
            $value = $criteria->remove(PDReactionPeer::ID);
            if ($value) {
                $selectCriteria->add(PDReactionPeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(PDReactionPeer::TABLE_NAME);
            }

        } else { // $values is PDReaction object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(PDReactionPeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the p_d_reaction table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(PDReactionPeer::TABLE_NAME, $con, PDReactionPeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PDReactionPeer::clearInstancePool();
            PDReactionPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a PDReaction or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or PDReaction object or primary key or array of primary keys
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
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            PDReactionPeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof PDReaction) { // it's a model object
            // invalidate the cache for this single object
            PDReactionPeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PDReactionPeer::DATABASE_NAME);
            $criteria->add(PDReactionPeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                PDReactionPeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(PDReactionPeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            PDReactionPeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given PDReaction object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param PDReaction $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(PDReactionPeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(PDReactionPeer::TABLE_NAME);

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

        return BasePeer::doValidate(PDReactionPeer::DATABASE_NAME, PDReactionPeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return PDReaction
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = PDReactionPeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(PDReactionPeer::DATABASE_NAME);
        $criteria->add(PDReactionPeer::ID, $pk);

        $v = PDReactionPeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return PDReaction[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(PDReactionPeer::DATABASE_NAME);
            $criteria->add(PDReactionPeer::ID, $pks, Criteria::IN);
            $objs = PDReactionPeer::doSelect($criteria, $con);
        }

        return $objs;
    }

    // nested_set behavior

    /**
     * Returns the root nodes for the tree
     *
     * @param      PropelPDO $con	Connection to use.
     * @return     PDReaction			Propel object for root node
     */
    public static function retrieveRoots(Criteria $criteria = null, PropelPDO $con = null)
    {
        if ($criteria === null) {
            $criteria = new Criteria(PDReactionPeer::DATABASE_NAME);
        }
        $criteria->add(PDReactionPeer::LEFT_COL, 1, Criteria::EQUAL);

        return PDReactionPeer::doSelect($criteria, $con);
    }

    /**
     * Returns the root node for a given scope
     *
     * @param      int $scope		Scope to determine which root node to return
     * @param      PropelPDO $con	Connection to use.
     * @return     PDReaction			Propel object for root node
     */
    public static function retrieveRoot($scope = null, PropelPDO $con = null)
    {
        $c = new Criteria(PDReactionPeer::DATABASE_NAME);
        $c->add(PDReactionPeer::LEFT_COL, 1, Criteria::EQUAL);
        $c->add(PDReactionPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        return PDReactionPeer::doSelectOne($c, $con);
    }

    /**
     * Returns the whole tree node for a given scope
     *
     * @param      int $scope		Scope to determine which root node to return
     * @param      Criteria $criteria	Optional Criteria to filter the query
     * @param      PropelPDO $con	Connection to use.
     * @return     PDReaction			Propel object for root node
     */
    public static function retrieveTree($scope = null, Criteria $criteria = null, PropelPDO $con = null)
    {
        if ($criteria === null) {
            $criteria = new Criteria(PDReactionPeer::DATABASE_NAME);
        }
        $criteria->addAscendingOrderByColumn(PDReactionPeer::LEFT_COL);
        $criteria->add(PDReactionPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        return PDReactionPeer::doSelect($criteria, $con);
    }

    /**
     * Tests if node is valid
     *
     * @param      PDReaction $node	Propel object for src node
     * @return     bool
     */
    public static function isValid(PDReaction $node = null)
    {
        if (is_object($node) && $node->getRightValue() > $node->getLeftValue()) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Delete an entire tree
     *
     * @param      int $scope		Scope to determine which tree to delete
     * @param      PropelPDO $con	Connection to use.
     *
     * @return     int  The number of deleted nodes
     */
    public static function deleteTree($scope = null, PropelPDO $con = null)
    {
        $c = new Criteria(PDReactionPeer::DATABASE_NAME);
        $c->add(PDReactionPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        return PDReactionPeer::doDelete($c, $con);
    }

    /**
     * Adds $delta to all L and R values that are >= $first and <= $last.
     * '$delta' can also be negative.
     *
     * @param      int $delta		Value to be shifted by, can be negative
     * @param      int $first		First node to be shifted
     * @param      int $last			Last node to be shifted (optional)
     * @param      int $scope		Scope to use for the shift
     * @param      PropelPDO $con		Connection to use.
     */
    public static function shiftRLValues($delta, $first, $last = null, $scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        // Shift left column values
        $whereCriteria = new Criteria(PDReactionPeer::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(PDReactionPeer::LEFT_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(PDReactionPeer::LEFT_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);
        $whereCriteria->add(PDReactionPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        $valuesCriteria = new Criteria(PDReactionPeer::DATABASE_NAME);
        $valuesCriteria->add(PDReactionPeer::LEFT_COL, array('raw' => PDReactionPeer::LEFT_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);

        // Shift right column values
        $whereCriteria = new Criteria(PDReactionPeer::DATABASE_NAME);
        $criterion = $whereCriteria->getNewCriterion(PDReactionPeer::RIGHT_COL, $first, Criteria::GREATER_EQUAL);
        if (null !== $last) {
            $criterion->addAnd($whereCriteria->getNewCriterion(PDReactionPeer::RIGHT_COL, $last, Criteria::LESS_EQUAL));
        }
        $whereCriteria->add($criterion);
        $whereCriteria->add(PDReactionPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        $valuesCriteria = new Criteria(PDReactionPeer::DATABASE_NAME);
        $valuesCriteria->add(PDReactionPeer::RIGHT_COL, array('raw' => PDReactionPeer::RIGHT_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);
    }

    /**
     * Adds $delta to level for nodes having left value >= $first and right value <= $last.
     * '$delta' can also be negative.
     *
     * @param      int $delta		Value to be shifted by, can be negative
     * @param      int $first		First node to be shifted
     * @param      int $last			Last node to be shifted
     * @param      int $scope		Scope to use for the shift
     * @param      PropelPDO $con		Connection to use.
     */
    public static function shiftLevel($delta, $first, $last, $scope = null, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PDReactionPeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $whereCriteria = new Criteria(PDReactionPeer::DATABASE_NAME);
        $whereCriteria->add(PDReactionPeer::LEFT_COL, $first, Criteria::GREATER_EQUAL);
        $whereCriteria->add(PDReactionPeer::RIGHT_COL, $last, Criteria::LESS_EQUAL);
        $whereCriteria->add(PDReactionPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        $valuesCriteria = new Criteria(PDReactionPeer::DATABASE_NAME);
        $valuesCriteria->add(PDReactionPeer::LEVEL_COL, array('raw' => PDReactionPeer::LEVEL_COL . ' + ?', 'value' => $delta), Criteria::CUSTOM_EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);
    }

    /**
     * Reload all already loaded nodes to sync them with updated db
     *
     * @param      PDReaction $prune		Object to prune from the update
     * @param      PropelPDO $con		Connection to use.
     */
    public static function updateLoadedNodes($prune = null, PropelPDO $con = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            $keys = array();
            foreach (PDReactionPeer::$instances as $obj) {
                if (!$prune || !$prune->equals($obj)) {
                    $keys[] = $obj->getPrimaryKey();
                }
            }

            if (!empty($keys)) {
                // We don't need to alter the object instance pool; we're just modifying these ones
                // already in the pool.
                $criteria = new Criteria(PDReactionPeer::DATABASE_NAME);
                $criteria->add(PDReactionPeer::ID, $keys, Criteria::IN);
                $stmt = PDReactionPeer::doSelectStmt($criteria, $con);
                while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
                    $key = PDReactionPeer::getPrimaryKeyHashFromRow($row, 0);
                    if (null !== ($object = PDReactionPeer::getInstanceFromPool($key))) {
                        $object->setScopeValue($row[3]);
                        $object->setLeftValue($row[29]);
                        $object->setRightValue($row[30]);
                        $object->setLevel($row[31]);
                        $object->clearNestedSetChildren();
                    }
                }
                $stmt->closeCursor();
            }
        }
    }

    /**
     * Update the tree to allow insertion of a leaf at the specified position
     *
     * @param      int $left	left column value
     * @param      integer $scope	scope column value
     * @param      mixed $prune	Object to prune from the shift
     * @param      PropelPDO $con	Connection to use.
     */
    public static function makeRoomForLeaf($left, $scope, $prune = null, PropelPDO $con = null)
    {
        // Update database nodes
        PDReactionPeer::shiftRLValues(2, $left, null, $scope, $con);

        // Update all loaded nodes
        PDReactionPeer::updateLoadedNodes($prune, $con);
    }

    /**
     * Update the tree to allow insertion of a leaf at the specified position
     *
     * @param      integer $scope	scope column value
     * @param      PropelPDO $con	Connection to use.
     */
    public static function fixLevels($scope, PropelPDO $con = null)
    {
        $c = new Criteria();
        $c->add(PDReactionPeer::SCOPE_COL, $scope, Criteria::EQUAL);
        $c->addAscendingOrderByColumn(PDReactionPeer::LEFT_COL);
        $stmt = PDReactionPeer::doSelectStmt($c, $con);

        // set the class once to avoid overhead in the loop
        $cls = PDReactionPeer::getOMClass(false);
        $level = null;
        // iterate over the statement
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {

            // hydrate object
            $key = PDReactionPeer::getPrimaryKeyHashFromRow($row, 0);
            if (null === ($obj = PDReactionPeer::getInstanceFromPool($key))) {
                $obj = new $cls();
                $obj->hydrate($row);
                PDReactionPeer::addInstanceToPool($obj, $key);
            }

            // compute level
            // Algorithm shamelessly stolen from sfPropelActAsNestedSetBehaviorPlugin
            // Probably authored by Tristan Rivoallan
            if ($level === null) {
                $level = 0;
                $i = 0;
                $prev = array($obj->getRightValue());
            } else {
                while ($obj->getRightValue() > $prev[$i]) {
                    $i--;
                }
                $level = ++$i;
                $prev[$i] = $obj->getRightValue();
            }

            // update level in node if necessary
            if ($obj->getLevel() !== $level) {
                $obj->setLevel($level);
                $obj->save($con);
            }
        }
        $stmt->closeCursor();
    }

    /**
     * Updates all scope values for items that has negative left (<=0) values.
     *
     * @param      mixed     $scope
     * @param      PropelPDO $con	Connection to use.
     */
    public static function setNegativeScope($scope, PropelPDO $con = null)
    {
        //adjust scope value to $scope
        $whereCriteria = new Criteria(PDReactionPeer::DATABASE_NAME);
        $whereCriteria->add(PDReactionPeer::LEFT_COL, 0, Criteria::LESS_EQUAL);

        $valuesCriteria = new Criteria(PDReactionPeer::DATABASE_NAME);
        $valuesCriteria->add(PDReactionPeer::SCOPE_COL, $scope, Criteria::EQUAL);

        BasePeer::doUpdate($whereCriteria, $valuesCriteria, $con);
    }

} // BasePDReactionPeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BasePDReactionPeer::buildTableMap();

