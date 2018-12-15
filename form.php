<?php

require_once 'init.php';

$categories = get_categories($link);
$lots = get_lots($link);


// Проверка отправки формы
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    //header("Location: /index.php?success=true");
    // 1. Валидация
    // 2. Добавление лота
    // 3. перенаправить на страницу созданного лота

// 1.
    ?><pre><?php var_dump($_POST) ?></pre><?php
    $_POST['lot-rate'] = 'пятьсот рублей';
    echo $_POST['lot-rate'];
    echo (int) $_POST['lot-rate'];

    $required_fields = ['lot-name', 'message', 'lot-rate', 'lot-step', 'lot-date'];
    $number_fields = ['lot-rate', 'lot-step'];
    $errors = [];
    // 1.1 Проверка на не пустоту
    foreach ($required_fields as $field) {
       //echo '<p>Поле <b>' . $field  . '</b>: ' . $_POST[$field];
        if ( empty ($_POST[$field]) ) {
            echo '<p>Необходимо заполнить поле <b>' . $field  . '</b></p>';
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
        //echo '<p>Поле <b>' . $field  . '</b>: ' . $_POST[$field];
        if ( gettype( ((int) $_POST[$field]) !== 'integer') &&  ((int) $_POST[$field]) <= 0 ) {
       // if ( filter_var($_POST[$field], FILTER_VALIDATE_FLOAT , $filter_options)  ) {
            echo '<p>Необходимо корректно заполнить (указать число) поле <b>' . $field  . ' </b></p>';
            $errors[$field] = 'Необходимо корректно заполнить (указать число) поле';
        }
    }
    // 1.3 Проверка файла
 ?><pre><?php var_dump($_FILES) ?></pre><?php
 
    if (isset($_FILES['lot_image']['name'])) {
        $tmp_name = $_FILES['lot_image']['tmp_name'];
        $path = $_FILES['lot_image']['name'];

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $file_type = finfo_file($finfo, $tmp_name);
        if ($file_type !== "image/gif") {
            $errors['file'] = 'Загрузите картинку в формате GIF';
        }
        else {
            move_uploaded_file($tmp_name, 'uploads/' . $path);
            $gif['path'] = $path;
        }
    }
    else {
        $errors['file'] = 'Вы не загрузили файл';
    }


}
else {
    //header("Location: /lot.php?success=false");
}


