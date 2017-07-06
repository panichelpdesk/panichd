<table id="tickets-table" class="table table-condensed table-striped table-hover ddt-responsive" class="ticketit-table" style="width: 100%">
    <thead>
        <tr>
            <td>{{ trans('ticketit::lang.table-id') }}</td>
            <td>{{ trans('ticketit::lang.table-subject') }}</td>
            <td>{{ trans('ticketit::lang.table-description') }}</td>
			<td>{{ trans('ticketit::lang.table-intervention') }}</td>
			<td>{{ trans('ticketit::lang.table-status') }}</td>
            <td>{{ trans('ticketit::lang.table-last-updated') }}</td>
			  @if (session('ticketit_filter_agent')=="" && $u->currentLevel() > 1)
				<td>{{ trans('ticketit::lang.table-agent') }}</td>	
			  @endif			
			  @if( $u->currentLevel() > 1 )			
				<td>{{ trans('ticketit::lang.table-priority') }}</td>
				@if (session('ticketit_filter_owner')=="")
					<td>{{ trans('ticketit::lang.table-owner') }}</td>
					@if ($setting::grab('departments_feature'))
						<td>{{ trans('ticketit::lang.table-department') }}</td>
					@endif
				@endif
				@if (session('ticketit_filter_category')=="")
					<td>{{ trans('ticketit::lang.table-category') }}</td>
				@endif
				<td>Etiquetes</td>
			  @endif
        </tr>
    </thead>
</table>