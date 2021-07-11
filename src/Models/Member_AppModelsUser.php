<?php

namespace PanicHD\PanicHD\Models;

use App\Models\User;
use PanicHD\PanicHD\Traits\MemberTrait;

class Member_AppModelsUser extends User
{
    use MemberTrait;

    protected $table = 'users';
}
