<?php
$is_auth = rand(0, 1);

$user_name = 'Kirill';
$user_avatar = 'img/user.jpg';

$site_title = 'YetiCave - интернет-аукцион';


require_once 'functions.php';

$db = [
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'yeticave10'
];

$link = mysqli_connect($db['host'], $db['user'], $db['password'], $db['database']);
mysqli_set_charset($link, 'utf8');


if (!$link) {
    $error = mysqli_connect_error();
    die(include_template('error.php', ['error' => $error]));
}

$sql = 'SELECT id, title FROM category';
$result = mysqli_query($link, $sql);

if ($result === false) {
    $error = mysqli_error($link);
    $content = include_template('error.php', ['error' => $error]);
}

$categories = mysqli_fetch_all($result, MYSQLI_ASSOC);

$sql_lots = '
    SELECT l.id, l.title, l.price_start, l.img_url,
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
    $content = include_template('error.php', ['error' => $error]);
}
$lots = mysqli_fetch_all($result_lots, MYSQLI_ASSOC);


$main = include_template(
    'index.php',
    [
        'categories' => $categories,
        'lots' => $lots
    ]
);

$layout = include_template(
    'layout.php',
    [
        'content' => $main,
        'site_title' => $site_title,
        'user_name' => $user_name,
        'user_avatar' => $user_avatar,
        'is_auth' => $is_auth,
        'categories' => $categories,
    ]
);

print($layout);
