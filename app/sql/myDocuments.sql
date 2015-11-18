#  Réactions
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\Model\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_user_id = 2
    AND p_d_reaction.published = 1
    AND p_d_reaction.online = 1
)

UNION DISTINCT

#  Débats
( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.published_at as published_at, 'Politizr\\Model\\PDDebate' as type
FROM p_d_debate
WHERE
    p_user_id = 2
    AND p_d_debate.published = 1
    AND p_d_debate.online = 1
)

ORDER BY published_at DESC
