# DRAFTS
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

# USER PUBLICATIONS
# Réactions
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.published_at as published_at, p_d_reaction.updated_at as updated_at, p_d_reaction.note_pos as note_pos, p_d_reaction.note_neg as note_neg, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_user_id = 1
    AND p_d_reaction.published = 1
    AND p_d_reaction.online = 1
)

UNION DISTINCT

#  Débats
( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.published_at as published_at, p_d_debate.updated_at as updated_at, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
WHERE
    p_user_id = 1
    AND p_d_debate.published = 1
    AND p_d_debate.online = 1
)

UNION DISTINCT

# Commentaires débats publiés
( SELECT p_d_d_comment.id as id, "commentaire" as title, p_d_d_comment.published_at as published_at, p_d_d_comment.updated_at as updated_at, p_d_d_comment.note_pos as note_pos, p_d_d_comment.note_neg as note_neg, 'Politizr\\Model\\PDDComment' as type
FROM p_d_d_comment
WHERE
    p_d_d_comment.online = 1
    AND p_d_d_comment.p_user_id = 1 )

UNION DISTINCT

# Commentaires réactions publiés
( SELECT p_d_r_comment.id as id, "commentaire" as title, p_d_r_comment.published_at as published_at, p_d_r_comment.updated_at as updated_at, p_d_r_comment.note_pos as note_pos, p_d_r_comment.note_neg as note_neg, 'Politizr\\Model\\PDRComment' as type
FROM p_d_r_comment
WHERE
    p_d_r_comment.online = 1
    AND p_d_r_comment.p_user_id = 1 )

ORDER BY published_at DESC
