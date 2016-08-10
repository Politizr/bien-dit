<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1470667086.
 * Generated on 2016-08-08 16:38:06 by lionel
 */
class PropelMigration_1470667086
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

CREATE INDEX `acl_object_identity_ancestors_I_2` ON `acl_object_identity_ancestors` (`ancestor_id`);

CREATE TABLE `p_l_region`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_tag_id` INTEGER NOT NULL,
    `uuid` VARCHAR(50),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_l_region_U_1` (`uuid`),
    INDEX `p_l_region_FI_1` (`p_tag_id`),
    CONSTRAINT `p_l_region_FK_1`
        FOREIGN KEY (`p_tag_id`)
        REFERENCES `p_tag` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `p_l_department`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_l_region_id` INTEGER NOT NULL,
    `p_tag_id` INTEGER NOT NULL,
    `code` VARCHAR(10),
    `uuid` VARCHAR(50),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_l_department_U_1` (`uuid`),
    INDEX `p_l_department_FI_1` (`p_l_region_id`),
    INDEX `p_l_department_FI_2` (`p_tag_id`),
    CONSTRAINT `p_l_department_FK_1`
        FOREIGN KEY (`p_l_region_id`)
        REFERENCES `p_l_region` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_l_department_FK_2`
        FOREIGN KEY (`p_tag_id`)
        REFERENCES `p_tag` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

CREATE TABLE `p_l_city`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_l_department_id` INTEGER,
    `name` VARCHAR(150),
    `name_simple` VARCHAR(150),
    `name_real` VARCHAR(150),
    `name_soundex` VARCHAR(150),
    `name_metaphone` VARCHAR(150),
    `zipcode` VARCHAR(150),
    `municipality` VARCHAR(150),
    `municipality_code` VARCHAR(10),
    `district` INTEGER,
    `canton` VARCHAR(10),
    `amdi` INTEGER,
    `nb_people_2010` INTEGER,
    `nb_people_1999` INTEGER,
    `nb_people_2012` INTEGER,
    `density_2010` INTEGER,
    `surface` FLOAT,
    `longitude_deg` FLOAT,
    `latitude_deg` FLOAT,
    `longitude_grd` VARCHAR(10),
    `latitude_grd` VARCHAR(10),
    `longitude_dms` VARCHAR(10),
    `latitude_dms` VARCHAR(10),
    `zmin` INTEGER,
    `zmax` INTEGER,
    `uuid` VARCHAR(50),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_l_city_U_1` (`uuid`),
    UNIQUE INDEX `p_l_city_slug` (`slug`(255)),
    INDEX `p_l_city_FI_1` (`p_l_department_id`),
    CONSTRAINT `p_l_city_FK_1`
        FOREIGN KEY (`p_l_department_id`)
        REFERENCES `p_l_department` (`id`)
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

DROP TABLE IF EXISTS `p_l_region`;

DROP TABLE IF EXISTS `p_l_department`;

DROP TABLE IF EXISTS `p_l_city`;

DROP INDEX `acl_object_identity_ancestors_I_2` ON `acl_object_identity_ancestors`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}