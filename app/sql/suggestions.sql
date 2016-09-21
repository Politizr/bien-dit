#########################
# Suggestions de débats	#
#########################
#  Concordance des tags suivis / tags caractérisant des débats
SELECT DISTINCT
    id,
    uuid,
    p_user_id,
    p_l_city_id,
    p_l_department_id,
    p_l_region_id,
    p_l_country_id,
    title,
    file_name,
    copyright,
    description,
    note_pos,
    note_neg,
    nb_views,
    published,
    published_at,
    published_by,
    favorite,
    online,
    moderated,
    moderated_partial,
    moderated_at,
    created_at,
    updated_at,
    slug,
    nb_users
FROM (

#  Débats ville = user
( SELECT DISTINCT p_d_debate.*, 0 as nb_users, 1 as unionsorting
FROM p_d_debate
WHERE
    p_d_debate.p_l_city_id = 11719
    AND p_d_debate.online = 1
    AND p_d_debate.published = 1
    AND p_d_debate.id NOT IN (1,5)
    AND p_d_debate.p_user_id NOT IN (6,9,60)
    AND p_d_debate.p_user_id <> 73
)

UNION DISTINCT

#  Débats département = user
( SELECT DISTINCT p_d_debate.*, 0 as nb_users, 2 as unionsorting
FROM p_d_debate
WHERE
    p_d_debate.p_l_department_id = 9
    AND p_d_debate.online = 1
    AND p_d_debate.published = 1
    AND p_d_debate.id NOT IN (1,5)
    AND p_d_debate.p_user_id NOT IN (6,9,60)
    AND p_d_debate.p_user_id <> 73
)

UNION DISTINCT

#  Débats région = user
( SELECT DISTINCT p_d_debate.*, 0 as nb_users, 3 as unionsorting
FROM p_d_debate
WHERE
    p_d_debate.p_l_region_id = 9
    AND p_d_debate.online = 1
    AND p_d_debate.published = 1
    AND p_d_debate.id NOT IN (1,5)
    AND p_d_debate.p_user_id NOT IN (6,9,60)
    AND p_d_debate.p_user_id <> 73
)

UNION DISTINCT

#  Débats les plus populaires
( SELECT DISTINCT p_d_debate.*, COUNT(p_u_follow_d_d.p_d_debate_id) as nb_users, 4 as unionsorting
FROM p_d_debate
    LEFT JOIN p_u_follow_d_d
        ON p_d_debate.id = p_u_follow_d_d.p_d_debate_id
WHERE
    p_d_debate.online = 1
    AND p_d_debate.published = 1
    AND p_d_debate.id NOT IN (1,5)
    AND p_d_debate.p_user_id NOT IN (6,9,60)
    AND p_d_debate.p_user_id <> 73
GROUP BY p_d_debate.id
ORDER BY nb_users DESC
)

ORDER BY unionsorting ASC, nb_users DESC, published_at DESC
) unionsorting

LIMIT 0, 10


#########################
# Suggestions de users	#
#########################
#  Concordance des tags suivis / tags caractérisant des users
SELECT DISTINCT
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
    qualified,
    validated,
    online,
    banned,
    banned_nb_days_left,
    banned_nb_total,
    abuse_level,
    created_at,
    updated_at,
    slug
FROM (
( SELECT p_user.*, COUNT(p_user.id) as nb_users, 1 as unionsorting
FROM p_user
    LEFT JOIN p_u_tagged_t
        ON p_user.id = p_u_tagged_t.p_user_id
WHERE
	p_u_tagged_t.p_tag_id IN (
                SELECT p_tag.id
                FROM p_tag
                    LEFT JOIN p_u_tagged_t
                        ON p_tag.id = p_u_tagged_t.p_tag_id
                WHERE
                    p_tag.online = true
                    AND p_u_tagged_t.p_user_id = 73
	)
    AND p_user.online = 1
    AND p_user.id NOT IN (SELECT p_user_id FROM p_u_follow_u WHERE p_user_id = 73)
    AND p_user.id <> 73
)

UNION DISTINCT

#  Users les plus populaires
( SELECT p_user.*, COUNT(p_u_follow_u.p_user_id) as nb_users, 2 as unionsorting
FROM p_user
    LEFT JOIN p_u_follow_u
        ON p_user.id = p_u_follow_u.p_user_id
WHERE
    p_user.online = 1
    AND p_user.id NOT IN (SELECT p_user_id FROM p_u_follow_u WHERE p_user_id = 73)
    AND p_user.id <> 73
GROUP BY p_user.id
ORDER BY nb_users DESC
)

ORDER BY unionsorting ASC
) unionsorting

LIMIT 0, 10

