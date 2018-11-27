CREATE DATABASE yeticave
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE lots (
	id INT AUTO_INCREMENT PRIMARY KEY,
	add_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
	title CHAR,
	descr CHAR,
	img CHAR,
	price_start INT,
    end_date DATETIME,
	bet_step INT,
	cat_id INT,
	user_id INT,
	winner_id INT
);

CREATE TABLE categories(
	id INT AUTO_INCREMENT PRIMARY KEY,
	title CHAR
);

CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
	add_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    email CHAR(128) NOT NULL UNIQUE,
	name CHAR(128),
    pass CHAR(64) NOT NULL UNIQUE,
    avatar CHAR,
    contacts TEXT,
    lots_id INT,
    bets_id INT
);

CREATE TABLE bets(
	id INT AUTO_INCREMENT PRIMARY KEY,
	add_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    bet_value INT,
    user_id INT,
    lot_id INT
);

CREATE INDEX l_title ON lots(title);
CREATE INDEX l_descr ON lots(descr);
CREATE UNIQUE INDEX c_title ON categories(title);
CREATE UNIQUE INDEX u_email ON users(email);
CREATE UNIQUE INDEX u_pass ON users(pass);
