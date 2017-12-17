<?php

namespace PanicHD\PanicHD\Middleware;

use Closure;
use PanicHD\PanicHD\Models\Agent;
use PanicHD\PanicHD\Models\Setting;
use PanicHD\PanicHD\Traits\TicketRoutes;

class AgentAccessMiddleware
{
    use TicketRoutes;
	/**
     * Run the request filter.
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
		
		if ($agent->isAgent()) {
			// Assigned Agent has access always
			if ($agent->isAssignedAgent($ticket->id)){
				return $next($request);
			}
			
			if ($agent->currentLevel() > 1 and Setting::grab('agent_restrict') == 0){
				// Check if element is a visible item for this agent
				if ($agent->categories()->where('id',$this->getRouteCategory($request)->id)->count() == 1){
					return $next($request);
				}
			}
		}		

        return redirect()->action('\PanicHD\PanicHD\Controllers\TicketsController@index')
            ->with('warning', trans('panichd::lang.you-are-not-permitted-to-access'));
    }
}
