<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1447839284.
 * Generated on 2015-11-18 10:34:44 by lionel
 */
class PropelMigration_1447839284
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

ALTER TABLE `p_tag`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE UNIQUE INDEX `p_tag_U_1` ON `p_tag` (`uuid`);

DROP INDEX `p_tag_archive_I_4` ON `p_tag_archive`;

ALTER TABLE `p_tag_archive`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE INDEX `p_tag_archive_I_4` ON `p_tag_archive` (`uuid`);

CREATE INDEX `p_tag_archive_I_5` ON `p_tag_archive` (`slug`);

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

DROP INDEX `p_tag_U_1` ON `p_tag`;

ALTER TABLE `p_tag` DROP `uuid`;

DROP INDEX `p_tag_archive_I_5` ON `p_tag_archive`;

DROP INDEX `p_tag_archive_I_4` ON `p_tag_archive`;

ALTER TABLE `p_tag_archive` DROP `uuid`;

CREATE INDEX `p_tag_archive_I_4` ON `p_tag_archive` (`slug`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}