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

$link = init_database($db);

$categories = get_categories($link);
$lots = get_lots($link);


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
