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
    const LIMIT = 10;
    const PAGE = 1;
    const ITEM_PER_PAGE_OPTIONS = array(10,25,50,100);
}
