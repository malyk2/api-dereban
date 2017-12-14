<?php
return [
    'url_has_hash' => ':attribute не містить секцію {hash} для подальшої заміни.',
    'custom' => [
        "email" => [
            "required" => "Поле email обов'язкове.",
            "email" => "Не валідний email.",
            "unique" => "Email вже використовується.",
            "exists" => "Некоректний Email."
        ],
        "password" => [
            "required" => "Поле password обов'язкове.",
            "min" => ":Attribute мае бути мінімум :min символів.",
            "confirmed" => "Паролі не співпадать."
        ],
        "url" => [
            "required" => "Поле url обов'язкове.",
            "url" => "Не валідний url.",
        ],
        "hash" => [
            "required" => "Поле hash обов'язкове.",
        ],
    ],
];