<?php

namespace Kordy\Ticketit\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kordy\Ticketit\Models;

class DeptsUsersController extends Controller
{
    public function index()
	{
		// All users
		$a_users = Models\Agent::whereNotNull('ticketit_department')->orderBy('name')->get();
		
		// All departments
		$a_depts = Models\Department::orderBy('department')->orderBy('sub1')->get();
		
		return view('ticketit::admin.deptsuser.index', compact('a_users', 'a_depts'));
	}
}
