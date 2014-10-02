
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
    `title` VARCHAR(150),
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_tag_slug` (`slug`(255)),
    INDEX `p_tag_FI_1` (`p_t_tag_type_id`),
    CONSTRAINT `p_tag_FK_1`
        FOREIGN KEY (`p_t_tag_type_id`)
        REFERENCES `p_t_tag_type` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
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
    `title` VARCHAR(150),
    `description` TEXT,
    `grade` TINYINT,
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_r_badge_slug` (`slug`(255)),
    INDEX `p_r_badge_FI_1` (`p_r_badge_type_id`),
    CONSTRAINT `p_r_badge_FK_1`
        FOREIGN KEY (`p_r_badge_type_id`)
        REFERENCES `p_r_badge_type` (`id`)
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
    `p_u_type_id` INTEGER NOT NULL,
    `p_u_status_id` INTEGER NOT NULL,
    `file_name` VARCHAR(150),
    `gender` TINYINT,
    `firstname` VARCHAR(150),
    `name` VARCHAR(150),
    `birthday` DATE,
    `biography` TEXT,
    `website` VARCHAR(150),
    `twitter` VARCHAR(150),
    `facebook` VARCHAR(150),
    `phone` VARCHAR(30),
    `newsletter` TINYINT(1),
    `last_connect` DATETIME,
    `nb_views` INTEGER,
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_user_U_1` (`username_canonical`),
    UNIQUE INDEX `p_user_U_2` (`email_canonical`),
    UNIQUE INDEX `p_user_slug` (`slug`(255)),
    INDEX `p_user_FI_1` (`p_u_type_id`),
    INDEX `p_user_FI_2` (`p_u_status_id`),
    CONSTRAINT `p_user_FK_1`
        FOREIGN KEY (`p_u_type_id`)
        REFERENCES `p_u_type` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_user_FK_2`
        FOREIGN KEY (`p_u_status_id`)
        REFERENCES `p_u_status` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_u_type
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_u_type`;

CREATE TABLE `p_u_type`
(
    `id` INTEGER NOT NULL,
    `title` VARCHAR(150),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`)
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
-- p_u_qualification
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_u_qualification`;

CREATE TABLE `p_u_qualification`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER NOT NULL,
    `p_u_political_party_id` INTEGER,
    `p_u_mandate_type_id` INTEGER,
    `description` TEXT,
    `begin_at` DATETIME,
    `end_at` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_qualification_FI_1` (`p_user_id`),
    INDEX `p_u_qualification_FI_2` (`p_u_political_party_id`),
    INDEX `p_u_qualification_FI_3` (`p_u_mandate_type_id`),
    CONSTRAINT `p_u_qualification_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_qualification_FK_2`
        FOREIGN KEY (`p_u_political_party_id`)
        REFERENCES `p_u_political_party` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL,
    CONSTRAINT `p_u_qualification_FK_3`
        FOREIGN KEY (`p_u_mandate_type_id`)
        REFERENCES `p_u_mandate_type` (`id`)
        ON UPDATE CASCADE
        ON DELETE SET NULL
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_u_political_party
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_u_political_party`;

CREATE TABLE `p_u_political_party`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(250),
    `initials` VARCHAR(50),
    `file_name` VARCHAR(150),
    `description` TEXT,
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_u_political_party_slug` (`slug`(255))
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_u_mandate_type
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_u_mandate_type`;

CREATE TABLE `p_u_mandate_type`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `title` VARCHAR(250),
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    `sortable_rank` INTEGER,
    PRIMARY KEY (`id`),
    UNIQUE INDEX `p_u_mandate_type_slug` (`slug`(255))
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
-- p_u_reputation_r_b
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_u_reputation_r_b`;

CREATE TABLE `p_u_reputation_r_b`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER NOT NULL,
    `p_r_badge_id` INTEGER NOT NULL,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_reputation_r_b_FI_1` (`p_user_id`),
    INDEX `p_u_reputation_r_b_FI_2` (`p_r_badge_id`),
    CONSTRAINT `p_u_reputation_r_b_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_reputation_r_b_FK_2`
        FOREIGN KEY (`p_r_badge_id`)
        REFERENCES `p_r_badge` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_u_reputation_r_a
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_u_reputation_r_a`;

CREATE TABLE `p_u_reputation_r_a`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER NOT NULL,
    `p_r_action_id` INTEGER NOT NULL,
    `p_object_name` VARCHAR(150),
    `p_object_id` INTEGER,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_reputation_r_a_FI_1` (`p_user_id`),
    INDEX `p_u_reputation_r_a_FI_2` (`p_r_action_id`),
    CONSTRAINT `p_u_reputation_r_a_FK_1`
        FOREIGN KEY (`p_user_id`)
        REFERENCES `p_user` (`id`)
        ON UPDATE CASCADE
        ON DELETE CASCADE,
    CONSTRAINT `p_u_reputation_r_a_FK_2`
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
-- p_document
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_document`;

