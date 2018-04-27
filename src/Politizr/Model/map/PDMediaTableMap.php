<?php

namespace Politizr\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'p_d_media' table.
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
class PDMediaTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Politizr.Model.map.PDMediaTableMap';

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
        $this->setName('p_d_media');
        $this->setPhpName('PDMedia');
        $this->setClassname('Politizr\\Model\\PDMedia');
        $this->setPackage('src.Politizr.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('p_d_debate_id', 'PDDebateId', 'INTEGER', 'p_d_debate', 'id', false, null, null);
        $this->addForeignKey('p_d_reaction_id', 'PDReactionId', 'INTEGER', 'p_d_reaction', 'id', false, null, null);
        $this->addColumn('path', 'Path', 'VARCHAR', false, 250, null);
        $this->addColumn('file_name', 'FileName', 'VARCHAR', false, 150, null);
        $this->addColumn('extension', 'Extension', 'VARCHAR', false, 150, null);
        $this->addColumn('size', 'Size', 'INTEGER', false, null, null);
        $this->addColumn('width', 'Width', 'INTEGER', false, null, null);
        $this->addColumn('height', 'Height', 'INTEGER', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('uuid', 'Uuid', 'CHAR', true, 36, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('PDDebate', 'Politizr\\Model\\PDDebate', RelationMap::MANY_TO_ONE, array('p_d_debate_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('PDReaction', 'Politizr\\Model\\PDReaction', RelationMap::MANY_TO_ONE, array('p_d_reaction_id' => 'id', ), 'CASCADE', 'CASCADE');
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
            'uuid' =>  array (
  'name' => 'uuid',
  'version' => '4',
  'permanent' => 'false',
  'required' => 'true',
  'unique' => 'true',
),
        );
    } // getBehaviors()

} // PDMediaTableMap
