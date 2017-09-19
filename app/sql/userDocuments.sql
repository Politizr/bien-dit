# DRAFTS
#  Réactions
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.file_name as fileName, p_d_reaction.published_at as published_at, 'Politizr\\Model\\PDReaction' as type
FROM p_d_reaction
WHERE
    p_user_id = 2
    AND p_d_reaction.published = 1
    AND p_d_reaction.online = 1
)

UNION DISTINCT

#  Débats
( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.file_name as fileName, p_d_debate.published_at as published_at, 'Politizr\\Model\\PDDebate' as type
FROM p_d_debate
WHERE
    p_user_id = 2
    AND p_d_debate.published = 1
    AND p_d_debate.online = 1
)

ORDER BY published_at DESC

# BOOKMARKS
#  Débats
( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.file_name as fileName, p_d_debate.published_at as published_at, 'Politizr\\Model\\PDDebate' as type, p_u_bookmark_d_d.created_at as bookmarked_at
FROM p_d_debate
INNER JOIN p_u_bookmark_d_d ON p_u_bookmark_d_d.p_d_debate_id = p_d_debate.id
WHERE
    p_u_bookmark_d_d.p_user_id = 3
    AND p_d_debate.published = 1
    AND p_d_debate.online = 1
)

UNION DISTINCT

#  Réactions
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.file_name as fileName, p_d_reaction.published_at as published_at, 'Politizr\\Model\\PDReaction' as type, p_u_bookmark_d_r.created_at as bookmarked_at
FROM p_d_reaction
INNER JOIN p_u_bookmark_d_r ON p_u_bookmark_d_r.p_d_reaction_id = p_d_reaction.id
WHERE
    p_u_bookmark_d_r.p_user_id = 3
    AND p_d_reaction.published = 1
    AND p_d_reaction.online = 1
)

ORDER BY bookmarked_at DESC

# USER PUBLICATIONS
#  Débats
( SELECT p_d_debate.id as id, p_d_debate.title as title, p_d_debate.file_name as fileName, p_d_debate.description as description, p_d_debate.slug as slug, p_d_debate.published_at as published_at, p_d_debate.updated_at as updated_at, p_d_debate.note_pos as note_pos, p_d_debate.note_neg as note_neg, 'Politizr\\\Model\\\PDDebate' as type
FROM p_d_debate
    LEFT JOIN p_d_d_tagged_t
        ON p_d_debate.id = p_d_d_tagged_t.p_d_debate_id
WHERE
    p_user_id = 1
    AND p_d_debate.published = 1
    AND p_d_debate.online = 1
    AND p_d_debate.p_c_topic_id is NULL OR p_d_debate.p_c_topic_id IN (1, 2, 3, 4, 5, 6, 7, 8)
    AND p_d_d_tagged_t.p_tag_id IN (627, 479, 433, 271, 498, 514, 252, 407, 839, 652, 651)
)

UNION DISTINCT

# Réactions
( SELECT p_d_reaction.id as id, p_d_reaction.title as title, p_d_reaction.file_name as fileName, p_d_reaction.description as description, p_d_reaction.slug as slug, p_d_reaction.published_at as published_at, p_d_reaction.updated_at as updated_at, p_d_reaction.note_pos as note_pos, p_d_reaction.note_neg as note_neg, 'Politizr\\\Model\\\PDReaction' as type
FROM p_d_reaction
    LEFT JOIN p_d_r_tagged_t
        ON p_d_reaction.id = p_d_r_tagged_t.p_d_reaction_id
WHERE
    p_user_id = 1
    AND p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.p_c_topic_id is NULL OR p_d_reaction.p_c_topic_id IN (1, 2, 3, 4, 5, 6, 7, 8)
    AND p_d_r_tagged_t.p_tag_id IN (627, 479, 433, 271, 498, 514, 252, 407, 839, 652, 651)
)

UNION DISTINCT

# Commentaires débats publiés
( SELECT p_d_d_comment.id as id, "commentaire" as title, "image" as fileName, p_d_d_comment.description as description, "slug" as slug, p_d_d_comment.published_at as published_at, p_d_d_comment.updated_at as updated_at, p_d_d_comment.note_pos as note_pos, p_d_d_comment.note_neg as note_neg, 'Politizr\\Model\\PDDComment' as type
FROM p_d_d_comment
    LEFT JOIN p_d_debate
        ON p_d_d_comment.p_d_debate_id = p_d_debate.id
    LEFT JOIN p_d_d_tagged_t
        ON p_d_d_comment.p_d_debate_id = p_d_d_tagged_t.p_d_debate_id
WHERE
    p_d_d_comment.online = 1
    AND p_d_d_comment.p_user_id = 1
    AND p_d_debate.published = 1
    AND p_d_debate.online = 1
    AND p_d_debate.p_c_topic_id is NULL OR p_d_debate.p_c_topic_id IN (1, 2, 3, 4, 5, 6, 7, 8)
    AND p_d_d_tagged_t.p_tag_id IN (627, 479, 433, 271, 498, 514, 252, 407, 839, 652, 651)
)

UNION DISTINCT

# Commentaires réactions publiés
( SELECT p_d_r_comment.id as id, "commentaire" as title, "image" as fileName, p_d_r_comment.description as description, "slug" as slug, p_d_r_comment.published_at as published_at, p_d_r_comment.updated_at as updated_at, p_d_r_comment.note_pos as note_pos, p_d_r_comment.note_neg as note_neg, 'Politizr\\Model\\PDRComment' as type
FROM p_d_r_comment
    LEFT JOIN p_d_reaction
        ON p_d_r_comment.p_d_reaction_id = p_d_reaction.id
    LEFT JOIN p_d_r_tagged_t
        ON p_d_r_comment.p_d_reaction_id = p_d_r_tagged_t.p_d_reaction_id
WHERE
    p_d_r_comment.online = 1
    AND p_d_r_comment.p_user_id = 1
    AND p_d_reaction.published = 1
    AND p_d_reaction.online = 1
    AND p_d_reaction.p_c_topic_id is NULL OR p_d_reaction.p_c_topic_id IN (1, 2, 3, 4, 5, 6, 7, 8)
    AND p_d_r_tagged_t.p_tag_id IN (627, 479, 433, 271, 498, 514, 252, 407, 839, 652, 651)
)

ORDER BY published_at DESC
