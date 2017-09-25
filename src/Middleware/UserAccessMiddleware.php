<?php

namespace Kordy\Ticketit\Middleware;

use Closure;
use Kordy\Ticketit\Models\Agent;
use Kordy\Ticketit\Models\Setting;
use Kordy\Ticketit\Traits\RouteTicketId;

class UserAccessMiddleware 
{
    use RouteTicketId;
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
		$ticket = $this->routeTicketId($request);
		
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
		
		// Tickets from users in a visible ticketit_department value for current user
		if (in_array($ticket->user_id, $agent->getMyNoticesUsers())){
			return $next($request);
		} 
		
        return redirect()->action('\Kordy\Ticketit\Controllers\TicketsController@index')
            ->with('warning', trans('ticketit::lang.you-are-not-permitted-to-access'));
    }
}