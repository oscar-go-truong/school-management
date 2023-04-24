<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class APIUrlEnums extends Enum
{
    const TABLE_USER_API = "/users/table";
    const TABLE_COURSE_API = "/courses/table";

    const TABLE_SUBJECT_API = "/subjects/table";
}
