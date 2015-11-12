<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1447324144.
 * Generated on 2015-11-12 11:29:04 by lionel
 */
class PropelMigration_1447324144
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

ALTER TABLE `p_m_d_comment_historic`
    ADD `p_object_id` INTEGER AFTER `p_d_d_comment_id`;

ALTER TABLE `p_m_debate_historic`
    ADD `p_object_id` INTEGER AFTER `p_d_debate_id`;

ALTER TABLE `p_m_r_comment_historic`
    ADD `p_object_id` INTEGER AFTER `p_d_r_comment_id`;

ALTER TABLE `p_m_reaction_historic`
    ADD `p_object_id` INTEGER AFTER `p_d_reaction_id`;

ALTER TABLE `p_m_user_historic`
    ADD `p_object_id` INTEGER AFTER `p_user_id`;

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

ALTER TABLE `p_m_d_comment_historic` DROP `p_object_id`;

ALTER TABLE `p_m_debate_historic` DROP `p_object_id`;

ALTER TABLE `p_m_r_comment_historic` DROP `p_object_id`;

ALTER TABLE `p_m_reaction_historic` DROP `p_object_id`;

ALTER TABLE `p_m_user_historic` DROP `p_object_id`;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}