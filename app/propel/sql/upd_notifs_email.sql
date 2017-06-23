# notifs email
INSERT INTO `p_n_email` (`id`, `title`, `description`) VALUES
(1, 'Notification de l\'administrateur', 'Notification de l\'administrateur (direct)'),
(2, 'Activité de mon profil', 'Activité de mon profil (quotidien)'),
(3, 'Activité liée à mes centres d\'intérêts et à mon lieu de vie', 'Activité liée à mes centres d\'intérêts et à mon lieu de vie (hebdo');

# cmd sf +UUID
# app/console politizr:uuids:populate PNEmail

# abonnement par défaut pour tous les users
INSERT INTO `p_u_subscribe_p_n_e` (`p_user_id`, `p_n_email_id`)
SELECT DISTINCT id, 1 from p_user;

INSERT INTO `p_u_subscribe_p_n_e` (`p_user_id`, `p_n_email_id`)
SELECT DISTINCT id, 2 from p_user;

INSERT INTO `p_u_subscribe_p_n_e` (`p_user_id`, `p_n_email_id`)
SELECT DISTINCT id, 3 from p_user;
