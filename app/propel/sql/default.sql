
# This is a fix for InnoDB in MySQL >= 4.1.x
# It "suspends judgement" for fkey relationships until are tables are set.
SET FOREIGN_KEY_CHECKS = 0;

-- ---------------------------------------------------------------------
-- fos_user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `fos_user`;

CREATE TABLE `fos_user`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `username` VARCHAR(255),
    `username_canonical` VARCHAR(255),
    `email` VARCHAR(255),
    `email_canonical` VARCHAR(255),
    `enabled` TINYINT(1) DEFAULT 0,
    `salt` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `last_login` DATETIME,
    `locked` TINYINT(1) DEFAULT 0,
    `expired` TINYINT(1) DEFAULT 0,
    `expires_at` DATETIME,
    `confirmation_token` VARCHAR(255),
    `password_requested_at` DATETIME,
    `credentials_expired` TINYINT(1) DEFAULT 0,
    `credentials_expire_at` DATETIME,
    `roles` TEXT,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `fos_user_U_1` (`username_canonical`),
    UNIQUE INDEX `fos_user_U_2` (`email_canonical`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- fos_group
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `fos_group`;

CREATE TABLE `fos_group`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `name` VARCHAR(255) NOT NULL,
    `roles` TEXT,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- fos_user_group
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `fos_user_group`;

CREATE TABLE `fos_user_group`
(
    `fos_user_id` INTEGER NOT NULL,
    `fos_group_id` INTEGER NOT NULL,
    PRIMARY KEY (`fos_user_id`,`fos_group_id`),
    INDEX `fos_user_group_FI_2` (`fos_group_id`),
    CONSTRAINT `fos_user_group_FK_1`
        FOREIGN KEY (`fos_user_id`)
        REFERENCES `fos_user` (`id`),
    CONSTRAINT `fos_user_group_FK_2`
        FOREIGN KEY (`fos_group_id`)
        REFERENCES `fos_group` (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_tag
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_tag`;

CREATE TABLE `p_tag`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_t_tag_type_id` INTEGER NOT NULL,
    `p_user_id` INTEGER,
    `title` VARCHAR(150),
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_tag_slug` (`slug`(255)),
    INDEX `p_tag_FI_1` (`p_t_tag_type_id`),
    INDEX `p_tag_FI_2` (`p_user_id`),
    CONSTRAINT `p_tag_FK_1`
        FOREIGN KEY (`p_t_tag_type_id`)
        REFERENCES `p_t_tag_type` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_tag_FK_2`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_t_tag_type
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_t_tag_type`;

CREATE TABLE `p_t_tag_type`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(150),
    `description` TEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_r_badge
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_r_badge`;

CREATE TABLE `p_r_badge`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_r_badge_type_id` INTEGER NOT NULL,
    `p_r_badge_metal_id` INTEGER NOT NULL,
    `title` VARCHAR(150),
    `description` TEXT,
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_r_badge_slug` (`slug`(255)),
    INDEX `p_r_badge_FI_1` (`p_r_badge_type_id`),
    INDEX `p_r_badge_FI_2` (`p_r_badge_metal_id`),
    CONSTRAINT `p_r_badge_FK_1`
        FOREIGN KEY (`p_r_badge_type_id`)
        REFERENCES `p_r_badge_type` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_r_badge_FK_2`
        FOREIGN KEY (`p_r_badge_metal_id`)
        REFERENCES `p_r_badge_metal` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_r_badge_type
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_r_badge_type`;

CREATE TABLE `p_r_badge_type`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(150),
    `description` TEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_r_badge_metal
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_r_badge_metal`;

CREATE TABLE `p_r_badge_metal`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(150),
    `description` TEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_r_action
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_r_action`;

CREATE TABLE `p_r_action`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(150),
    `description` TEXT,
    `p_object_name` VARCHAR(150),
    `p_object_id` INTEGER,
    `score_evolution` INTEGER,
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_r_action_slug` (`slug`(255))
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_order
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_order`;

CREATE TABLE `p_order`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER,
    `p_o_order_state_id` INTEGER,
    `p_o_payment_state_id` INTEGER,
    `p_o_payment_type_id` INTEGER,
    `p_o_subscription_id` INTEGER,
    `subscription_title` VARCHAR(150),
    `subscription_description` TEXT,
    `subscription_begin_at` DATE,
    `subscription_end_at` DATE,
    `information` TEXT,
    `price` DECIMAL(10,2),
    `promotion` DECIMAL(10,2),
    `total` DECIMAL(10,2),
    `gender` TINYINT,
    `name` VARCHAR(150),
    `firstname` VARCHAR(150),
    `phone` VARCHAR(30),
    `email` VARCHAR(255),
    `invoice_ref` VARCHAR(250),
    `invoice_at` DATETIME,
    `invoice_filename` VARCHAR(250),
    `supporting_document` VARCHAR(250),
    `elective_mandates` TEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_order_FI_1` (`p_user_id`),
    INDEX `p_order_FI_2` (`p_o_order_state_id`),
    INDEX `p_order_FI_3` (`p_o_payment_state_id`),
    INDEX `p_order_FI_4` (`p_o_payment_type_id`),
    INDEX `p_order_FI_5` (`p_o_subscription_id`),
    CONSTRAINT `p_order_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `p_order_FK_2`
        FOREIGN KEY (`p_o_order_state_id`)
        REFERENCES `p_o_order_state` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `p_order_FK_3`
        FOREIGN KEY (`p_o_payment_state_id`)
        REFERENCES `p_o_payment_state` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `p_order_FK_4`
        FOREIGN KEY (`p_o_payment_type_id`)
        REFERENCES `p_o_payment_type` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `p_order_FK_5`
        FOREIGN KEY (`p_o_subscription_id`)
        REFERENCES `p_o_subscription` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_o_order_state
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_o_order_state`;

CREATE TABLE `p_o_order_state`
(
    `id` INTEGER NOT NULL,
    `title` VARCHAR(150),
    `description` TEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_o_payment_state
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_o_payment_state`;

CREATE TABLE `p_o_payment_state`
(
    `id` INTEGER NOT NULL,
    `title` VARCHAR(150),
    `description` TEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_o_payment_type
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_o_payment_type`;

CREATE TABLE `p_o_payment_type`
(
    `id` INTEGER NOT NULL,
    `title` VARCHAR(150),
    `description` TEXT,
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_o_subscription
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_o_subscription`;

CREATE TABLE `p_o_subscription`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(150),
    `description` TEXT,
    `price` DECIMAL(10,2),
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_o_subscription_slug` (`slug`(255))
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_o_email
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_o_email`;

CREATE TABLE `p_o_email`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_order_id` INTEGER NOT NULL,
    `p_o_order_state_id` INTEGER,
    `p_o_payment_state_id` INTEGER,
    `p_o_payment_type_id` INTEGER,
    `p_o_subscription_id` INTEGER,
    `send` VARCHAR(150),
    `subject` VARCHAR(250),
    `html_body` TEXT,
    `txt_body` TEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_o_email_FI_1` (`p_order_id`),
    INDEX `p_o_email_FI_2` (`p_o_order_state_id`),
    INDEX `p_o_email_FI_3` (`p_o_payment_state_id`),
    INDEX `p_o_email_FI_4` (`p_o_payment_type_id`),
    INDEX `p_o_email_FI_5` (`p_o_subscription_id`),
    CONSTRAINT `p_o_email_FK_1`
        FOREIGN KEY (`p_order_id`)
        REFERENCES `p_order` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_o_email_FK_2`
        FOREIGN KEY (`p_o_order_state_id`)
        REFERENCES `p_o_order_state` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `p_o_email_FK_3`
        FOREIGN KEY (`p_o_payment_state_id`)
        REFERENCES `p_o_payment_state` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `p_o_email_FK_4`
        FOREIGN KEY (`p_o_payment_type_id`)
        REFERENCES `p_o_payment_type` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `p_o_email_FK_5`
        FOREIGN KEY (`p_o_subscription_id`)
        REFERENCES `p_o_subscription` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_qualification
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_qualification`;

CREATE TABLE `p_qualification`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(250),
    `description` TEXT,
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_qualification_slug` (`slug`(255))
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_q_type
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_q_type`;

CREATE TABLE `p_q_type`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(250),
    `description` TEXT,
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_q_type_slug` (`slug`(255))
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_q_mandate
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_q_mandate`;

CREATE TABLE `p_q_mandate`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_q_type_id` INTEGER NOT NULL,
    `title` VARCHAR(250),
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_q_mandate_slug` (`slug`(255)),
    INDEX `p_q_mandate_FI_1` (`p_q_type_id`),
    CONSTRAINT `p_q_mandate_FK_1`
        FOREIGN KEY (`p_q_type_id`)
        REFERENCES `p_q_type` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_q_organization
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_q_organization`;

CREATE TABLE `p_q_organization`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(250),
    `initials` VARCHAR(50),
    `file_name` VARCHAR(150),
    `description` TEXT,
    `url` VARCHAR(150),
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    `sortable_rank` INTEGER,
    `p_q_type_id` INTEGER,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_q_organization_slug` (`slug`(255)),
    INDEX `p_q_organization_FI_1` (`p_q_type_id`),
    CONSTRAINT `p_q_organization_FK_1`
        FOREIGN KEY (`p_q_type_id`)
        REFERENCES `p_q_type` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_notification
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_notification`;

CREATE TABLE `p_notification`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(250),
    `description` TEXT,
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_user
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_user`;

CREATE TABLE `p_user`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `provider` VARCHAR(255),
    `provider_id` VARCHAR(255),
    `nickname` VARCHAR(255),
    `realname` VARCHAR(255),
    `username` VARCHAR(255),
    `username_canonical` VARCHAR(255),
    `email` VARCHAR(255),
    `email_canonical` VARCHAR(255),
    `enabled` TINYINT(1) DEFAULT 0,
    `salt` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `last_login` DATETIME,
    `locked` TINYINT(1) DEFAULT 0,
    `expired` TINYINT(1) DEFAULT 0,
    `expires_at` DATETIME,
    `confirmation_token` VARCHAR(255),
    `password_requested_at` DATETIME,
    `credentials_expired` TINYINT(1) DEFAULT 0,
    `credentials_expire_at` DATETIME,
    `roles` TEXT,
    `last_activity` DATETIME,
    `p_u_status_id` INTEGER NOT NULL,
    `file_name` VARCHAR(150),
    `back_file_name` VARCHAR(150),
    `gender` TINYINT,
    `firstname` VARCHAR(150),
    `name` VARCHAR(150),
    `birthday` DATE,
    `subtitle` VARCHAR(500),
    `biography` TEXT,
    `website` VARCHAR(150),
    `twitter` VARCHAR(150),
    `facebook` VARCHAR(150),
    `phone` VARCHAR(30),
    `newsletter` TINYINT(1),
    `last_connect` DATETIME,
    `nb_connected_days` INTEGER DEFAULT 0,
    `nb_views` INTEGER,
    `qualified` TINYINT(1),
    `validated` TINYINT(1) DEFAULT 0,
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_user_U_1` (`username_canonical`),
    UNIQUE INDEX `p_user_U_2` (`email_canonical`),
    UNIQUE INDEX `p_user_slug` (`slug`(255)),
    INDEX `p_user_FI_1` (`p_u_status_id`),
    CONSTRAINT `p_user_FK_1`
        FOREIGN KEY (`p_u_status_id`)
        REFERENCES `p_u_status` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_u_status
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_u_status`;

CREATE TABLE `p_u_status`
(
    `id` INTEGER NOT NULL,
    `title` VARCHAR(150),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_u_follow_u
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_u_follow_u`;

CREATE TABLE `p_u_follow_u`
(
    `notif_debate` TINYINT(1) DEFAULT 1,
    `notif_reaction` TINYINT(1) DEFAULT 1,
    `notif_comment` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `p_user_id` INTEGER NOT NULL,
    `p_user_follower_id` INTEGER NOT NULL,
    PRIMARY KEY (`p_user_id`,`p_user_follower_id`),
    INDEX `FI_ser_follower_id` (`p_user_follower_id`),
    CONSTRAINT `p_user_id`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `p_user_follower_id`
        FOREIGN KEY (`p_user_follower_id`)
        REFERENCES `p_user` (`id`)
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_u_follow_d_d
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_u_follow_d_d`;

CREATE TABLE `p_u_follow_d_d`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER NOT NULL,
    `p_d_debate_id` INTEGER NOT NULL,
    `notif_reaction` TINYINT(1) DEFAULT 1,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_follow_d_d_FI_1` (`p_user_id`),
    INDEX `p_u_follow_d_d_FI_2` (`p_d_debate_id`),
    CONSTRAINT `p_u_follow_d_d_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_follow_d_d_FK_2`
        FOREIGN KEY (`p_d_debate_id`)
        REFERENCES `p_d_debate` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_u_badge
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_u_badge`;

CREATE TABLE `p_u_badge`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER NOT NULL,
    `p_r_badge_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_badge_FI_1` (`p_user_id`),
    INDEX `p_u_badge_FI_2` (`p_r_badge_id`),
    CONSTRAINT `p_u_badge_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_badge_FK_2`
        FOREIGN KEY (`p_r_badge_id`)
        REFERENCES `p_r_badge` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_u_reputation
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_u_reputation`;

CREATE TABLE `p_u_reputation`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER NOT NULL,
    `p_r_action_id` INTEGER NOT NULL,
    `p_object_name` VARCHAR(150),
    `p_object_id` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_reputation_FI_1` (`p_user_id`),
    INDEX `p_u_reputation_FI_2` (`p_r_action_id`),
    CONSTRAINT `p_u_reputation_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_reputation_FK_2`
        FOREIGN KEY (`p_r_action_id`)
        REFERENCES `p_r_action` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_u_tagged_t
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_u_tagged_t`;

CREATE TABLE `p_u_tagged_t`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER NOT NULL,
    `p_tag_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_tagged_t_FI_1` (`p_user_id`),
    INDEX `p_u_tagged_t_FI_2` (`p_tag_id`),
    CONSTRAINT `p_u_tagged_t_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_tagged_t_FK_2`
        FOREIGN KEY (`p_tag_id`)
        REFERENCES `p_tag` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_u_follow_t
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_u_follow_t`;

CREATE TABLE `p_u_follow_t`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER NOT NULL,
    `p_tag_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_follow_t_FI_1` (`p_user_id`),
    INDEX `p_u_follow_t_FI_2` (`p_tag_id`),
    CONSTRAINT `p_u_follow_t_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_follow_t_FK_2`
        FOREIGN KEY (`p_tag_id`)
        REFERENCES `p_tag` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_u_role_q
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_u_role_q`;

CREATE TABLE `p_u_role_q`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER NOT NULL,
    `p_qualification_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_role_q_FI_1` (`p_user_id`),
    INDEX `p_u_role_q_FI_2` (`p_qualification_id`),
    CONSTRAINT `p_u_role_q_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_role_q_FK_2`
        FOREIGN KEY (`p_qualification_id`)
        REFERENCES `p_qualification` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_u_mandate
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_u_mandate`;

CREATE TABLE `p_u_mandate`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER NOT NULL,
    `p_q_type_id` INTEGER NOT NULL,
    `p_q_mandate_id` INTEGER NOT NULL,
    `p_q_organization_id` INTEGER,
    `localization` VARCHAR(150),
    `begin_at` DATE,
    `end_at` DATE,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_mandate_FI_1` (`p_user_id`),
    INDEX `p_u_mandate_FI_2` (`p_q_type_id`),
    INDEX `p_u_mandate_FI_3` (`p_q_mandate_id`),
    INDEX `p_u_mandate_FI_4` (`p_q_organization_id`),
    CONSTRAINT `p_u_mandate_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_mandate_FK_2`
        FOREIGN KEY (`p_q_type_id`)
        REFERENCES `p_q_type` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_mandate_FK_3`
        FOREIGN KEY (`p_q_mandate_id`)
        REFERENCES `p_q_mandate` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_mandate_FK_4`
        FOREIGN KEY (`p_q_organization_id`)
        REFERENCES `p_q_organization` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_u_affinity_q_o
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_u_affinity_q_o`;

CREATE TABLE `p_u_affinity_q_o`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER NOT NULL,
    `p_q_organization_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_affinity_q_o_FI_1` (`p_user_id`),
    INDEX `p_u_affinity_q_o_FI_2` (`p_q_organization_id`),
    CONSTRAINT `p_u_affinity_q_o_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_affinity_q_o_FK_2`
        FOREIGN KEY (`p_q_organization_id`)
        REFERENCES `p_q_organization` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_u_current_q_o
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_u_current_q_o`;

CREATE TABLE `p_u_current_q_o`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER NOT NULL,
    `p_q_organization_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_current_q_o_FI_1` (`p_user_id`),
    INDEX `p_u_current_q_o_FI_2` (`p_q_organization_id`),
    CONSTRAINT `p_u_current_q_o_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_current_q_o_FK_2`
        FOREIGN KEY (`p_q_organization_id`)
        REFERENCES `p_q_organization` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_u_notification
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_u_notification`;

CREATE TABLE `p_u_notification`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER NOT NULL,
    `p_notification_id` INTEGER NOT NULL,
    `p_object_name` VARCHAR(150),
    `p_object_id` INTEGER,
    `p_author_user_id` INTEGER,
    `checked` TINYINT(1),
    `checked_at` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_notification_FI_1` (`p_user_id`),
    INDEX `p_u_notification_FI_2` (`p_notification_id`),
    CONSTRAINT `p_u_notification_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_notification_FK_2`
        FOREIGN KEY (`p_notification_id`)
        REFERENCES `p_notification` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_u_subscribe_email
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_u_subscribe_email`;

CREATE TABLE `p_u_subscribe_email`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER NOT NULL,
    `p_notification_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_subscribe_email_FI_1` (`p_user_id`),
    INDEX `p_u_subscribe_email_FI_2` (`p_notification_id`),
    CONSTRAINT `p_u_subscribe_email_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_subscribe_email_FK_2`
        FOREIGN KEY (`p_notification_id`)
        REFERENCES `p_notification` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_u_subscribe_screen
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_u_subscribe_screen`;

CREATE TABLE `p_u_subscribe_screen`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER NOT NULL,
    `p_notification_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_subscribe_screen_FI_1` (`p_user_id`),
    INDEX `p_u_subscribe_screen_FI_2` (`p_notification_id`),
    CONSTRAINT `p_u_subscribe_screen_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_subscribe_screen_FK_2`
        FOREIGN KEY (`p_notification_id`)
        REFERENCES `p_notification` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_document
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_document`;

CREATE TABLE `p_document`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER,
    `title` VARCHAR(100),
    `file_name` VARCHAR(150),
    `summary` TEXT,
    `description` TEXT,
    `note_pos` INTEGER DEFAULT 0,
    `note_neg` INTEGER DEFAULT 0,
    `nb_views` INTEGER,
    `published` TINYINT(1),
    `published_at` DATETIME,
    `published_by` VARCHAR(300),
    `favorite` TINYINT(1),
    `online` TINYINT(1),
    `descendant_class` VARCHAR(100),
    PRIMARY KEY (`id`),
    INDEX `p_document_FI_1` (`p_user_id`),
    CONSTRAINT `p_document_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_d_debate
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_d_debate`;

CREATE TABLE `p_d_debate`
(
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    `id` INTEGER NOT NULL,
    `p_user_id` INTEGER,
    `title` VARCHAR(100),
    `file_name` VARCHAR(150),
    `summary` TEXT,
    `description` TEXT,
    `note_pos` INTEGER DEFAULT 0,
    `note_neg` INTEGER DEFAULT 0,
    `nb_views` INTEGER,
    `published` TINYINT(1),
    `published_at` DATETIME,
    `published_by` VARCHAR(300),
    `favorite` TINYINT(1),
    `online` TINYINT(1),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_d_debate_slug` (`slug`(255)),
    INDEX `p_d_debate_I_1` (`p_user_id`),
    CONSTRAINT `p_d_debate_FK_1`
        FOREIGN KEY (`id`)
        REFERENCES `p_document` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `p_d_debate_FK_2`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_d_reaction
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_d_reaction`;

CREATE TABLE `p_d_reaction`
(
    `p_d_debate_id` INTEGER NOT NULL,
    `parent_reaction_id` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    `tree_left` INTEGER,
    `tree_right` INTEGER,
    `tree_level` INTEGER,
    `id` INTEGER NOT NULL,
    `p_user_id` INTEGER,
    `title` VARCHAR(100),
    `file_name` VARCHAR(150),
    `summary` TEXT,
    `description` TEXT,
    `note_pos` INTEGER DEFAULT 0,
    `note_neg` INTEGER DEFAULT 0,
    `nb_views` INTEGER,
    `published` TINYINT(1),
    `published_at` DATETIME,
    `published_by` VARCHAR(300),
    `favorite` TINYINT(1),
    `online` TINYINT(1),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_d_reaction_slug` (`slug`(255)),
    INDEX `p_d_reaction_FI_1` (`p_d_debate_id`),
    INDEX `p_d_reaction_I_2` (`p_user_id`),
    CONSTRAINT `p_d_reaction_FK_1`
        FOREIGN KEY (`p_d_debate_id`)
        REFERENCES `p_d_debate` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_d_reaction_FK_2`
        FOREIGN KEY (`id`)
        REFERENCES `p_document` (`id`)
        ON DELETE CASCADE,
    CONSTRAINT `p_d_reaction_FK_3`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_d_comment
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_d_comment`;

CREATE TABLE `p_d_comment`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER,
    `p_document_id` INTEGER NOT NULL,
    `description` TEXT,
    `paragraph_no` INTEGER,
    `note_pos` INTEGER DEFAULT 0,
    `note_neg` INTEGER DEFAULT 0,
    `published_at` DATETIME,
    `published_by` VARCHAR(300),
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_d_comment_FI_1` (`p_user_id`),
    INDEX `p_d_comment_FI_2` (`p_document_id`),
    CONSTRAINT `p_d_comment_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `p_d_comment_FK_2`
        FOREIGN KEY (`p_document_id`)
        REFERENCES `p_document` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_d_d_tagged_t
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_d_d_tagged_t`;

CREATE TABLE `p_d_d_tagged_t`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_d_debate_id` INTEGER NOT NULL,
    `p_tag_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_d_d_tagged_t_FI_1` (`p_d_debate_id`),
    INDEX `p_d_d_tagged_t_FI_2` (`p_tag_id`),
    CONSTRAINT `p_d_d_tagged_t_FK_1`
        FOREIGN KEY (`p_d_debate_id`)
        REFERENCES `p_d_debate` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_d_d_tagged_t_FK_2`
        FOREIGN KEY (`p_tag_id`)
        REFERENCES `p_tag` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_tag_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_tag_archive`;

CREATE TABLE `p_tag_archive`
(
    `id` INTEGER NOT NULL,
    `p_t_tag_type_id` INTEGER NOT NULL,
    `p_user_id` INTEGER,
    `title` VARCHAR(150),
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `archived_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_tag_archive_I_1` (`p_t_tag_type_id`),
    INDEX `p_tag_archive_I_2` (`p_user_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_r_badge_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_r_badge_archive`;

CREATE TABLE `p_r_badge_archive`
(
    `id` INTEGER NOT NULL,
    `p_r_badge_type_id` INTEGER NOT NULL,
    `p_r_badge_metal_id` INTEGER NOT NULL,
    `title` VARCHAR(150),
    `description` TEXT,
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `archived_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_r_badge_archive_I_1` (`p_r_badge_type_id`),
    INDEX `p_r_badge_archive_I_2` (`p_r_badge_metal_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_order_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_order_archive`;

CREATE TABLE `p_order_archive`
(
    `id` INTEGER NOT NULL,
    `p_user_id` INTEGER,
    `p_o_order_state_id` INTEGER,
    `p_o_payment_state_id` INTEGER,
    `p_o_payment_type_id` INTEGER,
    `p_o_subscription_id` INTEGER,
    `subscription_title` VARCHAR(150),
    `subscription_description` TEXT,
    `subscription_begin_at` DATE,
    `subscription_end_at` DATE,
    `information` TEXT,
    `price` DECIMAL(10,2),
    `promotion` DECIMAL(10,2),
    `total` DECIMAL(10,2),
    `gender` TINYINT,
    `name` VARCHAR(150),
    `firstname` VARCHAR(150),
    `phone` VARCHAR(30),
    `email` VARCHAR(255),
    `invoice_ref` VARCHAR(250),
    `invoice_at` DATETIME,
    `invoice_filename` VARCHAR(250),
    `supporting_document` VARCHAR(250),
    `elective_mandates` TEXT,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `archived_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_order_archive_I_1` (`p_user_id`),
    INDEX `p_order_archive_I_2` (`p_o_order_state_id`),
    INDEX `p_order_archive_I_3` (`p_o_payment_state_id`),
    INDEX `p_order_archive_I_4` (`p_o_payment_type_id`),
    INDEX `p_order_archive_I_5` (`p_o_subscription_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_user_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_user_archive`;

CREATE TABLE `p_user_archive`
(
    `id` INTEGER NOT NULL,
    `provider` VARCHAR(255),
    `provider_id` VARCHAR(255),
    `nickname` VARCHAR(255),
    `realname` VARCHAR(255),
    `username` VARCHAR(255),
    `username_canonical` VARCHAR(255),
    `email` VARCHAR(255),
    `email_canonical` VARCHAR(255),
    `enabled` TINYINT(1) DEFAULT 0,
    `salt` VARCHAR(255) NOT NULL,
    `password` VARCHAR(255) NOT NULL,
    `last_login` DATETIME,
    `locked` TINYINT(1) DEFAULT 0,
    `expired` TINYINT(1) DEFAULT 0,
    `expires_at` DATETIME,
    `confirmation_token` VARCHAR(255),
    `password_requested_at` DATETIME,
    `credentials_expired` TINYINT(1) DEFAULT 0,
    `credentials_expire_at` DATETIME,
    `roles` TEXT,
    `last_activity` DATETIME,
    `p_u_status_id` INTEGER NOT NULL,
    `file_name` VARCHAR(150),
    `back_file_name` VARCHAR(150),
    `gender` TINYINT,
    `firstname` VARCHAR(150),
    `name` VARCHAR(150),
    `birthday` DATE,
    `subtitle` VARCHAR(500),
    `biography` TEXT,
    `website` VARCHAR(150),
    `twitter` VARCHAR(150),
    `facebook` VARCHAR(150),
    `phone` VARCHAR(30),
    `newsletter` TINYINT(1),
    `last_connect` DATETIME,
    `nb_connected_days` INTEGER DEFAULT 0,
    `nb_views` INTEGER,
    `qualified` TINYINT(1),
    `validated` TINYINT(1) DEFAULT 0,
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    `archived_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_user_archive_I_1` (`p_u_status_id`),
    INDEX `p_user_archive_I_2` (`username_canonical`),
    INDEX `p_user_archive_I_3` (`email_canonical`),
    INDEX `p_user_archive_I_4` (`slug`(255))
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_u_mandate_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_u_mandate_archive`;

CREATE TABLE `p_u_mandate_archive`
(
    `id` INTEGER NOT NULL,
    `p_user_id` INTEGER NOT NULL,
    `p_q_type_id` INTEGER NOT NULL,
    `p_q_mandate_id` INTEGER NOT NULL,
    `p_q_organization_id` INTEGER,
    `localization` VARCHAR(150),
    `begin_at` DATE,
    `end_at` DATE,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `archived_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_mandate_archive_I_1` (`p_user_id`),
    INDEX `p_u_mandate_archive_I_2` (`p_q_type_id`),
    INDEX `p_u_mandate_archive_I_3` (`p_q_mandate_id`),
    INDEX `p_u_mandate_archive_I_4` (`p_q_organization_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_document_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_document_archive`;

CREATE TABLE `p_document_archive`
(
    `id` INTEGER NOT NULL,
    `p_user_id` INTEGER,
    `title` VARCHAR(100),
    `file_name` VARCHAR(150),
    `summary` TEXT,
    `description` TEXT,
    `note_pos` INTEGER DEFAULT 0,
    `note_neg` INTEGER DEFAULT 0,
    `nb_views` INTEGER,
    `published` TINYINT(1),
    `published_at` DATETIME,
    `published_by` VARCHAR(300),
    `favorite` TINYINT(1),
    `online` TINYINT(1),
    `archived_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_document_archive_I_1` (`p_user_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_d_debate_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_d_debate_archive`;

CREATE TABLE `p_d_debate_archive`
(
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    `id` INTEGER NOT NULL,
    `p_user_id` INTEGER,
    `title` VARCHAR(100),
    `file_name` VARCHAR(150),
    `summary` TEXT,
    `description` TEXT,
    `note_pos` INTEGER DEFAULT 0,
    `note_neg` INTEGER DEFAULT 0,
    `nb_views` INTEGER,
    `published` TINYINT(1),
    `published_at` DATETIME,
    `published_by` VARCHAR(300),
    `favorite` TINYINT(1),
    `online` TINYINT(1),
    `archived_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_d_debate_archive_I_1` (`p_user_id`),
    INDEX `p_d_debate_archive_I_2` (`slug`(255))
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_d_reaction_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_d_reaction_archive`;

CREATE TABLE `p_d_reaction_archive`
(
    `p_d_debate_id` INTEGER NOT NULL,
    `parent_reaction_id` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    `tree_left` INTEGER,
    `tree_right` INTEGER,
    `tree_level` INTEGER,
    `id` INTEGER NOT NULL,
    `p_user_id` INTEGER,
    `title` VARCHAR(100),
    `file_name` VARCHAR(150),
    `summary` TEXT,
    `description` TEXT,
    `note_pos` INTEGER DEFAULT 0,
    `note_neg` INTEGER DEFAULT 0,
    `nb_views` INTEGER,
    `published` TINYINT(1),
    `published_at` DATETIME,
    `published_by` VARCHAR(300),
    `favorite` TINYINT(1),
    `online` TINYINT(1),
    `archived_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_d_reaction_archive_I_1` (`p_d_debate_id`),
    INDEX `p_d_reaction_archive_I_2` (`p_user_id`),
    INDEX `p_d_reaction_archive_I_3` (`slug`(255))
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_d_comment_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_d_comment_archive`;

CREATE TABLE `p_d_comment_archive`
(
    `id` INTEGER NOT NULL,
    `p_user_id` INTEGER,
    `p_document_id` INTEGER NOT NULL,
    `description` TEXT,
    `paragraph_no` INTEGER,
    `note_pos` INTEGER DEFAULT 0,
    `note_neg` INTEGER DEFAULT 0,
    `published_at` DATETIME,
    `published_by` VARCHAR(300),
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `archived_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_d_comment_archive_I_1` (`p_user_id`),
    INDEX `p_d_comment_archive_I_2` (`p_document_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_d_d_tagged_t_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_d_d_tagged_t_archive`;

CREATE TABLE `p_d_d_tagged_t_archive`
(
    `id` INTEGER NOT NULL,
    `p_d_debate_id` INTEGER NOT NULL,
    `p_tag_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `archived_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_d_d_tagged_t_archive_I_1` (`p_d_debate_id`),
    INDEX `p_d_d_tagged_t_archive_I_2` (`p_tag_id`)
) ENGINE=InnoDB;

# This restores the fkey checks, after having unset them earlier
SET FOREIGN_KEY_CHECKS = 1;
