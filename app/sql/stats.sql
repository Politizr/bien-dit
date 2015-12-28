# debate / reaction 's notes evolution
SELECT
    DATE(p_u_reputation.created_at) as DATE,
    p_u_reputation.id,
    COUNT(CASE WHEN p_u_reputation.p_r_action_id = 10 THEN 1 END) AS COUNT_NOTE_POS,
    COUNT(CASE WHEN p_u_reputation.p_r_action_id = 11 THEN 1 END) AS COUNT_NOTE_NEG
FROM `p_u_reputation` LEFT JOIN `p_r_action` ON p_u_reputation.p_r_action_id = p_r_action.id
WHERE
p_u_reputation.p_object_name = 'Politizr\\Model\\PDDebate'
AND p_u_reputation.p_object_id = 109
AND (p_u_reputation.p_r_action_id = 10 OR p_u_reputation.p_r_action_id = 11)
AND p_u_reputation.created_at >= '2015-09-01 00:00:00'
AND p_u_reputation.created_at < '2016-01-01 00:00:00'
GROUP BY DATE(p_u_reputation.created_at)

# debate / reaction 's notes sum until a date
SELECT
    p_u_reputation.id,
    COUNT(CASE WHEN p_u_reputation.p_r_action_id = 10 THEN 1 END) AS COUNT_NOTE_POS,
    COUNT(CASE WHEN p_u_reputation.p_r_action_id = 11 THEN 1 END) AS COUNT_NOTE_NEG
FROM `p_u_reputation` LEFT JOIN `p_r_action` ON p_u_reputation.p_r_action_id = p_r_action.id
WHERE
p_u_reputation.p_object_name = 'Politizr\\Model\\PDDebate'
AND p_u_reputation.p_object_id = 109
AND (p_u_reputation.p_r_action_id = 10 OR p_u_reputation.p_r_action_id = 11)
AND p_u_reputation.created_at < '2016-01-01 00:00:00'

