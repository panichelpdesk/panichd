<?php

namespace PanicHD\PanicHD\Controllers;

use App\Http\Controllers\Controller;
use Cache;
use Carbon\Carbon;
use DB;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use PanicHD\PanicHD\Helpers\LaravelVersion;
use Intervention\Image\ImageManagerStatic as Image;
use PanicHD\PanicHD\Events\CommentCreated;
use PanicHD\PanicHD\Events\TicketCreated;
use PanicHD\PanicHD\Events\TicketUpdated;
use PanicHD\PanicHD\Models;
use PanicHD\PanicHD\Models\Attachment;
use PanicHD\PanicHD\Models\Category;
use PanicHD\PanicHD\Models\Department;
use PanicHD\PanicHD\Models\Member;
use PanicHD\PanicHD\Models\Setting;
use PanicHD\PanicHD\Models\Tag;
use PanicHD\PanicHD\Models\Ticket;
use PanicHD\PanicHD\Traits\Attachments;
use PanicHD\PanicHD\Traits\CacheVars;
use PanicHD\PanicHD\Traits\Purifiable;
use PanicHD\PanicHD\Traits\TicketFilters;

class TicketsController extends Controller
{
    use Attachments, CacheVars, Purifiable, TicketFilters;

    protected $tickets;
	protected $member;
	protected $a_search_fields_numeric = ['creator_id', 'user_id', 'status_id', 'priority_id', 'category_id', 'agent_id'];
	protected $a_search_fields_text = ['subject', 'content', 'intervention'];
	protected $a_search_fields_text_special = ['comments', 'attachment_name', 'any_text_field'];
	protected $a_search_fields_date = ['start_date', 'limit_date', 'created_at', 'completed_at', 'updated_at'];

    public function __construct(Ticket $tickets, \PanicHDMember $member)
    {
        $this->middleware('PanicHD\PanicHD\Middleware\EnvironmentReadyMiddleware', ['only' => ['create']]);
		$this->middleware('PanicHD\PanicHD\Middleware\UserAccessMiddleware', ['only' => ['show', 'downloadAttachment', 'viewAttachment']]);
        $this->middleware('PanicHD\PanicHD\Middleware\AgentAccessMiddleware', ['only' => ['edit', 'update', 'changeAgent', 'changePriority', 'hide']]);
		$this->middleware('PanicHD\PanicHD\Middleware\IsAdminMiddleware', ['only' => ['destroy']]);
		$this->middleware('PanicHD\PanicHD\Middleware\IsAgentMiddleware', ['only' => ['search_form', 'register_search_fields']]);

        $this->tickets = $tickets;
        $this->member = $member;
    }

	// This is loaded via AJAX at file Views\index.blade.php
    public function data($ticketList = 'active')
    {
		$members_table = (new \PanicHDMember)->getTable();

		$datatables = app(\Yajra\Datatables\Datatables::class);

		$agent = $this->member->find(auth()->user()->id);
		
		$collection = Ticket::visible();

		if ($ticketList == 'search'){
			if(!session()->has('search_fields')){
				// Load an empty table
				$collection->where('panichd_tickets.id', '0');

			}else{
				// Filter by all specified fields
				$search_fields = session()->get('search_fields');
				foreach ($search_fields as $field => $value){
					if ($field == 'department_id'){
						$collection->whereHas('owner', function($query)use($search_fields){
							$query->whereHas('department', function($q1)use($search_fields){
								$q1->where('id', $search_fields['department_id'])
									->orWhere('department_id', $search_fields['department_id']);
							});
						});

					}elseif ($field == 'list'){
						$collection->inList($value);

					}elseif(in_array($field, $this->a_search_fields_date)){
						if (in_array($search_fields[$field . '_type'], ['exact_year', 'exact_month'])){
							$collection->whereYear('panichd_tickets.' . $field, Carbon::createFromFormat(trans('panichd::lang.datetime-format'), $value)->format('Y'));

							if ($search_fields[$field . '_type'] == 'exact_month'){
								$collection->whereMonth('panichd_tickets.' . $field, Carbon::createFromFormat(trans('panichd::lang.datetime-format'), $value)->format('m'));
							}
						
						}elseif ($search_fields[$field . '_type'] == 'exact_day'){
							$collection->whereDate('panichd_tickets.' . $field, Carbon::createFromFormat(trans('panichd::lang.datetime-format'), $value)->toDateString());
						
						}else{
							$collection->where('panichd_tickets.' . $field, ($search_fields[$field . '_type'] == 'from' ? '>=' : '<'), Carbon::createFromFormat(trans('panichd::lang.datetime-format'), $value)->toDateTimeString());
						}

					}elseif(in_array($field, $this->a_search_fields_text)){
						if ($field == 'content'){
							$collection->where(function($query) use($search_fields, $value){
								$query->where('content', 'like', '%' . $value . '%')
									->orWhere('html', 'like', '%' . $value . '%');
							});
						
						}elseif ($field == 'intervention'){
							$collection->where(function($query) use($search_fields, $value){
								$query->where('intervention', 'like', '%' . $value . '%')
									->orWhere('intervention_html', 'like', '%' . $value . '%');
							});
						
						}else{
							$collection->where($field, 'like', '%' . $value . '%');
						}
					
					}elseif(in_array($field, $this->a_search_fields_numeric)){
						$collection->where($field, $value);
					}
				}

				if (isset($search_fields['tags'])){
					foreach ($search_fields['tags'] as $tag_id){
						$collection->whereHas('tags', function($q1) use($tag_id){
							$q1->where('id', $tag_id);
						});
					}
				}

				if (isset($search_fields['comments'])){
					$collection->whereHas('comments', function($q1) use($search_fields){
						$q1->where('content', 'like', '%' . $search_fields['comments'] . '%')
							->orWhere('html', 'like', '%' . $search_fields['comments'] . '%');
					});
				}

				if (isset($search_fields['attachment_name'])){
					$collection->where(function($query) use($search_fields){
						$query->whereHas('attachments', function($q1) use($search_fields){
							$q1->where('original_filename', 'like', '%' . $search_fields['attachment_name'] . '%')
								->orWhere('new_filename', 'like', '%' . $search_fields['attachment_name'] . '%')
								->orWhere('description', 'like', '%' . $search_fields['attachment_name'] . '%');
							})
							->orWhereHas('comments', function($q2) use($search_fields) {
								$q2->whereHas('attachments', function($q3) use($search_fields){
									$q3->where('original_filename', 'like', '%' . $search_fields['attachment_name'] . '%')
										->orWhere('new_filename', 'like', '%' . $search_fields['attachment_name'] . '%')
										->orWhere('description', 'like', '%' . $search_fields['attachment_name'] . '%');
								});
							});
					});
				}

				if (isset($search_fields['any_text_field'])){
					$collection->where(function($query) use($search_fields){
						$query->where('subject', 'like', '%' . $search_fields['any_text_field'] . '%')
							->orWhere('content', 'like', '%' . $search_fields['any_text_field'] . '%')
							->orWhere('html', 'like', '%' . $search_fields['any_text_field'] . '%')
							->orWhere('intervention', 'like', '%' . $search_fields['any_text_field'] . '%')
							->orWhere('intervention_html', 'like', '%' . $search_fields['any_text_field'] . '%')
							->orWhereHas('attachments', function($q1) use($search_fields){
								$q1->where('original_filename', 'like', '%' . $search_fields['any_text_field'] . '%')
									->orWhere('new_filename', 'like', '%' . $search_fields['any_text_field'] . '%')
									->orWhere('description', 'like', '%' . $search_fields['any_text_field'] . '%');
							})
							->orWhereHas('comments', function($q2) use($search_fields) {
								$q2->where('content', 'like', '%' . $search_fields['any_text_field'] . '%')
									->orWhere('html', 'like', '%' . $search_fields['any_text_field'] . '%')
									->orWhereHas('attachments', function($q3) use($search_fields){
									$q3->where('original_filename', 'like', '%' . $search_fields['any_text_field'] . '%')
										->orWhere('new_filename', 'like', '%' . $search_fields['any_text_field'] . '%')
										->orWhere('description', 'like', '%' . $search_fields['any_text_field'] . '%');
								});
							});
					});
				}
			}

		}else{
			$collection->inList($ticketList)->filtered($ticketList);
		}

        $collection
            ->leftJoin('users', function ($join1){
				$join1->on('users.id', '=', 'panichd_tickets.user_id');
			})
			->leftJoin($members_table . ' as members', function ($join2) {
				$join2->on('members.id', '=', 'panichd_tickets.user_id');
			})
			->leftJoin($members_table . ' as creator', function ($join3){
				$join3->on('creator.id', '=', 'panichd_tickets.creator_id');
			})
			->join('panichd_statuses', 'panichd_statuses.id', '=', 'panichd_tickets.status_id')
            ->leftJoin($members_table . ' as agent', function ($join4){
				$join4->on('agent.id', '=', 'panichd_tickets.agent_id');
			})
			->join('panichd_priorities', 'panichd_priorities.id', '=', 'panichd_tickets.priority_id')
            ->join('panichd_categories', 'panichd_categories.id', '=', 'panichd_tickets.category_id')


			// Tags joins
			->leftJoin('panichd_taggables', function ($join5) {
                $join5->on('panichd_tickets.id', '=', 'panichd_taggables.taggable_id')
                    ->where('panichd_taggables.taggable_type', '=', 'PanicHD\\PanicHD\\Models\\Ticket');
            })
            ->leftJoin('panichd_tags', 'panichd_taggables.tag_id', '=', 'panichd_tags.id');

		if (config('database.default')=='sqlite'){
			$int_start_date = 'cast(julianday(panichd_tickets.start_date) as real)';
			$int_limit_date = 'cast(julianday(panichd_tickets.limit_date) as real)';
		}else{
			$int_start_date = "CONVERT(date_format(panichd_tickets.start_date, '%Y%m%d%h%i%s'), SIGNED INTEGER)";
			$int_limit_date = "CONVERT(date_format(panichd_tickets.limit_date, '%Y%m%d%h%i%s'), SIGNED INTEGER)";
		}

		$a_select = [
			'panichd_tickets.id',
			'panichd_tickets.created_at',
			'panichd_tickets.subject AS subject',
			'panichd_tickets.hidden as hidden',
			'panichd_tickets.content AS content',
			'panichd_tickets.intervention AS intervention',
			'panichd_tickets.status_id as status_id',
			'panichd_statuses.name AS status',
			'panichd_statuses.color AS color_status',
			'panichd_priorities.color AS color_priority',
			'panichd_categories.color AS color_category',
			'panichd_tickets.start_date as start_date',
			\DB::raw(' 0-'.$int_start_date.' as inverse_start_date'),
			\DB::raw('CASE panichd_tickets.limit_date WHEN NULL THEN 0 ELSE 1 END as has_limit'),
			'panichd_tickets.limit_date as limit_date',
			\DB::raw(' 0-'.$int_limit_date.' as inverse_limit_date'),
			'panichd_tickets.limit_date as calendar',
			'panichd_tickets.updated_at AS updated_at',
			'panichd_tickets.completed_at AS completed_at',
			'panichd_tickets.agent_id',
			'agent.name as agent_name',
			'panichd_priorities.name AS priority',
			'panichd_priorities.magnitude AS priority_magnitude',
			'members.name AS owner_name',
			'creator.name as creator_name',
			'panichd_tickets.user_id',
			'panichd_tickets.creator_id',
			'panichd_categories.id as category_id',
			'panichd_categories.name AS category',

			// Tag Columns
			\DB::raw('group_concat(panichd_tags.id) AS tags_id'),
			\DB::raw('group_concat(panichd_tags.name) AS tags'),
			\DB::raw('group_concat(panichd_tags.bg_color) AS tags_bg_color'),
			\DB::raw('group_concat(panichd_tags.text_color) AS tags_text_color'),
		];

		if (Setting::grab('departments_feature')){
			$collection->leftJoin('panichd_departments', 'panichd_departments.id', '=', 'members.department_id')
				->leftJoin('panichd_departments as dep_ancestor', 'panichd_departments.department_id', '=', 'dep_ancestor.id');

			// Department columns
			$a_select[] = \DB::raw('CASE panichd_departments.department_id WHEN NULL THEN "" ELSE dep_ancestor.name END as dep_ancestor_name');

			if (config('database.default')=='sqlite'){
				$a_select[] = \DB::raw('dep_ancestor.name + panichd_departments.name as dept_full_name'); #\''.trans('panichd::lang.colon').' +
			}else{
				$a_select[] = \DB::raw('concat_ws(\'' . trans('panichd::lang.colon') . ' \', dep_ancestor.name, panichd_departments.name) as dept_full_name');
			}
		}else{
			$a_select[] = '"" as dep_ancestor_name';
		}

		$currentLevel = $agent->currentLevel();

		$collection
			->groupBy('panichd_tickets.id')
            ->select($a_select)
			->with('creator')
			->with('agent')
			->with('owner.department.ancestor')
			->withCount('allAttachments')
			->withCount(['comments' => function($query) use($currentLevel){
				$query->countable()->forLevel($currentLevel)->where('type', '!=', 'note');
			}])
			->withCount(['recentComments' => function($query) use($currentLevel){
				$query->countable()->forLevel($currentLevel)->where('type', '!=', 'note');
			}])
            ->withCount('internalNotes');

        $collection = $datatables->of($collection);

		\Carbon\Carbon::setLocale(config('app.locale'));

        $this->renderTicketTable($collection, $ticketList);

        // method rawColumns was introduced in laravel-datatables 7, which is only compatible with >L5.4
        // in previous laravel-datatables versions escaping columns wasn't defaut
        if (LaravelVersion::min('5.4')) {
            $a_raws = ['id', 'subject', 'intervention', 'status', 'agent', 'priority', 'owner_name', 'calendar', 'updated_at', 'complete_date', 'category', 'tags'];

			if (Setting::grab('departments_feature')){
				$a_raws[]= 'dept_full_name';
			}

			$collection->rawColumns($a_raws);
        }

        return $collection->make(true);
    }

