#  Débats suivis
( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.published_at as published_at, 'Politizr\\Model\\PDDebate' as type
FROM p_d_debate
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1
    AND p_d_debate.id IN (5,1) )

UNION DISTINCT

#  Réactions aux débats suivis
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\Model\\PDReaction' as type
FROM p_d_reaction
WHERE
	p_d_reaction.published = 1
	AND p_d_reaction.online = 1
	AND p_d_reaction.p_d_debate_id IN (5,1)
	AND p_d_reaction.tree_level > 0 )

UNION DISTINCT

# Débats des users suivis
( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.published_at as published_at, 'Politizr\\Model\\PDDebate' as type
FROM p_d_debate
WHERE
	p_d_debate.published = 1
	AND p_d_debate.online = 1
	AND p_d_debate.p_user_id IN (6,9,60) )

UNION DISTINCT

# Réactions des users suivis
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\Model\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.p_user_id IN (6,9,60) )

UNION DISTINCT

# Réactions sur mes débats
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\Model\\PDReaction' as type
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
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\Model\\PDReaction' as type
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

# Commentaires débats des users suivis
( SELECT p_d_d_comment.id as id, "commentaire" as title, p_d_d_comment.published_at as published_at, 'Politizr\\Model\\PDDComment' as type
FROM p_d_d_comment
WHERE
    p_d_d_comment.online = 1
    AND p_d_d_comment.p_user_id IN (6,9,60) )

UNION DISTINCT

# Commentaires réactions des users suivis
( SELECT p_d_r_comment.id as id, "commentaire" as title, p_d_r_comment.published_at as published_at, 'Politizr\\Model\\PDRComment' as type
FROM p_d_r_comment
WHERE
    p_d_r_comment.online = 1
    AND p_d_r_comment.p_user_id IN (6,9,60) )

UNION DISTINCT

# Commentaires sur mes débats
( SELECT p_d_d_comment.id as id, "commentaire" as title, p_d_d_comment.published_at as published_at, 'Politizr\\Model\\PDDComment' as type
FROM p_d_d_comment
WHERE
	p_d_d_comment.online = 1
	AND p_d_d_comment.p_d_debate_id IN (1, 12, 16) )

UNION DISTINCT

# Commentaires sur mes réactions
( SELECT p_d_r_comment.id as id, "commentaire" as title, p_d_r_comment.published_at as published_at, 'Politizr\\Model\\PDRComment' as type
FROM p_d_r_comment
WHERE
    p_d_r_comment.online = 1
    AND p_d_r_comment.p_d_reaction_id IN (1, 12, 16) )

ORDER BY published_at DESC
