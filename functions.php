<?php

require_once 'init.php';
require_once 'mysql_helper.php';

/**
 * Подключает шаблон
 *
 * @param $name string Имя шаблона
 * @param $data array Данные для вывода
 *
 * @return string Шаблон страницы
 */
function include_template(string $name, array $data): string
{
    $name = 'templates/' . $name;
    $result = '';

    if (!file_exists($name)) {
        return $result;
    }

    ob_start();
    extract($data);
    require $name;

    $result = ob_get_clean();

    return $result;
}

/**
 * Форматирует цену
 *
 * @param $price float Цена
 *
 * @return string Цена форматированная + валюта
 */
function format_price(float $price): string
{
    $price_rounded = number_format($price, 0, '.', ' ');
    return $price_rounded . '<b class="rub">р</b>';
}

/**
 * Вычисляет временной интервал до наступления заданной даты
 *
 * @param $end_at string Дата будущего события
 *
 * @return string Интервал до наступления заданной даты
 */
function time_till_end(string $end_at): string
{
    $end_at = date_create($end_at);
    $current_time = date_create('now');
    $diff_till_end = date_diff($end_at, $current_time);

    return date_interval_format($diff_till_end, "%dд %H:%I");
}

/**
 * Очищает  строку
 *
 * @param $data string Строка введенная
 *
 * @return string Строка обработанная
 */
function h(string $data): string
{
    return htmlspecialchars($data);
}

/**
 * Очищает  строку
 *
 * @param $data string Строка введенная
 *
 * @return string Строка обработанная
 */
function init_database(array $database_config)
{
    $link = mysqli_connect($database_config['host'], $database_config['user'], $database_config['password'], $database_config['database']);
    if (!$link) {
        $error = mysqli_connect_error();
        die(include_template('error.php', ['error' => $error]));
    }
    mysqli_set_charset($link, 'utf8');

    return $link;
}

/**
 * Получает категории из БД
 *
 * @param $link mysqli Ресурс соединения
 *
 * @return array Выбранные категории
 */
function get_categories($link): array
{
    $sql = 'SELECT id, title FROM category';
    $result = mysqli_query($link, $sql);

    if ($result === false) {
        $error = mysqli_error($link);
        die(include_template('error.php', ['error' => $error]));
    }

    return mysqli_fetch_all($result, MYSQLI_ASSOC);
}

/**
 * Получает лоты из БД
 *
 * @param $link mysqli Ресурс соединения
 *
 * @return array Выбранные лоты
 */
function get_lots($link): array
{
    $sql_lots = '
        SELECT l.id, l.title, l.price_start, l.img_url, l.end_at,
        IF (MAX(b.bet_value) IS NOT NULL, MAX(b.bet_value), l.price_start) as price_current,
        c.title as category_title
        FROM lot l
            LEFT JOIN bet b ON l.id = b.lot_id
            JOIN category c ON c.id = l.category_id
        WHERE NOW() < end_at
            AND l.created_at <= NOW()
        GROUP BY l.id
        ORDER BY l.created_at DESC;
    ';
    $result_lots = mysqli_query($link, $sql_lots);

    if ($result_lots === false) {
        $error = mysqli_error($link);
        die(include_template('error.php', ['error' => $error]));
    }

    return mysqli_fetch_all($result_lots, MYSQLI_ASSOC);
}

/**
 * Получает лот из БД
 *
 * @param $link mysqli Ресурс соединения
 * @param $lot_id int ID лота
 *
 * @return array Выбранны лот
 */
function get_lot_active($link, int $lot_id): array
{
    $sql_lot_single = "SELECT l.id, l.created_at, l.title, l.description, l.img_url, l.price_start, l.end_at, l.bet_step, l.category_id, l.user_id, l.winner_user_id, c.title as cat_title FROM lot l
      JOIN category c
      ON l.category_id = c.id
      WHERE l.id = $lot_id
      AND l.end_at > NOW()";
    $result_lot_single = mysqli_query($link, $sql_lot_single);

    if ($result_lot_single === false) {
        $error = mysqli_error($link);
        die(include_template('error.php', ['error' => $error]));
    }

/*    $fake_arr = [];
    echo gettype($fake_arr);*/
/*    ?><!--<pre>   <?php var_dump( mysqli_fetch_assoc($result_lot_single) ); ?>
    </pre>--><?php
*/
    //return mysqli_fetch_assoc($result_lot_single);
    //return null; // Отладка // Будет Fatal error

    //return (mysqli_fetch_assoc($result_lot_single) !== null ) ? mysqli_fetch_assoc($result_lot_single) : $fake_arr;
/*    if (mysqli_fetch_assoc($result_lot_single) === null) {
        return $fake_arr;
    } else {
        return mysqli_fetch_assoc($result_lot_single);
    }*/

    //return mysqli_fetch_assoc($result_lot_single) ;
    //return null;
    return mysqli_fetch_assoc($result_lot_single) ?? [];
}

