#  Brouillons de réactions
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.updated_at as updated_at, 'Politizr\\Model\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_user_id = 2
    AND p_d_reaction.published = 1
    AND p_d_reaction.online = 1
)

UNION DISTINCT

#  Brouillons de débats
( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.updated_at as updated_at, 'Politizr\\Model\\PDDebate' as type
FROM p_d_debate
WHERE
    p_user_id = 2
    AND p_d_debate.published = 1
    AND p_d_debate.online = 1
)

ORDER BY updated_at DESC
