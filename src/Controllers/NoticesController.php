<?php

namespace PanicHD\PanicHD\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PanicHD\PanicHD\Models;

class NoticesController extends Controller
{
    public function __construct()
	{
		 $this->middleware('PanicHD\PanicHD\Middleware\RequiredSettingMiddleware:Notices');
	}
	
	
	/**
     * Display a listing of the resource.
     *
     * @return Response
     */
	public function index()
	{
		// All users
		$a_users = \PanicHDMember::whereNotNull('panichd_notice_group_id')->orderBy('name')->get();
		
		// All departments
		$departments = Models\Department::doesntHave('ancestor')->with(['descendants' => function($query){
			$query->orderBy('name');
		}])->orderBy('name')->get();
		
		return view('panichd::admin.notice.index', compact('a_users', 'departments'));
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
		
		if ($request->input('group_id') != '0'){
			$rules['group_id'] = 'required|exists:panichd_groups,id';			
		}
		
        $this->validate($request, $rules);

        $user = \PanicHDMember::findOrFail($request->input('user_id'));
		
		$user->panichd_notice_group_id = $request->input('group_id');
		$user->save();
				
        \Session::flash('status', trans('panichd::admin.notice-saved-ok'));

        return redirect()->action('\PanicHD\PanicHD\Controllers\NoticesController@index');
    }
	
	public function update(Request $request)
	{
		return $this->store($request);
	}
	
	public function destroy($id){
		$user = \PanicHDMember::findOrFail($id);
		$user->panichd_notice_group_id = null;
		$user->save();
		
		\Session::flash('status', trans('panichd::admin.notice-deleted-ok'));

        return redirect()->action('\PanicHD\PanicHD\Controllers\NoticesController@index');
	}
}
