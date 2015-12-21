
# Get distinct [objectName+objectId+authorUserID]' rows from PUNotifications
SELECT DISTINCT p_object_name, p_object_id, p_author_user_id
FROM `p_u_notification` 
WHERE p_u_notification.p_user_id=1 AND (p_u_notification.created_at>='2015-09-08 15:42:00' OR p_u_notification.checked=0) 
ORDER BY p_u_notification.created_at DESC
