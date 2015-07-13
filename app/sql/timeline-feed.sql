#  Réactions descendantes au débat courant
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.description as description, p_d_reaction.published_at as published_at, 'Politizr\\Model\\PDReaction' as type
FROM p_d_reaction
WHERE
	p_d_reaction.published = 1
	AND p_d_reaction.online = 1
	AND p_d_reaction.p_d_debate_id = 3
	AND p_d_reaction.tree_level > 0
)

UNION DISTINCT

# Commentaires du débat courant des users suivis + ses propres commentaires
( SELECT p_d_d_comment.id as id, "commentaire" as title, p_d_d_comment.description as description, p_d_d_comment.published_at as published_at, 'Politizr\\Model\\PDComment' as type
FROM p_d_d_comment
WHERE
	p_d_d_comment.online = 1
	AND p_d_d_comment.p_d_debate_id = 3
	AND p_d_d_comment.p_user_id IN (73, 36, 42)
)

UNION DISTINCT

# Commentaires sur une des réactions descendantes du débat courant des users suivis + ses propres commentaires
( SELECT p_d_r_comment.id as id, "commentaire" as title, p_d_r_comment.description as description, p_d_r_comment.published_at as published_at, 'Politizr\\Model\\PDComment' as type
FROM p_d_r_comment
WHERE 
	p_d_r_comment.online = 1
	AND p_d_r_comment.p_d_reaction_id IN (
		# Requête "Réactions descendantes au débat courant"
		SELECT p_d_reaction.id as id
		FROM p_d_reaction
		WHERE
			p_d_reaction.published = 1
			AND p_d_reaction.online = 1
			AND p_d_reaction.p_d_debate_id = 3
			AND p_d_reaction.tree_level > 0
			)
			AND p_d_r_comment.p_user_id IN (73, 36, 42)
	)

ORDER BY published_at ASC

