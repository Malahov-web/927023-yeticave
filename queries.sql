-- Добавление категорий
INSERT INTO category SET title = 'Доски и лыжи';
INSERT INTO category SET title = 'Крепления';
INSERT INTO category SET title = 'Ботинки';
INSERT INTO category SET title = 'Одежда';
INSERT INTO category SET title = 'Инструменты';
INSERT INTO category SET title = 'Разное';

-- Добавление пользователей
INSERT INTO user (email, name, password, avatar_url) VALUES ('malahovk@gmail.com', 'Кирилл', '12345', 'kirill.jpg');
INSERT INTO user (email, name, password, avatar_url) VALUES ('keks@htmlacademy.com', 'Кекс', 'meow', 'keks.jpg');

-- Добавление лотов
INSERT INTO lot (title, category_id, user_id, description, img_url, price_start, end_at, bet_step)
    VALUES ('2014 Rossignol District Snowboard', 1, 1, 'Описание этой доски', 'img/lot-1.jpg', 10999.5, '31.12.2018', 500);

INSERT INTO lot (title, category_id, user_id, description, img_url, price_start, end_at, bet_step)
    VALUES ('DC Ply Mens 2016/2017 Snowboard', 1, 1, 'Описание этой лыжи', 'img/lot-2.jpg', 159999, '2018.12.15', 500);

INSERT INTO lot (title, category_id, user_id, description, img_url, price_start, end_at, bet_step)
    VALUES ('Крепления Union Contact Pro 2015 года размер L/XL', 2, 1, 'Описание этой штуки', 'img/lot-3.jpg', 8000, '2018.12.15', 300);

INSERT INTO lot (title, category_id, user_id, description, img_url, price_start, end_at, bet_step)
    VALUES ('Ботинки для сноуборда DC Mutiny Charocal', 3, 2, 'Описание Ботинка', 'img/lot-4.jpg', 10999, '2018.12.20', 300);

INSERT INTO lot (title, category_id, user_id, description, img_url, price_start, end_at, bet_step)
    VALUES ('Куртка для сноуборда DC Mutiny Charocal', 4, 1, 'Описание куртки', 'img/lot-5.jpg', 7500, '', 200);

INSERT INTO lot (title, category_id, user_id, description, img_url, price_start, end_at, bet_step)
    VALUES ('Маска Oakley Canopy', 6, 1, 'Описание куртки', 'img/lot-5.jpg', 5400, '', 200);

-- Добавление лотов
INSERT INTO bet (bet_value, user_id, lot_id )
    VALUES (11000, 2, 1);

INSERT INTO bet (bet_value, user_id, lot_id )
    VALUES (12000, 1, 1);


-- Получить все категории
SELECT * FROM category;

---- Получить самые новые, открытые лоты.
---- Каждый лот должен включать название, стартовую цену, ссылку на изображение, цену, название категории;
SELECT
    l.title, l.price_start, l.img_url, c.title
FROM
    lot l JOIN category c
WHERE
    end_at < STR_TO_DATE('2018-30-11 00:00:00', '%Y-%m-%d %H:%i:%s')
-- ? Открытые лоты, это те у которых end_at < текщей даты
-- ? А как получить цену? Получить все ставки и прибавить их к стартовой?
-- Это все необходимо сделать в одном запросе?
    
-- Показать лот по его id. Получите также название категории, к которой принадлежит лот
SELECT l.*, c.title FROM lot l
    JOIN category c
    ON l.category_id = c.id
    WHERE l.id = 3

-- Обновить название лота по его идентификатору
UPDATE lot 
    SET title = 'Новое_название'
    WHERE id = 3;

-- Получить список самых свежих ставок для лота по его идентификатору;
SELECT b.* FROM lot l
    JOIN bet b
    ON l.id = b.lot_id
    WHERE l.id = 1;
-- ? Как понять "Самых свежих ставок" ?




















