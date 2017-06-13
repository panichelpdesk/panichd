<?php

namespace Kordy\Ticketit\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Kordy\Ticketit\Models;
use Kordy\Ticketit\Models\Agent;
use Kordy\Ticketit\Models\Category;
use Kordy\Ticketit\Models\Setting;
use Kordy\Ticketit\Models\Tag;
use Kordy\Ticketit\Models\Ticket;
use Kordy\Ticketit\Traits\Purifiable;
use Yajra\Datatables\Datatables;
use Yajra\Datatables\Engines\EloquentEngine;

class TicketsController extends Controller
{
    use Purifiable;

    protected $tickets;
    protected $agent;

    public function __construct(Ticket $tickets, Agent $agent)
    {
        $this->middleware('Kordy\Ticketit\Middleware\ResAccessMiddleware', ['only' => ['show']]);
        $this->middleware('Kordy\Ticketit\Middleware\IsAgentMiddleware', ['only' => ['edit', 'update']]);
        $this->middleware('Kordy\Ticketit\Middleware\IsAdminMiddleware', ['only' => ['destroy']]);

        $this->tickets = $tickets;
        $this->agent = $agent;
    }

	// This is loaded via AJAX at file Views\index.blade.php
    public function data(Datatables $datatables, $ticketList = 'active')
    {
        $user = $this->agent->find(auth()->user()->id);

        $collection = Ticket::inList($ticketList)->visible()->filtered();

        $collection
            ->join('users', 'users.id', '=', 'ticketit.user_id')
            ->join('ticketit_statuses', 'ticketit_statuses.id', '=', 'ticketit.status_id')
            ->join('ticketit_priorities', 'ticketit_priorities.id', '=', 'ticketit.priority_id')
            ->join('ticketit_categories', 'ticketit_categories.id', '=', 'ticketit.category_id')
            ->leftJoin('ticketit_taggables', function ($join) {
                $join->on('ticketit.id', '=', 'ticketit_taggables.taggable_id')
                    ->where('ticketit_taggables.taggable_type', '=', 'Kordy\\Ticketit\\Models\\Ticket');
            })
            ->leftJoin('ticketit_tags', 'ticketit_taggables.tag_id', '=', 'ticketit_tags.id')
            ->groupBy('ticketit.id')
            ->select([
                'ticketit.id',
                'ticketit.subject AS subject',
                'ticketit.content AS content',
                'ticketit.intervention AS intervention',
                'ticketit_statuses.name AS status',
                'ticketit_statuses.color AS color_status',
                'ticketit_priorities.color AS color_priority',
                'ticketit_categories.color AS color_category',
                'ticketit.id AS agent',
                'ticketit.updated_at AS updated_at',
                'ticketit_priorities.name AS priority',
                'users.name AS owner',
                'ticketit.agent_id',
                'ticketit_categories.name AS category',
                \DB::raw('group_concat(ticketit_tags.id) AS tags_id'),
                \DB::raw('group_concat(ticketit_tags.name) AS tags'),
                \DB::raw('group_concat(ticketit_tags.bg_color) AS tags_bg_color'),
                \DB::raw('group_concat(ticketit_tags.text_color) AS tags_text_color'),
            ])
			->withCount('comments');

        $collection = $datatables->of($collection);

        $this->renderTicketTable($collection);

        $collection->editColumn('updated_at', '{!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $updated_at)->diffForHumans() !!}');

        return $collection->make(true);
    }

