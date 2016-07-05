INSERT INTO `p_notification` (`id`, `uuid`, `p_n_type_id`, `title`, `description`, `online`, `created_at`, `updated_at`) 
VALUES (17, 'fa9d1c01-1df8-4da3-b779-2c1daa1e7806', 3, 'Lorsqu\'un nouveau profil correspond à une de mes thématiques suivies.', 'Lorsqu\'un nouveau profil correspond à une de mes thématiques suivies.', 1, '2016-07-04 12:33:27', '2016-07-04 12:33:27');


INSERT INTO `p_notification` (`id`, `uuid`, `p_n_type_id`, `title`, `description`, `online`, `created_at`, `updated_at`) 
VALUES (18, '084e545e-37f4-499f-8ac3-c950a443fe7a', 3, 'Lorsqu\'une nouvelle publication correspond à une de mes thématiques suivies.', 'Lorsqu\'une nouvelle publication correspond à une de mes thématiques suivies.', 1, '2016-07-04 12:36:16', '2016-07-04 12:36:16');


# upd abonnement par défaut > checked pour tous les users
INSERT INTO `p_u_subscribe_email` (`p_notification_id`, `p_user_id`)
SELECT 17, `id` FROM `p_user`;

INSERT INTO `p_u_subscribe_email` (`p_notification_id`, `p_user_id`)
SELECT 18, `id` FROM `p_user`;
