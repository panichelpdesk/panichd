<?php

namespace PanicHD\PanicHD\Middleware;

use Closure;
use PanicHD\PanicHD\Models\Setting;
use PanicHD\PanicHD\Traits\TicketRoutes;

class UserAccessMiddleware
{
    use TicketRoutes;

    /**
     * Session user has at least user level on route or specified resource.
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

        // Get Ticket instance. Fails if not found
        $ticket = $this->getRouteTicket($request);

        // Ticket Owner has access
        if ($member->isTicketOwner($ticket->id) and !$ticket->hidden) {
            return $next($request);
        }

        if ($member->isAgent()) {
            // Assigned Agent has access always
            if ($member->isAssignedAgent($ticket->id)) {
                return $next($request);
            }

            if ($member->currentLevel() > 1 and Setting::grab('agent_restrict') == 0) {
                // Check if element is a visible item for this agent
                if ($member->categories()->where('id', $ticket->category_id)->count() == 1) {
                    return $next($request);
                }
            }
        }

        // Notified users may access the ticket
        if ($ticket->commentNotifications()->where('member_id', $member->id)->count() > 0) {
            return $next($request);
        }

        if ($this->mod_route_prefix == 'ticket') {
            // Tickets from users in a visible ticketit_department value for current user
            if (in_array($ticket->user_id, $member->getMyNoticesUsers())) {
                return $next($request);
            }
        }

        return redirect()->action('\PanicHD\PanicHD\Controllers\TicketsController@index')
            ->with('warning', trans('panichd::lang.you-are-not-permitted-to-access'));
    }
}
