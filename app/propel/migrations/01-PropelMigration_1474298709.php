<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1474298709.
 * Generated on 2016-09-19 17:25:09 by lionel
 */
class PropelMigration_1474298709
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

ALTER TABLE `p_l_department`
    ADD `title` VARCHAR(150) AFTER `code`,
    ADD `slug` VARCHAR(255) AFTER `updated_at`;

CREATE UNIQUE INDEX `p_l_department_slug` ON `p_l_department` (`slug`);

ALTER TABLE `p_l_region` DROP FOREIGN KEY `p_l_region_FK_1`;

DROP INDEX `p_l_region_FI_1` ON `p_l_region`;

ALTER TABLE `p_l_region`
    ADD `p_l_country_id` INTEGER NOT NULL AFTER `id`,
    ADD `title` VARCHAR(150) AFTER `p_tag_id`,
    ADD `slug` VARCHAR(255) AFTER `updated_at`;

CREATE INDEX `p_l_region_FI_1` ON `p_l_region` (`p_l_country_id`);

CREATE INDEX `p_l_region_FI_2` ON `p_l_region` (`p_tag_id`);

CREATE UNIQUE INDEX `p_l_region_slug` ON `p_l_region` (`slug`);

ALTER TABLE `p_l_region` ADD CONSTRAINT `p_l_region_FK_1`
    FOREIGN KEY (`p_l_country_id`)
    REFERENCES `p_l_country` (`id`)
    ON UPDATE CASCADE
    ON DELETE CASCADE;

CREATE TABLE `p_l_country`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(150),
    `uuid` VARCHAR(50),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_l_country_U_1` (`uuid`),
    UNIQUE INDEX `p_l_country_slug` (`slug`(255))
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

DROP TABLE IF EXISTS `p_l_country`;

DROP INDEX `acl_object_identity_ancestors_I_2` ON `acl_object_identity_ancestors`;

DROP INDEX `p_l_department_slug` ON `p_l_department`;

ALTER TABLE `p_l_department` DROP `title`;

ALTER TABLE `p_l_department` DROP `slug`;

ALTER TABLE `p_l_region` DROP FOREIGN KEY `p_l_region_FK_1`;

DROP INDEX `p_l_region_FI_2` ON `p_l_region`;

DROP INDEX `p_l_region_slug` ON `p_l_region`;

DROP INDEX `p_l_region_FI_1` ON `p_l_region`;

ALTER TABLE `p_l_region` DROP `p_l_country_id`;

ALTER TABLE `p_l_region` DROP `title`;

ALTER TABLE `p_l_region` DROP `slug`;

CREATE INDEX `p_l_region_FI_1` ON `p_l_region` (`p_tag_id`);

ALTER TABLE `p_l_region` ADD CONSTRAINT `p_l_region_FK_1`
    FOREIGN KEY (`p_tag_id`)
    REFERENCES `p_tag` (`id`)
    ON UPDATE CASCADE
    ON DELETE CASCADE;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}