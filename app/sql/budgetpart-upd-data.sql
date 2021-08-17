# upd p_circle_type w. budgetpart
UPDATE `p_circle_type` SET
`id` = '2',
`title` = 'Budget Participatif',
`summary` = 'Consultation de type budget participatif',
`created_at` = '2021-03-12 14:12:05',
`updated_at` = '2021-05-15 09:49:07'
WHERE `id` = '2';

# upd p_circle_type w. survey form
INSERT INTO `p_circle_type` (`title`, `summary`, `created_at`, `updated_at`)
VALUES ('Questionnaire', 'Questionnaire, enquête, sondage', now(), now());
