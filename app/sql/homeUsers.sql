SELECT p_user.id as id
FROM p_user
WHERE
    p_user.online = 1
    AND p_user.homepage = 1

ORDER BY rand()
LIMIT 0, 9