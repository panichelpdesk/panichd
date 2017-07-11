<?php

namespace Kordy\Ticketit\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kordy\Ticketit\Models\Agent;

class DeptsUsersController extends Controller
{
    public function index()
	{
		$a_users = Agent::whereNotNull('ticketit_department')->orderBy('name')->get();
		
		return view('ticketit::admin.deptsuser.index', compact('a_users'));
	}
}
