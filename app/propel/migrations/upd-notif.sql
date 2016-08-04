# maj bdd
ALTER TABLE `p_u_notification`
    ADD `description` TEXT AFTER `p_author_user_id`;


# nv notif
INSERT INTO `p_n_type` (`title`, `description`, `created_at`, `updated_at`)
VALUES ('Support', 'Notification libre effectuée par l\'administrateur', now(), now());

INSERT INTO `p_notification` (`id`, `uuid`, `p_n_type_id`, `title`, `description`, `online`, `created_at`, `updated_at`) VALUES
(19,    'd58fce06-2216-415e-b761-c89b3240b07b', 5,  'Lorsque le support Politizr a un message à communiquer',   'Notification libre effectuée par un administrateur Politizr',  1,  '2016-08-03 12:48:07',  '2016-08-03 13:01:55');


# + maj user<>p_u_subscribe_email via "app/console politizr:notif:subscribe 19"