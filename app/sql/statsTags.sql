# Top tags for users
SELECT count(p_tag.id) as nb_users, p_tag.title as title
FROM `p_tag`
LEFT JOIN `p_u_tagged_t` ON (p_tag.id = p_u_tagged_t.p_tag_id)
WHERE p_u_tagged_t.created_at > '2019-02-01'
GROUP BY p_tag.id
ORDER BY nb_users DESC


# Top tags for subjects
SELECT count(p_tag.id) as `nb_debates`, p_tag.title as `title`
FROM `p_tag`
LEFT JOIN `p_d_d_tagged_t` ON (p_tag.id = p_d_d_tagged_t.p_tag_id)
WHERE p_t_tag_type_id = 2
AND p_d_d_tagged_t.created_at > '2015-01-01'
GROUP BY p_tag.id
ORDER BY nb_debates DESC