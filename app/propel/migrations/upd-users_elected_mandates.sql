# correctif typo & co
UPDATE `p_user` SET `email_canonical`='verofstpan@gmail.com', `firstname`='Véronique', `homepage`=0, `banned`=0, `updated_at`='2016-08-14 18:39:17', `slug`='veronique-fernandez' 
WHERE p_user.id=325;

DELETE 
FROM `p_u_tagged_t` 
WHERE p_u_tagged_t.p_user_id=236 AND p_u_tagged_t.p_tag_id=16;

INSERT INTO `p_u_tagged_t` (`p_user_id`, `p_tag_id`, `created_at`, `updated_at`) 
VALUES (236, 87, '2016-08-14 19:11:46', '2016-08-14 19:11:46');

UPDATE `p_u_mandate` SET `localization`='Coufouleux (Tarn)', `updated_at`='2016-08-14 19:14:00' 
WHERE p_u_mandate.id=23;

UPDATE `p_u_mandate` SET `localization`='Occitanie', `end_at`=NULL, `updated_at`='2016-08-15 11:13:52' 
WHERE p_u_mandate.id=9;

UPDATE `p_u_mandate` SET `localization`='Communauté d\'agglomeration du grand Narbonne', `end_at`=NULL, `updated_at`='2016-08-15 11:14:41' 
WHERE p_u_mandate.id=8;

UPDATE `p_u_mandate` SET `localization`='Gruissan', `end_at`=NULL, `updated_at`='2016-08-15 11:15:11' 
WHERE p_u_mandate.id=7;

UPDATE `p_user` SET `roles`='| ROLE_CITIZEN | ROLE_PROFILE_COMPLETED |', `qualified`=0, `homepage`=0, `updated_at`='2016-08-16 09:36:42' 
WHERE p_user.id=93;

UPDATE `p_user` SET `roles`='| ROLE_CITIZEN | ROLE_PROFILE_COMPLETED |', `qualified`=0, `homepage`=0, `updated_at`='2016-08-16 09:49:01' 
WHERE p_user.id=110;

DELETE 
FROM `p_u_tagged_t` 
WHERE p_u_tagged_t.p_user_id=138 AND p_u_tagged_t.p_tag_id=18;

INSERT INTO `p_u_tagged_t` (`p_user_id`, `p_tag_id`, `created_at`, `updated_at`) 
VALUES (138, 81, '2016-08-16 10:11:01', '2016-08-16 10:11:01');

UPDATE `p_user` SET `roles`='| ROLE_CITIZEN | ROLE_PROFILE_COMPLETED |', `qualified`=0, `homepage`=0, `updated_at`='2016-08-17 09:36:23' 
WHERE p_user.id=177;


# mandats
INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `end_at`, `created_at`, `updated_at`) 
VALUES ('ed31eca1-2b6f-4072-9aa7-a024ac8ed0dd', 98, 1, 1, 'Vigoulet-Auzil', '2008-01-01', '2014-01-01', '2016-08-14 17:47:49', '2016-08-14 17:47:49');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('669cf549-f3af-4175-92b5-38b6bf82ed53', 106, 1, 1, 'Mirecourt', '2014-01-01', '2016-08-14 18:07:34', '2016-08-14 18:07:34');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('2164a91f-f6fa-4a6f-8286-c217af4a105f', 156, 1, 1, 'Lyon', '2014-01-01', '2016-08-14 18:12:24', '2016-08-14 18:12:24');

