<?php

namespace Kordy\Ticketit\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Jenssegers\Date\Date;
use Kordy\Ticketit\Models\Agent;
use Kordy\Ticketit\Traits\ContentEllipse;

/**
 * @property Attachment[]|Collection attachments
 *
 * @see Ticket::attachments()
 */
class Ticket extends Model
{
    use ContentEllipse;

    protected $table = 'ticketit';
    protected $dates = ['completed_at'];

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
        return $this->belongsTo('Kordy\Ticketit\Models\Status', 'status_id');
    }

    /**
     * Get Ticket priority.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function priority()
    {
        return $this->belongsTo('Kordy\Ticketit\Models\Priority', 'priority_id');
    }

    /**
     * Get Ticket category.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function category()
    {
        return $this->belongsTo('Kordy\Ticketit\Models\Category', 'category_id');
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
     * Get Ticket owner as App\User model
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function owner()
    {
        return $this->belongsTo('Kordy\Ticketit\Models\Agent', 'user_id');
    }	
	

    /**
     * Get Ticket agent.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function agent()
    {
        return $this->belongsTo('Kordy\Ticketit\Models\Agent', 'agent_id');
    }

    /**
     * Get Ticket comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('Kordy\Ticketit\Models\Comment', 'ticket_id');
    }
	
	/**
     * Get Ticket comments.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function recentComments()
    {
        return $this->hasMany('Kordy\Ticketit\Models\Comment', 'ticket_id')->where('ticketit_comments.updated_at','>', Carbon::yesterday());
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
    //        return $this->hasMany('Kordy\Ticketit\Models\Audit', 'ticket_id');
    //    }
    //

    /**
     * Get related tags.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphToMany
     */
    public function tags()
    {
        return $this->morphToMany('Kordy\Ticketit\Models\Tag', 'taggable', 'ticketit_taggables')->orderBy('name');
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
		$date_text = date('H:i', strtotime($date));
		
		if ($date_diff == -1){
			$date_text = trans('ticketit::lang.yesterday') . ", " . $date_text;
		}elseif ($date_diff === 0){
			$date_text = trans('ticketit::lang.today') . ", " . $date_text;
		}elseif ($date_diff == 1){
			$date_text = trans('ticketit::lang.tomorrow') . ", " . $date_text;
		}elseif ($date_diff > 1 and $parsed->diffInSeconds(Carbon::now()->endOfWeek(), false) > 0){
			$date_text = trans('ticketit::lang.day_'.$parsed->dayOfWeek) . ", " . $date_text;
		}else{
			if ($descriptive){
				$date_text = Carbon::parse($date)->diffForHumans();
			}else{
				$date_text = date(trans('ticketit::lang.date-format'), strtotime($date));
			}
		}
			
		return $date_text;
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
        return $query->where('user_id', $id);
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
            $subquery->where('agent_id', $id)->orWhere('user_id', $id);
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
        if (auth()->user()->ticketit_admin) {
            return $query;
        } elseif (auth()->user()->ticketit_agent){
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
		$agent = Agent::findOrFail($id);		
		
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
	public function scopeFiltered($query)
	{
		$agent = Agent::find(auth()->user()->id);
		
		if ($agent->currentLevel() == 1){
			// If session()->has('ticketit_filter_currentLevel')
			return $query->userTickets(auth()->user()->id);
		}else{
			if (session()->has('ticketit_filters')){
				// Calendar filter
				if (session()->has('ticketit_filter_calendar')){
					$cld = session('ticketit_filter_calendar');
					
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
				
				
				// Category filter
				if (session()->has('ticketit_filter_category')){
					$category = session('ticketit_filter_category');
					$query = $query->where('category_id', session('ticketit_filter_category'));
				}
				
				// Agent filter
				if (session()->has('ticketit_filter_agent')){
					$agent = session('ticketit_filter_agent');
					$query = $query->agentTickets(session('ticketit_filter_agent'));
				}

				// Owner filter
				if (session()->has('ticketit_filter_owner') and session('ticketit_filter_owner')=="me"){
					$query = $query->userTickets(auth()->user()->id);
				}			
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
        $first_admin = Agent::admins()->first();
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