    public function renderTicketTable(EloquentEngine $collection)
    {
		// Agents for each category
		$a_cat_pre = Category::select('id')
			->withCount('agents')
			->with([
				'agents' => function($q1){
					$q1->select('id','name');
				}
			
			])
			->get()->toArray();
		
		$a_cat = [];
		foreach ($a_cat_pre as $cat){
			$a_cat[$cat['id']] = $cat;
			$html = '<div>';
			foreach ($cat['agents'] as $agent){
				$html.='<label><input type="radio" name="%1$s_agent" value="'.$agent['id'].'"> '.$agent['name'].'</label><br />';
			}
			$html.='<br /><button type="button" class="jquery_submit_integrated_agent" data-ticket-id="%1$s">'.trans('ticketit::lang.btn-change').'</button></div>';
			$a_cat[$cat['id']]['html']=$html;
			
		}
		
		// Column edits		
        $collection->editColumn('subject', function ($ticket) {
            return (string) link_to_route(
                Setting::grab('main_route').'.show',
                $ticket->subject,
                $ticket->id
            );
        });
		
		$collection->editColumn('intervention', function ($ticket) {
			$field=$ticket->intervention;
			if ($ticket->intervention!="" and $ticket->comments_count>0) $field.="<br />";
			if ($ticket->comments_count>0){
				$field.=$ticket->comments_count.' <span class="glyphicons glyphicon glyphicon-transfer" title="Comentaris"></span>';
			}
			
			return $field;
		});

        $collection->editColumn('status', function ($ticket) {
            $color = $ticket->color_status;
            $status = $ticket->status;

            return "<div style='color: $color'>$status</div>";
        });

        $collection->editColumn('priority', function ($ticket) {
            $color = $ticket->color_priority;
            $priority = $ticket->priority;

            return "<div style='color: $color'>$priority</div>";
        });

        $collection->editColumn('category', function ($ticket) {
            $color = $ticket->color_category;
            $category = $ticket->category;

            return "<div style='color: $color'>$category</div>";
        });

        $collection->editColumn('agent', function ($ticket) use($a_cat) {
            $ticket = $this->tickets->find($ticket->id);
			$count = $a_cat[$ticket->category_id]['agents_count'];
			
            $text = '<a href="#" class="jquery_agent_change_'.($count>4 ? 'modal' : ($count == 1 ? 'info' : 'integrated')).'" ';
			
			if($count>4){
				$text.= ' title="'.trans('ticketit::lang.table-change-agent').'"';
			}elseif($count==1){
				$text.= ' title="'.trans('ticketit::lang.table-one-agent').'" data-toggle="tooltip" data-placement="auto bottom" ';
			}else{
				$text.= ' title="'.trans('ticketit::lang.agents').'" data-toggle="popover" data-placement="auto bottom" data-content="'.e(sprintf($a_cat[$ticket->category_id]['html'],$ticket->id)).'" ';
			}
			$text.= 'data-ticket-id="'.$ticket->id.'" data-category-id="'.$ticket->category_id.'" data-agent-id="'.$ticket->agent->id.'">'.$ticket->agent->name.'</a>';
				
			return $text;
        });

        $collection->editColumn('tags', function ($ticket) {
            $text = '';
            if ($ticket->tags != '') {
                $a_ids = explode(',', $ticket->tags_id);
                $a_tags = array_combine($a_ids, explode(',', $ticket->tags));
                $a_bg_color = array_combine($a_ids, explode(',', $ticket->tags_bg_color));
                $a_text_color = array_combine($a_ids, explode(',', $ticket->tags_text_color));
                foreach ($a_tags as $id=> $tag) {
                    $text .= '<button class="btn btn-default btn-tag btn-xs" style="pointer-events: none; background-color: '.$a_bg_color[$id].'; color: '.$a_text_color[$id].'">'.$tag.'</button> ';
                }
            }

            return $text;
        });

        return $collection;
    }

    /**
     * Display a listing of active tickets related to user.
     *
     * @return Response
     */
    public function index(Request $request)
    {
        return $this->indexProcess($request, 'active');		
    }
	
	/**
     * Display a listing of active tickets related to user.
     *
     * @return Response
     */
    public function indexNewest(Request $request)
    {
		return $this->indexProcess($request, 'newest');		
    }

    /**
     * Display a listing of completed tickets related to user.
     *
     * @return Response
     */
    public function indexComplete(Request $request)
    {
        return $this->indexProcess($request, 'complete');
    }
	
