<table id="tickets-table" class="table table-condensed table-striped table-hover ddt-responsive" style="width: 100%">
    <thead>
        <tr>
            <td>{{ trans('panichd::lang.table-id') }}</td>
			
			<td>{{-- hidden: priority order --}}</td>
			<td>{{-- hidden: updated at order --}}</td>
			<td>{{-- hidden: has limit --}}</td>
			<td>{{-- hidden: inverse limit date --}}</td>
			<td>{{-- hidden: inverse start date --}}</td>
			@if ($setting::grab('departments_feature'))
				<td>{{-- hidden: dep_ancestor_name --}}</td>
			@endif
			
            <td>{{ trans('panichd::lang.table-subject') }}</td>
            @if ($setting->grab('subject_content_column') == 'no')
				<td>{{ trans('panichd::lang.table-description') }}</td>
			@endif
			<td>{{ trans('panichd::lang.table-intervention') }}</td>
			<td>{{ trans('panichd::lang.table-status') }}</td>            
			@if (session('panichd_filter_agent')=="" && $u->currentLevel() > 1)
				<td>{{ trans('panichd::lang.table-agent') }}</td>	
			@endif			
			@if( $u->currentLevel() > 1 )
				<td>{{ trans('panichd::lang.table-priority') }}</td>
				
				@if (session('panichd_filter_owner')=="")
					<td>{{ trans('panichd::lang.table-owner') }}</td>
					@if ($setting::grab('departments_feature'))
						<td>{{ trans('panichd::lang.table-department') }}</td>
					@endif					
				@endif
				@if ($ticketList == 'complete')
					<td>{{ trans('panichd::lang.table-completed_at') }}</td>
				@else
					<td>{{ trans('panichd::lang.table-calendar') }}</td>
				@endif
			@endif
			<td>{{ trans('panichd::lang.table-last-updated') }}</td>
			@if( $u->currentLevel() > 1 )
				@if (session('panichd_filter_category')=="")
					<td>{{ trans('panichd::lang.table-category') }}</td>
				@endif
				<td>{{ trans('panichd::lang.tags') }}</td>
			@endif
        </tr>
    </thead>
</table>