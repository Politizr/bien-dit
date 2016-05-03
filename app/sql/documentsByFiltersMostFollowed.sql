#  Débats publiés
SELECT DISTINCT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.file_name as fileName, p_d_debate.description as description, p_d_debate.slug as slug, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, p_d_debate.nb_views as nb_views, COUNT(p_u_follow_d_d.id) as nb_followers, p_d_debate.published_at as published_at, p_d_debate.updated_at as updated_at, 'Politizr\\Model\\PDDebate' as type
FROM p_d_debate
    LEFT JOIN p_u_follow_d_d
        ON p_d_debate.id = p_u_follow_d_d.p_d_debate_id
    LEFT JOIN p_d_d_tagged_t
        ON p_d_debate.id = p_d_d_tagged_t.p_d_debate_id
    # LEFT JOIN p_user
    #     ON p_d_debate.p_user_id = p_user.id
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1 
    AND p_d_d_tagged_t.p_tag_id IN (8, 31)
    # AND p_d_debate.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() 
    # AND p_user.qualified = 1

GROUP BY p_d_debate.id

ORDER BY nb_followers DESC, note_pos DESC, note_neg ASC
