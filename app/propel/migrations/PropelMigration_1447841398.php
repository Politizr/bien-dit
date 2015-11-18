<?php

/**
 * Data object containing the SQL and PHP code to migrate the database
 * up to version 1447841398.
 * Generated on 2015-11-18 11:09:58 by lionel
 */
class PropelMigration_1447841398
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

ALTER TABLE `p_d_d_comment`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE UNIQUE INDEX `p_d_d_comment_U_1` ON `p_d_d_comment` (`uuid`);

ALTER TABLE `p_d_d_comment_archive`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE INDEX `p_d_d_comment_archive_I_3` ON `p_d_d_comment_archive` (`uuid`);

ALTER TABLE `p_d_debate`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE UNIQUE INDEX `p_d_debate_U_1` ON `p_d_debate` (`uuid`);

DROP INDEX `p_d_debate_archive_I_2` ON `p_d_debate_archive`;

ALTER TABLE `p_d_debate_archive`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE INDEX `p_d_debate_archive_I_2` ON `p_d_debate_archive` (`uuid`);

CREATE INDEX `p_d_debate_archive_I_3` ON `p_d_debate_archive` (`slug`);

ALTER TABLE `p_d_r_comment`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE UNIQUE INDEX `p_d_r_comment_U_1` ON `p_d_r_comment` (`uuid`);

ALTER TABLE `p_d_r_comment_archive`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE INDEX `p_d_r_comment_archive_I_3` ON `p_d_r_comment_archive` (`uuid`);

ALTER TABLE `p_d_reaction`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE UNIQUE INDEX `p_d_reaction_U_1` ON `p_d_reaction` (`uuid`);

DROP INDEX `p_d_reaction_archive_I_3` ON `p_d_reaction_archive`;

ALTER TABLE `p_d_reaction_archive`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE INDEX `p_d_reaction_archive_I_3` ON `p_d_reaction_archive` (`uuid`);

ALTER TABLE `p_o_subscription`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE UNIQUE INDEX `p_o_subscription_U_1` ON `p_o_subscription` (`uuid`);

ALTER TABLE `p_order`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE UNIQUE INDEX `p_order_U_1` ON `p_order` (`uuid`);

ALTER TABLE `p_order_archive`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE INDEX `p_order_archive_I_6` ON `p_order_archive` (`uuid`);

ALTER TABLE `p_q_mandate`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE UNIQUE INDEX `p_q_mandate_U_1` ON `p_q_mandate` (`uuid`);

ALTER TABLE `p_q_organization`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE UNIQUE INDEX `p_q_organization_U_1` ON `p_q_organization` (`uuid`);

ALTER TABLE `p_q_type`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE UNIQUE INDEX `p_q_type_U_1` ON `p_q_type` (`uuid`);

ALTER TABLE `p_qualification`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE UNIQUE INDEX `p_qualification_U_1` ON `p_qualification` (`uuid`);

ALTER TABLE `p_r_action`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE UNIQUE INDEX `p_r_action_U_1` ON `p_r_action` (`uuid`);

ALTER TABLE `p_r_badge`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE UNIQUE INDEX `p_r_badge_U_1` ON `p_r_badge` (`uuid`);

DROP INDEX `p_r_badge_archive_I_2` ON `p_r_badge_archive`;

ALTER TABLE `p_r_badge_archive`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE INDEX `p_r_badge_archive_I_2` ON `p_r_badge_archive` (`uuid`);

CREATE INDEX `p_r_badge_archive_I_3` ON `p_r_badge_archive` (`slug`);

DROP INDEX `p_user_U_1` ON `p_user`;

DROP INDEX `p_user_U_2` ON `p_user`;

ALTER TABLE `p_user`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE UNIQUE INDEX `p_user_U_1` ON `p_user` (`uuid`);

CREATE UNIQUE INDEX `p_user_U_2` ON `p_user` (`username_canonical`);

CREATE UNIQUE INDEX `p_user_U_3` ON `p_user` (`email_canonical`);

DROP INDEX `p_user_archive_I_2` ON `p_user_archive`;

DROP INDEX `p_user_archive_I_3` ON `p_user_archive`;

DROP INDEX `p_user_archive_I_4` ON `p_user_archive`;

ALTER TABLE `p_user_archive`
    ADD `uuid` VARCHAR(50) AFTER `id`;

CREATE INDEX `p_user_archive_I_2` ON `p_user_archive` (`uuid`);

CREATE INDEX `p_user_archive_I_3` ON `p_user_archive` (`username_canonical`);

