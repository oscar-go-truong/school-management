<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class GradeContants extends Enum
{
    const EXELLENT = ['gradePoints' => 9, 'gradeLetter' => 'A+'];
    const VERY_GOOD = ['gradePoints' => 8, 'gradeLetter' => 'A'];
    const GOOD = ['gradePoints' => 7, 'gradeLetter' => 'B+'];
    const ABOVE_AVERANGE = ['gradePoints' => 6, 'gradeLetter' => 'B'];
    const AVERANGE = ['gradePoints' => 5, 'gradeLetter' => 'C'];
    const PASS = ['gradePoints' => 4, 'gradeLetter' => 'P'];

    const FAIL = ['gradePoints' => 0, 'gradeLetter' => 'F'];
}
