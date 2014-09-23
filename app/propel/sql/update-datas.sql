# MAJ p_d_debate.p_user_id 
UPDATE `p_d_debate`
SET `p_user_id` = FLOOR(1 + (RAND() * (10 - 1)));

# MAJ p_d_reaction.p_user_id 
UPDATE `p_d_reaction`
SET `p_user_id` = FLOOR(1 + (RAND() * (10 - 1)));
