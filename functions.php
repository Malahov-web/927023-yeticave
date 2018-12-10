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


function time_till_tomorrow_midnight(): string
{
    $current_time = date_create('now');
    $tomorrow_midnight_time = date_create('tomorrow midnight');
    $diff_till_tomorrow_midnight = date_diff($tomorrow_midnight_time, $current_time);

    return date_interval_format($diff_till_tomorrow_midnight, "%H:%I");
}

function h(string $data): string
{
    return htmlspecialchars($data);
}

function init_database(iterable $db): object
{
    $link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
    mysqli_set_charset($link, 'utf8');

    if (!$link) {
        $error = mysqli_connect_error();
        die(include_template('error.php', ['error' => $error]));
    }
    //echo gettype($link), "\n"; // object

    return $link;
}
