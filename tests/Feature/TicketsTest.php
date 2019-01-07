<?php

namespace PanicHD\PanicHD\Tests\Feature;

use PanicHD\PanicHD\Models\Ticket;
use PanicHD\PanicHD\Tests\TestCase;

class TicketsTest extends TestCase
{
    /*
     * Check that we don't have access without auth() Member
    */
    public function testNoAuth()
    {
        $this->load_vars();

        if ($this->status == "Installed" and !is_null($this->main_route)){
            // Main route
            $response = $this->get($this->main_route);
            $response->assertRedirect('/login');

            // Ticket creation
            $response = $this->get(route($this->main_route . '.create'));
            $response->assertRedirect('/login');
        }
    }

    /*
     * Access to the main route
    */
    public function testMainRoute()
    {
        $this->load_vars();

        if ($this->status == "Installed" and !is_null($this->main_route)){
            // Member access
            if(!is_null($this->member)){
                $response = $this->actingAs($this->member)->get($this->main_route);
                $response->assertStatus(200);
            }

            // Agent access
            if(!is_null($this->agent)){
                $response = $this->actingAs($this->agent)->get($this->main_route);
                $response->assertStatus(200);
            }

            // Admin access
            if(!is_null($this->admin)){
                $response = $this->actingAs($this->admin)->get($this->main_route);
                $response->assertStatus(200);
            }
        }
    }

    /*
     * Ticket creation
    */
    public function testTicketCreate()
    {
        $this->load_vars();

        if ($this->status == "Installed" and !is_null($this->main_route)){
            // Member access
            if(!is_null($this->member)){
                $response = $this->actingAs($this->member)->get(route($this->main_route . '.create'));
            }

            // Agent access
            if(!is_null($this->agent)){
                $response = $this->actingAs($this->agent)->get(route($this->main_route . '.create'));
            }

            // Admin access
            if(!is_null($this->admin)){
                $response = $this->actingAs($this->admin)->get(route($this->main_route . '.create'));
            }
        }
    }

    /*
     * Access a Ticket Card
    */
    public function testTicketShow()
    {
        $this->load_vars();

        if ($this->status == "Installed" and !is_null($this->main_route)){
            // Member access
            if(!is_null($this->member)){
                // Visible ticket
                $ticket = clone $this->member_tickets_builder;
                $ticket = $ticket->notHidden()->first();
                $response = $this->actingAs($this->member)->get(route($this->main_route . '.show', ['id' => $ticket->id]));
                $response->assertStatus(200);

                // Hidden ticket without $this->member in notifications
                $ticket = clone $this->member_tickets_builder;
                $member = $this->member;
                $ticket = $ticket->hidden()
                    ->whereDoesntHave('commentNotifications', function($query)use($member){
                        $query->where('member_id', $member->id);
                    })
                    ->first();
                if(!is_null($ticket)){
                    $response = $this->actingAs($this->member)->get(route($this->main_route . '.show', ['id' => $ticket->id]));
                    $response->assertStatus(302);
                }
            }

            // Agent access
            if(!is_null($this->agent)){
                $ticket = $this->agent->agentTickets()->inRandomOrder()->first();

                $response = $this->actingAs($this->agent)->get(route($this->main_route . '.show', ['id' => $ticket->id]));
                $response->assertStatus(200);
            }

            // Admin access
            if(!is_null($this->admin)){
                $ticket_build = Ticket::inRandomOrder();

                // Newest ticket
                $build = clone $ticket_build;
                $ticket = $build->newest()->first();

                if (!is_null($ticket)){
                    $response = $this->actingAs($this->admin)->get(route($this->main_route . '.show', ['id' => $ticket->id]));
                    $response->assertStatus(200);
                }

                // Active ticket
                $build = clone $ticket_build;
                $ticket = $build->active()->first();

                if (!is_null($ticket)){
                    $response = $this->actingAs($this->admin)->get(route($this->main_route . '.show', ['id' => $ticket->id]));
                    $response->assertStatus(200);
                }

                // Complete ticket
                $build = clone $ticket_build;
                $ticket = $build->complete()->first();

                if (!is_null($ticket)){
                    $response = $this->actingAs($this->admin)->get(route($this->main_route . '.show', ['id' => $ticket->id]));
                    $response->assertStatus(200);
                }
            }
        }
    }
}
