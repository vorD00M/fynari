<?php

return new PDO(
    'mysql:host=localhost;dbname=crm;charset=utf8mb4',
    'master', // имя пользователя
    '',     // пароль
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);

