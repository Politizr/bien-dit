# Débats rédigés
( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.published_at as published_at, 'Politizr\\Model\\PDDebate' as type
FROM p_d_debate
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1
    AND p_d_debate.p_user_id = 2 )

UNION DISTINCT

#  Réactions rédigées
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\Model\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.tree_level > 0
    AND p_d_reaction.p_user_id = 2 )

UNION DISTINCT

# Commentaires débats rédigés
( SELECT p_d_d_comment.id as id, "commentaire" as title, p_d_d_comment.published_at as published_at, 'Politizr\\Model\\PDDComment' as type
FROM p_d_d_comment
WHERE
    p_d_d_comment.online = 1
    AND p_d_d_comment.p_user_id = 2 )

UNION DISTINCT

# Commentaires réactions rédigés
( SELECT p_d_r_comment.id as id, "commentaire" as title, p_d_r_comment.published_at as published_at, 'Politizr\\Model\\PDRComment' as type
FROM p_d_r_comment
WHERE
    p_d_r_comment.online = 1
    AND p_d_r_comment.p_user_id = 2 )

ORDER BY published_at DESC
