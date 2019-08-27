<?php


namespace App\Enums\Report;


use App\Enums\Enum;

abstract class Type extends Enum
{
    const RACISM_HOMOPHOBIC_SEXISM = 1;
    const OFFENSE = 2;
    const PRODUCT = 3;
    const OFFENSIVE_IMAGE = 4;
    const INAPPROPRIATE_MESSAGE = 5;
}
