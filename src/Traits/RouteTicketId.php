<?php

namespace Kordy\Ticketit\Traits;

use Kordy\Ticketit\Helpers\LaravelVersion;
use Kordy\Ticketit\Models\Attachment;
use Kordy\Ticketit\Models\Comment;
use Kordy\Ticketit\Models\Setting;
use Kordy\Ticketit\Models\Ticket;

trait RouteTicketId
{
    /**
     * Returns an array with both filtered and excluded html.
     *
     * @return string
     */
    public function routeTicketId($request)
    {
        $a_route = explode('.', $request->route()->getName());
		$route_prefix = current($a_route);
		$route_suffix = last($a_route);
		
        if ($route_prefix == Setting::grab('main_route')) {
            // Attachment routes
			if (in_array($route_suffix, ['download-attachment', 'view-attachment'])){
				$attachment = $request->route('attachment');
				$ticket_id = Attachment::findOrFail($attachment)->ticket_id;

			}else{
				// Ticket routes: Get ticket_id
				if (LaravelVersion::lt('5.2.0')) {
					$ticket_id = $request->route(Setting::grab('main_route'));
				} else {
					$ticket_id = $request->route('ticket');
				}
			}

		// Comment routes
        }elseif ($route_prefix == Setting::grab('main_route').'-comment') {
            if (isset($request->ticket_comment)){
				$comment = Comment::findOrFail($request->ticket_comment);
				$ticket_id = $comment->ticket_id;
			}else{
				$ticket_id = $request->get('ticket_id');
			}
			
        }
	
		return Ticket::findOrFail($ticket_id);
    }
}