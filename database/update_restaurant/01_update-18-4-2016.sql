INSERT INTO `menus` (`id`, `name`, `url`, `icon_font`, `priority`, `created_at`, `updated_at`, `deleted_at`) VALUES
(30, 'Cambio contraseña', '/institucion/inst/cambio-clave', 'fa fa-cog', '', '2016-04-19 01:29:13', '2016-04-19 01:30:26', NULL);

/**menu*/
INSERT INTO `menu_task` (`task_id`, `menu_id`, `status`) VALUES(1, 30, 1);
INSERT INTO `menu_task` (`task_id`, `menu_id`, `status`) VALUES(2, 30, 0);
INSERT INTO `menu_task` (`task_id`, `menu_id`, `status`) VALUES(3, 30, 0);
INSERT INTO `menu_task` (`task_id`, `menu_id`, `status`) VALUES(4, 30, 0);
INSERT INTO `menu_task` (`task_id`, `menu_id`, `status`) VALUES(5, 30, 0);


ALTER TABLE `cooked_products` ADD `money` ENUM('colones','dolares') NOT NULL AFTER `type`;
#modify COLUMN created_at timestamp not null default CURRENT_TIMESTAMP,
#modify COLUMN updated_at timestamp not null default CURRENT_TIMESTAMP;

ALTER TABLE `menu_restaurants` ADD `money` ENUM('colones','dolares') NOT NULL AFTER `costo`;
#modify COLUMN created_at timestamp not null default CURRENT_TIMESTAMP,
#modify COLUMN updated_at timestamp not null default CURRENT_TIMESTAMP;


ALTER TABLE `menuRestaurant_cookedProduct` ADD `type` ENUM('Base','Adicional') NOT NULL DEFAULT 'Base' AFTER `amount`;
#modify COLUMN created_at timestamp not null default CURRENT_TIMESTAMP,
#modify COLUMN updated_at timestamp not null default CURRENT_TIMESTAMP;


ALTER TABLE `modify_order_salons` ADD `type` ENUM('Base','Adicional') NOT NULL DEFAULT 'Base' AFTER `cooked_product_id`;
#modify COLUMN created_at timestamp not null default CURRENT_TIMESTAMP,
#modify COLUMN updated_at timestamp not null default CURRENT_TIMESTAMP;