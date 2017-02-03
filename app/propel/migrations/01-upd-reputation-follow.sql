# Mise à 0 du nb de points lié au follow/unfollow d'un débat
UPDATE `p_r_action` SET `score_evolution`=0, `updated_at`='2017-02-03 12:16:50' 
WHERE p_r_action.id=26;

UPDATE `p_r_action` SET `score_evolution`=0, `updated_at`='2017-02-03 12:18:33' 
WHERE p_r_action.id=27;

UPDATE `p_r_action` SET `score_evolution`=0, `updated_at`='2017-02-03 12:19:48' 
WHERE p_r_action.id=23;
