<?php

/**
 * La base de donnée + 1 premier utilisateur sera automatiquement créer au premier chargement du site
 * si les identifiants de connexion à mysql sont valide, que l'utilisateur possède les droits requis
 * et que l'option `database:create_if_not_exist` est à `true`
 */
return [
    "app_root_dir" => __DIR__,
    "app_public_dir" => __DIR__,
    "app_page_dir" => __DIR__ . "/pages",
    "database" => [
        "user" => "<mysql_username>",
        "password" => "<mysql_password>",
        "database" => "exam_dfs22c_user_pets",
        "port" => "3306",
        "host" => "localhost",
        "create_if_not_exist" => true
    ],
    "first_connection_cookiekey" => "__fc"
];
