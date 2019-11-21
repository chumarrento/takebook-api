<?php


namespace App\Enums\Book;


use App\Enums\Enum;

abstract class Condition extends Enum
{
    const NEW = 1;
    const SEMINEW = 2;
    const USED = 3;
}
