# Insertion dans p_c_group_l_c de toutes les villes d'ariège
INSERT INTO `p_c_group_l_c` (`p_circle_id`, `p_l_city_id`, `created_at`, `updated_at`)
SELECT 1, `id`, NOW(), NOW() FROM `p_l_city` WHERE `p_l_department_id` = 9


# Force geoloc topic
UPDATE `p_c_topic` SET `force_geoloc_type`='department', `force_geoloc_id`=9;

# Inscription de tous les ariégeois
INSERT INTO `p_u_in_p_c` (`p_circle_id`, `p_user_id`, `created_at`, `updated_at`)
SELECT 1, `id`, NOW(), NOW()
FROM `p_user`
WHERE `p_l_city_id` IN (
    SELECT `id` FROM `p_l_city` WHERE `p_l_department_id` = 9
)

# MAJ des droits
UPDATE `p_user` SET `roles` = REPLACE(`roles`, ' ROLE_CIRCLE_1 |', '');

UPDATE `p_user` SET `roles` = CONCAT(`roles`, ' ROLE_CIRCLE_1 |'), `updated_at` = NOW() 
WHERE `p_l_city_id` IN (
    SELECT `id` FROM `p_l_city` WHERE `p_l_department_id` = 9
)

# MAJ des users ayant l'autorisation de répondre
UPDATE `p_u_in_p_c` SET `is_authorized_reaction` = 1 WHERE `p_user_id` = 3;
