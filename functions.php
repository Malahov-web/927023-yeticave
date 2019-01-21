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
 * Подключает шаблон Навигации
 *
 * @param $data array Данные для вывода
 *
 * @return string Шаблон страницы
 */
function get_navigation(array $categories): string {

    $result = include_template('navigation.php', ['categories' => $categories]);

    return $result;
}

/**
 * Подключает шаблон Шапки
 *
 * @param $data array Данные для вывода
 *
 * @return string Шаблон страницы
 */
function get_header(array $categories): string {

    $result = include_template('header.php', ['categories' => $categories]);

    return $result;
}

/**
 * Проверяет авторизовани ли пользователь
 *
 * @param
 *
 * @return bool
 */
function is_user_authorized(): bool {

    if (isset($_SESSION['user'])) {
        return true;
    }

    return false;
}

/**
 * Разлогинивает пользователя
 *
 * @param
 *
 * @return
 */
function logout()  {
    // Нужно ли тут условие, ловить какие-то ошибки ?
    unset( $_SESSION['user'] );

    return ;
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
 * Создает подключение к базе данных
 *
 * @param array $database_config параметры подключения к базе данных
 *
 * @return object ресурс соединения
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

    $result = fetch_data($link, $sql);

    return mysqli_fetch_all($result, MYSQLI_ASSOC) ?? [];
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

    $result_lots = fetch_data($link, $sql_lots);

    return mysqli_fetch_all($result_lots, MYSQLI_ASSOC) ?? [];
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
    $sql_lot_single = "SELECT l.id, l.created_at, l.title, l.description, l.img_url, l.price_start, l.end_at, 
        l.bet_step, l.category_id, l.user_id, l.winner_user_id, c.title as cat_title 
        FROM lot l
            JOIN category c
        ON l.category_id = c.id
            WHERE l.id = $lot_id
            AND l.end_at > NOW()";

    $result_lot_single = fetch_data($link, $sql_lot_single);

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
{
    return insert_and_get_last_id($lot, 'lot', $link);
}

/**
 * Генерирует плейсхолдеры для подготовленного выражения
 *
 * @param $n int число, количество параметров запроса
 * @return string строка плейсхолдеров
 */
function get_N_questions(int $n):string {
    $questions = '';
    for ($i = 0; $i < $n; $i++) {
        $questions .= '?,';
    }
    $questions =  substr($questions, 0 , strlen($questions) - 1 );

    return $questions;
}

/**
 * Добавляет запись в БД и получает ID записи
 *
 * @param $record array Данные о записи*
 * @param $table_name string Имя таблицы*
 * @param $link mysqli Ресурс соединения
 *
 * @return int ID добавленной записи
 */
function insert_and_get_last_id(array $record, string $table_name, $link): int {
    $sql = sprintf("INSERT INTO $table_name (%s) VALUES (%s)",
        implode(',', array_keys($record)),
        get_N_questions(count($record))
    );

    $stmt = db_get_prepare_stmt($link, $sql, array_values($record));

    $res = mysqli_stmt_execute($stmt);

    if ($res) {
        return mysqli_insert_id($link);
    }

    $error = mysqli_error($link);
    die(include_template('error.php', ['error' => $error]));
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
 * @return string
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
        if (empty ($lot_uploaded[$field])) {
            $errors[$field] = 'Необходимо заполнить поле';
        }
    }

    foreach ($number_fields as $field) {
        if (!is_positive_numeric($lot_uploaded[$field])) {
            $errors[$field] = 'Необходимо корректно заполнить (указать число) поле';
        }
        $lot_uploaded[$field] = (int)$lot_uploaded[$field];
    }

    if (isset($_FILES['img_url']['name'])) {
        $temp_name = $_FILES['img_url']['tmp_name'];
        $path = $_FILES['img_url']['name'];

        $file_type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $temp_name);

        if (!in_array($file_type, ['image/png', 'image/jpeg'], true)) {
            $errors['img_url'] = 'Загрузите картинку в формате PNG или JPEG';
        } else {
            $lot_uploaded = set_uploaded_lot_file($temp_name, $path, $lot_uploaded, 'img_url');
        }
    } else {
        $errors['img_url'] = 'Вы не загрузили файл';
    }

    $validate_data['errors'] = $errors;
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
function set_uploaded_lot_file(string $temp_name, string $path, array $lot_uploaded, string $file_field_name): array
{
    move_uploaded_file($temp_name, 'img/' . $path);
    $lot_uploaded[$file_field_name] = 'img/' . $path;

    return $lot_uploaded;
}


