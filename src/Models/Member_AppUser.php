<?php

namespace PanicHD\PanicHD\Models;

use UCSF\DOM\Models\MySQL\User;
use PanicHD\PanicHD\Traits\MemberTrait;

class Member_AppUser extends User
{
    use MemberTrait;

    protected $table = 'users';
}