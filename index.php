<?php

require_once 'init.php';

$categories = get_categories($link);
$lots = get_lots($link);


$main = include_template(
    'index.php',
    [
        'categories' => $categories,
        'lots' => $lots
    ]
);

print(get_layout($main, $categories));
