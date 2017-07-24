# notifs email
INSERT INTO `p_n_email` (`id`, `title`, `description`, `created_at`) VALUES
(1, 'Notification de l\'administrateur', 'Notification de l\'administrateur (direct)', NOW()),
(2, 'Activités de mon profil (1 email par jour maximum)', 'Activités de mon profil (quotidien)', NOW()),
(3, 'Activités liées à mes centres d\'intérêts et à mon lieu de vie (1 email par semaine maximum)', 'Activités liées à mes centres d\'intérêts et à mon lieu de vie (hebdo)', NOW());

# abonnement par défaut pour tous les users
INSERT INTO `p_u_subscribe_p_n_e` (`p_user_id`, `p_n_email_id`)
SELECT DISTINCT id, 1 from p_user;

INSERT INTO `p_u_subscribe_p_n_e` (`p_user_id`, `p_n_email_id`)
SELECT DISTINCT id, 2 from p_user;

INSERT INTO `p_u_subscribe_p_n_e` (`p_user_id`, `p_n_email_id`)
SELECT DISTINCT id, 3 from p_user;

# cmd sf +UUID
# app/console politizr:uuids:populate PNEmail

