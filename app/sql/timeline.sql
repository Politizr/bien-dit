#  Débats publiés
( SELECT p_d_debate.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, p_d_debate.title as title, p_d_debate.published_at as published_at, 'Politizr\\Model\\PDDebate' as type
FROM p_d_debate
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1
    AND p_d_debate.p_user_id = 1 )

UNION DISTINCT

#  Réactions publiés
( SELECT p_d_reaction.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\Model\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.p_user_id = 1
    AND p_d_reaction.tree_level > 0 )

UNION DISTINCT

#  Débats suivis
( SELECT p_d_debate.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, p_d_debate.title as title, p_d_debate.published_at as published_at, 'Politizr\\Model\\PDDebate' as type
FROM p_d_debate
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1
    AND p_d_debate.id IN (5,1) )

UNION DISTINCT

#  Réactions aux débats suivis
( SELECT p_d_reaction.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\Model\\PDReaction' as type
FROM p_d_reaction
WHERE
	p_d_reaction.published = 1
	AND p_d_reaction.online = 1
	AND p_d_reaction.p_d_debate_id IN (5,1)
	AND p_d_reaction.tree_level > 0 )

UNION DISTINCT

# Débats des users suivis
( SELECT p_d_debate.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, p_d_debate.title as title, p_d_debate.published_at as published_at, 'Politizr\\Model\\PDDebate' as type
FROM p_d_debate
WHERE
	p_d_debate.published = 1
	AND p_d_debate.online = 1
	AND p_d_debate.p_user_id IN (6,9,60) )

UNION DISTINCT

# Réactions des users suivis
( SELECT p_d_reaction.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\Model\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.p_user_id IN (6,9,60) )

UNION DISTINCT

# Réactions sur mes débats
( SELECT p_d_reaction.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\Model\\PDReaction' as type
FROM p_d_reaction
    LEFT JOIN p_d_debate
        ON p_d_reaction.p_d_debate_id = p_d_debate.id
WHERE
	p_d_reaction.published = 1
	AND p_d_reaction.online = 1
	AND p_d_debate.p_user_id = 72
	AND p_d_reaction.tree_level > 0 )

UNION DISTINCT

# Réactions sur mes réactions
( SELECT p_d_reaction.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\Model\\PDReaction' as type
FROM p_d_reaction as p_d_reaction
    LEFT JOIN p_d_reaction as my_reaction
        ON p_d_reaction.p_d_debate_id = my_reaction.p_d_debate_id
WHERE
	p_d_reaction.published = 1
	AND p_d_reaction.online = 1
	AND my_reaction.id IN (12, 16)
  	AND p_d_reaction.tree_left > my_reaction.tree_left
	AND p_d_reaction.tree_left < my_reaction.tree_right
	AND p_d_reaction.tree_level > my_reaction.tree_level
	AND p_d_reaction.tree_level > 1 )

UNION DISTINCT

# Commentaires débats publiés
( SELECT p_d_d_comment.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, "commentaire" as title, p_d_d_comment.published_at as published_at, 'Politizr\\Model\\PDDComment' as type
FROM p_d_d_comment
WHERE
    p_d_d_comment.online = 1
    AND p_d_d_comment.p_user_id = 1 )

UNION DISTINCT

# Commentaires réactions publiés
( SELECT p_d_r_comment.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, "commentaire" as title, p_d_r_comment.published_at as published_at, 'Politizr\\Model\\PDRComment' as type
FROM p_d_r_comment
WHERE
    p_d_r_comment.online = 1
    AND p_d_r_comment.p_user_id = 1 )

UNION DISTINCT

# Commentaires débats des users suivis
( SELECT p_d_d_comment.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, "commentaire" as title, p_d_d_comment.published_at as published_at, 'Politizr\\Model\\PDDComment' as type
FROM p_d_d_comment
WHERE
    p_d_d_comment.online = 1
    AND p_d_d_comment.p_user_id IN (6,9,60) )

UNION DISTINCT

# Commentaires réactions des users suivis
( SELECT p_d_r_comment.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, "commentaire" as title, p_d_r_comment.published_at as published_at, 'Politizr\\Model\\PDRComment' as type
FROM p_d_r_comment
WHERE
    p_d_r_comment.online = 1
    AND p_d_r_comment.p_user_id IN (6,9,60) )

UNION DISTINCT

# Commentaires sur mes débats
( SELECT p_d_d_comment.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, "commentaire" as title, p_d_d_comment.published_at as published_at, 'Politizr\\Model\\PDDComment' as type
FROM p_d_d_comment
WHERE
	p_d_d_comment.online = 1
	AND p_d_d_comment.p_d_debate_id IN (1, 12, 16) )

UNION DISTINCT

# Commentaires sur mes réactions
( SELECT p_d_r_comment.id as id, 'null' as target_id, 'null' as target_user_id, 'null' as target_object_name, "commentaire" as title, p_d_r_comment.published_at as published_at, 'Politizr\\Model\\PDRComment' as type
FROM p_d_r_comment
WHERE
    p_d_r_comment.online = 1
    AND p_d_r_comment.p_d_reaction_id IN (1, 12, 16) )

