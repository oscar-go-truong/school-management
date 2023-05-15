<?php

namespace App\Enums;

use BenSampo\Enum\Enum;

/**
 * @method static static OptionOne()
 * @method static static OptionTwo()
 * @method static static OptionThree()
 */
final class UserRoleNameContants extends Enum
{
    const ADMIN = 'admin';
    const TEACHER = 'teacher';
    const STUDENT = 'student';
}
