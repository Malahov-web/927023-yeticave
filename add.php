<?php

require_once 'init.php';

$categories = get_categories($link);
$lots = get_lots($link);


if (!is_user_authorized()) {

    http_response_code(403);
    $errors = "У Вас нет прав просматривать эту страницу. Только авторизованные пользователи могут это делать";
    $add = include_template(
        'error.php',
        [
            'categories' => $categories,
            'error' => $errors
        ]
    );

    die(get_layout($add, $categories));

//    die(include_template('error.php', ['error' => $error]));
}


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $lot_uploaded = $_POST;

    $validate_data = validate_form_lot($lot_uploaded);
    $errors = $validate_data['errors'];
    $lot_uploaded = $validate_data['lot_uploaded'];

    $init_data = get_init_data();
    $lot_uploaded['user_id'] = $init_data['user_id'];

    if (!count($errors)) {
        $lot_uploaded_id = add_lot_and_get_inserted_id($link, $lot_uploaded);
        die(header("Location: lot.php?id=" . $lot_uploaded_id));
    }

    $add = include_template(
        'add.php',
        [
            'categories' => $categories,
            'lot' => $lot_uploaded,
            'errors' => $errors
        ]
    );


    die(get_layout($add, $categories));

}

$add = include_template(
    'add.php',
    [
        'categories' => $categories,
    ]
);
print(get_layout($add, $categories));

