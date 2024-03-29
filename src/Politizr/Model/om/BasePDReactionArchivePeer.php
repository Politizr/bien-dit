<?php

namespace Politizr\Model\om;

use \BasePeer;
use \Criteria;
use \PDO;
use \PDOStatement;
use \Propel;
use \PropelException;
use \PropelPDO;
use Politizr\Model\PDReactionArchive;
use Politizr\Model\PDReactionArchivePeer;
use Politizr\Model\map\PDReactionArchiveTableMap;

abstract class BasePDReactionArchivePeer
{

    /** the default database name for this class */
    const DATABASE_NAME = 'default';

    /** the table name for this class */
    const TABLE_NAME = 'p_d_reaction_archive';

    /** the related Propel class for this table */
    const OM_CLASS = 'Politizr\\Model\\PDReactionArchive';

    /** the related TableMap class for this table */
    const TM_CLASS = 'Politizr\\Model\\map\\PDReactionArchiveTableMap';

    /** The total number of columns. */
    const NUM_COLUMNS = 36;

    /** The number of lazy-loaded columns. */
    const NUM_LAZY_LOAD_COLUMNS = 0;

    /** The number of columns to hydrate (NUM_COLUMNS - NUM_LAZY_LOAD_COLUMNS) */
    const NUM_HYDRATE_COLUMNS = 36;

    /** the column name for the id field */
    const ID = 'p_d_reaction_archive.id';

    /** the column name for the uuid field */
    const UUID = 'p_d_reaction_archive.uuid';

    /** the column name for the p_user_id field */
    const P_USER_ID = 'p_d_reaction_archive.p_user_id';

    /** the column name for the p_d_debate_id field */
    const P_D_DEBATE_ID = 'p_d_reaction_archive.p_d_debate_id';

    /** the column name for the parent_reaction_id field */
    const PARENT_REACTION_ID = 'p_d_reaction_archive.parent_reaction_id';

    /** the column name for the p_l_city_id field */
    const P_L_CITY_ID = 'p_d_reaction_archive.p_l_city_id';

    /** the column name for the p_l_department_id field */
    const P_L_DEPARTMENT_ID = 'p_d_reaction_archive.p_l_department_id';

    /** the column name for the p_l_region_id field */
    const P_L_REGION_ID = 'p_d_reaction_archive.p_l_region_id';

    /** the column name for the p_l_country_id field */
    const P_L_COUNTRY_ID = 'p_d_reaction_archive.p_l_country_id';

    /** the column name for the p_c_topic_id field */
    const P_C_TOPIC_ID = 'p_d_reaction_archive.p_c_topic_id';

    /** the column name for the fb_ad_id field */
    const FB_AD_ID = 'p_d_reaction_archive.fb_ad_id';

    /** the column name for the title field */
    const TITLE = 'p_d_reaction_archive.title';

    /** the column name for the file_name field */
    const FILE_NAME = 'p_d_reaction_archive.file_name';

    /** the column name for the copyright field */
    const COPYRIGHT = 'p_d_reaction_archive.copyright';

    /** the column name for the description field */
    const DESCRIPTION = 'p_d_reaction_archive.description';

    /** the column name for the note_pos field */
    const NOTE_POS = 'p_d_reaction_archive.note_pos';

    /** the column name for the note_neg field */
    const NOTE_NEG = 'p_d_reaction_archive.note_neg';

    /** the column name for the nb_views field */
    const NB_VIEWS = 'p_d_reaction_archive.nb_views';

    /** the column name for the want_boost field */
    const WANT_BOOST = 'p_d_reaction_archive.want_boost';

    /** the column name for the published field */
    const PUBLISHED = 'p_d_reaction_archive.published';

    /** the column name for the published_at field */
    const PUBLISHED_AT = 'p_d_reaction_archive.published_at';

    /** the column name for the published_by field */
    const PUBLISHED_BY = 'p_d_reaction_archive.published_by';

    /** the column name for the favorite field */
    const FAVORITE = 'p_d_reaction_archive.favorite';

    /** the column name for the online field */
    const ONLINE = 'p_d_reaction_archive.online';

    /** the column name for the homepage field */
    const HOMEPAGE = 'p_d_reaction_archive.homepage';

    /** the column name for the moderated field */
    const MODERATED = 'p_d_reaction_archive.moderated';

    /** the column name for the moderated_partial field */
    const MODERATED_PARTIAL = 'p_d_reaction_archive.moderated_partial';

    /** the column name for the moderated_at field */
    const MODERATED_AT = 'p_d_reaction_archive.moderated_at';

    /** the column name for the indexed_at field */
    const INDEXED_AT = 'p_d_reaction_archive.indexed_at';

    /** the column name for the created_at field */
    const CREATED_AT = 'p_d_reaction_archive.created_at';

