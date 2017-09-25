<?php

namespace Kordy\Ticketit\Models;

use App\User;
use Auth;

class Agent extends User
{
    protected $table = 'users';

    /**
     * list of all agents and returning collection.
     *
     * @param $query
     * @param bool $paginate
     *
     * @return bool
     *
     * @internal param int $cat_id
     */
    public function scopeAgents($query, $paginate = false)
    {
        if ($paginate) {
            return $query->where('ticketit_agent', '1')->paginate($paginate, ['*'], 'agents_page');
        } else {
            return $query->where('ticketit_agent', '1');
        }
    }

    /**
     * list of all admins and returning collection.
     *
     * @param $query
     * @param bool $paginate
     *
     * @return bool
     *
     * @internal param int $cat_id
     */
    public function scopeAdmins($query, $paginate = false)
    {
        if ($paginate) {
            return $query->where('ticketit_admin', '1')->paginate($paginate, ['*'], 'admins_page');
        } else {
            return $query->where('ticketit_admin', '1')->get();
        }
    }

    /**
     * list of all agents and returning collection.
     *
     * @param $query
     * @param bool $paginate
     *
     * @return bool
     *
     * @internal param int $cat_id
     */
    public function scopeUsers($query, $paginate = false)
    {
        if ($paginate) {
            return $query->where('ticketit_agent', '0')->paginate($paginate, ['*'], 'users_page');
        } else {
            return $query->where('ticketit_agent', '0')->get();
        }
    }

    /**
     * list of all agents and returning lists array of id and name.
     *
     * @param $query
     *
     * @return bool
     *
     * @internal param int $cat_id
     */
    public function scopeAgentsLists($query)
    {
        if (version_compare(app()->version(), '5.2.0', '>=')) {
            return $query->where('ticketit_agent', '1')->pluck('name', 'id')->toArray();
        } else { // if Laravel 5.1
            return $query->where('ticketit_agent', '1')->lists('name', 'id')->toArray();
        }
    }

    /**
     * Check if user is agent.
     *
     * @return bool
     */
    public static function isAgent($id = null)
    {
        if (isset($id)) {
            $user = User::find($id);
            if ($user->ticketit_agent) {
                return true;
            }

            return false;
        }
        if (auth()->check()) {
            if (auth()->user()->ticketit_agent) {
                return true;
            }
        }
    }

    /**
     * Check if user is admin.
     *
     * @return bool
     */
    public static function isAdmin()
    {
        return auth()->check() && auth()->user()->ticketit_admin;
    }

    /**
     * Check if user is the assigned agent for a ticket.
     *
     * @param int $id ticket id
     *
     * @return bool
     */
    public static function isAssignedAgent($id)
    {
        if (auth()->check() && Auth::user()->ticketit_agent) {
            if (Auth::user()->id == Ticket::find($id)->agent->id) {
                return true;
            }
        }
    }

    /**
     * Check if user is the owner for a ticket.
     *
     * @param int $id ticket id
     *
     * @return bool
     */
    public static function isTicketOwner($id)
    {
        if (auth()->check()) {
            if (auth()->user()->id == Ticket::find($id)->user->id) {
                return true;
            }
        }
    }
	
	/**
     * Get general maximum permission level for current user.
     *
     * @param int $id category id
     *
     * @return integer
     */
	public static function maxLevel()
	{
		if (!auth()->check()) return 0;
		$agent = Agent::find(auth()->user()->id);
		if ($agent->isAdmin()){
			return 3;
		}elseif($agent->isAgent()){
			return 2;
		}else
			return 1;
	}
	
	/**
     * Get current permission level for user.
     *
     * @param int $id category id
     *
     * @return integer
     */
	public static function currentLevel()
	{
		if (!auth()->check()) return 0;
		$agent = Agent::find(auth()->user()->id);
		if ($agent->isAdmin()){
			return 3;
		}elseif($agent->isAgent()){
			if (session()->exists('ticketit_filter_currentLevel') and session('ticketit_filter_currentLevel')==1){
				return 1;
			}else{
				return 2;
			}
		}else
			return 1;
	}
	
