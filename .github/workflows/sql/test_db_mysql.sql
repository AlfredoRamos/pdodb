DROP DATABASE IF EXISTS test_db;

CREATE DATABASE IF NOT EXISTS test_db DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

USE test_db;

DROP TABLE IF EXISTS t_users;

CREATE TABLE t_users (
	id int(11) NOT NULL AUTO_INCREMENT,
	first_name varchar(50) COLLATE utf8_unicode_ci NOT NULL,
	last_name varchar(50) COLLATE utf8_unicode_ci NOT NULL,
	city varchar(50) COLLATE utf8_unicode_ci NOT NULL,
	PRIMARY KEY (id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;
