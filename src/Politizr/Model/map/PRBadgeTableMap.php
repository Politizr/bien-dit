<?php

namespace Politizr\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'p_r_badge' table.
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
class PRBadgeTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Politizr.Model.map.PRBadgeTableMap';

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
        $this->setName('p_r_badge');
        $this->setPhpName('PRBadge');
        $this->setClassname('Politizr\\Model\\PRBadge');
        $this->setPackage('src.Politizr.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('p_r_badge_type_id', 'PRBadgeTypeId', 'INTEGER', 'p_r_badge_type', 'id', true, null, null);
        $this->addForeignKey('p_r_badge_metal_id', 'PRBadgeMetalId', 'INTEGER', 'p_r_badge_metal', 'id', true, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', false, 150, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('online', 'Online', 'BOOLEAN', false, 1, null);
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
        $this->addRelation('PRBadgeType', 'Politizr\\Model\\PRBadgeType', RelationMap::MANY_TO_ONE, array('p_r_badge_type_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('PRBadgeMetal', 'Politizr\\Model\\PRBadgeMetal', RelationMap::MANY_TO_ONE, array('p_r_badge_metal_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('PUReputationRB', 'Politizr\\Model\\PUReputationRB', RelationMap::ONE_TO_MANY, array('id' => 'p_r_badge_id', ), 'CASCADE', 'CASCADE', 'PUReputationRBs');
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
        );
    } // getBehaviors()

} // PRBadgeTableMap
