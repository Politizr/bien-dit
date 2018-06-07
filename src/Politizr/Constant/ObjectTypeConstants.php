<?php

namespace Politizr\Constant;

/**
 * Object type constants
 *
 * @author Lionel Bouzonville
 */
class ObjectTypeConstants
{
    const TYPE_DEBATE = 'Politizr\Model\PDDebate';
    const TYPE_REACTION = 'Politizr\Model\PDReaction';
    const TYPE_DEBATE_COMMENT = 'Politizr\Model\PDDComment';
    const TYPE_REACTION_COMMENT = 'Politizr\Model\PDRComment';
    const TYPE_USER = 'Politizr\Model\PUser';
    const TYPE_TAG = 'Politizr\Model\PTag';
    const TYPE_LOCALIZATION = 'Politizr\FrontBundle\Lib\PLocalization';
    const TYPE_LOCALIZATION_COUNTRY = 'Politizr\Model\PLCountry';
    const TYPE_LOCALIZATION_REGION = 'Politizr\Model\PLRegion';
    const TYPE_LOCALIZATION_DEPARTMENT = 'Politizr\Model\PLDepartment';
    const TYPE_LOCALIZATION_CITY = 'Politizr\Model\PLCity';
    const TYPE_BADGE = 'Politizr\Model\PRBadge';
    const TYPE_ABUSE = 'Politizr\Model\PMAbuseReporting';
    const TYPE_ASK_FOR_UPDATE = 'Politizr\Model\PMAskForUpdate';
    const TYPE_ACTION = 'Politizr\Model\PRAction';
    const TYPE_CIRCLE = 'Politizr\Model\PCircle';
    const TYPE_CIRCLE_OWNER = 'Politizr\Model\PCOwner';
    const TYPE_TOPIC = 'Politizr\Model\PCTopic';
    const TYPE_OPERATION = 'Politizr\Model\PEOperation';
    const TYPE_MEDIA = 'Politizr\Model\PDMedia';

    const CONTEXT_DEBATE = 'debate';
    const CONTEXT_REACTION = 'reaction';
    const CONTEXT_COMMENT = 'comment';
    const CONTEXT_USER = 'user';
    const CONTEXT_PUBLICATION = 'publication';

    // SQL columns used in raw SQL requests > to update if DB structure is updated
    const SQL_P_USER_COLUMNS = "
    id,
    uuid,
    provider,
    provider_id,
    nickname,
    realname,
    username,
    username_canonical,
    email,
    email_canonical,
    enabled,
    salt,
    password,
    last_login,
    locked,
    expired,
    expires_at,
    confirmation_token,
    password_requested_at,
    credentials_expired,
    credentials_expire_at,
    roles,
    last_activity,
    p_u_status_id,
    p_l_city_id,
    file_name,
    back_file_name,
    copyright,
    gender,
    firstname,
    name,
    birthday,
    subtitle,
    biography,
    website,
    twitter,
    facebook,
    phone,
    newsletter,
    last_connect,
    nb_connected_days,
    nb_views,
    organization,
    qualified,
    validated,
    nb_id_check,
    online,
    homepage,
    support_group,
    banned,
    banned_nb_days_left,
    banned_nb_total,
    abuse_level,
    created_at,
    updated_at,
    indexed_at,
    slug
";

    const SQL_P_D_DEBATE_COLUMNS = "
    id,
    uuid,
    p_user_id,
    p_e_operation_id,
    p_l_city_id,
    p_l_department_id,
    p_l_region_id,
    p_l_country_id,
    p_c_topic_id,
    fb_ad_id,
    title,
    file_name,
    copyright,
    description,
    note_pos,
    note_neg,
    nb_views,
    want_boost,
    published,
    published_at,
    published_by,
    favorite,
    online,
    homepage,
    moderated,
    moderated_partial,
    moderated_at,
    created_at,
    updated_at,
    indexed_at,
    slug
";
}
