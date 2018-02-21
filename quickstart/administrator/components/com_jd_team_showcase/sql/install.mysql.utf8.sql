CREATE TABLE IF NOT EXISTS `#__jd_team_members` (
`id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `ordering` int(11) NOT NULL,
  `state` tinyint(1) NOT NULL,
  `checked_out` int(11) NOT NULL,
  `checked_out_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  `created_by` int(11) NOT NULL,
  `modified_by` int(11) NOT NULL,
  `member_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `member_image` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `member_bio` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `department` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `job_title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `social_icons` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `facebook_url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `twitter_url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `instagram_url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `pintrest_url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `telephone_number` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_facebook` tinyint(4) NOT NULL,
  `is_twitter` tinyint(4) NOT NULL,
  `is_instagram` tinyint(4) NOT NULL,
  `is_pintrest` tinyint(4) NOT NULL,
  `is_telephone` tinyint(4) NOT NULL,
  `is_email` tinyint(4) NOT NULL,
  `is_linkedin` tinyint(4) NOT NULL,
  `linkedin_url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_googlepluse` tinyint(4) NOT NULL,
  `googlepluse_url` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci AUTO_INCREMENT=6 ;


INSERT INTO `#__content_types` (`type_title`, `type_alias`, `table`, `content_history_options`)
SELECT * FROM ( SELECT 'Team Member','com_jd_team_showcase.teammember','{"special":{"dbtable":"#__jd_team_members","key":"id","type":"Team Member","prefix":"Jd_team_showcaseTable"}}', '{"formFile":"administrator\/components\/com_jd_team_showcase\/models\/forms\/teammember.xml", "hideFields":["checked_out","checked_out_time","params","language"], "ignoreChanges":["modified_by", "modified", "checked_out", "checked_out_time"], "convertToInt":["publish_up", "publish_down"], "displayLookup":[{"sourceColumn":"catid","targetTable":"#__categories","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"group_id","targetTable":"#__usergroups","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"created_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"},{"sourceColumn":"access","targetTable":"#__viewlevels","targetColumn":"id","displayColumn":"title"},{"sourceColumn":"modified_by","targetTable":"#__users","targetColumn":"id","displayColumn":"name"}]}') AS tmp
WHERE NOT EXISTS (
	SELECT type_alias FROM `#__content_types` WHERE (`type_alias` = 'com_jd_team_showcase.teammember')
) LIMIT 1;
