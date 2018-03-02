<script>
$(function(){
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
			.popover({
				html: true
			})
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