<?php

namespace StudioEcho\StudioEchoMediaBundle\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'se_media_object' table.
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
class SeMediaObjectTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.StudioEcho.StudioEchoMediaBundle.Model.map.SeMediaObjectTableMap';

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
        $this->setName('se_media_object');
        $this->setPhpName('SeMediaObject');
        $this->setClassname('StudioEcho\\StudioEchoMediaBundle\\Model\\SeMediaObject');
        $this->setPackage('src.StudioEcho.StudioEchoMediaBundle.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('object_id', 'ObjectId', 'INTEGER', true, null, null);
        $this->addColumn('object_classname', 'ObjectClassname', 'VARCHAR', true, 250, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('SeObjectHasFile', 'StudioEcho\\StudioEchoMediaBundle\\Model\\SeObjectHasFile', RelationMap::ONE_TO_MANY, array('id' => 'se_media_object_id', ), 'CASCADE', 'CASCADE', 'SeObjectHasFiles');
        $this->addRelation('SeMediaFile', 'StudioEcho\\StudioEchoMediaBundle\\Model\\SeMediaFile', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'SeMediaFiles');
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
        );
    } // getBehaviors()

} // SeMediaObjectTableMap
