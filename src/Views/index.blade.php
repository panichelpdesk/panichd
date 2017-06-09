@extends($master)

@section('page')
    {{ trans('ticketit::lang.index-title') }}
@stop

@section('content')
    @include('ticketit::shared.header')
    @include('ticketit::tickets.index')
	@include('ticketit::tickets.partials.modal_agent')
@stop

@section('footer')
	<script src="//cdn.datatables.net/1.10.9/js/jquery.dataTables.min.js"></script>
	<script src="//cdn.datatables.net/plug-ins/505bef35b56/integration/bootstrap/3/dataTables.bootstrap.js"></script>
	<script src="//cdn.datatables.net/responsive/1.0.7/js/dataTables.responsive.min.js"></script>
	<script>
	$(function(){		
		// Ticket list load
		$('.table').DataTable({
	        processing: false,
	        serverSide: true,
	        responsive: true,
            pageLength: {{ $setting->grab('paginate_items') }},
        	lengthMenu: {{ json_encode($setting->grab('length_menu')) }},
	        ajax: '{!! route($setting->grab('main_route').'.data', $ticketList) !!}',
	        language: {
				decimal:        "{{ trans('ticketit::lang.table-decimal') }}",
				emptyTable:     "{{ trans('ticketit::lang.table-empty') }}",
				info:           "{{ trans('ticketit::lang.table-info') }}",
				infoEmpty:      "{{ trans('ticketit::lang.table-info-empty') }}",
				infoFiltered:   "{{ trans('ticketit::lang.table-info-filtered') }}",
				infoPostFix:    "{{ trans('ticketit::lang.table-info-postfix') }}",
				thousands:      "{{ trans('ticketit::lang.table-thousands') }}",
				lengthMenu:     "{{ trans('ticketit::lang.table-length-menu') }}",
				loadingRecords: "{{ trans('ticketit::lang.table-loading-results') }}",
				processing:     "{{ trans('ticketit::lang.table-processing') }}",
				search:         "{{ trans('ticketit::lang.table-search') }}",
				zeroRecords:    "{{ trans('ticketit::lang.table-zero-records') }}",
				paginate: {
					first:      "{{ trans('ticketit::lang.table-paginate-first') }}",
					last:       "{{ trans('ticketit::lang.table-paginate-last') }}",
					next:       "{{ trans('ticketit::lang.table-paginate-next') }}",
					previous:   "{{ trans('ticketit::lang.table-paginate-prev') }}"
				},
				aria: {
					sortAscending:  "{{ trans('ticketit::lang.table-aria-sort-asc') }}",
					sortDescending: "{{ trans('ticketit::lang.table-aria-sort-desc') }}"
				},
			},
	        columns: [
	            { data: 'id', name: 'ticketit.id' },
	            { data: 'subject', name: 'subject' },
				{ data: 'content', name: 'content' },
				{ data: 'intervention', name: 'intervention' },
	            { data: 'status', name: 'ticketit_statuses.name' },
	            { data: 'updated_at', name: 'ticketit.updated_at' },
            	@if (session('ticketit_filter_agent')=="" && $u->currentLevel() > 1)
					{ data: 'agent', name: 'users.name' },
				@endif				
	            @if( $u->currentLevel() > 1 )
		            { data: 'priority', name: 'ticketit_priorities.name' },
	            	@if (session('ticketit_filter_owner')=="")
						{ data: 'owner', name: 'users.name' },
		            @endif
					@if (session('ticketit_filter_category')=="")
						{ data: 'category', name: 'ticketit_categories.name' },
					@endif
					{ data: 'tags', name: 'ticketit_tags.name' }
	            @endif				
	        ],
			order: [
				[5,'desc']				
			]
			
	    });		
		
		// Ticket List: Change ticket agent
		$('#tickets-table').on('draw.dt', function(e){
			$('.agent_change').click(function(e){
				e.preventDefault();
				
				$('#agentChange #agent_ticket_id_text').text($(this).attr('data-ticket-id'));
				$('#agentChange #agent_ticket_id_field').val($(this).attr('data-ticket-id'));
				$('#agentChange #ticket_subject').text($(this).attr('data-ticket-subject'));
				$('#agentChange #current_agent').text($(this).attr('data-agent-name'));
				
				$('#agentChange').modal('show');
				$('#agentChange .categories_agent_change').hide();
				$('#agentChange #category_'+$(this).attr('data-category-id')+'_agents').show()
					.find(":radio[value="+$(this).attr('data-agent-id')+"]").prop('checked',true);
			});
		});

		@yield('footer_jquery')
	});
	</script>
@append
