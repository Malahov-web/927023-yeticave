<?php

require_once 'init.php';

$categories = get_categories($link);
$lots = get_lots($link);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $lot_uploaded = $_POST;

    $required_fields = ['lot-name', 'category', 'message',  'lot-rate', 'lot-step', 'lot-date'];
    $number_fields = ['lot-rate', 'lot-step'];
    $errors = [];

    foreach ($required_fields as $field) {
        if (empty ($_POST[$field])) {
            $errors[$field] = 'Необходимо заполнить поле';
        }
    }

    $filter_options = [
        'options' => [
            'default' => 0, // значение, возвращаемое, если фильтрация завершилась неудачей
            'min_range' => 1
        ],
        // 'flags' => FILTER_FLAG_ALLOW_OCTAL,
    ];

    foreach ($number_fields as $field) {
        if ( gettype( (int) $_POST[$field] ) !== 'integer' && ((int)$_POST[$field]) <= 0 ) {
        //if ( gettype( (int)$_POST[$field] ) !== 'integer' && ((int)$_POST[$field]) <= 0 ) {
            // if ( filter_var($_POST[$field], FILTER_VALIDATE_FLOAT , $filter_options)  ) {
            $errors[$field] = 'Необходимо корректно заполнить (указать число) поле';
        }
    }


    if (isset($_FILES['lot_image']['name'])) {
        $temp_name = $_FILES['lot_image']['tmp_name'];
        $path = $_FILES['lot_image']['name'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $temp_name);

        if ($file_type !== "image/png") {
            $errors['file'] = 'Загрузите картинку в формате PNG';
        } else {
            move_uploaded_file($temp_name, 'img/' . $path);
            $lot_uploaded['path'] = $path;
        }
    } else {
        $errors['file'] = 'Вы не загрузили файл';
    }

    $lot_uploaded['user_id'] = 1;

    if (count($errors)) {
        $add = include_template(
            'add.php',
            [
                'categories' => $categories,
                'lot' => $lot_uploaded,
                'errors' => $errors
            ]
        );

    } else {
        //$lot_uploaded_id = set_lot_single($link, $lot_uploaded );
        $lot_uploaded_id = set_lot_single($link, $lot_uploaded);
        

        $add = include_template(
            'lot.php?id=$lot_uploaded_id',
            [
                'categories' => $categories,
                'lot' => $lot_uploaded
            ]
        );
    }


} else {
    $add = include_template(
        'add.php',
        [
            'categories' => $categories,
        ]
    );

}

print(get_layout($add, $categories));


