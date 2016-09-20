# fill country table
insert into p_l_country(`title`, `slug`, `created_at`, `updated_at`) values ('France', 'france', NOW(), NOW());

# cmd symfony uuid
# app/console politizr:uuids:populate PLCountry

# upd region table
update p_l_region
set p_l_country_id = 1;