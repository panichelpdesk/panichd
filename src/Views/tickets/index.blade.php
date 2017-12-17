@extends($master)

@section('page')
    {{ trans('panichd::lang.index-title') }}
@stop

@include('panichd::shared.common')

@if (PanicHD\PanicHD\Models\Ticket::count() == 0)
	@section('content')
		<div class="panel panel-default">
			<div class="panel-body">
				{{ trans('panichd::lang.no-tickets-yet') }}
			</div>
		</div>
	@stop
@else
	@section('content')
		@include('panichd::tickets.partials.filter_panel')
		<div class="panel panel-default">
			<div class="panel-body">
				<div id="message"></div>
				@include('panichd::tickets.partials.datatable')
			</div>
		</div>
		@include('panichd::tickets.partials.modal_agent')
		@include('panichd::tickets.partials.priority_popover_form')
	@stop

	@section('footer')
		<script src="//cdn.datatables.net/v/bs/dt-{{ PanicHD\PanicHD\Helpers\Cdn::DataTables }}/r-{{ PanicHD\PanicHD\Helpers\Cdn::DataTablesResponsive }}/datatables.min.js"></script>
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
					decimal:        "{{ trans('panichd::lang.table-decimal') }}",
					emptyTable:     "{{ trans('panichd::lang.table-empty') }}",
					info:           "{{ trans('panichd::lang.table-info') }}",
					infoEmpty:      "{{ trans('panichd::lang.table-info-empty') }}",
					infoFiltered:   "{{ trans('panichd::lang.table-info-filtered') }}",
					infoPostFix:    "{{ trans('panichd::lang.table-info-postfix') }}",
					thousands:      "{{ trans('panichd::lang.table-thousands') }}",
					lengthMenu:     "{{ trans('panichd::lang.table-length-menu') }}",
					loadingRecords: "{{ trans('panichd::lang.table-loading-results') }}",
					processing:     "{{ trans('panichd::lang.table-processing') }}",
					search:         "{{ trans('panichd::lang.table-search') }}",
					zeroRecords:    "{{ trans('panichd::lang.table-zero-records') }}",
					paginate: {
						first:      "{{ trans('panichd::lang.table-paginate-first') }}",
						last:       "{{ trans('panichd::lang.table-paginate-last') }}",
						next:       "{{ trans('panichd::lang.table-paginate-next') }}",
						previous:   "{{ trans('panichd::lang.table-paginate-prev') }}"
					},
					aria: {
						sortAscending:  "{{ trans('panichd::lang.table-aria-sort-asc') }}",
						sortDescending: "{{ trans('panichd::lang.table-aria-sort-desc') }}"
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
				
				// Agent / Priority change: Popover menu
				$(".jquery_popover")
					.tooltip({
						placement: 'top',
						trigger: "hover"
					})
					.popover({ html: true})
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
				
				// Agent change: Popover menu submit
				$(document).on('click','.submit_priority_popover',function(e){
					e.preventDefault();
									
					// Form fields
					$('#PriorityPopoverForm #priority_ticket_id_field').val($(this).attr('data-ticket-id'));
					var priority_val = $(this).parent('div').find('input[name='+$(this).attr('data-ticket-id')+'_priority]:checked').val();
					$('#PriorityPopoverForm #priority_id_field').val(priority_val);
				
					// Form submit
					$('#PriorityPopoverForm').find('form').submit();
					
				});

				// Agent change: Tooltip for 1 agents
				$(".tooltip-info").tooltip();
			});

			@yield('footer_jquery')
		});
		</script>
	@append
@endif