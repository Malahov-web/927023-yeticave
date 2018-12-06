<?php
$is_auth = rand(0, 1);

$user_name = 'Kirill';
$user_avatar = 'img/user.jpg';

$site_title = 'YetiCave - интернет-аукцион';

$categories = [
    'Доски и лыжи',
    'Крепления',
    'Ботинки',
    'Одежда',
    'Инструменты',
    'Разное'
];

$lots = [
    [
        'title' => '2014 <a href="http://htmlacademy.ru">ЯкорьСсылкиПочтиXSS<a/>Rossignol District Snowboard',
        'cat' => 'Доски и лыжи',
        'price' => 10999.5,
        'img_url' => 'img/lot-1.jpg'
    ],
    [
        'title' => 'DC Ply Mens 2016/2017 Snowboard',
        'cat' => 'Доски и лыжи',
        'price' => 159999,
        'img_url' => 'img/lot-2.jpg'
    ],
    [
        'title' => 'Крепления Union Contact Pro 2015 года размер L/XL',
        'cat' => 'Крепления',
        'price' => 8000,
        'img_url' => 'img/lot-3.jpg'
    ],
    [
        'title' => 'Ботинки для сноуборда DC Mutiny Charocal',
        'cat' => 'Ботинки',
        'price' => 10999,
        'img_url' => 'img/lot-4.jpg'
    ],
    [
        'title' => 'Куртка для сноуборда DC Mutiny Charocal',
        'cat' => 'Одежда',
        'price' => 7500,
        'img_url' => 'img/lot-5.jpg'
    ],
    [
        'title' => 'Маска Oakley Canopy',
        'cat' => 'Разное',
        'price' => 5400,
        'img_url' => 'img/lot-6.jpg'
    ]
];


require_once('functions.php');

$main = include_template(
    'index.php',
    [
        'categories' => $categories,
        'lots' => $lots
    ]
);

$layout = include_template(
    'layout.php',
    [
        'content' => $main,
        'site_title' => $site_title,
        'user_name' => $user_name,
        'user_avatar' => $user_avatar,
        'is_auth' => $is_auth,
        'categories' => $categories,
    ]
);

print($layout);
