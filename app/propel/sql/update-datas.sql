# MAJ p_document.p_user_id 
# UPDATE `p_document`
# SET `p_user_id` = FLOOR(1 + (RAND() * (10 - 1)))
# WHERE `p_user_id` IS NULL;