<?php

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


function get_lot_single($link, $lot_id)
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

    //echo $result_lot_single->num_rows;
    if ($result_lot_single->num_rows == 0) {

        $error = include_template(
            '404.php',
            [
                'categories' => $categories,
                'error' => 'Ошибка 404! Страница не найдена'
            ]
        );

        die(include_template('layout.php',
            [
                'content' => $error,
                'site_title' => $site_title,
                'user_name' => $user_name,
                'user_avatar' => $user_avatar,
                'is_auth' => $is_auth,
                'categories' => $categories,
            ]
        ));

    }

    return mysqli_fetch_assoc($result_lot_single);
}
