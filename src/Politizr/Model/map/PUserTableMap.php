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
        $this->addColumn('uuid', 'Uuid', 'VARCHAR', false, 50, null);
        $this->addForeignKey('p_u_status_id', 'PUStatusId', 'INTEGER', 'p_u_status', 'id', true, null, null);
        $this->addForeignKey('p_l_city_id', 'PLCityId', 'INTEGER', 'p_l_city', 'id', false, null, null);
        $this->addColumn('provider', 'Provider', 'VARCHAR', false, 255, null);
        $this->addColumn('provider_id', 'ProviderId', 'VARCHAR', false, 255, null);
        $this->addColumn('nickname', 'Nickname', 'VARCHAR', false, 255, null);
        $this->addColumn('realname', 'Realname', 'VARCHAR', false, 255, null);
        $this->addColumn('username', 'Username', 'VARCHAR', false, 255, null);
        $this->addColumn('username_canonical', 'UsernameCanonical', 'VARCHAR', false, 255, null);
        $this->addColumn('email', 'Email', 'VARCHAR', false, 255, null);
        $this->addColumn('email_canonical', 'EmailCanonical', 'VARCHAR', false, 255, null);
        $this->addColumn('enabled', 'Enabled', 'BOOLEAN', false, 1, false);
        $this->addColumn('salt', 'Salt', 'VARCHAR', false, 255, null);
        $this->addColumn('password', 'Password', 'VARCHAR', false, 255, null);
        $this->addColumn('last_login', 'LastLogin', 'TIMESTAMP', false, null, null);
        $this->addColumn('locked', 'Locked', 'BOOLEAN', false, 1, false);
        $this->addColumn('expired', 'Expired', 'BOOLEAN', false, 1, false);
        $this->addColumn('expires_at', 'ExpiresAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('confirmation_token', 'ConfirmationToken', 'VARCHAR', false, 255, null);
        $this->addColumn('password_requested_at', 'PasswordRequestedAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('credentials_expired', 'CredentialsExpired', 'BOOLEAN', false, 1, false);
        $this->addColumn('credentials_expire_at', 'CredentialsExpireAt', 'TIMESTAMP', false, null, null);
        $this->addColumn('roles', 'Roles', 'ARRAY', false, null, null);
        $this->addColumn('last_activity', 'LastActivity', 'TIMESTAMP', false, null, null);
        $this->addColumn('file_name', 'FileName', 'VARCHAR', false, 150, null);
        $this->addColumn('back_file_name', 'BackFileName', 'VARCHAR', false, 150, null);
        $this->addColumn('copyright', 'Copyright', 'LONGVARCHAR', false, null, null);
        $this->addColumn('gender', 'Gender', 'ENUM', false, null, null);
        $this->getColumn('gender', false)->setValueSet(array (
  0 => 'Madame',
  1 => 'Monsieur',
));
        $this->addColumn('firstname', 'Firstname', 'VARCHAR', false, 150, null);
        $this->addColumn('name', 'Name', 'VARCHAR', false, 150, null);
        $this->addColumn('birthday', 'Birthday', 'DATE', false, null, null);
        $this->addColumn('subtitle', 'Subtitle', 'LONGVARCHAR', false, null, null);
        $this->addColumn('biography', 'Biography', 'LONGVARCHAR', false, null, null);
        $this->addColumn('website', 'Website', 'VARCHAR', false, 150, null);
        $this->addColumn('twitter', 'Twitter', 'VARCHAR', false, 150, null);
        $this->addColumn('facebook', 'Facebook', 'VARCHAR', false, 150, null);
        $this->addColumn('phone', 'Phone', 'VARCHAR', false, 30, null);
        $this->addColumn('newsletter', 'Newsletter', 'BOOLEAN', false, 1, null);
        $this->addColumn('last_connect', 'LastConnect', 'TIMESTAMP', false, null, null);
        $this->addColumn('nb_connected_days', 'NbConnectedDays', 'INTEGER', false, null, 0);
        $this->addColumn('nb_views', 'NbViews', 'INTEGER', false, null, null);
        $this->addColumn('qualified', 'Qualified', 'BOOLEAN', false, 1, null);
        $this->addColumn('validated', 'Validated', 'BOOLEAN', false, 1, false);
        $this->addColumn('nb_id_check', 'NbIdCheck', 'INTEGER', false, null, null);
        $this->addColumn('online', 'Online', 'BOOLEAN', false, 1, null);
        $this->addColumn('homepage', 'Homepage', 'BOOLEAN', false, 1, null);
        $this->addColumn('banned', 'Banned', 'BOOLEAN', false, 1, null);
        $this->addColumn('banned_nb_days_left', 'BannedNbDaysLeft', 'INTEGER', false, null, null);
        $this->addColumn('banned_nb_total', 'BannedNbTotal', 'INTEGER', false, null, null);
        $this->addColumn('abuse_level', 'AbuseLevel', 'INTEGER', false, null, null);
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
        $this->addRelation('PUStatus', 'Politizr\\Model\\PUStatus', RelationMap::MANY_TO_ONE, array('p_u_status_id' => 'id', ), 'CASCADE', 'CASCADE');
        $this->addRelation('PLCity', 'Politizr\\Model\\PLCity', RelationMap::MANY_TO_ONE, array('p_l_city_id' => 'id', ), 'SET NULL', 'CASCADE');
        $this->addRelation('PUser', 'Politizr\\Model\\PTag', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'SET NULL', 'CASCADE', 'PUsers');
        $this->addRelation('POwner', 'Politizr\\Model\\PTag', RelationMap::ONE_TO_MANY, array('id' => 'p_owner_id', ), 'SET NULL', 'CASCADE', 'POwners');
        $this->addRelation('POrder', 'Politizr\\Model\\POrder', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'SET NULL', 'CASCADE', 'POrders');
        $this->addRelation('PuFollowDdPUser', 'Politizr\\Model\\PUFollowDD', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', 'CASCADE', 'PuFollowDdPUsers');
        $this->addRelation('PuBookmarkDdPUser', 'Politizr\\Model\\PUBookmarkDD', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', 'CASCADE', 'PuBookmarkDdPUsers');
        $this->addRelation('PuBookmarkDrPUser', 'Politizr\\Model\\PUBookmarkDR', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', 'CASCADE', 'PuBookmarkDrPUsers');
        $this->addRelation('PuTrackDdPUser', 'Politizr\\Model\\PUTrackDD', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', 'CASCADE', 'PuTrackDdPUsers');
        $this->addRelation('PuTrackDrPUser', 'Politizr\\Model\\PUTrackDR', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', 'CASCADE', 'PuTrackDrPUsers');
        $this->addRelation('PUBadge', 'Politizr\\Model\\PUBadge', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', 'CASCADE', 'PUBadges');
        $this->addRelation('PUReputation', 'Politizr\\Model\\PUReputation', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', 'CASCADE', 'PUReputations');
        $this->addRelation('PuTaggedTPUser', 'Politizr\\Model\\PUTaggedT', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', 'CASCADE', 'PuTaggedTPUsers');
        $this->addRelation('PURoleQ', 'Politizr\\Model\\PURoleQ', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', 'CASCADE', 'PURoleQs');
        $this->addRelation('PUMandate', 'Politizr\\Model\\PUMandate', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', 'CASCADE', 'PUMandates');
        $this->addRelation('PUAffinityQOPUser', 'Politizr\\Model\\PUAffinityQO', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', 'CASCADE', 'PUAffinityQOPUsers');
        $this->addRelation('PUCurrentQOPUser', 'Politizr\\Model\\PUCurrentQO', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', 'CASCADE', 'PUCurrentQOPUsers');
        $this->addRelation('PUNotificationPUser', 'Politizr\\Model\\PUNotification', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', 'CASCADE', 'PUNotificationPUsers');
        $this->addRelation('PUSubscribeEmailPUser', 'Politizr\\Model\\PUSubscribeEmail', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', 'CASCADE', 'PUSubscribeEmailPUsers');
        $this->addRelation('PUSubscribeScreenPUser', 'Politizr\\Model\\PUSubscribeScreen', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', 'CASCADE', 'PUSubscribeScreenPUsers');
        $this->addRelation('PDDebate', 'Politizr\\Model\\PDDebate', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'SET NULL', 'CASCADE', 'PDDebates');
        $this->addRelation('PDReaction', 'Politizr\\Model\\PDReaction', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'SET NULL', 'CASCADE', 'PDReactions');
        $this->addRelation('PDDComment', 'Politizr\\Model\\PDDComment', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'SET NULL', 'CASCADE', 'PDDComments');
        $this->addRelation('PDRComment', 'Politizr\\Model\\PDRComment', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'SET NULL', 'CASCADE', 'PDRComments');
        $this->addRelation('PMUserModerated', 'Politizr\\Model\\PMUserModerated', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', 'CASCADE', 'PMUserModerateds');
        $this->addRelation('PMUserMessage', 'Politizr\\Model\\PMUserMessage', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'SET NULL', 'CASCADE', 'PMUserMessages');
        $this->addRelation('PMUserHistoric', 'Politizr\\Model\\PMUserHistoric', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'SET NULL', 'CASCADE', 'PMUserHistorics');
        $this->addRelation('PMDebateHistoric', 'Politizr\\Model\\PMDebateHistoric', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'SET NULL', 'CASCADE', 'PMDebateHistorics');
        $this->addRelation('PMReactionHistoric', 'Politizr\\Model\\PMReactionHistoric', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'SET NULL', 'CASCADE', 'PMReactionHistorics');
        $this->addRelation('PMDCommentHistoric', 'Politizr\\Model\\PMDCommentHistoric', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'SET NULL', 'CASCADE', 'PMDCommentHistorics');
        $this->addRelation('PMRCommentHistoric', 'Politizr\\Model\\PMRCommentHistoric', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'SET NULL', 'CASCADE', 'PMRCommentHistorics');
        $this->addRelation('PMAskForUpdate', 'Politizr\\Model\\PMAskForUpdate', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'SET NULL', 'CASCADE', 'PMAskForUpdates');
        $this->addRelation('PMAbuseReporting', 'Politizr\\Model\\PMAbuseReporting', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'SET NULL', 'CASCADE', 'PMAbuseReportings');
        $this->addRelation('PMAppException', 'Politizr\\Model\\PMAppException', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'SET NULL', 'CASCADE', 'PMAppExceptions');
        $this->addRelation('PUFollowURelatedByPUserId', 'Politizr\\Model\\PUFollowU', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id', ), 'CASCADE', null, 'PUFollowUsRelatedByPUserId');
        $this->addRelation('PUFollowURelatedByPUserFollowerId', 'Politizr\\Model\\PUFollowU', RelationMap::ONE_TO_MANY, array('id' => 'p_user_follower_id', ), 'CASCADE', null, 'PUFollowUsRelatedByPUserFollowerId');
        $this->addRelation('PUTrackURelatedByPUserIdSource', 'Politizr\\Model\\PUTrackU', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id_source', ), 'CASCADE', null, 'PUTrackUsRelatedByPUserIdSource');
        $this->addRelation('PUTrackURelatedByPUserIdDest', 'Politizr\\Model\\PUTrackU', RelationMap::ONE_TO_MANY, array('id' => 'p_user_id_dest', ), 'CASCADE', null, 'PUTrackUsRelatedByPUserIdDest');
        $this->addRelation('PuFollowDdPDDebate', 'Politizr\\Model\\PDDebate', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PuFollowDdPDDebates');
        $this->addRelation('PuBookmarkDdPDDebate', 'Politizr\\Model\\PDDebate', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PuBookmarkDdPDDebates');
        $this->addRelation('PuBookmarkDrPDReaction', 'Politizr\\Model\\PDReaction', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PuBookmarkDrPDReactions');
        $this->addRelation('PuTrackDdPDDebate', 'Politizr\\Model\\PDDebate', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PuTrackDdPDDebates');
        $this->addRelation('PuTrackDrPDReaction', 'Politizr\\Model\\PDReaction', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PuTrackDrPDReactions');
        $this->addRelation('PRBadge', 'Politizr\\Model\\PRBadge', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PRBadges');
        $this->addRelation('PRAction', 'Politizr\\Model\\PRAction', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PRActions');
        $this->addRelation('PuTaggedTPTag', 'Politizr\\Model\\PTag', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PuTaggedTPTags');
        $this->addRelation('PQualification', 'Politizr\\Model\\PQualification', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PQualifications');
        $this->addRelation('PUAffinityQOPQOrganization', 'Politizr\\Model\\PQOrganization', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PUAffinityQOPQOrganizations');
        $this->addRelation('PUCurrentQOPQOrganization', 'Politizr\\Model\\PQOrganization', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PUCurrentQOPQOrganizations');
        $this->addRelation('PUNotificationPNotification', 'Politizr\\Model\\PNotification', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PUNotificationPNotifications');
        $this->addRelation('PUSubscribeEmailPNotification', 'Politizr\\Model\\PNotification', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PUSubscribeEmailPNotifications');
        $this->addRelation('PUSubscribeScreenPNotification', 'Politizr\\Model\\PNotification', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PUSubscribeScreenPNotifications');
        $this->addRelation('PMModerationType', 'Politizr\\Model\\PMModerationType', RelationMap::MANY_TO_MANY, array(), 'CASCADE', 'CASCADE', 'PMModerationTypes');
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
            'uuid' =>  array (
  'name' => 'uuid',
  'version' => '4',
  'permanent' => 'false',
  'required' => 'true',
  'unique' => 'true',
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
            'equal_nest_parent' =>  array (
  'middle_table' => 'p_u_follow_u',
),
        );
    } // getBehaviors()

} // PUserTableMap
