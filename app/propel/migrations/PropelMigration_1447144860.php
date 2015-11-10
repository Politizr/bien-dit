<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1447144860.
 * Generated on 2015-11-10 09:41:00 by lionel
 */
class PropelMigration_1447144860
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

CREATE TABLE `p_m_moderation_type`
(
    `id` INTEGER NOT NULL,
    `title` VARCHAR(150),
    `message` TEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `p_m_user_moderated`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER NOT NULL,
    `p_m_moderation_type_id` INTEGER NOT NULL,
    `p_object_name` VARCHAR(150),
    `p_object_id` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_m_user_moderated_FI_1` (`p_user_id`),
    INDEX `p_m_user_moderated_FI_2` (`p_m_moderation_type_id`),
    CONSTRAINT `p_m_user_moderated_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_m_user_moderated_FK_2`
        FOREIGN KEY (`p_m_moderation_type_id`)
        REFERENCES `p_m_moderation_type` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `p_m_user_message`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER,
    `p_object_name` VARCHAR(150),
    `p_object_id` INTEGER,
    `message` TEXT,
    `evol_reput` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_m_user_message_FI_1` (`p_user_id`),
    CONSTRAINT `p_m_user_message_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE `p_m_user_message_archive`
(
    `id` INTEGER NOT NULL,
    `p_user_id` INTEGER,
    `p_object_name` VARCHAR(150),
    `p_object_id` INTEGER,
    `message` TEXT,
    `evol_reput` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `archived_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_m_user_message_archive_I_1` (`p_user_id`)
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

DROP TABLE IF EXISTS `p_m_moderation_type`;

DROP TABLE IF EXISTS `p_m_user_moderated`;

DROP TABLE IF EXISTS `p_m_user_message`;

DROP TABLE IF EXISTS `p_m_user_message_archive`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}