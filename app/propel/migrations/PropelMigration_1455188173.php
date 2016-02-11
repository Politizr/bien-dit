<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1455188173.
 * Generated on 2016-02-11 11:56:13 by lionel
 */
class PropelMigration_1455188173
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

ALTER TABLE `p_r_badge` ADD CONSTRAINT `p_r_badge_FK_2`
    FOREIGN KEY (`p_r_badge_family_id`)
    REFERENCES `p_r_badge_family` (`id`)
    ON UPDATE CASCADE
    ON DELETE CASCADE;

CREATE INDEX `p_u_reputation_I_1` ON `p_u_reputation` (`p_object_id`);

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

ALTER TABLE `p_r_badge` DROP FOREIGN KEY `p_r_badge_FK_2`;

DROP INDEX `p_u_reputation_I_1` ON `p_u_reputation`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}