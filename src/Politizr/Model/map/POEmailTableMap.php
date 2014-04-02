<?php

namespace Politizr\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'p_o_email' table.
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
class POEmailTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Politizr.Model.map.POEmailTableMap';

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
        $this->setName('p_o_email');
        $this->setPhpName('POEmail');
        $this->setClassname('Politizr\\Model\\POEmail');
        $this->setPackage('src.Politizr.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addForeignKey('p_order_id', 'POrderId', 'INTEGER', 'p_order', 'id', true, null, null);
        $this->addForeignKey('p_o_order_state_id', 'POOrderStateId', 'INTEGER', 'p_o_order_state', 'id', false, null, null);
        $this->addForeignKey('p_o_payment_state_id', 'POPaymentStateId', 'INTEGER', 'p_o_payment_state', 'id', false, null, null);
        $this->addForeignKey('p_o_payment_type_id', 'POPaymentTypeId', 'INTEGER', 'p_o_payment_type', 'id', false, null, null);
        $this->addForeignKey('p_o_subscription_id', 'POSubscriptionId', 'INTEGER', 'p_o_subscription', 'id', false, null, null);
        $this->addColumn('subject', 'Subject', 'VARCHAR', false, 250, null);
        $this->addColumn('html_body', 'HtmlBody', 'LONGVARCHAR', false, null, null);
        $this->addColumn('txt_body', 'TxtBody', 'LONGVARCHAR', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('POrder', 'Politizr\\Model\\POrder', RelationMap::MANY_TO_ONE, array('p_order_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('POOrderState', 'Politizr\\Model\\POOrderState', RelationMap::MANY_TO_ONE, array('p_o_order_state_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('POPaymentState', 'Politizr\\Model\\POPaymentState', RelationMap::MANY_TO_ONE, array('p_o_payment_state_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('POPaymentType', 'Politizr\\Model\\POPaymentType', RelationMap::MANY_TO_ONE, array('p_o_payment_type_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('POSubscription', 'Politizr\\Model\\POSubscription', RelationMap::MANY_TO_ONE, array('p_o_subscription_id' => 'id', ), 'SET NULL', 'CASCADE');
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

} // POEmailTableMap
