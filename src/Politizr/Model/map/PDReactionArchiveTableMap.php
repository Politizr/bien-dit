<?php

namespace Politizr\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'p_d_reaction_archive' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.Politizr.Model.map
 */
class PDReactionArchiveTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Politizr.Model.map.PDReactionArchiveTableMap';

    /**
     * Initialize the table attributes, columns and validators
     * Relations are not initialized by this method since they are lazy loaded
     *
     * @return void
     * @throws PropelException
     */
    public function initialize()
    {
        // attributes
        $this->setName('p_d_reaction_archive');
        $this->setPhpName('PDReactionArchive');
        $this->setClassname('Politizr\\Model\\PDReactionArchive');
        $this->setPackage('src.Politizr.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('uuid', 'Uuid', 'VARCHAR', false, 50, null);
        $this->addColumn('p_user_id', 'PUserId', 'INTEGER', false, null, null);
        $this->addColumn('p_d_debate_id', 'PDDebateId', 'INTEGER', true, null, null);
        $this->addColumn('parent_reaction_id', 'ParentReactionId', 'INTEGER', false, null, null);
        $this->addColumn('p_l_city_id', 'PLCityId', 'INTEGER', false, null, null);
        $this->addColumn('p_l_department_id', 'PLDepartmentId', 'INTEGER', false, null, null);
        $this->addColumn('p_l_region_id', 'PLRegionId', 'INTEGER', false, null, null);
        $this->addColumn('p_l_country_id', 'PLCountryId', 'INTEGER', false, null, null);
        $this->addColumn('fb_ad_id', 'FbAdId', 'VARCHAR', false, 150, null);
        $this->addColumn('title', 'Title', 'VARCHAR', false, 100, null);
        $this->addColumn('file_name', 'FileName', 'VARCHAR', false, 150, null);
        $this->addColumn('copyright', 'Copyright', 'LONGVARCHAR', false, null, null);
        $this->addColumn('description', 'Description', 'CLOB', false, null, null);
        $this->addColumn('note_pos', 'NotePos', 'INTEGER', false, null, 0);
        $this->addColumn('note_neg', 'NoteNeg', 'INTEGER', false, null, 0);
        $this->addColumn('nb_views', 'NbViews', 'INTEGER', false, null, null);
        $this->addColumn('published', 'Published', 'BOOLEAN', false, 1, null);
        $this->addColumn('published_at', 'PublishedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('published_by', 'PublishedBy', 'VARCHAR', false, 300, null);
        $this->addColumn('favorite', 'Favorite', 'BOOLEAN', false, 1, null);
        $this->addColumn('online', 'Online', 'BOOLEAN', false, 1, null);
        $this->addColumn('homepage', 'Homepage', 'BOOLEAN', false, 1, null);
        $this->addColumn('moderated', 'Moderated', 'BOOLEAN', false, 1, null);
        $this->addColumn('moderated_partial', 'ModeratedPartial', 'BOOLEAN', false, 1, null);
        $this->addColumn('moderated_at', 'ModeratedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('slug', 'Slug', 'VARCHAR', false, 255, null);
        $this->addColumn('tree_left', 'TreeLeft', 'INTEGER', false, null, null);
        $this->addColumn('tree_right', 'TreeRight', 'INTEGER', false, null, null);
        $this->addColumn('tree_level', 'TreeLevel', 'INTEGER', false, null, null);
        $this->addColumn('archived_at', 'ArchivedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
    } // buildRelations()

} // PDReactionArchiveTableMap
