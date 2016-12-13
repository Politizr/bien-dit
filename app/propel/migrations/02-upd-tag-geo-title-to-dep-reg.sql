# upd departements' title from tag
update p_l_department
inner join p_tag on p_l_department.p_tag_id = p_tag.id
set p_l_department.title = p_tag.title, p_l_department.slug = p_tag.slug;

# upd regions' title from tag
update p_l_region
inner join p_tag on p_l_region.p_tag_id = p_tag.id
set p_l_region.title = p_tag.title, p_l_region.slug = p_tag.slug;