	/*
	 * Processes the selected index with data 
	*/
	public function indexProcess($request, $ticketList)
	{
		$a_cat_agents = Category::with(['agents'=>function($q){$q->select('id','name');}])->select('id','name')->get();
			
		return view('ticketit::index', [
			'ticketList'=>$ticketList,
			'counts'=>$this->ticketCounts($request, $ticketList),
			'a_cat_agents'=>$a_cat_agents			
		]);
	}

    /**
     * Calculates Tickets counts to show.
     *
     * @return
     */
    public function ticketCounts($request, $ticketList)
    {
        $counts = [];
        $category = session('ticketit_filter_category') == '' ? null : session('ticketit_filter_category');

        if ($this->agent->isAdmin() or ($this->agent->isAgent() and Setting::grab('agent_restrict') == 0)) {
            // Ticket count for all categories
            $counts['total_category'] = Ticket::inList($ticketList)->visible()->count();

            // Ticket count for each Category
            if ($this->agent->isAdmin()) {
                $counts['category'] = Category::orderBy('name')->withCount(['tickets'=> function ($q) use ($ticketList) {
                    $q->inList($ticketList);
                }])->get();
            } else {
                $counts['category'] = Agent::where('id', auth()->user()->id)->firstOrFail()->categories()->orderBy('name')->withCount(['tickets'=> function ($q) use ($ticketList) {
                    $q->inList($ticketList);
                }])->get();
            }

            // Ticket count for all agents
            if (session('ticketit_filter_category') != '') {
                $counts['total_agent'] = $counts['category']->filter(function ($q) use ($category) {
                    return $q->id == $category;
                })->first()->tickets_count;
            } else {
                $counts['total_agent'] = $counts['total_category'];
            }

            // Ticket counts for each visible Agent
            if (session('ticketit_filter_category') != '') {
                $counts['agent'] = Agent::visible()->whereHas('categories', function ($q1) use ($category) {
                    $q1->where('id', $category);
                });
            } else {
                $counts['agent'] = Agent::visible();
            }

            $counts['agent'] = $counts['agent']->withCount(['agentTotalTickets'=> function ($q2) use ($ticketList, $category) {
                $q2->inList($ticketList)->visible()->inCategory($category);
            }])->get();
        }

        // Forget agent if it doesn't exist in current category
        $agent = session('ticketit_filter_agent');
        if (isset($counts['agent']) and $counts['agent']->filter(function ($q) use ($agent) {
            return $q->id == $agent;
        })->count() == 0) {
            $request->session()->forget('ticketit_filter_agent');
        }

        if ($this->agent->isAdmin() or $this->agent->isAgent()) {
            // All visible Tickets (depends on selected Agent)
            if (session('ticketit_filter_agent') == '') {
                if (isset($counts['total_agent'])) {
                    $counts['owner']['all'] = $counts['total_agent'];
                } else {
                    // Case of agent with agent_restrict == 1
                    $counts['owner']['all'] = Ticket::inList($ticketList)->inCategory($category)->agentTickets(auth()->user()->id)->count();
                }
            } else {
                $counts['owner']['all'] = Ticket::inList($ticketList)->inCategory($category)->agentTickets(session('ticketit_filter_agent'))->visible()->count();
            }

            // Current user Tickets
            $me = Ticket::inList($ticketList)->userTickets(auth()->user()->id);
            if (session('ticketit_filter_agent') != '') {
                $me = $me->agentTickets(session('ticketit_filter_agent'));
            }
            $counts['owner']['me'] = $me->count();
        }

        return $counts;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
        if (version_compare(app()->version(), '5.2.0', '>=')) {
            $status_lists = Models\Status::pluck('name', 'id');
			$priorities = Models\Priority::pluck('name', 'id');
            $categories = Models\Category::pluck('name', 'id');
        } else { // if Laravel 5.1
            $status_lists = Models\Status::lists('name', 'id');
			$priorities = Models\Priority::lists('name', 'id');
            $categories = Models\Category::lists('name', 'id');
        }
		
		$user = $this->agent->find(auth()->user()->id);
		$a_current = [];
		
		if (!old('category_id')){
			$a_current['complete'] = "no";
			
			// Default category		
			$a_current['cat_id'] = @$user->tickets()->latest()->first()->category_id;

			if ($a_current['cat_id'] == null){
				if ($user->isAgent() and $a_current['cat_id'] = $user->categories()->wherePivot('autoassign','1')->first()->id){
				
				}else{
					$a_current['cat_id'] = key($categories);
				}
			}
			
			// Default agent
			$a_current['agent_id'] = $user->id;
			
		}else{
			// Form old values
			$a_current['complete'] = old('complete');
			$a_current['cat_id'] = old('category_id');
			$a_current['agent_id'] = old('agent_id');
		}

		
		// Agent list
		$agent_lists = $this->agentList($a_current['cat_id']);
		
		
		// Permission level for category
		$permission_level = Agent::levelIn($a_current['cat_id']);
		
		// Current default status
		$a_current['status_id'] = $permission_level > 1 ? Setting::grab('default_reopen_status_id') : Setting::grab('default_status_id');

		
		// Tag lists
        $tag_lists = Category::whereHas('tags')
        ->with([
            'tags'=> function ($q1) {
                $q1->select('id', 'name');
            },
            'tags.tickets'=> function ($q2) {
                $q2->where('id', '0')->select('id');
            },
        ])
        ->select('id', 'name')->get();
		
		// Selected tags
		$a_tags_selected = (old('category_id') and old('category_'.old('category_id').'_tags')) ? old('category_'.old('category_id').'_tags') : [];
		
        return view('ticketit::tickets.create', compact('priorities', 'status_lists', 'categories', 'agent_lists', 'a_current', 'permission_level', 'tag_lists', 'a_tags_selected'));
    }

