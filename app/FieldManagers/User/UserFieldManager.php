<?php

namespace App\FieldManagers\User;


use App\FieldManagers\FieldManager;

class UserFieldManager extends FieldManager
{

    protected $fields = [
        'first_name' => [
            'rules' => 'string',
        ],
        'last_name' => [
            'rules' => 'string',
        ],
        'avatar_file' => [
            'rules' => 'image',
        ],
        'email' => [
            'rules' => 'email|unique:users',
        ],
        'password' => [
            'rules' => 'string',
        ],
        'address_street' => [
            'rules' => 'string',
        ],
        'address_number' => [
            'rules' => 'string',
        ],
        'address_neighborhood' => [
            'rules' => 'string',
        ],
        'address_city' => [
            'rules' => 'string',
        ],
        'address_state' => [
            'rules' => 'string',
        ],
        'address_zip_code' => [
            'rules' => 'string',
        ],
        'is_admin' => [
            'rules' => 'boolean'
        ]
    ];

    public function store()
    {
        $fields = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required',
            'password' => 'required'
        ];

        return $this->rules($fields);
    }

    public function filters()
    {
        return [
            [
                'field' => 'name',
                'type' => 'like'

            ],
            [
                'field' => 'is_admin',
                'type' => 'equals'
            ]
        ];
    }
}
