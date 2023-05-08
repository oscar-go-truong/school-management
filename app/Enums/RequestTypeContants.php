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
    const BOOK_ROOM_OR_LAB =  2;
    const  SWITCH_COURSE = 3;
}