/**
 * Валидирует форму добавления пользователя
 *
 * @param $user_uploaded array Данные из формы
 *
 * @return array [Ошибки; Данные юзера]
 */
function validate_form_user(array $user_uploaded): array
{
    $required_fields = ['email', 'password', 'name', 'contacts'];
    $errors = [];

    foreach ($required_fields as $field) {
        if (empty ($user_uploaded[$field])) {
            $errors[$field] = 'Необходимо заполнить поле';
        }
    }

    $validate_data['errors'] = $errors;
    $validate_data['user_uploaded'] = $user_uploaded;

    return $validate_data;
}

function validate_form_login(array $user_logined): array
{
    $required_fields = ['email', 'password'];
    $errors = [];

    $errors = validate_form_empty($user_logined);

    $validate_data['errors'] = $errors;
    $validate_data['user_logined'] = $user_logined;

    return $validate_data;
}

function validate_form_empty(array $form_fields): array {

    $errors = [];

    foreach ($form_fields as $field_name => $field) {
//        echo "<br>$field_name : " . "$field";
        if (empty ($form_fields[$field_name] )) {
//            echo "<br>$field_name " . "$field is empty";
            $errors[$field_name] = 'Необходимо заполнить поле';
        }
    }
//echo '<br>';  var_dump($errors);
    return $errors;
}

/**
 * Проверяет что указанный email уже не используется другим пользователем
 *
 * @param $link mysqli Ресурс соединения
 * @param $email array Email пользователя
 *
 * @return bool Флаг
 */
function is_email_already_use($link, string $email): int
{

    $email = mysqli_real_escape_string($link, $email);
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $result = mysqli_query($link, $sql);

//    return (mysqli_num_rows($result) > 0) ? 1 : 0;
    return mysqli_num_rows($result);
}

/**
 * Добавляет пользователя в БД
 *
 * @param $link mysqli Ресурс соединения
 * @param $user array Данные о пользователе
 *
 * @return int ID добавленного пользователя
 */
function add_user_and_get_inserted_id($link, array $user): int
{
    $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
    return insert_and_get_last_id($user, 'user', $link);
}

/**
 * Проверяет валидность Email
 *
 * @param $email string Email-адрес
 *
 * @return string Email-адрес отфильтрованный
 */
function is_email_valid(string $email): bool
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

/**
 * Проверяет был ли добавлен в форму аватар, поле avatar_url
 *
 * @param $errors array Ошибки валидации
 * @param $user_uploaded array Данные из формы
 *
 * @return array [Ошибки; Данные юзера, Флаг]
 */
function check_avatar(array $errors, array $user_uploaded): array
{
    $avatar_is_valid = 0;
    if (!empty($_FILES['avatar_url']['name'])) {
        $temp_name = $_FILES['avatar_url']['tmp_name'];
        $path = $_FILES['avatar_url']['name'];
        $file_type = finfo_file(finfo_open(FILEINFO_MIME_TYPE), $temp_name);

        if ($file_type !== "image/jpeg") {
            $errors['avatar_url'] = 'Загрузите картинку в формате JPEG';
        } else {
            $user_uploaded = set_uploaded_lot_file($temp_name, $path, $user_uploaded, 'avatar_url');
            $avatar_is_valid = 1;
        }

    } else {
        $avatar_is_valid = 1;
    }

    $check_avatar['errors'] = $errors;
    $check_avatar['user_uploaded'] = $user_uploaded;
    $check_avatar['avatar_is_valid'] = $avatar_is_valid;

    return $check_avatar;
}

/**
 * Добавляет аватар пользователя в БД
 *
 * @param $link mysqli Ресурс соединения
 * @param $user_id int Id пользователя
 * @param $avatar_url string URL аватара
 *
 * @return int ID пользователя
 */
function add_user_avatar($link, int $user_id, string $avatar_url)
{
    $sql_user_avatar = "UPDATE user SET avatar_url = '$avatar_url' WHERE id = $user_id";
    $res = fetch_data($link, $sql_user_avatar);

}

function is_positive_numeric( $field ): bool {

    return (gettype((int)$lot_uploaded[$field]) == 'integer' && ((int)$lot_uploaded[$field]) > 0);
}

/**
 * Делает SQL-запрос в БД и выводит ошибку
 *
 * @param $link mysqli Ресурс соединения
 * @param $sql string запрос в БД
 *
 * @return mysqli_result данные
 */
function fetch_data($link, string $sql): mysqli_result
{

    $result = mysqli_query($link, $sql);

    if ($result === false) {
        $error = mysqli_error($link);
        die(include_template('error.php', ['error' => $error]));
    }

    return $result;
}
