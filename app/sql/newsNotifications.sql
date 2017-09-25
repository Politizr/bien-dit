# Sélection des élus ville > dep > region > nat
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
    AND p_user.id <> 3
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
    AND p_user.id <> 3
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
    AND p_user.id <> 3
    AND p_l_region.id = 9

ORDER BY unionsorting ASC

) unionsorting



# sélection des sujets ville > dep > thématiques suivies > reg > nat (best activity) 1 max / auteur
SELECT DISTINCT
    id,
    uuid,
    p_user_id,
    p_e_operation_id,
    p_l_city_id,
    p_l_department_id,
    p_l_region_id,
    p_l_country_id,
    fb_ad_id,
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
    homepage,
    moderated,
    moderated_partial,
    moderated_at,
    created_at,
    updated_at,
    slug,
    nb_users
FROM (
( SELECT DISTINCT p_d_debate.*, COUNT(p_u_follow_d_d.p_d_debate_id) as nb_users, 1 as unionsorting
FROM p_d_debate
    LEFT JOIN p_u_follow_d_d
        ON p_d_debate.id = p_u_follow_d_d.p_d_debate_id
WHERE
    p_d_debate.p_l_city_id = 3071
    AND p_d_debate.online = 1
    AND p_d_debate.published = 1
    AND (p_d_debate.p_c_topic_id is NULL OR p_d_debate.p_c_topic_id IN (1,2,3,4,5,6,7,8))
    AND p_d_debate.created_at > '2008-01-01 00:00:00'
    AND p_d_debate.created_at < '2018-01-01 00:00:00'
    AND p_d_debate.id NOT IN (2,30,62,100,109,111,110,132,116,142,144,155,156,159,167,162,173,187,169,188,189,193,201,203,190,238,245,195,198,249,236,257,265,266,284,317,315,321,329,330,332,334,336,376,374,383,377,424,449,453,468,475,458,487,488,499,304,500,575,571,601,583,611,610,616,620,617,612,624,618,636,640,625,647,654,655,489,656,657,660,659,641,662,637,669,626,690,684,709,712,715,721,722,727,734,739,732,680,730,743,758,761,763,783,784,787,781,788,790,791,802,809,827,834,836,842,852,858,848,849,861,855,863,868,883,890,891,892,897,905,904,914,937,176,933,954,962,975,978,979,976,932,1001,1002,1000,992,1004,1037,1046,1044,1013,1057,1058,1056,1050,1061,934,1075,1094,1092,1101,1071,1114,1115,1127,1137,1152,1160,1166,1165,1167,1170,1171,1174,1175,1176,1177,1186,1187,1188,1193,1191,1196,1205,1208)
    AND p_d_debate.p_user_id NOT IN (1,4,5,8,19,21,22,27,28,29,33,36,48,55,60,77,80,92,98,103,104,105,106,109,112,114,115,119,122,129,131,141,146,155,157,160,165,173,175,178,181,185,186,189,192,194,212,216,223,224,225,230,231,234,238,239,247,254,267,270,271,273,277,282,286,296,315,322,323,324,350,353,368,381,389,421,474,478,480,481,486,488,497,515,518,539,646,660,663,664,704,709,715,754,796,829,838,841,865,921,929,931,940,949,978,1019,1028,1029,1038,1057,1094,1131,1168,1172,1192,1206,1239,1266,1314,1457,1480,1484,1502,1511,1515,1563,1620,1623,1640,1660,1794,1809,1826,1841,1851,1893,1915,1940,1941,1958)
    AND p_d_debate.p_user_id <> 3
GROUP BY p_d_debate.id
)

UNION DISTINCT

( SELECT DISTINCT p_d_debate.*, COUNT(p_u_follow_d_d.p_d_debate_id) as nb_users, 2 as unionsorting
FROM p_d_debate
    LEFT JOIN p_u_follow_d_d
        ON p_d_debate.id = p_u_follow_d_d.p_d_debate_id
WHERE
    p_d_debate.p_l_department_id = 9
    AND p_d_debate.online = 1
    AND p_d_debate.published = 1
    AND (p_d_debate.p_c_topic_id is NULL OR p_d_debate.p_c_topic_id IN (1,2,3,4,5,6,7,8))
    AND p_d_debate.created_at > '2008-01-01 00:00:00'
    AND p_d_debate.created_at < '2018-01-01 00:00:00'
    AND p_d_debate.id NOT IN (2,30,62,100,109,111,110,132,116,142,144,155,156,159,167,162,173,187,169,188,189,193,201,203,190,238,245,195,198,249,236,257,265,266,284,317,315,321,329,330,332,334,336,376,374,383,377,424,449,453,468,475,458,487,488,499,304,500,575,571,601,583,611,610,616,620,617,612,624,618,636,640,625,647,654,655,489,656,657,660,659,641,662,637,669,626,690,684,709,712,715,721,722,727,734,739,732,680,730,743,758,761,763,783,784,787,781,788,790,791,802,809,827,834,836,842,852,858,848,849,861,855,863,868,883,890,891,892,897,905,904,914,937,176,933,954,962,975,978,979,976,932,1001,1002,1000,992,1004,1037,1046,1044,1013,1057,1058,1056,1050,1061,934,1075,1094,1092,1101,1071,1114,1115,1127,1137,1152,1160,1166,1165,1167,1170,1171,1174,1175,1176,1177,1186,1187,1188,1193,1191,1196,1205,1208)
    AND p_d_debate.p_user_id NOT IN (1,4,5,8,19,21,22,27,28,29,33,36,48,55,60,77,80,92,98,103,104,105,106,109,112,114,115,119,122,129,131,141,146,155,157,160,165,173,175,178,181,185,186,189,192,194,212,216,223,224,225,230,231,234,238,239,247,254,267,270,271,273,277,282,286,296,315,322,323,324,350,353,368,381,389,421,474,478,480,481,486,488,497,515,518,539,646,660,663,664,704,709,715,754,796,829,838,841,865,921,929,931,940,949,978,1019,1028,1029,1038,1057,1094,1131,1168,1172,1192,1206,1239,1266,1314,1457,1480,1484,1502,1511,1515,1563,1620,1623,1640,1660,1794,1809,1826,1841,1851,1893,1915,1940,1941,1958)
    AND p_d_debate.p_user_id <> 3
GROUP BY p_d_debate.id
)

UNION DISTINCT

( SELECT DISTINCT p_d_debate.*, COUNT(p_u_follow_d_d.p_d_debate_id) as nb_users, 3 as unionsorting
FROM p_d_debate
    LEFT JOIN p_u_follow_d_d
        ON p_d_debate.id = p_u_follow_d_d.p_d_debate_id
    LEFT JOIN p_d_d_tagged_t
        ON p_d_debate.id = p_d_d_tagged_t.p_d_debate_id
WHERE
    p_d_debate.online = 1 
    AND p_d_debate.published = 1
    AND (p_d_debate.p_c_topic_id is NULL OR p_d_debate.p_c_topic_id IN (1,2,3,4,5,6,7,8))
    AND p_d_debate.created_at > '2008-01-01 00:00:00'
    AND p_d_debate.created_at < '2018-01-01 00:00:00'
    AND p_d_d_tagged_t.p_tag_id IN (360,366,830)
    AND p_d_debate.id NOT IN (2,30,62,100,109,111,110,132,116,142,144,155,156,159,167,162,173,187,169,188,189,193,201,203,190,238,245,195,198,249,236,257,265,266,284,317,315,321,329,330,332,334,336,376,374,383,377,424,449,453,468,475,458,487,488,499,304,500,575,571,601,583,611,610,616,620,617,612,624,618,636,640,625,647,654,655,489,656,657,660,659,641,662,637,669,626,690,684,709,712,715,721,722,727,734,739,732,680,730,743,758,761,763,783,784,787,781,788,790,791,802,809,827,834,836,842,852,858,848,849,861,855,863,868,883,890,891,892,897,905,904,914,937,176,933,954,962,975,978,979,976,932,1001,1002,1000,992,1004,1037,1046,1044,1013,1057,1058,1056,1050,1061,934,1075,1094,1092,1101,1071,1114,1115,1127,1137,1152,1160,1166,1165,1167,1170,1171,1174,1175,1176,1177,1186,1187,1188,1193,1191,1196,1205,1208)
    AND p_d_debate.p_user_id NOT IN (1,4,5,8,19,21,22,27,28,29,33,36,48,55,60,77,80,92,98,103,104,105,106,109,112,114,115,119,122,129,131,141,146,155,157,160,165,173,175,178,181,185,186,189,192,194,212,216,223,224,225,230,231,234,238,239,247,254,267,270,271,273,277,282,286,296,315,322,323,324,350,353,368,381,389,421,474,478,480,481,486,488,497,515,518,539,646,660,663,664,704,709,715,754,796,829,838,841,865,921,929,931,940,949,978,1019,1028,1029,1038,1057,1094,1131,1168,1172,1192,1206,1239,1266,1314,1457,1480,1484,1502,1511,1515,1563,1620,1623,1640,1660,1794,1809,1826,1841,1851,1893,1915,1940,1941,1958)
    AND p_d_debate.p_user_id <> 3
GROUP BY p_d_debate.id
)

UNION DISTINCT

( SELECT DISTINCT p_d_debate.*, COUNT(p_u_follow_d_d.p_d_debate_id) as nb_users, 4 as unionsorting
FROM p_d_debate
    LEFT JOIN p_u_follow_d_d
        ON p_d_debate.id = p_u_follow_d_d.p_d_debate_id
WHERE
    p_d_debate.p_l_region_id = 9
    AND p_d_debate.online = 1
    AND p_d_debate.published = 1
    AND (p_d_debate.p_c_topic_id is NULL OR p_d_debate.p_c_topic_id IN (1,2,3,4,5,6,7,8))
    AND p_d_debate.created_at > '2008-01-01 00:00:00'
    AND p_d_debate.created_at < '2018-01-01 00:00:00'
    AND p_d_debate.id NOT IN (2,30,62,100,109,111,110,132,116,142,144,155,156,159,167,162,173,187,169,188,189,193,201,203,190,238,245,195,198,249,236,257,265,266,284,317,315,321,329,330,332,334,336,376,374,383,377,424,449,453,468,475,458,487,488,499,304,500,575,571,601,583,611,610,616,620,617,612,624,618,636,640,625,647,654,655,489,656,657,660,659,641,662,637,669,626,690,684,709,712,715,721,722,727,734,739,732,680,730,743,758,761,763,783,784,787,781,788,790,791,802,809,827,834,836,842,852,858,848,849,861,855,863,868,883,890,891,892,897,905,904,914,937,176,933,954,962,975,978,979,976,932,1001,1002,1000,992,1004,1037,1046,1044,1013,1057,1058,1056,1050,1061,934,1075,1094,1092,1101,1071,1114,1115,1127,1137,1152,1160,1166,1165,1167,1170,1171,1174,1175,1176,1177,1186,1187,1188,1193,1191,1196,1205,1208)
    AND p_d_debate.p_user_id NOT IN (1,4,5,8,19,21,22,27,28,29,33,36,48,55,60,77,80,92,98,103,104,105,106,109,112,114,115,119,122,129,131,141,146,155,157,160,165,173,175,178,181,185,186,189,192,194,212,216,223,224,225,230,231,234,238,239,247,254,267,270,271,273,277,282,286,296,315,322,323,324,350,353,368,381,389,421,474,478,480,481,486,488,497,515,518,539,646,660,663,664,704,709,715,754,796,829,838,841,865,921,929,931,940,949,978,1019,1028,1029,1038,1057,1094,1131,1168,1172,1192,1206,1239,1266,1314,1457,1480,1484,1502,1511,1515,1563,1620,1623,1640,1660,1794,1809,1826,1841,1851,1893,1915,1940,1941,1958)
    AND p_d_debate.p_user_id <> 3
GROUP BY p_d_debate.id
)

UNION DISTINCT

( SELECT DISTINCT p_d_debate.*, COUNT(p_u_follow_d_d.p_d_debate_id) as nb_users, 5 as unionsorting
FROM p_d_debate
    LEFT JOIN p_u_follow_d_d
        ON p_d_debate.id = p_u_follow_d_d.p_d_debate_id
WHERE
    p_d_debate.online = 1
    AND p_d_debate.published = 1
    AND (p_d_debate.p_c_topic_id is NULL OR p_d_debate.p_c_topic_id IN (1,2,3,4,5,6,7,8))
    AND p_d_debate.created_at > '2008-01-01 00:00:00'
    AND p_d_debate.created_at < '2018-01-01 00:00:00'
    AND p_d_debate.id NOT IN (2,30,62,100,109,111,110,132,116,142,144,155,156,159,167,162,173,187,169,188,189,193,201,203,190,238,245,195,198,249,236,257,265,266,284,317,315,321,329,330,332,334,336,376,374,383,377,424,449,453,468,475,458,487,488,499,304,500,575,571,601,583,611,610,616,620,617,612,624,618,636,640,625,647,654,655,489,656,657,660,659,641,662,637,669,626,690,684,709,712,715,721,722,727,734,739,732,680,730,743,758,761,763,783,784,787,781,788,790,791,802,809,827,834,836,842,852,858,848,849,861,855,863,868,883,890,891,892,897,905,904,914,937,176,933,954,962,975,978,979,976,932,1001,1002,1000,992,1004,1037,1046,1044,1013,1057,1058,1056,1050,1061,934,1075,1094,1092,1101,1071,1114,1115,1127,1137,1152,1160,1166,1165,1167,1170,1171,1174,1175,1176,1177,1186,1187,1188,1193,1191,1196,1205,1208)
    AND p_d_debate.p_user_id NOT IN (1,4,5,8,19,21,22,27,28,29,33,36,48,55,60,77,80,92,98,103,104,105,106,109,112,114,115,119,122,129,131,141,146,155,157,160,165,173,175,178,181,185,186,189,192,194,212,216,223,224,225,230,231,234,238,239,247,254,267,270,271,273,277,282,286,296,315,322,323,324,350,353,368,381,389,421,474,478,480,481,486,488,497,515,518,539,646,660,663,664,704,709,715,754,796,829,838,841,865,921,929,931,940,949,978,1019,1028,1029,1038,1057,1094,1131,1168,1172,1192,1206,1239,1266,1314,1457,1480,1484,1502,1511,1515,1563,1620,1623,1640,1660,1794,1809,1826,1841,1851,1893,1915,1940,1941,1958)
    AND p_d_debate.p_user_id <> 3
GROUP BY p_d_debate.id
HAVING nb_users >= 5
)

ORDER BY unionsorting ASC, note_pos DESC, note_neg ASC, nb_users DESC, published_at DESC
) unionsorting

GROUP BY p_user_id
ORDER BY unionsorting ASC, note_pos DESC, note_neg ASC, nb_users DESC, published_at DESC
