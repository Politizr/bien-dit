# Insertion du propriétaire du groupe
INSERT INTO `p_c_owner` (`id`, `uuid`, `title`, `summary`, `description`, `created_at`, `updated_at`, `slug`)
VALUES ('1', NULL, 'Conseil Départemental de l\'Ariège', '<p>Conseil Départemental de l\'Ariège</p>', '<p>Conseil Départemental de l\'Ariège</p>', now(), now(), 'conseil-departemental-de-l-ariege');

# app/console politizr:uuids:populate PCOwner

# Insertion du groupe
INSERT INTO `p_circle` (`id`, `p_c_owner_id`, `uuid`, `title`, `summary`, `description`, `url`, `online`, `only_elected`, `created_at`, `updated_at`, `slug`)
VALUES ('1', 1, NULL, 'Budget Primitif 2017', '<p>Budget Primitif 2017</p>', '<p>Budget Primitif 2017</p>', NULL, '1', '0', now(), now(), 'conseil-departemental-ariege');

# app/console politizr:uuids:populate PCircle

# Insertion des topics principaux
INSERT INTO `p_c_topic` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`, `slug`) 
VALUES (1, 1, 'Agriculture', '<p>Emensis itaque difficultatibus multis et nive obrutis callibus plurimis ubi prope Rauracum ventum est ad supercilia fluminis Rheni, resistente multitudine Alamanna pontem suspendere navium conpage Romani vi nimia vetabantur ritu grandinis undique convolantibus telis, et cum id inpossibile videretur, imperator cogitationibus magnis attonitus, quid capesseret ambigebat.</p>', '<p>Emensis itaque difficultatibus multis et nive obrutis callibus plurimis ubi prope Rauracum ventum est ad supercilia fluminis Rheni, resistente multitudine Alamanna pontem suspendere navium conpage Romani vi nimia vetabantur ritu grandinis undique convolantibus telis, et cum id inpossibile videretur, imperator cogitationibus magnis attonitus, quid capesseret ambigebat.</p>', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'agriculture');

INSERT INTO `p_c_topic` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`, `slug`) 
VALUES (2, 1, 'Culture', '<p>Emensis itaque difficultatibus multis et nive obrutis callibus plurimis ubi prope Rauracum ventum est ad supercilia fluminis Rheni, resistente multitudine Alamanna pontem suspendere navium conpage Romani vi nimia vetabantur ritu grandinis undique convolantibus telis, et cum id inpossibile videretur, imperator cogitationibus magnis attonitus, quid capesseret ambigebat.</p>', '<p>Emensis itaque difficultatibus multis et nive obrutis callibus plurimis ubi prope Rauracum ventum est ad supercilia fluminis Rheni, resistente multitudine Alamanna pontem suspendere navium conpage Romani vi nimia vetabantur ritu grandinis undique convolantibus telis, et cum id inpossibile videretur, imperator cogitationibus magnis attonitus, quid capesseret ambigebat.</p>', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'culture');

INSERT INTO `p_c_topic` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`, `slug`) 
VALUES (3, 1, 'Économie', '<p>Emensis itaque difficultatibus multis et nive obrutis callibus plurimis ubi prope Rauracum ventum est ad supercilia fluminis Rheni, resistente multitudine Alamanna pontem suspendere navium conpage Romani vi nimia vetabantur ritu grandinis undique convolantibus telis, et cum id inpossibile videretur, imperator cogitationibus magnis attonitus, quid capesseret ambigebat.</p>', '<p>Emensis itaque difficultatibus multis et nive obrutis callibus plurimis ubi prope Rauracum ventum est ad supercilia fluminis Rheni, resistente multitudine Alamanna pontem suspendere navium conpage Romani vi nimia vetabantur ritu grandinis undique convolantibus telis, et cum id inpossibile videretur, imperator cogitationibus magnis attonitus, quid capesseret ambigebat.</p>', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'economie');

INSERT INTO `p_c_topic` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`, `slug`) 
VALUES (4, 1, 'Éducation', '<p>Emensis itaque difficultatibus multis et nive obrutis callibus plurimis ubi prope Rauracum ventum est ad supercilia fluminis Rheni, resistente multitudine Alamanna pontem suspendere navium conpage Romani vi nimia vetabantur ritu grandinis undique convolantibus telis, et cum id inpossibile videretur, imperator cogitationibus magnis attonitus, quid capesseret ambigebat.</p>', '<p>Emensis itaque difficultatibus multis et nive obrutis callibus plurimis ubi prope Rauracum ventum est ad supercilia fluminis Rheni, resistente multitudine Alamanna pontem suspendere navium conpage Romani vi nimia vetabantur ritu grandinis undique convolantibus telis, et cum id inpossibile videretur, imperator cogitationibus magnis attonitus, quid capesseret ambigebat.</p>', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'education');

