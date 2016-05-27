<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1464337784.
 * Generated on 2016-05-27 10:29:44 by lionel
 */
class PropelMigration_1464337784
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

CREATE TABLE `p_m_cgu`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(100),
    `summary` TEXT,
    `description` LONGTEXT,
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `p_m_cgv`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(100),
    `summary` TEXT,
    `description` LONGTEXT,
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `p_m_charte`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(100),
    `summary` TEXT,
    `description` LONGTEXT,
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
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

DROP TABLE IF EXISTS `p_m_cgu`;

DROP TABLE IF EXISTS `p_m_cgv`;

DROP TABLE IF EXISTS `p_m_charte`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}