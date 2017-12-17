<?php

namespace PanicHD\PanicHD\Controllers;

use App\Http\Controllers\Controller;
use DB;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use PanicHD\PanicHD\Models\Agent;
use PanicHD\PanicHD\Models\Category;
use PanicHD\PanicHD\Models\Setting;

class AgentsController extends Controller
{
    public function index()
    {
        $agents = Agent::agents()->with('categories')->get();
		$not_agents = Agent::where('ticketit_agent', '0')->get();
        $categories = Category::get();

        return view('panichd::admin.agent.index', compact('agents', 'not_agents', 'categories'));
    }

    public function create()
    {
        
    }

    public function store(Request $request)
    {
        if ($request->input('agent_cats') == null){
			return redirect()->back()->with('warning', trans('ticketit::admin.agent-store-error-no-category'));
		}
		
		DB::beginTransaction();
		
		$user = Agent::findOrFail($request->agent_id);
		$user->ticketit_agent = true;
		$user->save();
		
		$this->syncAgentCategories($request, $user->id, $user);
		
		DB::commit();

        Session::flash('status', trans('ticketit::admin.agent-store-ok', ['name' => $user->name]));

        return redirect()->action('\PanicHD\PanicHD\Controllers\AgentsController@index');
    }

    public function update($id, Request $request)
    {
        if ($request->input('agent_cats') == null){
			return $this->destroy($id);
		}else{
			$this->syncAgentCategories($request, $id);
			
			$user = Agent::findOrFail($id);
			
			Session::flash('status', trans('ticketit::admin.agent-updated-ok', ['name' => $user->name]));

			return redirect()->action('\PanicHD\PanicHD\Controllers\AgentsController@index');
		}
    }

    public function destroy($id)
    {
        $agent = Agent::findOrFail($id);
		
		$agent->categories()->detach();
		
		$agent->ticketit_agent = false;
        $agent->save();

        Session::flash('status', trans('ticketit::admin.agent-excluded-ok', ['name' => $agent->name]));

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
        if (!$agent) $agent = Agent::findOrFail($id);
		
		$form_cats = $fc = ($request->input('agent_cats') == null) ? [] : $request->input('agent_cats');
        $form_auto = ($request->input('agent_cats_autoassign') == null) ? [] : $request->input('agent_cats_autoassign');

        // Attach Autoassign parameter
        if ($form_cats) {
            $form_cats = [];
            foreach ($fc as $cat) {
                $form_cats[$cat] = ['autoassign'=>(in_array($cat, $form_auto) ? '1' : '0')];
            }
        }

        // Update Agent Categories in ticketit_categories_users
        $agent->categories()->sync($form_cats);
    }
}
