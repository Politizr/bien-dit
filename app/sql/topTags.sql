# Most popular tags
SELECT p_tag_id, count(p_tag_id) AS nb_tagged_objects
FROM
(
SELECT p_tag_id
FROM p_d_d_tagged_t
LEFT JOIN p_d_debate ON p_d_d_tagged_t.p_d_debate_id = p_d_debate.id
WHERE p_d_debate.online = 1
AND p_d_debate.published = 1
AND p_d_debate.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 90 DAY) AND NOW()

UNION ALL

SELECT p_tag_id
FROM p_d_r_tagged_t
LEFT JOIN p_d_reaction ON p_d_r_tagged_t.p_d_reaction_id = p_d_reaction.id
WHERE p_d_reaction.online = 1
AND p_d_reaction.published = 1
AND p_d_reaction.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 90 DAY) AND NOW()

UNION ALL

SELECT p_tag_id
FROM p_u_tagged_t
LEFT JOIN p_user ON p_u_tagged_t.p_user_id = p_user.id
WHERE p_user.online = 1
AND p_user.p_u_status_id = 1
AND p_user.created_at BETWEEN DATE_SUB(NOW(), INTERVAL 90 DAY) AND NOW()

) tables

GROUP BY p_tag_id
ORDER BY nb_tagged_objects desc