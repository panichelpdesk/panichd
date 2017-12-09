<?php

namespace PanicHD\PanicHD\Controllers;

use App\Http\Controllers\Controller;
use Cache;
use Carbon\Carbon;
use DB;
use Illuminate\Http\Request;
use PanicHD\PanicHD\Helpers\LaravelVersion;
use Intervention\Image\ImageManagerStatic as Image;
use InvalidArgumentException;
use PanicHD\PanicHD\Models;
use PanicHD\PanicHD\Models\Agent;
use PanicHD\PanicHD\Models\Attachment;
use PanicHD\PanicHD\Models\Category;
use PanicHD\PanicHD\Models\Setting;
use PanicHD\PanicHD\Models\Tag;
use PanicHD\PanicHD\Models\Ticket;
use PanicHD\PanicHD\Traits\Attachments;
use PanicHD\PanicHD\Traits\Purifiable;

class TicketsController extends Controller
{
    use Attachments, Purifiable;

    protected $tickets;
    protected $agent;

    public function __construct(Ticket $tickets, Agent $agent)
    {
        $this->middleware('PanicHD\PanicHD\Middleware\UserAccessMiddleware', ['only' => ['show', 'downloadAttachment', 'viewAttachment']]);
        $this->middleware('PanicHD\PanicHD\Middleware\AgentAccessMiddleware', ['only' => ['edit', 'update', 'changeAgent', 'changePriority']]);
        $this->middleware('PanicHD\PanicHD\Middleware\IsAdminMiddleware', ['only' => ['destroy']]);

        $this->tickets = $tickets;
        $this->agent = $agent;
    }

	// This is loaded via AJAX at file Views\index.blade.php
    public function data($ticketList = 'active')
    {
        if (LaravelVersion::min('5.4')) {
            $datatables = app(\Yajra\DataTables\DataTables::class);
        } else {
            $datatables = app(\Yajra\Datatables\Datatables::class);
        }

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
                    ->where('ticketit_taggables.taggable_type', '=', 'PanicHD\\PanicHD\\Models\\Ticket');
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
			\DB::raw('concat(if(ticketit.limit_date, ticketit.limit_date, \'9999\'), ticketit.start_date) as calendar_order'),
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
			$a_select[] = \DB::raw('group_concat(distinct(ticketit_departments.sub1)) AS dept_sub1');
			$a_select[] = \DB::raw('concat_ws(\' \', group_concat(distinct(ticketit_departments.department)), group_concat(distinct(ticketit_departments.sub1))) as dept_full');
		}
		
		$collection
            ->groupBy('ticketit.id')
            ->select($a_select)
			->with('creator')
			->with('owner.personDepts.department')
			->withCount('allAttachments')
			->withCount('comments')
			->withCount('recentComments');

        $collection = $datatables->of($collection);

		\Carbon\Carbon::setLocale(config('app.locale'));
		
        $this->renderTicketTable($collection);

        // method rawColumns was introduced in laravel-datatables 7, which is only compatible with >L5.4
        // in previous laravel-datatables versions escaping columns wasn't defaut
        if (LaravelVersion::min('5.4')) {
            $collection->rawColumns(['subject', 'status', 'priority', 'category', 'agent']);
        }

