<?php

namespace PanicHD\PanicHD\Traits;

use Cache;
use PanicHD\PanicHD\Models\Ticket;

trait CacheVars
{
    /**
     * Get array with ticket creation_at years.
     *
     * @Return string
     */
    protected function getFirstTicketCompleteYear()
    {
        return Cache::remember('panichd::first_ticket_complete_year', \Carbon\Carbon::now()->addMinutes(60), function () {
            $ticket = Ticket::complete()->orderBy('completed_at', 'asc')->first();

            return $ticket ? $ticket->completed_at->year : date('Y');
        });
    }

    /**
     * Get array with complete tickets count by creation_at year.
     *
     * @Return array
     */
    protected function getCompleteTicketYearCounts()
    {
        return Cache::remember('panichd::a_complete_ticket_year_counts', \Carbon\Carbon::now()->addMinutes(60), function () {
            $a_years = range($this->getFirstTicketCompleteYear(), date('Y'));
            rsort($a_years);
            $a_year_counts = [];
            foreach ($a_years as $year) {
                $a_year_counts[$year] = Ticket::whereYear('completed_at', $year)->count();
            }

            return $a_year_counts;
        });
    }
}
