# Insertion dans p_c_group_l_c de toutes les villes
INSERT INTO `p_c_group_l_c` (`p_circle_id`, `p_l_city_id`, `created_at`, `updated_at`)
SELECT 3, `id`, NOW(), NOW() FROM `p_l_city`;
