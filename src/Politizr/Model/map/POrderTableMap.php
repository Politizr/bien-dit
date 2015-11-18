<?php

namespace Politizr\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'p_order' table.
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
class POrderTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Politizr.Model.map.POrderTableMap';

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
        $this->setName('p_order');
        $this->setPhpName('POrder');
        $this->setClassname('Politizr\\Model\\POrder');
        $this->setPackage('src.Politizr.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('uuid', 'Uuid', 'VARCHAR', false, 50, null);
        $this->addForeignKey('p_user_id', 'PUserId', 'INTEGER', 'p_user', 'id', false, null, null);
        $this->addForeignKey('p_o_order_state_id', 'POOrderStateId', 'INTEGER', 'p_o_order_state', 'id', false, null, null);
        $this->addForeignKey('p_o_payment_state_id', 'POPaymentStateId', 'INTEGER', 'p_o_payment_state', 'id', false, null, null);
        $this->addForeignKey('p_o_payment_type_id', 'POPaymentTypeId', 'INTEGER', 'p_o_payment_type', 'id', false, null, null);
        $this->addForeignKey('p_o_subscription_id', 'POSubscriptionId', 'INTEGER', 'p_o_subscription', 'id', false, null, null);
        $this->addColumn('subscription_title', 'SubscriptionTitle', 'VARCHAR', false, 150, null);
        $this->addColumn('subscription_description', 'SubscriptionDescription', 'LONGVARCHAR', false, null, null);
        $this->addColumn('subscription_begin_at', 'SubscriptionBeginAt', 'DATE', false, null, null);
        $this->addColumn('subscription_end_at', 'SubscriptionEndAt', 'DATE', false, null, null);
        $this->addColumn('information', 'Information', 'LONGVARCHAR', false, null, null);
        $this->addColumn('price', 'Price', 'DECIMAL', false, 10, null);
        $this->addColumn('promotion', 'Promotion', 'DECIMAL', false, 10, null);
        $this->addColumn('total', 'Total', 'DECIMAL', false, 10, null);
        $this->addColumn('gender', 'Gender', 'ENUM', false, null, null);
        $this->getColumn('gender', false)->setValueSet(array (
  0 => 'Madame',
  1 => 'Monsieur',
));
        $this->addColumn('name', 'Name', 'VARCHAR', false, 150, null);
        $this->addColumn('firstname', 'Firstname', 'VARCHAR', false, 150, null);
        $this->addColumn('phone', 'Phone', 'VARCHAR', false, 30, null);
        $this->addColumn('email', 'Email', 'VARCHAR', false, 255, null);
        $this->addColumn('invoice_ref', 'InvoiceRef', 'VARCHAR', false, 250, null);
        $this->addColumn('invoice_at', 'InvoiceAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('invoice_filename', 'InvoiceFilename', 'VARCHAR', false, 250, null);
        $this->addColumn('supporting_document', 'SupportingDocument', 'VARCHAR', false, 250, null);
        $this->addColumn('elective_mandates', 'ElectiveMandates', 'LONGVARCHAR', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('PUser', 'Politizr\\Model\\PUser', RelationMap::MANY_TO_ONE, array('p_user_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('POOrderState', 'Politizr\\Model\\POOrderState', RelationMap::MANY_TO_ONE, array('p_o_order_state_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('POPaymentState', 'Politizr\\Model\\POPaymentState', RelationMap::MANY_TO_ONE, array('p_o_payment_state_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('POPaymentType', 'Politizr\\Model\\POPaymentType', RelationMap::MANY_TO_ONE, array('p_o_payment_type_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('POSubscription', 'Politizr\\Model\\POSubscription', RelationMap::MANY_TO_ONE, array('p_o_subscription_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('POEmail', 'Politizr\\Model\\POEmail', RelationMap::ONE_TO_MANY, array('id' => 'p_order_id', ), 'CASCADE', 'CASCADE', 'POEmails');
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
            'uuid' =>  array (
  'name' => 'uuid',
  'version' => '1',
  'permanent' => 'false',
  'required' => 'true',
  'unique' => 'true',
),
            'event' =>  array (
),
            'extend' =>  array (
),
        );
    } // getBehaviors()

} // POrderTableMap
