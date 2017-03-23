# Upd tag type
INSERT INTO `p_t_tag_type` (`id`, `title`, `description`, `created_at`, `updated_at`) 
VALUES (4, 'Famille', 'Grandes familles thématiques', '2017-03-23 09:25:29', '2017-03-23 09:25:29');

# Upd theme type
UPDATE `p_tag` SET `p_t_tag_type_id`=4, `moderated`=0, `updated_at`='2017-03-23 09:44:15' 
WHERE p_tag.id=627;
UPDATE `p_tag` SET `p_t_tag_type_id`=4, `title`='Environnement', `moderated`=0, `updated_at`='2017-03-23 09:45:40' 
WHERE p_tag.id=479;
UPDATE `p_tag` SET `p_t_tag_type_id`=4, `title`='Économie', `updated_at`='2017-03-23 09:46:41' 
WHERE p_tag.id=433;
UPDATE `p_tag` SET `p_t_tag_type_id`=4, `title`='Éducation', `updated_at`='2017-03-23 09:48:02' 
WHERE p_tag.id=651;
UPDATE `p_tag` SET `p_t_tag_type_id`=4, `title`='Patrimoine, culture et loisirs', `moderated`=0, `updated_at`='2017-03-23 09:49:04', `slug`='patrimoine-culture-et-loisirs' 
WHERE p_tag.id=839;
UPDATE `p_tag` SET `p_t_tag_type_id`=4, `moderated`=0, `updated_at`='2017-03-23 09:50:08' 
WHERE p_tag.id=652;
UPDATE `p_tag` SET `p_t_tag_type_id`=4, `title`='Sécurité', `moderated`=0, `updated_at`='2017-03-23 09:51:29' 
WHERE p_tag.id=271;
UPDATE `p_tag` SET `p_t_tag_type_id`=4, `title`='Social', `moderated`=0, `updated_at`='2017-03-23 09:52:30' 
WHERE p_tag.id=498;
UPDATE `p_tag` SET `p_t_tag_type_id`=4, `title`='Technologie', `moderated`=0, `updated_at`='2017-03-23 09:53:38', `slug`='technologie' 
WHERE p_tag.id=514;
UPDATE `p_tag` SET `p_t_tag_type_id`=4, `title`='Transport', `moderated`=0, `updated_at`='2017-03-23 09:54:59', `slug`='transport' 
WHERE p_tag.id=252;
UPDATE `p_tag` SET `p_t_tag_type_id`=4, `moderated`=0, `updated_at`='2017-03-23 09:56:00' 
WHERE p_tag.id=407;