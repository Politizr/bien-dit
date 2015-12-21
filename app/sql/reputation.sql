# reputation evolution
SELECT DATE(p_u_reputation.created_at) as DATE, p_u_reputation.id, SUM(p_r_action.score_evolution) as SUM_SCORE
FROM `p_u_reputation` LEFT JOIN `p_r_action` ON p_u_reputation.p_r_action_id = p_r_action.id
WHERE
`p_user_id` = '1'
AND p_u_reputation.created_at >= '2015-09-01 00:00:00'
AND p_u_reputation.created_at < '2015-10-01 00:00:00'
GROUP BY DATE(p_u_reputation.created_at)
