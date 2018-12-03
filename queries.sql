-- Добавление категорий
INSERT INTO category SET title = 'Доски и лыжи';
INSERT INTO category SET title = 'Крепления';
INSERT INTO category SET title = 'Ботинки';
INSERT INTO category SET title = 'Одежда';
INSERT INTO category SET title = 'Инструменты';
INSERT INTO category SET title = 'Разное';

-- Добавление пользователей
INSERT INTO user (email, name, password, avatar_url, contacts) VALUES ('malahovk@gmail.com', 'Кирилл', '12345', 'kirill.jpg', 'skype:whiteiceweb');
INSERT INTO user (email, name, password, avatar_url, contacts) VALUES ('keks@htmlacademy.com', 'Кекс', 'meow', 'keks.jpg', 'skype:keks');

-- Добавление лотов
INSERT INTO lot (title, category_id, user_id, description, img_url, price_start, end_at, bet_step)
    VALUES ('2014 Rossignol District Snowboard', 1, 1, 'Описание этой доски', 'img/lot-1.jpg', 10999.5, '31.12.2018', 500);

INSERT INTO lot (title, category_id, user_id, description, img_url, price_start, end_at, bet_step)
    VALUES ('DC Ply Mens 2016/2017 Snowboard', 1, 1, 'Описание этой лыжи', 'img/lot-2.jpg', 159999, '2018.12.15', 500);

INSERT INTO lot (title, category_id, user_id, description, img_url, price_start, end_at, bet_step)
    VALUES ('Крепления Union Contact Pro 2015 года размер L/XL', 2, 1, 'Описание этой штуки', 'img/lot-3.jpg', 8000, '2018.12.15', 300);

INSERT INTO lot (title, category_id, user_id, description, img_url, price_start, end_at, bet_step)
    VALUES ('Ботинки для сноуборда DC Mutiny Charocal', 3, 2, 'Описание Ботинка', 'img/lot-4.jpg', 10999, '2018.12.2', 300);

INSERT INTO lot (title, category_id, user_id, description, img_url, price_start, end_at, bet_step)
    VALUES ('Куртка для сноуборда DC Mutiny Charocal', 4, 1, 'Описание куртки', 'img/lot-5.jpg', 7500, '2018.12.1', 200);

INSERT INTO lot (title, category_id, user_id, description, img_url, price_start, end_at, bet_step)
    VALUES ('Маска Oakley Canopy', 6, 1, 'Описание куртки', 'img/lot-5.jpg', 5400, '2018.11.30', 200);

-- Добавление ставок
INSERT INTO bet (bet_value, user_id, lot_id )
    VALUES (11000, 2, 1);

INSERT INTO bet (bet_value, user_id, lot_id )
    VALUES (12000, 1, 1);

INSERT INTO bet (bet_value, user_id, lot_id )
    VALUES (14000, 2, 1);


-- Получить все категории
SELECT * FROM category;

---- Получить самые новые, открытые лоты.
---- Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории;
SELECT l.id, l.title, l.price_start, l.img_url, MAX(b.bet_value) as price_current, c.title
FROM lot l
	LEFT JOIN bet b ON l.id = b.lot_id
	JOIN category c ON c.id = l.category_id
WHERE NOW() < end_at
    AND l.created_at <= NOW()
GROUP BY l.id
ORDER BY l.created_at DESC;

-- Показать лот по его id. Получите также название категории, к которой принадлежит лот
SELECT l.id, l.created_at, l.title, l.description, l.img_url, l.price_start, l.end_at, l.bet_step, l.category_id, l.user_id, l.winner_user_id, c.title as cat_title FROM lot l
    JOIN category c
    ON l.category_id = c.id
    WHERE l.id = 3

-- Обновить название лота по его идентификатору
UPDATE lot
    SET title = 'Новое_название'
    WHERE id = 3;

-- Получить список самых свежих ставок для лота по его идентификатору;
SELECT b.id, b.created_at, b.bet_value, b.user_id, b.lot_id FROM lot l
    JOIN bet b
    ON l.id = b.lot_id
    WHERE l.id = 1;