    /** the column name for the updated_at field */
    const UPDATED_AT = 'p_d_reaction_archive.updated_at';

    /** the column name for the slug field */
    const SLUG = 'p_d_reaction_archive.slug';

    /** the column name for the tree_left field */
    const TREE_LEFT = 'p_d_reaction_archive.tree_left';

    /** the column name for the tree_right field */
    const TREE_RIGHT = 'p_d_reaction_archive.tree_right';

    /** the column name for the tree_level field */
    const TREE_LEVEL = 'p_d_reaction_archive.tree_level';

    /** the column name for the archived_at field */
    const ARCHIVED_AT = 'p_d_reaction_archive.archived_at';

    /** The default string format for model objects of the related table **/
    const DEFAULT_STRING_FORMAT = 'YAML';

    /**
     * An identity map to hold any loaded instances of PDReactionArchive objects.
     * This must be public so that other peer classes can access this when hydrating from JOIN
     * queries.
     * @var        array PDReactionArchive[]
     */
    public static $instances = array();


    /**
     * holds an array of fieldnames
     *
     * first dimension keys are the type constants
     * e.g. PDReactionArchivePeer::$fieldNames[PDReactionArchivePeer::TYPE_PHPNAME][0] = 'Id'
     */
    protected static $fieldNames = array (
        BasePeer::TYPE_PHPNAME => array ('Id', 'Uuid', 'PUserId', 'PDDebateId', 'ParentReactionId', 'PLCityId', 'PLDepartmentId', 'PLRegionId', 'PLCountryId', 'PCTopicId', 'FbAdId', 'Title', 'FileName', 'Copyright', 'Description', 'NotePos', 'NoteNeg', 'NbViews', 'WantBoost', 'Published', 'PublishedAt', 'PublishedBy', 'Favorite', 'Online', 'Homepage', 'Moderated', 'ModeratedPartial', 'ModeratedAt', 'IndexedAt', 'CreatedAt', 'UpdatedAt', 'Slug', 'TreeLeft', 'TreeRight', 'TreeLevel', 'ArchivedAt', ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id', 'uuid', 'pUserId', 'pDDebateId', 'parentReactionId', 'pLCityId', 'pLDepartmentId', 'pLRegionId', 'pLCountryId', 'pCTopicId', 'fbAdId', 'title', 'fileName', 'copyright', 'description', 'notePos', 'noteNeg', 'nbViews', 'wantBoost', 'published', 'publishedAt', 'publishedBy', 'favorite', 'online', 'homepage', 'moderated', 'moderatedPartial', 'moderatedAt', 'indexedAt', 'createdAt', 'updatedAt', 'slug', 'treeLeft', 'treeRight', 'treeLevel', 'archivedAt', ),
        BasePeer::TYPE_COLNAME => array (PDReactionArchivePeer::ID, PDReactionArchivePeer::UUID, PDReactionArchivePeer::P_USER_ID, PDReactionArchivePeer::P_D_DEBATE_ID, PDReactionArchivePeer::PARENT_REACTION_ID, PDReactionArchivePeer::P_L_CITY_ID, PDReactionArchivePeer::P_L_DEPARTMENT_ID, PDReactionArchivePeer::P_L_REGION_ID, PDReactionArchivePeer::P_L_COUNTRY_ID, PDReactionArchivePeer::P_C_TOPIC_ID, PDReactionArchivePeer::FB_AD_ID, PDReactionArchivePeer::TITLE, PDReactionArchivePeer::FILE_NAME, PDReactionArchivePeer::COPYRIGHT, PDReactionArchivePeer::DESCRIPTION, PDReactionArchivePeer::NOTE_POS, PDReactionArchivePeer::NOTE_NEG, PDReactionArchivePeer::NB_VIEWS, PDReactionArchivePeer::WANT_BOOST, PDReactionArchivePeer::PUBLISHED, PDReactionArchivePeer::PUBLISHED_AT, PDReactionArchivePeer::PUBLISHED_BY, PDReactionArchivePeer::FAVORITE, PDReactionArchivePeer::ONLINE, PDReactionArchivePeer::HOMEPAGE, PDReactionArchivePeer::MODERATED, PDReactionArchivePeer::MODERATED_PARTIAL, PDReactionArchivePeer::MODERATED_AT, PDReactionArchivePeer::INDEXED_AT, PDReactionArchivePeer::CREATED_AT, PDReactionArchivePeer::UPDATED_AT, PDReactionArchivePeer::SLUG, PDReactionArchivePeer::TREE_LEFT, PDReactionArchivePeer::TREE_RIGHT, PDReactionArchivePeer::TREE_LEVEL, PDReactionArchivePeer::ARCHIVED_AT, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID', 'UUID', 'P_USER_ID', 'P_D_DEBATE_ID', 'PARENT_REACTION_ID', 'P_L_CITY_ID', 'P_L_DEPARTMENT_ID', 'P_L_REGION_ID', 'P_L_COUNTRY_ID', 'P_C_TOPIC_ID', 'FB_AD_ID', 'TITLE', 'FILE_NAME', 'COPYRIGHT', 'DESCRIPTION', 'NOTE_POS', 'NOTE_NEG', 'NB_VIEWS', 'WANT_BOOST', 'PUBLISHED', 'PUBLISHED_AT', 'PUBLISHED_BY', 'FAVORITE', 'ONLINE', 'HOMEPAGE', 'MODERATED', 'MODERATED_PARTIAL', 'MODERATED_AT', 'INDEXED_AT', 'CREATED_AT', 'UPDATED_AT', 'SLUG', 'TREE_LEFT', 'TREE_RIGHT', 'TREE_LEVEL', 'ARCHIVED_AT', ),
        BasePeer::TYPE_FIELDNAME => array ('id', 'uuid', 'p_user_id', 'p_d_debate_id', 'parent_reaction_id', 'p_l_city_id', 'p_l_department_id', 'p_l_region_id', 'p_l_country_id', 'p_c_topic_id', 'fb_ad_id', 'title', 'file_name', 'copyright', 'description', 'note_pos', 'note_neg', 'nb_views', 'want_boost', 'published', 'published_at', 'published_by', 'favorite', 'online', 'homepage', 'moderated', 'moderated_partial', 'moderated_at', 'indexed_at', 'created_at', 'updated_at', 'slug', 'tree_left', 'tree_right', 'tree_level', 'archived_at', ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, )
    );

    /**
     * holds an array of keys for quick access to the fieldnames array
     *
     * first dimension keys are the type constants
     * e.g. PDReactionArchivePeer::$fieldNames[BasePeer::TYPE_PHPNAME]['Id'] = 0
     */
    protected static $fieldKeys = array (
        BasePeer::TYPE_PHPNAME => array ('Id' => 0, 'Uuid' => 1, 'PUserId' => 2, 'PDDebateId' => 3, 'ParentReactionId' => 4, 'PLCityId' => 5, 'PLDepartmentId' => 6, 'PLRegionId' => 7, 'PLCountryId' => 8, 'PCTopicId' => 9, 'FbAdId' => 10, 'Title' => 11, 'FileName' => 12, 'Copyright' => 13, 'Description' => 14, 'NotePos' => 15, 'NoteNeg' => 16, 'NbViews' => 17, 'WantBoost' => 18, 'Published' => 19, 'PublishedAt' => 20, 'PublishedBy' => 21, 'Favorite' => 22, 'Online' => 23, 'Homepage' => 24, 'Moderated' => 25, 'ModeratedPartial' => 26, 'ModeratedAt' => 27, 'IndexedAt' => 28, 'CreatedAt' => 29, 'UpdatedAt' => 30, 'Slug' => 31, 'TreeLeft' => 32, 'TreeRight' => 33, 'TreeLevel' => 34, 'ArchivedAt' => 35, ),
        BasePeer::TYPE_STUDLYPHPNAME => array ('id' => 0, 'uuid' => 1, 'pUserId' => 2, 'pDDebateId' => 3, 'parentReactionId' => 4, 'pLCityId' => 5, 'pLDepartmentId' => 6, 'pLRegionId' => 7, 'pLCountryId' => 8, 'pCTopicId' => 9, 'fbAdId' => 10, 'title' => 11, 'fileName' => 12, 'copyright' => 13, 'description' => 14, 'notePos' => 15, 'noteNeg' => 16, 'nbViews' => 17, 'wantBoost' => 18, 'published' => 19, 'publishedAt' => 20, 'publishedBy' => 21, 'favorite' => 22, 'online' => 23, 'homepage' => 24, 'moderated' => 25, 'moderatedPartial' => 26, 'moderatedAt' => 27, 'indexedAt' => 28, 'createdAt' => 29, 'updatedAt' => 30, 'slug' => 31, 'treeLeft' => 32, 'treeRight' => 33, 'treeLevel' => 34, 'archivedAt' => 35, ),
        BasePeer::TYPE_COLNAME => array (PDReactionArchivePeer::ID => 0, PDReactionArchivePeer::UUID => 1, PDReactionArchivePeer::P_USER_ID => 2, PDReactionArchivePeer::P_D_DEBATE_ID => 3, PDReactionArchivePeer::PARENT_REACTION_ID => 4, PDReactionArchivePeer::P_L_CITY_ID => 5, PDReactionArchivePeer::P_L_DEPARTMENT_ID => 6, PDReactionArchivePeer::P_L_REGION_ID => 7, PDReactionArchivePeer::P_L_COUNTRY_ID => 8, PDReactionArchivePeer::P_C_TOPIC_ID => 9, PDReactionArchivePeer::FB_AD_ID => 10, PDReactionArchivePeer::TITLE => 11, PDReactionArchivePeer::FILE_NAME => 12, PDReactionArchivePeer::COPYRIGHT => 13, PDReactionArchivePeer::DESCRIPTION => 14, PDReactionArchivePeer::NOTE_POS => 15, PDReactionArchivePeer::NOTE_NEG => 16, PDReactionArchivePeer::NB_VIEWS => 17, PDReactionArchivePeer::WANT_BOOST => 18, PDReactionArchivePeer::PUBLISHED => 19, PDReactionArchivePeer::PUBLISHED_AT => 20, PDReactionArchivePeer::PUBLISHED_BY => 21, PDReactionArchivePeer::FAVORITE => 22, PDReactionArchivePeer::ONLINE => 23, PDReactionArchivePeer::HOMEPAGE => 24, PDReactionArchivePeer::MODERATED => 25, PDReactionArchivePeer::MODERATED_PARTIAL => 26, PDReactionArchivePeer::MODERATED_AT => 27, PDReactionArchivePeer::INDEXED_AT => 28, PDReactionArchivePeer::CREATED_AT => 29, PDReactionArchivePeer::UPDATED_AT => 30, PDReactionArchivePeer::SLUG => 31, PDReactionArchivePeer::TREE_LEFT => 32, PDReactionArchivePeer::TREE_RIGHT => 33, PDReactionArchivePeer::TREE_LEVEL => 34, PDReactionArchivePeer::ARCHIVED_AT => 35, ),
        BasePeer::TYPE_RAW_COLNAME => array ('ID' => 0, 'UUID' => 1, 'P_USER_ID' => 2, 'P_D_DEBATE_ID' => 3, 'PARENT_REACTION_ID' => 4, 'P_L_CITY_ID' => 5, 'P_L_DEPARTMENT_ID' => 6, 'P_L_REGION_ID' => 7, 'P_L_COUNTRY_ID' => 8, 'P_C_TOPIC_ID' => 9, 'FB_AD_ID' => 10, 'TITLE' => 11, 'FILE_NAME' => 12, 'COPYRIGHT' => 13, 'DESCRIPTION' => 14, 'NOTE_POS' => 15, 'NOTE_NEG' => 16, 'NB_VIEWS' => 17, 'WANT_BOOST' => 18, 'PUBLISHED' => 19, 'PUBLISHED_AT' => 20, 'PUBLISHED_BY' => 21, 'FAVORITE' => 22, 'ONLINE' => 23, 'HOMEPAGE' => 24, 'MODERATED' => 25, 'MODERATED_PARTIAL' => 26, 'MODERATED_AT' => 27, 'INDEXED_AT' => 28, 'CREATED_AT' => 29, 'UPDATED_AT' => 30, 'SLUG' => 31, 'TREE_LEFT' => 32, 'TREE_RIGHT' => 33, 'TREE_LEVEL' => 34, 'ARCHIVED_AT' => 35, ),
        BasePeer::TYPE_FIELDNAME => array ('id' => 0, 'uuid' => 1, 'p_user_id' => 2, 'p_d_debate_id' => 3, 'parent_reaction_id' => 4, 'p_l_city_id' => 5, 'p_l_department_id' => 6, 'p_l_region_id' => 7, 'p_l_country_id' => 8, 'p_c_topic_id' => 9, 'fb_ad_id' => 10, 'title' => 11, 'file_name' => 12, 'copyright' => 13, 'description' => 14, 'note_pos' => 15, 'note_neg' => 16, 'nb_views' => 17, 'want_boost' => 18, 'published' => 19, 'published_at' => 20, 'published_by' => 21, 'favorite' => 22, 'online' => 23, 'homepage' => 24, 'moderated' => 25, 'moderated_partial' => 26, 'moderated_at' => 27, 'indexed_at' => 28, 'created_at' => 29, 'updated_at' => 30, 'slug' => 31, 'tree_left' => 32, 'tree_right' => 33, 'tree_level' => 34, 'archived_at' => 35, ),
        BasePeer::TYPE_NUM => array (0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24, 25, 26, 27, 28, 29, 30, 31, 32, 33, 34, 35, )
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
        $toNames = PDReactionArchivePeer::getFieldNames($toType);
        $key = isset(PDReactionArchivePeer::$fieldKeys[$fromType][$name]) ? PDReactionArchivePeer::$fieldKeys[$fromType][$name] : null;
        if ($key === null) {
            throw new PropelException("'$name' could not be found in the field names of type '$fromType'. These are: " . print_r(PDReactionArchivePeer::$fieldKeys[$fromType], true));
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
        if (!array_key_exists($type, PDReactionArchivePeer::$fieldNames)) {
            throw new PropelException('Method getFieldNames() expects the parameter $type to be one of the class constants BasePeer::TYPE_PHPNAME, BasePeer::TYPE_STUDLYPHPNAME, BasePeer::TYPE_COLNAME, BasePeer::TYPE_FIELDNAME, BasePeer::TYPE_NUM. ' . $type . ' was given.');
        }

        return PDReactionArchivePeer::$fieldNames[$type];
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
     * @param      string $column The column name for current table. (i.e. PDReactionArchivePeer::COLUMN_NAME).
     * @return string
     */
    public static function alias($alias, $column)
    {
        return str_replace(PDReactionArchivePeer::TABLE_NAME.'.', $alias.'.', $column);
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
            $criteria->addSelectColumn(PDReactionArchivePeer::ID);
            $criteria->addSelectColumn(PDReactionArchivePeer::UUID);
            $criteria->addSelectColumn(PDReactionArchivePeer::P_USER_ID);
            $criteria->addSelectColumn(PDReactionArchivePeer::P_D_DEBATE_ID);
            $criteria->addSelectColumn(PDReactionArchivePeer::PARENT_REACTION_ID);
            $criteria->addSelectColumn(PDReactionArchivePeer::P_L_CITY_ID);
            $criteria->addSelectColumn(PDReactionArchivePeer::P_L_DEPARTMENT_ID);
            $criteria->addSelectColumn(PDReactionArchivePeer::P_L_REGION_ID);
            $criteria->addSelectColumn(PDReactionArchivePeer::P_L_COUNTRY_ID);
            $criteria->addSelectColumn(PDReactionArchivePeer::P_C_TOPIC_ID);
            $criteria->addSelectColumn(PDReactionArchivePeer::FB_AD_ID);
            $criteria->addSelectColumn(PDReactionArchivePeer::TITLE);
            $criteria->addSelectColumn(PDReactionArchivePeer::FILE_NAME);
            $criteria->addSelectColumn(PDReactionArchivePeer::COPYRIGHT);
            $criteria->addSelectColumn(PDReactionArchivePeer::DESCRIPTION);
            $criteria->addSelectColumn(PDReactionArchivePeer::NOTE_POS);
            $criteria->addSelectColumn(PDReactionArchivePeer::NOTE_NEG);
            $criteria->addSelectColumn(PDReactionArchivePeer::NB_VIEWS);
            $criteria->addSelectColumn(PDReactionArchivePeer::WANT_BOOST);
            $criteria->addSelectColumn(PDReactionArchivePeer::PUBLISHED);
            $criteria->addSelectColumn(PDReactionArchivePeer::PUBLISHED_AT);
            $criteria->addSelectColumn(PDReactionArchivePeer::PUBLISHED_BY);
            $criteria->addSelectColumn(PDReactionArchivePeer::FAVORITE);
            $criteria->addSelectColumn(PDReactionArchivePeer::ONLINE);
            $criteria->addSelectColumn(PDReactionArchivePeer::HOMEPAGE);
            $criteria->addSelectColumn(PDReactionArchivePeer::MODERATED);
            $criteria->addSelectColumn(PDReactionArchivePeer::MODERATED_PARTIAL);
            $criteria->addSelectColumn(PDReactionArchivePeer::MODERATED_AT);
            $criteria->addSelectColumn(PDReactionArchivePeer::INDEXED_AT);
            $criteria->addSelectColumn(PDReactionArchivePeer::CREATED_AT);
            $criteria->addSelectColumn(PDReactionArchivePeer::UPDATED_AT);
            $criteria->addSelectColumn(PDReactionArchivePeer::SLUG);
            $criteria->addSelectColumn(PDReactionArchivePeer::TREE_LEFT);
            $criteria->addSelectColumn(PDReactionArchivePeer::TREE_RIGHT);
            $criteria->addSelectColumn(PDReactionArchivePeer::TREE_LEVEL);
            $criteria->addSelectColumn(PDReactionArchivePeer::ARCHIVED_AT);
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
            $criteria->addSelectColumn($alias . '.fb_ad_id');
            $criteria->addSelectColumn($alias . '.title');
            $criteria->addSelectColumn($alias . '.file_name');
            $criteria->addSelectColumn($alias . '.copyright');
            $criteria->addSelectColumn($alias . '.description');
            $criteria->addSelectColumn($alias . '.note_pos');
            $criteria->addSelectColumn($alias . '.note_neg');
            $criteria->addSelectColumn($alias . '.nb_views');
            $criteria->addSelectColumn($alias . '.want_boost');
            $criteria->addSelectColumn($alias . '.published');
            $criteria->addSelectColumn($alias . '.published_at');
            $criteria->addSelectColumn($alias . '.published_by');
            $criteria->addSelectColumn($alias . '.favorite');
            $criteria->addSelectColumn($alias . '.online');
            $criteria->addSelectColumn($alias . '.homepage');
            $criteria->addSelectColumn($alias . '.moderated');
            $criteria->addSelectColumn($alias . '.moderated_partial');
            $criteria->addSelectColumn($alias . '.moderated_at');
            $criteria->addSelectColumn($alias . '.indexed_at');
            $criteria->addSelectColumn($alias . '.created_at');
            $criteria->addSelectColumn($alias . '.updated_at');
            $criteria->addSelectColumn($alias . '.slug');
            $criteria->addSelectColumn($alias . '.tree_left');
            $criteria->addSelectColumn($alias . '.tree_right');
            $criteria->addSelectColumn($alias . '.tree_level');
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
        $criteria->setPrimaryTableName(PDReactionArchivePeer::TABLE_NAME);

        if ($distinct && !in_array(Criteria::DISTINCT, $criteria->getSelectModifiers())) {
            $criteria->setDistinct();
        }

        if (!$criteria->hasSelectClause()) {
            PDReactionArchivePeer::addSelectColumns($criteria);
        }

        $criteria->clearOrderByColumns(); // ORDER BY won't ever affect the count
        $criteria->setDbName(PDReactionArchivePeer::DATABASE_NAME); // Set the correct dbName

        if ($con === null) {
            $con = Propel::getConnection(PDReactionArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
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
     * @return PDReactionArchive
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doSelectOne(Criteria $criteria, PropelPDO $con = null)
    {
        $critcopy = clone $criteria;
        $critcopy->setLimit(1);
        $objects = PDReactionArchivePeer::doSelect($critcopy, $con);
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
        return PDReactionArchivePeer::populateObjects(PDReactionArchivePeer::doSelectStmt($criteria, $con));
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
            $con = Propel::getConnection(PDReactionArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        if (!$criteria->hasSelectClause()) {
            $criteria = clone $criteria;
            PDReactionArchivePeer::addSelectColumns($criteria);
        }

        // Set the correct dbName
        $criteria->setDbName(PDReactionArchivePeer::DATABASE_NAME);

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
     * @param PDReactionArchive $obj A PDReactionArchive object.
     * @param      string $key (optional) key to use for instance map (for performance boost if key was already calculated externally).
     */
    public static function addInstanceToPool($obj, $key = null)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if ($key === null) {
                $key = (string) $obj->getId();
            } // if key === null
            PDReactionArchivePeer::$instances[$key] = $obj;
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
     * @param      mixed $value A PDReactionArchive object or a primary key value.
     *
     * @return void
     * @throws PropelException - if the value is invalid.
     */
    public static function removeInstanceFromPool($value)
    {
        if (Propel::isInstancePoolingEnabled() && $value !== null) {
            if (is_object($value) && $value instanceof PDReactionArchive) {
                $key = (string) $value->getId();
            } elseif (is_scalar($value)) {
                // assume we've been passed a primary key
                $key = (string) $value;
            } else {
                $e = new PropelException("Invalid value passed to removeInstanceFromPool().  Expected primary key or PDReactionArchive object; got " . (is_object($value) ? get_class($value) . ' object.' : var_export($value,true)));
                throw $e;
            }

            unset(PDReactionArchivePeer::$instances[$key]);
        }
    } // removeInstanceFromPool()

    /**
     * Retrieves a string version of the primary key from the DB resultset row that can be used to uniquely identify a row in this table.
     *
     * For tables with a single-column primary key, that simple pkey value will be returned.  For tables with
     * a multi-column primary key, a serialize()d version of the primary key will be returned.
     *
     * @param      string $key The key (@see getPrimaryKeyHash()) for this instance.
     * @return PDReactionArchive Found object or null if 1) no instance exists for specified key or 2) instance pooling has been disabled.
     * @see        getPrimaryKeyHash()
     */
    public static function getInstanceFromPool($key)
    {
        if (Propel::isInstancePoolingEnabled()) {
            if (isset(PDReactionArchivePeer::$instances[$key])) {
                return PDReactionArchivePeer::$instances[$key];
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
        foreach (PDReactionArchivePeer::$instances as $instance) {
          $instance->clearAllReferences(true);
        }
      }
        PDReactionArchivePeer::$instances = array();
    }

    /**
     * Method to invalidate the instance pool of all tables related to p_d_reaction_archive
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
        $cls = PDReactionArchivePeer::getOMClass();
        // populate the object(s)
        while ($row = $stmt->fetch(PDO::FETCH_NUM)) {
            $key = PDReactionArchivePeer::getPrimaryKeyHashFromRow($row, 0);
            if (null !== ($obj = PDReactionArchivePeer::getInstanceFromPool($key))) {
                // We no longer rehydrate the object, since this can cause data loss.
                // See http://www.propelorm.org/ticket/509
                // $obj->hydrate($row, 0, true); // rehydrate
                $results[] = $obj;
            } else {
                $obj = new $cls();
                $obj->hydrate($row);
                $results[] = $obj;
                PDReactionArchivePeer::addInstanceToPool($obj, $key);
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
     * @return array (PDReactionArchive object, last column rank)
     */
    public static function populateObject($row, $startcol = 0)
    {
        $key = PDReactionArchivePeer::getPrimaryKeyHashFromRow($row, $startcol);
        if (null !== ($obj = PDReactionArchivePeer::getInstanceFromPool($key))) {
            // We no longer rehydrate the object, since this can cause data loss.
            // See http://www.propelorm.org/ticket/509
            // $obj->hydrate($row, $startcol, true); // rehydrate
            $col = $startcol + PDReactionArchivePeer::NUM_HYDRATE_COLUMNS;
        } else {
            $cls = PDReactionArchivePeer::OM_CLASS;
            $obj = new $cls();
            $col = $obj->hydrate($row, $startcol);
            PDReactionArchivePeer::addInstanceToPool($obj, $key);
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
        return Propel::getDatabaseMap(PDReactionArchivePeer::DATABASE_NAME)->getTable(PDReactionArchivePeer::TABLE_NAME);
    }

    /**
     * Add a TableMap instance to the database for this peer class.
     */
    public static function buildTableMap()
    {
      $dbMap = Propel::getDatabaseMap(BasePDReactionArchivePeer::DATABASE_NAME);
      if (!$dbMap->hasTable(BasePDReactionArchivePeer::TABLE_NAME)) {
        $dbMap->addTableObject(new \Politizr\Model\map\PDReactionArchiveTableMap());
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
        return PDReactionArchivePeer::OM_CLASS;
    }

    /**
     * Performs an INSERT on the database, given a PDReactionArchive or Criteria object.
     *
     * @param      mixed $values Criteria or PDReactionArchive object containing data that is used to create the INSERT statement.
     * @param      PropelPDO $con the PropelPDO connection to use
     * @return mixed           The new primary key.
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doInsert($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PDReactionArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity
        } else {
            $criteria = $values->buildCriteria(); // build Criteria from PDReactionArchive object
        }


        // Set the correct dbName
        $criteria->setDbName(PDReactionArchivePeer::DATABASE_NAME);

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
     * Performs an UPDATE on the database, given a PDReactionArchive or Criteria object.
     *
     * @param      mixed $values Criteria or PDReactionArchive object containing data that is used to create the UPDATE statement.
     * @param      PropelPDO $con The connection to use (specify PropelPDO connection object to exert more control over transactions).
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function doUpdate($values, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PDReactionArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        $selectCriteria = new Criteria(PDReactionArchivePeer::DATABASE_NAME);

        if ($values instanceof Criteria) {
            $criteria = clone $values; // rename for clarity

            $comparison = $criteria->getComparison(PDReactionArchivePeer::ID);
            $value = $criteria->remove(PDReactionArchivePeer::ID);
            if ($value) {
                $selectCriteria->add(PDReactionArchivePeer::ID, $value, $comparison);
            } else {
                $selectCriteria->setPrimaryTableName(PDReactionArchivePeer::TABLE_NAME);
            }

        } else { // $values is PDReactionArchive object
            $criteria = $values->buildCriteria(); // gets full criteria
            $selectCriteria = $values->buildPkeyCriteria(); // gets criteria w/ primary key(s)
        }

        // set the correct dbName
        $criteria->setDbName(PDReactionArchivePeer::DATABASE_NAME);

        return BasePeer::doUpdate($selectCriteria, $criteria, $con);
    }

    /**
     * Deletes all rows from the p_d_reaction_archive table.
     *
     * @param      PropelPDO $con the connection to use
     * @return int             The number of affected rows (if supported by underlying database driver).
     * @throws PropelException
     */
    public static function doDeleteAll(PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PDReactionArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }
        $affectedRows = 0; // initialize var to track total num of affected rows
        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();
            $affectedRows += BasePeer::doDeleteAll(PDReactionArchivePeer::TABLE_NAME, $con, PDReactionArchivePeer::DATABASE_NAME);
            // Because this db requires some delete cascade/set null emulation, we have to
            // clear the cached instance *after* the emulation has happened (since
            // instances get re-added by the select statement contained therein).
            PDReactionArchivePeer::clearInstancePool();
            PDReactionArchivePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Performs a DELETE on the database, given a PDReactionArchive or Criteria object OR a primary key value.
     *
     * @param      mixed $values Criteria or PDReactionArchive object or primary key or array of primary keys
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
            $con = Propel::getConnection(PDReactionArchivePeer::DATABASE_NAME, Propel::CONNECTION_WRITE);
        }

        if ($values instanceof Criteria) {
            // invalidate the cache for all objects of this type, since we have no
            // way of knowing (without running a query) what objects should be invalidated
            // from the cache based on this Criteria.
            PDReactionArchivePeer::clearInstancePool();
            // rename for clarity
            $criteria = clone $values;
        } elseif ($values instanceof PDReactionArchive) { // it's a model object
            // invalidate the cache for this single object
            PDReactionArchivePeer::removeInstanceFromPool($values);
            // create criteria based on pk values
            $criteria = $values->buildPkeyCriteria();
        } else { // it's a primary key, or an array of pks
            $criteria = new Criteria(PDReactionArchivePeer::DATABASE_NAME);
            $criteria->add(PDReactionArchivePeer::ID, (array) $values, Criteria::IN);
            // invalidate the cache for this object(s)
            foreach ((array) $values as $singleval) {
                PDReactionArchivePeer::removeInstanceFromPool($singleval);
            }
        }

        // Set the correct dbName
        $criteria->setDbName(PDReactionArchivePeer::DATABASE_NAME);

        $affectedRows = 0; // initialize var to track total num of affected rows

        try {
            // use transaction because $criteria could contain info
            // for more than one table or we could emulating ON DELETE CASCADE, etc.
            $con->beginTransaction();

            $affectedRows += BasePeer::doDelete($criteria, $con);
            PDReactionArchivePeer::clearRelatedInstancePool();
            $con->commit();

            return $affectedRows;
        } catch (Exception $e) {
            $con->rollBack();
            throw $e;
        }
    }

    /**
     * Validates all modified columns of given PDReactionArchive object.
     * If parameter $columns is either a single column name or an array of column names
     * than only those columns are validated.
     *
     * NOTICE: This does not apply to primary or foreign keys for now.
     *
     * @param PDReactionArchive $obj The object to validate.
     * @param      mixed $cols Column name or array of column names.
     *
     * @return mixed TRUE if all columns are valid or the error message of the first invalid column.
     */
    public static function doValidate($obj, $cols = null)
    {
        $columns = array();

        if ($cols) {
            $dbMap = Propel::getDatabaseMap(PDReactionArchivePeer::DATABASE_NAME);
            $tableMap = $dbMap->getTable(PDReactionArchivePeer::TABLE_NAME);

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

        return BasePeer::doValidate(PDReactionArchivePeer::DATABASE_NAME, PDReactionArchivePeer::TABLE_NAME, $columns);
    }

    /**
     * Retrieve a single object by pkey.
     *
     * @param int $pk the primary key.
     * @param      PropelPDO $con the connection to use
     * @return PDReactionArchive
     */
    public static function retrieveByPK($pk, PropelPDO $con = null)
    {

        if (null !== ($obj = PDReactionArchivePeer::getInstanceFromPool((string) $pk))) {
            return $obj;
        }

        if ($con === null) {
            $con = Propel::getConnection(PDReactionArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $criteria = new Criteria(PDReactionArchivePeer::DATABASE_NAME);
        $criteria->add(PDReactionArchivePeer::ID, $pk);

        $v = PDReactionArchivePeer::doSelect($criteria, $con);

        return !empty($v) > 0 ? $v[0] : null;
    }

    /**
     * Retrieve multiple objects by pkey.
     *
     * @param      array $pks List of primary keys
     * @param      PropelPDO $con the connection to use
     * @return PDReactionArchive[]
     * @throws PropelException Any exceptions caught during processing will be
     *		 rethrown wrapped into a PropelException.
     */
    public static function retrieveByPKs($pks, PropelPDO $con = null)
    {
        if ($con === null) {
            $con = Propel::getConnection(PDReactionArchivePeer::DATABASE_NAME, Propel::CONNECTION_READ);
        }

        $objs = null;
        if (empty($pks)) {
            $objs = array();
        } else {
            $criteria = new Criteria(PDReactionArchivePeer::DATABASE_NAME);
            $criteria->add(PDReactionArchivePeer::ID, $pks, Criteria::IN);
            $objs = PDReactionArchivePeer::doSelect($criteria, $con);
        }

        return $objs;
    }

} // BasePDReactionArchivePeer

// This is the static code needed to register the TableMap for this table with the main Propel class.
//
BasePDReactionArchivePeer::buildTableMap();