        return $collection->make(true);		
    }

    public function renderTicketTable($collection)
    {

		// Column edits		
        $collection->editColumn('subject', function ($ticket) {
            return (string) link_to_route(
                Setting::grab('main_route').'.show',
                $ticket->subject,
                $ticket->id
            );
        });
		
		$collection->editColumn('content', function ($ticket) {
			$field=$ticket->content;
			if ($ticket->all_attachments_count>0) $field.= "<br />" . $ticket->all_attachments_count . ' <span class="glyphicons glyphicon glyphicon-paperclip tooltip-info" title="'.trans('ticketit::lang.table-info-attachments-total', ['num' => $ticket->all_attachments_count]).'"></span>';
						
			return $field;
		});
		
		$collection->editColumn('intervention', function ($ticket) {
			$field=$ticket->intervention;
			if ($ticket->intervention!="" and $ticket->comments_count>0) $field.="<br />";
			if ($ticket->recent_comments_count>0){
				$field.=$ticket->recent_comments_count;
			}
			if ($ticket->comments_count>0){
				$field.=' <span class="glyphicons glyphicon glyphicon-comment tooltip-info" title="'.trans('ticketit::lang.table-info-comments-total', ['num'=>$ticket->comments_count]).($ticket->recent_comments_count>0 ? ' '.trans('ticketit::lang.table-info-comments-recent', ['num'=>$ticket->recent_comments_count]) : '').'"></span>';
			}
			
			return $field;
		});

        $collection->editColumn('status', function ($ticket) {
            $color = $ticket->color_status;
            $status = e($ticket->status);

            return "<div style='color: $color'>$status</div>";
        });
		
		$collection->editColumn('updated_at', function ($ticket){
			return '<span class="tooltip-info" data-toggle="tooltip" title="'.Carbon::createFromFormat("Y-m-d H:i:s", $ticket->updated_at)->diffForHumans().'">'.$ticket->getUpdatedAbbr().'</span>';
		});

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
			$html.='<br /><button type="button" class="submit_agent_popover" data-ticket-id="%1$s">'.trans('ticketit::lang.btn-change').'</button></div>';
			$a_cat[$cat['id']]['html']=$html;
		}
		
		// Agent column with $a_cat[]
		$collection->editColumn('agent', function ($ticket) use($a_cat) {
            $ticket = $this->tickets->find($ticket->id);
			$count = $a_cat[$ticket->category_id]['agents_count'];			
			
            $text = '<a href="#" class="'.($count>4 ? 'jquery_agent_change_modal' : ($count == 1 ? 'tooltip-info' : 'jquery_popover')).'" ';
			
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
		
        $collection->editColumn('priority', function ($ticket) {
            $a_priorities = Models\Priority::all();
			$html = "";
			foreach ($a_priorities as $priority){
				$html.= '<label style="color: '.$priority->color.'"><input type="radio" name="'.$ticket->id.'_priority" value="'.$priority->id.'"> '.$priority->name.'</label><br />';
			}
			
			$html = '<div>'.$html.'</div><br />'
				.'<button type="button" class="submit_priority_popover" data-ticket-id="'.$ticket->id.'">'.trans('ticketit::lang.btn-change').'</button>';

            return '<a href="#Priority" style="color: '.$ticket->color_priority.'" class="jquery_popover" data-toggle="popover" data-placement="auto bottom" title="'.trans('ticketit::lang.table-change-priority').'" data-content="'.e($html).'">'.e($ticket->priority).'</a>';
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
			return $ticket->getCalendarField();
        });

        $collection->editColumn('category', function ($ticket) {
            $color = $ticket->color_category;
            $category = e($ticket->category);

            return "<div style='color: $color'>$category</div>";
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
		
		$data = [
			'ticketList'=>$ticketList,			
			'a_cat_agents'=>$a_cat_agents			
		];
		$data = array_merge ($data, $this->ticketCounts($request, $ticketList));
		
		return view('ticketit::tickets.index', $data);
	}

    /**
     * Calculates Tickets counts to show.
     *
     * @return
     */
    public function ticketCounts($request, $ticketList)
    {
        $counts = $filters = [];
		$tickets;
        $category = session('ticketit_filter_category') == '' ? null : session('ticketit_filter_category');
		
		if ($this->agent->isAdmin() or $this->agent->isAgent()){
			// Get all forth tickets
			$forth_tickets = Ticket::whereBetween('limit_date', [
				Carbon::now()->today(),
				Carbon::now()->endOfWeek()
			])
			->orWhereBetween('limit_date', [
				Carbon::now()->today(),
				Carbon::now()->endOfMonth()
			]);			
			$forth_tickets = $forth_tickets->inList($ticketList)->visible()->get();			
			
			// Calendar expired filter
			$a_cal['expired'] = Ticket::inList($ticketList)->visible()->where('limit_date','<', Carbon::now());
			
			// Calendar forth filters
			$a_cal['today'] = $forth_tickets->filter(function($q){
				return $q->limit_date < Carbon::now()->tomorrow(); 
			});
			
			$a_cal['tomorrow'] = $forth_tickets->filter(function($q){
				return $q->limit_date >= Carbon::now()->tomorrow(); 
			})
			->filter(function($q2){
				return $q2->limit_date < Carbon::now()->addDays(2)->startOfDay(); 
			});
			
			$a_cal['week'] = $forth_tickets->filter(function($q){
				return $q->limit_date < Carbon::now()->endOfWeek();
			});
			
			$a_cal['month'] = $forth_tickets->filter(function($q){
				return $q->limit_date < Carbon::now()->endOfMonth();
			});
			
			// Calendar counts
			foreach ($a_cal as $cal=>$cal_tickets){
				$counts['calendar'][$cal] = $cal_tickets->count();
			}
			
			// Calendar filter to tickets collection
			if (session('ticketit_filter_calendar') != '') {
				$tickets = $a_cal[session('ticketit_filter_calendar')];
			}else{
				// Tickets collection
				$tickets = Ticket::inList($ticketList)->visible();
			}
			
			// Get ticket ids array
			if (version_compare(app()->version(), '5.3.0', '>=')) {				
				$a_tickets_id = $tickets->pluck('id')->toArray();
			} else { // if Laravel 5.1				
				$a_tickets_id = $tickets->lists('id')->toArray();
			}
		}		
		
        if ($this->agent->isAdmin() or ($this->agent->isAgent() and Setting::grab('agent_restrict') == 0)) {
            // Ticket filter for each Category
            if ($this->agent->isAdmin()) {
                $filters['category'] = Category::orderBy('name')->withCount(['tickets'=> function ($q) use ($a_tickets_id) {
					$q->whereIn('id',$a_tickets_id);
                }])->get();
            } else {
                $filters['category'] = Agent::where('id', auth()->user()->id)->firstOrFail()->categories()->orderBy('name')->withCount(['tickets'=> function ($q) use ($a_tickets_id) {
					$q->whereIn('id',$a_tickets_id);					
                }])->get();
            }

            // Ticket filter for each visible Agent
            if (session('ticketit_filter_category') != '') {
                $filters['agent'] = Agent::visible()->whereHas('categories', function ($q1) use ($category) {
                    $q1->where('id', $category);
                });
            } else {
                $filters['agent'] = Agent::visible();
            }

            $filters['agent'] = $filters['agent']->withCount(['agentTotalTickets'=> function ($q2) use ($a_tickets_id, $category) {
                $q2->whereIn('id',$a_tickets_id)->inCategory($category);
            }])->get();
        }

        // Forget agent if it doesn't exist in current category
        $agent = session('ticketit_filter_agent');
        if (isset($filters['agent']) and $filters['agent']->filter(function ($q) use ($agent) {
            return $q->id == $agent;
        })->count() == 0) {
            $request->session()->forget('ticketit_filter_agent');
        }

        return ['counts' => $counts, 'filters' => $filters];
    }

    /**
     * Returns priorities, categories and statuses lists in this order
     * Decouple it with list().
     *
     * @return array
     */
    protected function getCacheList($list)
    {
        $instance = false;
		
		switch ($list){
			case 'priorities':
				$instance = Cache::remember('ticketit::priorities', 60, function () {
					return Models\Priority::all();
				});
				break;
			case 'statuses':
				$instance = Cache::remember('ticketit::statuses', 60, function () {
					return Models\Status::all();
				});
				break;
			default:
				return false;
		}

        if (LaravelVersion::min('5.3.0')) {
            return $instance->pluck('name', 'id');
        } else {
            return $instance->lists('name', 'id');
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

		$data['categories'] = $this->agent->findOrFail(auth()->user()->id)->getNewTicketCategories();

        return view('ticketit::tickets.createedit', $data);
    }
	
	public function edit($id){
		$ticket = $this->tickets->findOrFail($id);
		
		$data = $this->create_edit_data($ticket);
		
		$data['ticket'] = $ticket;
		
		$data['ticket_owner_id'] = $data['ticket']->user_id;
		
		$data['categories'] = $this->agent->findOrFail(auth()->user()->id)->getEditTicketCategories();
		
        return view('ticketit::tickets.createedit', $data);
	}
	
	public function create_edit_data($ticket = false)
	{
		$user = $this->agent->find(auth()->user()->id);
		
		if (Setting::grab('departments_notices_feature')){
			// Get notices from related users
			$a_notices = Ticket::active()->whereIn('user_id', $user->getMyNoticesUsers())
				->with('owner.personDepts.department')
				->with('status')->with('tags')->get();
		}else{
			// Don't show notices
			$a_notices = [];
		}
		
		if ($user->currentLevel() > 1){
			$a_owners = Agent::with('userDepartment')->orderBy('name')->get();
		}else{
			$a_owners = Agent::whereNull('ticketit_department')->orWhere('id','=',$user->id)->with('userDepartment')->orderBy('name')->get();
		}
		
		$priorities = $this->getCacheList('priorities');
		$status_lists = $this->getCacheList('statuses');		

		$a_current = [];

		\Carbon\Carbon::setLocale(config('app.locale'));
		
		if (old('category_id')){
			// Form old values
			$a_current['complete'] = old('complete');
			
			$a_current['start_date'] = old ('start_date');
			$a_current['limit_date'] = old ('limit_date');
			
			$a_current['cat_id'] = old('category_id');
			$a_current['agent_id'] = old('agent_id');
			
		}elseif($ticket){
			// Edition values
			$a_current['complete'] = $ticket->isComplete() ? "yes" : "no";
			$a_current['status_id'] = $ticket->status_id;
			
			$a_current['start_date'] = $ticket->start_date;
			$a_current['limit_date'] = $ticket->limit_date;
			
			$a_current['cat_id'] = $ticket->category_id;
			$a_current['agent_id'] = $ticket->agent_id;			
		}else{
			// Defaults
			$a_current['complete'] = "no";
			
			$a_current['start_date'] = $a_current['limit_date'] = "";
			
			// Default category		
			$a_current['cat_id'] = @$user->tickets()->latest()->first()->category_id;

			if ($a_current['cat_id'] == null){
				$a_current['cat_id'] = $user->getNewTicketCategories()->keys()->first();
			}
			
			// Default agent
			$a_current['agent_id'] = $user->id;			
		}
		
		// Agent list
		$agent_lists = $this->agentList($a_current['cat_id']);
				
		// Permission level for category
		$permission_level = $user->levelInCategory($a_current['cat_id']);
		
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
		
		return compact('a_notices', 'a_owners', 'priorities', 'status_lists', 'categories', 'agent_lists', 'a_current', 'permission_level', 'tag_lists', 'a_tags_selected');
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
		$category_level = $user->levelInCategory($request->category_id);
		$permission_level = ($user->currentLevel() > 1 and $category_level > 1) ? $category_level : 1;
		
		$a_content = $this->purifyHtml($request->get('content'));
        $request->merge([
            'subject'=> trim($request->get('subject')),
            'content'=> $a_content['content'],
			'content_html'=> $a_content['html']
        ]);

		$allowed_categories = implode(",", $user->getNewTicketCategories()->keys()->toArray());
		
		$fields = [
            'subject'     => 'required|min:3',
			'owner_id'    => 'required|exists:users,id',
			'category_id' => 'required|in:'.$allowed_categories,
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
			
			if ($request->exists('attachments')){
				$fields = ['attachments' => 'array'];
			}
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
		
		DB::beginTransaction();
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
		
		if ($permission_level == 1 or $request->input('agent_id') == 'auto') {
			$ticket->autoSelectAgent();
		} else {
			$ticket->agent_id = $request->input('agent_id');
		}
		
        $ticket->content = $a_content['content'];
        $ticket->html = $a_content['html'];

        if ($permission_level > 1) {
            $ticket->intervention = $a_intervention['intervention'];
			$ticket->intervention_html = $a_intervention['intervention_html'];
		}
		
        $ticket->save();
		
		if (Setting::grab('ticket_attachments_feature')){
			$attach_error = $this->saveAttachments($request, $ticket);
			if ($attach_error){
				return redirect()->back()->with('warning', $attach_error);
			}
		}
		
        
		// End transaction
		DB::commit();

        $this->sync_ticket_tags($request, $ticket);

        session()->flash('status', trans('ticketit::lang.the-ticket-has-been-created', [
			'name' => '#'.$ticket->id.' '.$ticket->subject,
			'link' => route(Setting::grab('main_route').'.show', $ticket->id),
			'title' => trans('ticketit::lang.ticket-status-link-title')
		]));

        return redirect()->action('\PanicHD\PanicHD\Controllers\TicketsController@index');
    }

    public function downloadAttachment($attachment_id)
    {
        /** @var Attachment $attachment */
        $attachment = Attachment::findOrFail($attachment_id);

        return response()
            ->download($attachment->file_path, $attachment->new_filename);
    }
	
	public function viewAttachment($attachment_id)
    {
        /** @var Attachment $attachment */
        $attachment = Attachment::findOrFail($attachment_id);
		
		$mime = $attachment->getShorthandMime($attachment->mimetype);
		
		if ( $mime == "image"){
			$img = Image::make($attachment->file_path);
			return $img->response();
		}elseif($mime == "pdf"){
			return response()->file($attachment->file_path);
		}else{
			return response()
				->download($attachment->file_path, basename($attachment->file_path));
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
		
		$ticket = $ticket->findOrFail($id);
		
        if (version_compare(app()->version(), '5.3.0', '>=')) {
            $a_reasons = $ticket->category->closingReasons()->pluck('text','id')->toArray();
			$a_tags_selected = $ticket->tags()->pluck('id')->toArray();
        } else { // if Laravel 5.1
            $a_reasons = $ticket->category->closingReasons()->lists('text','id')->toArray();
			$a_tags_selected = $ticket->tags()->lists('id')->toArray();
        }
		
		$status_lists = $this->getCacheList('statuses');

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
		
		if ($user->levelInCategory($ticket->category_id) > 1){
			$comments = $ticket->comments();
		}else{
			$comments = $ticket->comments()->where('type','!=','note');
		}
        $comments = $comments->orderBy('created_at','desc')->paginate(Setting::grab('paginate_items'));

        return view('ticketit::tickets.show',
            compact('ticket', 'a_reasons', 'a_tags_selected', 'status_lists', 'agent_lists', 'tag_lists',
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
		$user = $this->agent->find(auth()->user()->id);
		
		$a_content = $this->purifyHtml($request->get('content'));
        $request->merge([
            'subject'=> trim($request->get('subject')),
            'content'=> $a_content['content'],
        ]);
		
		$allowed_categories = implode(",", $user->getEditTicketCategories()->keys()->toArray());
		
		$fields = [
            'subject'     => 'required|min:3',
			'owner_id'    => 'exists:users,id',
            'priority_id' => 'required|exists:ticketit_priorities,id',
            'category_id' => 'required|in:'.$allowed_categories,
            'status_id'   => 'required|exists:ticketit_statuses,id',
            'agent_id'    => 'required',
			'content'     => 'required|min:6',
        ];
		
		if ($request->has('attachments')){
			$fields = ['attachments' => 'array'];
		}

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


		DB::beginTransaction();
			
        $ticket = $this->tickets->findOrFail($id);

        $ticket->subject = $request->subject;
		$ticket->user_id = $request->owner_id;
        $ticket->content = $a_content['content'];
        $ticket->html = $a_content['html'];

		$user = $this->agent->find(auth()->user()->id);
        if ($user->isAgent() or $user->isAdmin()) {
            $ticket->intervention = $a_intervention['intervention'];
			$ticket->intervention_html = $a_intervention['intervention_html'];
		}

		if ($request->complete=='yes'){
			$ticket->completed_at = Carbon::now();
		}else{
			$ticket->completed_at = null;
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

		if (Setting::grab('ticket_attachments_feature')){
			$attachment_errors = false;
			
			// 1 - destroy checked attachments
			if ($request->has('delete_files')) $attachment_errors = $this->destroyAttachmentIds($request->delete_files);
			
			// 2 - update existing attachment fields
			if (!$attachment_errors) $attachment_errors = $this->updateAttachments($request, $ticket->attachments()->get());
			
			// 3 - add new attachments
			if (!$attachment_errors) $attachment_errors = $this->saveAttachments($request, $ticket);
			
			if ($attachment_errors){
				return redirect()->back()->with('warning', $attachment_errors);
			}
		}		
        
		// End transaction
		DB::commit();		

        $this->sync_ticket_tags($request, $ticket);

        session()->flash('status', trans('ticketit::lang.the-ticket-has-been-modified', ['name' => '#'.$ticket->id.' "'.$ticket->subject.'"']));

        return redirect()->route(Setting::grab('main_route').'.show', $id);
    }

    /**
     * Syncs tags for ticket instance.
     *
     * @param $ticket instance of PanicHD\PanicHD\Models\Ticket
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
		
		if (Setting::grab('ticket_attachments_feature')){
			$attach_error = $this->destroyAttachmentsFrom($ticket);
			if ($attach_error){
				return redirect()->back()->with('warning', $attach_error);
			}
		}
		
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
			$date = date(trans('ticketit::lang.date-format'), time());
			$ticket->intervention = $ticket->intervention . ' ' . $date . ' ' . $reason_text;
			$ticket->intervention_html = $ticket->intervention_html . '<br />' . $date . ' ' . $reason_text;
			
			if ($user->currentLevel()<2){
				// Check clarification text
				$a_clarification = $this->purifyHtml($request->get('clarification'));
				if ($a_clarification['content'] != ""){
					$ticket->intervention = $ticket->intervention . ' ' . trans('ticketit::lang.closing-clarifications') . trans('ticketit::lang.colon') . $a_clarification['content'];
					$ticket->intervention_html = $ticket->intervention_html . '<br />' . trans('ticketit::lang.closing-clarifications') . trans('ticketit::lang.colon') . $a_clarification['html'];
				}
			}
			
			$ticket->completed_at = Carbon::now();

            $ticket->save();
			
			// Add closing comment			
			$comment = new Models\Comment;
			$comment->type = "complete";
			
			if ($user->currentLevel()>1){ 
				$comment->content = $comment->html = trans('ticketit::lang.comment-complete-title');
			}else{
				$comment->content = $comment->html = trans('ticketit::lang.comment-complete-title') . ($reason ? trans('ticketit::lang.colon').$reason->text : '');
							
				if ($a_clarification['content'] != ""){
					$comment->content = $comment->content . ' ' . trans('ticketit::lang.closing-clarifications') . trans('ticketit::lang.colon') . $a_clarification['content'];
					$comment->html = $comment->html . '<br />' . trans('ticketit::lang.closing-clarifications') . trans('ticketit::lang.colon') . $a_clarification['html'];
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
			
			$date = date(trans('ticketit::lang.date-format'), time());
			$ticket->intervention = $ticket->intervention . ' ' . $date . ' ' . trans('ticketit::lang.reopened-by-user', ['user' => $user->name]);
			$ticket->intervention_html = $ticket->intervention_html . '<br />' . $date . ' ' . trans('ticketit::lang.reopened-by-user', ['user' => $user->name]);					
			

            $ticket->save();
			
			// Add reopen comment
			$comment = new Models\Comment;
			$comment->type = "reopen";
			$comment->content = $comment->html = trans('ticketit::lang.comment-reopen-title');
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
		$old_agent = $ticket->agent()->first();
		$new_agent = Agent::findOrFail($request->input('agent_id'));
		
		if ($ticket->agent_id==$request->input('agent_id')){
			return redirect()->back()->with('warning', trans('ticketit::lang.update-agent-same', [
				'name' => '#'.$ticket->id.' '.$ticket->subject,
				'link' => route(Setting::grab('main_route').'.show', $ticket->id),
				'title' => trans('ticketit::lang.ticket-status-link-title')
			]));
		}else{
			$ticket->agent_id = $request->input('agent_id');
			
			if ($ticket->status_id==Setting::grab('default_status_id')){
				$ticket->status_id=Setting::grab('default_reopen_status_id');
			}
			$ticket->save();
			
			session()->flash('status', trans('ticketit::lang.update-agent-ok', [
				'name' => '#'.$ticket->id.' '.$ticket->subject,
				'link' => route(Setting::grab('main_route').'.show', $ticket->id),
				'title' => trans('ticketit::lang.ticket-status-link-title'),
				'old_agent' => $old_agent->name,
				'new_agent' => $new_agent->name
			]));
			
			return redirect()->route(Setting::grab('main_route').'.index');
		}		
	}

	/*
	 * Change priority in ticket list
	*/
	public function changePriority(Request $request){
		$ticket = Ticket::findOrFail($request->input('ticket_id'));
		$old_priority = $ticket->priority()->first();		
		$new_priority = Models\Priority::findOrFail($request->input('priority_id'));
		
		if ($ticket->priority_id==$request->input('priority_id')){
			return redirect()->back()->with('warning', trans('ticketit::lang.update-priority-same', [
				'name' => '#'.$ticket->id.' '.$ticket->subject,
				'link' => route(Setting::grab('main_route').'.show', $ticket->id),
				'title' => trans('ticketit::lang.ticket-status-link-title')
			]));
		}else{
			$ticket->priority_id = $request->input('priority_id');
			$ticket->save();
			
			session()->flash('status', trans('ticketit::lang.update-priority-ok', [
				'name' => '#'.$ticket->id.' '.$ticket->subject,
				'link' => route(Setting::grab('main_route').'.show', $ticket->id),
				'title' => trans('ticketit::lang.ticket-status-link-title'),
				'old' => $old_priority->name,
				'new' => $new_priority->name
			]));
			
			return redirect()->route(Setting::grab('main_route').'.index');
		}		
	}

	/**
	 * Return integer user level for specified category (false = no permission, 1 = user, 2 = agent, 3 = admin)
	*/
	public function permissionLevel ($category_id)
	{
		$user = $this->agent->find(auth()->user()->id);
		
		return $user->levelInCategory($category_id);
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
