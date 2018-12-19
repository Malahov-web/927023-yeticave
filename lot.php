<?php

require_once 'init.php';

//$link = init_database($database_config);

$categories = get_categories($link);
$lots = get_lots($link);

if (!isset($_GET['id'])) {
    die(get_layout_404($categories));
}
$lot_id = (int)$_GET['id'];

$lot_single = get_lot_single($link, $lot_id);

if (empty($lot_single)) {
    die(get_layout_404($categories));
}

$lot_page = include_template(
    'lot.php',
    [
        'categories' => $categories,
        'lots' => $lot_single
    ]
);

print(get_layout($lot_page, $categories));
