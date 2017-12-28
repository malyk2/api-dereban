<?php
return [
    'url_has_hash' => ':attribute не містить секцію {hash} для подальшої заміни.',
    'required' => "Поле :attribute обов'язкове.",
    'exists' => 'Поле :attribute недійсне.',
    'min'                  => [
        'numeric' => 'Поле :attribute має бути мінімум :min.',
        'file'    => 'Поле :attribute має бути мінімум :min кілобайт.',
        'string'  => 'Поле :attribute має бути мінімум :min символів.',
        'array'   => 'Поле :attribute має містити мінімум :min елементів.',
    ],
    'custom' => [
        "email" => [
            "email" => "Не валідний email.",
            "unique" => "Email вже використовується."
        ],
        "password" => [
            "min" => ":Attribute мае бути мінімум :min символів."
        ],
        "url" => [
            "url" => "Не валідний url.",
        ],
        "lang" => [
            "size" => "lang має містити :size символи.",
            "in" => "Некоректне значення lang."
        ],
        "hash" => [
            "required" => "Поле hash обов'язкове.",
        ],
    ],
];