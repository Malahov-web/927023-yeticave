<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once 'functions.php';
require_once 'config.php';

function get_init_data(): array
{
    return [
        'is_auth' => rand(0, 1),
       // 'is_auth' => 0,
        'user_name' => 'Kirill',
        'user_avatar' => 'img/user.jpg',
        'site_title' => 'YetiCave - интернет-аукцион',
        'user_id' => 1,
    ];
}

$link = init_database($database_config);
