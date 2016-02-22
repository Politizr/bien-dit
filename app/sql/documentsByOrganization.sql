#  Débats publiés
( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, p_d_debate.published_at as published_at, 'Politizr\\Model\\PDDebate' as type
FROM p_d_debate
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1 
    AND p_d_debate.p_user_id IN (
        SELECT p_user.id
        FROM p_user
            LEFT JOIN p_u_current_q_o
                ON p_user.id = p_u_current_q_o.p_user_id
        WHERE
            p_u_current_q_o.p_q_organization_id = 1
    )
    # AND p_d_debate.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() 
)

UNION DISTINCT

#  Réactions publiés
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.note_pos as note_pos, p_d_reaction.note_neg as note_neg, p_d_reaction.published_at as published_at, 'Politizr\\Model\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.tree_level > 0
    AND p_d_reaction.p_user_id IN (
        SELECT p_user.id
        FROM p_user
            LEFT JOIN p_u_current_q_o
                ON p_user.id = p_u_current_q_o.p_user_id
        WHERE
            p_u_current_q_o.p_q_organization_id = 1
    )
    # AND p_d_reaction.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() 
)

ORDER BY note_pos DESC, note_neg ASC
