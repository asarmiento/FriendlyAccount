

-- Table closing_cash_desks
ALTER TABLE closing_cash_desks
CHANGE taxable_sales service_sales double(15,4) null;
#modify COLUMN created_at timestamp not null default CURRENT_TIMESTAMP,
#modify COLUMN updated_at timestamp not null default CURRENT_TIMESTAMP;

-- Table cash_desk
insert into cash_desks (name, created_at, updated_at)
values ('Caja 1', current_timestamp, current_timestamp);

-- Table salons
ALTER TABLE table_salons
ADD barra tinyint(1) not null default 0;
#modify COLUMN created_at timestamp not null default CURRENT_TIMESTAMP,
#modify COLUMN updated_at timestamp not null default CURRENT_TIMESTAMP;

-- Table invoices
ALTER TABLE invoices
ADD court tinyint(1) not null default 0;
#modify COLUMN created_at timestamp not null default CURRENT_TIMESTAMP,
#modify COLUMN updated_at timestamp not null default CURRENT_TIMESTAMP;
