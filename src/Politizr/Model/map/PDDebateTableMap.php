<?php

namespace Politizr\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'p_d_debate' table.
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
class PDDebateTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Politizr.Model.map.PDDebateTableMap';

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
        $this->setName('p_d_debate');
        $this->setPhpName('PDDebate');
        $this->setClassname('Politizr\\Model\\PDDebate');
        $this->setPackage('src.Politizr.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('uuid', 'Uuid', 'VARCHAR', false, 50, null);
        $this->addForeignKey('p_user_id', 'PUserId', 'INTEGER', 'p_user', 'id', false, null, null);
        $this->addForeignKey('p_e_operation_id', 'PEOperationId', 'INTEGER', 'p_e_operation', 'id', false, null, null);
        $this->addForeignKey('p_l_city_id', 'PLCityId', 'INTEGER', 'p_l_city', 'id', false, null, null);
        $this->addForeignKey('p_l_department_id', 'PLDepartmentId', 'INTEGER', 'p_l_department', 'id', false, null, null);
        $this->addForeignKey('p_l_region_id', 'PLRegionId', 'INTEGER', 'p_l_region', 'id', false, null, null);
        $this->addForeignKey('p_l_country_id', 'PLCountryId', 'INTEGER', 'p_l_country', 'id', false, null, null);
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
        $this->addColumn('indexed_at', 'IndexedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('slug', 'Slug', 'VARCHAR', false, 255, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('PUser', 'Politizr\\Model\\PUser', RelationMap::MANY_TO_ONE, array('p_user_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('PLCity', 'Politizr\\Model\\PLCity', RelationMap::MANY_TO_ONE, array('p_l_city_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('PLDepartment', 'Politizr\\Model\\PLDepartment', RelationMap::MANY_TO_ONE, array('p_l_department_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('PLRegion', 'Politizr\\Model\\PLRegion', RelationMap::MANY_TO_ONE, array('p_l_region_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('PLCountry', 'Politizr\\Model\\PLCountry', RelationMap::MANY_TO_ONE, array('p_l_country_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('PEOperation', 'Politizr\\Model\\PEOperation', RelationMap::MANY_TO_ONE, array('p_e_operation_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('PuFollowDdPDDebate', 'Politizr\\Model\\PUFollowDD', RelationMap::ONE_TO_MANY, array('id' => 'p_d_debate_id', ), 'CASCADE', 'CASCADE', 'PuFollowDdPDDebates');
        $this->addRelation('PuBookmarkDdPDDebate', 'Politizr\\Model\\PUBookmarkDD', RelationMap::ONE_TO_MANY, array('id' => 'p_d_debate_id', ), 'CASCADE', 'CASCADE', 'PuBookmarkDdPDDebates');
        $this->addRelation('PuTrackDdPDDebate', 'Politizr\\Model\\PUTrackDD', RelationMap::ONE_TO_MANY, array('id' => 'p_d_debate_id', ), 'CASCADE', 'CASCADE', 'PuTrackDdPDDebates');
        $this->addRelation('PDReaction', 'Politizr\\Model\\PDReaction', RelationMap::ONE_TO_MANY, array('id' => 'p_d_debate_id', ), 'CASCADE', 'CASCADE', 'PDReactions');
        $this->addRelation('PDDComment', 'Politizr\\Model\\PDDComment', RelationMap::ONE_TO_MANY, array('id' => 'p_d_debate_id', ), 'CASCADE', 'CASCADE', 'PDDComments');
        $this->addRelation('PDDTaggedT', 'Politizr\\Model\\PDDTaggedT', RelationMap::ONE_TO_MANY, array('id' => 'p_d_debate_id', ), 'CASCADE', 'CASCADE', 'PDDTaggedTs');
        $this->addRelation('PMDebateHistoric', 'Politizr\\Model\\PMDebateHistoric', RelationMap::ONE_TO_MANY, array('id' => 'p_d_debate_id', ), 'SET NULL', 'CASCADE', 'PMDebateHistorics');
        $this->addRelation('PuFollowDdPUser', 'Politizr\\Model\\PUser', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PuFollowDdPUsers');
        $this->addRelation('PuBookmarkDdPUser', 'Politizr\\Model\\PUser', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PuBookmarkDdPUsers');
        $this->addRelation('PuTrackDdPUser', 'Politizr\\Model\\PUser', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PuTrackDdPUsers');
        $this->addRelation('PTag', 'Politizr\\Model\\PTag', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PTags');
    } // buildRelations()

    /**
     *
     * Gets the list of behaviors registered for this table
     *
     * @return array Associative array (name => parameters) of behaviors
     */
    public function getBehaviors()
    {
        return array(
            'timestampable' =>  array (
  'create_column' => 'created_at',
  'update_column' => 'updated_at',
  'disable_updated_at' => 'false',
),
            'query_cache' =>  array (
  'backend' => 'apc',
  'lifetime' => 3600,
),
            'sluggable' =>  array (
  'add_cleanup' => 'true',
  'slug_column' => 'slug',
  'slug_pattern' => '{title}',
  'replace_pattern' => '/\\W+/',
  'replacement' => '-',
  'separator' => '-',
  'permanent' => 'false',
  'scope_column' => '',
),
            'uuid' =>  array (
  'name' => 'uuid',
  'version' => '4',
  'permanent' => 'false',
  'required' => 'true',
  'unique' => 'true',
),
            'archivable' =>  array (
  'archive_table' => '',
  'archive_phpname' => NULL,
  'archive_class' => '',
  'log_archived_at' => 'true',
  'archived_at_column' => 'archived_at',
  'archive_on_insert' => 'false',
  'archive_on_update' => 'false',
  'archive_on_delete' => 'true',
),
        );
    } // getBehaviors()

} // PDDebateTableMap
