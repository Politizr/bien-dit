<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1448010007.
 * Generated on 2015-11-20 10:00:07 by lionel
 */
class PropelMigration_1448010007
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

ALTER TABLE `p_u_mandate`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE UNIQUE INDEX `p_u_mandate_U_1` ON `p_u_mandate` (`uuid`);

ALTER TABLE `p_u_mandate_archive`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE INDEX `p_u_mandate_archive_I_5` ON `p_u_mandate_archive` (`uuid`);

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

DROP INDEX `p_u_mandate_U_1` ON `p_u_mandate`;

ALTER TABLE `p_u_mandate` DROP `uuid`;

DROP INDEX `p_u_mandate_archive_I_5` ON `p_u_mandate_archive`;

ALTER TABLE `p_u_mandate_archive` DROP `uuid`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}