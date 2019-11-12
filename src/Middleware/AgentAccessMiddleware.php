<?php

namespace PanicHD\PanicHD\Middleware;

use Closure;
use PanicHD\PanicHD\Models\Setting;
use PanicHD\PanicHD\Models\Ticket;
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
        $member = \PanicHDMember::findOrFail(auth()->user()->id);

        // Granted to all Admins
        if ($member->isAdmin()) {
            return $next($request);
        }

        // Get Ticket from route
        $ticket = $this->getRouteTicket($request);

        if (!$ticket) {
            if ($request->filled('ticket_id')) {
                // Get ticket from Request
                $ticket = Ticket::find($request->ticket_id);
            }
        }

        if ($ticket and $member->isAgent()) {
            // Assigned Agent has access always
            if ($member->isAssignedAgent($ticket->id)) {
                return $next($request);
            }

            if ($member->currentLevel() > 1 and Setting::grab('agent_restrict') == 0) {
                // Check if element is a visible item for this agent
                if ($member->categories()->where('id', $ticket->category->id)->count() == 1) {
                    return $next($request);
                }
            }
        }

        return redirect()->action('\PanicHD\PanicHD\Controllers\TicketsController@index')
            ->with('warning', trans('panichd::lang.you-are-not-permitted-to-access'));
    }
}
