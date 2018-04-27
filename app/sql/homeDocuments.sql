#  Débats publiés
( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.published_at as published_at, 'Politizr\\Model\\PDDebate' as type
FROM p_d_debate
WHERE
    p_d_debate.published = 1
    AND p_d_debate.online = 1 
    AND p_d_debate.p_c_topic_id is NULL
    AND p_d_debate.homepage = 1 
)

UNION DISTINCT

#  Réactions publiés
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.published_at as published_at, 'Politizr\\Model\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.p_c_topic_id is NULL
    AND p_d_reaction.homepage = 1
    AND p_d_reaction.tree_level > 0
)

ORDER BY rand()
LIMIT 0, 9