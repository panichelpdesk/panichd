<?php

namespace PanicHD\PanicHD\Middleware;

use Closure;
use PanicHD\PanicHD\Models\Agent;
use PanicHD\PanicHD\Models\Setting;
use PanicHD\PanicHD\Traits\TicketRoutes;

class UserAccessMiddleware 
{
    use TicketRoutes;
	/**
     * Session user has at least user level on route or specified resource
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure                 $next
     *
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
		$agent = Agent::findOrFail(auth()->user()->id);
		
		// Granted to all Admins		
		if ($agent->isAdmin()) {
            return $next($request);
        }
		
		// Get Ticket instance. Fails if not found
		$ticket = $this->getRouteTicket($request);
		
		// Ticket Owner has access
		if ($agent->isTicketOwner($ticket->id)) {
			return $next($request);
		}

		if ($agent->isAgent()) {
			// Assigned Agent has access always
			if ($agent->isAssignedAgent($ticket->id)){
				return $next($request);
			}
			
			if ($agent->currentLevel() > 1 and Setting::grab('agent_restrict') == 0){
				// Check if element is a visible item for this agent
				if ($agent->categories()->where('id',$ticket->category_id)->count() == 1){
					return $next($request);
				}
			}
		}
		
		// Disable comment store for foreign user
		if ($this->mod_route_prefix != 'comment') {
			// Tickets from users in a visible ticketit_department value for current user
			if (in_array($ticket->user_id, $agent->getMyNoticesUsers())){
				return $next($request);
			}
		} 
		
        return redirect()->action('\PanicHD\PanicHD\Controllers\TicketsController@index')
            ->with('warning', trans('panichd::lang.you-are-not-permitted-to-access'));
    }
}