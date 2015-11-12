<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1447315320.
 * Generated on 2015-11-12 09:02:00 by lionel
 */
class PropelMigration_1447315320
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

CREATE TABLE `p_m_user_historic`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER,
    `file_name` VARCHAR(150),
    `back_file_name` VARCHAR(150),
    `copyright` TEXT,
    `subtitle` TEXT,
    `biography` TEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_m_user_historic_FI_1` (`p_user_id`),
    CONSTRAINT `p_m_user_historic_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE `p_m_debate_historic`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_d_debate_id` INTEGER,
    `file_name` VARCHAR(150),
    `title` VARCHAR(100),
    `description` LONGTEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_m_debate_historic_FI_1` (`p_d_debate_id`),
    CONSTRAINT `p_m_debate_historic_FK_1`
        FOREIGN KEY (`p_d_debate_id`)
        REFERENCES `p_d_debate` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE `p_m_reaction_historic`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_d_reaction_id` INTEGER,
    `file_name` VARCHAR(150),
    `title` VARCHAR(100),
    `description` LONGTEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_m_reaction_historic_FI_1` (`p_d_reaction_id`),
    CONSTRAINT `p_m_reaction_historic_FK_1`
        FOREIGN KEY (`p_d_reaction_id`)
        REFERENCES `p_d_reaction` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE `p_m_d_comment_historic`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_d_d_comment_id` INTEGER,
    `description` TEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_m_d_comment_historic_FI_1` (`p_d_d_comment_id`),
    CONSTRAINT `p_m_d_comment_historic_FK_1`
        FOREIGN KEY (`p_d_d_comment_id`)
        REFERENCES `p_d_d_comment` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE `p_m_r_comment_historic`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_d_r_comment_id` INTEGER,
    `description` TEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_m_r_comment_historic_FI_1` (`p_d_r_comment_id`),
    CONSTRAINT `p_m_r_comment_historic_FK_1`
        FOREIGN KEY (`p_d_r_comment_id`)
        REFERENCES `p_d_r_comment` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
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

DROP TABLE IF EXISTS `p_m_user_historic`;

DROP TABLE IF EXISTS `p_m_debate_historic`;

DROP TABLE IF EXISTS `p_m_reaction_historic`;

DROP TABLE IF EXISTS `p_m_d_comment_historic`;

DROP TABLE IF EXISTS `p_m_r_comment_historic`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}