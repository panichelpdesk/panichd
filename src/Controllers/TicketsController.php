<?php

namespace Kordy\Ticketit\Controllers;

use App\Http\Controllers\Controller;
use Cache;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Kordy\Ticketit\Helpers\LaravelVersion;
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
            ->join('users as agent', 'agent.id', '=', 'ticketit.agent_id')
			->join('ticketit_priorities', 'ticketit_priorities.id', '=', 'ticketit.priority_id')
            ->join('ticketit_categories', 'ticketit_categories.id', '=', 'ticketit.category_id')			
			
            
			// Tags joins
			->leftJoin('ticketit_taggables', function ($join) {
                $join->on('ticketit.id', '=', 'ticketit_taggables.taggable_id')
                    ->where('ticketit_taggables.taggable_type', '=', 'Kordy\\Ticketit\\Models\\Ticket');
            })
            ->leftJoin('ticketit_tags', 'ticketit_taggables.tag_id', '=', 'ticketit_tags.id');
				
		$a_select = [
			'ticketit.id',
			'ticketit.subject AS subject',
			'ticketit.content AS content',
			'ticketit.intervention AS intervention',
			'ticketit_statuses.name AS status',
			'ticketit_statuses.color AS color_status',
			'ticketit_priorities.color AS color_priority',
			'ticketit_categories.color AS color_category',			
			'ticketit.start_date',
			'ticketit.limit_date',
			'ticketit.updated_at AS updated_at',
			'ticketit.agent_id',
			\DB::raw('group_concat(agent.name) AS agent_name'),
			'ticketit_priorities.name AS priority',
			'users.name AS owner_name',
			'ticketit.user_id',
			'ticketit.creator_id',		
			'ticketit_categories.name AS category',			
			
			// Tag Columns
			\DB::raw('group_concat(ticketit_tags.id) AS tags_id'),
			\DB::raw('group_concat(ticketit_tags.name) AS tags'),
			\DB::raw('group_concat(ticketit_tags.bg_color) AS tags_bg_color'),
			\DB::raw('group_concat(ticketit_tags.text_color) AS tags_text_color'),
		];
		
		if (Setting::grab('departments_feature')){			
			// Department joins
			$collection				
				->leftJoin('ticketit_departments_persons', function ($join1) {
					$join1->on('users.person_id','=','ticketit_departments_persons.person_id');
				})	
				->leftJoin('ticketit_departments','ticketit_departments_persons.department_id','=','ticketit_departments.id');
			
			// Department columns				
			$a_select[] = \DB::raw('group_concat(distinct(ticketit_departments.department)) AS dept_info');
			$a_select[] = \DB::raw('group_concat(distinct(ticketit_departments.shortening)) AS dept_short');
			$a_select[] = \DB::raw('group_concat(distinct(ticketit_departments.sub1)) AS dept_sub1');			
		}
		
		$collection
            ->groupBy('ticketit.id')
            ->select($a_select)
			->with('creator')
			->with('owner.personDepts.department')
			->withCount('comments')
			->withCount('recentComments');

        $collection = $datatables->of($collection);

		\Carbon\Carbon::setLocale(config('app.locale'));
		
        $this->renderTicketTable($collection);

        $collection->editColumn('updated_at', '{!! \Carbon\Carbon::createFromFormat("Y-m-d H:i:s", $updated_at)->diffForHumans() !!}');

        // method rawColumns was introduced in laravel-datatables 7, which is only compatible with >L5.4
        // in previous laravel-datatables versions escaping columns wasn't defaut
        if (LaravelVersion::min('5.4')) {
            $collection->rawColumns(['subject', 'status', 'priority', 'category', 'agent']);
        }

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
			if ($ticket->recent_comments_count>0){
				$field.=$ticket->recent_comments_count;
			}
			if ($ticket->comments_count>0){
				$field.=' <span class="glyphicons glyphicon glyphicon-transfer" title="Comentaris"></span>';
			}
			
			return $field;
		});

        $collection->editColumn('status', function ($ticket) {
            $color = $ticket->color_status;
            $status = e($ticket->status);

            return "<div style='color: $color'>$status</div>";
        });

        $collection->editColumn('priority', function ($ticket) {
            $color = $ticket->color_priority;
            $priority = e($ticket->priority);

            return "<div style='color: $color'>$priority</div>";
        });
		
		$collection->editColumn('owner_name', function ($ticket) {
			$return = str_replace (" ", "&nbsp;", $ticket->owner_name);
			if ($ticket->user_id != $ticket->creator_id){
				$return .="&nbsp;<span class=\"glyphicon glyphicon-user tooltip-info\" title=\"".trans('ticketit::lang.show-ticket-creator').trans('ticketit::lang.colon').$ticket->creator->name."\" data-toggle=\"tooltip\" data-placement=\"auto bottom\" style=\"color: #aaa;\"></span>";				
			}
			
			return $return;
		});
		
		if (Setting::grab('departments_feature')){
			$collection->editColumn('dept_info', function ($ticket) {
				$dept_info = $title = "";			
				
				if ($ticket->owner->person_id and $ticket->owner->personDepts[0]){
					$dept_info = $ticket->owner->personDepts[0]->department->resume();
					$title = $ticket->owner->personDepts[0]->department->title();
				}				
				
				return "<span title=\"$title\">$dept_info</span>";
			});
		}
		
		$collection->editColumn('calendar', function ($ticket) {
            
			$date = $title = $icon = "";
			$color = "text-muted";
			$start_days_diff = Carbon::now()->diffInDays(Carbon::parse($ticket->start_date), false);
			if ($ticket->limit_date != ""){
				$limit_days_diff = Carbon::now()->diffInDays(Carbon::parse($ticket->limit_date), false);
				if ($limit_days_diff == 0){
					$limit_seconds_diff = Carbon::now()->diffInSeconds(Carbon::parse($ticket->limit_date), false);
				}
			}else{
				$limit_days_diff = false;
			}
			
			if ($limit_days_diff < 0 or ($limit_days_diff == 0 and isset($limit_seconds_diff) and $limit_seconds_diff < 0)){
				// Expired
				$date = $ticket->limit_date;
				$title = trans('ticketit::lang.calendar-expired');
				$icon = "glyphicon-exclamation-sign";
				$color = "text-danger";
			}elseif($limit_days_diff > 0 or $limit_days_diff === false){
				if ($start_days_diff > 0){
					// Scheduled
					$date = $ticket->start_date;
					$title = trans('ticketit::lang.calendar-scheduled');
					$icon = "glyphicon-calendar";
					$color = "text-info";
				}elseif($limit_days_diff){
					// Active with limit
					$date = $ticket->limit_date;
					$title = trans('ticketit::lang.calendar-expiration');
					$icon = "glyphicon-time";
				}else{
					// Active without limit
					$date = $ticket->start_date;
					$title = trans('ticketit::lang.calendar-active');
					$icon = "glyphicon-file";					
				}				
			}else{
				// Due today
				$date = $ticket->limit_date;
				$title = trans('ticketit::lang.calendar-expires-today');
				$icon = "glyphicon-warning-sign";
				$color = "text-warning";
			}

			return "<div class=\"tooltip-info $color\" title=\"$title\" data-toggle=\"tooltip\"><span class=\"glyphicon $icon\"></span> ".Carbon::parse($date)->diffForHumans()."</div>";            
        });

        $collection->editColumn('category', function ($ticket) {
            $color = $ticket->color_category;
            $category = e($ticket->category);

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
			$text.= 'data-ticket-id="'.$ticket->id.'" data-category-id="'.$ticket->category_id.'" data-agent-id="'.$ticket->agent_id.'">'.$ticket->agent->name.'</a>';
				
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
		
		if ($this->agent->isAdmin() or $this->agent->isAgent()){
			// Ticket count for all categories
            $counts['total'] = Ticket::inList($ticketList)->visible()->count();
			
			// Calendar expired count
			$counts['calendar']['expired'] = Ticket::inList($ticketList)->visible()->where('limit_date','<', Carbon::now())->count();
						
			// Calendar forth counts
			$cals = Ticket::inList($ticketList)->visible()->whereBetween('limit_date', [
				Carbon::now()->today(),
				Carbon::now()->endOfWeek()
			])
			->orWhereBetween('limit_date', [
				Carbon::now()->today(),
				Carbon::now()->endOfMonth()
			])->get();
			
			$counts['calendar']['today'] = $cals->filter(function($q){
				return $q->limit_date < Carbon::now()->tomorrow(); 
			})->count();
			
			$counts['calendar']['tomorrow'] = $cals->filter(function($q){
				return $q->limit_date >= Carbon::now()->tomorrow(); 
			})
			->filter(function($q2){
				return $q2->limit_date < Carbon::now()->addDays(3)->startOfDay(); 
			})->count();
			
			$counts['calendar']['week'] = $cals->filter(function($q){
				return $q->limit_date < Carbon::now()->endOfWeek();
			})->count();
			
			$counts['calendar']['month'] = $cals->filter(function($q){
				return $q->limit_date < Carbon::now()->endOfMonth();
			})->count();
		}
		
		
        if ($this->agent->isAdmin() or ($this->agent->isAgent() and Setting::grab('agent_restrict') == 0)) {            

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
                $counts['total_agent'] = $counts['total'];
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
     * Returns priorities, categories and statuses lists in this order
     * Decouple it with list()
     *
     * @return array
     */
    protected function PCS()
    {
        $priorities = Cache::remember('ticketit::priorities', 60, function() {
            return Models\Priority::all();
        });

        $categories = Cache::remember('ticketit::categories', 60, function() {
            return Models\Category::all();
        });

        $statuses = Cache::remember('ticketit::statuses', 60, function() {
            return Models\Status::all();
        });

        if (LaravelVersion::min('5.3.0')) {
            return [$priorities->pluck('name', 'id'), $categories->pluck('name', 'id'), $statuses->pluck('name', 'id')];
        } else {
            return [$priorities->lists('name', 'id'), $categories->lists('name', 'id'), $statuses->lists('name', 'id')];
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function create()
    {
		$data = $this->create_edit_data();
		
		$data['ticket_owner_id'] = auth()->user()->id;
		
        return view('ticketit::tickets.createedit', $data);
    }
	
	public function edit($id){
		$ticket = $this->tickets->findOrFail($id);
		
		$data = $this->create_edit_data($ticket);
		
		$data['ticket'] = $ticket;
		
		$data['ticket_owner_id'] = $data['ticket']->user_id;
		
        return view('ticketit::tickets.createedit', $data);
	}
	
	public function create_edit_data($ticket = false)
	{
		$user = $this->agent->find(auth()->user()->id);
		
		if (Setting::grab('departments_notices_feature')){
			// Get my related departments
			$related_departments = [];
			foreach ($user->personDepts()->get() as $dept){
				foreach ($dept->department()->first()->related() as $rel){
					$related_departments [] = $rel->id;
				}			
			}

			/*
			 *	Get related Departamental users from my related departments
			 *
			 * Conditions:
			 *    - agent ticketit_department in related_departments
			 *    - agent person in related_departments
			*/
			$related_users = Agent::where('id','!=',$user->id)
				->whereIn('ticketit_department', $related_departments);		
			
			// Get users that are visible by all departments
			$all_dept_users = Agent::where('ticketit_department','0');
			
			if (version_compare(app()->version(), '5.3.0', '>=')) {
				$related_users = $related_users->pluck('id')->toArray();
				$related_users = array_unique(array_merge($related_users, $all_dept_users->pluck('id')->toArray()));
			}else{
				$related_users = $related_users->lists('id')->toArray();
				$related_users = array_unique(array_merge($related_users, $all_dept_users->lists('id')->toArray()));
			}
			
			// Get notices from related users
			$a_notices = Ticket::active()->whereIn('user_id', $related_users)
				->with('owner.personDepts.department')
				->with('status')->get();
		}else{
			$a_notices = [];
		}
			
		list($priorities, $categories, $status_lists) = $this->PCS();
		
		$a_current = [];

		\Carbon\Carbon::setLocale(config('app.locale'));
		
		if (old('category_id')){
			// Form old values
			$a_current['complete'] = old('complete');
			
			$a_current['start_date'] = old ('start_date');
			
			$a_current['cat_id'] = old('category_id');
			$a_current['agent_id'] = old('agent_id');
			
		}elseif($ticket){
			// Edition values
			$a_current['complete'] = $ticket->isComplete() ? "yes" : "no";
			$a_current['status_id'] = $ticket->status_id;
			
			$a_current['start_date'] = $ticket->start_date;
			
			$a_current['cat_id'] = $ticket->category_id;
			$a_current['agent_id'] = $ticket->agent_id;			
		}else{
			// Defaults
			$a_current['complete'] = "no";
			
			$a_current['start_date'] = "";
			
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
		}
		
		// Agent list
		$agent_lists = $this->agentList($a_current['cat_id']);
				
		// Permission level for category
		$permission_level = Agent::levelIn($a_current['cat_id']);
		
		// Current default status
		if (!$ticket){
			$a_current['status_id'] = $permission_level > 1 ? Setting::grab('default_reopen_status_id') : Setting::grab('default_status_id');
		}else{
			$a_current['status_id'] = $ticket->status_id;
		}
		
		// Current description and intervention
		if(old('category_id')){
			$a_current['description'] = old('content_html');
			$a_current['intervention'] = old('intervention_html');
		}elseif ($ticket){
			$a_current['description'] = $ticket->html;
			$a_current['intervention'] = $ticket->intervention_html;
		}else{
			$a_current['description'] = $a_current['intervention'] = "";
		}
		
				
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
		if (old('category_id') and old('category_'.old('category_id').'_tags')){
			$a_tags_selected = old('category_'.old('category_id').'_tags');
			
		}elseif($ticket){
			if (version_compare(app()->version(), '5.3.0', '>=')) {				
				$a_tags_selected = $ticket->tags()->pluck('id')->toArray();
			} else { // if Laravel 5.1				
				$a_tags_selected = $ticket->tags()->lists('id')->toArray();
			}			
		}else{
			$a_tags_selected = [];
		}
		
		return compact('a_notices', 'priorities', 'status_lists', 'categories', 'agent_lists', 'a_current', 'permission_level', 'tag_lists', 'a_tags_selected');
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
			'owner_id'    => 'required|exists:users,id',
			'category_id' => 'required|exists:ticketit_categories,id',
            'content'     => 'required|min:6',            
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
		$ticket->creator_id = auth()->user()->id;
		$ticket->user_id = $request->owner_id;
		
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

		if ($request->start_date != ""){
			$ticket->start_date = date('Y-m-d H:i:s', strtotime($request->start_date));
		}else{
			$ticket->start_date = date('Y-m-d H:i:s');
		}
		if ($request->limit_date == ""){
			$ticket->limit_date = null;
		}else{
			$ticket->limit_date = date('Y-m-d H:i:s', strtotime($request->limit_date));
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

        session()->flash('status', trans('ticketit::lang.the-ticket-has-been-created', [
			'name' => '#'.$ticket->id.' '.$ticket->subject,
			'link' => route(Setting::grab('main_route').'.show', $ticket->id),
			'title' => trans('ticketit::lang.ticket-status-link-title')
		]));

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
        $ticket = $this->tickets->with('category.closingReasons')->with('tags');
		$user = $this->agent->find(auth()->user()->id);
		
		if ($user->currentLevel()>1 and Setting::grab('departments_feature')){
			// Departments related
			$ticket = $ticket->join('users', 'users.id', '=', 'ticketit.user_id')
			->leftJoin('ticketit_departments_persons', function ($join1) {
				$join1->on('users.person_id','=','ticketit_departments_persons.person_id');
			})
			->leftJoin('ticketit_departments','ticketit_departments_persons.department_id','=','ticketit_departments.id')
			->select('ticketit.*', 'ticketit_departments.department', 'ticketit_departments.sub1');
		}
		
		$ticket = $ticket->find($id);
		
        if (version_compare(app()->version(), '5.3.0', '>=')) {
            $a_reasons = $ticket->category->closingReasons()->pluck('text','id')->toArray();
			$a_tags_selected = $ticket->tags()->pluck('id')->toArray();
        } else { // if Laravel 5.1
            $a_reasons = $ticket->category->closingReasons()->lists('text','id')->toArray();
			$a_tags_selected = $ticket->tags()->lists('id')->toArray();
        }

        list($priority_lists, $category_lists, $status_lists) = $this->PCS();

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
			$comments = $ticket->comments()->where('type','!=','note');
		}
        $comments = $comments->orderBy('created_at','desc')->paginate(Setting::grab('paginate_items'));

        return view('ticketit::tickets.show',
            compact('ticket', 'a_reasons', 'a_tags_selected', 'status_lists', 'priority_lists', 'category_lists', 'a_categories', 'agent_lists', 'tag_lists',
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
            'priority_id' => 'required|exists:ticketit_priorities,id',
            'category_id' => 'required|exists:ticketit_categories,id',
            'status_id'   => 'required|exists:ticketit_statuses,id',
            'agent_id'    => 'required',
			'content'     => 'required|min:6',
        ];

        $user = $this->agent->find(auth()->user()->id);
        if ($user->isAgent() or $user->isAdmin()) {			
			$a_intervention = $this->purifyInterventionHtml($request->get('intervention'));
			$request->merge([
				'intervention'=> $a_intervention['intervention']
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
		
        $this->validate($request, $fields, $custom_messages);

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
		
		if ($request->start_date != ""){
			$ticket->start_date = date('Y-m-d H:i:s', strtotime($request->start_date));
		}else{
			$ticket->start_date = date('Y-m-d H:i:s');
		}
		if ($request->limit_date == ""){
			$ticket->limit_date = null;
		}else{
			$ticket->limit_date = date('Y-m-d H:i:s', strtotime($request->limit_date));
		}		

        if ($request->input('agent_id') == 'auto') {
            $ticket->autoSelectAgent();
        } else {
            $ticket->agent_id = $request->input('agent_id');
        }

        $ticket->save();

        $this->sync_ticket_tags($request, $ticket);

        session()->flash('status', trans('ticketit::lang.the-ticket-has-been-modified', ['name' => '#'.$ticket->id.' "'.$ticket->subject.'"']));

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
    public function complete(Request $request, $id)
    {
        if ($this->permToClose($id) == 'yes') {
            $ticket = $this->tickets->findOrFail($id);
			$user = $this->agent->find(auth()->user()->id);
			
			$reason_text = trans('ticketit::lang.complete-by-user', ['user' => $user->name]);
			
			if ($user->currentLevel()>1){
				if (!$ticket->intervention_html and !$request->exists('blank_intervention')){
					return redirect()->back()->with('warning', trans('ticketit::lang.show-ticket-complete-blank-intervention-alert'));
				}else{
					$status_id = $request->input('status_id');
					try {
						Models\Status::findOrFail($status_id);
					}catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e){
						return redirect()->back()->with('warning', trans('ticketit::lang.show-ticket-complete-bad-status'));
					}
				}

				$ticket->status_id = $status_id;
			}else{
				// Verify Closing Reason
				if ($ticket->has('category.closingReasons')){
					if (!$request->exists('reason_id')){
						return redirect()->back()->with('warning', trans('ticketit::lang.show-ticket-modal-complete-blank-reason-alert'));					
					}
					
					try {
						$reason = Models\Closingreason::findOrFail($request->input('reason_id'));
					}catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e){
						return redirect()->back()->with('warning', trans('ticketit::lang.show-ticket-complete-bad-reason-id'));
					}
					
					$reason_text .= trans('ticketit::lang.colon') . $reason->text;
					$ticket->status_id = $reason->status_id;
				}else{					
					$ticket->status_id = Setting::grab('default_close_status_id');
				}				
			}
			
			// Add Closing Reason to intervention field
			$ticket->intervention = $ticket->intervention . $reason_text;
			$ticket->intervention_html = $ticket->intervention_html . '<br />' .$reason_text;
			
			if ($user->currentLevel()<2){
				// Check clarification text
				$a_clarification = $this->purifyHtml($request->get('clarification'));
				if ($a_clarification['content'] != ""){
					$ticket->intervention = $ticket->intervention . $a_clarification['content'];
					$ticket->intervention_html = $ticket->intervention_html . $a_clarification['html'];
				}
			}
			
			$ticket->completed_at = Carbon::now();

            $ticket->save();
			
			// Add closing comment			
			$comment = new Models\Comment;
			$comment->type = "complete";
			
			if ($user->currentLevel()>1){ 
				$comment->content = $comment->html = trans('ticketit::lang.ticket-comment-type-complete');
			}else{
				$comment->content = $comment->html = trans('ticketit::lang.ticket-comment-type-complete') . ($reason ? trans('ticketit::lang.colon').$reason->text : '');
							
				if ($a_clarification['content'] != ""){
					$comment->content = $comment->content . $a_clarification['content'];
					$comment->html = $comment->html . $a_clarification['html'];
				}
			}			

			$comment->ticket_id = $id;
			$comment->user_id = $user->id;
			$comment->save();
			
			
            session()->flash('status', trans('ticketit::lang.the-ticket-has-been-completed', [
				'name' => '#'.$id.' '.$ticket->subject,
				'link' => route(Setting::grab('main_route').'.show', $id),
				'title' => trans('ticketit::lang.ticket-status-link-title')
			]));

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
			$user = $this->agent->find(auth()->user()->id);
			
            $ticket->completed_at = null;

            if (Setting::grab('default_reopen_status_id')) {
                $ticket->status_id = Setting::grab('default_reopen_status_id');
            }			
			
			$ticket->intervention = $ticket->intervention . trans('ticketit::lang.reopened-by-user', ['user' => $user->name]);
			$ticket->intervention_html = $ticket->intervention_html . '<br />' . trans('ticketit::lang.reopened-by-user', ['user' => $user->name]);					
			

            $ticket->save();
			
			// Add reopen comment
			$comment = new Models\Comment;
			$comment->type = "reopen";
			$comment->content = $comment->html = trans('ticketit::lang.ticket-comment-type-reopen');
			$comment->ticket_id = $id;
			$comment->user_id = $user->id;
			$comment->save();
			

            session()->flash('status', trans('ticketit::lang.the-ticket-has-been-reopened', [
				'name' => '#'.$id.' '.$ticket->subject,
				'link' => route(Setting::grab('main_route').'.show', $id),
				'title' => trans('ticketit::lang.ticket-status-link-title')
			]));

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
