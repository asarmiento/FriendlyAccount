ALTER TABLE  `deposits` ADD INDEX (  `catalog_id` ) ;
ALTER TABLE  `deposits` CHANGE  `catalog_id`  `catalog_id` INT( 10 ) UNSIGNED NOT NULL ;
ALTER TABLE  `deposits` ADD CONSTRAINT  `catalogs_deposits_foreign` FOREIGN KEY ( `catalog_id` ) REFERENCES  `softgboo_modules`.`catalogs` (

`id`
) ON DELETE RESTRICT ON UPDATE RESTRICT ;