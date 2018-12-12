<?php // 2. Начало сценария-)
$is_auth = rand(0, 1);

$user_name = 'Kirill';
$user_avatar = 'img/user.jpg';

$site_title = 'YetiCave - интернет-аукцион';


require_once 'functions.php';

$database_config = [
    'host' => 'localhost',
    'user' => 'root',
    'password' => '',
    'database' => 'yeticave10'
];

$link = init_database($database_config);

$categories = get_categories($link);


if (isset($_GET['id'])) {
    $lot_id = $_GET['id'];
}
else {
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
