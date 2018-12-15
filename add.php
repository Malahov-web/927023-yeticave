<?php

require_once 'init.php';

$categories = get_categories($link);
$lots = get_lots($link);


// Проверка отправки формы
// Проверка отправки формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //header("Location: /index.php?success=true");
    // 1. Валидация
    // 2. Добавление лота
    // 3. перенаправить на страницу созданного лота

    $lot_uploaded = $_POST;

// 1.
    ?>
    <pre><?php var_dump($_POST) ?></pre><?php
   // $_POST['lot-rate'] = 'пятьсот рублей';
    //echo $_POST['lot-rate'];
   // echo (int)$_POST['lot-rate'];

    $required_fields = ['lot-name', 'category', 'message',  'lot-rate', 'lot-step', 'lot-date'];
    $number_fields = ['lot-rate', 'lot-step'];
    $errors = [];
    // 1.1 Проверка на не пустоту
    foreach ($required_fields as $field) {
        //echo '<p>Поле <b>' . $field  . '</b>: ' . $_POST[$field];
        if (empty ($_POST[$field])) {
            echo '<p>Необходимо заполнить поле <b>' . $field . '</b></p>';
            $errors[$field] = 'Необходимо заполнить поле';
        }
    }
    // 1.2 Проверка на числа
    $filter_options = [
        'options' => [
            'default' => 0, // значение, возвращаемое, если фильтрация завершилась неудачей
            'min_range' => 1
        ],
        // 'flags' => FILTER_FLAG_ALLOW_OCTAL,
    ];

    foreach ($number_fields as $field) {
        if (gettype(((int)$_POST[$field]) !== 'integer') && ((int)$_POST[$field]) <= 0) {
            // if ( filter_var($_POST[$field], FILTER_VALIDATE_FLOAT , $filter_options)  ) {
            echo '<p>Необходимо корректно заполнить (указать число) поле <b>' . $field . ' </b></p>';
            $errors[$field] = 'Необходимо корректно заполнить (указать число) поле';
        }
    }
    // 1.3 Проверка файла
    ?>
    <pre><?php var_dump($_FILES) ?></pre><?php //echo $_FILES;

///////////////////////////////
    if (isset($_FILES['lot_image']['name'])) {
        $temp_name = $_FILES['lot_image']['tmp_name'];
        $path = $_FILES['lot_image']['name'];
echo '<br>$temp_name: ' . $temp_name;
echo '<br>$path: ' . $path;

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
echo '<br>$finfo: ' . $finfo;
        $file_type = finfo_file($finfo, $temp_name);
echo '<br>$file_type: ' . $file_type;
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
?><pre><?php var_dump($errors) ?></pre><?php //echo $_FILES;
    // Подключили шаблон
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
       // $lot_uploaded_id = set_lot_single($link, $lot_uploaded );

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


/*
$add = include_template(
    'add.php',
    [
        'categories' => $categories,
        'lots' => $lots
    ]
);

print(get_layout($add, $categories));
*/