/**
 * Добавляет лот в БД
 *
 * @param $link mysqli Ресурс соединения
 * @param $lot array Данные о лоте
 *
 * @return int ID добавленного лота
 */
function add_lot_and_get_inserted_id($link, array $lot): int
//function set_lot_single($link, array $lot): int
{
    $lot_name = $lot['title'];
    $category = (int) $lot['category_id'];
    $description = $lot['description'];
    $price_start = (int) $lot['price_start'];
    $bet_step = $lot['bet_step'];
    $end_at = $lot['end_at'];
    $img_url = $lot['img_url'];
    $user_id = (int) $lot['user_id'];

    $sql_lot_single = 'INSERT INTO lot (title, category_id, user_id, description, img_url, price_start, end_at, bet_step) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';

/*    $stmt = mysqli_prepare($link, $sql_lot_single);
    mysqli_stmt_bind_param($stmt, 'siissisi', $lot_name, $category, $user_id, $description, $img_url, $price_start, $end_at, $bet_step);*/

    $stmt = db_get_prepare_stmt($link, $sql_lot_single, $data = [$lot_name, $category, $user_id, $description, $img_url, $price_start, $end_at, $bet_step ]);

    $res = mysqli_stmt_execute($stmt);
    //$res = false; // Отладка
    if ($res) {
        $res = mysqli_insert_id($link);
    }
    $lot_id = $res;

    return $lot_id;
}

/**
 * Выводит шаблон страницы общий
 *
 * @param $content string Шаблон внутренней страницы
 * @param $categories array Категории
 *
 * @return string ?
 */
function get_layout(string $content, array $categories): string
{
    $init_data = get_init_data();

    return include_template(
        'layout.php',
        array_merge($init_data, [
            'content' => $content,
            'categories' => $categories,
        ])
    );
}

/**
 * Выводит шаблон страницы общий с шаблоно ошибки 404
 *
 * @param $categories array Категории
 *
 * @return string ?
 */
function get_layout_404(array $categories): string
{
    $error = include_template(
        '404.php',
        [
            'categories' => $categories,
            'error' => 'Ошибка 404! Страница не найдена'
        ]
    );

    return get_layout($error, $categories);
}

/**
 * Валидирует форму добавления лота
 *
 * @param $lot_uploaded array Данные из формы
 *
 * @return array [Ошибки; Данные лота]
 */
function validate_form_lot(array $lot_uploaded): array
{

    $required_fields = ['title', 'category_id', 'description', 'price_start', 'bet_step', 'end_at'];
    $number_fields = ['price_start', 'bet_step'];
    $errors = [];

    foreach ($required_fields as $field) {
        if (empty ($_POST[$field])) {
            $errors[$field] = 'Необходимо заполнить поле';
        }
    }

    $filter_options = [
        'options' => [
            'default' => 0, // значение, возвращаемое, если фильтрация завершилась неудачей
            'min_range' => 1
        ],
        //  'flags' => FILTER_FLAG_ALLOW_OCTAL,
    ];

    foreach ($number_fields as $field) {
        if (gettype((int)$_POST[$field]) !== 'integer' && ((int)$_POST[$field]) <= 0) {
            $errors[$field] = 'Необходимо корректно заполнить (указать число) поле';
        }
    }


    if (isset($_FILES['img_url']['name'])) {
        $temp_name = $_FILES['img_url']['tmp_name'];
        $path = $_FILES['img_url']['name'];

        $file_type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $temp_name);

        if ($file_type !== "image/png") {
            $errors['file'] = 'Загрузите картинку в формате PNG';
        } else {
            //move_uploaded_file($temp_name, 'img/' . $path);
            // $lot_uploaded['img_url'] = 'img/' . $path;
            $lot_uploaded = set_uploaded_lot_file($temp_name, $path, $lot_uploaded);
        }
    } else {
        $errors['file'] = 'Вы не загрузили файл';
    }

    $validate_data['errors'] = $error;
    $validate_data['lot_uploaded'] = $lot_uploaded;

    return $validate_data;
}

/**
 * Перемещает загруженный файл из временной директории в заданную
 *
 * @param $temp_name string Временное имя файла
 * @param $path string Исходное имя файла
 * @param $lot_uploaded array Данные лота, к которому относится файл
 *
 * @return array Данные лота
 */
function set_uploaded_lot_file(string $temp_name, string $path, array $lot_uploaded): array
{
    move_uploaded_file($temp_name, 'img/' . $path);
    $lot_uploaded['img_url'] = 'img/' . $path;

    return $lot_uploaded;
}
