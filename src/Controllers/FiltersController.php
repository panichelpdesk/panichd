<?php

namespace PanicHD\PanicHD\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PanicHD\PanicHD\Models;


class FiltersController extends Controller
{
    public function manage(Request $request, $filter, $value)
    {
		$a_filters = ['currentLevel', 'owner', 'calendar', 'category', 'agent'];
        //### PENDING: User permissions check or redirect back

        if ($filter=="removeall"){
			// Delete each filter from session
			foreach ($a_filters as $single){
				$request->session()->forget('panichd_filter_'.$single);
			}
			
			// General filter uncheck
			$request->session()->forget('panichd_filters');
			
			// Redirect to specified list
			return \Redirect::route(Models\Setting::grab('main_route').($value=="complete" ? '-complete' : '.index'));
		}
		
		if (in_array($filter, $a_filters) == true) {
            if ($value == 'remove') {
                // Delete filter
                $request->session()->forget('panichd_filter_'.$filter);
				
				// General filter uncheck if none
				$current = false;
				foreach ($a_filters as $single){
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
				
				if ($filter == 'calendar' and in_array($value, ['expired', 'today', 'tomorrow', 'week', 'month'])){
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
}
