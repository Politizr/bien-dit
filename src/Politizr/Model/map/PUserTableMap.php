<?php

namespace Politizr\Model\map;

use \RelationMap;
use \TableMap;


/**
 * This class defines the structure of the 'p_user' table.
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
class PUserTableMap extends TableMap
{

    /**
     * The (dot-path) name of this class
     */
    const CLASS_NAME = 'src.Politizr.Model.map.PUserTableMap';

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
        $this->setName('p_user');
        $this->setPhpName('PUser');
        $this->setClassname('Politizr\\Model\\PUser');
        $this->setPackage('src.Politizr.Model');
        $this->setUseIdGenerator(true);
        // columns
        $this->addPrimaryKey('id', 'Id', 'INTEGER', true, null, null);
        $this->addColumn('provider', 'Provider', 'VARCHAR', false, 255, null);
        $this->addColumn('provider_id', 'ProviderId', 'VARCHAR', false, 255, null);
        $this->addColumn('nickname', 'Nickname', 'VARCHAR', false, 255, null);
        $this->addColumn('realname', 'Realname', 'VARCHAR', false, 255, null);
        $this->addColumn('username', 'Username', 'VARCHAR', false, 255, null);
        $this->addColumn('username_canonical', 'UsernameCanonical', 'VARCHAR', false, 255, null);
        $this->addColumn('email', 'Email', 'VARCHAR', false, 255, null);
        $this->addColumn('email_canonical', 'EmailCanonical', 'VARCHAR', false, 255, null);
        $this->addColumn('enabled', 'Enabled', 'BOOLEAN', false, 1, false);
        $this->addColumn('salt', 'Salt', 'VARCHAR', true, 255, null);
        $this->addColumn('password', 'Password', 'VARCHAR', true, 255, null);
        $this->addColumn('last_login', 'LastLogin', 'TIMESTAMP', false, null, null);
        $this->addColumn('locked', 'Locked', 'BOOLEAN', false, 1, false);
        $this->addColumn('expired', 'Expired', 'BOOLEAN', false, 1, false);
        $this->addColumn('expires_at', 'ExpiresAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('confirmation_token', 'ConfirmationToken', 'VARCHAR', false, 255, null);
        $this->addColumn('password_requested_at', 'PasswordRequestedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('credentials_expired', 'CredentialsExpired', 'BOOLEAN', false, 1, false);
        $this->addColumn('credentials_expire_at', 'CredentialsExpireAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('roles', 'Roles', 'ARRAY', false, null, null);
        $this->addForeignKey('p_u_type_id', 'PUTypeId', 'INTEGER', 'p_u_type', 'id', true, null, null);
        $this->addForeignKey('p_u_status_id', 'PUStatusId', 'INTEGER', 'p_u_status', 'id', true, null, null);
        $this->addColumn('file_name', 'FileName', 'VARCHAR', false, 150, null);
        $this->addColumn('gender', 'Gender', 'ENUM', false, null, null);
        $this->getColumn('gender', false)->setValueSet(array (
  0 => 'Madame',
  1 => 'Monsieur',
));
        $this->addColumn('firstname', 'Firstname', 'VARCHAR', false, 150, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 150, null);
        $this->addColumn('birthday', 'Birthday', 'DATE', false, null, null);
        $this->addColumn('summary', 'Summary', 'LONGVARCHAR', false, null, null);
        $this->addColumn('biography', 'Biography', 'LONGVARCHAR', false, null, null);
        $this->addColumn('website', 'Website', 'VARCHAR', false, 150, null);
        $this->addColumn('twitter', 'Twitter', 'VARCHAR', false, 150, null);
        $this->addColumn('facebook', 'Facebook', 'VARCHAR', false, 150, null);
        $this->addColumn('phone', 'Phone', 'VARCHAR', false, 30, null);
        $this->addColumn('newsletter', 'Newsletter', 'BOOLEAN', false, 1, null);
        $this->addColumn('last_connect', 'LastConnect', 'TIMESTAMP', false, null, null);
        $this->addColumn('online', 'Online', 'BOOLEAN', false, 1, null);
        $this->addColumn('created_at', 'CreatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('updated_at', 'UpdatedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('slug', 'Slug', 'VARCHAR', false, 255, null);
        // validators
    } // initialize()

    /**
     * Build the RelationMap objects for this table relationships
     */
    public function buildRelations()
    {
        $this->addRelation('PUType', 'Politizr\\Model\\PUType', RelationMap::MANY_TO_ONE, array('p_u_type_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('PUStatus', 'Politizr\\Model\\PUStatus', RelationMap::MANY_TO_ONE, array('p_u_status_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('POrder', 'Politizr\\Model\\POrder', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'SET NULL', 'CASCADE', 'POrders');
        $this->addRelation('PUQualification', 'Politizr\\Model\\PUQualification', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', 'CASCADE', 'PUQualifications');
        $this->addRelation('PuFollowDdPUser', 'Politizr\\Model\\PUFollowDD', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', 'CASCADE', 'PuFollowDdPUsers');
        $this->addRelation('PuReputationRbPUser', 'Politizr\\Model\\PUReputationRB', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', 'CASCADE', 'PuReputationRbPUsers');
        $this->addRelation('PuReputationRaPUser', 'Politizr\\Model\\PUReputationRA', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', 'CASCADE', 'PuReputationRaPUsers');
        $this->addRelation('PuTaggedTPUser', 'Politizr\\Model\\PUTaggedT', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', 'CASCADE', 'PuTaggedTPUsers');
        $this->addRelation('PuFollowTPUser', 'Politizr\\Model\\PUFollowT', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', 'CASCADE', 'PuFollowTPUsers');
        $this->addRelation('PDDebate', 'Politizr\\Model\\PDDebate', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'SET NULL', 'CASCADE', 'PDDebates');
        $this->addRelation('PDDComment', 'Politizr\\Model\\PDDComment', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'SET NULL', 'CASCADE', 'PDDComments');
        $this->addRelation('PDReaction', 'Politizr\\Model\\PDReaction', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'SET NULL', 'CASCADE', 'PDReactions');
        $this->addRelation('PDRComment', 'Politizr\\Model\\PDRComment', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'SET NULL', 'CASCADE', 'PDRComments');
        $this->addRelation('PUFollowURelatedByPUserId', 'Politizr\\Model\\PUFollowU', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', null, 'PUFollowUsRelatedByPUserId');
        $this->addRelation('PUFollowURelatedByPUserFollowerId', 'Politizr\\Model\\PUFollowU', RelationMap::ONE_TO_MANY, array('id' => 'p_user_follower_id', ), 'CASCADE', null, 'PUFollowUsRelatedByPUserFollowerId');
        $this->addRelation('PuFollowDdPDDebate', 'Politizr\\Model\\PDDebate', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PuFollowDdPDDebates');
        $this->addRelation('PuReputationRbPRBadge', 'Politizr\\Model\\PRBadge', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PuReputationRbPRBadges');
        $this->addRelation('PuReputationRaPRBadge', 'Politizr\\Model\\PRAction', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PuReputationRaPRBadges');
        $this->addRelation('PuTaggedTPTag', 'Politizr\\Model\\PTag', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PuTaggedTPTags');
        $this->addRelation('PuFollowTPTag', 'Politizr\\Model\\PTag', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PuFollowTPTags');
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
            'online' =>  array (
  'online_column' => 'online',
),
            'timestampable' =>  array (
  'create_column' => 'created_at',
  'update_column' => 'updated_at',
  'disable_updated_at' => 'false',
),
            'sluggable' =>  array (
  'add_cleanup' => 'true',
  'slug_column' => 'slug',
  'slug_pattern' => '',
  'replace_pattern' => '/\\W+/',
  'replacement' => '-',
  'separator' => '-',
  'permanent' => 'false',
  'scope_column' => '',
),
            'equal_nest_parent' =>  array (
  'middle_table' => 'p_u_follow_u',
),
        );
    } // getBehaviors()

} // PUserTableMap
