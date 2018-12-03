-- ���������� ���������
INSERT INTO category SET title = '����� � ����';
INSERT INTO category SET title = '���������';
INSERT INTO category SET title = '�������';
INSERT INTO category SET title = '������';
INSERT INTO category SET title = '�����������';
INSERT INTO category SET title = '������';

-- ���������� �������������
INSERT INTO user (email, name, password, avatar_url) VALUES ('malahovk@gmail.com', '������', '12345', 'kirill.jpg');
INSERT INTO user (email, name, password, avatar_url) VALUES ('keks@htmlacademy.com', '����', 'meow', 'keks.jpg');

-- ���������� �����
INSERT INTO lot (title, category_id, user_id, description, img_url, price_start, end_at, bet_step)
    VALUES ('2014 Rossignol District Snowboard', 1, 1, '�������� ���� �����', 'img/lot-1.jpg', 10999.5, '31.12.2018', 500);

INSERT INTO lot (title, category_id, user_id, description, img_url, price_start, end_at, bet_step)
    VALUES ('DC Ply Mens 2016/2017 Snowboard', 1, 1, '�������� ���� ����', 'img/lot-2.jpg', 159999, '2018.12.15', 500);

INSERT INTO lot (title, category_id, user_id, description, img_url, price_start, end_at, bet_step)
    VALUES ('��������� Union Contact Pro 2015 ���� ������ L/XL', 2, 1, '�������� ���� �����', 'img/lot-3.jpg', 8000, '2018.12.15', 300);

INSERT INTO lot (title, category_id, user_id, description, img_url, price_start, end_at, bet_step)
    VALUES ('������� ��� ��������� DC Mutiny Charocal', 3, 2, '�������� �������', 'img/lot-4.jpg', 10999, '2018.12.20', 300);

INSERT INTO lot (title, category_id, user_id, description, img_url, price_start, end_at, bet_step)
    VALUES ('������ ��� ��������� DC Mutiny Charocal', 4, 1, '�������� ������', 'img/lot-5.jpg', 7500, '', 200);

INSERT INTO lot (title, category_id, user_id, description, img_url, price_start, end_at, bet_step)
    VALUES ('����� Oakley Canopy', 6, 1, '�������� ������', 'img/lot-5.jpg', 5400, '', 200);

-- ���������� �����
INSERT INTO bet (bet_value, user_id, lot_id )
    VALUES (11000, 2, 1);

INSERT INTO bet (bet_value, user_id, lot_id )
    VALUES (12000, 1, 1);


-- �������� ��� ���������
SELECT * FROM category;

---- �������� ����� �����, �������� ����.
---- ������ ��� ������ �������� ��������, ��������� ����, ������ �� �����������, ����, �������� ���������;
SELECT
    l.title, l.price_start, l.img_url, c.title
FROM
    lot l JOIN category c
WHERE
    end_at < STR_TO_DATE('2018-30-11 00:00:00', '%Y-%m-%d %H:%i:%s')
-- ? �������� ����, ��� �� � ������� end_at < ������ ����
-- ? � ��� �������� ����? �������� ��� ������ � ��������� �� � ���������?
-- ��� ��� ���������� ������� � ����� �������?
    
-- �������� ��� �� ��� id. �������� ����� �������� ���������, � ������� ����������� ���
SELECT l.*, c.title FROM lot l
    JOIN category c
    ON l.category_id = c.id
    WHERE l.id = 3

-- �������� �������� ���� �� ��� ��������������
UPDATE lot 
    SET title = '�����_��������'
    WHERE id = 3;

-- �������� ������ ����� ������ ������ ��� ���� �� ��� ��������������;
SELECT b.* FROM lot l
    JOIN bet b
    ON l.id = b.lot_id
    WHERE l.id = 1;
-- ? ��� ������ "����� ������ ������" ?




















