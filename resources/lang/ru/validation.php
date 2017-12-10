<?php
return [
    'custom' => [
        "email" => [
            "required" => "Поле email обовезательное.",
            "email" => "Не валидный email.",
            "unique" => "Email уже используется."
            
        ],
        "password" => [
            "required" => "Поле password обовезательное.",
            "min" => "Поле password должно быть минимум :min символов."
        ],
    ],
];