	/**
     * Get permission level for specified category.
     *
     * @param int $id category id
     *
     * @return integer
     */
	public static function levelInCategory($id = false)
	{
		if (!auth()->check()) return false;
		$agent = Agent::find(auth()->user()->id);
		
		if ($agent->isAdmin()){
			return 3;
		}elseif(!$agent->isAgent()){
			return 1;
		}else{
			if ($id == false){
				return 1; # user level by default
			}else{
				if ($agent->categories()->where('id',$id)->count()==1){
					return 2;
				}else{
					return 1;
				}
			}
		}
	}	
	
	/**
     * Check if user has manage permissions on a ticket.
     *
     * @param int $id ticket id
     *
     * @return bool
     */
	public static function canManageTicket($id)
	{
		if (!auth()->check()) return false;
		$agent = Agent::find(auth()->user()->id);
		
		if ($agent->isAdmin()){
			return true;
		}elseif ($ticket = Ticket::find($id) ){
			if ($agent->id == $ticket->agent_id or (Setting::grab('agent_restrict') == 0 and $agent->categories()->where('id',$ticket->category_id)->count() == 1)) {
				return true;
			}
		}
		
		return false;
	}
	
	/**
     * Check if user has close permissions on a ticket.
     *
     * @param int $id ticket id
     *
     * @return bool
     */
	public static function canCloseTicket($id)
	{
		if (!auth()->check()) return false;
		$agent = Agent::find(auth()->user()->id);
		
		$a_perm = Setting::grab('close_ticket_perm');

        if ($agent->isAdmin() && $a_perm['admin'] == 'yes') {
            return true;
        }
        if ($ticket = Ticket::find($id)){
			if ($agent->id == $ticket->agent_id or (Setting::grab('agent_restrict') == 0 and $agent->categories()->where('id',$ticket->category_id)->count() == 1)) {
				return true;
			}
		}
		
        if ($agent->isTicketOwner($id) && $a_perm['owner'] == 'yes') {
            return true;
        }
		
		return false;
	}
	
	/**
     * Check if user can view new tickets button.
     *
     * @param int $id ticket id
     *
     * @return bool
     */
	public static function canViewNewTickets()
	{
		if (!auth()->check()) return false;
		$agent = Agent::find(auth()->user()->id);
		
		if ($agent->isAdmin()){
			return true;
		}elseif($agent->isAgent() and $agent->currentLevel() == 2){		
			if(Setting::grab('agent_restrict')==1){
				return $agent->categories()->wherePivot('autoassign','1')->count()==0 ? false : true;			
			}else{
				return true;
			}
		}else{
			return false;
		}
	}

	/**
     * Get directly associated department (ticketit_department)
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userDepartment()
    {
        return $this->belongsTo('Kordy\Ticketit\Models\Department', 'ticketit_department', 'id');
    }
	
	/**
     * Get associated department list through person_id
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
	public function personDepts()
	{
		return $this->HasMany('Kordy\Ticketit\Models\DepartmentPerson', 'person_id', 'person_id');
	}	
	
    /**
     * Get related categories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->belongsToMany('Kordy\Ticketit\Models\Category', 'ticketit_categories_users', 'user_id', 'category_id')->withPivot('autoassign')->orderBy('name');
    }

    /**
     * Get related agent tickets (To be deprecated).
     */
    public function agentTickets($complete = false)
    {
        if ($complete) {
            return $this->hasMany('Kordy\Ticketit\Models\Ticket', 'agent_id')->whereNotNull('completed_at');
        } else {
            return $this->hasMany('Kordy\Ticketit\Models\Ticket', 'agent_id')->whereNull('completed_at');
        }
    }