    /**
     * Store a newly created ticket and auto assign an agent for it.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {	
	    $user = $this->agent->find(auth()->user()->id);
		$permission_level = $user->levelIn($request->category_id);
		
		$a_content = $this->purifyHtml($request->get('content'));
        $request->merge([
            'subject'=> trim($request->get('subject')),
            'content'=> $a_content['content'],
			'content_html'=> $a_content['html'],
        ]);
		
		$fields = [
            'subject'     => 'required|min:3',
            'content'     => 'required|min:6',            
            'category_id' => 'required|exists:ticketit_categories,id',
        ];
		
		if ($permission_level > 1) {
			$fields['status_id'] = 'required|exists:ticketit_statuses,id';
			$fields['priority_id'] = 'required|exists:ticketit_priorities,id';
			
			$a_intervention = $this->purifyInterventionHtml($request->get('intervention'));
			$request->merge([
				'intervention'=> $a_intervention['intervention'],
				'intervention_html'=> $a_intervention['intervention_html'],
			]);
        }

		// Custom validation messages
		$custom_messages = [
			'subject.required' => 'ticketit::lang.validate-ticket-subject.required',
			'subject.min' => 'ticketit::lang.validate-ticket-subject.min',
			'content.required' => 'ticketit::lang.validate-ticket-content.required',
			'content.min' => 'ticketit::lang.validate-ticket-content.min',
		];
		foreach ($custom_messages as $field => $lang_key){
			$trans = trans ($lang_key);
			if ($lang_key == $trans){
				unset($custom_messages[$field]);
			}else{
				$custom_messages[$field] = $trans;
			}
		}		
		
		// Form validation
        $this->validate($request, $fields, $custom_messages);
		
        $ticket = new Ticket();

        $ticket->subject = $request->subject;
		$ticket->user_id = auth()->user()->id;
		
		if ($permission_level > 1) {
			if ($request->complete=='yes'){
				$ticket->completed_at = Carbon::now();
			}
			
			$ticket->status_id = $request->status_id;
			$ticket->priority_id = $request->priority_id;			
		}else{
			$ticket->status_id = Setting::grab('default_status_id');		
			$ticket->priority_id = Models\Priority::first()->id;
		}		

		$ticket->category_id = $request->category_id;
		$ticket->autoSelectAgent();
		
        $ticket->content = $a_content['content'];
        $ticket->html = $a_content['html'];

        if ($permission_level > 1) {
            $ticket->intervention = $a_intervention['intervention'];
			$ticket->intervention_html = $a_intervention['intervention_html'];
		}
		
        $ticket->save();

        $this->sync_ticket_tags($request, $ticket);

        session()->flash('status', trans('ticketit::lang.the-ticket-has-been-created'));

        return redirect()->action('\Kordy\Ticketit\Controllers\TicketsController@index');
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
        $ticket = $this->tickets->with('tags')->find($id);

        if (version_compare(app()->version(), '5.3.0', '>=')) {
            $status_lists = Models\Status::pluck('name', 'id');
            $priority_lists = Models\Priority::pluck('name', 'id');
            $category_lists = $a_categories = Models\Category::pluck('name', 'id');
            $a_tags_selected = $ticket->tags()->pluck('id')->toArray();
        } else { // if Laravel 5.1
            $status_lists = Models\Status::lists('name', 'id');
            $priority_lists = Models\Priority::lists('name', 'id');
            $category_lists = $a_categories = Models\Category::lists('name', 'id');
            $a_tags_selected = $ticket->tags()->lists('id')->toArray();
        }

        // Category tags
        $tag_lists = Category::whereHas('tags')
        ->with([
            'tags'=> function ($q1) use ($id) {
                $q1->select('id', 'name');
            },
        ])
        ->select('id', 'name')->get();

        $close_perm = $this->permToClose($id);
        $reopen_perm = $this->permToReopen($id);

        $agent_lists = $this->agentList($ticket->category_id);
		
		if (Agent::levelIn($ticket->category_id) > 1){
			$comments = $ticket->comments();
		}else{
			$comments = $ticket->comments()->where('type','reply');
		}
        $comments = $comments->orderBy('created_at','desc')->paginate(Setting::grab('paginate_items'));

        return view('ticketit::tickets.show',
            compact('ticket', 'a_tags_selected', 'status_lists', 'priority_lists', 'category_lists', 'a_categories', 'agent_lists', 'tag_lists',
                'comments', 'close_perm', 'reopen_perm'));
    }
	
    /**
     * Update the specified resource in storage.
     *
     * @param Request $request
     * @param int     $id
     *
     * @return Response
     */
    public function update(Request $request, $id)
    {
		$a_content = $this->purifyHtml($request->get('content'));
        $request->merge([
            'subject'=> trim($request->get('subject')),
            'content'=> $a_content['content'],
        ]);
		
		
		$fields = [
            'subject'     => 'required|min:3',
            'content'     => 'required|min:6',
            'priority_id' => 'required|exists:ticketit_priorities,id',
            'category_id' => 'required|exists:ticketit_categories,id',
            'status_id'   => 'required|exists:ticketit_statuses,id',
            'agent_id'    => 'required',
        ];

        $user = $this->agent->find(auth()->user()->id);
        if ($user->isAgent() or $user->isAdmin()) {			
			$a_intervention = $this->purifyInterventionHtml($request->get('intervention'));
			$request->merge([
				'intervention'=> $a_intervention['intervention']
			]);
        }

        $this->validate($request, $fields);

        $ticket = $this->tickets->findOrFail($id);

        $ticket->subject = $request->subject;

        $ticket->content = $a_content['content'];
        $ticket->html = $a_content['html'];

		$user = $this->agent->find(auth()->user()->id);
        if ($user->isAgent() or $user->isAdmin()) {
            $ticket->intervention = $a_intervention['intervention'];
			$ticket->intervention_html = $a_intervention['intervention_html'];
		}

        $ticket->status_id = $request->status_id;
        $ticket->category_id = $request->category_id;
        $ticket->priority_id = $request->priority_id;

        if ($request->input('agent_id') == 'auto') {
            $ticket->autoSelectAgent();
        } else {
            $ticket->agent_id = $request->input('agent_id');
        }

        $ticket->save();

        $this->sync_ticket_tags($request, $ticket);

        session()->flash('status', trans('ticketit::lang.the-ticket-has-been-modified'));

        return redirect()->route(Setting::grab('main_route').'.show', $id);
    }

