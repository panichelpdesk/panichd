<?php

namespace PanicHD\PanicHD\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PanicHD\PanicHD\Models;
use PanicHD\PanicHD\Models\Setting;
use PanicHD\PanicHD\Traits\CacheVars;


class FiltersController extends Controller
{
    use CacheVars;

	// Available ticket filters
	private $a_filters = ['currentLevel', 'owner', 'calendar', 'year', 'category', 'agent'];
	
	// Filters that can be called via /filteronly/
	private $a_only_filters = ['category', 'owner', 'agent'];
	
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
				
				// General filter uncheck if none
				$current = false;
				foreach ($this->a_filters as $single){
					if ($request->session()->exists('panichd_filter_'.$single)){
						$current = true;
						break;
					}
				}
				if (!$current){
					$request->session()->forget('panichd_filters');
				}
            } else {
                $add = false;

                // Filter checks
                if ($filter=='currentLevel' and in_array($value,[1,2,3])){
					$add = true;
				}
				
				if ($filter == 'owner' and $value == 'me') {
					$add = true;
                }
				
				if ($filter == 'calendar' and in_array($value, ['expired', 'today', 'tomorrow', 'week', 'month', 'within-7-days', 'within-14-days', 'not-scheduled'])){
					$add = true;
				}
				
				if ($filter == 'year' and ($value == 'all' or in_array($value, range($this->getFirstTicketCompleteYear(), date('Y'))))){
					$add = true;
				}
				
				if ($filter == 'category' and Models\Category::where('id', $value)->count() == 1) {
                    $add = true;
                }
				
				if ($filter == 'agent' and Models\Member::where('id', $value)->count() == 1) {
                    $add = true;
                }  

                // Add filter
                if ($add) {
                    $request->session()->put('panichd_filter_'.$filter, $value);
					
					// General filter check
					$request->session()->put('panichd_filters','yes');
                }
            }
        }

        return \Redirect::back();
    }
	
	/*
	 * Delete all filters and apply only the selected one
	*/
	public function only(Request $request, $filter, $value, $list)
	{
		if (in_array($filter, $this->a_only_filters) and in_array($list, array_keys($this->a_lists))){
			
			$apply = false;
			
			if (in_array($filter, ['owner', 'agent'])){
				$member = Models\Member::where('id', $value);
			}
			
			if (($filter == "owner" and $member->count() == 1)
				or ($filter == "agent" and $member->count() == 1 and $member->first()->isAgent())){
				
				$apply = true;
				
			}elseif($filter == "category" and Models\Category::where('id', $value)->count() == 1){
				
				$apply = true;
			}
			
			if ($apply){
				// Delete each filter from session
				foreach ($this->a_filters as $delete){
					$request->session()->forget('panichd_filter_'.$delete);
				}
				
				// Add single filter
				$request->session()->put('panichd_filter_'.$filter, $value);
				
				// General filter check
				$request->session()->put('panichd_filters','yes');
				
				// Redirect to specified route
				return redirect()->route(Setting::grab('main_route').$this->a_lists[$list]);
			}
		}
		
		return \Redirect::back();
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