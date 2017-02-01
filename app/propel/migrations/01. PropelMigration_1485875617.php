<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1485875617.
 * Generated on 2017-01-31 16:13:37 by lionel
 */
class PropelMigration_1485875617
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

CREATE UNIQUE INDEX `fos_group_U_1` ON `fos_group` (`name`);

CREATE UNIQUE INDEX `fos_user_U_3` ON `fos_user` (`confirmation_token`);

ALTER TABLE `p_d_debate`
    ADD `p_c_topic_id` INTEGER AFTER `p_l_country_id`;

CREATE INDEX `p_d_debate_FI_6` ON `p_d_debate` (`p_c_topic_id`);

ALTER TABLE `p_d_debate` ADD CONSTRAINT `p_d_debate_FK_6`
    FOREIGN KEY (`p_c_topic_id`)
    REFERENCES `p_c_topic` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

DROP INDEX `p_d_debate_archive_I_6` ON `p_d_debate_archive`;

DROP INDEX `p_d_debate_archive_I_7` ON `p_d_debate_archive`;

ALTER TABLE `p_d_debate_archive`
    ADD `p_c_topic_id` INTEGER AFTER `p_l_country_id`;

CREATE INDEX `p_d_debate_archive_I_6` ON `p_d_debate_archive` (`p_c_topic_id`);

CREATE INDEX `p_d_debate_archive_I_7` ON `p_d_debate_archive` (`uuid`);

CREATE INDEX `p_d_debate_archive_I_8` ON `p_d_debate_archive` (`slug`);

ALTER TABLE `p_d_reaction`
    ADD `p_c_topic_id` INTEGER AFTER `p_l_country_id`;

CREATE INDEX `p_d_reaction_FI_7` ON `p_d_reaction` (`p_c_topic_id`);

ALTER TABLE `p_d_reaction` ADD CONSTRAINT `p_d_reaction_FK_7`
    FOREIGN KEY (`p_c_topic_id`)
    REFERENCES `p_c_topic` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

DROP INDEX `p_d_reaction_archive_I_7` ON `p_d_reaction_archive`;

DROP INDEX `p_d_reaction_archive_I_8` ON `p_d_reaction_archive`;

ALTER TABLE `p_d_reaction_archive`
    ADD `p_c_topic_id` INTEGER AFTER `p_l_country_id`;

CREATE INDEX `p_d_reaction_archive_I_7` ON `p_d_reaction_archive` (`p_c_topic_id`);

CREATE INDEX `p_d_reaction_archive_I_8` ON `p_d_reaction_archive` (`uuid`);

CREATE INDEX `p_d_reaction_archive_I_9` ON `p_d_reaction_archive` (`slug`);

CREATE TABLE `p_circle`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `uuid` VARCHAR(50),
    `title` VARCHAR(100),
    `summary` TEXT,
    `description` LONGTEXT,
    `url` VARCHAR(150),
    `online` TINYINT(1),
    `only_elected` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_circle_U_1` (`uuid`),
    UNIQUE INDEX `p_circle_slug` (`slug`(255))
) ENGINE=InnoDB;

CREATE TABLE `p_c_topic`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `uuid` VARCHAR(50),
    `p_circle_id` INTEGER NOT NULL,
    `title` VARCHAR(100),
    `summary` TEXT,
    `description` LONGTEXT,
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_c_topic_U_1` (`uuid`),
    UNIQUE INDEX `p_c_topic_slug` (`slug`(255)),
    INDEX `p_c_topic_FI_1` (`p_circle_id`),
    CONSTRAINT `p_c_topic_FK_1`
        FOREIGN KEY (`p_circle_id`)
        REFERENCES `p_circle` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `p_c_group_l_c`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_circle_id` INTEGER NOT NULL,
    `p_l_city_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_c_group_l_c_FI_1` (`p_circle_id`),
    INDEX `p_c_group_l_c_FI_2` (`p_l_city_id`),
    CONSTRAINT `p_c_group_l_c_FK_1`
        FOREIGN KEY (`p_circle_id`)
        REFERENCES `p_circle` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_c_group_l_c_FK_2`
        FOREIGN KEY (`p_l_city_id`)
        REFERENCES `p_l_city` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `p_circle_archive`
(
    `id` INTEGER NOT NULL,
    `uuid` VARCHAR(50),
    `title` VARCHAR(100),
    `summary` TEXT,
    `description` LONGTEXT,
    `url` VARCHAR(150),
    `online` TINYINT(1),
    `only_elected` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    `archived_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_circle_archive_I_1` (`uuid`),
    INDEX `p_circle_archive_I_2` (`slug`(255))
) ENGINE=InnoDB;

CREATE TABLE `p_c_topic_archive`
(
    `id` INTEGER NOT NULL,
    `uuid` VARCHAR(50),
    `p_circle_id` INTEGER NOT NULL,
    `title` VARCHAR(100),
    `summary` TEXT,
    `description` LONGTEXT,
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    `archived_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_c_topic_archive_I_1` (`p_circle_id`),
    INDEX `p_c_topic_archive_I_2` (`uuid`),
    INDEX `p_c_topic_archive_I_3` (`slug`(255))
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

DROP TABLE IF EXISTS `p_circle`;

DROP TABLE IF EXISTS `p_c_topic`;

DROP TABLE IF EXISTS `p_c_group_l_c`;

DROP TABLE IF EXISTS `p_circle_archive`;

DROP TABLE IF EXISTS `p_c_topic_archive`;

DROP INDEX `acl_object_identity_ancestors_I_2` ON `acl_object_identity_ancestors`;

DROP INDEX `fos_group_U_1` ON `fos_group`;

DROP INDEX `fos_user_U_3` ON `fos_user`;

ALTER TABLE `p_d_debate` DROP FOREIGN KEY `p_d_debate_FK_6`;

DROP INDEX `p_d_debate_FI_6` ON `p_d_debate`;

ALTER TABLE `p_d_debate` DROP `p_c_topic_id`;

DROP INDEX `p_d_debate_archive_I_8` ON `p_d_debate_archive`;

DROP INDEX `p_d_debate_archive_I_6` ON `p_d_debate_archive`;

DROP INDEX `p_d_debate_archive_I_7` ON `p_d_debate_archive`;

ALTER TABLE `p_d_debate_archive` DROP `p_c_topic_id`;

CREATE INDEX `p_d_debate_archive_I_6` ON `p_d_debate_archive` (`uuid`);

CREATE INDEX `p_d_debate_archive_I_7` ON `p_d_debate_archive` (`slug`);

ALTER TABLE `p_d_reaction` DROP FOREIGN KEY `p_d_reaction_FK_7`;

DROP INDEX `p_d_reaction_FI_7` ON `p_d_reaction`;

ALTER TABLE `p_d_reaction` DROP `p_c_topic_id`;

DROP INDEX `p_d_reaction_archive_I_9` ON `p_d_reaction_archive`;

DROP INDEX `p_d_reaction_archive_I_7` ON `p_d_reaction_archive`;

DROP INDEX `p_d_reaction_archive_I_8` ON `p_d_reaction_archive`;

ALTER TABLE `p_d_reaction_archive` DROP `p_c_topic_id`;

CREATE INDEX `p_d_reaction_archive_I_7` ON `p_d_reaction_archive` (`uuid`);

CREATE INDEX `p_d_reaction_archive_I_8` ON `p_d_reaction_archive` (`slug`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}