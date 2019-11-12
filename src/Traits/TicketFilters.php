<?php

namespace PanicHD\PanicHD\Traits;

use PanicHD\PanicHD\Models;

trait TicketFilters
{
    // Available ticket filters
    private $a_filters = ['currentLevel', 'owner', 'calendar', 'year', 'category', 'agent'];

    /*
     * Validate all present filters. Delete ones with no valid data
    */
    public function validateFilters($request)
    {
        $filters_count = 0;

        if (session('panichd_filter_currentLevel') != '') {
            if (!in_array(session('panichd_filter_currentLevel'), [1, 2, 3])) {
                $request->session()->forget('panichd_filter_currentLevel');
            } else {
                $filters_count++;
            }
        }

        if (session('panichd_filter_owner') != '') {
            $owner = session('panichd_filter_owner');
            if ($owner != 'me' and is_null(\PanicHDMember::find($owner))) {
                $request->session()->forget('panichd_filter_owner');
            } else {
                $filters_count++;
            }
        }

        if (session('panichd_filter_calendar') != '') {
            if (!in_array(session('panichd_filter_calendar'), ['expired', 'today', 'tomorrow', 'week', 'month', 'within-7-days', 'within-14-days', 'not-scheduled'])) {
                $request->session()->forget('panichd_filter_calendar');
            } else {
                $filters_count++;
            }
        }

        if (session('panichd_filter_year') != '') {
            $year = session('panichd_filter_year');
            if ($year != 'all' and !in_array($year, range($this->getFirstTicketCompleteYear(), date('Y')))) {
                $request->session()->forget('panichd_filter_year');
            } else {
                $filters_count++;
            }
        }

        if (session('panichd_filter_category') != '') {
            if (is_null(Models\Category::find(session('panichd_filter_category')))) {
                $request->session()->forget('panichd_filter_year');
            } else {
                $filters_count++;
            }
        }

        if (session('panichd_filter_agent') != '') {
            $member = \PanicHDMember::find(session('panichd_filter_agent'));
            if (is_null($member) or !$member->isAgent()) {
                $request->session()->forget('panichd_filter_agent');
            } else {
                $filters_count++;
            }
        }

        // General filter uncheck if none
        if ($filters_count == 0) {
            $request->session()->forget('panichd_filters');
        }

        return [$request, $filters_count];
    }
}
