CREATE DATABASE yeticave
    DEFAULT CHARACTER SET utf8
    DEFAULT COLLATE utf8_general_ci;

USE yeticave;

CREATE TABLE user(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    email CHAR(128) NOT NULL UNIQUE KEY,
    name CHAR(128) NOT NULL,
    password CHAR(64) NOT NULL,
    avatar_url CHAR(255) NULL DEFAULT NULL,
    contacts TEXT NOT NULL
);

CREATE TABLE category(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    title CHAR(128) NOT NULL UNIQUE KEY
);

CREATE TABLE lot(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL,
    title CHAR(128) NOT NULL UNIQUE KEY,
    description CHAR(255) NOT NULL,
    img_url CHAR(255) NOT NULL,
    price_start FLOAT NOT NULL,
    end_at DATETIME NOT NULL,
    bet_step INT NOT NULL,
    category_id    int NOT NULL,
    user_id        int NOT NULL,
    winner_user_id int NULL DEFAULT NULL,
    KEY lot_user_id_fk (user_id),
    KEY lot_category_id_fk (category_id),
    KEY lot_winner_user_id_fk (winner_user_id),
    CONSTRAINT lot_category_id_fk FOREIGN KEY (category_id) REFERENCES category (id) ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT lot_user_id_fk FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT lot_winner_user_id_fk FOREIGN KEY (winner_user_id) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT
);

CREATE TABLE bet(
    id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
    created_at TIMESTAMP  NOT NULL DEFAULT CURRENT_TIMESTAMP,
    bet_value INT NOT NULL,
    user_id    int NOT NULL,
    lot_id     int NOT NULL,
    KEY bet_user_id_fk (user_id),
    KEY bet_lot_id_fk (lot_id),
    CONSTRAINT bet_lot_id_fk FOREIGN KEY (lot_id) REFERENCES lot (id) ON UPDATE RESTRICT ON DELETE RESTRICT,
    CONSTRAINT bet_user_id_fk FOREIGN KEY (user_id) REFERENCES user (id) ON UPDATE RESTRICT ON DELETE RESTRICT
);
