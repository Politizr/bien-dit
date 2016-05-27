# Cgu / Cgv / Charte

INSERT INTO `p_m_cgu` (`id`, `title`, `online`, `created_at`, `updated_at`)
VALUES (1, 'Conditions Générales d\'Utilisation', 1, NOW(), NOW());

INSERT INTO `p_m_cgv` (`id`, `title`, `online`, `created_at`, `updated_at`)
VALUES (1, 'Conditions Générales de Vente', 1, NOW(), NOW());

INSERT INTO `p_m_charte` (`id`, `title`, `online`, `created_at`, `updated_at`)
VALUES (1, 'Charte Politizr', 1, NOW(), NOW());


# statuts

UPDATE `p_u_status` SET
`title` = 'En cours d\'inscription',
`updated_at` = '2015-09-30 12:29:40'
WHERE `id` = '2';
