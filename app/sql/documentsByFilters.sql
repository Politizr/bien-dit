#  Débats publiés
( SELECT DISTINCT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.file_name as fileName, p_d_debate.description as description, p_d_debate.slug as slug, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, p_d_debate.nb_views as nb_views, p_d_debate.published_at as published_at, p_d_debate.updated_at as updated_at, 'Politizr\\Model\\PDDebate' as type
FROM p_d_debate
    LEFT JOIN p_user
        ON p_d_debate.p_user_id = p_user.id
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1 
    AND p_d_debate.p_l_city_id IN (1,2,3)
    OR p_d_debate.p_l_department_id IN (1,2,3) 
    OR p_d_debate.p_l_region_id = 1 
    OR p_d_debate.p_l_country_id = 1 
    # AND p_user.qualified = 1
    # AND p_d_debate.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() 
)

UNION DISTINCT

#  Réactions publiés
( SELECT DISTINCT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.file_name as fileName, p_d_reaction.description as description, p_d_reaction.slug as slug, p_d_reaction.note_pos as note_pos, p_d_reaction.note_neg as note_neg, p_d_reaction.nb_views as nb_views, p_d_reaction.published_at as published_at, p_d_reaction.updated_at as updated_at,'Politizr\\Model\\PDReaction' as type
FROM p_d_reaction
    LEFT JOIN p_user
        ON p_d_reaction.p_user_id = p_user.id
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.tree_level > 0
    AND p_d_reaction.p_l_city_id IN (1,2,3)
    OR p_d_reaction.p_l_department_id IN (1,2,3) 
    OR p_d_reaction.p_l_region_id = 1 
    OR p_d_reaction.p_l_country_id = 1 
    # AND p_user.qualified = 1
    # AND p_d_reaction.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() 
)

UNION DISTINCT

# Commentaires débats publiés
( SELECT DISTINCT p_d_d_comment.id as id, "commentaire" as title, "image" as fileName, p_d_d_comment.description as description, "slug" as slug, p_d_d_comment.note_pos as note_pos, p_d_d_comment.note_neg as note_neg, -1 as nb_views, p_d_d_comment.published_at as published_at, p_d_d_comment.updated_at as updated_at, 'Politizr\\Model\\PDDComment' as type
FROM p_d_d_comment
    LEFT JOIN p_d_debate
        ON p_d_d_comment.p_d_debate_id = p_d_debate.id
    LEFT JOIN p_user
        ON p_user.id = p_d_d_comment.p_user_id
WHERE
    p_d_d_comment.online = 1
    AND p_d_debate.p_l_city_id IN (1,2,3)
    OR p_d_debate.p_l_department_id IN (1,2,3) 
    OR p_d_debate.p_l_region_id = 1
    OR p_d_debate.p_l_country_id = 1
    # AND p_d_d_tagged_t.p_tag_id IN (8, 31)
    # AND p_user.qualified = 1
    # AND p_d_d_comment.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() 
)

UNION DISTINCT

# Commentaires réactions publiés
( SELECT DISTINCT p_d_r_comment.id as id, "commentaire" as title, "image" as fileName, p_d_r_comment.description as description, "slug" as slug, p_d_r_comment.note_pos as note_pos, p_d_r_comment.note_neg as note_neg, -1 as nb_views, p_d_r_comment.published_at as published_at, p_d_r_comment.updated_at as updated_at, 'Politizr\\Model\\PDRComment' as type
FROM p_d_r_comment
    LEFT JOIN p_d_reaction
        ON p_d_r_comment.p_d_reaction_id = p_d_reaction.id
    LEFT JOIN p_user
        ON p_user.id = p_d_r_comment.p_user_id
WHERE
    p_d_r_comment.online = 1
    AND p_d_reaction.p_l_city_id IN (1,2,3)
    OR p_d_reaction.p_l_department_id IN (1,2,3) 
    OR p_d_reaction.p_l_region_id = 1
    OR p_d_reaction.p_l_country_id = 1
    # AND p_d_r_tagged_t.p_tag_id IN (8, 31)
    # AND p_user.qualified = 1
    # AND p_d_r_comment.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() 
)


# ORDER BY note_pos DESC, note_neg ASC
ORDER BY nb_views DESC
