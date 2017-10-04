@extends($master)

@section('page')
    {{ trans('ticketit::lang.index-title') }}
@stop

@include('ticketit::shared.common')

@section('content')
    @include('ticketit::tickets.index')
	@include('ticketit::tickets.partials.modal_agent')
@stop

@section('footer')
	<script src="//cdn.datatables.net/v/bs/dt-{{ Kordy\Ticketit\Helpers\Cdn::DataTables }}/r-{{ Kordy\Ticketit\Helpers\Cdn::DataTablesResponsive }}/datatables.min.js"></script>
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
            	@if (session('ticketit_filter_agent')=="" && $u->currentLevel() > 1)
					{ data: 'agent', name: 'agent.name' },
				@endif				
	            @if( $u->currentLevel() > 1 )
		            { data: 'priority', name: 'ticketit_priorities.name' },
	            	@if (session('ticketit_filter_owner')=="")
						{ data: 'owner_name', name: 'users.name' },
						@if ($setting::grab('departments_feature'))
							{ data: 'dept_info', name: 'dept_full', searchable: false },
						@endif
					@endif
					{ data: 'calendar', name: 'calendar_order', searchable: false },
				@endif
				{ data: 'updated_at', name: 'ticketit.updated_at' },
				@if( $u->currentLevel() > 1 )
					@if (session('ticketit_filter_category')=="")
						{ data: 'category', name: 'ticketit_categories.name' },
					@endif
					{ data: 'tags', name: 'ticketit_tags.name' }
	            @endif				
	        ],
			order: [
				[0,'desc']				
			]
			
	    });		
		
		// Ticket List: Change ticket agent
		$('#tickets-table').on('draw.dt', function(e){
			
			// Agent change: Modal for > 4 agents
			$('.jquery_agent_change_modal').click(function(e){
				e.preventDefault();				
				
				// Row hover
				$(this).closest('tr').addClass('hover');				
				
				// Form fields
				$('#modalAgentChange #agent_ticket_id_field').val($(this).attr('data-ticket-id'));
				
				// Modal itself
				$('#modalAgentChange').modal('show');
				$('#modalAgentChange .categories_agent_change').hide();
				$('#modalAgentChange #category_'+$(this).attr('data-category-id')+'_agents').show()
					.find(":radio[value="+$(this).attr('data-agent-id')+"]").prop('checked',true);
			});
			
			$('#modalAgentChange').on('hidden.bs.modal', function () {
				$(document).find('tr').removeClass('hover');
			});
			
			// Agent change: Popover menu
			$(".jquery_agent_change_integrated").popover({ html: true})
			.click(function(e){
				e.preventDefault();
			});
			
			// Agent change: Popover menu submit
			$(document).on('click','.submit_agent_popover',function(e){
				e.preventDefault();
								
				// Form fields
				$('#modalAgentChange #agent_ticket_id_field').val($(this).attr('data-ticket-id'));
				$('#modalAgentChange').find(":radio[value="+$(this).parent('div').find('input[name='+$(this).attr('data-ticket-id')+'_agent]:checked').val()+"]").prop('checked',true);
			
				// Form submit
				$('#modalAgentChange').find('form').submit();
				
			});

			// Agent change: Tooltip for 1 agents
			$(".tooltip-info").tooltip();
		});

		@yield('footer_jquery')
	});
	</script>
@append