# @todo fix this insert
INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('1451d3b8-aea6-4b22-a812-ad4db43a592b', 353, 1, 1, 'Versailles', '2014-01-01', '2016-08-14 18:15:09', '2016-08-14 18:15:09');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('bc3da854-961a-48c7-8b91-550a1c48290c', 220, 1, 1, 'Oullins', '2014-01-01', '2016-08-14 18:21:16', '2016-08-14 18:21:16');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('7eebf6b2-2b24-4c82-a411-f22025026ac0', 282, 1, 3, 'Villeneuve d\'Olmes', '2014-01-01', '2016-08-14 18:25:58', '2016-08-14 18:25:58');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('4509c0b8-1ec7-4f96-bb9c-b7c4268cddc5', 173, 1, 13, '1ère circonstription des français établis hors de France', '2013-01-01', '2016-08-14 18:29:39', '2016-08-14 18:29:39');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('b12a267b-fcd7-4a52-af8d-bff647b4d1e7', 149, 1, 2, 'Roubaix', '2014-01-01', '2016-08-14 18:56:36', '2016-08-14 18:56:36');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `end_at`, `created_at`, `updated_at`) 
VALUES ('9d40238f-4413-47fd-8e45-48a7e776e42f', 260, 1, 2, 'Foix', '2007-01-01', '2014-01-01', '2016-08-14 19:00:54', '2016-08-14 19:00:54');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('1df11d36-d2b1-4818-8eb2-b1cbf19c06c6', 275, 1, 3, 'Saint-Léger-du-Bourg-Denis', '2014-01-01', '2016-08-14 19:05:08', '2016-08-14 19:05:08');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('8b841cf1-3587-4aba-8ed8-8b87e8265678', 175, 1, 3, 'L\'Hospitalet-près-l\'Andorre', '2008-01-01', '2016-08-14 19:06:56', '2016-08-14 19:06:56');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('b47a110a-93a1-45db-a660-81b85673c5c5', 184, 1, 1, 'Fauville', '2014-01-01', '2016-08-14 19:19:41', '2016-08-14 19:19:41');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('0007169f-05ba-4773-b389-b959ae16b8f7', 171, 1, 1, 'Quiévrechain', '2014-01-01', '2016-08-15 11:05:59', '2016-08-15 11:05:59');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('0d28e443-3100-4895-9da8-0ec0445a2af8', 159, 1, 3, 'Juvisy-sur-Orge', '2014-01-01', '2016-08-15 11:08:22', '2016-08-15 11:08:22');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('f080f682-a705-4bfe-87bb-a887b88f8fbf', 159, 1, 10, 'Ile de France', '2016-01-01', '2016-08-15 11:08:56', '2016-08-15 11:08:56');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('e3ef4b77-1c85-43e8-9181-67a9d009bceb', 79, 1, 3, 'Jardin (Isère)', '2014-01-01', '2016-08-15 11:11:44', '2016-08-15 11:11:44');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('80c8bab5-39e5-429f-9679-cb5b2483280e', 128, 1, 3, 'Vernosc-lès-Annonay', '2014-01-01', '2016-08-15 11:17:29', '2016-08-15 11:17:29');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('92272848-47e9-4c44-888d-1b13e5a12272', 113, 1, 2, 'Orléans', '2014-01-01', '2016-08-15 11:20:29', '2016-08-15 11:20:29');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('c377a387-fda9-4ecf-a587-d1c898eebe9b', 99, 1, 2, 'Saint-Quentin', '2014-01-01', '2016-08-15 11:23:36', '2016-08-15 11:23:36');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('fe895b70-dfbf-4dac-8a68-9c4142e75965', 86, 1, 3, 'Wangen', '2014-01-01', '2016-08-15 11:26:47', '2016-08-15 11:26:47');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `end_at`, `created_at`, `updated_at`) 
VALUES ('99cedca6-f9e6-4a12-a73c-f20b2e73279b', 85, 1, 7, 'Caen', '2004-01-01', '2011-01-01', '2016-08-15 11:30:12', '2016-08-15 11:30:12');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `end_at`, `created_at`, `updated_at`) 
VALUES ('f59cd3db-0e9b-4822-b426-12c08c7e2fb0', 85, 1, 1, 'Hérouville-Saint-Clair', '2008-01-01', '2014-01-01', '2016-08-15 11:30:48', '2016-08-15 11:30:48');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('c4f6bf23-d89f-47d8-b362-12a36005017d', 67, 1, 22, 'Roumanie-Moldavie', '2014-01-01', '2016-08-15 11:54:14', '2016-08-15 11:54:14');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('39a422e9-f824-4dfd-a3e1-c33064eedf41', 49, 1, 1, 'Coussa', '2014-01-01', '2016-08-15 11:55:49', '2016-08-15 11:55:49');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('5ac1ab2c-4ebd-4e86-af49-6d715dea30af', 50, 1, 1, 'Burlioncourt', '2014-01-01', '2016-08-15 11:58:30', '2016-08-15 11:58:30');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('ecce3ab4-e184-44af-8629-d42063647578', 262, 1, 2, 'Montrouge', '2014-01-01', '2016-08-15 12:02:14', '2016-08-15 12:02:14');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('2c526dfc-6f23-4194-a9b6-390c0434b9c6', 263, 1, 1, 'Paris', '2014-01-01', '2016-08-15 13:28:25', '2016-08-15 13:28:25');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('678aa9b7-4eae-4f7e-9f85-8aab190f881a', 273, 1, 1, 'Colomiers', '2014-01-01', '2016-08-15 13:30:19', '2016-08-15 13:30:19');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('c8426ac7-7d66-49f1-bf0a-1b140fd44366', 276, 1, 3, 'Sainte-Hélène', '2014-01-01', '2016-08-15 13:32:15', '2016-08-15 13:32:15');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('dc2f15aa-31c5-41ee-bf03-fbdfc0244ed9', 284, 1, 2, 'Metz', '2008-01-01', '2016-08-15 13:34:48', '2016-08-15 13:34:48');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('ee668c34-74d9-440d-8629-ada299d00776', 288, 1, 1, 'Boulogne-Billancourt', '2014-01-01', '2016-08-15 13:40:07', '2016-08-15 13:40:07');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('f25748ab-ec1c-4e57-b926-baf11fe6c6ae', 298, 1, 3, 'Jurançon', '2008-01-01', '2016-08-15 13:43:14', '2016-08-15 13:43:14');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('16362d63-5fcc-467f-9444-2e1710bfdb1e', 57, 1, 2, 'Vélizy Villacoublay', '2014-01-01', '2016-08-15 13:45:24', '2016-08-15 13:45:24');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('9633b78d-d52b-4bf6-8e1a-e8556e7d3a42', 69, 1, 2, 'Paris (4ème)', '2014-01-01', '2016-08-15 13:47:22', '2016-08-15 13:47:22');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('304cdb36-0eaa-4f4d-b946-b930167a6e98', 71, 1, 1, 'Rambouillet', '2014-01-01', '2016-08-15 13:49:49', '2016-08-15 13:49:49');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('7897611e-5fcc-491f-8376-89babcacc6f6', 72, 1, 3, 'Querqueville', '2008-01-01', '2016-08-15 14:20:39', '2016-08-15 14:20:39');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('6a52b1b6-7faf-472e-9520-cf6a67841a28', 74, 1, 2, 'Montélimar', '2014-01-01', '2016-08-15 14:23:44', '2016-08-15 14:23:44');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('a9b0de79-d4cc-4f56-beac-d51228cbbbec', 78, 1, 3, 'Saint-Romain-de-Colbosc', '2014-01-01', '2016-08-15 14:27:35', '2016-08-15 14:27:35');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `end_at`, `created_at`, `updated_at`) 
VALUES ('0ce0b5bc-e31e-49a6-958a-ef64a6e509a0', 81, 1, 7, 'Pas de Calais, canton de Calais centre', '1998-01-01', '2015-01-01', '2016-08-15 14:37:11', '2016-08-15 14:37:11');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('9ddc4292-864b-4163-a351-b8f09070c654', 82, 1, 3, 'Monthureux-sur-Saône', '2008-01-01', '2016-08-15 14:40:42', '2016-08-15 14:40:42');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('9715d87d-3cec-4e52-8099-0e44daad5a9d', 83, 1, 1, 'Nonant', '2014-01-01', '2016-08-15 14:42:37', '2016-08-15 14:42:37');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `end_at`, `created_at`, `updated_at`) 
VALUES ('093815d3-edb6-44c7-bf8f-5fad257b5fea', 91, 1, 1, 'Paris', '2008-01-01', '2014-01-01', '2016-08-16 09:23:28', '2016-08-16 09:23:28');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `end_at`, `created_at`, `updated_at`) 
VALUES ('5d3657f5-0bda-4515-a82b-68d4842c93d4', 91, 1, 10, 'Ile de France', '2010-01-01', '2015-01-01', '2016-08-16 09:24:06', '2016-08-16 09:24:06');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `end_at`, `created_at`, `updated_at`) 
VALUES ('c64a453c-c583-4046-a71e-6d648f3f7bdb', 91, 1, 2, 'Paris', '2001-01-01', '2008-01-01', '2016-08-16 09:24:37', '2016-08-16 09:24:37');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('e4e4566a-b42d-4513-a4f5-28cad9006268', 94, 1, 3, 'Émancé', '2014-01-01', '2016-08-16 09:40:36', '2016-08-16 09:40:36');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('0d019080-c731-4793-bd32-2a625452261f', 97, 1, 1, 'Alfortville', '2014-01-01', '2016-08-16 09:44:20', '2016-08-16 09:44:20');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('e6465bca-595f-4fee-afe9-5c4f4f402243', 112, 1, 1, 'Bessan', '2014-01-01', '2016-08-16 09:53:25', '2016-08-16 09:53:25');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('c544fb87-b8bf-4b72-a97f-10c4afc4c66d', 117, 1, 3, 'Vence', '2014-01-01', '2016-08-16 09:55:41', '2016-08-16 09:55:41');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('9491a40f-fbc0-4053-8d80-769a37a625fe', 119, 1, 1, 'Maureillas-las-Illas', '2014-01-01', '2016-08-16 09:58:15', '2016-08-16 09:58:15');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `end_at`, `created_at`, `updated_at`) 
VALUES ('b68982a8-86a9-46eb-bfcf-d8ac803d3577', 130, 1, 2, 'Creil', '2008-01-01', '2014-01-01', '2016-08-16 10:03:56', '2016-08-16 10:03:56');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('6ecc51d6-85a0-4f15-b5ee-b7dad17d58cc', 134, 1, 3, 'Auger-Saint-Vincent', '2014-01-01', '2016-08-16 10:05:56', '2016-08-16 10:05:56');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('7fc1356d-eb55-4138-b2a8-17ade4a24f03', 136, 1, 2, 'Montlignon', '2014-01-01', '2016-08-16 10:07:57', '2016-08-16 10:07:57');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `end_at`, `created_at`, `updated_at`) 
VALUES ('ada8110b-d55f-4dc9-a1df-9c2eaeb1e7f9', 138, 1, 3, 'Nevers', '2010-01-01', '2014-01-01', '2016-08-16 10:12:49', '2016-08-16 10:12:49');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('ac868c41-3d8d-4da5-b85e-7f60644dc55d', 138, 1, 1, 'Nevers', '2014-01-01', '2016-08-16 10:13:32', '2016-08-16 10:13:32');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `end_at`, `created_at`, `updated_at`) 
VALUES ('d351f074-5927-4fa0-9f61-9950e1028f0b', 150, 1, 1, 'Cachan', '2008-01-01', '2014-01-01', '2016-08-16 10:16:04', '2016-08-16 10:16:04');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('810a7d03-3a04-4eb8-85c4-8962ef7f62b1', 150, 1, 2, 'Cachan', '2014-01-01', '2016-08-16 10:16:29', '2016-08-16 10:16:29');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('394f1638-3bc1-4c82-acf1-0648b54959ae', 150, 1, 7, 'Canton de Cachan', '2015-01-01', '2016-08-16 10:17:47', '2016-08-16 10:17:47');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('c773a571-d66f-4ce5-b42f-9877e54acde5', 153, 1, 1, 'Noisy-le-Sec', '2014-01-01', '2016-08-17 09:19:58', '2016-08-17 09:19:58');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `end_at`, `created_at`, `updated_at`) 
VALUES ('1812a598-8e05-4ffb-89ef-a2924a77e5aa', 161, 1, 1, 'Thann', '2008-01-01', '2014-01-01', '2016-08-17 09:24:50', '2016-08-17 09:24:50');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('f1ed7eac-0c3a-4693-8daf-79a629e5d5cb', 165, 1, 7, 'Caen', '1994-01-01', '2016-08-17 09:31:51', '2016-08-17 09:31:51');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('b0498630-d9d3-4b58-931e-545df92d812c', 165, 1, 1, 'Caen', '2014-01-01', '2016-08-17 09:33:59', '2016-08-17 09:33:59');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `end_at`, `created_at`, `updated_at`) 
VALUES ('36943fee-94df-4be7-a94e-aad46da6f2be', 180, 1, 7, 'Canton de Reims 5', '2001-01-01', '2015-01-01', '2016-08-17 09:43:01', '2016-08-17 09:43:01');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('ede96548-9bd6-4245-bad1-95a645c62472', 180, 1, 1, 'Reims', '2014-01-01', '2016-08-17 09:44:24', '2016-08-17 09:44:24');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `end_at`, `created_at`, `updated_at`) 
VALUES ('29e77333-9975-4688-a7bf-060ba031da48', 180, 1, 2, 'Reims', '2008-01-01', '2014-01-01', '2016-08-17 09:47:03', '2016-08-17 09:47:03');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('f90560e5-e3fc-45a9-bce1-4e57705a1162', 187, 1, 1, 'Guichainville', '2014-01-01', '2016-08-17 09:50:39', '2016-08-17 09:50:39');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('e9c177cc-dd2f-4ebb-a13e-1ffb4c8e3f1c', 188, 1, 1, 'Saint-Romans', '2014-01-01', '2016-08-17 09:52:53', '2016-08-17 09:52:53');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `end_at`, `created_at`, `updated_at`) 
VALUES ('be3c7217-66ec-4ab3-b96e-26c2a1001522', 198, 1, 19, 'Massif Central - Centre', '2009-01-01', '2014-01-01', '2016-08-17 10:11:37', '2016-08-17 10:11:37');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('7d3a9672-0e50-4938-b695-42f200ba6bd9', 198, 1, 1, 'Tours', '2008-01-01', '2016-08-17 10:13:24', '2016-08-17 10:13:24');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('dbdefe62-7388-46b4-8877-af826638a7e2', 201, 1, 1, 'Aubervilliers', '2014-01-01', '2016-08-17 10:22:43', '2016-08-17 10:22:43');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `end_at`, `created_at`, `updated_at`) 
VALUES ('33a5a391-b83d-4570-a2b4-da733f354d88', 201, 1, 2, 'Aubervilliers', '2007-01-01', '2014-01-01', '2016-08-17 10:23:12', '2016-08-17 10:23:12');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('76f4f9bc-3f5c-4666-ae73-16355ff03ab5', 239, 1, 1, 'Villeneuve-lès-Avignon', '2008-01-01', '2016-08-17 10:27:59', '2016-08-17 10:27:59');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('115c75ab-282d-4467-974e-dbfb86d8be1c', 244, 1, 3, 'Herleville', '2014-01-01', '2016-08-17 10:30:25', '2016-08-17 10:30:25');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('455c44d5-a9b7-4fb3-8e64-22c727bc5dde', 246, 1, 10, 'Ile de France', '2015-01-01', '2016-08-17 10:36:18', '2016-08-17 10:36:18');

INSERT INTO `p_u_mandate` (`uuid`, `p_user_id`, `p_q_type_id`, `p_q_mandate_id`, `localization`, `begin_at`, `created_at`, `updated_at`) 
VALUES ('c3155341-1c12-4be6-a3ae-d434d433d4a4', 250, 1, 1, 'Paris (7ème)', '2014-01-01', '2016-08-17 10:50:40', '2016-08-17 10:50:40');


