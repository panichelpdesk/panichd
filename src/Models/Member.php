<?php

namespace PanicHD\PanicHD\Models;

use App\User;
use PanicHD\PanicHD\Helpers\LaravelVersion;
use PanicHD\PanicHD\Models\Category;

class Member extends User
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
            return $query->where('panichd_agent', '1')->paginate($paginate, ['*'], 'agents_page');
        } else {
            return $query->where('panichd_agent', '1');
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
            return $query->where('panichd_admin', '1')->paginate($paginate, ['*'], 'admins_page');
        } else {
            return $query->where('panichd_admin', '1')->get();
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
            return $query->where('panichd_agent', '0')->paginate($paginate, ['*'], 'users_page');
        } else {
            return $query->where('panichd_agent', '0')->get();
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
            return $query->where('panichd_agent', '1')->pluck('name', 'id')->toArray();
        } else { // if Laravel 5.1
            return $query->where('panichd_agent', '1')->lists('name', 'id')->toArray();
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
            $user = \PanicHDMember::find($id);
            if ($user->panichd_agent) {
                return true;
            }

            return false;
        }
        if (auth()->check()) {
            if (auth()->user()->panichd_agent) {
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
        return auth()->check() && auth()->user()->panichd_admin;
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
        if (auth()->check() && auth()->user()->panichd_agent) {
            if (auth()->user()->id == Ticket::find($id)->agent->id) {
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
            if (auth()->user()->id == Ticket::find($id)->user_id) {
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
		$member = \PanicHDMember::find(auth()->user()->id);
		if ($member->isAdmin()){
			return 3;
		}elseif($member->isAgent()){
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
		$member = \PanicHDMember::find(auth()->user()->id);
		if ($member->isAdmin()){
			return 3;
		}elseif($member->isAgent()){
			if (session()->exists('panichd_filter_currentLevel') and session('panichd_filter_currentLevel')==1){
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
	public function levelInCategory($id = false)
	{
		if ($this->panichd_admin){ # avoiding ->isAdmin()
			return 3;
		}elseif(!$this->panichd_agent){ # avoiding ->isAgent()
			return 1;
		}else{
			if ($id == false){
				return 1; # user level by default
			}else{
				if ($this->categories()->where('id',$id)->count()==1){
					return 2;
				}else{
					return 1;
				}
			}
		}
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
		$member = \PanicHDMember::find(auth()->user()->id);

		$a_perm = Setting::grab('close_ticket_perm');

        if ($member->isAdmin() && $a_perm['admin'] == 'yes') {
            return true;
        }
        if ($ticket = Ticket::find($id)){
			if ($member->id == $ticket->agent_id or (Setting::grab('agent_restrict') == 0 and $member->categories()->where('id',$ticket->category_id)->count() == 1)) {
				return true;
			}
		}

        if ($member->isTicketOwner($id) && $a_perm['owner'] == 'yes') {
            return true;
        }

		return false;
	}

	/**
     * Check if user can make a comment on a ticket.
     *
     * @param $ticket instance of PanicHD\PanicHD\Models\Ticket
     *
     * @return bool
     */
	public static function canCommentTicket($ticket)
	{
		if (!auth()->check()) return false;

		if (\PanicHDMember::canManageTicket($ticket->id)
            or \PanicHDMember::isTicketOwner($ticket->id)
            or $ticket->commentNotifications()->where('member_id', auth()->user()->id)->count() > 0){

            return true;
		}

        return false;
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
		$member = \PanicHDMember::find(auth()->user()->id);

		if ($member->isAdmin()){
			return true;
		}elseif ($ticket = Ticket::find($id) ){
			if ($member->id == $ticket->agent_id or (Setting::grab('agent_restrict') == 0 and $member->categories()->where('id',$ticket->category_id)->count() == 1)) {
				return true;
			}
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
		$member = \PanicHDMember::find(auth()->user()->id);

		if ($member->isAdmin()){
			return true;
		}elseif($member->isAgent() and $member->currentLevel() == 2){
			if(Setting::grab('agent_restrict')==1){
				return $member->categories()->wherePivot('autoassign','1')->count()==0 ? false : true;
			}else{
				return true;
			}
		}else{
			return false;
		}
	}

	/**
     * Get member group
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
	public function group()
	{
		return $this->belongsTo('PanicHD\PanicHD\Models\Group', 'panichd_group_id');
	}
	
	/**
     * Get member associated notice group
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
	public function noticeGroup()
	{
		return $this->belongsTo('PanicHD\PanicHD\Models\Group', 'panichd_notice_group_id');
	}

    /**
     * Get related categories.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function categories()
    {
        return $this->belongsToMany('PanicHD\PanicHD\Models\Category', 'panichd_categories_users', 'user_id', 'category_id')->withPivot('autoassign')->orderBy('name');
    }

	/**
	 * Get categories where user has permission to create new tickets
	 *
	 * @return Array
	*/
	public function getNewTicketCategories()
	{
		if ($this->isAdmin()){
			$categories = Category::orderBy('name');
		}else{
			$categories = Category::where('create_level', '1')->orderBy('name')->get();

			if ($this->isAgent() and $this->currentLevel() == 2){
				$create_level_2 = $this->categories()->where('create_level', '2')->get();

				$categories = $categories->merge($create_level_2)->sortBy('name');

			}
		}

		if (LaravelVersion::min('5.3.0')) {
            return $categories->pluck('name', 'id');
        } else {
            return $categories->lists('name', 'id');
        }
	}

	/**
	 * Get categories where user has permission to edit tickets
	 *
	 * @return Array
	*/
	public function getEditTicketCategories()
	{
		if ($this->isAdmin()){
			$categories = Category::orderBy('name');
		}elseif($this->isAgent() and $this->currentLevel() == 2){
			$categories = $this->categories()->where('create_level', '<=', '2')->orderBy('name');
		}else{
			return [];
		}

		if (LaravelVersion::min('5.3.0')) {
            return $categories->pluck('name', 'id');
        } else {
            return $categories->lists('name', 'id');
        }
	}

    /**
     * Get related agent tickets (To be deprecated).
     */
    public function agentTickets($complete = false)
    {
        if ($complete) {
            return $this->hasMany('PanicHD\PanicHD\Models\Ticket', 'agent_id')->whereNotNull('completed_at');
        } else {
            return $this->hasMany('PanicHD\PanicHD\Models\Ticket', 'agent_id')->whereNull('completed_at');
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
            return $this->hasMany('PanicHD\PanicHD\Models\Ticket', 'user_id')->whereNotNull('completed_at');
        } else {
            return $this->hasMany('PanicHD\PanicHD\Models\Ticket', 'user_id')->whereNull('completed_at');
        }
    }

	/**
     * Get ALL member tickets
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function tickets($complete = false)
    {
        return $this->hasMany('PanicHD\PanicHD\Models\Ticket', 'user_id');
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
        return $this->hasMany('PanicHD\PanicHD\Models\Ticket', 'agent_id');
    }

    /**
     * Get related agent Completed tickets.
     */
    public function agentCompleteTickets()
    {
        return $this->hasMany('PanicHD\PanicHD\Models\Ticket', 'agent_id')->whereNotNull('completed_at');
    }

    /**
     * Get related agent tickets.
     */
    public function agentOpenTickets()
    {
        return $this->hasMany('PanicHD\PanicHD\Models\Ticket', 'agent_id')->whereNull('completed_at');
    }

    /**
     * Get related user Completed tickets.
     */
    public function userCompleteTickets()
    {
        return $this->hasMany('PanicHD\PanicHD\Models\Ticket', 'user_id')->whereNotNull('completed_at');
    }

    /**
     * Get related user tickets.
     */
    public function userOpenTickets()
    {
        return $this->hasMany('PanicHD\PanicHD\Models\Ticket', 'user_id')->whereNull('completed_at');
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

        if (auth()->user()->panichd_admin) {
            return $query->orderBy('name', 'ASC');
        } elseif (auth()->user()->panichd_agent) {
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
        $member = \PanicHDMember::findOrFail($id);

		if ($member->currentLevel() == 2) {
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
     * Get array with all user id's from the departments where current user belongs and users that have panichd_notice_group_id = 0
     *
     * @return array
     */
    public function getMyNoticesUsers()
    {
		// Get my related departments
		$related_departments = [];

        $c_related = $this->getRelatedDepartments();

        if (!$c_related) return [];

        foreach ($c_related as $rel){
			$related_departments [] = $rel->id;
		}


		/*
		 *	Get related Departamental users from my related departments
		 *
		 * Conditions:
		 *    - agent panichd_notice_group_id in related_departments
		 *    - agent person in related_departments
		*/
		$related_users = \PanicHDMember::where('id','!=',$this->id)
			->whereIn('panichd_notice_group_id', $related_departments);

		// Get users that are visible by all departments
		$all_dept_users = \PanicHDMember::where('panichd_notice_group_id','0');

		if (version_compare(app()->version(), '5.3.0', '>=')) {
			$related_users = $related_users->pluck('id')->toArray();
			$related_users = array_unique(array_merge($related_users, $all_dept_users->pluck('id')->toArray()));
		}else{
			$related_users = $related_users->lists('id')->toArray();
			$related_users = array_unique(array_merge($related_users, $all_dept_users->lists('id')->toArray()));
		}

		return $related_users;
	}

	/*
	 * Get all groups in member Group hierarchy
	 *
	 * For a main group: Returns self + all descendants
	 * For descendant: Returns self + ancestor
	 *
	 * @Return collection
	*/
	public function getRelatedDepartments()
	{
        if (is_null($this->group)) return [];

        $member_department = $this->group()->get();

		if ($this->group()->first()->is_main()){
			return $member_department->merge($this->group()->first()->descendants()->get());
		}else{
			return $member_department->merge($this->group()->first()->ancestor()->get());
		}
	}
}
