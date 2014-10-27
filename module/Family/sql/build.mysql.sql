DROP TABLE IF EXISTS families;
CREATE TABLE families (
	id integer(11) auto_increment primary key,
	familyId varchar(255) NOT NULL unique,
	name varchar(255) NOT NULL,
	phone varchar(255) NOT NULL,
	address varchar(255) NOT NULL
);

DROP TABLE IF EXISTS transactions;
CREATE TABLE transactions (
	id integer(11) auto_increment primary key,
	date date NOT NULL,
	familyId varchar(255) NOT NULL,
	amount decimal(10,2) NOT NULL,
	type varchar(255) NOT NULL
);

DROP TABLE IF EXISTS users;
CREATE TABLE users (
	id integer(11) auto_increment primary key,
	userId varchar(255) NOT NULL unique,
	password varchar(255) NOT NULL,
	role varchar(50)
);