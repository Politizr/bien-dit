
# Être l’auteur de contenus "débat" ou "réaction" ayant obtenu au moins X réactions
# Compte le nombre de réactions filles
SELECT COUNT(*) as nb
FROM
(
# Liste des réactions filles de 1er niveau pour les réactions d un user (à l'exclusion des réactions rédigées par le user)
SELECT child.id
FROM p_d_reaction parent, p_d_reaction child
WHERE
    parent.p_user_id = 3
    AND child.p_user_id <> 3
    AND child.p_d_debate_id = parent.p_d_debate_id
    AND child.tree_level = parent.tree_level + 1
    AND child.tree_left > parent.tree_left
    AND child.tree_right < parent.tree_right
GROUP BY child.p_d_debate_id

UNION

# Liste des réactions filles de 1er niveau pour les débats d un user (à l'exclusion des réactions rédigées par le user)
SELECT child.id
FROM p_d_debate parent, p_d_reaction child
WHERE
    parent.p_user_id = 3
    AND child.p_user_id <> 3
    AND child.p_d_debate_id = parent.id
    AND child.tree_level = 1
GROUP BY child.p_d_debate_id
) x