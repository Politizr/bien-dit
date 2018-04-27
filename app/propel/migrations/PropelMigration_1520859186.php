<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1520859186.
 * Generated on 2018-03-12 13:53:06 by lionel
 */
class PropelMigration_1520859186
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

ALTER TABLE `p_circle`
    ADD `p_circle_type_id` INTEGER AFTER `p_c_owner_id`;

CREATE INDEX `p_circle_FI_2` ON `p_circle` (`p_circle_type_id`);

ALTER TABLE `p_circle` ADD CONSTRAINT `p_circle_FK_2`
    FOREIGN KEY (`p_circle_type_id`)
    REFERENCES `p_circle_type` (`id`)
    ON UPDATE CASCADE
    ON DELETE CASCADE;

DROP INDEX `p_circle_archive_I_2` ON `p_circle_archive`;

DROP INDEX `p_circle_archive_I_3` ON `p_circle_archive`;

ALTER TABLE `p_circle_archive`
    ADD `p_circle_type_id` INTEGER AFTER `p_c_owner_id`;

CREATE INDEX `p_circle_archive_I_2` ON `p_circle_archive` (`p_circle_type_id`);

CREATE INDEX `p_circle_archive_I_3` ON `p_circle_archive` (`uuid`);

CREATE INDEX `p_circle_archive_I_4` ON `p_circle_archive` (`slug`);

CREATE TABLE `p_circle_type`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(100),
    `summary` TEXT,
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

DROP TABLE IF EXISTS `p_circle_type`;

DROP INDEX `acl_object_identity_ancestors_I_2` ON `acl_object_identity_ancestors`;

ALTER TABLE `p_circle` DROP FOREIGN KEY `p_circle_FK_2`;

DROP INDEX `p_circle_FI_2` ON `p_circle`;

ALTER TABLE `p_circle` DROP `p_circle_type_id`;

DROP INDEX `p_circle_archive_I_4` ON `p_circle_archive`;

DROP INDEX `p_circle_archive_I_2` ON `p_circle_archive`;

DROP INDEX `p_circle_archive_I_3` ON `p_circle_archive`;

ALTER TABLE `p_circle_archive` DROP `p_circle_type_id`;

CREATE INDEX `p_circle_archive_I_2` ON `p_circle_archive` (`uuid`);

CREATE INDEX `p_circle_archive_I_3` ON `p_circle_archive` (`slug`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}