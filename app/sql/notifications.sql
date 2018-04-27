
# Get distinct [objectName+objectId+authorUserID]' rows from PUNotifications
SELECT DISTINCT p_object_name, p_object_id, p_author_user_id
FROM `p_u_notification` 
WHERE p_u_notification.p_user_id=1 AND (p_u_notification.created_at>='2015-09-08 15:42:00' OR p_u_notification.checked=0) 
ORDER BY p_u_notification.created_at DESC


# Notifications load
SELECT *
FROM `p_u_notification` 
WHERE
p_u_notification.p_user_id=3
AND (p_u_notification.created_at>='2017-09-18 15:29:56' OR p_u_notification.checked=0)
AND p_u_notification.p_notification_id IN (1,2,3,4,5,6,7,8,9,10,17,18,19,20,21,22,23,24,25) 
AND (p_u_notification.p_c_topic_id is NULL OR p_u_notification.p_c_topic_id IN (1,2,3,4,5,6,7,8))

ORDER BY p_u_notification.created_at DESC

# Notifications count
SELECT COUNT(id) as 'nb'
FROM `p_u_notification` 
WHERE
p_u_notification.p_user_id=3
AND p_u_notification.checked=0
AND p_u_notification.p_notification_id IN (1,2,3,4,5,6,7,8,9,10,17,18,19,20,21,22,23,24,25) 
AND (p_u_notification.p_c_topic_id is NULL OR p_u_notification.p_c_topic_id IN (1,2,3,4,5,6,7,8))

ORDER BY p_u_notification.created_at DESC

# Notifications in emailing
SELECT *
FROM p_u_notification 
WHERE
p_u_notification.p_user_id = 3
AND p_u_notification.created_at >= '2015-01-01 18:00'
AND p_u_notification.created_at <= '2017-01-01 18:00'
AND (p_u_notification.p_c_topic_id is NULL OR p_u_notification.p_c_topic_id IN (1,2,3,4,5,6,7,8))

ORDER BY p_u_notification.id ASC, p_u_notification.p_object_name ASC, p_u_notification.p_object_id ASC
