<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class RequestType extends Enum
{
    const Review_grades =   1;
    const Book_room_or_labs =  2;
    const  Swicth_class= 3;
}
