--
-- 2022/11/11
-- Billing System - Database Structure
--
--

--
-- CREATE DATABASE & ADMIN USER FOR THE APPLICATION
--

CREATE DATABASE billing;
CREATE USER 'admin'@'localhost' IDENTIFIED BY 'admin';
GRANT ALL PRIVILEGES ON billing.* TO 'admin'@'localhost';
FLUSH PRIVILEGES;

--
-- USER MANAGEMENT
--

DROP TABLE IF EXISTS users;
CREATE TABLE users(
	name VARCHAR(140) NOT NULL PRIMARY KEY,
	username VARCHAR(140) NOT NULL UNIQUE,
	email VARCHAR(140) NOT NULL,
	password VARCHAR(140) NOT NULL,
	docstatus TINYINT(1) DEFAULT 0,
	created_by  VARCHAR(140),
	created_on  DATETIME DEFAULT CURRENT_TIMESTAMP,
	modified_by VARCHAR(140),
	modified_on DATETIME DEFAULT CURRENT_TIMESTAMP
);

--
-- CUSTOMERS
--

DROP TABLE IF EXISTS customers;
CREATE TABLE customers(
	name VARCHAR(140) NOT NULL PRIMARY KEY,
	customer_name VARCHAR(140) NOT NULL UNIQUE,
	email VARCHAR(140),
	contact_no VARCHAR(140),
	address1 VARCHAR(140),
	address2 VARCHAR(140),
	address3 VARCHAR(140),
	docstatus TINYINT(1) DEFAULT 0,
	created_by  VARCHAR(140),
	created_on  DATETIME DEFAULT CURRENT_TIMESTAMP,
	modified_by VARCHAR(140),
	modified_on DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- INSERT INTO customers(name, customer_name) VALUES('Customer1', 'Customer1'),
-- 	('Customer2', 'Customer2'), ('Customer3', 'Customer3'), ('Customer4', 'Customer4'),
-- 	('Customer5', 'Customer5'),('Customer6', 'Customer6'),('Customer7', 'Customer7'),
-- 	('Customer8', 'Customer8'),('Customer9', 'Customer9'),('Customer10', 'Customer10');

-- INSERT INTO customers(name, customer_name) VALUES('Customer-11', 'Customer-11'),
-- 	('Customer-12', 'Customer-12'), ('Customer-13', 'Customer-13'), ('Customer-14', 'Customer-14'),
-- 	('Customer-15', 'Customer-15'),('Customer-16', 'Customer-16'),('Customer-17', 'Customer-17'),
-- 	('Customer-18', 'Customer-18'),('Customer-19', 'Customer-19'),('Customer-20', 'Customer-20');

--
-- COMMENTS
--

CREATE TABLE comments(
	name VARCHAR(140) NOT NULL PRIMARY KEY,
	comment TEXT NOT NULL,
	docstatus TINYINT(1) DEFAULT 0,
	created_by  VARCHAR(140),
	created_on  DATETIME DEFAULT CURRENT_TIMESTAMP,
	modified_by VARCHAR(140),
	modified_on DATETIME DEFAULT CURRENT_TIMESTAMP
);

--
-- SUBSCRIPTION PLAN
--

CREATE TABLE subscription_plan(
	name VARCHAR(140) NOT NULL PRIMARY KEY,
	plan_name VARCHAR(140) NOT NULL,
	billing_interval VARCHAR(140),
	cost DECIMAL(10,2),
	docstatus TINYINT(1) DEFAULT 0,
	created_by  VARCHAR(140),
	created_on  DATETIME DEFAULT CURRENT_TIMESTAMP,
	modified_by VARCHAR(140),
	modified_on DATETIME DEFAULT CURRENT_TIMESTAMP
);


--
-- SUBSCRIPTION
--

CREATE TABLE subscription(
	name VARCHAR(140) NOT NULL PRIMARY KEY,
	customer VARCHAR(140),
	subscription_plan VARCHAR(140),
	subscription_start DATE DEFAULT CURRENT_DATE,
	subscription_end DATE,
	status 	VARCHAR(140) DEFAULT 'Active',
	docstatus TINYINT(1) DEFAULT 0,
	created_by  VARCHAR(140),
	created_on  DATETIME DEFAULT CURRENT_TIMESTAMP,
	modified_by VARCHAR(140),
	modified_on DATETIME DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (customer) REFERENCES customers(name),
	FOREIGN KEY (subscription_plan) REFERENCES subscription_plan(name)
);


--
-- SERIES
--

CREATE TABLE series(
 name VARCHAR(140) NOT NULL PRIMARY KEY,
 current INT(10)
);

--
-- INVOICE ENTRY
--

CREATE TABLE invoice_entry(
 name VARCHAR(140) NOT NULL PRIMARY KEY,
 invoice_month VARCHAR(140),
 total_invoice_count INT(10),
 total_invoice_amount DECIMAL(10,2),
 docstatus TINYINT(1) DEFAULT 0,
 created_by  VARCHAR(140),
 created_on  DATETIME DEFAULT CURRENT_TIMESTAMP,
 modified_by VARCHAR(140),
 modified_on DATETIME DEFAULT CURRENT_TIMESTAMP
);

--
-- INVOICE
--

CREATE TABLE invoice(
	name VARCHAR(140) NOT NULL PRIMARY KEY,
	customer VARCHAR(140),
	subscription VARCHAR(140),
	subscription_plan VARCHAR(140),
	invoice_entry VARCHAR(140),
	invoice_month VARCHAR(140),
	amount DECIMAL(10,2),
	paid DECIMAL(10,2),
	outstanding DECIMAL(10,2),
	docstatus TINYINT(1) DEFAULT 0,
	created_by  VARCHAR(140),
	created_on  DATETIME DEFAULT CURRENT_TIMESTAMP,
	modified_by VARCHAR(140),
	modified_on DATETIME DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (customer) REFERENCES customers(name),
	FOREIGN KEY (subscription_plan) REFERENCES subscription_plan(name),
	FOREIGN KEY (subscription) REFERENCES subscription(name)
);

--
-- PAYMENT
--

CREATE TABLE payment(
	name VARCHAR(140) NOT NULL PRIMARY KEY,
	customer VARCHAR(140),
	payment_date DATE,
	total_outstanding DECIMAL(10,2),
	paid_amount DECIMAL(10,2),
	balance_amount DECIMAL(10,2),
	mode_of_payment VARCHAR(140),
	payment_reference VARCHAR(140),
	docstatus TINYINT(1) DEFAULT 0,
	created_by  VARCHAR(140),
	created_on  DATETIME DEFAULT CURRENT_TIMESTAMP,
	modified_by VARCHAR(140),
	modified_on DATETIME DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (customer) REFERENCES customers(name)
);

CREATE TABLE payment_item(
	name VARCHAR(140) NOT NULL PRIMARY KEY,
	subscription VARCHAR(140),
	subscription_plan VARCHAR(140),
	invoice VARCHAR(140),
	invoice_month VARCHAR(140),
	invoice_amount DECIMAL(10,2),
	outstanding DECIMAL(10,2),
	paid_amount DECIMAL(10,2),
	payment_date DATE,
	parent VARCHAR(140),
	docstatus TINYINT(1) DEFAULT 0,
	created_by  VARCHAR(140),
	created_on  DATETIME DEFAULT CURRENT_TIMESTAMP,
	modified_by VARCHAR(140),
	modified_on DATETIME DEFAULT CURRENT_TIMESTAMP,
	FOREIGN KEY (subscription) REFERENCES subscription(name),
	FOREIGN KEY (subscription_plan) REFERENCES subscription_plan(name),
	FOREIGN KEY (invoice) REFERENCES invoice(name),
	FOREIGN KEY (parent) REFERENCES payment(name)
);