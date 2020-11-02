<?php


namespace App\Enums\Book;


use App\Enums\Enum;

abstract class Status extends Enum
{
    const ANALYZE = 1;
    const APPROVED = 2;
    const DISAPPROVED = 3;
    const SOLD = 4;
}
