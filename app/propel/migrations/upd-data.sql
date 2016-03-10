# à exécuter req par req

# RAF > create table pr_metal_type
# INSERT INTO `p_r_metal_type` (`id`, `title`, `created_at`, `updated_at`) VALUES
# (1, 'bronze',   '2016-02-09 09:38:18',  '2016-02-09 09:38:18'),
# (2, 'silver',   '2016-02-09 09:39:30',  '2016-02-09 09:39:30'),
# (3, 'gold', '2016-02-09 09:39:30',  '2016-02-09 09:39:30');


# reputation > donnée incohérente
DELETE FROM `p_u_reputation`
WHERE `id` = '4';

# reputation > MAJ des id "suiveurs"

# 1/ Patch à la con (support vous suit partout)
UPDATE p_u_reputation
SET p_object_id = 1
WHERE
p_r_action_id = 28

# 2/ MAJ par date équivalente
UPDATE p_u_reputation as t1,
(
SELECT *
FROM p_u_reputation
WHERE
    p_r_action_id = 24
) as t2
SET t1.p_object_id = t2.p_user_id
WHERE
t1.created_at = t2.created_at
AND t1.p_r_action_id = 28

