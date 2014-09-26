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
        $this->addColumn('file_name', 'FileName', 'VARCHAR', false, 150, null);
        $this->addForeignKey('p_document_id', 'PDocumentId', 'INTEGER', 'p_document', 'id', true, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('PDocument', 'Politizr\\Model\\PDocument', RelationMap::MANY_TO_ONE, array('p_document_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('PuFollowDdPDDebate', 'Politizr\\Model\\PUFollowDD', RelationMap::ONE_TO_MANY, array('id' => 'p_d_debate_id', ), 'CASCADE', 'CASCADE', 'PuFollowDdPDDebates');
        $this->addRelation('PDReaction', 'Politizr\\Model\\PDReaction', RelationMap::ONE_TO_MANY, array('id' => 'p_d_debate_id', ), 'CASCADE', 'CASCADE', 'PDReactions');
        $this->addRelation('PddTaggedTPDDebate', 'Politizr\\Model\\PDDTaggedT', RelationMap::ONE_TO_MANY, array('id' => 'p_d_debate_id', ), 'CASCADE', 'CASCADE', 'PddTaggedTPDDebates');
        $this->addRelation('PuFollowDdPUser', 'Politizr\\Model\\PUser', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PuFollowDdPUsers');
        $this->addRelation('PddTaggedTPTag', 'Politizr\\Model\\PTag', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PddTaggedTPTags');
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
            'delegate' =>  array (
  'to' => 'p_document',
),
        );
    } // getBehaviors()

} // PDDebateTableMap
