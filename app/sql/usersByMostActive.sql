
# not used directly but w. Propel
SELECT DISTINCT p_user.id, p_user.uuid, p_user.name, p_user.username, COUNT(p_u_reputation.id) AS `MostActive`
FROM `p_user` 
LEFT JOIN `p_u_reputation` ON (p_user.id=p_u_reputation.p_user_id) AND p_u_reputation.p_r_action_id IN (3,4,5,10,12,14,22,24)
WHERE p_user.online=1 AND p_user.p_u_status_id=1 AND p_user.qualified=1
GROUP BY p_user.id
