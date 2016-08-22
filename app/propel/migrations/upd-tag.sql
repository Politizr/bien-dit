INSERT INTO `p_t_tag_type` (`title`, `description`, `created_at`, `updated_at`)
VALUES ('Type', 'Type d\'une publication', now(), now());


INSERT INTO `p_tag` (`uuid`, `p_t_tag_type_id`, `title`, `created_at`, `updated_at`, `slug`, `online`) 
VALUES ('486eff79-cc23-47a1-bc3d-ea8a44812829', 3, 'Question', '2016-08-22 14:37:50', '2016-08-22 14:37:50', 'question', 1);

INSERT INTO `p_tag` (`uuid`, `p_t_tag_type_id`, `title`, `created_at`, `updated_at`, `slug`, `online`) 
VALUES ('2fcd6924-a650-4fec-99c0-fa4a637fde5c', 3, 'Opinion', '2016-08-22 15:38:31', '2016-08-22 15:38:31', 'opinion', 1);

INSERT INTO `p_tag` (`uuid`, `p_t_tag_type_id`, `title`, `created_at`, `updated_at`, `slug`, `online`) 
VALUES ('aeebdfc7-3fa8-4cf7-84d2-1b50914b97cc', 3, 'Action concrète', '2016-08-22 15:43:30', '2016-08-22 15:43:30', 'action-concrete', 1);

INSERT INTO `p_tag` (`uuid`, `p_t_tag_type_id`, `title`, `created_at`, `updated_at`, `slug`, `online`) 
VALUES ('a42762f2-f0db-4c5a-b372-809228defdf7', 3, 'Retour d\'expérience', '2016-08-22 15:45:09', '2016-08-22 15:45:09', 'retour-d-experience', 1);



UPDATE `p_tag` SET `p_t_tag_type_id`=3, `moderated`=0, `updated_at`='2016-08-22 15:41:59' 
WHERE p_tag.id=422
