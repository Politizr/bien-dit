<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1498210744.
 * Generated on 2017-06-23 11:39:04 by lionel
 */
class PropelMigration_1498210744
{

    public function preUp($manager)
    {
        // add the pre-migration code here
    }

    public function postUp($manager)
    {
        // add the post-migration code here
    }

    public function preDown($manager)
    {
        // add the pre-migration code here
    }

    public function postDown($manager)
    {
        // add the post-migration code here
    }

    /**
     * Get the SQL statements for the Up migration
     *
     * @return array list of the SQL strings to execute for the Up migration
     *               the keys being the datasources
     */
    public function getUpSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `p_u_subscribe_email`;

DROP TABLE IF EXISTS `p_u_subscribe_screen`;

CREATE INDEX `acl_object_identity_ancestors_I_2` ON `acl_object_identity_ancestors` (`ancestor_id`);

CREATE TABLE `p_n_email`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `uuid` VARCHAR(50),
    `title` VARCHAR(250),
    `description` TEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_n_email_U_1` (`uuid`)
) ENGINE=InnoDB;

CREATE TABLE `p_u_subscribe_p_n_e`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER NOT NULL,
    `p_n_email_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_subscribe_p_n_e_FI_1` (`p_user_id`),
    INDEX `p_u_subscribe_p_n_e_FI_2` (`p_n_email_id`),
    CONSTRAINT `p_u_subscribe_p_n_e_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_subscribe_p_n_e_FK_2`
        FOREIGN KEY (`p_n_email_id`)
        REFERENCES `p_n_email` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

    /**
     * Get the SQL statements for the Down migration
     *
     * @return array list of the SQL strings to execute for the Down migration
     *               the keys being the datasources
     */
    public function getDownSQL()
    {
        return array (
  'default' => '
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

DROP TABLE IF EXISTS `p_n_email`;

DROP TABLE IF EXISTS `p_u_subscribe_p_n_e`;

DROP INDEX `acl_object_identity_ancestors_I_2` ON `acl_object_identity_ancestors`;

CREATE TABLE `p_u_subscribe_email`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER NOT NULL,
    `p_notification_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_subscribe_email_FI_1` (`p_user_id`),
    INDEX `p_u_subscribe_email_FI_2` (`p_notification_id`),
    CONSTRAINT `p_u_subscribe_email_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_subscribe_email_FK_2`
        FOREIGN KEY (`p_notification_id`)
        REFERENCES `p_notification` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `p_u_subscribe_screen`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER NOT NULL,
    `p_notification_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_subscribe_screen_FI_1` (`p_user_id`),
    INDEX `p_u_subscribe_screen_FI_2` (`p_notification_id`),
    CONSTRAINT `p_u_subscribe_screen_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_subscribe_screen_FK_2`
        FOREIGN KEY (`p_notification_id`)
        REFERENCES `p_notification` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}