INSERT INTO `p_notification` (`id`, `uuid`, `p_n_type_id`, `title`, `created_at`, `updated_at`) 
VALUES (15, 'dfe92694-faed-4078-92b9-1a15561a8564', 3, 'Lorsqu\'un commentaire a été publié sur un sujet suivi', '2016-04-27 10:51:12', '2016-04-27 10:51:12');

UPDATE `p_notification` SET `description`='Lorsqu\'un commentaire a été publié sur un sujet suivi', `online`=1, `updated_at`='2016-04-27 10:52:32' 
WHERE p_notification.id=15;

INSERT INTO `p_notification` (`id`, `uuid`, `p_n_type_id`, `title`, `created_at`, `updated_at`) 
VALUES (16, 'cf41ce98-83c1-42b2-a208-87879dca21b6', 3, 'Lorsqu\'un commentaire a été publié sur une réponse à un sujet suivi', '2016-04-27 10:54:01', '2016-04-27 10:54:01');

UPDATE `p_notification` SET `description`='Lorsqu\'un commentaire a été publié sur une réponse à un sujet suivi', `online`=1, `updated_at`='2016-04-27 10:55:29' 
WHERE p_notification.id=16;


INSERT INTO `p_u_subscribe_email`
    (`p_user_id`, `p_notification_id`, `created_at`, `updated_at`)
SELECT id, 15, NOW(), NOW() from p_user;

INSERT INTO `p_u_subscribe_email`
    (`p_user_id`, `p_notification_id`, `created_at`, `updated_at`)
SELECT id, 16, NOW(), NOW() from p_user;

