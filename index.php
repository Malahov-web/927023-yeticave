<?php

require_once 'init.php';
require_once 'functions.php';
require_once 'config.php';
/*
$database_config = [
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'yeticave10'
];
*/
$link = init_database($database_config);

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
