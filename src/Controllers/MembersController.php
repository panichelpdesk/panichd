<?php

namespace PanicHD\PanicHD\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PanicHD\PanicHD\Models;


class MembersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
	public function index(Request $request)
	{
		$a_members = Models\Member::orderBy('name')->get();
		
		return view('panichd::admin.member.index', compact('a_members'));
	}
}