INSERT INTO `p_c_topic` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`, `slug`) 
VALUES (5, 1, 'Jeunesse', '<p>Emensis itaque difficultatibus multis et nive obrutis callibus plurimis ubi prope Rauracum ventum est ad supercilia fluminis Rheni, resistente multitudine Alamanna pontem suspendere navium conpage Romani vi nimia vetabantur ritu grandinis undique convolantibus telis, et cum id inpossibile videretur, imperator cogitationibus magnis attonitus, quid capesseret ambigebat.</p>', '<p>Emensis itaque difficultatibus multis et nive obrutis callibus plurimis ubi prope Rauracum ventum est ad supercilia fluminis Rheni, resistente multitudine Alamanna pontem suspendere navium conpage Romani vi nimia vetabantur ritu grandinis undique convolantibus telis, et cum id inpossibile videretur, imperator cogitationibus magnis attonitus, quid capesseret ambigebat.</p>', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'jeunesse');

INSERT INTO `p_c_topic` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`, `slug`) 
VALUES (6, 1, 'Solidarité', '<p>Emensis itaque difficultatibus multis et nive obrutis callibus plurimis ubi prope Rauracum ventum est ad supercilia fluminis Rheni, resistente multitudine Alamanna pontem suspendere navium conpage Romani vi nimia vetabantur ritu grandinis undique convolantibus telis, et cum id inpossibile videretur, imperator cogitationibus magnis attonitus, quid capesseret ambigebat.</p>', '<p>Emensis itaque difficultatibus multis et nive obrutis callibus plurimis ubi prope Rauracum ventum est ad supercilia fluminis Rheni, resistente multitudine Alamanna pontem suspendere navium conpage Romani vi nimia vetabantur ritu grandinis undique convolantibus telis, et cum id inpossibile videretur, imperator cogitationibus magnis attonitus, quid capesseret ambigebat.</p>', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'solidarite');

INSERT INTO `p_c_topic` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`, `slug`) 
VALUES (7, 1, 'Sport', '<p>Emensis itaque difficultatibus multis et nive obrutis callibus plurimis ubi prope Rauracum ventum est ad supercilia fluminis Rheni, resistente multitudine Alamanna pontem suspendere navium conpage Romani vi nimia vetabantur ritu grandinis undique convolantibus telis, et cum id inpossibile videretur, imperator cogitationibus magnis attonitus, quid capesseret ambigebat.</p>', '<p>Emensis itaque difficultatibus multis et nive obrutis callibus plurimis ubi prope Rauracum ventum est ad supercilia fluminis Rheni, resistente multitudine Alamanna pontem suspendere navium conpage Romani vi nimia vetabantur ritu grandinis undique convolantibus telis, et cum id inpossibile videretur, imperator cogitationibus magnis attonitus, quid capesseret ambigebat.</p>', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'sport');

INSERT INTO `p_c_topic` (`id`, `p_circle_id`, `title`, `summary`, `description`, `online`, `created_at`, `updated_at`, `slug`) 
VALUES (8, 1, 'Tranports', '<p>Emensis itaque difficultatibus multis et nive obrutis callibus plurimis ubi prope Rauracum ventum est ad supercilia fluminis Rheni, resistente multitudine Alamanna pontem suspendere navium conpage Romani vi nimia vetabantur ritu grandinis undique convolantibus telis, et cum id inpossibile videretur, imperator cogitationibus magnis attonitus, quid capesseret ambigebat.</p>', '<p>Emensis itaque difficultatibus multis et nive obrutis callibus plurimis ubi prope Rauracum ventum est ad supercilia fluminis Rheni, resistente multitudine Alamanna pontem suspendere navium conpage Romani vi nimia vetabantur ritu grandinis undique convolantibus telis, et cum id inpossibile videretur, imperator cogitationibus magnis attonitus, quid capesseret ambigebat.</p>', 1, '2017-09-08 14:38:56', '2017-09-08 14:38:56', 'transports');

# app/console politizr:uuids:populate PCTopic


# Insertion dans p_c_group_l_c de toutes les villes d'ariège
INSERT INTO `p_c_group_l_c` (`p_circle_id`, `p_l_city_id`, `created_at`, `updated_at`)
SELECT 1, `id`, NOW(), NOW() FROM `p_l_city` WHERE `p_l_department_id` = 9;

# Force geoloc topic
UPDATE `p_c_topic` SET `force_geoloc_type`= "department", `force_geoloc_id`=9;

# Inscription de tous les ariégeois
INSERT INTO `p_u_in_p_c` (`p_circle_id`, `p_user_id`, `created_at`, `updated_at`)
SELECT 1, `id`, NOW(), NOW()
FROM `p_user`
WHERE `p_l_city_id` IN (
    SELECT `id` FROM `p_l_city` WHERE `p_l_department_id` = 9
);

# MAJ des droits
UPDATE `p_user` SET `roles` = REPLACE(`roles`, ' ROLE_CIRCLE_1 |', '');

UPDATE `p_user` SET `roles` = CONCAT(`roles`, ' ROLE_CIRCLE_1 |'), `updated_at` = NOW() 
WHERE `p_l_city_id` IN (
    SELECT `id` FROM `p_l_city` WHERE `p_l_department_id` = 9
);

# MAJ des users ayant l'autorisation de répondre
UPDATE `p_u_in_p_c` SET `is_authorized_reaction` = 1 WHERE `p_user_id` = 3;
