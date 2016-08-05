<?php

namespace Politizr\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'p_d_reaction' table.
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
class PDReactionTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Politizr.Model.map.PDReactionTableMap';

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
        $this->setName('p_d_reaction');
        $this->setPhpName('PDReaction');
        $this->setClassname('Politizr\\Model\\PDReaction');
        $this->setPackage('src.Politizr.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('uuid', 'Uuid', 'VARCHAR', false, 50, null);
        $this->addForeignKey('p_user_id', 'PUserId', 'INTEGER', 'p_user', 'id', false, null, null);
        $this->addForeignKey('p_d_debate_id', 'PDDebateId', 'INTEGER', 'p_d_debate', 'id', true, null, null);
        $this->addColumn('parent_reaction_id', 'ParentReactionId', 'INTEGER', false, null, null);
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
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('PUser', 'Politizr\\Model\\PUser', RelationMap::MANY_TO_ONE, array('p_user_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('PDDebate', 'Politizr\\Model\\PDDebate', RelationMap::MANY_TO_ONE, array('p_d_debate_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('PuBookmarkDrPDReaction', 'Politizr\\Model\\PUBookmarkDR', RelationMap::ONE_TO_MANY, array('id' => 'p_d_reaction_id', ), 'CASCADE', 'CASCADE', 'PuBookmarkDrPDReactions');
        $this->addRelation('PuTrackDrPDReaction', 'Politizr\\Model\\PUTrackDR', RelationMap::ONE_TO_MANY, array('id' => 'p_d_reaction_id', ), 'CASCADE', 'CASCADE', 'PuTrackDrPDReactions');
        $this->addRelation('PDRComment', 'Politizr\\Model\\PDRComment', RelationMap::ONE_TO_MANY, array('id' => 'p_d_reaction_id', ), 'CASCADE', 'CASCADE', 'PDRComments');
        $this->addRelation('PDRTaggedT', 'Politizr\\Model\\PDRTaggedT', RelationMap::ONE_TO_MANY, array('id' => 'p_d_reaction_id', ), 'CASCADE', 'CASCADE', 'PDRTaggedTs');
        $this->addRelation('PMReactionHistoric', 'Politizr\\Model\\PMReactionHistoric', RelationMap::ONE_TO_MANY, array('id' => 'p_d_reaction_id', ), 'SET NULL', 'CASCADE', 'PMReactionHistorics');
        $this->addRelation('PuBookmarkDrPUser', 'Politizr\\Model\\PUser', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PuBookmarkDrPUsers');
        $this->addRelation('PuTrackDrPUser', 'Politizr\\Model\\PUser', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PuTrackDrPUsers');
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
            'nested_set' =>  array (
  'left_column' => 'tree_left',
  'right_column' => 'tree_right',
  'level_column' => 'tree_level',
  'use_scope' => 'true',
  'scope_column' => 'p_d_debate_id',
  'method_proxies' => 'false',
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

} // PDReactionTableMap
