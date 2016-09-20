# delete geo tag
delete from p_tag
where p_t_tag_type_id = 1;

# delete geo tag type
delete from p_t_tag_type
where id = 1;
