<?php

namespace Politizr\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'p_u_notification' table.
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
class PUNotificationTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Politizr.Model.map.PUNotificationTableMap';

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
        $this->setName('p_u_notification');
        $this->setPhpName('PUNotification');
        $this->setClassname('Politizr\\Model\\PUNotification');
        $this->setPackage('src.Politizr.Model');
        $this->setUseIdGenerator(true);
        $this->setIsCrossRef(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('uuid', 'Uuid', 'VARCHAR', false, 50, null);
        $this->addForeignKey('p_user_id', 'PUserId', 'INTEGER', 'p_user', 'id', true, null, null);
        $this->addForeignKey('p_notification_id', 'PNotificationId', 'INTEGER', 'p_notification', 'id', true, null, null);
        $this->addColumn('p_object_name', 'PObjectName', 'VARCHAR', false, 150, null);
        $this->addColumn('p_object_id', 'PObjectId', 'INTEGER', false, null, null);
        $this->addColumn('p_author_user_id', 'PAuthorUserId', 'INTEGER', false, null, null);
        $this->addColumn('p_c_topic_id', 'PCTopicId', 'INTEGER', false, null, null);
        $this->addColumn('description', 'Description', 'LONGVARCHAR', false, null, null);
        $this->addColumn('checked', 'Checked', 'BOOLEAN', false, 1, null);
        $this->addColumn('checked_at', 'CheckedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('PUNotificationPUser', 'Politizr\\Model\\PUser', RelationMap::MANY_TO_ONE, array('p_user_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('PUNotificationPNotification', 'Politizr\\Model\\PNotification', RelationMap::MANY_TO_ONE, array('p_notification_id' => 'id', ), 'CASCADE', 'CASCADE');
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

} // PUNotificationTableMap
