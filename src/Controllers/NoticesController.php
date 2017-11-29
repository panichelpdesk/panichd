<?php

namespace Kordy\Ticketit\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kordy\Ticketit\Models;

class NoticesController extends Controller
{
    public function __construct()
	{
		 $this->middleware('Kordy\Ticketit\Middleware\RequiredSettingMiddleware:Notices');
	}
	
	
	/**
     * Display a listing of the resource.
     *
     * @return Response
     */
	public function index()
	{
		// All users
		$a_users = Models\Agent::whereNotNull('ticketit_department')->orderBy('name')->get();
		
		// All departments
		$a_depts = Models\Department::orderBy('department')->orderBy('sub1')->get();
		
		return view('ticketit::admin.notice.index', compact('a_users', 'a_depts'));
	}
	
	/**
     * Store a newly created resource in storage.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
		$rules['user_id'] = 'required|exists:users,id';
		
		if ($request->input('department_id') != '0'){
			$rules['department_id'] = 'required|exists:ticketit_departments,id';			
		}
		
        $this->validate($request, $rules);

        $user = \App\User::findOrFail($request->input('user_id'));
		
		$user->ticketit_department = $request->input('department_id');
		$user->save();
				
        \Session::flash('status', trans('ticketit::admin.notice-saved-ok'));

        return redirect()->action('\Kordy\Ticketit\Controllers\NoticesController@index');
    }
	
	public function update(Request $request)
	{
		return $this->store($request);
	}
	
	public function destroy($id){
		$user = \App\User::findOrFail($id);
		$user->ticketit_department = null;
		$user->save();
		
		\Session::flash('status', trans('ticketit::admin.notice-deleted-ok'));

        return redirect()->action('\Kordy\Ticketit\Controllers\NoticesController@index');
	}
}