CREATE INDEX `p_user_archive_I_4` ON `p_user_archive` (`email_canonical`);

CREATE INDEX `p_user_archive_I_5` ON `p_user_archive` (`slug`);

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

DROP INDEX `p_d_d_comment_U_1` ON `p_d_d_comment`;

ALTER TABLE `p_d_d_comment` DROP `uuid`;

DROP INDEX `p_d_d_comment_archive_I_3` ON `p_d_d_comment_archive`;

ALTER TABLE `p_d_d_comment_archive` DROP `uuid`;

DROP INDEX `p_d_debate_U_1` ON `p_d_debate`;

ALTER TABLE `p_d_debate` DROP `uuid`;

DROP INDEX `p_d_debate_archive_I_3` ON `p_d_debate_archive`;

DROP INDEX `p_d_debate_archive_I_2` ON `p_d_debate_archive`;

ALTER TABLE `p_d_debate_archive` DROP `uuid`;

CREATE INDEX `p_d_debate_archive_I_2` ON `p_d_debate_archive` (`slug`);

DROP INDEX `p_d_r_comment_U_1` ON `p_d_r_comment`;

ALTER TABLE `p_d_r_comment` DROP `uuid`;

DROP INDEX `p_d_r_comment_archive_I_3` ON `p_d_r_comment_archive`;

ALTER TABLE `p_d_r_comment_archive` DROP `uuid`;

DROP INDEX `p_d_reaction_U_1` ON `p_d_reaction`;

ALTER TABLE `p_d_reaction` DROP `uuid`;

DROP INDEX `p_d_reaction_archive_I_3` ON `p_d_reaction_archive`;

ALTER TABLE `p_d_reaction_archive` DROP `uuid`;

CREATE INDEX `p_d_reaction_archive_I_3` ON `p_d_reaction_archive` (`slug`);

DROP INDEX `p_o_subscription_U_1` ON `p_o_subscription`;

ALTER TABLE `p_o_subscription` DROP `uuid`;

DROP INDEX `p_order_U_1` ON `p_order`;

ALTER TABLE `p_order` DROP `uuid`;

DROP INDEX `p_order_archive_I_6` ON `p_order_archive`;

ALTER TABLE `p_order_archive` DROP `uuid`;

DROP INDEX `p_q_mandate_U_1` ON `p_q_mandate`;

ALTER TABLE `p_q_mandate` DROP `uuid`;

DROP INDEX `p_q_organization_U_1` ON `p_q_organization`;

ALTER TABLE `p_q_organization` DROP `uuid`;

DROP INDEX `p_q_type_U_1` ON `p_q_type`;

ALTER TABLE `p_q_type` DROP `uuid`;

DROP INDEX `p_qualification_U_1` ON `p_qualification`;

ALTER TABLE `p_qualification` DROP `uuid`;

DROP INDEX `p_r_action_U_1` ON `p_r_action`;

ALTER TABLE `p_r_action` DROP `uuid`;

DROP INDEX `p_r_badge_U_1` ON `p_r_badge`;

ALTER TABLE `p_r_badge` DROP `uuid`;

DROP INDEX `p_r_badge_archive_I_3` ON `p_r_badge_archive`;

DROP INDEX `p_r_badge_archive_I_2` ON `p_r_badge_archive`;

ALTER TABLE `p_r_badge_archive` DROP `uuid`;

CREATE INDEX `p_r_badge_archive_I_2` ON `p_r_badge_archive` (`slug`);

DROP INDEX `p_user_U_3` ON `p_user`;

DROP INDEX `p_user_U_1` ON `p_user`;

DROP INDEX `p_user_U_2` ON `p_user`;

ALTER TABLE `p_user` DROP `uuid`;

CREATE UNIQUE INDEX `p_user_U_1` ON `p_user` (`username_canonical`);

CREATE UNIQUE INDEX `p_user_U_2` ON `p_user` (`email_canonical`);

DROP INDEX `p_user_archive_I_5` ON `p_user_archive`;

DROP INDEX `p_user_archive_I_2` ON `p_user_archive`;

DROP INDEX `p_user_archive_I_3` ON `p_user_archive`;

DROP INDEX `p_user_archive_I_4` ON `p_user_archive`;

ALTER TABLE `p_user_archive` DROP `uuid`;

CREATE INDEX `p_user_archive_I_2` ON `p_user_archive` (`username_canonical`);

CREATE INDEX `p_user_archive_I_3` ON `p_user_archive` (`email_canonical`);

CREATE INDEX `p_user_archive_I_4` ON `p_user_archive` (`slug`);

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
',
);
    }

}