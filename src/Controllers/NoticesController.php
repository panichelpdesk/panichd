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
        $a_users = \PanicHDMember::whereNotNull('ticketit_department')->orderBy('name')->get();

        // All departments
        $departments = Models\Department::doesntHave('ancestor')->with(['descendants' => function ($query) {
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

        if ($request->input('department_id') != '0') {
            $rules['department_id'] = 'required|exists:panichd_departments,id';
        }

        $this->validate($request, $rules);

        $user = \PanicHDMember::findOrFail($request->input('user_id'));

        $user->ticketit_department = $request->input('department_id');
        $user->save();

        \Session::flash('status', trans('panichd::admin.notice-saved-ok'));

        return redirect()->action('\PanicHD\PanicHD\Controllers\NoticesController@index');
    }

    public function update(Request $request)
    {
        return $this->store($request);
    }

    public function destroy($id)
    {
        $user = \PanicHDMember::findOrFail($id);
        $user->ticketit_department = null;
        $user->save();

        \Session::flash('status', trans('panichd::admin.notice-deleted-ok'));

        return redirect()->action('\PanicHD\PanicHD\Controllers\NoticesController@index');
    }
}
