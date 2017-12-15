<?php
return [
    'url_has_hash' => ':attribute не містить секцію {hash} для подальшої заміни.',
    'custom' => [
        "email" => [
            "required" => "Поле email обов'язкове.",
            "email" => "Не валідний email.",
            "unique" => "Email вже використовується."
        ],
        "password" => [
            "required" => "Поле password обов'язкове.",
            "min" => ":Attribute мае бути мінімум :min символів."
        ],
        "url" => [
            "required" => "Поле url обов'язкове.",
            "url" => "Не валідний url.",
        ],
        "hash" => [
            "required" => "Поле hash обов'язкове.",
        ],
        "lang" => [
            "required" => "Поле lang обов'язкове.",
            "size" => "lang має містити :size символи.",
            "in" => "Некоректне значення lang."
        ]
    ],
];