    public function renderTicketTable($collection, $ticketList)
    {

		// Column edits
        $collection->editColumn('id', function ($ticket) {
			return '<div class="tooltip-wrap-15">'
				.'<div class="tooltip-info" data-toggle="tooltip" title="'
				.trans('panichd::lang.creation-date', ['date' => Carbon::parse($ticket->created_at)->format(trans('panichd::lang.datetime-format'))])
				.'">'.$ticket->id
				.'</div></div>';
		});

		$collection->editColumn('subject', function ($ticket) {
            $field=(string) link_to_route(
					Setting::grab('main_route').'.show',
					$ticket->subject,
					$ticket->id
				);

			if (Setting::grab('subject_content_column') == 'no'){
				return $field;
			}else{
				$field = '<div style="margin: 0em 0em 1em 0em;">'.$field.'</div>';

				if (Setting::grab('list_text_max_length') != 0 and strlen($ticket->content) > (Setting::grab('list_text_max_length')+30)){
                    $field.= '<div class="ticket_text jquery_ticket_' . $ticket->id . '_text" data-height-plus="" data-height-minus="">'
                        .'<span class="text_minus">' . mb_substr($ticket->content, 0, Setting::grab('list_text_max_length')) . '...</span>'
                        .'<span class="text_plus" style="display: none">' . $ticket->content . '</span>'
                        .' <button class="btn btn-light btn-xs jquery_ticket_text_toggle" data-id="' . $ticket->id . '"><span class="fa fa-plus"></span></button></div>';

                }else{
                    $field.= $ticket->content;
                }

				if ($ticket->all_attachments_count>0){
                    $field.= "<br />" . $ticket->all_attachments_count . ' <span class="fa fa-paperclip tooltip-info attachment" title="'.trans('panichd::lang.table-info-attachments-total', ['num' => $ticket->all_attachments_count]).'"></span>';
                }
				return $field;
			}
        });

		if (Setting::grab('subject_content_column') == 'no'){
			$collection->editColumn('content', function ($ticket) {
                if (Setting::grab('list_text_max_length') != 0 and strlen($ticket->content) > (Setting::grab('list_text_max_length')+30)){
                    $field = '<div class="ticket_text jquery_ticket_' . $ticket->id . '_text" data-height-plus="" data-height-minus="">'
                        .'<span class="text_minus">' . mb_substr($ticket->content, 0, Setting::grab('list_text_max_length')) . '...</span>'
                        .'<span class="text_plus" style="display: none">' . $ticket->content . '</span>'
                        .' <button class="btn btn-light btn-xs jquery_ticket_text_toggle" data-id="' . $ticket->id . '"><span class="fa fa-plus"></span></button></div>';

                }else{
                    $field = $ticket->content;
                }
				if ($ticket->all_attachments_count>0) $field.= "<br />" . $ticket->all_attachments_count . ' <span class="fa fa-paperclip tooltip-info attachment" title="'.trans('panichd::lang.table-info-attachments-total', ['num' => $ticket->all_attachments_count]).'"></span>';

				return $field;
			});
		}

		$collection->editColumn('intervention', function ($ticket) {

            if (Setting::grab('list_text_max_length') != 0 and strlen($ticket->intervention) > (Setting::grab('list_text_max_length')+30)){
                $field = '<div class="ticket_text jquery_ticket_' . $ticket->id . '_text" data-height-plus="" data-height-minus="">'
                    .'<span class="text_minus">...' . mb_substr($ticket->intervention, (mb_strlen($ticket->intervention)-Setting::grab('list_text_max_length'))) . '</span>'
                    .'<span class="text_plus" style="display: none">' . $ticket->intervention . '</span>'
                    .' <button class="btn btn-light btn-xs jquery_ticket_text_toggle" data-id="' . $ticket->id . '"><span class="fa fa-plus"></span></button></div>';

            }else{
                $field = $ticket->intervention;
            }

			if ($ticket->intervention!="" and ($ticket->comments_count>0 or $ticket->hidden)) $field.="<br />";

			if($ticket->hidden) $field.= '<span class="fa fa-eye-slash tooltip-info tickethidden" data-toggle="tooltip" title="'.trans('panichd::lang.ticket-hidden').'" style="margin: 0em 0.5em 0em 0em;"></span>';

			if ($ticket->comments_count>0){
				$field.=$ticket->comments_count . ' <span class="fa fa-comments tooltip-info comment" title="'.trans('panichd::lang.table-info-comments-total', ['num'=>$ticket->comments_count]).($ticket->recent_comments_count>0 ? ' '.trans('panichd::lang.table-info-comments-recent', ['num'=>$ticket->recent_comments_count]) : '').'"></span>';
			}
			if ($this->member->currentLevel() >= 2 and $ticket->internal_notes_count > 0){
			    $field.= ' ' . $ticket->internal_notes_count . ' <span class="fa fa-pencil-alt tooltip-info comment" title="' . trans('panichd::lang.table-info-notes-total', ['num' => $ticket->internal_notes_count]) . '"></span>';
            }

			return $field;
		});

        $collection->editColumn('status', function ($ticket) {
            $color = $ticket->color_status;
            $status = e($ticket->status);

            return "<div style='color: $color'>$status</div>";
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

    $active_status_name = Setting::grab('default_reopen_status_id') == '0' ? Models\Status::first()->name : Models\Status::find(Setting::grab('default_reopen_status_id'))->name;

		$a_cat = [];
		foreach ($a_cat_pre as $cat){
			$a_cat[$cat['id']] = $cat;
			$html = '<div>';
			foreach ($cat['agents'] as $agent){
				$html.='<label><input type="radio" name="%1$s_agent" value="'.$agent['id'].'"> '.$agent['name'].'</label><br />';
			}
      if ($ticketList == 'newest' and Setting::grab('use_default_status_id')){
        $html.= '<br /><label><input type="checkbox" name="%1$s_status_checkbox"> ' . trans('panichd::lang.table-agent-status-check', ['status' => $active_status_name]) . '</label>';
      }
      $html.='<br /><button type="button" class="btn btn-default btn-sm submit_agent_popover" data-ticket-id="%1$s">'.trans('panichd::lang.btn-change').'</button></div>';
			$a_cat[$cat['id']]['html']=$html;
		}

		// Agent column with $a_cat[]
		$collection->editColumn('agent', function ($ticket) use($a_cat) {
			$count = $a_cat[$ticket->category_id]['agents_count'];
			$text = "";

			if ($ticket->agent_name == "" or is_null($ticket->agent)){
				$text.= "<span class=\"fa fa-exclamation-circle tooltip-info text-danger\"  data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"".trans('panichd::lang.deleted-member')."\"></span> ";
			}

			if($count>4){
				$text.= '<a href="#" class="jquery_agent_change_modal" title="'.trans('panichd::lang.table-change-agent').'"';
			}elseif($count==1){
				$text.= '<a href="#" class="tooltip-info" title="'.trans('panichd::lang.table-one-agent').'" data-toggle="tooltip" data-placement="bottom" ';
			}else{
				$text.= '<a href="#" class="jquery_popover" data-toggle="popover" data-placement="bottom" title="'
					.e('<button type="button" class="float-right" onclick="$(this).closest(\'.popover\').popover(\'hide\');">&times;</button> ')
					.trans('panichd::lang.agents').'" data-content="'.e(sprintf($a_cat[$ticket->category_id]['html'],$ticket->id)).'" data-tooltip-title="'.trans('panichd::lang.agents').'" ';
			}
			$text.= 'data-ticket-id="'.$ticket->id.'" data-category-id="'.$ticket->category_id.'" data-agent-id="'.$ticket->agent_id.'">'
				. ($ticket->agent_name == "" ? trans('panichd::lang.deleted-member') : (is_null($ticket->agent) ? $ticket->agent_name : $ticket->agent->name))
				. '</a>';

			return $text;
        });

		$a_priorities = Models\Priority::orderBy('magnitude', 'desc')->get();

        $collection->editColumn('priority', function ($ticket) use($a_priorities) {
			$html = "";
			foreach ($a_priorities as $priority){
				$html.= '<label style="color: '.$priority->color.'"><input type="radio" name="'.$ticket->id.'_priority" value="'.$priority->id.'"> '.$priority->name.'</label><br />';
			}

			$html = '<div>'.$html.'</div><br />'
				.'<button type="button" class="btn btn-default btn-sm submit_priority_popover" data-ticket-id="'.$ticket->id.'">'.trans('panichd::lang.btn-change').'</button>';

            return '<a href="#Priority" style="color: '.$ticket->color_priority.'" class="jquery_popover" data-toggle="popover" data-placement="bottom" title="'
				.e('<button type="button" class="float-right" onclick="$(this).closest(\'.popover\').popover(\'hide\');">&times;</button> ')
				.trans('panichd::lang.table-change-priority').'" data-content="'.e($html).'">'.e($ticket->priority).'</a>';
        });

		$collection->editColumn('owner_name', function ($ticket) {
			if ($ticket->owner_name == ""){
				$return = trans('panichd::lang.deleted-member');
			}else
				$return = str_replace (" ", "&nbsp;", $ticket->owner_name);

			if ($ticket->owner_name == "" or is_null($ticket->owner)){
				$return = "<span class=\"tooltip-info\" data-toggle=\"tooltip\" data-placement=\"bottom\" title=\"".trans('panichd::lang.deleted-member')."\">"
					."<span class=\"fa fa-exclamation-circle text-danger\"></span>"
					."&nbsp;" . $return . "</span>";
			}

			if ($ticket->owner_name != ""){
				if (Setting::grab('user_route') != 'disabled'){
					$return = '<a href="'.route(Setting::grab('user_route'), ['id' => $ticket->user_id]).'">'.$return.'</a>';
				}
			}

			if ($ticket->user_id != $ticket->creator_id){
				$return .="&nbsp;<span class=\"fa fa-user tooltip-info\" title=\"".trans('panichd::lang.show-ticket-creator').trans('panichd::lang.colon'). ($ticket->creator_name == "" ? trans('panichd::lang.deleted-member') : (is_null($ticket->creator) ? $ticket->creator_name : $ticket->creator->name)) ."\" data-toggle=\"tooltip\" data-placement=\"bottom\" style=\"color: #aaa;\"></span>";
			}

			return $return;
		});

		if (Setting::grab('departments_feature')){
			$collection->editColumn('dept_full_name', function ($ticket) {
				if (isset($ticket->owner->department->name)){
					return '<span class="tooltip-info" data-toggle="tooltip" title="' . $ticket->dept_full_name . '">' . ($ticket->dep_ancestor_name == "" ? ucwords(mb_strtolower($ticket->dept_full_name)) : $ticket->owner->department->ancestor->shortening . trans('panichd::lang.colon') . ucwords(mb_strtolower($ticket->owner->department->name))) . '</span>';
				}
			});
		}

		$collection->editColumn('calendar', function ($ticket) {
			return '<div style="width: 8em;">'.$ticket->getCalendarInfo().'</div>';
        });

		$collection->editColumn('updated_at', function ($ticket){
			return '<div class="tooltip-info" data-toggle="tooltip" title="'
				.trans('panichd::lang.updated-date', ['date' => Carbon::createFromFormat("Y-m-d H:i:s", $ticket->updated_at)->diffForHumans()])
				.'" style="width: 3em;">'.$ticket->getUpdatedAbbr().'</div>';
		});

		$collection->editColumn('complete_date', function ($ticket) {
			return '<div style="width: 8em;">'.$ticket->getDateForHumans('completed_at').'</div>';
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

		$this->validateFilters($request);

		$data = array_merge ($data, $this->ticketCounts($request, $ticketList));

		return view('panichd::tickets.index', $data);
	}

    /**
     * Calculates Tickets counts to show.
     *
     * @return
     */
    public function ticketCounts($request, $ticketList)
    {
		$counts = $filters = [];
		$tickets = Ticket::inList($ticketList)->visible();
        $category = session('panichd_filter_category') == '' ? null : session('panichd_filter_category');

		if ($this->member->isAdmin() or $this->member->isAgent()){
			if ($ticketList != 'complete'){
				// Calendar expired filter
				$expired = clone $tickets;
				$a_cal['expired'] = $expired->where('limit_date','<', Carbon::now());

				// Calendar all forth filters
				$month_collection = clone $tickets;
				$month_collection = $month_collection->whereBetween('limit_date', [
					Carbon::now()->today(),
					Carbon::now()->addDays(32)
				])->get();

				$a_cal['today'] = $month_collection->filter(function($q){
					return $q->limit_date < Carbon::now()->tomorrow();
				});

				$a_cal['tomorrow'] = $month_collection->filter(function($q){
					return $q->limit_date >= Carbon::now()->tomorrow();
				})
				->filter(function($q2){
					return $q2->limit_date < Carbon::now()->addDays(2)->startOfDay();
				});

				if (Setting::grab('calendar_month_filter')){
					// Calendar week
					$a_cal['week'] = $month_collection->filter(function($q){
						return $q->limit_date < Carbon::now()->endOfWeek();
					});

					// Calendar month
					$a_cal['month'] = $month_collection->filter(function($q){
						return $q->limit_date < Carbon::now()->endOfMonth();
					});
				}else{
					// From today to forth 7 days
					$a_cal['within-7-days'] = $month_collection->filter(function($q){
						return $q->limit_date < Carbon::now()->addDays(7)->endOfDay();
					});

					// From today to forth 14 days
					$a_cal['within-14-days'] = $month_collection->filter(function($q){
						return $q->limit_date < Carbon::now()->addDays(14)->endOfDay();
					});
				}

				// Tickets without limit_date
				$not_scheduled = clone $tickets;
				$a_cal['not-scheduled'] = $not_scheduled->whereNull('limit_date');

				// Builder with calendar filter
				$tickets->filtered($ticketList, 'calendar');

				// Calendar counts
				foreach ($a_cal as $cal=>$cal_tickets){
					$counts['calendar'][$cal] = $cal_tickets->count();
				}
			}else{
				$counts['years'] = $this->getCompleteTicketYearCounts();

				// Year filter to tickets collection
				if ($ticketList == 'complete'){
					$year = session('panichd_filter_year') != '' ? session('panichd_filter_year') : '';
					$tickets = $tickets->completedOnYear($year);
				}
			}
		}

        if ($this->member->isAdmin() or ($this->member->isAgent() and Models\Setting::grab('agent_restrict') == 0)) {

			// Visible categories
            $filters['category'] = Category::visible()->orderBy('name')->get();

			// Ticket counts for each Category
			$cat_tickets = clone $tickets;
			$category_counts = $cat_tickets->groupBy('category_id')->select('category_id', DB::raw('count(*) as num'))->get();
			if (version_compare(app()->version(), '5.3.0', '>=')) {
				$a_category_counts = $category_counts->pluck('num','category_id')->toArray();
			} else { // if Laravel 5.1
				$a_category_counts = $category_counts->lists('num','category_id')->toArray();
			}

			foreach ($filters['category'] as $cat){
				$counts['category'][$cat->id] = isset($a_category_counts[$cat->id]) ? $a_category_counts[$cat->id] : 0;
			}

			// Add Category filter to ticket builder
			if (session('panichd_filter_category') != '') {
				$tickets->where('category_id', session('panichd_filter_category'));
			}

			// Add Owner filter
			if (session('panichd_filter_owner') != '') {
				$tickets->where('user_id', session('panichd_filter_owner'));
				$counts['owner'] = $tickets->count();
			}

            // Visible Agents
            if (session('panichd_filter_category') == '') {
				$filters['agent'] = \PanicHDMember::visible()->get();
			}else{
                $filters['agent']= \PanicHDMember::visible()->whereHas('categories', function ($q1) use ($category) {
                    $q1->where('id', $category);
                })
				->get();
            }

			// Ticket counts for each Agent
			$ag_tickets = clone $tickets;
			$ag_counts = $ag_tickets->groupBy('agent_id')->select('agent_id', DB::raw('count(*) as num'))->get();
			if (version_compare(app()->version(), '5.3.0', '>=')) {
				$ag_counts = $ag_counts->pluck('num','agent_id')->toArray();
			} else { // if Laravel 5.1
				$ag_counts = $ag_counts->lists('num','agent_id')->toArray();
			}

			foreach ($filters['agent'] as $ag){
				$counts['agent'][$ag->id] = isset($ag_counts[$ag->id]) ? $ag_counts[$ag->id] : 0;
			}
        }

        // Forget agent if it doesn't exist in current category
        $agent = session('panichd_filter_agent');
        if (isset($filters['agent']) and $filters['agent']->filter(function ($q) use ($agent) {
            return $q->id == $agent;
        })->count() == 0) {
            $request->session()->forget('panichd_filter_agent');
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
				$instance = Cache::remember('panichd::priorities', \Carbon\Carbon::now()->addMinutes(60), function () {
					return Models\Priority::orderBy('magnitude', 'desc')->get();
				});
				break;
			case 'statuses':
            case 'complete_statuses':
				$instance = Cache::remember('panichd::' . $list, \Carbon\Carbon::now()->addMinutes(60), function () use($list) {
				    if (!Setting::grab('use_default_status_id') or $list == 'complete_statuses'){
                        return Models\Status::all()->where('id', '!=', Setting::grab('default_status_id'));
                    }else
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
     * Ticket search form
     *
     * @return Response
     */
    public function search_form($parameters = null)
    {
		// Forget last search
		session()->forget('search_fields');
		
		$data = $this->search_form_defaults();

		// If there are search parameters
		if (!is_null($parameters)){
			// URL allowed parameter arrays
			$a_numeric_params = array_merge($this->a_search_fields_numeric, ['department_id', 'list', 'tags']);
			$a_text_params = array_merge($this->a_search_fields_text, $this->a_search_fields_text_special);

			$a_temp = explode('/', $parameters);
			$a_parameters = [];

			if (count($a_temp) % 2 == 0){
				$key = "";
				foreach($a_temp as $param){
					if ($key == ""){
						$key = $param;
					
					}else{
						if (in_array($key, $a_numeric_params)){
							// Add param value pair into search_fields array
							$search_fields[$key] = $param;
						
						}elseif(in_array($key, $a_text_params)){
							// Decode strings in URL
							$search_fields[$key] = urldecode($param);
						}
						
						// key reset to look for a new parameter
						$key = "";
					}
				}
			}

			if (isset($search_fields['category_id']) and isset($search_fields['tags'])){
				// Add tag id's to the array
				$search_fields['tags'] = explode(',', $search_fields['tags']);
			}
		}

		if (isset($search_fields)){
			// Store search fields in session to use in datatable
			session(compact('search_fields'));

			// Pass search fields array to the view
			$data['search_fields'] = $search_fields;
		}

		$data['ticketList'] = 'search';

        return view('panichd::tickets.search', $data);
	}

	/**
     * Ticket search default data
     *
     * @return Array
     */
	public function search_form_defaults()
	{
		$member = $this->member->find(auth()->user()->id);

		$c_members = $this->members_collection($member);

		$c_status = \PanicHD\PanicHD\Models\Status::all();

		$priorities = $this->getCacheList('priorities');

		$a_categories = $this->member->findOrFail(auth()->user()->id)->getEditTicketCategories();

		$c_visible_agents = Member::visible()->get();

		// Tag lists
        $c_cat_tags = Category::whereHas('tags')
        ->with([
            'tags'=> function ($q1) {
                $q1->select('id', 'name');
            },
            'tags.tickets'=> function ($q2) {
                $q2->where('id', '0')->select('id');
            },
        ])
		->select('id', 'name')->get();
		
		$data = compact('c_members', 'c_status', 'priorities', 'a_categories', 'c_visible_agents', 'c_cat_tags');

		if (Setting::grab('departments_feature')){
			$data['c_departments'] = Department::whereNull('department_id')->with('descendants.ancestor')->orderBy('name', 'asc')->get();
		}

		return $data;
	}

	/**
     * Register search fields in user session
     *
     * @return Response
     */
    public function register_search_fields(Request $request)
    {
		$result = "error";
		$message = trans('panichd::lang.searchform-validation-no-field');
		
		// Forget last search
		session()->forget('search_fields');

		// Check all fields
		$a_fields = array_merge($this->a_search_fields_numeric, $this->a_search_fields_text, $this->a_search_fields_date, $this->a_search_fields_text_special, ['department_id', 'list']);
		foreach ($a_fields as $field){
			if($request->filled($field)){
				$search_fields[$field] = $request->{$field};
			}
		}

		// Register date field related types (specified with radio buttons)
		foreach ($this->a_search_fields_date as $field){
			if($request->filled($field)){
				$search_fields[$field . '_type'] = $request->{$field . '_type'};
			}
		}

		// Check ticket tags
		if ($request->filled('category_id') and $request->filled('category_' . $request->category_id . '_tags')){
			$search_fields['tags'] = $request->{'category_' . $request->category_id . '_tags'};
		}

		if (isset($search_fields)){
			// Store search fields in session to use in datatable
			session(compact('search_fields'));

			$result = "ok";
			$message = trans('panichd::lang.searchform-validation-success', ['num' => count($search_fields)]);
		}

        return response()->json(['result' => $result, 'messages' => [$message]]);
	}

    /**
     * Open Ticket creation form with optional parameters pre-setted by URL
     *
     * @return Response
     */
    public function create($parameters = null)
    {
  		$data = $this->create_edit_data();

      if (!is_null($parameters)){
         $data = $this->ticket_URL_parameters($data, $parameters);
      }

		$data['categories'] = $this->member->findOrFail(auth()->user()->id)->getNewTicketCategories();

        $data['a_notifications'] = [
            'note' => ($data['a_current']['agent_id'] != auth()->user()->id ? [$data['a_current']['agent_id']] : []),
            'reply' => ($data['a_current']['owner_id'] != auth()->user()->id ? [$data['a_current']['owner_id']] : [])
        ];

        return view('panichd::tickets.createedit', $data);
    }

    /*
     * Edit a ticket with optional parameters set by URL
     *
     */
    public function edit($id, $parameters = null)
    {
        $ticket = $this->tickets->findOrFail($id);

        $data = $this->create_edit_data($ticket);

        // Get URL parameters and replace a_current array with them
        if (!is_null($parameters)){
            $data = $this->ticket_URL_parameters($data, $parameters);
        }

        $data['ticket'] = $ticket;

        $data['categories'] = $this->member->findOrFail(auth()->user()->id)->getEditTicketCategories();

        $member = $this->member->find(auth()->user()->id);

        // Ticket comments
        $all_comments = $ticket->comments()->with('notifications');
        $comments = clone $all_comments;
        $data['comments'] = $comments->forLevel($member->levelInCategory($ticket->category_id))->orderBy('id','desc')->paginate(Setting::grab('paginate_items'));

        // Default notification recipients
        $all_c = clone $all_comments;
        $a_reply = [(!is_null($ticket->owner) ? $ticket->owner->id : $ticket->user_id)];
        $a_note = [$ticket->agent->id];
        foreach($all_c->get() as $comm){
            if ($comm->type == 'reply'){
                $a_reply = array_merge($a_reply, $comm->notifications->pluck('member_id')->toArray());
            }elseif($comm->type == 'note'){
                $a_note = array_merge($a_note, $comm->notifications->pluck('member_id')->toArray());
            }
        }
        $data['a_notifications'] = [
            'note' => array_unique($a_note),
            'reply' => array_unique($a_reply)
        ];

        $data['a_resend_notifications'] = $this->get_resend_notifications($ticket, $all_comments);

        return view('panichd::tickets.createedit', $data);
    }

    /*
     * Process parameter pairs passed by URL and update $data array
     */
    public function ticket_URL_parameters($data, $parameters)
    {
        // Get URL parameters and replace a_current array with them
        $a_temp = explode('/', $parameters);
        $a_parameters = [];

        if (count($a_temp) % 2 == 0){
          $key = "";
          foreach($a_temp as $param){
              if ($key == ""){
                  $key = $param;
              }else{
                  $data['a_current'][$key] = $param;
                  $key = "";
              }
          }
        }

        return $data;
    }

	public function create_edit_data($ticket = false, $a_parameters = false)
	{
        $menu = $ticket ? 'edit' : 'create';

        $member = $this->member->find(auth()->user()->id);

		$c_members = $this->members_collection($member);

		$priorities = $this->getCacheList('priorities');
		$status_lists = $this->getCacheList('statuses');

		$a_current = [];

		\Carbon\Carbon::setLocale(config('app.locale'));

		if (old('category_id')){
			// Form old values
            $a_current['owner_id'] = old('owner_id');

            $a_current['complete'] = old('complete');

			$a_current['start_date'] = old ('start_date');
			$a_current['limit_date'] = old ('limit_date');

			$a_current['priority_id'] = old('priority_id');

			$a_current['cat_id'] = old('category_id');
			$a_current['agent_id'] = old('agent_id');

		}elseif($ticket){
			// Edition values
            $a_current['owner_id'] = $ticket->user_id;

            $a_current['complete'] = $ticket->isComplete() ? "yes" : "no";
			$a_current['status_id'] = $ticket->status_id;

			$a_current['start_date'] = $ticket->start_date;
			$a_current['limit_date'] = $ticket->limit_date;

			$a_current['priority_id'] = $ticket->priority_id;

			$a_current['cat_id'] = $ticket->category_id;
			$a_current['agent_id'] = $ticket->agent_id;
		}else{
			// Defaults
            $a_current['owner_id'] = auth()->user()->id;

            $a_current['complete'] = "no";

			$a_current['start_date'] = $a_current['limit_date'] = "";

			$a_current['priority_id'] = Setting::grab('default_priority_id');

			// Default category
			if ($member->currentLevel() > 1){
				$a_current['cat_id'] = @$member->categories()->get()->first()->id;
				if ($a_current['cat_id'] == null){
					$a_current['cat_id'] = $member->getNewTicketCategories()->keys()->first();
				}
			}else{
				$a_current['cat_id'] = Category::orderBy('name')->first()->id;
			}

			// Default agent
			$a_current['agent_id'] = $member->id;
		}

		// Agent list
		$agent_lists = $this->agentList($a_current['cat_id']);

		// Permission level for category
		$permission_level = $member->levelInCategory($a_current['cat_id']);

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

		return compact('menu', 'c_members', 'priorities', 'status_lists', 'agent_lists', 'a_current', 'permission_level', 'tag_lists', 'a_tags_selected');
	}

	/**
	 * Previous tasks for ticket validation
	*/
	protected function validation_common($request, $new_ticket = true)
	{
		$member = $this->member->find(auth()->user()->id);
		$category_level = $member->levelInCategory($request->category_id);
		$permission_level = ($member->currentLevel() > 1 and $category_level > 1) ? $category_level : 1;

		$a_content = $this->purifyHtml($request->get('content'));
		$common_data = [
			'a_content' => $a_content,
			'permission_level' => $permission_level,
		];

        $request->merge([
            'subject'=> trim($request->get('subject')),
            'content'=> $a_content['content'],
			'content_html'=> $a_content['html']
        ]);

		if ($new_ticket){
			$allowed_categories = implode(",", $member->getNewTicketCategories()->keys()->toArray());
		}else{
			$allowed_categories = implode(",", $member->getEditTicketCategories()->keys()->toArray());
		}


		$fields = [
            'subject'     => 'required|min:3',
			'owner_id'    => 'required|exists:' . $this->member->getTable() . ',id',
			'category_id' => 'required|in:'.$allowed_categories,
            'content'     => 'required|min:6',
        ];
		$a_result_errors = [];

		if ($permission_level > 1) {
			if (in_array($request->input('hidden'), ['true', 'false'])){
				$request->merge(['hidden' => $request->input('hidden') == 'true' ? 1 : 0]);
			}else{
				$request->merge(['hidden' => 0]);
			}

			$fields['status_id'] = 'required|exists:panichd_statuses,id';
			if (!Setting::grab('use_default_status_id')) $fields['status_id'].= '|not_in:' . Setting::grab('default_status_id');

			$fields['priority_id'] = 'required|exists:panichd_priorities,id';

			if ($request->input('start_date') != ""){
				\Datetime::createFromFormat(trans('panichd::lang.datetime-format'), $request->input('start_date'));
				$errors = \DateTime::getLastErrors();
				if (isset($errors['warnings']) and isset($errors['errors']) and ($errors['warnings'] or $errors['errors'])){
					$date_error = trans('panichd::lang.validate-ticket-start_date-format', ['format' => trans('panichd::lang.datetimepicker-format')]);
					$a_result_errors = array_merge_recursive($a_result_errors, [
						'messages' => [$date_error],
						'fields' => ['start_date' => $date_error]
					]);
				}else{
					$start_date = Carbon::createFromFormat(trans('panichd::lang.datetime-format'), $request->input('start_date'));

					$plus_10_y = date('Y', time())+10;

					$request->merge([
						// Avoid PDOException for example with year 1
						'start_date' => ($start_date->year < Setting::grab('oldest_year') or $start_date->year > $plus_10_y) ? Carbon::now()->toDateTimeString() : $start_date->toDateTimeString(),
						'start_date_year' => $start_date->year
					]);

					$fields['start_date'] = 'date';
					$fields['start_date_year'] = 'in:'.implode(',', range(Setting::grab('oldest_year'), $plus_10_y));
				}
			}

			if ($request->input('limit_date') != ""){
				\Datetime::createFromFormat(trans('panichd::lang.datetime-format'), $request->input('limit_date'));
				$errors = \DateTime::getLastErrors();

				if (isset($errors['warnings']) and isset($errors['errors']) and ($errors['warnings'] or $errors['errors'])){
					$date_error = trans('panichd::lang.validate-ticket-limit_date-format', ['format' => trans('panichd::lang.datetimepicker-format')]);
					$a_result_errors = array_merge_recursive($a_result_errors, [
						'messages' => [$date_error],
						'fields' => ['limit_date' => $date_error]
					]);
				}else{
					$limit_date = Carbon::createFromFormat(trans('panichd::lang.datetime-format'), $request->input('limit_date'));
					$plus_10_y = date('Y', time())+10;


					$request->merge([
						// Avoid PDOException for example with year 1
						'limit_date' => ($limit_date->year < Setting::grab('oldest_year') or $limit_date->year > $plus_10_y) ? Carbon::now()->toDateTimeString() : $limit_date->toDateTimeString(),
						'limit_date_year' => $limit_date->year
					]);

					$fields['limit_date'] = 'date';
					$fields['limit_date_year'] = 'in:'.implode(',', range(Setting::grab('oldest_year'), $plus_10_y));
				}
			}


			$a_intervention = $common_data['a_intervention'] = $this->purifyInterventionHtml($request->get('intervention'));
			$request->merge([
				'intervention'=> $a_intervention['intervention'],
				'intervention_html'=> $a_intervention['intervention_html'],
			]);

			if ($request->exists('attachments')){
				$fields['attachments'] = 'array';
			}
        }

		// Custom validation messages
		$custom_messages = [
			'subject.required'        => trans ('panichd::lang.validate-ticket-subject.required'),
			'subject.min'             => trans ('panichd::lang.validate-ticket-subject.min'),
			'content.required'        => trans ('panichd::lang.validate-ticket-content.required'),
			'content.min'             => trans ('panichd::lang.validate-ticket-content.min'),
			'start_date_year.in'      => trans ('panichd::lang.validate-ticket-start_date'),
			'limit_date_year.in'      => trans ('panichd::lang.validate-ticket-limit_date'),
		];

		// Form validation
        $validator = Validator::make($request->all(), $fields, $custom_messages);

		if ($validator->fails()) {
			$a_messages = (array)$validator->errors()->all();

			$a_fields = (array)$validator->errors()->messages();

			if (isset($a_fields['start_date_year']) and !isset($a_fields['start_date']) and !isset($a_resolt_errors['fields']['start_date'])){
				$a_fields['start_date'] = $a_fields['start_date_year'];
				unset ($a_fields['start_date_year']);
			}

			if (isset($a_fields['limit_date_year']) and !isset($a_fields['limit_date']) and !isset($a_resolt_errors['fields']['limit_date'])){
				$a_fields['limit_date'] = $a_fields['limit_date_year'];
				unset ($a_fields['limit_date_year']);
			}

			foreach ($a_fields as $field=>$errors){
				$a_fields[$field] = implode('. ', $errors);
			}

			$a_result_errors = array_merge_recursive($a_result_errors, [
				'messages' => $a_messages,
				'fields' => $a_fields
			]);
		}


		// Date diff validation
		if ($request->input('start_date') != "" and isset($start_date) and $request->input('limit_date') != "" and isset($limit_date)
			and (!$a_result_errors or ($a_result_errors and !isset($a_result_errors['fields']['limit_date'])))){

			if ($start_date->diffInSeconds($limit_date, false) < 0){
				$lower_limit_date = trans('panichd::lang.validate-ticket-limit_date-lower');
				$a_result_errors = array_merge_recursive($a_result_errors, [
					'messages' => [$lower_limit_date],
					'fields' => ['limit_date' => $lower_limit_date]
				]);
			}
		}

		$common_data = array_merge($common_data, [
			'request' => $request,
			'a_result_errors' => $a_result_errors
		]);
		return $common_data;
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
		$common_data = $this->validation_common($request);
		extract($common_data);

		DB::beginTransaction();
        $ticket = new Ticket();

        $ticket->subject = $request->subject;
		$ticket->creator_id = auth()->user()->id;
		$ticket->user_id = $request->owner_id;

		if ($permission_level > 1) {
			$ticket->hidden = $request->hidden;

			if ($request->complete=='yes'){
				$ticket->completed_at = Carbon::now();
			}

			$ticket->status_id = $request->status_id;
			$ticket->priority_id = $request->priority_id;
		}else{
			$ticket->status_id = Setting::grab('default_status_id');
			$default_priority_id = Setting::grab('default_priority_id');
			$ticket->priority_id = $default_priority_id == 0 ? Models\Priority::first()->id : $default_priority_id;
		}

		if ($request->start_date != ""){
			$ticket->start_date = date('Y-m-d H:i:s', strtotime($request->start_date));
		}else{
			$ticket->start_date = Carbon::now();
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
			// Create attachments from embedded images
			$this->embedded_images_to_attachments($permission_level, $ticket);

			// Attached files
			$a_result_errors = $this->saveAttachments(compact('request', 'a_result_errors', 'ticket'));
		}

        // Embedded Comments
        list($a_new_comments, $a_result_errors) = $this->add_embedded_comments($permission_level, $request, $ticket, $a_result_errors);

        // If errors present
        if ($a_result_errors){
            return response()->json(array_merge(
                ['result' => 'error'],
                $a_result_errors
            ));
        }

		// End transaction
		DB::commit();

        // Ticket event
		event(new TicketCreated($ticket));

        // Comment events
        if ($a_new_comments){
            foreach($a_new_comments as $comment){
                event(new CommentCreated($comment, $request));
            }
        }

        $this->sync_ticket_tags($request, $ticket);

        session()->flash('status', trans('panichd::lang.the-ticket-has-been-created', [
			'name' => '#'.$ticket->id.' '.$ticket->subject,
			'link' => route(Setting::grab('main_route').'.show', $ticket->id),
			'title' => trans('panichd::lang.ticket-status-link-title')
		]));

        return response()->json([
			'result' => 'ok',
			'url' => action('\PanicHD\PanicHD\Controllers\TicketsController@index')
		]);
    }

    /*
     * Add embedded comments in ticket creation / edition
    */
    public function add_embedded_comments($permission_level, $request, $ticket, $a_result_errors)
    {
        $a_new_comments = [];

        if ($request->input('form_comments') != ""){
            $custom_messages = [
                'content.required' => trans('panichd::lang.validate-comment-required'),
                'content.min' => trans('panichd::lang.validate-comment-min'),
            ];

            foreach($request->form_comments as $i){

                if (!$request->input('response_' . $i) != "") continue;

                $response_type = in_array($request->{'response_' . $i}, ['note','reply']) ? $request->{'response_' . $i} : 'note';

                $request_comment = $request->input('comment_' . $i);
                $a_content = $this->purifyHtml($request_comment);

                $validator = Validator::make($a_content, ['content' => 'required|min:6'], $custom_messages);
                if ($validator->fails()) {
					$a_messages = $validator->errors()->messages();
					$a_result_errors['messages'][] = trans('panichd::lang.comment') . trans('panichd::lang.colon') . current($a_messages['content']);
					$a_result_errors['fields']['comment_' . $i] = current($a_messages['content']);
				}

				// Create new comment
				$comment = new Models\Comment();
				$comment->type = $response_type;
				$comment->ticket_id = $ticket->id;
				$comment->user_id = auth()->user()->id;
				$comment->content = $a_content['content'];
				$comment->html = $a_content['html'];
				$comment->save();

				// Create attachments from embedded images
				$this->embedded_images_to_attachments($permission_level, $ticket, $comment);
				
				if (Setting::grab('ticket_attachments_feature')){
					// Add embedded comment attached files
					$info = compact('request', 'a_result_errors', 'ticket', 'comment');
					
					// Specify form fields
					$info['attachments_prefix'] = 'comment_' . $i . '_';
					$info['attachments_field'] = 'comment_' . $i . '_attachments';
					$info['attachment_filenames_field'] = 'comment_' . $i . 'attachment_new_filenames';
					$info['attachment_descriptions_field'] = 'comment_' . $i . 'attachment_descriptions';

					// Process attachments
					$a_result_errors = $this->saveAttachments($info);
				}

				// Comment recipients
				if($request->input('comment_' . $i . '_recipients') != "") $comment->a_recipients = $request->{'comment_' . $i . '_recipients'};

				// Adds this as a $comment property to check it in NotificationsController
				if($request->input('comment_' . $i . '_notification_text') != "") $comment->add_in_user_notification_text =  'yes';

				$a_new_comments[] = $comment;
                
            }
        }

        return [$a_new_comments, $a_result_errors];
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
		$member = $this->member->find(auth()->user()->id);
		$members_table = $this->member->getTable();

		$ticket = $this->tickets
			->with('owner')
			->with('creator')
			->with('agent')
			->with('category.closingReasons')
			->with('tags')
			->leftJoin($members_table, function($join1) use($members_table){
				$join1->on($members_table . '.id', '=', 'panichd_tickets.user_id');
			})
			->leftJoin($members_table . ' as creator', function($join2){
				$join2->on('creator.id', '=', 'panichd_tickets.creator_id');
			})
			->leftJoin($members_table . ' as agent', function($join3){
				$join3->on('agent.id', '=', 'panichd_tickets.agent_id');
			});

		if (Setting::grab('departments_feature')){
			$ticket = $ticket->with('owner.department.ancestor');
		}

		$a_select = [
			'panichd_tickets.*',
			$members_table . '.name as owner_name',
			'creator.name as creator_name',
			'agent.name as agent_name',
			$members_table . '.email as owner_email'
		];

		// Select Ticket and properties
		$ticket = $ticket->select($a_select)->findOrFail($id);

        if (version_compare(app()->version(), '5.3.0', '>=')) {
            $a_reasons = $ticket->category->closingReasons()->pluck('text','id')->toArray();
			$a_tags_selected = $ticket->tags()->pluck('id')->toArray();
        } else { // if Laravel 5.1
            $a_reasons = $ticket->category->closingReasons()->lists('text','id')->toArray();
			$a_tags_selected = $ticket->tags()->lists('id')->toArray();
        }

		$status_lists = $this->getCacheList('statuses');
        $complete_status_list = $this->getCacheList('complete_statuses');

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

        $all_comments = $ticket->comments()->with('notifications')->forLevel($member->levelInCategory($ticket->category_id));
        $comments = clone $all_comments;
        $comments = $comments->orderBy('id','desc')->paginate(Setting::grab('paginate_items'));

        $a_notifications = ['reply' => [], 'note' => []];

        if($member->currentLevel() > 1 && $member->canManageTicket($ticket->id)){
            $all_c = clone $all_comments;
            $a_reply = [(!is_null($ticket->owner) ? $ticket->owner->id : $ticket->user_id)];
            $a_note = [$ticket->agent->id];
            foreach($all_c->get() as $comm){
                if($comm->type == 'note'){
                    $a_note = array_merge($a_note, $comm->notifications->pluck('member_id')->toArray());
                }elseif ($comm->type == 'reply'){
                    $a_reply = array_merge($a_reply, $comm->notifications->pluck('member_id')->toArray());
                }
            }

            $a_notifications = [
                'note' => array_unique($a_note),
                'reply' => array_unique($a_reply)
            ];
        }

        $c_members = $this->members_collection($member, false);

        $a_resend_notifications = $this->get_resend_notifications($ticket, $all_comments);

        $data = compact('ticket', 'a_reasons', 'a_tags_selected', 'status_lists', 'complete_status_list', 'agent_lists', 'tag_lists',
            'comments', 'a_notifications', 'c_members', 'a_resend_notifications', 'close_perm', 'reopen_perm');
        $data['menu'] = 'show';
        return view('panichd::tickets.show', $data);
    }

    /*
     * Get list of resend notifiications for each reply
     *
     * @return Array
    */
    public function get_resend_notifications($ticket, $all_comments)
    {
        // Recipients for notification resend
        $a_resend_notifications = [];

        foreach ($all_comments->get() as $comment){
            if ($comment->type == 'reply'){

                $a_comment_email_recipients = [$ticket->agent->email];
                $a_resend_notifications[$comment->id][] = (object)[
                    'member_id' => $ticket->agent->id,
                    'email' => $ticket->agent->email,
                    'name' => $ticket->agent->name
                ];

                if (!$ticket->hidden and $ticket->agent->email != $ticket->owner->email){
                     $a_comment_email_recipients[] = $ticket->owner->email;
                    $a_resend_notifications[$comment->id][] = (object)[
                        'member_id' => $ticket->owner->id,
                        'email' => $ticket->owner->email,
                        'name' => $ticket->owner->name
                    ];
                }

                if (count($comment->notifications) > 0){
                    foreach ($comment->notifications as $notification){
                        if (!in_array($notification->email, $a_comment_email_recipients)){
                            $a_comment_email_recipients[] = $notification->email;
                            $a_resend_notifications[$comment->id][] = (object)[
                                'member_id' => $notification->member_id,
                                'email' => $notification->email,
                                'name' => $notification->name
                            ];
                        }
                    }
                }
            }
        }

        return $a_resend_notifications;
    }

    /*
     * Return a collection with all members
     *
     * @return Collection
    */
    public function members_collection($member, $auth = true)
    {
        $c_members = \PanicHDMember::with('userDepartment');
        if (!$auth) $c_members = $c_members->where('email', '!=', auth()->user()->email);
        if ($member->currentLevel() > 1){
            return $c_members->orderBy('name')->get();
        }else{
            return $c_members->whereNull('ticketit_department')->orWhere('id','=',$member->id)->orderBy('name')->get();
        }
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
		$common_data = $this->validation_common($request, false);
		extract($common_data);

		$original_ticket = Ticket::findOrFail($id); // Requires a specific object instance
		$ticket = Ticket::findOrFail($id);

		DB::beginTransaction();

        $ticket->subject = $request->subject;
		$ticket->user_id = $request->owner_id;
		$ticket->hidden = $request->hidden;

        $ticket->content = $a_content['content'];
        $ticket->html = $a_content['html'];

		$member = $this->member->find(auth()->user()->id);
        if ($member->isAgent() or $member->isAdmin()) {
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
			$ticket->start_date = $ticket->created_at;
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
			// Create attachments from embedded images
			$this->embedded_images_to_attachments($permission_level, $ticket);

			// 1 - update existing attachment fields
			$a_result_errors = $this->updateAttachments($request, $a_result_errors, $ticket->attachments()->get());

			// 2 - add new attachments
			$a_result_errors = $this->saveAttachments(compact('request', 'a_result_errors', 'ticket'));

			if (!$a_result_errors){
				// 3 - destroy checked attachments
				if ($request->input('delete_files') != ""){
					$destroy_error = $this->destroyAttachmentIds($request->delete_files);

					if ($destroy_error) $a_result_errors['messages'][] = $destroy_error;
				}
			}
		}

        // Embedded Comments
        list($a_new_comments, $a_result_errors) = $this->add_embedded_comments($permission_level, $request, $ticket, $a_result_errors);

		// If errors present
		if ($a_result_errors){
			return response()->json(array_merge(
				['result' => 'error'],
				$a_result_errors
			));
		}

		// If ticket hidden changes, execute related actions
		if ($original_ticket->hidden != $ticket->hidden){
			$this->hide_actions($ticket);
		}

		// End transaction
		DB::commit();
		event(new TicketUpdated($original_ticket, $ticket));

        // Comment events
        if ($a_new_comments){
            foreach($a_new_comments as $comment){
                event(new CommentCreated($comment, $request));
            }
        }

		// Add complete/reopen comment
		if ($original_ticket->completed_at != $ticket->completed_at and ($original_ticket->completed_at == '' or $ticket->completed_at == '') ){
			$this->complete_change_actions($ticket, $member);
		}

        $this->sync_ticket_tags($request, $ticket);

        session()->flash('status', trans('panichd::lang.the-ticket-has-been-modified', ['name' => '#'.$ticket->id.' "'.$ticket->subject.'"']));

        return response()->json([
			'result' => 'ok',
			'url' => route(Setting::grab('main_route').'.show', $id)
		]);
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

        $error = $ticket->delete();

		if ($error){
			return redirect()->back()->with('warning', trans('panichd::lang.ticket-destroy-error', ['error' => $error]));
		}else{
			// Delete orphan tags (Without any related categories or tickets)
			Tag::doesntHave('categories')->doesntHave('tickets')->delete();

			session()->flash('status', trans('panichd::lang.the-ticket-has-been-deleted', ['name' => $subject]));
			return redirect()->route(Setting::grab('main_route').'.index');
		}


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
            $original_ticket = $this->tickets->findOrFail($id);
			$ticket = clone $original_ticket;
			$member = $this->member->find(auth()->user()->id);

			if ($ticket->hidden and $member->currentLevel() == 1){
				return redirect()->route(Setting::grab('main_route').'.index')->with('warning', trans('panichd::lang.you-are-not-permitted-to-access'));
			}

			$reason_text = trans('panichd::lang.complete-by-user', ['user' => $member->name]);
			$member_reason = $a_clarification = false;

			if ($member->currentLevel()>1){
				if (!$ticket->intervention_html and !$request->exists('blank_intervention')){
					return redirect()->back()->with('warning', trans('panichd::lang.show-ticket-complete-blank-intervention-alert'));
				}else{
					$status_id = $request->input('status_id');
					try {
						Models\Status::findOrFail($status_id);
					}catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e){
						return redirect()->back()->with('warning', trans('panichd::lang.show-ticket-complete-bad-status'));
					}
				}

				$ticket->status_id = $status_id;
			}else{
				// Verify Closing Reason
				if ($ticket->has('category.closingReasons')){
					if (!$request->exists('reason_id')){
						return redirect()->back()->with('warning', trans('panichd::lang.show-ticket-modal-complete-blank-reason-alert'));
					}

					try {
						$reason = Models\Closingreason::findOrFail($request->input('reason_id'));
						$member_reason = $reason->text;
					}catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e){
						return redirect()->back()->with('warning', trans('panichd::lang.show-ticket-complete-bad-reason-id'));
					}

					$reason_text .= trans('panichd::lang.colon') . $member_reason;
					$ticket->status_id = $reason->status_id;
				}else{
					$ticket->status_id = Setting::grab('default_close_status_id');
				}
			}

			// Add Closing Reason to intervention field
			$date = date(trans('panichd::lang.date-format'), time());
			$had_intervention = $ticket->intervention == "" ? false : true;
			$ticket->intervention = ($had_intervention ? $ticket->intervention . ' ' : '') . $date . ' ' . $reason_text;
			$ticket->intervention_html = ($had_intervention ? $ticket->intervention_html . '<br />' : '') . $date . ' ' . $reason_text;

			if ($member->currentLevel()<2){
				// Check clarification text
				$a_clarification = $this->purifyHtml($request->get('clarification'));
				if ($a_clarification['content'] != ""){
					$ticket->intervention = $ticket->intervention . ' ' . trans('panichd::lang.closing-clarifications') . trans('panichd::lang.colon') . $a_clarification['content'];
					$ticket->intervention_html = $ticket->intervention_html . '<br />' . trans('panichd::lang.closing-clarifications') . trans('panichd::lang.colon') . $a_clarification['html'];
				}
			}

			$ticket->completed_at = Carbon::now();
            $ticket->save();

			event(new TicketUpdated($original_ticket, $ticket));

			// Add complete comment
			$this->complete_change_actions($ticket, $member, $member_reason, $a_clarification);

            session()->flash('status', trans('panichd::lang.the-ticket-has-been-completed', [
				'name' => '#'.$id.' '.$ticket->subject,
				'link' => route(Setting::grab('main_route').'.show', $id),
				'title' => trans('panichd::lang.ticket-status-link-title')
			]));

            return redirect()->route(Setting::grab('main_route').'.index');
        }

        return redirect()->route(Setting::grab('main_route').'.index')
            ->with('warning', trans('panichd::lang.you-are-not-permitted-to-do-this'));
    }

	/**
	 * Actions to take when a ticket completion status changes
	*/
	public function complete_change_actions($ticket, $member, $member_reason = false, $a_clarification = false)
	{
		$latest = Models\Comment::where('ticket_id', $ticket->id)->where('user_id', $member->id)->orderBy('id', 'desc')->first();

		if ($latest and in_array($latest->type, ['complete', 'reopen'])){
			// Delete last comment for consecutive complete-reopen
			$latest->delete();
			return false;
		}

		// Create the comment
		$comment = new Models\Comment;

		if ($ticket->completed_at != ''){
			if ($member->currentLevel()>1){
				$comment->type = "complete";
				$comment->content = $comment->html = '';
			}else{
				$comment->type = "completetx";
				$comment->content = $comment->html = trans('panichd::lang.comment-completetx-title') . ($member_reason ? trans('panichd::lang.colon').$member_reason : '');

				if ($a_clarification and $a_clarification['content'] != ""){
					$comment->content = $comment->content . ' ' . trans('panichd::lang.closing-clarifications') . trans('panichd::lang.colon') . $a_clarification['content'];
					$comment->html = $comment->html . '<br />' . trans('panichd::lang.closing-clarifications') . trans('panichd::lang.colon') . $a_clarification['html'];
				}
			}
		}else{
			// Reopen comment
			$comment->type = "reopen";
			$comment->content = $comment->html = trans('panichd::lang.comment-reopen-title');
		}

		$comment->ticket_id = $ticket->id;
		$comment->user_id = $member->id;
		$comment->save();
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
			$member = $this->member->find(auth()->user()->id);

            $ticket->completed_at = null;

            if (Setting::grab('default_reopen_status_id')) {
                $ticket->status_id = Setting::grab('default_reopen_status_id');
            }

			$date = date(trans('panichd::lang.date-format'), time());
			$ticket->intervention = $ticket->intervention . ' ' . $date . ' ' . trans('panichd::lang.reopened-by-user', ['user' => $member->name]);
			$ticket->intervention_html = $ticket->intervention_html . '<br />' . $date . ' ' . trans('panichd::lang.reopened-by-user', ['user' => $member->name]);

            $ticket->save();

			// Add reopen comment
			$this->complete_change_actions($ticket, $member);


            session()->flash('status', trans('panichd::lang.the-ticket-has-been-reopened', [
				'name' => '#'.$id.' '.$ticket->subject,
				'link' => route(Setting::grab('main_route').'.show', $id),
				'title' => trans('panichd::lang.ticket-status-link-title')
			]));

            return redirect()->route(Setting::grab('main_route').'.index');
        }

        return redirect()->route(Setting::grab('main_route').'.index')
            ->with('warning', trans('panichd::lang.you-are-not-permitted-to-do-this'));
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
	public function changeAgent(Request $request)
  {
		$original_ticket = Ticket::findOrFail($request->input('ticket_id'));
		$ticket = clone $original_ticket;
		$old_agent = $ticket->agent()->first();
    if ($request->input('agent_id') != ""){
      $new_agent = \PanicHDMember::find($request->input('agent_id'));
    }else
      $new_agent = clone $old_agent;

		if (!$request->input('status_checkbox') != "" and (is_null($new_agent) || $ticket->agent_id == $request->input('agent_id'))){
			return redirect()->back()->with('warning', trans('panichd::lang.update-agent-same', [
				'name' => '#'.$ticket->id.' '.$ticket->subject,
				'link' => route(Setting::grab('main_route').'.show', $ticket->id),
				'title' => trans('panichd::lang.ticket-status-link-title')
			]));
		}

		$ticket->agent_id =  $new_agent->id;

		$old_status_id = $ticket->status_id;
		if ($request->input('status_checkbox') != "" || !Setting::grab('use_default_status_id')){
			$ticket->status_id = Setting::grab('default_reopen_status_id');
		}
		$ticket->save();
		event(new TicketUpdated($original_ticket, $ticket));

		session()->flash('status', trans('panichd::lang.update-agent-ok', [
			'name' => '#'.$ticket->id.' '.$ticket->subject,
			'link' => route(Setting::grab('main_route').'.show', $ticket->id),
			'title' => trans('panichd::lang.ticket-status-link-title'),
			'old_agent' => $old_agent->name,
			'new_agent' => $new_agent->name
		]));

		return redirect()->route(Setting::grab('main_route') . ($ticket->isComplete() ? '-complete' : ($old_status_id == Setting::grab('default_status_id') ? '-newest' : '.index')));
	}

	/*
	 * Change priority in ticket list
	*/
	public function changePriority(Request $request){
		$ticket = Ticket::findOrFail($request->input('ticket_id'));
		$old_priority = $ticket->priority()->first();
		$new_priority = Models\Priority::find($request->input('priority_id'));

		if (is_null($new_priority) || $ticket->priority_id==$request->input('priority_id')){
			return redirect()->back()->with('warning', trans('panichd::lang.update-priority-same', [
				'name' => '#'.$ticket->id.' '.$ticket->subject,
				'link' => route(Setting::grab('main_route').'.show', $ticket->id),
				'title' => trans('panichd::lang.ticket-status-link-title')
			]));
		}

		$ticket->priority_id = $request->input('priority_id');
		$ticket->save();

		session()->flash('status', trans('panichd::lang.update-priority-ok', [
			'name' => '#'.$ticket->id.' '.$ticket->subject,
			'link' => route(Setting::grab('main_route').'.show', $ticket->id),
			'title' => trans('panichd::lang.ticket-status-link-title'),
			'old' => $old_priority->name,
			'new' => $new_priority->name
		]));

		return redirect()->route(Setting::grab('main_route') . ($ticket->isComplete() ? '-complete' : ($ticket->status_id == Setting::grab('default_status_id') ? '-newest' : '.index')));
	}

	/**
	 * Hide or make visible a ticket for a user
	*/
	public function hide($value, $id)
	{
		$ticket = Ticket::findOrFail($id);
		if (!in_array($value, ['true', 'false'])){
			return redirect()->back()->with('warning', trans('panichd::lang.validation-error'));
		}

		$ticket->hidden = $value=='true' ? 1 : 0;
		$ticket->save();

		$this->hide_actions($ticket);

		session()->flash('status', trans('panichd::lang.ticket-visibility-changed'));
		return redirect()->back();
	}

	/**
	 * Actions to take when a ticket hidden value changes
	*/
	public function hide_actions($ticket)
	{
		$member = $this->member->find(auth()->user()->id);

		$latest = Models\Comment::where('ticket_id', $ticket->id)->where('user_id', $member->id)->orderBy('id', 'desc')->first();

		if ($latest and in_array($latest->type, ['hide_0', 'hide_1'])){
			// Delete last comment for consecutive ticket hide and show for user
			$latest->delete();
			return false;
		}

		// Add hide/notHide comment
		$comment = new Models\Comment;
		$comment->type = "hide_".$ticket->hidden;
		$comment->content = $comment->html = trans('panichd::lang.ticket-hidden-'.$ticket->hidden.'-comment');
		$comment->ticket_id = $ticket->id;
		$comment->user_id = $member->id;
		$comment->save();
	}

	/**
	 * Return integer user level for specified category (false = no permission, 1 = user, 2 = agent, 3 = admin)
	*/
	public function permissionLevel ($category_id)
	{
		$user = $this->member->find(auth()->user()->id);

		return $user->levelInCategory($category_id);
	}


    /**
     * @param $id
     *
     * @return bool
     */
    public function permToClose($id)
    {
        $user = $this->member->find(auth()->user()->id);

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
        if ($this->member->isAdmin() && $reopen_ticket_perm['admin'] == 'yes') {
            return 'yes';
        } elseif ($this->member->isAgent() && $reopen_ticket_perm['agent'] == 'yes') {
            return 'yes';
        } elseif ($this->member->isTicketOwner($id) && $reopen_ticket_perm['owner'] == 'yes') {
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
