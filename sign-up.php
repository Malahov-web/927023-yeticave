<?php

require_once 'init.php';

$categories = get_categories($link);
$lots = get_lots($link);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    //echo '1';

    $user_uploaded = $_POST;

    $validate_data = validate_form_user($link, $user_uploaded);
    $errors = $validate_data['errors'];
    $user_uploaded = $validate_data['user_uploaded'];

 /* ?><pre><?php var_dump($validate_data) ?></pre><?php */

    //$init_data = get_init_data();
    //$lot_uploaded['user_id'] = $init_data['user_id'];

    $user_uploaded['email'] = is_email_valid($user_uploaded['email']);
    if ( !$user_uploaded['email'] ) {
        $errors['email'] = 'Необходимо указать корректный email';
    }

    if ( is_email_already_use($link, $user_uploaded['email']) ) {
        $errors['email'] = 'Пользователь с этим email уже зарегистрирован';
    }


    if (!count($errors)) {
        $user_uploaded_id = (int) add_user_and_get_inserted_id($link, $user_uploaded);
//echo '$user_uploaded_id ' . $user_uploaded_id; // Верно

        // Если был добавлен аватар, то проверить его и добавить его в бд
        $check_avatar = check_avatar($errors, $user_uploaded);
        $errors = $check_avatar['errors'];
        $user_uploaded = $check_avatar['user_uploaded'];
        $avatar_is_valid = $check_avatar['avatar_is_valid'];
        echo '$avatar_is_valid ' . $avatar_is_valid;
        echo 'avatar_url ' . $user_uploaded['avatar_url'];

        if ($avatar_is_valid) {
            add_user_avatar($link, $user_uploaded_id, $user_uploaded['avatar_url']);
        }

        die(header("Location: index.php"));
    }

    $add = include_template(
        'sign-up.php',
        [
            'categories' => $categories,
            'user'       => $user_uploaded,
            'errors'     => $errors
        ]
    );
    print(get_layout($add, $categories));


} else {
    //echo '0';

    $add = include_template(
        'sign-up.php',
        [
            'categories' => $categories,
        ]
    );
    print(get_layout($add, $categories));
}
