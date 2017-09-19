# Insertion dans p_c_group_l_c de toutes les villes d'ariège

INSERT INTO `p_c_group_l_c` (`p_circle_id`, `p_l_city_id`, `created_at`, `updated_at`)
SELECT 1, `id`, NOW(), NOW() FROM `p_l_city` WHERE `p_l_department_id` = 9


# Force geoloc topic
UPDATE `p_c_topic` SET `force_geoloc_type`='department', `force_geoloc_id`=9;