<?php

namespace Politizr\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'p_o_order_state' table.
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
class POOrderStateTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Politizr.Model.map.POOrderStateTableMap';

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
        $this->setName('p_o_order_state');
        $this->setPhpName('POOrderState');
        $this->setClassname('Politizr\\Model\\POOrderState');
        $this->setPackage('src.Politizr.Model');
        $this->setUseIdGenerator(false);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', false, 150, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('POrder', 'Politizr\\Model\\POrder', RelationMap::ONE_TO_MANY, array('id' => 'p_o_order_state_id', ), 'SET NULL', 'CASCADE', 'POrders');
        $this->addRelation('POEmail', 'Politizr\\Model\\POEmail', RelationMap::ONE_TO_MANY, array('id' => 'p_o_order_state_id', ), 'SET NULL', 'CASCADE', 'POEmails');
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
        );
    } // getBehaviors()

} // POOrderStateTableMap
