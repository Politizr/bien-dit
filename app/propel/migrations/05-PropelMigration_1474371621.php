<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1474371621.
 * Generated on 2016-09-20 13:40:21 by lionel
 */
class PropelMigration_1474371621
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
    ADD `p_l_city_id` INTEGER AFTER `p_user_id`,
    ADD `p_l_department_id` INTEGER AFTER `p_l_city_id`,
    ADD `p_l_region_id` INTEGER AFTER `p_l_department_id`,
    ADD `p_l_country_id` INTEGER AFTER `p_l_region_id`;

CREATE INDEX `p_d_debate_FI_2` ON `p_d_debate` (`p_l_city_id`);

CREATE INDEX `p_d_debate_FI_3` ON `p_d_debate` (`p_l_department_id`);

CREATE INDEX `p_d_debate_FI_4` ON `p_d_debate` (`p_l_region_id`);

CREATE INDEX `p_d_debate_FI_5` ON `p_d_debate` (`p_l_country_id`);

ALTER TABLE `p_d_debate` ADD CONSTRAINT `p_d_debate_FK_2`
    FOREIGN KEY (`p_l_city_id`)
    REFERENCES `p_l_city` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

ALTER TABLE `p_d_debate` ADD CONSTRAINT `p_d_debate_FK_3`
    FOREIGN KEY (`p_l_department_id`)
    REFERENCES `p_l_department` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

ALTER TABLE `p_d_debate` ADD CONSTRAINT `p_d_debate_FK_4`
    FOREIGN KEY (`p_l_region_id`)
    REFERENCES `p_l_region` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

ALTER TABLE `p_d_debate` ADD CONSTRAINT `p_d_debate_FK_5`
    FOREIGN KEY (`p_l_country_id`)
    REFERENCES `p_l_country` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

DROP INDEX `p_d_debate_archive_I_2` ON `p_d_debate_archive`;

DROP INDEX `p_d_debate_archive_I_3` ON `p_d_debate_archive`;

ALTER TABLE `p_d_debate_archive`
    ADD `p_l_city_id` INTEGER AFTER `p_user_id`,
    ADD `p_l_department_id` INTEGER AFTER `p_l_city_id`,
    ADD `p_l_region_id` INTEGER AFTER `p_l_department_id`,
    ADD `p_l_country_id` INTEGER AFTER `p_l_region_id`;

CREATE INDEX `p_d_debate_archive_I_2` ON `p_d_debate_archive` (`p_l_city_id`);

CREATE INDEX `p_d_debate_archive_I_3` ON `p_d_debate_archive` (`p_l_department_id`);

CREATE INDEX `p_d_debate_archive_I_4` ON `p_d_debate_archive` (`p_l_region_id`);

CREATE INDEX `p_d_debate_archive_I_5` ON `p_d_debate_archive` (`p_l_country_id`);

CREATE INDEX `p_d_debate_archive_I_6` ON `p_d_debate_archive` (`uuid`);

CREATE INDEX `p_d_debate_archive_I_7` ON `p_d_debate_archive` (`slug`);

ALTER TABLE `p_d_reaction`
    ADD `p_l_city_id` INTEGER AFTER `parent_reaction_id`,
    ADD `p_l_department_id` INTEGER AFTER `p_l_city_id`,
    ADD `p_l_region_id` INTEGER AFTER `p_l_department_id`,
    ADD `p_l_country_id` INTEGER AFTER `p_l_region_id`;

CREATE INDEX `p_d_reaction_FI_3` ON `p_d_reaction` (`p_l_city_id`);

CREATE INDEX `p_d_reaction_FI_4` ON `p_d_reaction` (`p_l_department_id`);

CREATE INDEX `p_d_reaction_FI_5` ON `p_d_reaction` (`p_l_region_id`);

CREATE INDEX `p_d_reaction_FI_6` ON `p_d_reaction` (`p_l_country_id`);

ALTER TABLE `p_d_reaction` ADD CONSTRAINT `p_d_reaction_FK_3`
    FOREIGN KEY (`p_l_city_id`)
    REFERENCES `p_l_city` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

ALTER TABLE `p_d_reaction` ADD CONSTRAINT `p_d_reaction_FK_4`
    FOREIGN KEY (`p_l_department_id`)
    REFERENCES `p_l_department` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

ALTER TABLE `p_d_reaction` ADD CONSTRAINT `p_d_reaction_FK_5`
    FOREIGN KEY (`p_l_region_id`)
    REFERENCES `p_l_region` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

ALTER TABLE `p_d_reaction` ADD CONSTRAINT `p_d_reaction_FK_6`
    FOREIGN KEY (`p_l_country_id`)
    REFERENCES `p_l_country` (`id`)
    ON UPDATE CASCADE
    ON DELETE SET NULL;

DROP INDEX `p_d_reaction_archive_I_3` ON `p_d_reaction_archive`;

DROP INDEX `p_d_reaction_archive_I_4` ON `p_d_reaction_archive`;

ALTER TABLE `p_d_reaction_archive`
    ADD `p_l_city_id` INTEGER AFTER `parent_reaction_id`,
    ADD `p_l_department_id` INTEGER AFTER `p_l_city_id`,
    ADD `p_l_region_id` INTEGER AFTER `p_l_department_id`,
    ADD `p_l_country_id` INTEGER AFTER `p_l_region_id`;

