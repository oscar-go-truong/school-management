<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ExamType extends Enum
{
    const Quiz =   1;
    const Middle =   2;
    const Final = 3;
}
