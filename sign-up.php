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


    if (!count($errors)) {
        $user_uploaded_id = add_user_and_get_inserted_id($link, $user_uploaded);
        die(header("Location: index.php"));
    }

    $add = include_template(
        'sign-up.php',
        [
            'categories' => $categories,
            'user' => $user_uploaded,
            'errors' => $errors
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
