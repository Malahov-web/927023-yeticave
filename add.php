<?php

require_once 'init.php';

$categories = get_categories($link);
$lots = get_lots($link);


$add = include_template(
    'add.php',
    [
        'categories' => $categories,
        'lots' => $lots
    ]
);

print(get_layout($add, $categories));
