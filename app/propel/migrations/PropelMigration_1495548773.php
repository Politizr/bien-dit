<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1495548773.
 * Generated on 2017-05-23 16:12:53 by lionel
 */
class PropelMigration_1495548773
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

ALTER TABLE `p_d_debate`
    ADD `p_e_operation_id` INTEGER AFTER `p_user_id`;

ALTER TABLE `p_d_debate` ADD CONSTRAINT `p_d_debate_FK_2`
    FOREIGN KEY (`p_e_operation_id`)
    REFERENCES `p_e_operation` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

ALTER TABLE `p_d_debate_archive`
    ADD `p_e_operation_id` INTEGER AFTER `p_user_id`;

ALTER TABLE `p_e_operation`
    ADD `editing_description` TEXT AFTER `description`;

ALTER TABLE `p_e_operation_archive`
    ADD `editing_description` TEXT AFTER `description`;

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

ALTER TABLE `p_d_debate` DROP FOREIGN KEY `p_d_debate_FK_2`;

DROP INDEX `p_d_debate_FI_6` ON `p_d_debate`;

DROP INDEX `p_d_debate_FI_2` ON `p_d_debate`;

DROP INDEX `p_d_debate_FI_3` ON `p_d_debate`;

DROP INDEX `p_d_debate_FI_4` ON `p_d_debate`;

DROP INDEX `p_d_debate_FI_5` ON `p_d_debate`;

ALTER TABLE `p_d_debate` DROP `p_e_operation_id`;

CREATE INDEX `p_d_debate_FI_2` ON `p_d_debate` (`p_l_city_id`);

CREATE INDEX `p_d_debate_FI_3` ON `p_d_debate` (`p_l_department_id`);

CREATE INDEX `p_d_debate_FI_4` ON `p_d_debate` (`p_l_region_id`);

CREATE INDEX `p_d_debate_FI_5` ON `p_d_debate` (`p_l_country_id`);

ALTER TABLE `p_d_debate` ADD CONSTRAINT `p_d_debate_FK_2`
    FOREIGN KEY (`p_l_city_id`)
    REFERENCES `p_l_city` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

DROP INDEX `p_d_debate_archive_I_8` ON `p_d_debate_archive`;

DROP INDEX `p_d_debate_archive_I_2` ON `p_d_debate_archive`;

DROP INDEX `p_d_debate_archive_I_3` ON `p_d_debate_archive`;

DROP INDEX `p_d_debate_archive_I_4` ON `p_d_debate_archive`;

DROP INDEX `p_d_debate_archive_I_5` ON `p_d_debate_archive`;

DROP INDEX `p_d_debate_archive_I_6` ON `p_d_debate_archive`;

DROP INDEX `p_d_debate_archive_I_7` ON `p_d_debate_archive`;

ALTER TABLE `p_d_debate_archive` DROP `p_e_operation_id`;

CREATE INDEX `p_d_debate_archive_I_2` ON `p_d_debate_archive` (`p_l_city_id`);

CREATE INDEX `p_d_debate_archive_I_3` ON `p_d_debate_archive` (`p_l_department_id`);

CREATE INDEX `p_d_debate_archive_I_4` ON `p_d_debate_archive` (`p_l_region_id`);

CREATE INDEX `p_d_debate_archive_I_5` ON `p_d_debate_archive` (`p_l_country_id`);

CREATE INDEX `p_d_debate_archive_I_6` ON `p_d_debate_archive` (`uuid`);

CREATE INDEX `p_d_debate_archive_I_7` ON `p_d_debate_archive` (`slug`);

ALTER TABLE `p_e_operation` DROP `editing_description`;

ALTER TABLE `p_e_operation_archive` DROP `editing_description`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}