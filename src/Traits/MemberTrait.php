<?php

namespace PanicHD\PanicHD\Traits;

use PanicHD\PanicHD\Helpers\LaravelVersion;

trait MemberTrait
{
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
     * Check if auth() member is agent.
     *
     * @return bool
     */
    public function isAgent()
    {
        return $this->panichd_agent;
    }

    /**
     * Check if auth() member is admin.
     *
     * @return bool
     */
    public function isAdmin()
    {
        return $this->panichd_admin;
    }

    /**
     * Check if user is the assigned agent for a ticket.
     *
     * @param int $id ticket id
     *
     * @return bool
     */
    public function isAssignedAgent($id)
    {
        if ($this->panichd_agent) {
            if ($this->id == \PanicHD\PanicHD\Models\Ticket::find($id)->agent->id) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user is the owner for a ticket.
     *
     * @param int $id ticket id
     *
     * @return bool
     */
    public function isTicketOwner($id)
    {
        if ($this->id == \PanicHD\PanicHD\Models\Ticket::find($id)->user_id) {
            return true;
        }

        return false;
    }

    /**
     * Get general maximum permission level for current user.
     *
     * @param int $id category id
     *
     * @return int
     */
    public function maxLevel()
    {
        if ($this->isAdmin()) {
            return 3;
        } elseif ($this->isAgent()) {
            return 2;
        } else {
            return 1;
        }
    }

    /**
     * Get current permission level for user.
     *
     * @param int $id category id
     *
     * @return int
     */
    public function currentLevel()
    {
        if ($this->isAdmin()) {
            return 3;
        } elseif ($this->isAgent()) {
            if (session()->exists('panichd_filter_currentLevel') and session('panichd_filter_currentLevel') == 1) {
                return 1;
            } else {
                return 2;
            }
        } else {
            return 1;
        }
    }

    /**
     * Get permission level for specified category.
     *
     * @param int $id category id
     *
     * @return int
     */
    public function levelInCategory($id = false)
    {
        if ($this->panichd_admin) { // avoiding ->isAdmin()
            return 3;
        } elseif (!$this->panichd_agent) { // avoiding ->isAgent()
            return 1;
        } else {
            if ($id == false) {
                return 1; // user level by default
            } else {
                if ($this->categories()->where('id', $id)->count() == 1) {
                    return 2;
                } else {
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
    public function canCloseTicket($id)
    {
        $a_perm = \PanicHD\PanicHD\Models\Setting::grab('close_ticket_perm');

        if ($this->isAdmin() && $a_perm['admin'] == 'yes') {
            return true;
        }
        if ($ticket = \PanicHD\PanicHD\Models\Ticket::find($id)) {
            if ($this->id == $ticket->agent_id or \PanicHD\PanicHD\Models\Setting::grab('agent_restrict') == 0 and $this->categories()->where('id', $ticket->category_id)->count() == 1) {
                return true;
            }
        }

        if ($this->isTicketOwner($id) && $a_perm['owner'] == 'yes') {
            return true;
        }

        return false;
    }

    /**
     * Check if user can make a comment on a ticket.
     *
     * @param $ticket instance of \PanicHD\PanicHD\Models\Ticket
     *
     * @return bool
     */
    public function canCommentTicket($ticket)
    {
        if ($this->canManageTicket($ticket->id)
            or $this->isTicketOwner($ticket->id)
            or $ticket->commentNotifications()->where('member_id', $this->id)->count() > 0) {
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
    public function canManageTicket($id)
    {
        if ($this->isAdmin()) {
            return true;
        } elseif ($ticket = \PanicHD\PanicHD\Models\Ticket::find($id)) {
            if ($this->id == $ticket->agent_id or \PanicHD\PanicHD\Models\Setting::grab('agent_restrict') == 0 and $this->categories()->where('id', $ticket->category_id)->count() == 1) {
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
    public function canViewNewTickets()
    {
        if ($this->isAdmin()) {
            return true;
        } elseif ($this->isAgent() and $this->currentLevel() == 2) {
            if (\PanicHD\PanicHD\Models\Setting::grab('agent_restrict') == 1) {
                return $this->categories()->wherePivot('autoassign', '1')->count() == 0 ? false : true;
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

    /**
     * Get directly associated department (ticketit_department).
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function userDepartment()
    {
        return $this->belongsTo('PanicHD\PanicHD\Models\Department', 'ticketit_department', 'id');
    }

    /**
     * Get associated department list through person_id.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function department()
    {
        return $this->belongsTo('PanicHD\PanicHD\Models\Department');
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
     * Get categories where user has permission to create new tickets.
     *
     * @return array
     */
    public function getNewTicketCategories()
    {
        if ($this->isAdmin()) {
            $categories = \PanicHD\PanicHD\Models\Category::orderBy('name');
        } else {
            $categories = \PanicHD\PanicHD\Models\Category::where('create_level', '1')->orderBy('name')->get();

            if ($this->isAgent() and $this->currentLevel() == 2) {
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
     * Get categories where user has permission to edit tickets.
     *
     * @return array
     */
    public function getEditTicketCategories()
    {
        if ($this->isAdmin()) {
            $categories = \PanicHD\PanicHD\Models\Category::orderBy('name');
        } elseif ($this->isAgent() and $this->currentLevel() == 2) {
            $categories = $this->categories()->where('create_level', '<=', '2')->orderBy('name');
        } else {
            return [];
        }

        if (LaravelVersion::min('5.3.0')) {
            return $categories->pluck('name', 'id');
        } else {
            return $categories->lists('name', 'id');
        }
    }

    /**
     * Get related tickets as agent.
     */
    public function ticketsAsAgent()
    {
        return $this->hasMany('PanicHD\PanicHD\Models\Ticket', 'agent_id');
    }

    /**
     * Get related owner tickets.
     */
    public function ticketsAsOwner()
    {
        return $this->hasMany('PanicHD\PanicHD\Models\Ticket', 'user_id');
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

        $member = \PanicHDMember::findOrFail(auth()->user()->id);

        if ($member->panichd_admin) {
            return $query->orderBy('name', 'ASC');
        } elseif ($member->panichd_agent) {
            return $query->VisibleForAgent($member->id);
        } else {
            return $query->where('id', '0');
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
        $member = \PanicHDMember::findOrFail(auth()->user()->id);

        if ($member->currentLevel() == 2) {
            // Depends on agent_restrict
            if (\PanicHD\PanicHD\Models\Setting::grab('agent_restrict') == 0) {
                return $query->whereHas('categories', function ($q1) use ($id) {
                    $q1->whereHas('agents', function ($q2) use ($id) {
                        $q2->where('id', $id);
                    });
                })->orderBy('name', 'ASC');
            } else {
                return $query->where('id', $id);
            }
        } else {
            return $query->where('id', '0');
        }
    }

    /**
     * Get array with all user id's from the departments where current user belongs and users that have ticketit_department = 0.
     *
     * @return array
     */
    public function getMyNoticesUsers()
    {
        // Get my related departments
        $related_departments = [];

        $c_related = $this->getRelatedDepartments();

        if (!$c_related) {
            return [];
        }

        foreach ($c_related as $rel) {
            $related_departments[] = $rel->id;
        }

        /*
         *	Get related Departamental users from my related departments
         *
         * Conditions:
         *    - agent ticketit_department in related_departments
         *    - agent person in related_departments
        */
        $related_users = \PanicHDMember::where('id', '!=', $this->id)
            ->whereIn('ticketit_department', $related_departments);

        // Get users that are visible by all departments
        $all_dept_users = \PanicHDMember::where('ticketit_department', '0');

        if (version_compare(app()->version(), '5.3.0', '>=')) {
            $related_users = $related_users->pluck('id')->toArray();
            $related_users = array_unique(array_merge($related_users, $all_dept_users->pluck('id')->toArray()));
        } else {
            $related_users = $related_users->lists('id')->toArray();
            $related_users = array_unique(array_merge($related_users, $all_dept_users->lists('id')->toArray()));
        }

        return $related_users;
    }

    /*
     * Get member related departments in Department hierarchy
     *
     * For a main department: Returns self + all descendants
     * For descendant: Returns self + ancestor
     *
     * @Return collection
    */
    public function getRelatedDepartments()
    {
        if (is_null($this->department)) {
            return [];
        }

        $member_department = $this->department()->first();

        if ($member_department->is_main()) {
            return $member_department->get()->merge($member_department->descendants()->get());
        } else {
            return $member_department->get()->merge($member_department->ancestor()->get());
        }
    }
}