    /**
     * Syncs tags for ticket instance.
     *
     * @param $ticket instance of Kordy\Ticketit\Models\Ticket
     */
    protected function sync_ticket_tags($request, $ticket)
    {

        // Get marked current tags
        $input_tags = $request->input('category_'.$request->input('category_id').'_tags');
        if (!$input_tags) {
            $input_tags = [];
        }

        // Valid tags has all category tags
        $category_tags = $ticket->category->tags()->get();
        $category_tags = (version_compare(app()->version(), '5.3.0', '>=')) ? $category_tags->pluck('id')->toArray() : $category_tags->lists('id')->toArray();
        // Valid tags has ticket tags that doesn't have category
        $ticket_only_tags = Tag::doesntHave('categories')->whereHas('tickets', function ($q2) use ($ticket) {
            $q2->where('id', $ticket->id);
        })->get();
        $ticket_only_tags = (version_compare(app()->version(), '5.3.0', '>=')) ? $ticket_only_tags->pluck('id')->toArray() : $ticket_only_tags->lists('id')->toArray();

        $tags = array_intersect($input_tags, array_merge($category_tags, $ticket_only_tags));

        // Sync all ticket tags
        $ticket->tags()->sync($tags);

        // Delete orphan tags (Without any related categories or tickets)
        Tag::doesntHave('categories')->doesntHave('tickets')->delete();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     *
     * @return Response
     */
    public function destroy($id)
    {
        $ticket = $this->tickets->findOrFail($id);
        $subject = $ticket->subject;
        $ticket->delete();

        session()->flash('status', trans('ticketit::lang.the-ticket-has-been-deleted', ['name' => $subject]));

        // Delete orphan tags (Without any related categories or tickets)
        Tag::doesntHave('categories')->doesntHave('tickets')->delete();

        return redirect()->route(Setting::grab('main_route').'.index');
    }

    /**
     * Mark ticket as complete.
     *
     * @param int $id
     *
     * @return Response
     */
    public function complete($id)
    {
        if ($this->permToClose($id) == 'yes') {
            $ticket = $this->tickets->findOrFail($id);
            $ticket->completed_at = Carbon::now();

            if (Setting::grab('default_close_status_id')) {
                $ticket->status_id = Setting::grab('default_close_status_id');
            }

            $subject = $ticket->subject;
            $ticket->save();

            session()->flash('status', trans('ticketit::lang.the-ticket-has-been-completed', ['name' => $subject]));

            return redirect()->route(Setting::grab('main_route').'.index');
        }

        return redirect()->route(Setting::grab('main_route').'.index')
            ->with('warning', trans('ticketit::lang.you-are-not-permitted-to-do-this'));
    }

    /**
     * Reopen ticket from complete status.
     *
     * @param int $id
     *
     * @return Response
     */
    public function reopen($id)
    {
        if ($this->permToReopen($id) == 'yes') {
            $ticket = $this->tickets->findOrFail($id);
            $ticket->completed_at = null;

            if (Setting::grab('default_reopen_status_id')) {
                $ticket->status_id = Setting::grab('default_reopen_status_id');
            }

            $subject = $ticket->subject;
            $ticket->save();

            session()->flash('status', trans('ticketit::lang.the-ticket-has-been-reopened', ['name' => $subject]));

            return redirect()->route(Setting::grab('main_route').'.index');
        }

        return redirect()->route(Setting::grab('main_route').'.index')
            ->with('warning', trans('ticketit::lang.you-are-not-permitted-to-do-this'));
    }

	/*
	 * Returns HTML <SELECT> with Agent List for specified category
	*/
	public function agentSelectList($category_id, $selected_Agent = false)
    {
		$agents = $this->agentList($category_id);

        $select = '<select class="form-control" id="agent_id" name="agent_id">';
        foreach ($agents as $id => $name) {
            $selected = ($id == $selected_Agent) ? 'selected' : '';
            $select .= '<option value="'.$id.'" '.$selected.'>'.$name.'</option>';
        }
        $select .= '</select>';

        return $select;
    }
	
	/*
	 * Returns array with Agent List for specified category
	*/
	public function agentList ($category_id)
	{
		$cat_agents = Models\Category::find($category_id)->agents()->agentsLists();
        if (is_array($cat_agents)) {
            return ['auto' => 'Auto Select'] + $cat_agents;
        } else {
            return ['auto' => 'Auto Select'];
        }
	}
	
	/*
	 * Change agent in ticket list
	*/
	public function changeAgent(Request $request){
		$ticket = Ticket::findOrFail($request->input('ticket_id'));
		Agent::findOrFail($request->input('agent_id'));
		
		if ($ticket->agent_id==$request->input('agent_id')){
			return redirect()->back()->with('warning', 'No has canviat l\'agent');
		}else{
			$ticket->agent_id = $request->input('agent_id');
			
			if ($ticket->status_id==Setting::grab('default_status_id')){
				$ticket->status_id=Setting::grab('default_reopen_status_id');
			}
			$ticket->save();
			return redirect()->back()->with('status', 'Agent canviat correctament');
		}		
	}
	
	public function permissionLevel ($category_id)
	{
		return Agent::levelIn($category_id);
	}
	

    /**
     * @param $id
     *
     * @return bool
     */
    public function permToClose($id)
    {
        $user = $this->agent->find(auth()->user()->id);
		
		return $user->canCloseTicket($id) ? "yes" : "no";
    }

    /**
     * @param $id
     *
     * @return bool
     */
    public function permToReopen($id)
    {
        $reopen_ticket_perm = Setting::grab('reopen_ticket_perm');
        if ($this->agent->isAdmin() && $reopen_ticket_perm['admin'] == 'yes') {
            return 'yes';
        } elseif ($this->agent->isAgent() && $reopen_ticket_perm['agent'] == 'yes') {
            return 'yes';
        } elseif ($this->agent->isTicketOwner($id) && $reopen_ticket_perm['owner'] == 'yes') {
            return 'yes';
        }

        return 'no';
    }

    /**
     * Calculate average closing period of days per category for number of months.
     *
     * @param int $period
     *
     * @return \Illuminate\Database\Eloquent\Collection|static[]
     */
    public function monthlyPerfomance($period = 2)
    {
        $categories = Category::all();
        foreach ($categories as $cat) {
            $records['categories'][] = $cat->name;
        }

        for ($m = $period; $m >= 0; $m--) {
            $from = Carbon::now();
            $from->day = 1;
            $from->subMonth($m);
            $to = Carbon::now();
            $to->day = 1;
            $to->subMonth($m);
            $to->endOfMonth();
            $records['interval'][$from->format('F Y')] = [];
            foreach ($categories as $cat) {
                $records['interval'][$from->format('F Y')][] = round($this->intervalPerformance($from, $to, $cat->id), 1);
            }
        }

        return $records;
    }

    /**
     * Calculate the date length it took to solve a ticket.
     *
     * @param Ticket $ticket
     *
     * @return int|false
     */
    public function ticketPerformance($ticket)
    {
        if ($ticket->completed_at == null) {
            return false;
        }

        $created = new Carbon($ticket->created_at);
        $completed = new Carbon($ticket->completed_at);
        $length = $created->diff($completed)->days;

        return $length;
    }

    /**
     * Calculate the average date length it took to solve tickets within date period.
     *
     * @param $from
     * @param $to
     *
     * @return int
     */
    public function intervalPerformance($from, $to, $cat_id = false)
    {
        if ($cat_id) {
            $tickets = Ticket::where('category_id', $cat_id)->whereBetween('completed_at', [$from, $to])->get();
        } else {
            $tickets = Ticket::whereBetween('completed_at', [$from, $to])->get();
        }

        if (empty($tickets->first())) {
            return false;
        }

        $performance_count = 0;
        $counter = 0;
        foreach ($tickets as $ticket) {
            $performance_count += $this->ticketPerformance($ticket);
            $counter++;
        }
        $performance_average = $performance_count / $counter;

        return $performance_average;
    }
}
