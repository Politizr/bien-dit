# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

ALTER TABLE `p_d_debate` DROP FOREIGN KEY `p_d_debate_FK_6`;

DROP INDEX `p_d_debate_FI_6` ON `p_d_debate`;

ALTER TABLE `p_d_debate`
    ADD `p_c_topic_id` INTEGER AFTER `p_l_country_id`;

CREATE INDEX `p_d_debate_FI_6` ON `p_d_debate` (`p_c_topic_id`);

CREATE INDEX `p_d_debate_FI_7` ON `p_d_debate` (`p_e_operation_id`);

ALTER TABLE `p_d_debate` ADD CONSTRAINT `p_d_debate_FK_6`
    FOREIGN KEY (`p_c_topic_id`)
    REFERENCES `p_c_topic` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

DROP INDEX `p_d_debate_archive_I_6` ON `p_d_debate_archive`;

DROP INDEX `p_d_debate_archive_I_7` ON `p_d_debate_archive`;

DROP INDEX `p_d_debate_archive_I_8` ON `p_d_debate_archive`;

ALTER TABLE `p_d_debate_archive`
    ADD `p_c_topic_id` INTEGER AFTER `p_l_country_id`;

CREATE INDEX `p_d_debate_archive_I_6` ON `p_d_debate_archive` (`p_c_topic_id`);

CREATE INDEX `p_d_debate_archive_I_7` ON `p_d_debate_archive` (`p_e_operation_id`);

CREATE INDEX `p_d_debate_archive_I_8` ON `p_d_debate_archive` (`uuid`);

CREATE INDEX `p_d_debate_archive_I_9` ON `p_d_debate_archive` (`slug`);

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


ALTER TABLE `p_d_debate` ADD CONSTRAINT `p_d_debate_FK_7`
    FOREIGN KEY (`p_e_operation_id`)
    REFERENCES `p_e_operation` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

CREATE TABLE `p_u_in_p_c`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_circle_id` INTEGER NOT NULL,
    `p_user_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_in_p_c_FI_1` (`p_circle_id`),
    INDEX `p_u_in_p_c_FI_2` (`p_user_id`),
    CONSTRAINT `p_u_in_p_c_FK_1`
        FOREIGN KEY (`p_circle_id`)
        REFERENCES `p_circle` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_in_p_c_FK_2`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

ALTER TABLE `p_c_topic`
    ADD `force_geoloc_type` VARCHAR(100) AFTER `online`,
    ADD `force_geoloc_id` INTEGER AFTER `force_geoloc_type`;

ALTER TABLE `p_c_topic_archive`
    ADD `force_geoloc_type` VARCHAR(100) AFTER `online`,
    ADD `force_geoloc_id` INTEGER AFTER `force_geoloc_type`;

ALTER TABLE `p_u_in_p_c`
    ADD `is_authorized_reaction` TINYINT(1) DEFAULT 0 AFTER `p_user_id`;

ALTER TABLE `p_u_notification`
    ADD `p_c_topic_id` INTEGER AFTER `p_author_user_id`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;