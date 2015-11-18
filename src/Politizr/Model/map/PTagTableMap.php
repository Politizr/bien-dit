<?php

namespace Politizr\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'p_tag' table.
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
class PTagTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Politizr.Model.map.PTagTableMap';

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
        $this->setName('p_tag');
        $this->setPhpName('PTag');
        $this->setClassname('Politizr\\Model\\PTag');
        $this->setPackage('src.Politizr.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('p_t_tag_type_id', 'PTTagTypeId', 'INTEGER', 'p_t_tag_type', 'id', true, null, null);
        $this->addForeignKey('p_t_parent_id', 'PTParentId', 'INTEGER', 'p_tag', 'id', false, null, null);
        $this->addForeignKey('p_user_id', 'PUserId', 'INTEGER', 'p_user', 'id', false, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', false, 150, null);
        $this->addColumn('moderated', 'Moderated', 'BOOLEAN', false, 1, null);
        $this->addColumn('moderated_at', 'ModeratedAt', 'TIMESTAMP', false, null, null);
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
        $this->addRelation('PTTagType', 'Politizr\\Model\\PTTagType', RelationMap::MANY_TO_ONE, array('p_t_tag_type_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('PTagRelatedByPTParentId', 'Politizr\\Model\\PTag', RelationMap::MANY_TO_ONE, array('p_t_parent_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('PUser', 'Politizr\\Model\\PUser', RelationMap::MANY_TO_ONE, array('p_user_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('PTagRelatedById', 'Politizr\\Model\\PTag', RelationMap::ONE_TO_MANY, array('id' => 'p_t_parent_id', ), 'SET NULL', 'CASCADE', 'PTagsRelatedById');
        $this->addRelation('PuTaggedTPTag', 'Politizr\\Model\\PUTaggedT', RelationMap::ONE_TO_MANY, array('id' => 'p_tag_id', ), 'CASCADE', 'CASCADE', 'PuTaggedTPTags');
        $this->addRelation('PuFollowTPTag', 'Politizr\\Model\\PUFollowT', RelationMap::ONE_TO_MANY, array('id' => 'p_tag_id', ), 'CASCADE', 'CASCADE', 'PuFollowTPTags');
        $this->addRelation('PDDTaggedT', 'Politizr\\Model\\PDDTaggedT', RelationMap::ONE_TO_MANY, array('id' => 'p_tag_id', ), 'CASCADE', 'CASCADE', 'PDDTaggedTs');
        $this->addRelation('PDRTaggedT', 'Politizr\\Model\\PDRTaggedT', RelationMap::ONE_TO_MANY, array('id' => 'p_tag_id', ), 'CASCADE', 'CASCADE', 'PDRTaggedTs');
        $this->addRelation('PuTaggedTPUser', 'Politizr\\Model\\PUser', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PuTaggedTPUsers');
        $this->addRelation('PuFollowTPUser', 'Politizr\\Model\\PUser', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PuFollowTPUsers');
        $this->addRelation('PDDebate', 'Politizr\\Model\\PDDebate', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PDDebates');
        $this->addRelation('PDReaction', 'Politizr\\Model\\PDReaction', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PDReactions');
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
            'event' =>  array (
),
            'extend' =>  array (
),
        );
    } // getBehaviors()

} // PTagTableMap
