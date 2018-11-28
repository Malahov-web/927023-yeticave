CREATE DATABASE yeticave
	DEFAULT CHARACTER SET utf8
	DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE lot (
	id INT AUTO_INCREMENT PRIMARY KEY,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
	title CHAR NOT NULL,
	description CHAR,
	img_url CHAR NOT NULL,
	price_start INT NOT NULL,
    end_date DATETIME,
	bet_step INT,
	cat_id INT references category(id),
	user_id INT references user(id),
	winner_id INT references user(id)
);

CREATE TABLE category(
	id INT AUTO_INCREMENT PRIMARY KEY,
	title CHAR,
    UNIQUE INDEX index_category (title)
);

CREATE TABLE user(
    id INT AUTO_INCREMENT PRIMARY KEY,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    email CHAR(128) NOT NULL UNIQUE,
	name CHAR(128),
    pass CHAR(64) NOT NULL UNIQUE,
    avatar_url CHAR NOT NULL,
    contacts TEXT,
    lots_id INT references lot(id),
    bets_id INT references bet(id),
    UNIQUE INDEX index_user (email, pass)
);

CREATE TABLE bet(
	id INT AUTO_INCREMENT PRIMARY KEY,
	created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    bet_value INT,
    user_id INT references user(id),
    lot_id INT references lot(id)
);

CREATE INDEX index_title ON lot(title);
CREATE INDEX index_description ON lot(description);
