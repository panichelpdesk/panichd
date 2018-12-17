<?php

namespace PanicHD\PanicHD\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PanicHD\PanicHD\Models;
use PanicHD\PanicHD\Models\Setting;
use PanicHD\PanicHD\Traits\CacheVars;
use PanicHD\PanicHD\Traits\TicketFilters;


class FiltersController extends Controller
{
    use CacheVars, TicketFilters;

	// $a_filters is in TicketFilters trait

	// Ticket list names and related route name
	private $a_lists = [
		'newest' => '-newest',
		'active' => '.index',
		'complete' => '-complete'
	];

	/*
	 * Update a single filter
	*/
	public function manage(Request $request, $filter, $value)
    {
		//### PENDING: User permissions check or redirect back

        if (in_array($filter, $this->a_filters) == true) {
            if ($value == 'remove') {
                // Delete filter
                $request->session()->forget('panichd_filter_'.$filter);

				// Check all filters
				$this->validateFilters($request);

            } else {
                // Validate and add a filter
				$this->addAFilter($request, $filter, $value);
            }
        }

        return \Redirect::back();
    }

	/*
	 * Delete all filters and apply only the selected one
	*/
	public function only(Request $request, $filter, $value, $list)
	{
		if (in_array($list, array_keys($this->a_lists))){

			// Delete each filter from session
			foreach ($this->a_filters as $delete){
				$request->session()->forget('panichd_filter_'.$delete);
			}

			// Validate and add a filter
			if ($this->addAFilter($request, $filter, $value)){
				// Redirect to specified route
				return redirect()->route(Setting::grab('main_route').$this->a_lists[$list]);
			}
		}

		return \Redirect::back();
	}

	/*
	 * Add a filter and validate it
	 *
	 * @Return bool
	*/
	public function addAFilter($request, $filter, $value)
	{
		// Add filter
		$request->session()->put('panichd_filter_'.$filter, $value);

		// Check all filters
		list($request, $filters_count) = $this->validateFilters($request);

		if ($filters_count > 0) {
			// General filter check
			$request->session()->put('panichd_filters','yes');
		}

		return $request->session()->exists('panichd_filter_'.$filter);
	}

	/*
	 * Remove all filters
	*/
	public function removeall(Request $request, $list = null)
	{
		// Delete each filter from session
		foreach ($this->a_filters as $filter){
			$request->session()->forget('panichd_filter_'.$filter);
		}

		// General filter uncheck
		$request->session()->forget('panichd_filters');

		if ($list != "" and array_key_exists($list, $this->a_lists)){
			// Redirect to specified route
			return redirect()->route(Setting::grab('main_route').$this->a_lists[$list]);
		}

		return \Redirect::back();
	}
}
