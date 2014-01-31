<?php

namespace StudioEcho\StudioEchoMediaBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'se_object_has_file' table.
 *
 *
 *
 * This map class is used by Propel to do runtime db structure discovery.
 * For example, the createSelectSql() method checks the type of a given column used in an
 * ORDER BY clause to know whether it needs to apply SQL to make the ORDER BY case-insensitive
 * (i.e. if it's a text column type).
 *
 * @package    propel.generator.src.StudioEcho.StudioEchoMediaBundle.Model.map
 */
class SeObjectHasFileTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.StudioEcho.StudioEchoMediaBundle.Model.map.SeObjectHasFileTableMap';

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
        $this->setName('se_object_has_file');
        $this->setPhpName('SeObjectHasFile');
        $this->setClassname('StudioEcho\\StudioEchoMediaBundle\\Model\\SeObjectHasFile');
        $this->setPackage('src.StudioEcho.StudioEchoMediaBundle.Model');
        $this->setUseIdGenerator(false);
        $this->setIsCrossRef(true);
        // columns
        $this->addForeignPrimaryKey('se_media_object_id', 'SeMediaObjectId', 'INTEGER' , 'se_media_object', 'id', true, null, null);
        $this->addForeignPrimaryKey('se_media_file_id', 'SeMediaFileId', 'INTEGER' , 'se_media_file', 'id', true, null, null);
        $this->addColumn('sortable_rank', 'SortableRank', 'INTEGER', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('SeMediaObject', 'StudioEcho\\StudioEchoMediaBundle\\Model\\SeMediaObject', RelationMap::MANY_TO_ONE, array('se_media_object_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('SeMediaFile', 'StudioEcho\\StudioEchoMediaBundle\\Model\\SeMediaFile', RelationMap::MANY_TO_ONE, array('se_media_file_id' => 'id', ), 'CASCADE', 'CASCADE');
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
            'sortable' =>  array (
  'rank_column' => 'sortable_rank',
  'use_scope' => 'true',
  'scope_column' => 'se_media_object_id',
),
            'timestampable' =>  array (
  'create_column' => 'created_at',
  'update_column' => 'updated_at',
  'disable_updated_at' => 'false',
),
        );
    } // getBehaviors()

} // SeObjectHasFileTableMap
