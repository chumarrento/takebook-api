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
            'rules' => 'email',
        ],
        'password' => [
            'rules' => 'string',
        ],
		'address' => [
			'rules' => 'array'
		],
        'address.street' => [
            'rules' => 'string',
        ],
        'address.number' => [
            'rules' => 'string|nullable',
        ],
        'address.neighborhood' => [
            'rules' => 'string',
        ],
        'address.city' => [
            'rules' => 'string',
        ],
        'address.state' => [
            'rules' => 'string',
        ],
        'address.zip_code' => [
            'rules' => 'string',
        ],
		'address.latitude' => [
			'rules' => 'string|nullable',
		],
		'address.longitude' => [
			'rules' => 'string|nullable',
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
            'email' => 'required|unique:users',
            'password' => 'required'
        ];

        return $this->rules($fields);
    }

    public function filters()
    {
        return [
            [
                'field' => 'first_name',
                'type' => 'like'

            ],
            [
                'field' => 'is_admin',
                'type' => 'equals'
            ]
        ];
    }
}
