<?php

namespace Politizr\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'p_u_track_u' table.
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
class PUTrackUTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Politizr.Model.map.PUTrackUTableMap';

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
        $this->setName('p_u_track_u');
        $this->setPhpName('PUTrackU');
        $this->setClassname('Politizr\\Model\\PUTrackU');
        $this->setPackage('src.Politizr.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addForeignPrimaryKey('p_user_id_source', 'PUserIdSource', 'INTEGER' , 'p_user', 'id', true, null, null);
        $this->addForeignPrimaryKey('p_user_id_dest', 'PUserIdDest', 'INTEGER' , 'p_user', 'id', true, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('PUserRelatedByPUserIdSource', 'Politizr\\Model\\PUser', RelationMap::MANY_TO_ONE, array('p_user_id_source' => 'id', ), 'CASCADE', null);
        $this->addRelation('PUserRelatedByPUserIdDest', 'Politizr\\Model\\PUser', RelationMap::MANY_TO_ONE, array('p_user_id_dest' => 'id', ), 'CASCADE', null);
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
            'equal_nest' =>  array (
  'parent_table' => 'p_user',
  'reference_column_1' => 'p_user_id_source',
  'reference_column_2' => 'p_user_id_dest',
),
        );
    } // getBehaviors()

} // PUTrackUTableMap
