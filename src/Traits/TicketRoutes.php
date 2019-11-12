<?php

namespace PanicHD\PanicHD\Traits;

use Illuminate\Http\Request;
use PanicHD\PanicHD\Helpers\LaravelVersion;
use PanicHD\PanicHD\Models\Attachment;
use PanicHD\PanicHD\Models\Category;
use PanicHD\PanicHD\Models\Comment;
use PanicHD\PanicHD\Models\Setting;
use PanicHD\PanicHD\Models\Ticket;

trait TicketRoutes
{
    protected $mod_route_prefix;
    protected $mod_route_suffix;
    protected $route_ticket;

    public function __construct(Request $request)
    {
        $a_route = explode('.', $request->route()->getName());

        // current($a_route) == Route prefix
        switch (current($a_route)) {
            case Setting::grab('main_route'):
                $this->mod_route_prefix = 'ticket';
                break;
            case Setting::grab('main_route').'-comment':
                $this->mod_route_prefix = 'comment';
                break;
            default:
                $this->mod_route_prefix = current($a_route);
        }

        // last($a_route) == Route suffix
        if (in_array(last($a_route), ['download-attachment', 'view-attachment'])) {
            $this->mod_route_suffix = 'get-attachment';
        } else {
            $this->mod_route_suffix = last($a_route);
        }

        $this->route_ticket = false;
    }

    /**
     * Returns related ticket instance to current route.
     *
     * @return PanicHD\PanicHD\Models\Ticket
     */
    public function getRouteTicket($request)
    {
        if ($this->mod_route_prefix == 'ticket') {
            // Attachment routes
            if ($this->mod_route_suffix == 'get-attachment') {
                $attachment = $request->route('attachment');
                $ticket_id = Attachment::findOrFail($attachment)->ticket_id;
            } else {
                // Ticket routes: Get ticket_id
                if (LaravelVersion::lt('5.2.0')) {
                    $ticket_id = $request->route(Setting::grab('main_route'));
                } else {
                    $ticket_id = $request->route('ticket');
                }
            }

            // Comment routes
        } elseif ($this->mod_route_prefix == 'comment') {
            if (isset($request->ticket_comment)) {
                $comment = Comment::findOrFail($request->ticket_comment);
                $ticket_id = $comment->ticket_id;
            } else {
                $ticket_id = $request->get('ticket_id');
            }
        } else {
            return false;
        }

        $this->route_ticket = Ticket::findOrFail($ticket_id);

        return $this->route_ticket;
    }

    /**
     * Returns related category instance to current route.
     *
     * @return PanicHD\PanicHD\Models\Category
     */

    // TODO: DEPRECATED
    public function getRouteCategory($request)
    {
        if ($request->input('category_id') != '') {
            $category_id = $request->category_id;
        } else {
            if ($this->route_ticket) {
                $category_id = $this->route_ticket->category_id;
            } else {
                $ticket = $this->getRouteTicket($request);

                if ($ticket and !is_null($ticket)) {
                    $this->route_ticket = $ticket;
                    $category_id = $this->route_ticket->category_id;
                } else {
                    return false;
                }
            }
        }

        $cat = Category::findOrFail($category_id);

        return $cat;
    }
}
