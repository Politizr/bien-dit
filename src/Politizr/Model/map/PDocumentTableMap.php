<?php

namespace Politizr\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'p_document' table.
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
class PDocumentTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Politizr.Model.map.PDocumentTableMap';

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
        $this->setName('p_document');
        $this->setPhpName('PDocument');
        $this->setClassname('Politizr\\Model\\PDocument');
        $this->setPackage('src.Politizr.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('p_user_id', 'PUserId', 'INTEGER', 'p_user', 'id', false, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', false, 100, null);
        $this->addColumn('summary', 'Summary', 'LONGVARCHAR', false, null, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('more_info', 'MoreInfo', 'LONGVARCHAR', false, null, null);
        $this->addColumn('note_pos', 'NotePos', 'INTEGER', false, null, null);
        $this->addColumn('note_neg', 'NoteNeg', 'INTEGER', false, null, null);
        $this->addColumn('published', 'Published', 'BOOLEAN', false, 1, null);
        $this->addColumn('published_at', 'PublishedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('published_by', 'PublishedBy', 'VARCHAR', false, 300, null);
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
        $this->addRelation('PUser', 'Politizr\\Model\\PUser', RelationMap::MANY_TO_ONE, array('p_user_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('PDDebate', 'Politizr\\Model\\PDDebate', RelationMap::ONE_TO_MANY, array('id' => 'p_document_id', ), 'CASCADE', 'CASCADE', 'PDDebates');
        $this->addRelation('PDReaction', 'Politizr\\Model\\PDReaction', RelationMap::ONE_TO_MANY, array('id' => 'p_document_id', ), 'CASCADE', 'CASCADE', 'PDReactions');
        $this->addRelation('PDComment', 'Politizr\\Model\\PDComment', RelationMap::ONE_TO_MANY, array('id' => 'p_document_id', ), 'CASCADE', 'CASCADE', 'PDComments');
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

} // PDocumentTableMap
