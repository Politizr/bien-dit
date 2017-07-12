
# Sélection des élus même ville > dep > region
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
    qualified,
    validated,
    nb_id_check,
    online,
    homepage,
    banned,
    banned_nb_days_left,
    banned_nb_total,
    abuse_level,
    created_at,
    updated_at,
    slug
FROM ( 

SELECT DISTINCT p_user.*, 1 as unionsorting
FROM p_user
WHERE
    p_user.qualified = 1
    AND p_user.online = 1
    AND p_user.p_u_status_id = 1
    AND p_user.created_at > '2008-01-01 00:00:00'
    AND p_user.created_at < '2018-01-01 00:00:00'
    AND p_user.p_l_city_id = 3071

UNION DISTINCT

SELECT DISTINCT p_user.*, 2 as unionsorting
FROM p_user
    LEFT JOIN p_l_city as p_l_city
        ON p_user.p_l_city_id = p_l_city.id
    LEFT JOIN p_l_department as p_l_department
        ON p_l_city.p_l_department_id = p_l_department.id
WHERE
    p_user.qualified = 1
    AND p_user.online = 1
    AND p_user.p_u_status_id = 1
    AND p_user.created_at > '2008-01-01 00:00:00'
    AND p_user.created_at < '2018-01-01 00:00:00'
    AND p_l_department.id = 9


UNION DISTINCT

SELECT DISTINCT p_user.*, 3 as unionsorting
FROM p_user
    LEFT JOIN p_l_city as p_l_city
        ON p_user.p_l_city_id = p_l_city.id
    LEFT JOIN p_l_department as p_l_department
        ON p_l_city.p_l_department_id = p_l_department.id
    LEFT JOIN p_l_region as p_l_region
        ON p_l_department.p_l_region_id = p_l_region.id
WHERE
    p_user.qualified = 1
    AND p_user.online = 1
    AND p_user.p_u_status_id = 1
    AND p_user.created_at > '2008-01-01 00:00:00'
    AND p_user.created_at < '2018-01-01 00:00:00'
    AND p_l_region.id = 9


ORDER BY unionsorting ASC

) unionsorting

