<?php  

require_once 'init.php';

$categories = get_categories($link);
$lots = get_lots($link);


if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $lot_uploaded = $_POST;

    $validate_data = validate_form_lot($lot_uploaded);

    $errors = $validate_data['errors'];
    $lot_uploaded = $validate_data['lot_uploaded'];

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
        $lot_uploaded_id = set_lot_single($link, $lot_uploaded);
        header("Location: lot.php?id=" . $lot_uploaded_id);
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

