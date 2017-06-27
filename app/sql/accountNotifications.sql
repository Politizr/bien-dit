# Sélection des publications par user possédant des réactions ou commentaires publiés durant une période donnée, triés par nb réactions > nb commentaires > note + > note - 

#  Sujets publiés
( SELECT DISTINCT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.file_name as fileName, p_d_debate.description as description, p_d_debate.slug as slug, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, COUNT(distinct p_d_reaction_child.id) as nb_reactions, COUNT(distinct p_d_comment_child.id) as nb_comments, p_d_debate.published_at as published_at, p_d_debate.updated_at as updated_at, 'Politizr\\Model\\PDDebate' as type
FROM p_d_debate
    LEFT JOIN p_d_reaction as p_d_reaction_child
        ON p_d_debate.id = p_d_reaction_child.p_d_debate_id
        AND p_d_reaction_child.published = 1
        AND p_d_reaction_child.online = 1
        AND p_d_reaction_child.published_at > '2008-01-01 00:00:00'
        AND p_d_reaction_child.published_at < '2018-01-01 00:00:00'
    LEFT JOIN p_d_d_comment as p_d_comment_child
        ON p_d_debate.id = p_d_comment_child.p_d_debate_id
        AND p_d_comment_child.online = 1
        AND p_d_comment_child.created_at > '2008-01-01 00:00:00'
        AND p_d_comment_child.created_at > '2018-01-01 00:00:00'
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1
    AND p_d_debate.p_user_id = 3

GROUP BY id
HAVING nb_reactions > 0 OR nb_comments > 0
)

UNION DISTINCT

#  Réactions publiées
( SELECT DISTINCT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.file_name as fileName, p_d_reaction.description as description, p_d_reaction.slug as slug, p_d_reaction.note_pos as note_pos, p_d_reaction.note_neg as note_neg, COUNT(distinct p_d_reaction_child.id) as nb_reactions, COUNT(distinct p_d_comment_child.id) as nb_comments, p_d_reaction.published_at as published_at, p_d_reaction.updated_at as updated_at,'Politizr\\Model\\PDReaction' as type
FROM p_d_reaction
    LEFT JOIN p_d_reaction as p_d_reaction_child
        ON p_d_reaction.id = p_d_reaction_child.parent_reaction_id
        AND p_d_reaction_child.published = 1
        AND p_d_reaction_child.online = 1
        AND p_d_reaction_child.published_at > '2008-01-01 00:00:00'
        AND p_d_reaction_child.published_at > '2018-01-01 00:00:00'
    LEFT JOIN p_d_r_comment as p_d_comment_child
        ON p_d_reaction.id = p_d_comment_child.p_d_reaction_id
        AND p_d_comment_child.online = 1
        AND p_d_comment_child.created_at > '2008-01-01 00:00:00'
        AND p_d_comment_child.created_at > '2008-18-01 00:00:00'
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.tree_level > 0
    AND p_d_reaction.p_user_id = 3

GROUP BY id
HAVING nb_reactions > 0 OR nb_comments > 0
)

ORDER BY nb_reactions DESC, nb_comments DESC, note_pos DESC, note_neg ASC

LIMIT 5



# Sélection des publications par user possédant le plus de note + durant une période donnée, triés par note + 

# Sujets publiés
( SELECT DISTINCT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.file_name as fileName, p_d_debate.description as description, p_d_debate.slug as slug, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, COUNT(distinct p_u_notification.id) as nb_notifications, p_d_debate.published_at as published_at, p_d_debate.updated_at as updated_at, 'Politizr\\Model\\PDDebate' as type
FROM p_d_debate
    LEFT JOIN p_u_notification as p_u_notification
        ON p_d_debate.id = p_u_notification.p_object_id

WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1
    AND p_d_debate.p_user_id = 3
    AND p_u_notification.p_notification_id = 2
    AND p_u_notification.p_object_name = 'Politizr\\Model\\PDDebate'
    AND p_u_notification.created_at > '2008-01-01 00:00:00'
    AND p_u_notification.created_at < '2018-01-01 00:00:00'

GROUP BY id
)


UNION DISTINCT


# Réactions publiés
( SELECT DISTINCT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.file_name as fileName, p_d_reaction.description as description, p_d_reaction.slug as slug, p_d_reaction.note_pos as note_pos, p_d_reaction.note_neg as note_neg, COUNT(distinct p_u_notification.id) as nb_notifications, p_d_reaction.published_at as published_at, p_d_reaction.updated_at as updated_at,'Politizr\\Model\\PDReaction' as type
FROM p_d_reaction
    LEFT JOIN p_u_notification as p_u_notification
        ON p_d_reaction.id = p_u_notification.p_object_id

WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.tree_level > 0
    AND p_d_reaction.p_user_id = 3
    AND p_u_notification.p_notification_id = 2
    AND p_u_notification.p_object_name = 'Politizr\\Model\\PDReaction'
    AND p_u_notification.created_at > '2008-01-01 00:00:00'
    AND p_u_notification.created_at < '2008-01-01 00:00:00'

GROUP BY id
)

ORDER BY nb_notifications DESC

LIMIT 5