UNION DISTINCT

#  Actions réputation: note +/- comment / sujet / reponse, suivre un utilisateur, être suivi par un utilisateur
( SELECT p_u_reputation.p_r_action_id as id, p_u_reputation.p_object_id as target_id, 'null' as target_user_id, p_u_reputation.p_object_name as target_object_name, p_r_action.title as title, p_u_reputation.created_at as published_at, 'Politizr\\Model\\PRAction' as type
FROM p_r_action
    LEFT JOIN p_u_reputation
        ON p_r_action.id = p_u_reputation.p_r_action_id

WHERE
    p_u_reputation.p_user_id = 1
    AND p_r_action.id IN (10, 11, 12, 13, 14, 15, 24, 25, 28, 22, 23)
)

UNION DISTINCT

# Actions réputation: recevoir une note + pour son débat, être suivi sur son débat
# /!\ Réaffectation des ids des actions vers les actions "target"
( SELECT 
    CASE p_u_reputation.p_r_action_id
    WHEN 10 THEN 16
    WHEN 22 THEN 26
    ELSE p_u_reputation.p_r_action_id
    END as id,
    p_u_reputation.p_object_id as target_id, p_u_reputation.p_user_id as target_user_id, p_u_reputation.p_object_name as target_object_name, p_r_action.title as title, p_u_reputation.created_at as published_at, 'Politizr\\Model\\PRAction' as type
FROM p_r_action
    LEFT JOIN p_u_reputation
        ON p_r_action.id = p_u_reputation.p_r_action_id

WHERE
   p_u_reputation.p_object_id IN (
        ( SELECT p_d_debate.id as id
        FROM p_d_debate
        WHERE
            p_d_debate.published = 1
            AND p_d_debate.online = 1
            AND p_d_debate.p_user_id = 1 )
    )
    AND p_r_action.id IN (10, 22)
)

UNION DISTINCT

# Actions réputation: recevoir une note + sur sa réaction
# /!\ Réaffectation des ids des actions vers les actions "target"
( SELECT 
    CASE p_u_reputation.p_r_action_id
    WHEN 12 THEN 18
    ELSE p_u_reputation.p_r_action_id
    END as id,
    p_u_reputation.p_object_id as target_id, p_u_reputation.p_user_id as target_user_id, p_u_reputation.p_object_name as target_object_name, p_r_action.title as title, p_u_reputation.created_at as published_at, 'Politizr\\Model\\PRAction' as type
FROM p_r_action
    LEFT JOIN p_u_reputation
        ON p_r_action.id = p_u_reputation.p_r_action_id

WHERE
   p_u_reputation.p_object_id IN (
        ( SELECT p_d_reaction.id as id
        FROM p_d_reaction
        WHERE
            p_d_reaction.published = 1
            AND p_d_reaction.online = 1
            AND p_d_reaction.p_user_id = 1
            AND p_d_reaction.tree_level > 0 )
    )
    AND p_r_action.id IN (12)
)

UNION DISTINCT

# Actions réputation: recevoir une note + sur son commentaire de débat
# /!\ Réaffectation des ids des actions vers les actions "target"
( SELECT 
    CASE p_u_reputation.p_r_action_id
    WHEN 14 THEN 20
    ELSE p_u_reputation.p_r_action_id
    END as id,
    p_u_reputation.p_object_id as target_id, p_u_reputation.p_user_id as target_user_id, p_u_reputation.p_object_name as target_object_name, p_r_action.title as title, p_u_reputation.created_at as published_at, 'Politizr\\Model\\PRAction' as type
FROM p_r_action
    LEFT JOIN p_u_reputation
        ON p_r_action.id = p_u_reputation.p_r_action_id

WHERE
   p_u_reputation.p_object_id IN (
        ( SELECT p_d_d_comment.id as id
        FROM p_d_d_comment
        WHERE
            p_d_d_comment.online = 1
            AND p_d_d_comment.p_user_id = 3
        )
    )
    AND p_u_reputation.p_object_name = 'Politizr\\Model\\PDDComment'
    AND p_r_action.id IN (14)
)

UNION DISTINCT

( SELECT 
    CASE p_u_reputation.p_r_action_id
    WHEN 14 THEN 20
    ELSE p_u_reputation.p_r_action_id
    END as id,
    p_u_reputation.p_object_id as target_id, p_u_reputation.p_user_id as target_user_id, p_u_reputation.p_object_name as target_object_name, p_r_action.title as title, p_u_reputation.created_at as published_at, 'Politizr\\Model\\PRAction' as type
FROM p_r_action
    LEFT JOIN p_u_reputation
        ON p_r_action.id = p_u_reputation.p_r_action_id

WHERE
   p_u_reputation.p_object_id IN (
        ( SELECT p_d_r_comment.id as id
        FROM p_d_r_comment
        WHERE
            p_d_r_comment.online = 1
            AND p_d_r_comment.p_user_id = 3
        )
    )
    AND p_u_reputation.p_object_name = 'Politizr\\Model\\PDRComment'
    AND p_r_action.id IN (14)
)

ORDER BY published_at DESC
