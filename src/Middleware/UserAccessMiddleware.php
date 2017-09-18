<?php

namespace Kordy\Ticketit\Middleware;

use Closure;
use Kordy\Ticketit\Helpers\LaravelVersion;
use Kordy\Ticketit\Models\Agent;
use Kordy\Ticketit\Models\Attachment;
use Kordy\Ticketit\Models\Setting;
use Kordy\Ticketit\Models\Ticket;

class UserAccessMiddleware
{
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
        if (!auth()->check()) {
			abort(404);
		}
		
		$agent = Agent::findOrFail(auth()->user()->id);
		
		// Granted to all Admins		
		if ($agent->isAdmin()) {
            return $next($request);
        }	
		
		$a_route = explode('.', $request->route()->getName());
		$route_prefix = current($a_route);
		$route_suffix = last($a_route);
		
		// Ticket routes: Get ticket_id
        if ($route_prefix == Setting::grab('main_route')) {
            if (in_array($route_suffix, ['download-attachment', 'view-attachment'])){				
				$attachment = $request->route('attachment');
				$ticket_id = Attachment::findOrFail($attachment)->ticket_id;
				
			}else{
				if (LaravelVersion::lt('5.2.0')) {
					$ticket_id = $request->route(Setting::grab('main_route'));
				} else {
					$ticket_id = $request->route('ticket');
				}
			}

		// Comment routes
        }elseif ($route_prefix == Setting::grab('main_route').'-comment') {
            $ticket_id = $request->get('ticket_id');
        }/* 
		
		
		ATTACHMENT ROUTES 
		
		
		
		*/
		
		if (isset($ticket_id)){
			$ticket = Ticket::findOrFail($ticket_id);
			
			// Ticket Owner has access
			if ($agent->isTicketOwner($ticket_id)) {
				return $next($request);
			}						
			
			if ($agent->isAgent()) {
				// Assigned Agent has access always
				if ($agent->isAssignedAgent($ticket_id)){
					return $next($request);
				}
				
				if ($agent->currentLevel() > 1 and Setting::grab('agent_restrict') == 0){
					// Check if element is a visible item for this agent
					if ($agent->categories()->where('id',$ticket->category_id)->count() == 1){
						return $next($request);
					}
				}
			}
		}
		
        return redirect()->action('\Kordy\Ticketit\Controllers\TicketsController@index')
            ->with('warning', trans('ticketit::lang.you-are-not-permitted-to-access'));
    }
}
