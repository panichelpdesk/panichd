<?php

namespace PanicHD\PanicHD\Models;

use App\User;
use PanicHD\PanicHD\Traits\MemberTrait;

class Member extends User
{
    use MemberTrait;

    protected $table = 'users';
}
