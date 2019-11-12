<?php

namespace PanicHD\PanicHD\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PanicHD\PanicHD\Models\Category;

class AgentsController extends Controller
{
    public function index()
    {
        $agents = \PanicHDMember::agents()->with('categories')->get();
        $not_agents = \PanicHDMember::where('panichd_agent', '0')->get();
        $categories = Category::get();

        return view('panichd::admin.agent.index', compact('agents', 'not_agents', 'categories'));
    }

    public function create()
    {
    }

    public function store(Request $request)
    {
        if ($request->input('agent_cats') == null) {
            return redirect()->back()->with('warning', trans('panichd::admin.agent-store-error-no-category'));
        }

        DB::beginTransaction();

        $user = \PanicHDMember::findOrFail($request->agent_id);
        $user->panichd_agent = true;
        $user->save();

        $this->syncAgentCategories($request, $user->id, $user);

        DB::commit();

        Session::flash('status', trans('panichd::admin.agent-store-ok', ['name' => $user->name]));

        return redirect()->action('\PanicHD\PanicHD\Controllers\AgentsController@index');
    }

    public function update($id, Request $request)
    {
        if ($request->input('agent_cats') == null) {
            return $this->destroy($id);
        } else {
            $this->syncAgentCategories($request, $id);

            $user = \PanicHDMember::findOrFail($id);

            Session::flash('status', trans('panichd::admin.agent-updated-ok', ['name' => $user->name]));

            return redirect()->action('\PanicHD\PanicHD\Controllers\AgentsController@index');
        }
    }

    public function destroy($id)
    {
        $agent = \PanicHDMember::findOrFail($id);

        $agent->categories()->detach();

        $agent->panichd_agent = false;
        $agent->save();

        Session::flash('status', trans('panichd::admin.agent-excluded-ok', ['name' => $agent->name]));

        return redirect()->action('\PanicHD\PanicHD\Controllers\AgentsController@index');
    }

    /**
     * Sync Agent categories with the selected categories got from update form.
     *
     * @param $id
     * @param Request $request
     */
    public function syncAgentCategories(Request $request, $id, $agent = false)
    {
        if (!$agent) {
            $agent = \PanicHDMember::findOrFail($id);
        }

        $form_cats = $fc = ($request->input('agent_cats') == null) ? [] : $request->input('agent_cats');
        $form_auto = ($request->input('agent_cats_autoassign') == null) ? [] : $request->input('agent_cats_autoassign');

        // Attach Autoassign parameter
        if ($form_cats) {
            $form_cats = [];
            foreach ($fc as $cat) {
                $form_cats[$cat] = ['autoassign'=>(in_array($cat, $form_auto) ? '1' : '0')];
            }
        }

        // Update Agent Categories in panichd_categories_users
        $agent->categories()->sync($form_cats);
    }
}
