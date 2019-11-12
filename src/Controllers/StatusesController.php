<?php

namespace PanicHD\PanicHD\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PanicHD\PanicHD\Helpers\LaravelVersion;
use PanicHD\PanicHD\Models\Status;

class StatusesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $statuses = Status::withCount('tickets')->get();

        if (LaravelVersion::min('5.3.0')) {
            $statuses_list = $statuses->pluck('name', 'id')->toArray();
        } else {
            $statuses_list = $statuses->lists('name', 'id')->toArray();
        }

        return view('panichd::admin.status.index', compact('statuses', 'statuses_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('panichd::admin.status.create');
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
        $this->validate($request, [
            'name'      => 'required',
            'color'     => 'required',
        ]);

        $status = new Status();
        $status->create(['name' => $request->name, 'color' => $request->color]);

        Session::flash('status', trans('panichd::lang.status-name-has-been-created', ['name' => $request->name]));

        return redirect()->action('\PanicHD\PanicHD\Controllers\StatusesController@index');
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function show($id)
    {
        return trans('panichd::lang.status-all-tickets-here');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     *
     * @return Response
     */
    public function edit($id)
    {
        $status = Status::findOrFail($id);

        return view('panichd::admin.status.edit', compact('status'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'name'      => 'required',
            'color'     => 'required',
        ]);

        $status = Status::findOrFail($id);
        $status->update(['name' => $request->name, 'color' => $request->color]);

        Session::flash('status', trans('panichd::lang.status-name-has-been-modified', ['name' => $request->name]));

        return redirect()->action('\PanicHD\PanicHD\Controllers\StatusesController@index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy(Request $request, $id)
    {
        $status = Status::findOrFail($id);
        $name = $status->name;

        if ($request->input('tickets_new_status_id') != '') {
            $this->validate($request, [
                'tickets_new_status_id' => 'required|exists:panichd_statuses,id',
            ]);
            $status->delete($request->tickets_new_status_id);
        } else {
            if ($status->tickets()->count() > 0) {
                return back()->with('warning', trans('panichd::admin.status-delete-error-no-status', ['name' => $name]));
            }

            $status->delete();
        }

        Session::flash('status', trans('panichd::lang.status-name-has-been-deleted', ['name' => $name]));

        return redirect()->action('\PanicHD\PanicHD\Controllers\StatusesController@index');
    }
}
