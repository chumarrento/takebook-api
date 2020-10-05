<?php


namespace App\FieldManagers\Book;


use App\FieldManagers\FieldManager;

class BookFieldManager extends FieldManager
{
    protected $fields = [
        'title' => [
            'rules' => 'string'
        ],
        'author' => [
            'rules' => 'string'
        ],
        'description' => [
            'rules' => 'string'
        ],
        'price' => [
            'rules' => 'numeric'
        ],
        'categories' => [
            'rules' => 'array'
        ],
        'condition_id' => [
            'rules' => 'integer|exists:book_conditions,id'
        ],
        'status_id' => [
            'rules' => 'integer|exists:book_status,id'
        ],
        'images' => [
            'rules' => 'array|max:5'
        ],
        'user_id' => [
            'rules' => 'integer|exists:users,id'
        ],
		'categories.*' => [
			'rules' => 'integer|exists:categories,id'
		],
		'images.*' => [
			'rules' => 'image'
		]
    ];

    public function store()
    {
        $fields = [
            'title' => 'required',
            'author' => 'required',
            'description' => 'required',
            'price' => 'required',
            'categories' => 'required',
            'images' => 'required',
            'condition_id' => 'required',
            'user_id' => 'required'
        ];

        return $this->rules($fields);
    }

    public function filters()
    {
        return [
            [
                'field' => 'title',
                'type' => 'like'
            ],
            [
                'field' => 'author',
                'type' => 'like'
            ],
            [
                'field' => 'user_id',
                'type' => 'equals'
            ],
            [
                'field' => 'status_id',
                'type' => 'equals'
            ],
            [
                'field' => 'condition_id',
                'type' => 'equals'
            ]
        ];
    }
}