    /**
     * Get related user tickets (To be deprecated).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function userTickets($complete = false)
    {
        if ($complete) {
            return $this->hasMany('Kordy\Ticketit\Models\Ticket', 'user_id')->whereNotNull('completed_at');
        } else {
            return $this->hasMany('Kordy\Ticketit\Models\Ticket', 'user_id')->whereNull('completed_at');
        }
    }

    public function tickets($complete = false)
    {
        if ($complete) {
            return $this->hasMany('Kordy\Ticketit\Models\Ticket', 'user_id')->whereNotNull('completed_at');
        } else {
            return $this->hasMany('Kordy\Ticketit\Models\Ticket', 'user_id')->whereNull('completed_at');
        }
    }

    public function allTickets($complete = false) // (To be deprecated)
    {
        if ($complete) {
            return Ticket::whereNotNull('completed_at');
        } else {
            return Ticket::whereNull('completed_at');
        }
    }

    public function getTickets($complete = false) // (To be deprecated)
    {
        $user = self::find(auth()->user()->id);

        if ($user->isAdmin()) {
            $tickets = $user->allTickets($complete);
        } elseif ($user->isAgent()) {
            $tickets = $user->agentTickets($complete);
        } else {
            $tickets = $user->userTickets($complete);
        }

        return $tickets;
    }

    /**
     * Get related agent total tickets.
     */
    public function agentTotalTickets()
    {
        return $this->hasMany('Kordy\Ticketit\Models\Ticket', 'agent_id');
    }

    /**
     * Get related agent Completed tickets.
     */
    public function agentCompleteTickets()
    {
        return $this->hasMany('Kordy\Ticketit\Models\Ticket', 'agent_id')->whereNotNull('completed_at');
    }

    /**
     * Get related agent tickets.
     */
    public function agentOpenTickets()
    {
        return $this->hasMany('Kordy\Ticketit\Models\Ticket', 'agent_id')->whereNull('completed_at');
    }

    /**
     * Get related user total tickets.
     */
    public function userTotalTickets()
    {
        return $this->hasMany('Kordy\Ticketit\Models\Ticket', 'user_id');
    }

    /**
     * Get related user Completed tickets.
     */
    public function userCompleteTickets()
    {
        return $this->hasMany('Kordy\Ticketit\Models\Ticket', 'user_id')->whereNotNull('completed_at');
    }

    /**
     * Get related user tickets.
     */
    public function userOpenTickets()
    {
        return $this->hasMany('Kordy\Ticketit\Models\Ticket', 'user_id')->whereNull('completed_at');
    }

    /**
     * Get all Visible agents for current user.
     *
     * @param $query
     * @param $id
     *
     * @return mixed
     */
    public function scopeVisible($query)
    {
        $query = $query->agents();

        if (auth()->user()->ticketit_admin) {
            return $query->orderBy('name', 'ASC');
        } elseif (auth()->user()->ticketit_agent) {
            return $query->VisibleForAgent(auth()->user()->id);
        } else {
            return $query->where('1', '=', '0');
        }
    }

    /**
     * Get all agents from the categories where Agent $id belongs to.
     *
     * @param $query
     * @param $id
     *
     * @return mixed
     */
    public function scopeVisibleForAgent($query, $id)
    {
        $agent = Agent::findOrFail($id);
		
		if ($agent->currentLevel() == 2) {
			// Depends on agent_restrict
			if (Setting::grab('agent_restrict') == 0) {
				return $query->whereHas('categories', function ($q1) use ($id) {
					$q1->whereHas('agents', function ($q2) use ($id) {
						$q2->where('id', $id);
					});
				})->orderBy('name', 'ASC');
			} else {
				return $query->where('id', $id);
			}
		}else{
			return false;
		}
    }
	
	/**
     * Get array with all user id's from the departments where current user belongs and users that have ticketit_department = 0
     *
     * @return array
     */
    public function getMyNoticesUsers()
    {
		$user = self::find(auth()->user()->id);
		
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
		
		return $related_users;
	}
}
