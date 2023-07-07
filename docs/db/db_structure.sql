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
	docstatus TINYINT(1) DEFAULT 0,
	created_by  VARCHAR(140),
	created_on  DATETIME DEFAULT CURRENT_TIMESTAMP,
	modified_by VARCHAR(140),
	modified_on DATETIME DEFAULT CURRENT_TIMESTAMP
);

INSERT INTO customers(name, customer_name) VALUES('Customer1', 'Customer1'),
	('Customer2', 'Customer2'), ('Customer3', 'Customer3'), ('Customer4', 'Customer4'),
	('Customer5', 'Customer5'),('Customer6', 'Customer6'),('Customer7', 'Customer7'),
	('Customer8', 'Customer8'),('Customer9', 'Customer9'),('Customer10', 'Customer10');

INSERT INTO customers(name, customer_name) VALUES('Customer-11', 'Customer-11'),
	('Customer-12', 'Customer-12'), ('Customer-13', 'Customer-13'), ('Customer-14', 'Customer-14'),
	('Customer-15', 'Customer-15'),('Customer-16', 'Customer-16'),('Customer-17', 'Customer-17'),
	('Customer-18', 'Customer-18'),('Customer-19', 'Customer-19'),('Customer-20', 'Customer-20');

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