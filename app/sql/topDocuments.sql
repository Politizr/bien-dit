# SIDEBAR
#  Débats publiés
( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, p_d_debate.published_at as published_at, 'Politizr\\Model\\PDDebate' as type
FROM p_d_debate
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1 
    AND p_d_debate.p_c_topic_id is NULL
    AND p_d_debate.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() 
    )

UNION DISTINCT

#  Réactions publiés
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.note_pos as note_pos, p_d_reaction.note_neg as note_neg, p_d_reaction.published_at as published_at, 'Politizr\\Model\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.tree_level > 0
    AND p_d_reaction.p_c_topic_id is NULL
    AND p_d_reaction.published_at BETWEEN DATE_SUB(NOW(), INTERVAL 30 DAY) AND NOW() 
    )

ORDER BY note_pos DESC, note_neg ASC

# TOP LISTING
# listing by day
# par jour => classement par nb d'interactions (note pos - note pos) sur les X derniers jour du jour en cours de sélection 

( SELECT COUNT(p_d_debate.id) as nb_note_pos, p_d_debate.id as id, p_d_debate.title as title, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, p_d_debate.published_at as published_at, 'Politizr\\Model\\PDDebate' as type
FROM p_u_reputation
    LEFT JOIN p_d_debate as p_d_debate
        ON p_u_reputation.p_object_id = p_d_debate.id
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1 
    AND p_d_debate.p_c_topic_id is NULL
    AND (p_d_debate.note_pos - p_d_debate.note_neg) > 0
    AND p_u_reputation.p_r_action_id = 10
    # AND p_u_reputation.created_at BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND NOW() 
    AND p_u_reputation.created_at BETWEEN LAST_DAY(DATE_SUB('2015-12-15', INTERVAL 1 MONTH)) AND LAST_DAY('2015-12-15')

GROUP BY id )

UNION DISTINCT

( SELECT COUNT(p_d_reaction.id) as nb_note_pos, p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.note_pos as note_pos, p_d_reaction.note_neg as note_neg, p_d_reaction.published_at as published_at, 'Politizr\\Model\\PDReaction' as type
FROM p_u_reputation
    LEFT JOIN p_d_reaction as p_d_reaction
        ON p_u_reputation.p_object_id = p_d_reaction.id
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.tree_level > 0
    AND p_d_reaction.p_c_topic_id is NULL
    AND (p_d_reaction.note_pos - p_d_reaction.note_neg) > 0
    AND p_u_reputation.p_r_action_id = 12
    # AND p_u_reputation.created_at BETWEEN DATE_SUB(NOW(), INTERVAL 60 DAY) AND NOW() 
    AND p_u_reputation.created_at BETWEEN LAST_DAY(DATE_SUB('2015-12-15', INTERVAL 1 MONTH)) AND LAST_DAY('2015-12-15')

GROUP BY id )

ORDER BY nb_note_pos DESC, note_pos DESC, note_neg ASC

# LAST PUBLISHED TOP LISTING
( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.published_at as published_at, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1 
    AND p_d_debate.p_c_topic_id is NULL
    )

UNION DISTINCT

( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.tree_level > 0
    AND p_d_reaction.p_c_topic_id is NULL
    )

ORDER BY published_at DESC

LIMIT 10