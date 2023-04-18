<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class PaginationContants extends Enum
{
    const LIMIT = 15;
    const PAGE = 1; 
    const ITEM_PER_PAGE_OPTIONS = array(10,15,20,25);
}
