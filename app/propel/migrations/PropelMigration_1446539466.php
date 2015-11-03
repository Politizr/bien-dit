<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1446539466.
 * Generated on 2015-11-03 09:31:06 by lionel
 */
class PropelMigration_1446539466
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

DROP INDEX `p_d_reaction_FI_3` ON `p_d_reaction`;

DROP INDEX `p_d_reaction_archive_I_3` ON `p_d_reaction_archive`;

CREATE INDEX `p_d_reaction_archive_I_3` ON `p_d_reaction_archive` (`slug`);

CREATE TABLE `p_m_ask_for_update`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER,
    `p_object_name` VARCHAR(150),
    `p_object_id` INTEGER,
    `message` TEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_m_ask_for_update_FI_1` (`p_user_id`),
    CONSTRAINT `p_m_ask_for_update_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;

CREATE TABLE `p_m_ask_for_update_archive`
(
    `id` INTEGER NOT NULL,
    `p_user_id` INTEGER,
    `p_object_name` VARCHAR(150),
    `p_object_id` INTEGER,
    `message` TEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `archived_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_m_ask_for_update_archive_I_1` (`p_user_id`)
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

DROP TABLE IF EXISTS `p_m_ask_for_update`;

DROP TABLE IF EXISTS `p_m_ask_for_update_archive`;

CREATE INDEX `p_d_reaction_FI_3` ON `p_d_reaction` (`parent_reaction_id`);

DROP INDEX `p_d_reaction_archive_I_3` ON `p_d_reaction_archive`;

CREATE INDEX `p_d_reaction_archive_I_3` ON `p_d_reaction_archive` (`parent_reaction_id`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}