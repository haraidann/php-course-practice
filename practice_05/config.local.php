<?php
// Скопируйте в config.local.php и укажите свою учётную запись MySQL.
// config.local.php не сдаётся и не публикуется: в нём пароль.
return [
    'dsn' => 'mysql:host=127.0.0.1;port=3306;dbname=catalog_db-p05;charset=utf8mb4',
    'user' => 'root',
    'password' => 'root',
];
