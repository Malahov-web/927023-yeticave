<?php

require_once 'init.php';
require_once 'mysql_helper.php';

function include_template($name, $data)
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


function format_price(float $price): string
{
    $price_rounded = number_format($price, 0, '.', ' ');
    return $price_rounded . '<b class="rub">р</b>';
}


function time_till_end(string $end_at): string
{
    $end_at = date_create($end_at);
    $current_time = date_create('now');
    $diff_till_end = date_diff($end_at, $current_time);

    return date_interval_format($diff_till_end, "%dд %H:%I");
}

function h(string $data): string
{
    return htmlspecialchars($data);
}

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


function get_lot_single($link, int $lot_id): array
{
    $sql_lot_single = "SELECT l.id, l.created_at, l.title, l.description, l.img_url, l.price_start, l.end_at, l.bet_step, l.category_id, l.user_id, l.winner_user_id, c.title as cat_title FROM lot l
      JOIN category c
      ON l.category_id = c.id
      WHERE l.id = $lot_id";
    $result_lot_single = mysqli_query($link, $sql_lot_single);

    if ($result_lot_single === false) {
        $error = mysqli_error($link);
        die(include_template('error.php', ['error' => $error]));
    }

    return mysqli_fetch_assoc($result_lot_single);
}


function set_lot_single($link, array $lot): int
{
    $lot_name = $lot['title'];
    $category = $lot["category_id"];
    $description = $lot['description'];
    $price_start = $lot["price_start"];
    $bet_step = $lot["bet_step"];
    $end_at = $lot["end_at"];
    $img_url = $lot["img_url"];
    $user_id = $lot["user_id"];

    $sql_lot_single = 'INSERT INTO lot (title, category_id, user_id, description, img_url, price_start, end_at, bet_step) VALUES (?, ?, ?, ?, ?, ?, ?, ?)';

    $stmt = mysqli_prepare($link, $sql_lot_single);
    mysqli_stmt_bind_param($stmt, 'siissisi', $lot_name, $category, $user_id, $description, $img_url, $price_start, $end_at, $bet_step);

    $res = mysqli_stmt_execute($stmt);
    if ($res) {
        $res = mysqli_insert_id($link);
    }
    $lot_id = $res;

    return $lot_id;


}


function db_insert_data($link, $sql, array $data): int
{
    $stmt = db_get_prepare_stmt($link, $sql, $data);

    $result = mysqli_stmt_execute($stmt);
    if ($result) {
        $result = mysqli_insert_id($link);
    }

    return $result;
}


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
