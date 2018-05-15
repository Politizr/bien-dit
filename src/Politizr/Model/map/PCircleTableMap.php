<?php

namespace Politizr\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'p_circle' table.
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
class PCircleTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Politizr.Model.map.PCircleTableMap';

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
        $this->setName('p_circle');
        $this->setPhpName('PCircle');
        $this->setClassname('Politizr\\Model\\PCircle');
        $this->setPackage('src.Politizr.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('uuid', 'Uuid', 'VARCHAR', false, 50, null);
        $this->addForeignKey('p_c_owner_id', 'PCOwnerId', 'INTEGER', 'p_c_owner', 'id', true, null, null);
        $this->addForeignKey('p_circle_type_id', 'PCircleTypeId', 'INTEGER', 'p_circle_type', 'id', false, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', false, 100, null);
        $this->addColumn('summary', 'Summary', 'LONGVARCHAR', false, null, null);
        $this->addColumn('description', 'Description', 'CLOB', false, null, null);
        $this->addColumn('logo_file_name', 'LogoFileName', 'VARCHAR', false, 150, null);
        $this->addColumn('url', 'Url', 'VARCHAR', false, 150, null);
        $this->addColumn('online', 'Online', 'BOOLEAN', false, 1, null);
        $this->addColumn('read_only', 'ReadOnly', 'BOOLEAN', false, 1, null);
        $this->addColumn('private_access', 'PrivateAccess', 'BOOLEAN', false, 1, false);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('slug', 'Slug', 'VARCHAR', false, 255, null);
        $this->addColumn('sortable_rank', 'SortableRank', 'INTEGER', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('PCOwner', 'Politizr\\Model\\PCOwner', RelationMap::MANY_TO_ONE, array('p_c_owner_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('PCircleType', 'Politizr\\Model\\PCircleType', RelationMap::MANY_TO_ONE, array('p_circle_type_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('PCTopic', 'Politizr\\Model\\PCTopic', RelationMap::ONE_TO_MANY, array('id' => 'p_circle_id', ), 'CASCADE', 'CASCADE', 'PCTopics');
        $this->addRelation('PCGroupLC', 'Politizr\\Model\\PCGroupLC', RelationMap::ONE_TO_MANY, array('id' => 'p_circle_id', ), 'CASCADE', 'CASCADE', 'PCGroupLCs');
        $this->addRelation('PUInPC', 'Politizr\\Model\\PUInPC', RelationMap::ONE_TO_MANY, array('id' => 'p_circle_id', ), 'CASCADE', 'CASCADE', 'PUInPCs');
        $this->addRelation('PMCharte', 'Politizr\\Model\\PMCharte', RelationMap::ONE_TO_MANY, array('id' => 'p_circle_id', ), 'SET NULL', 'CASCADE', 'PMChartes');
        $this->addRelation('PLCity', 'Politizr\\Model\\PLCity', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PLCities');
        $this->addRelation('PUser', 'Politizr\\Model\\PUser', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PUsers');
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
            'sortable' =>  array (
  'rank_column' => 'sortable_rank',
  'use_scope' => 'true',
  'scope_column' => 'p_c_owner_id',
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

} // PCircleTableMap
