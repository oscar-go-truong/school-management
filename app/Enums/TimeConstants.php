<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class TimeConstants extends Enum
{
    const TIMES  = array('09:00:00','09:45:00','10:30:00','11:15:00','13:00:00','13:45:00','14:30:00','15:15:00','16:00:00');

    const WEEKDAY = array('Mon', 'Tue','Wed','Thu','Fri');
}
