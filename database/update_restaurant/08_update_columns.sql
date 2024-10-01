-- Table invoices
ALTER TABLE invoices
ADD colones float null after user_auth_id,
ADD dolares float null after colones,
ADD colones_t float null after dolares,
ADD tc float not null after colones_t;


-- Table 
update payment_methods set name = 'Colones', type = 'sale' where id = 3;
update payment_methods set name = 'Dólares', type = 'sale' where id = 4;
update payment_methods set name = 'Tarjeta', type = 'sale' where id = 5;
insert into payment_methods (name, type) 
values 
('Colones y Dólares', 'sale'),
('Tarjeta y Colones', 'sale'),
('Tarjeta y Dólares', 'sale');