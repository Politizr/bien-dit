ALTER TABLE `p_l_city`
CHANGE `slug` `slug` varchar(255) COLLATE 'utf8_general_ci' NULL AFTER `p_l_department_id`;