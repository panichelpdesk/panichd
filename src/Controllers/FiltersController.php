<?php

namespace Kordy\Ticketit\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Kordy\Ticketit\Models\Agent;
use Kordy\Ticketit\Models\Category;

class FiltersController extends Controller
{
    public function manage(Request $request, $filter, $value)
    {
		$a_filters = ['agent', 'category', 'owner'];
        //### PENDING: User permissions check or redirect back

        if ($filter=="all" and $value=="remove"){
			// Delete each filter from session
			foreach ($a_filters as $single){
				$request->session()->forget('ticketit_filter_'.$single);
			}
			
			// General filter uncheck
			$request->session()->forget('ticketit_filters');
		}elseif (in_array($filter, $a_filters) == true) {
            if ($value == 'remove') {
                // Delete filter
                $request->session()->forget('ticketit_filter_'.$filter);
            } else {
                $add = false;

                // Filter checks
                if ($filter == 'agent') {
                    if (Agent::where('id', $value)->count() == 1) {
                        $add = true;
                    }
                }

                if ($filter == 'category') {
                    if (Category::where('id', $value)->count() == 1) {
                        $add = true;
                    }
                }

                if ($filter == 'owner') {
                    if ($value == 'me') {
                        $add = true;
                    }
                }

                // Add filter
                if ($add) {
                    $request->session()->put('ticketit_filter_'.$filter, $value);
					
					// General filter check
					$request->session()->put('ticketit_filters','yes');
                }
            }
        }

        return \Redirect::back();
    }
}
