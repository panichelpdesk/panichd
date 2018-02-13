<?php

namespace PanicHD\PanicHD\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Date\Date;
use PanicHD\PanicHD\Models\Member;
use PanicHD\PanicHD\Traits\ContentEllipse;

/**
 * @property Attachment[]|Collection attachments
 *
 * @see Ticket::attachments()
 */
class Ticket extends Model
{
    use ContentEllipse;

    protected $table = 'panichd_tickets';
    protected $dates = ['completed_at'];
	
	/**
	 * Delete Ticket instance and related ones
	*/
	public function delete()
	{
		$a_errors = [];
		foreach($this->allAttachments()->get() as $att){
			$error = $att->delete();
			if($error) $a_errors[] = $error;
		}
		
		if($a_errors){
			return implode('. ', $a_errors);
		}
		
		$this->tags()->detach();
		$this->comments()->delete();

		parent::delete();
	}
	
    /**
     * List of completed tickets.
     *
     * @return bool
     */
    public function hasComments()
    {
        return (bool) count($this->comments);
    }

    public function isComplete()
    {
        return (bool) $this->completed_at;
    }

	
	/**
     * List of active tickets.
     *
     * @return Collection
     */
    public function scopeActive($query)
    {
        return $query->whereNull('completed_at');
    }
	
    /**
     * List of completed tickets.
     *
     * @return Collection
     */
    public function scopeComplete($query)
    {
        return $query->whereNotNull('completed_at');
    }

	/**
     * List of new tickets (active with status "new")
     *
     * @return Collection
     */
    public function scopeNewest($query)
    {
        return $query->whereNull('completed_at')->where('status_id',Setting::grab('default_status_id'));
    }

    /**
     * Get specified ticket list (active or complete).
     *
     * @return Collection
     */
    public function scopeInList($query, $ticketList = 'active')
    {
        switch ($ticketList){
			case 'newest':
				return $query->newest($query);
				break;
			case 'complete':
				return $query->complete($query);
				break;			
			default:
				return $query->active($query);
				break;
		}        
    }

    /**
     * Get Ticket status.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function status()
    {
        return $this->belongsTo('PanicHD\PanicHD\Models\Status', 'status_id');
    }

    /**
     * Get Ticket priority.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function priority()
    {
        return $this->belongsTo('PanicHD\PanicHD\Models\Priority', 'priority_id');
    }

    /**
     * Get Ticket category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('PanicHD\PanicHD\Models\Category', 'category_id');
    }

	/**
     * Get Ticket creator.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function creator()
    {
        return $this->belongsTo('App\User', 'creator_id');
    }
	
    /**
     * Get Ticket owner as App\User model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
	
	/**
     * Get Ticket owner as PanicHD\PanicHD\Models\Member model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo('PanicHD\PanicHD\Models\Member', 'user_id');
    }

    /**
     * Get Ticket agent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent()
    {
        return $this->belongsTo('PanicHD\PanicHD\Models\Member', 'agent_id');
    }

    /**
     * Get Ticket comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('PanicHD\PanicHD\Models\Comment', 'ticket_id');
    }
	
	/**
     * Get Ticket comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recentComments()
    {
        return $this->hasMany('PanicHD\PanicHD\Models\Comment', 'ticket_id')->where('panichd_comments.updated_at','>', Carbon::yesterday());
    }

    /**
     * Ticket attachments (NOT including its comments attachments).
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function attachments()
    {
        return $this->hasMany(Attachment::class, 'ticket_id')
            ->whereNull('comment_id')->orderByRaw('CASE when mimetype LIKE "image/%" then 1 else 2 end');
    }
	
	/**
     * All related attachments for Ticket (+comment attachments) 
	 *
	 * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
	public function allAttachments()
	{
		return $this->hasMany(Attachment::class, 'ticket_id');
	}

//    /**
    //     * Get Ticket audits
    //     *
    //     * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //     */
    //    public function audits()
    //    {
    //        return $this->hasMany('PanicHD\PanicHD\Models\Audit', 'ticket_id');
    //    }
    //

    /**
     * Get related tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany('PanicHD\PanicHD\Models\Tag', 'taggable', 'panichd_taggables')->orderBy('name');
    }

    /**
     * @see Illuminate/Database/Eloquent/Model::asDateTime
     */
    public function freshTimestamp()
    {
        return new Date();
    }

