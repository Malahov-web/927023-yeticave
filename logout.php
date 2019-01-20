<?php

require_once 'init.php';

$categories = get_categories($link);
$lots = get_lots($link);


logout();


die(header("Location: index.php"));
/*
$main = include_template(
    'index.php',
    [
        'categories' => $categories,
        'lots' => $lots
    ]
);

die(get_layout($main, $categories));*/

