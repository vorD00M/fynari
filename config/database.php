<?php

return new PDO(
    'mysql:host=localhost;dbname=crmnew;charset=utf8mb4',
    'master', // имя пользователя
    'master123!@#',     // пароль
    [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]
);

//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

//  npm install -g git+git@github.com:vitejs/create-vite.git