    /**
     * @see Illuminate/Database/Eloquent/Model::asDateTime
     */
    protected function asDateTime($value)
    {
        if (is_numeric($value)) {
            return Date::createFromTimestamp($value);
        } elseif (preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $value)) {
            return Date::createFromFormat('Y-m-d', $value)->startOfDay();
        } elseif (!$value instanceof DateTime) {
            $format = $this->getDateFormat();

            return Date::createFromFormat($format, $value);
        }

        return Date::instance($value);
    }
	
	/*
	 * Improves Carbon diffForHumans to specify yesterday, today and tomorrow dates
	 *
	 * @param $date Eloquent property from timestamp field
	 *
	 * @return string
	*/
	public function getDateForHumans($date, $descriptive = false)
	{		
		$parsed = Carbon::parse($date);
		$date_diff = Carbon::now()->startOfDay()->diffInDays($parsed->startOfDay(), false);
		$date_text = "";
		$time = date('H:i', strtotime($date));
		if ($this->limit_date and Carbon::parse($this->start_date)->startOfDay()->diffInDays(Carbon::parse($this->limit_date)->startOfDay()) == 0){
			$time = date('H:i', strtotime($this->start_date)) .'-'. date('H:i', strtotime($this->limit_date));
		}
		
		if ($date_diff == -1){
			$date_text = trans('panichd::lang.yesterday') . ", " . $time;
		}elseif ($date_diff === 0){
			$date_text = trans('panichd::lang.today') . ", " . $time;
		}elseif ($date_diff == 1){
			$date_text = trans('panichd::lang.tomorrow') . ", " . $time;
		}elseif ($date_diff > 1 and $parsed->diffInSeconds(Carbon::now()->addDays(6)->endOfDay(), false) > 0){
			// This week
			$date_text = trans('panichd::lang.day_'.$parsed->dayOfWeek) . ", " . $time;
		}else{
			// Older than yesterday or after this week
			if ($descriptive){
				$date_text = Carbon::parse($date)->diffForHumans();
			}else{
				$date_text = date(trans('panichd::lang.date-format'), strtotime($date));
				
				// Future only
				if($date_diff > 1) $date_text.=", " . $time;
			}
		}
			
		return $date_text;
	}
	
	/**
	 * Process start date and limit date and return a formatted div with resumed calendar information
	 *
	 * @return string
	*/
	public function getCalendarInfo($question_sign = false, $show = 'field')
	{
		$date = $title = $icon = "";
		$color = "text-muted";
		$start_days_diff = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($this->start_date)->startOfDay(), false);			
		if ($this->limit_date != ""){
			$limit_days_diff = Carbon::now()->startOfDay()->diffInDays(Carbon::parse($this->limit_date)->startOfDay(), false);				
			if ($limit_days_diff == 0){
				$limit_seconds_diff = Carbon::now()->diffInSeconds(Carbon::parse($this->limit_date), false);
			}
		}else{
			$limit_days_diff = false;
		}
					
		if ($limit_days_diff < 0 or ($limit_days_diff == 0 and isset($limit_seconds_diff) and $limit_seconds_diff < 0)){
			// Expired
			$date = $this->limit_date;
			if ($limit_days_diff == 0){
				$title = trans('panichd::lang.calendar-expired-today', ['time' => date('H:i', strtotime($date))]);
			}else{
				$title = trans('panichd::lang.calendar-expired', ['description' => $this->getDateForHumans($date, true)]);
			}
			
			$icon = "glyphicon-exclamation-sign";
			$color = "text-danger";
		}elseif($limit_days_diff > 0 or $limit_days_diff === false){
			if ($start_days_diff > 0){
				// Scheduled
				$date = $this->start_date;
				if ($limit_days_diff){
					if ($start_days_diff == $limit_days_diff){
						$title = trans('panichd::lang.calendar-scheduled', [
							'date' => date(trans('panichd::lang.date-format'), strtotime($this->start_date)),
							'time1' =>date('H:i', strtotime($this->start_date)),
							'time2' =>date('H:i', strtotime($this->limit_date))
						]);
					}else{
						$title = trans('panichd::lang.calendar-scheduled-period', [
							'date1' => $this->getDateForHumans($date),
							'date2' => $this->getDateForHumans($this->limit_date)]);
					}
					$icon = $start_days_diff == 1 ? "glyphicon-time" : "glyphicon-calendar";
					$color = "text-info";
				}else{
					$title = trans('panichd::lang.calendar-active-future', ['description' => $this->getDateForHumans($date, true)]);
					$icon = "glyphicon-file";
				}										
				
			}elseif($limit_days_diff){
				// Active with limit
				$date = $this->limit_date;
				$title = trans('panichd::lang.calendar-expiration', ['description' => $this->getDateForHumans($date, true)]);
				$icon = "glyphicon-time";
				$color = "text-info";
			}else{
				// Active without limit
				$date = $this->start_date;
				if ($start_days_diff == 0){
					$title = trans('panichd::lang.calendar-active-today', ['description' => $this->getDateForHumans($date, true)]);
				}else{
					$title = trans('panichd::lang.calendar-active', ['description' => $this->getDateForHumans($date, true)]);
				}
				
				$icon = "glyphicon-file";					
			}				
		}else{
			// Due today
			$date = $this->limit_date;
			if(Carbon::now()->diffInSeconds(Carbon::parse($this->start_date), false) < 0){
				// Already started
				$title = trans('panichd::lang.calendar-expires-today', ['hour' => date('H:i', strtotime($date))]);
			}else{
				// Scheduled for today but not yet started
				$title = trans('panichd::lang.calendar-scheduled-today', [
					'time1' =>date('H:i', strtotime($this->start_date)),
					'time2' =>date('H:i', strtotime($this->limit_date))
				]);
			}
			
			$icon = "glyphicon-warning-sign";
			$color = "text-warning";
		}
		
		$date_text = $this->getDateForHumans($date);
		
		if ($show == 'description') {
			// Date description only
			return '<span class="glyphicon '.$icon.'"></span> '.$title;
		}else{
			// Full field
			return "<span class=\"tooltip-info $color\" title=\"$title\" data-toggle=\"tooltip\" data-placement=\"auto bottom\"><span class=\"glyphicon $icon\"></span> $date_text ".($question_sign ? "<span class=\"glyphicon glyphicon-question-sign\"></span>" : "")."</span>";
		}
	}
	
	/**
	 * Get abbreviated and localized last update time
	 *
	 * @return string
	*/
	public function getUpdatedAbbr()
	{
		$seconds = $this->updated_at->diffInSeconds();
		$days = $this->updated_at->diffInDays();
		
		if ($seconds < 60){
			return $seconds." ".trans('panichd::lang.second-abbr');
		}elseif($seconds < 3600){
			return $this->updated_at->diffInMinutes()." ".trans('panichd::lang.minute-abbr');
		}elseif($days < 1){
			return $this->updated_at->diffInHours()." ".trans('panichd::lang.hour-abbr');
		}elseif($days < 15){
			return $days." ".trans('panichd::lang.day-abbr');
		}elseif($days < 32){
			return $this->updated_at->diffInWeeks()." ".trans('panichd::lang.week-abbr');
		}else{
			return $this->updated_at->diffInMonths()." ".trans('panichd::lang.month-abbr');
		}
	}

    /**
     * Get all user tickets.
     *
     * @param $query
     * @param $id
     *
     * @return mixed
     */
    public function scopeUserTickets($query, $id)
    {
        return $query->where('user_id', $id)->notHidden();
    }

    /**
     * Get all agent tickets.
     *
     * @param $query
     * @param $id
     *
     * @return mixed
     */
    public function scopeAgentTickets($query, $id)
    {
        return $query->where('agent_id', $id);
    }

    /**
     * Get all agent tickets.
     *
     * @param $query
     * @param $id
     *
     * @return mixed
     */
    public function scopeAgentUserTickets($query, $id)
    {
        return $query->where(function ($subquery) use ($id) {
            $subquery->where('agent_id', $id)->orWhere('user_id', $id)->notHidden();
        });
    }

    /**
     * Get all visible tickets for current user.
     * Includes: general user permissions and applied filters
	 *
     * @param $query
     * @param $id
     *
     * @return mixed
     */
    public function scopeVisible($query)
    {
        if (auth()->user()->panichd_admin) {
            return $query;
        } elseif (auth()->user()->panichd_agent){
			return $query->visibleForAgent(auth()->user()->id);
        } else {
            return $query->userTickets(auth()->user()->id);
        }
    }

    /**
     * Get all visible tickets for agent.
     *
     * @param $query
     * @param $id
     *
     * @return mixed
     */
    public function scopeVisibleForAgent($query, $id = false)
    {
        if (!$id) $id = auth()->user()->id;
		$agent = Member::findOrFail($id);		
		
		if ($agent->currentLevel() == 2) {
			// Depends on agent_restrict
			if (Setting::grab('agent_restrict') == 0) {
				// Returns all tickets on Categories where Agent with $id belongs to.
				return $query->whereHas('category', function ($q1) use ($id) {
					$q1->whereHas('agents', function ($q2) use ($id) {
						$q2->where('id', $id);
					});
				});
			} else {
				// Returns all tickets Owned by Agent with $id only
				return $query->agentTickets($id);
			}
		}else{
			return $query->userTickets($id)
				->whereDoesntHave('category',function($q1) use($id){
					$q1->whereHas('agents',function($q2) use($id){
						$q2->where('id',$id);
					});
				});
		}
		
    }
	
	/**
     * Filters to ticket list
     *
     * @param $query
     * @param $id
     *
     * @return mixed
     */
	public function scopeFiltered($query, $ticketList)
	{
		$member = Member::find(auth()->user()->id);
		
		if ($member->currentLevel() == 1){
			// If session()->has('panichd_filter_currentLevel')
			return $query->userTickets(auth()->user()->id);
		}else{
			if (session()->has('panichd_filters')){
				
				if ($ticketList != 'complete'){
					// Calendar filter
					if (session()->has('panichd_filter_calendar')){
						$cld = session('panichd_filter_calendar');
					
						if ($cld == "expired"){
							$query = $query->where('limit_date', '<', Carbon::now());
						}else{										
							$query = $query->where('limit_date', '>=', Carbon::now()->today());
						}
						
						switch ($cld){
							case 'today':
								$query = $query->where('limit_date', '<', Carbon::now()->tomorrow());
								break;
								
							case 'tomorrow':
								$query = $query->where('limit_date', '>=', Carbon::now()->tomorrow());
								$query = $query->where('limit_date', '<', Carbon::now()->addDays(2)->startOfDay());
								break;
								
							case 'week':
								$query = $query->where('limit_date', '<', Carbon::now()->endOfWeek());
								break;
							
							case 'month':
								$query = $query->where('limit_date', '<', Carbon::now()->endOfMonth());
								break;
						}
					}
				}
				
				// Category filter
				if (session()->has('panichd_filter_category')){
					$category = session('panichd_filter_category');
					$query = $query->where('category_id', session('panichd_filter_category'));
				}
				
				// Agent filter
				if (session()->has('panichd_filter_agent')){
					$query = $query->agentTickets(session('panichd_filter_agent'));
				}

				// Owner filter
				if (session()->has('panichd_filter_owner') and session('panichd_filter_owner')=="me"){
					$query = $query->userTickets(auth()->user()->id);
				}			
			}
			
			if ($ticketList == 'complete'){
				// Year filter
				$query = $query->completedOnYear();
			}
			
			return $query;
		}		
	}

    /**
     * Get all tickets in specified category.
     *
     * @param $query
     * @param $id
     *
     * @return mixed
     */
    public function scopeInCategory($query, $id = null)
    {
        if (isset($id)) {
            return $query->where('category_id', $id);
        } else {
            return $query;
        }
    }

	/**
     * Get tickets that are not hidden for users
     *
     * @param $query
     *
     * @return mixed
     */
	public function scopeNotHidden($query)
	{
		return $query->where('hidden', '0');
	}
	
	/**
	 * Get tickets completed in selected year
	 *
	 * default is current year
	 *
	 * @param $query
	 *
     * @return mixed
	*/
	public function scopeCompletedOnYear($query, $year = false)
	{
		$query = $query->complete();
		
		if ($year){
			// All years
			if ($year == 'all') return $query;
		}elseif(session()->has('panichd_filter_year')){
			if (session('panichd_filter_year') == 'all'){
				// All years
				return $query;
			}else{
				// Filtered year
				$year = session('panichd_filter_year');
			}
		}else{
			// Current year
			$year = date('Y');
		}
		
		return $query->whereYear('completed_at', $year);
	}
	
	
	
    /**
     * Sets the agent with the lowest tickets assigned in specific category.
     *
     * @return Ticket
     */
    public function autoSelectAgent()
    {
        $cat_id = $this->category_id;
        $agents = Category::find($cat_id)->agents()->wherePivot('autoassign', '1')->with(['agentTotalTickets' => function ($query) {
            $query->addSelect(['id', 'agent_id']);
        }])->get();
        $count = 0;
        $lowest_tickets = 1000000;
        // If no agent selected, select the admin
        $first_admin = Member::admins()->first();
        $selected_agent_id = $first_admin->id;
        foreach ($agents as $agent) {
            if ($count == 0) {
                $lowest_tickets = $agent->agentTotalTickets->count();
                $selected_agent_id = $agent->id;
            } else {
                $tickets_count = $agent->agentTotalTickets->count();
                if ($tickets_count < $lowest_tickets) {
                    $lowest_tickets = $tickets_count;
                    $selected_agent_id = $agent->id;
                }
            }
            $count++;
        }
        $this->agent_id = $selected_agent_id;

        return $this;
    }
}
