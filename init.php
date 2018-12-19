<?php

require_once 'functions.php';
require_once 'config.php';

function get_init_data(): array
{
    return [
        'is_auth' => rand(0, 1),
        'user_name' => 'Kirill',
        'user_avatar' => 'img/user.jpg',
        'site_title' => 'YetiCave - интернет-аукцион',
    ];
}

$link = init_database($database_config);
