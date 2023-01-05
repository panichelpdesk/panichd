<?php

namespace PanicHD\PanicHD\Models;

use UCSF\DOM\Models\MySQL\User;
use PanicHD\PanicHD\Traits\MemberTrait;

class Member_AppModelsUser extends User
{
    use MemberTrait;

    protected $table = 'users';
}