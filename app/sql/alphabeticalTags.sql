SELECT p_tag.id as id, p_tag.uuid as uuid, p_tag.p_t_tag_type_id as p_t_tag_type_id, p_tag.p_t_parent_id as p_t_parent_id, p_tag.p_user_id as p_user_id, p_tag.p_owner_id as p_owner_id, p_tag.title as title, p_tag.moderated as moderated, p_tag.moderated_at as moderated_at, p_tag.online as online, p_tag.created_at as created_at, p_tag.updated_at as updated_at, p_tag.slug as slug 
FROM `p_tag` 
LEFT JOIN `p_d_d_tagged_t` ON (p_tag.id=p_d_d_tagged_t.p_tag_id) 
LEFT JOIN `p_d_debate` ON (p_d_d_tagged_t.p_d_debate_id=p_d_debate.id) 
WHERE p_tag.online=1 AND p_tag.p_t_tag_type_id=2 AND p_d_debate.online=1 AND p_d_debate.published=1

UNION DISTINCT

SELECT p_tag.id as id, p_tag.uuid as uuid, p_tag.p_t_tag_type_id as p_t_tag_type_id, p_tag.p_t_parent_id as p_t_parent_id, p_tag.p_user_id as p_user_id, p_tag.p_owner_id as p_owner_id, p_tag.title as title, p_tag.moderated as moderated, p_tag.moderated_at as moderated_at, p_tag.online as online, p_tag.created_at as created_at, p_tag.updated_at as updated_at, p_tag.slug as slug
FROM `p_tag` 
LEFT JOIN `p_d_r_tagged_t` ON (p_tag.id=p_d_r_tagged_t.p_tag_id) 
LEFT JOIN `p_d_reaction` ON (p_d_r_tagged_t.p_d_reaction_id=p_d_reaction.id) 
WHERE p_tag.online=1 AND p_tag.p_t_tag_type_id=2 AND p_d_reaction.online=1 AND p_d_reaction.published=1 

ORDER BY title ASC