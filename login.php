<?php

require_once 'init.php';

$categories = get_categories($link);
$lots = get_lots($link);


if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $user_logined = $_POST;

    $validate_data = validate_form_login($user_logined);
    $errors = $validate_data['errors'];
    $user_logined = $validate_data['user_logined'];

    $email = mysqli_real_escape_string($link, $user_logined['email']);
    $sql = "SELECT * FROM user WHERE email = '$email'";
    $res = mysqli_query($link, $sql);

    $user = $res ? mysqli_fetch_array($res, MYSQLI_ASSOC) : null;

    if (!count($errors) and $user) {
        if (password_verify($user_logined['password'], $user['password'])) {
            $_SESSION['user'] = $user;
            die(header("Location: index.php"));
        }
        else {
            $errors['password'] = 'Неверный пароль';
        }
    }
    else {
        $errors['email'] = 'Такой пользователь не найден';
    }


    $login = include_template(
        'login.php',
        [
            'categories' => $categories,
            'user' => $user_logined,
            'errors' => $errors
        ]
    );


    die(get_layout($login, $categories));
}

$login = include_template(
    'login.php',
    [
        'categories' => $categories,
    ]
);
print(get_layout($login, $categories));
