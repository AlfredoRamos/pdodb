DROP DATABASE IF EXISTS test_db;

CREATE DATABASE test_db WITH ENCODING = 'UTF8';

\connect test_db

DROP TABLE IF EXISTS t_users;

CREATE TABLE IF NOT EXISTS t_users (
	id serial PRIMARY KEY,
	first_name character varying(50) NOT NULL,
	last_name character varying(50) NOT NULL,
	city character varying(50) NOT NULL
);
