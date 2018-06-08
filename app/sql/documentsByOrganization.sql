#  Débats publiés
( SELECT DISTINCT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.file_name as fileName, p_d_debate.description as description, p_d_debate.slug as slug, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, p_d_debate.nb_views as nb_views, p_d_debate.published_at as published_at, p_d_debate.updated_at as updated_at, 'Politizr\\Model\\PDDebate' as type
FROM p_d_debate
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1 
    AND (p_d_debate.p_c_topic_id is NULL OR p_d_debate.p_c_topic_id IN (1, 2, 3, 4, 5, 6, 7, 8))
    AND p_d_debate.p_user_id IN (
        SELECT p_user.id
        FROM p_user
            LEFT JOIN p_u_current_q_o
                ON p_user.id = p_u_current_q_o.p_user_id
        WHERE
            p_user.qualified = 1
            AND p_u_current_q_o.p_q_organization_id = 9
    )
)

UNION DISTINCT

#  Réactions publiés
( SELECT DISTINCT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.file_name as fileName, p_d_reaction.description as description, p_d_reaction.slug as slug, p_d_reaction.note_pos as note_pos, p_d_reaction.note_neg as note_neg, p_d_reaction.nb_views as nb_views, p_d_reaction.published_at as published_at, p_d_reaction.updated_at as updated_at,'Politizr\\Model\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND (p_d_reaction.p_c_topic_id is NULL OR p_d_reaction.p_c_topic_id IN (1, 2, 3, 4, 5, 6, 7, 8))
    AND p_d_reaction.tree_level > 0
    AND p_d_reaction.p_user_id IN (
        SELECT p_user.id
        FROM p_user
            LEFT JOIN p_u_current_q_o
                ON p_user.id = p_u_current_q_o.p_user_id
        WHERE
            p_user.qualified = 1
            AND p_u_current_q_o.p_q_organization_id = 9
    )
)

UNION DISTINCT

# Commentaires débats publiés
( SELECT DISTINCT p_d_d_comment.id as id, "commentaire" as title, "image" as fileName, p_d_d_comment.description as description, "slug" as slug, p_d_d_comment.note_pos as note_pos, p_d_d_comment.note_neg as note_neg, -1 as nb_views, p_d_d_comment.published_at as published_at, p_d_d_comment.updated_at as updated_at, 'Politizr\\Model\\PDDComment' as type
FROM p_d_d_comment
    LEFT JOIN p_d_debate
        ON p_d_d_comment.p_d_debate_id = p_d_debate.id
WHERE
    p_d_d_comment.online = 1
    AND p_d_debate.published = 1
    AND p_d_debate.online = 1
    AND (p_d_debate.p_c_topic_id is NULL OR p_d_debate.p_c_topic_id IN (1, 2, 3, 4, 5, 6, 7, 8))
    AND p_d_d_comment.p_user_id IN (
        SELECT p_user.id
        FROM p_user
            LEFT JOIN p_u_current_q_o
                ON p_user.id = p_u_current_q_o.p_user_id
        WHERE
            p_user.qualified = 1
            AND p_u_current_q_o.p_q_organization_id = 9
    )
)

UNION DISTINCT

# Commentaires réactions publiés
( SELECT DISTINCT p_d_r_comment.id as id, "commentaire" as title, "image" as fileName, p_d_r_comment.description as description, "slug" as slug, p_d_r_comment.note_pos as note_pos, p_d_r_comment.note_neg as note_neg, -1 as nb_views, p_d_r_comment.published_at as published_at, p_d_r_comment.updated_at as updated_at, 'Politizr\\Model\\PDRComment' as type
FROM p_d_r_comment
    LEFT JOIN p_d_reaction
        ON p_d_r_comment.p_d_reaction_id = p_d_reaction.id
WHERE
    p_d_r_comment.online = 1
    AND p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND (p_d_reaction.p_c_topic_id is NULL OR p_d_reaction.p_c_topic_id IN (1, 2, 3, 4, 5, 6, 7, 8))
    AND p_d_r_comment.p_user_id IN (
        SELECT p_user.id
        FROM p_user
            LEFT JOIN p_u_current_q_o
                ON p_user.id = p_u_current_q_o.p_user_id
        WHERE
            p_user.qualified = 1
            AND p_u_current_q_o.p_q_organization_id = 9
    )
)

# ORDER BY note_pos DESC, note_neg ASC, published_at DESC
ORDER BY published_at DESC, note_pos DESC, note_neg ASC