CREATE INDEX `p_d_reaction_archive_I_3` ON `p_d_reaction_archive` (`p_l_city_id`);

CREATE INDEX `p_d_reaction_archive_I_4` ON `p_d_reaction_archive` (`p_l_department_id`);

CREATE INDEX `p_d_reaction_archive_I_5` ON `p_d_reaction_archive` (`p_l_region_id`);

CREATE INDEX `p_d_reaction_archive_I_6` ON `p_d_reaction_archive` (`p_l_country_id`);

CREATE INDEX `p_d_reaction_archive_I_7` ON `p_d_reaction_archive` (`uuid`);

CREATE INDEX `p_d_reaction_archive_I_8` ON `p_d_reaction_archive` (`slug`);

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

ALTER TABLE `p_d_debate` DROP FOREIGN KEY `p_d_debate_FK_3`;

ALTER TABLE `p_d_debate` DROP FOREIGN KEY `p_d_debate_FK_4`;

ALTER TABLE `p_d_debate` DROP FOREIGN KEY `p_d_debate_FK_5`;

DROP INDEX `p_d_debate_FI_2` ON `p_d_debate`;

DROP INDEX `p_d_debate_FI_3` ON `p_d_debate`;

DROP INDEX `p_d_debate_FI_4` ON `p_d_debate`;

DROP INDEX `p_d_debate_FI_5` ON `p_d_debate`;

ALTER TABLE `p_d_debate` DROP `p_l_city_id`;

ALTER TABLE `p_d_debate` DROP `p_l_department_id`;

ALTER TABLE `p_d_debate` DROP `p_l_region_id`;

ALTER TABLE `p_d_debate` DROP `p_l_country_id`;

DROP INDEX `p_d_debate_archive_I_4` ON `p_d_debate_archive`;

DROP INDEX `p_d_debate_archive_I_5` ON `p_d_debate_archive`;

DROP INDEX `p_d_debate_archive_I_6` ON `p_d_debate_archive`;

DROP INDEX `p_d_debate_archive_I_7` ON `p_d_debate_archive`;

DROP INDEX `p_d_debate_archive_I_2` ON `p_d_debate_archive`;

DROP INDEX `p_d_debate_archive_I_3` ON `p_d_debate_archive`;

ALTER TABLE `p_d_debate_archive` DROP `p_l_city_id`;

ALTER TABLE `p_d_debate_archive` DROP `p_l_department_id`;

ALTER TABLE `p_d_debate_archive` DROP `p_l_region_id`;

ALTER TABLE `p_d_debate_archive` DROP `p_l_country_id`;

CREATE INDEX `p_d_debate_archive_I_2` ON `p_d_debate_archive` (`uuid`);

CREATE INDEX `p_d_debate_archive_I_3` ON `p_d_debate_archive` (`slug`);

ALTER TABLE `p_d_reaction` DROP FOREIGN KEY `p_d_reaction_FK_3`;

ALTER TABLE `p_d_reaction` DROP FOREIGN KEY `p_d_reaction_FK_4`;

ALTER TABLE `p_d_reaction` DROP FOREIGN KEY `p_d_reaction_FK_5`;

ALTER TABLE `p_d_reaction` DROP FOREIGN KEY `p_d_reaction_FK_6`;

DROP INDEX `p_d_reaction_FI_3` ON `p_d_reaction`;

DROP INDEX `p_d_reaction_FI_4` ON `p_d_reaction`;

DROP INDEX `p_d_reaction_FI_5` ON `p_d_reaction`;

DROP INDEX `p_d_reaction_FI_6` ON `p_d_reaction`;

ALTER TABLE `p_d_reaction` DROP `p_l_city_id`;

ALTER TABLE `p_d_reaction` DROP `p_l_department_id`;

ALTER TABLE `p_d_reaction` DROP `p_l_region_id`;

ALTER TABLE `p_d_reaction` DROP `p_l_country_id`;

DROP INDEX `p_d_reaction_archive_I_5` ON `p_d_reaction_archive`;

DROP INDEX `p_d_reaction_archive_I_6` ON `p_d_reaction_archive`;

DROP INDEX `p_d_reaction_archive_I_7` ON `p_d_reaction_archive`;

DROP INDEX `p_d_reaction_archive_I_8` ON `p_d_reaction_archive`;

DROP INDEX `p_d_reaction_archive_I_3` ON `p_d_reaction_archive`;

DROP INDEX `p_d_reaction_archive_I_4` ON `p_d_reaction_archive`;

ALTER TABLE `p_d_reaction_archive` DROP `p_l_city_id`;

ALTER TABLE `p_d_reaction_archive` DROP `p_l_department_id`;

ALTER TABLE `p_d_reaction_archive` DROP `p_l_region_id`;

ALTER TABLE `p_d_reaction_archive` DROP `p_l_country_id`;

CREATE INDEX `p_d_reaction_archive_I_3` ON `p_d_reaction_archive` (`uuid`);

CREATE INDEX `p_d_reaction_archive_I_4` ON `p_d_reaction_archive` (`slug`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}