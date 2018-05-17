<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1526533791.
 * Generated on 2018-05-17 07:09:51 by lionel
 */
class PropelMigration_1526533791
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

CREATE TABLE `se_media_object`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `object_id` INTEGER NOT NULL,
    `object_classname` VARCHAR(250) NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `object_classname` (`object_classname`)
) ENGINE=InnoDB;

CREATE TABLE `se_media_file`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `category_id` INTEGER DEFAULT 1 NOT NULL,
    `name` VARCHAR(250),
    `extension` VARCHAR(10),
    `type` VARCHAR(250),
    `mime_type` VARCHAR(250),
    `size` INTEGER,
    `height` INTEGER,
    `width` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

CREATE TABLE `se_object_has_file`
(
    `se_media_object_id` INTEGER NOT NULL,
    `se_media_file_id` INTEGER NOT NULL,
    `sortable_rank` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`se_media_object_id`,`se_media_file_id`),
    INDEX `se_object_has_file_FI_2` (`se_media_file_id`),
    CONSTRAINT `se_object_has_file_FK_1`
        FOREIGN KEY (`se_media_object_id`)
        REFERENCES `se_media_object` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `se_object_has_file_FK_2`
        FOREIGN KEY (`se_media_file_id`)
        REFERENCES `se_media_file` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `se_media_file_i18n`
(
    `id` INTEGER NOT NULL,
    `locale` VARCHAR(5) DEFAULT \'fr\' NOT NULL,
    `title` VARCHAR(250),
    `description` TEXT,
    `copyright` TEXT,
    `online` TINYINT(1),
    PRIMARY KEY (`id`,`locale`),
    CONSTRAINT `se_media_file_i18n_FK_1`
        FOREIGN KEY (`id`)
        REFERENCES `se_media_file` (`id`)
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

DROP TABLE IF EXISTS `se_media_object`;

DROP TABLE IF EXISTS `se_media_file`;

DROP TABLE IF EXISTS `se_object_has_file`;

DROP TABLE IF EXISTS `se_media_file_i18n`;

DROP INDEX `acl_object_identity_ancestors_I_2` ON `acl_object_identity_ancestors`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}