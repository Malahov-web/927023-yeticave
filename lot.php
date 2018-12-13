<?php // 2. Начало сценария-)

require_once 'init.php';
require_once 'functions.php';
require_once 'config.php';


$link = init_database($database_config);

$categories = get_categories($link);


if (!isset($_GET['id'])) {
// else
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
// else

}
$lot_id = (int) $_GET['id'];


$lot_single = get_lot_single($link, $_GET['id']);


$lot = include_template(
    'lot.php',
    [
        'categories' => $categories,
        'lots' => $lot_single
    ]
);

$layout = include_template(
    'layout.php',
    [
        'content' => $lot,
        'site_title' => $site_title,
        'user_name' => $user_name,
        'user_avatar' => $user_avatar,
        'is_auth' => $is_auth,
        'categories' => $categories,
    ]
);

print($layout);