CREATE TABLE `p_document`
(
    `id` INTEGER NOT NULL AUTO_INCREMENT,
    `p_user_id` INTEGER,
    `title` VARCHAR(100),
    `summary` TEXT,
    `description` TEXT,
    `more_info` TEXT,
    `note_pos` INTEGER,
    `note_neg` INTEGER,
    `nb_views` INTEGER,
    `published` TINYINT(1),
    `published_at` DATETIME,
    `published_by` VARCHAR(300),
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
    `file_name` VARCHAR(150),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    `id` INTEGER NOT NULL,
    `p_user_id` INTEGER,
    `title` VARCHAR(100),
    `summary` TEXT,
    `description` TEXT,
    `more_info` TEXT,
    `note_pos` INTEGER,
    `note_neg` INTEGER,
    `nb_views` INTEGER,
    `published` TINYINT(1),
    `published_at` DATETIME,
    `published_by` VARCHAR(300),
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
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    `tree_left` INTEGER,
    `tree_right` INTEGER,
    `tree_level` INTEGER,
    `id` INTEGER NOT NULL,
    `p_user_id` INTEGER,
    `title` VARCHAR(100),
    `summary` TEXT,
    `description` TEXT,
    `more_info` TEXT,
    `note_pos` INTEGER,
    `note_neg` INTEGER,
    `nb_views` INTEGER,
    `published` TINYINT(1),
    `published_at` DATETIME,
    `published_by` VARCHAR(300),
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
    `note_pos` INTEGER,
    `note_neg` INTEGER,
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
    `title` VARCHAR(150),
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `archived_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_tag_archive_I_1` (`p_t_tag_type_id`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_r_badge_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_r_badge_archive`;

CREATE TABLE `p_r_badge_archive`
(
    `id` INTEGER NOT NULL,
    `p_r_badge_type_id` INTEGER NOT NULL,
    `title` VARCHAR(150),
    `description` TEXT,
    `grade` TINYINT,
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `archived_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_r_badge_archive_I_1` (`p_r_badge_type_id`)
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
    `p_u_type_id` INTEGER NOT NULL,
    `p_u_status_id` INTEGER NOT NULL,
    `file_name` VARCHAR(150),
    `gender` TINYINT,
    `firstname` VARCHAR(150),
    `name` VARCHAR(150),
    `birthday` DATE,
    `biography` TEXT,
    `website` VARCHAR(150),
    `twitter` VARCHAR(150),
    `facebook` VARCHAR(150),
    `phone` VARCHAR(30),
    `newsletter` TINYINT(1),
    `last_connect` DATETIME,
    `nb_views` INTEGER,
    `online` TINYINT(1),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `archived_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_user_archive_I_1` (`p_u_type_id`),
    INDEX `p_user_archive_I_2` (`p_u_status_id`),
    INDEX `p_user_archive_I_3` (`username_canonical`),
    INDEX `p_user_archive_I_4` (`email_canonical`)
) ENGINE=InnoDB;

-- ---------------------------------------------------------------------
-- p_u_qualification_archive
-- ---------------------------------------------------------------------

DROP TABLE IF EXISTS `p_u_qualification_archive`;

CREATE TABLE `p_u_qualification_archive`
(
    `id` INTEGER NOT NULL,
    `p_user_id` INTEGER NOT NULL,
    `p_u_political_party_id` INTEGER,
    `p_u_mandate_type_id` INTEGER,
    `description` TEXT,
    `begin_at` DATETIME,
    `end_at` DATETIME,
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `archived_at` DATETIME,
    PRIMARY KEY (`id`),
    INDEX `p_u_qualification_archive_I_1` (`p_user_id`),
    INDEX `p_u_qualification_archive_I_2` (`p_u_political_party_id`),
    INDEX `p_u_qualification_archive_I_3` (`p_u_mandate_type_id`)
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
    `summary` TEXT,
    `description` TEXT,
    `more_info` TEXT,
    `note_pos` INTEGER,
    `note_neg` INTEGER,
    `nb_views` INTEGER,
    `published` TINYINT(1),
    `published_at` DATETIME,
    `published_by` VARCHAR(300),
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
    `file_name` VARCHAR(150),
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    `id` INTEGER NOT NULL,
    `p_user_id` INTEGER,
    `title` VARCHAR(100),
    `summary` TEXT,
    `description` TEXT,
    `more_info` TEXT,
    `note_pos` INTEGER,
    `note_neg` INTEGER,
    `nb_views` INTEGER,
    `published` TINYINT(1),
    `published_at` DATETIME,
    `published_by` VARCHAR(300),
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
    `created_at` DATETIME,
    `updated_at` DATETIME,
    `slug` VARCHAR(255),
    `tree_left` INTEGER,
    `tree_right` INTEGER,
    `tree_level` INTEGER,
    `id` INTEGER NOT NULL,
    `p_user_id` INTEGER,
    `title` VARCHAR(100),
    `summary` TEXT,
    `description` TEXT,
    `more_info` TEXT,
    `note_pos` INTEGER,
    `note_neg` INTEGER,
    `nb_views` INTEGER,
    `published` TINYINT(1),
    `published_at` DATETIME,
    `published_by` VARCHAR(300),
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
    `note_pos` INTEGER,
    `note_neg` INTEGER,
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
