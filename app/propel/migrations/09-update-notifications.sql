# upd notification type
UPDATE `p_n_type` SET `id` = '6'
WHERE `id` = '5';


# new notification type
INSERT INTO `p_n_type` (`id`, `title`, `description`, `created_at`, `updated_at`)
VALUES ('5', 'Localisation', NULL, now(), now());

# new notifications
INSERT INTO `p_notification` (`id`, `p_n_type_id`, `title`, `description`, `online`, `created_at`, `updated_at`) 
VALUES (20, 5, 'Lorsqu\'un nouvel élu correspond à ma ville', 'Un nouvel élu correspond à ma ville', 1, '2016-12-22 17:45:59', '2016-12-22 17:45:59');

INSERT INTO `p_notification` (`id`, `p_n_type_id`, `title`, `description`, `online`, `created_at`, `updated_at`) 
VALUES (21, 5, 'Lorsqu\'un nouvel élu correspond à mon département', 'Un nouvel élu correspond à mon département', 1, '2016-12-22 17:45:59', '2016-12-22 17:45:59');

INSERT INTO `p_notification` (`id`, `p_n_type_id`, `title`, `description`, `online`, `created_at`, `updated_at`) 
VALUES (22, 5, 'Lorsqu\'un nouvel élu correspond à ma région', 'Un nouvel élu correspond à ma région', 1, '2016-12-22 17:45:59', '2016-12-22 17:45:59');

INSERT INTO `p_notification` (`id`, `p_n_type_id`, `title`, `description`, `online`, `created_at`, `updated_at`) 
VALUES (23, 5, 'Lorsqu\'une nouvelle publication correspond à ma ville', 'Une nouvelle publication correspond à ma ville', 1, '2016-12-22 17:45:59', '2016-12-22 17:45:59');

INSERT INTO `p_notification` (`id`, `p_n_type_id`, `title`, `description`, `online`, `created_at`, `updated_at`) 
VALUES (24, 5, 'Lorsqu\'une nouvelle publication correspond à mon département', 'Une nouvelle publication correspond à mon département', 1, '2016-12-22 17:45:59', '2016-12-22 17:45:59');

INSERT INTO `p_notification` (`id`, `p_n_type_id`, `title`, `description`, `online`, `created_at`, `updated_at`) 
VALUES (25, 5, 'Lorsqu\'une nouvelle publication correspond à ma région', 'Une nouvelle publication correspond à ma région', 1, '2016-12-22 17:45:59', '2016-12-22 17:45:59');


# sf command > app/console politizr:uuids:populate PNotification



# Subscribe all users to new notifs email
INSERT INTO `p_u_subscribe_email` (`p_user_id`, `p_notification_id`, `created_at`, `updated_at`) 
SELECT `id`, 20, NOW(), NOW() from p_user;

INSERT INTO `p_u_subscribe_email` (`p_user_id`, `p_notification_id`, `created_at`, `updated_at`) 
SELECT `id`, 21, NOW(), NOW() from p_user;

INSERT INTO `p_u_subscribe_email` (`p_user_id`, `p_notification_id`, `created_at`, `updated_at`) 
SELECT `id`, 22, NOW(), NOW() from p_user;

INSERT INTO `p_u_subscribe_email` (`p_user_id`, `p_notification_id`, `created_at`, `updated_at`) 
SELECT `id`, 23, NOW(), NOW() from p_user;

INSERT INTO `p_u_subscribe_email` (`p_user_id`, `p_notification_id`, `created_at`, `updated_at`) 
SELECT `id`, 24, NOW(), NOW() from p_user;

INSERT INTO `p_u_subscribe_email` (`p_user_id`, `p_notification_id`, `created_at`, `updated_at`) 
SELECT `id`, 25, NOW(), NOW() from p_user;
