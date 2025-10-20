CREATE DATABASE IF NOT EXISTS opencart DEFAULT CHARACTER SET utf8mb4;
CREATE USER IF NOT EXISTS 'root'@'%' IDENTIFIED BY 'opencart';
GRANT ALL PRIVILEGES ON *.* TO 'root'@'%' WITH GRANT OPTION;
FLUSH PRIVILEGES;
USE opencart;
SOURCE /tmp/vend_db.sql;
UPDATE `oc_setting` SET `setting_id` = '13061',`store_id` = '0',`code` = 'config',`key` = 'config_seo_url',`value` = '0',`serialized` = '0' WHERE `setting_id` = '13061';
