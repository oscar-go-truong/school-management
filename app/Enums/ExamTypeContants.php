<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class ExamTypeConstants extends Enum
{
    const QUIZ =   1;
    const MIDDLE =   2;
    const FINAL = 3;
}
