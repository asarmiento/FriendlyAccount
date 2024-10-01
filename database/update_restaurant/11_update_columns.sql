ALTER TABLE `order_salons` 
ADD `canceled` boolean default false AFTER `modify`,
ADD `description` text AFTER `canceled`;