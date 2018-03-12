# Insertion des types de groupe
INSERT INTO `p_circle_type` (`id`, `title`, `summary`, `created_at`, `updated_at`) 
VALUES 
(1, 'Ballotage', 'Groupe pour une offre ballotage', '2018-03-12 14:12:05', '2018-03-12 14:12:05'),
(2, 'Majorité', 'Groupe pour une offre majorité', '2018-03-12 14:12:05', '2018-03-12 14:12:05'),
(3, 'Unanimité', 'Groupe pour une offre unanimité', '2018-03-12 14:12:05', '2018-03-12 14:12:05'),
(4, 'Sur mesure', 'Groupe pour une offre sur-mesure', '2018-03-12 14:12:05', '2018-03-12 14:12:05');

# MAJ des groupes existants
UPDATE `p_circle`
SET `p_circle_type_id` = 4
WHERE id = 1;

UPDATE `p_circle`
SET `p_circle_type_id` = 4
WHERE id = 2;
