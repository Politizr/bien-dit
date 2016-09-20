#  Débats publiés
( SELECT DISTINCT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.file_name as fileName, p_d_debate.description as description, p_d_debate.slug as slug, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, COUNT(p_d_d_comment.id) as nb_comments, p_d_debate.published_at as published_at, p_d_debate.updated_at as updated_at, 'Politizr\\Model\\PDDebate' as type
FROM p_d_debate
    LEFT JOIN p_d_d_comment
        ON p_d_debate.id = p_d_d_comment.p_d_debate_id
    LEFT JOIN p_user
        ON p_d_debate.p_user_id = p_user.id
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1
    AND p_d_d_comment.online = true
    AND p_d_debate.p_l_city_id IN (1,2,3)
    OR p_d_debate.p_l_department_id IN (1,2,3) 
    OR p_d_debate.p_l_region_id = 1
    OR p_d_debate.p_l_country_id = 1
    AND p_user.qualified = 1
    # AND p_d_debate.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() 

GROUP BY id
)

UNION DISTINCT

#  Réactions publiés
( SELECT DISTINCT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.file_name as fileName, p_d_reaction.description as description, p_d_reaction.slug as slug, p_d_reaction.note_pos as note_pos, p_d_reaction.note_neg as note_neg, COUNT(p_d_r_comment.id) as nb_comments, p_d_reaction.published_at as published_at, p_d_reaction.updated_at as updated_at,'Politizr\\Model\\PDReaction' as type
FROM p_d_reaction
    LEFT JOIN p_d_r_comment
        ON p_d_reaction.id = p_d_r_comment.p_d_reaction_id
    LEFT JOIN p_user
        ON p_d_reaction.p_user_id = p_user.id
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_r_comment.online = true
    AND p_d_reaction.p_l_city_id IN (1,2,3)
    OR p_d_reaction.p_l_department_id IN (1,2,3) 
    OR p_d_reaction.p_l_region_id = 1
    OR p_d_reaction.p_l_country_id = 1
    AND p_user.qualified = 1
    # AND p_d_reaction.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() 

GROUP BY id
)

ORDER BY nb_comments DESC, note_pos DESC, note_neg ASC
