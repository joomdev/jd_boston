#
#<?php die('Forbidden.'); ?>
#Date: 2019-09-26 21:50:31 UTC
#Software: Joomla Platform 13.1.0 Stable [ Curiosity ] 24-Apr-2013 00:00 GMT

#Fields: datetime	priority clientip	category	message
2019-09-26T21:50:31+00:00	INFO ::1	update	Update started by user Super User (45). Old version is 3.9.6.
2019-09-26T21:50:36+00:00	INFO ::1	update	Downloading update file from https://s3-us-west-2.amazonaws.com/joomla-official-downloads/joomladownloads/joomla3/Joomla_3.9.12-Stable-Update_Package.zip?X-Amz-Algorithm=AWS4-HMAC-SHA256&X-Amz-Credential=AKIAIZ6S3Q3YQHG57ZRA%2F20190926%2Fus-west-2%2Fs3%2Faws4_request&X-Amz-Date=20190926T215039Z&X-Amz-Expires=60&X-Amz-SignedHeaders=host&X-Amz-Signature=cd780b8c279a70f9d6c6f7dacfa146a60ac59edd13c01ffe2298eaf10b86ec1e.
2019-09-26T21:51:29+00:00	INFO ::1	update	File Joomla_3.9.12-Stable-Update_Package.zip downloaded.
2019-09-26T21:51:29+00:00	INFO ::1	update	Starting installation of new version.
2019-09-26T21:51:35+00:00	INFO ::1	update	Finalising installation.
2019-09-26T21:51:36+00:00	INFO ::1	update	Ran query from file 3.9.7-2019-04-23. Query text: ALTER TABLE `#__session` ADD INDEX `client_id_guest` (`client_id`, `guest`);.
2019-09-26T21:51:36+00:00	INFO ::1	update	Ran query from file 3.9.7-2019-04-26. Query text: UPDATE `#__content_types` SET `content_history_options` = REPLACE(`content_histo.
2019-09-26T21:51:36+00:00	INFO ::1	update	Ran query from file 3.9.8-2019-06-11. Query text: UPDATE #__users SET params = REPLACE(params, '",,"', '","');.
2019-09-26T21:51:36+00:00	INFO ::1	update	Ran query from file 3.9.8-2019-06-15. Query text: ALTER TABLE `#__template_styles` DROP INDEX `idx_home`;.
2019-09-26T21:51:36+00:00	INFO ::1	update	Ran query from file 3.9.8-2019-06-15. Query text: ALTER TABLE `#__template_styles` ADD INDEX `idx_client_id` (`client_id`);.
2019-09-26T21:51:37+00:00	INFO ::1	update	Ran query from file 3.9.8-2019-06-15. Query text: ALTER TABLE `#__template_styles` ADD INDEX `idx_client_id_home` (`client_id`, `h.
2019-09-26T21:51:37+00:00	INFO ::1	update	Ran query from file 3.9.10-2019-07-09. Query text: ALTER TABLE `#__template_styles` MODIFY `home` char(7) NOT NULL DEFAULT '0';.
2019-09-26T21:51:37+00:00	INFO ::1	update	Deleting removed files and folders.
2019-09-26T21:51:40+00:00	INFO ::1	update	Cleaning up after installation.
2019-09-26T21:51:40+00:00	INFO ::1	update	Update to version 3.9.12 is complete.
