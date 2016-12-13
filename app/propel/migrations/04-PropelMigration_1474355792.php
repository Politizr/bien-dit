<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1474355792.
 * Generated on 2016-09-20 09:16:32 by lionel
 */
class PropelMigration_1474355792
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

ALTER TABLE `p_l_department` DROP FOREIGN KEY `p_l_department_FK_2`;

DROP INDEX `p_l_department_FI_2` ON `p_l_department`;

ALTER TABLE `p_l_department` DROP `p_tag_id`;

DROP INDEX `p_l_region_FI_2` ON `p_l_region`;

ALTER TABLE `p_l_region` DROP `p_tag_id`;

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

DROP INDEX `acl_object_identity_ancestors_I_2` ON `acl_object_identity_ancestors`;

ALTER TABLE `p_l_department`
    ADD `p_tag_id` INTEGER NOT NULL AFTER `p_l_region_id`;

CREATE INDEX `p_l_department_FI_2` ON `p_l_department` (`p_tag_id`);

ALTER TABLE `p_l_department` ADD CONSTRAINT `p_l_department_FK_2`
    FOREIGN KEY (`p_tag_id`)
    REFERENCES `p_tag` (`id`)
    ON UPDATE CASCADE
    ON DELETE CASCADE;

ALTER TABLE `p_l_region`
    ADD `p_tag_id` INTEGER NOT NULL AFTER `p_l_country_id`;

CREATE INDEX `p_l_region_FI_2` ON `p_l_region` (`p_tag_id`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}