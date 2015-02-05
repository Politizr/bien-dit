<?php

namespace Politizr\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'p_notification' table.
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
class PNotificationTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Politizr.Model.map.PNotificationTableMap';

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
        $this->setName('p_notification');
        $this->setPhpName('PNotification');
        $this->setClassname('Politizr\\Model\\PNotification');
        $this->setPackage('src.Politizr.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('title', 'Title', 'VARCHAR', false, 250, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('online', 'Online', 'BOOLEAN', false, 1, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('PUNotificationsPNotification', 'Politizr\\Model\\PUNotifications', RelationMap::ONE_TO_MANY, array('id' => 'p_notification_id', ), 'CASCADE', 'CASCADE', 'PUNotificationsPNotifications');
        $this->addRelation('PUSubscribeEmailPNotification', 'Politizr\\Model\\PUSubscribeEmail', RelationMap::ONE_TO_MANY, array('id' => 'p_notification_id', ), 'CASCADE', 'CASCADE', 'PUSubscribeEmailPNotifications');
        $this->addRelation('PUSubscribeScreenPNotification', 'Politizr\\Model\\PUSubscribeScreen', RelationMap::ONE_TO_MANY, array('id' => 'p_notification_id', ), 'CASCADE', 'CASCADE', 'PUSubscribeScreenPNotifications');
        $this->addRelation('PUNotificationsPUser', 'Politizr\\Model\\PUser', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PUNotificationsPUsers');
        $this->addRelation('PUSubscribeEmailPUser', 'Politizr\\Model\\PUser', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PUSubscribeEmailPUsers');
        $this->addRelation('PUSubscribeScreenPUser', 'Politizr\\Model\\PUser', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PUSubscribeScreenPUsers');
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

} // PNotificationTableMap
