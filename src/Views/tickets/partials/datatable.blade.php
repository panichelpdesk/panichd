<table id="tickets-table" class="table table-condensed table-striped table-hover ddt-responsive" style="width: 100%">
    <thead>
        <tr>
            <td>{{ trans('panichd::lang.table-id') }}</td>
            <td>{{ trans('panichd::lang.table-subject') }}</td>
            <td>{{ trans('panichd::lang.table-description') }}</td>
			<td>{{ trans('panichd::lang.table-intervention') }}</td>
			<td>{{ trans('panichd::lang.table-status') }}</td>            
			@if (session('ticketit_filter_agent')=="" && $u->currentLevel() > 1)
				<td>{{ trans('panichd::lang.table-agent') }}</td>	
			@endif			
			@if( $u->currentLevel() > 1 )
				<td>{{ trans('panichd::lang.table-priority') }}</td>
				<td>{{-- hidden: priority order --}}</td>
				@if (session('ticketit_filter_owner')=="")
					<td>{{ trans('panichd::lang.table-owner') }}</td>
					@if ($setting::grab('departments_feature'))
						<td>{{ trans('panichd::lang.table-department') }}</td>
					@endif					
				@endif
				<td>{{ trans('panichd::lang.table-calendar') }}</td>
			@endif
			<td>{{ trans('panichd::lang.table-last-updated') }}</td>
			@if( $u->currentLevel() > 1 )
				@if (session('ticketit_filter_category')=="")
					<td>{{ trans('panichd::lang.table-category') }}</td>
				@endif
				<td>Etiquetes</td>
			@endif
        </tr>
    </thead>
</table>