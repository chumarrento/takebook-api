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
//        'categories' => [
//            'rules' => 'array'
//        ],
        'user_id' => [
            'rules' => 'integer|exists:users,id'
        ]
    ];

    public function store()
    {
        $fields = [
            'title' => 'required',
            'author' => 'required',
            'description' => 'required',
            'price' => 'required',
//            'categories' => 'required',
            'user_id' => 'required'
        ];

        return $this->rules($fields);
    }

    public function simpleFilters()
    {
        return [
            [
                'field' => 'title',
                'type' => 'LIKE'
            ],
            [
                'field' => 'author',
                'type' => 'LIKE'
            ],
            [
                'field' => 'user_id',
                'type' => '='
            ]
        ];
    }
}