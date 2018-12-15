<?php

require_once 'init.php';

$categories = get_categories($link);
$lots = get_lots($link);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $lot_uploaded = $_POST;

    $required_fields = ['title', 'category_id', 'description',  'price_start', 'bet_step', 'end_at'];
    $number_fields = ['price_start', 'bet_step'];
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


    if (isset($_FILES['img_url']['name'])) {
        $temp_name = $_FILES['img_url']['tmp_name'];
        $path = $_FILES['img_url']['name'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $temp_name);

        if ($file_type !== "image/png") {
            $errors['file'] = 'Загрузите картинку в формате PNG';
        } else {
            move_uploaded_file($temp_name, 'img/' . $path);
            $lot_uploaded['img_url'] = $path;
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
        print(get_layout($add, $categories));

    } else {
        //$lot_uploaded_id = set_lot_single($link, $lot_uploaded );
        $lot_uploaded_id = (int) set_lot_single($link, $lot_uploaded);
        
//echo 'Ветвь 2' . $lot_uploaded_id; 
        header("Location: http://malahov-web.ru");
       // header("Location: lot.php?id=" . $lot_uploaded_id);
/*
        $add = include_template(
            'lot.php'.$lot_uploaded_id,
            [
                'categories' => $categories,
                'lot' => $lot_uploaded
            ]
        );
        print(get_layout($add, $categories));
        */
    }


} else {   
    $add = include_template(
        'add.php',
        [
            'categories' => $categories,
        ]
    );
    print(get_layout($add, $categories));

}

//print(get_layout($add, $categories));


