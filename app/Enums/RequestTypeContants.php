<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class RequestTypeContants extends Enum
{
    const REVIEW_GRADES =   1;
    const  SWITCH_COURSE = 2;
    const EDIT_EXAMS_SCORES = 3;
    
}
