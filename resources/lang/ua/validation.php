<?php
return [
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
    ],
];