<?php

namespace PanicHD\PanicHD\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PanicHD\PanicHD\Helpers\LaravelVersion;
use PanicHD\PanicHD\Models\Priority;

class PrioritiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $priorities = Priority::withCount('tickets')->orderBy('magnitude', 'desc')->get();

        if (LaravelVersion::min('5.3.0')) {
            $priorities_list = $priorities->pluck('name', 'id')->toArray();
        } else {
            $priorities_list = $priorities->lists('name', 'id')->toArray();
        }

        return view('panichd::admin.priority.index', compact('priorities', 'priorities_list'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        return view('panichd::admin.priority.create');
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

        // Update magnitude for all existent priorities
        $this->update_magnitudes(1);

        $priority = new Priority();

        $priority->create([
            'name'      => $request->name,
            'color'     => $request->color,
            'magnitude' => 1,
        ]);

        Session::flash('status', trans('panichd::lang.priority-name-has-been-created', ['name' => $request->name]));

        return redirect()->action('\PanicHD\PanicHD\Controllers\PrioritiesController@index');
    }

    /*
     * Update all existent priorities magnitude with specified $addition
    */
    public function update_magnitudes($addition = 0)
    {
        $a_magnitude = Priority::orderBy('magnitude', 'desc')->get();

        $new_max_magnitude = count($a_magnitude) + $addition;

        $loop = 0;
        foreach ($a_magnitude as $p) {
            $p->update([
                'magnitude' => $new_max_magnitude - $loop,
            ]);

            $loop++;
        }
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
        return trans('panichd::lang.priority-all-tickets-here');
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
        $priority = Priority::findOrFail($id);

        return view('panichd::admin.priority.edit', compact('priority'));
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

        $priority = Priority::findOrFail($id);
        $priority->update(['name' => $request->name, 'color' => $request->color]);

        Session::flash('status', trans('panichd::lang.priority-name-has-been-modified', ['name' => $request->name]));

        return redirect()->action('\PanicHD\PanicHD\Controllers\PrioritiesController@index');
    }

    public function reorder(Request $request)
    {
        $result = 'error';
        if ($request->input('priorities') != '') {
            $a_priorities = $a_priorities = explode(',', $request->priorities);
            if (Priority::whereNotIn('id', $a_priorities)->count() == 0
            and Priority::whereIn('id', $a_priorities)->count() == count($a_priorities)) {
                $max_magnitude = count($a_priorities);
                $index = 0;
                foreach ($a_priorities as $id) {
                    $priority = Priority::findOrFail($id);
                    $priority->magnitude = $max_magnitude - $index;
                    $priority->save();
                    $index++;
                }

                $result = 'ok';
            }
        }

        return response()->json(['result' => $result]);
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
        $priority = Priority::findOrFail($id);
        $name = $priority->name;

        if ($request->input('tickets_new_priority_id') != '') {
            $this->validate($request, [
                'tickets_new_priority_id' => 'required|exists:panichd_priorities,id',
            ]);
            $priority->delete($request->tickets_new_priority_id);
        } else {
            if ($priority->tickets()->count() > 0) {
                return back()->with('warning', trans('panichd::admin.priority-delete-error-no-priority', ['name' => $name]));
            }

            $priority->delete();
        }

        // Update magnitude for all existent priorities
        $this->update_magnitudes();

        Session::flash('status', trans('panichd::lang.priority-name-has-been-deleted', ['name' => $name]));

        return redirect()->action('\PanicHD\PanicHD\Controllers\PrioritiesController@index');
    }
}
