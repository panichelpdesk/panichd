<?php

namespace PanicHD\PanicHD\Tests;

use PanicHD\PanicHD\Models\Ticket;

include 'PanicHDTestCase.php';

class TicketsTest extends PanicHDTestCase
{
    /*
     * Check that we don't have access without auth() Member
    */
    public function testNoAuth()
    {
        $this->load_vars();

        if ($this->status == 'Installed' and !is_null($this->main_route)) {
            // Main route
            $response = $this->get($this->main_route);
            $this->versionAssertRedirect($response, '/login');

            // Ticket creation
            $response = $this->get(route($this->main_route.'.create'));
            $this->versionAssertRedirect($response, '/login');
        }
    }

    /*
     * Access to the main route
    */
    public function testMainRoute()
    {
        $this->load_vars();

        if ($this->status == 'Installed' and !is_null($this->main_route)) {
            // Member access
            if (!is_null($this->member)) {
                $response = $this->actingAs($this->member)->get($this->main_route);
                $this->versionAssertStatus($response, 200);
            }

            // Agent access
            if (!is_null($this->agent)) {
                $response = $this->actingAs($this->agent)->get($this->main_route);
                $this->versionAssertStatus($response, 200);
            }

            // Admin access
            if (!is_null($this->admin)) {
                $response = $this->actingAs($this->admin)->get($this->main_route);
                $this->versionAssertStatus($response, 200);
            }
        }
    }

    /*
     * Ticket creation
    */
    public function testTicketCreate()
    {
        $this->load_vars();

        if ($this->status == 'Installed' and !is_null($this->main_route)) {
            // Member access
            if (!is_null($this->member)) {
                $response = $this->actingAs($this->member)->get(route($this->main_route.'.create'));
                $this->versionAssertStatus($response, 200);
            }

            // Agent access
            if (!is_null($this->agent)) {
                $response = $this->actingAs($this->agent)->get(route($this->main_route.'.create'));
                $this->versionAssertStatus($response, 200);
            }

            // Admin access
            if (!is_null($this->admin)) {
                $response = $this->actingAs($this->admin)->get(route($this->main_route.'.create'));
                $this->versionAssertStatus($response, 200);
            }
        }
    }

    /*
     * Access a Ticket Card
    */
    public function testTicketShowEdit()
    {
        $this->load_vars();

        if ($this->status == 'Installed' and !is_null($this->main_route)) {
            // Member access
            if (!is_null($this->member)) {
                // Visible ticket
                $ticket = clone $this->member_tickets_builder;
                $ticket = $ticket->notHidden()->first();
                $response = $this->actingAs($this->member)->get(route($this->main_route.'.show', ['ticket' => $ticket->id]));
                $this->versionAssertStatus($response, 200);
                /*
                // TODO: Ensure to generate a fake user that doesn't have edit permissions on the aimed ticket
                $response = $this->actingAs($this->member)->get(route($this->main_route . '.edit', ['ticket' => $ticket->id]));
                $this->versionAssertStatus($response, 302);*/

                // Hidden ticket without $this->member in notifications
                $ticket = clone $this->member_tickets_builder;
                $member = $this->member;
                $ticket = $ticket->hidden()
                    ->whereDoesntHave('commentNotifications', function ($query) use ($member) {
                        $query->where('member_id', $member->id);
                    })
                    ->first();
                if (!is_null($ticket)) {
                    $response = $this->actingAs($this->member)->get(route($this->main_route.'.show', ['ticket' => $ticket->id]));
                    $this->versionAssertStatus($response, 302);
                    $response = $this->actingAs($this->member)->get(route($this->main_route.'.edit', ['ticket' => $ticket->id]));
                    $this->versionAssertStatus($response, 302);
                }
            }

            // Agent access to assigned ticket
            if (!is_null($this->agent)) {
                $ticket = $this->agent->ticketsAsAgent()->inRandomOrder()->first();

                $response = $this->actingAs($this->agent)->get(route($this->main_route.'.show', ['ticket' => $ticket->id]));
                $this->versionAssertStatus($response, 200);
                $response = $this->actingAs($this->agent)->get(route($this->main_route.'.edit', ['ticket' => $ticket->id]));
                $this->versionAssertStatus($response, 200);
            }

            // Admin access
            if (!is_null($this->admin)) {
                $ticket_build = Ticket::inRandomOrder();

                // Newest ticket
                $build = clone $ticket_build;
                $ticket = $build->newest()->first();

                if (!is_null($ticket)) {
                    $response = $this->actingAs($this->admin)->get(route($this->main_route.'.show', ['ticket' => $ticket->id]));
                    $this->versionAssertStatus($response, 200);
                    $response = $this->actingAs($this->admin)->get(route($this->main_route.'.edit', ['ticket' => $ticket->id]));
                    $this->versionAssertStatus($response, 200);
                }

                // Active ticket
                $build = clone $ticket_build;
                $ticket = $build->active()->first();

                if (!is_null($ticket)) {
                    $response = $this->actingAs($this->admin)->get(route($this->main_route.'.show', ['ticket' => $ticket->id]));
                    $this->versionAssertStatus($response, 200);
                    $response = $this->actingAs($this->admin)->get(route($this->main_route.'.edit', ['ticket' => $ticket->id]));
                    $this->versionAssertStatus($response, 200);
                }

                // Complete ticket
                $build = clone $ticket_build;
                $ticket = $build->complete()->first();

                if (!is_null($ticket)) {
                    $response = $this->actingAs($this->admin)->get(route($this->main_route.'.show', ['ticket' => $ticket->id]));
                    $this->versionAssertStatus($response, 200);
                    $response = $this->actingAs($this->admin)->get(route($this->main_route.'.edit', ['ticket' => $ticket->id]));
                    $this->versionAssertStatus($response, 200);
                }
            }
        }
    }

    /*
     * Ticket Search form
    */
    public function testTicketSearch()
    {
        $this->load_vars();

        if ($this->status == 'Installed' and !is_null($this->main_route)) {
            // Member should not be able to access
            if (!is_null($this->member)) {
                $response = $this->actingAs($this->member)->get(route($this->main_route.'.search'));
                $this->versionAssertStatus($response, 302);
            }

            // Agent access
            if (!is_null($this->agent)) {
                $response = $this->actingAs($this->agent)->get(route($this->main_route.'.search'));
                $this->versionAssertStatus($response, 200);
            }

            // Admin access
            if (!is_null($this->admin)) {
                $response = $this->actingAs($this->admin)->get(route($this->main_route.'.search'));
                $this->versionAssertStatus($response, 200);
            }
        }
    }
}
