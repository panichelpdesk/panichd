<script>
$(function(){
    $('#tickets-table').on('draw.dt', function(e){

	    // Plus / less buttons for text fields
        $('.jquery_ticket_text_toggle').click(function(e){
            var remove = $(this).find('span.fa').hasClass("fa-plus") ? 'plus' : 'minus';
            var action = $(this).find('span.fa').hasClass("fa-plus") ? 'minus' : 'plus';
            var id = $(this).data('id');

            $(this).closest('tr').find('td').first().effect('highlight');

            $('.jquery_ticket_' + id + '_text').each(function(){
                if (action == 'minus'){
                    $(this).prop('data-height-minus', $(this).height());
                    $(this).find('span.fa').removeClass('fa-plus').addClass('fa-minus');
                    $(this).find('.text_minus').hide();
                    $(this).find('.text_plus').css('display', 'inline');

                    $(this).prop('data-height-plus', $(this).height());
                    $(this).css('height', $(this).prop('data-height-minus')).animate({height: $(this).prop('data-height-plus')}, 500);
                }else{
                    $(this).find('span.fa').removeClass('fa-minus').addClass('fa-plus');
                    $(this).find('.text_minus').show();
                    $(this).find('.text_plus').hide();
                    $(this).css('height', '');
                }
            });
        });

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
				html: true,
				sanitize: false
			})
		.click(function(e){
			e.preventDefault();
		});

		// Agent change: Popover menu submit
		$(document).off('click','.submit_agent_popover');
		$(document).on('click','.submit_agent_popover',function(e){
			e.preventDefault();

			// Form fields
			$('#modalAgentChange #agent_ticket_id_field').val($(this).attr('data-ticket-id'));
			$('#modalAgentChange').find(":radio[value="+$(this).parent('div').find('input[name='+$(this).attr('data-ticket-id')+'_agent]:checked').val()+"]").prop('checked',true);

			if ($(this).parent('div').find('input[name=' + $(this).attr('data-ticket-id') + '_status_checkbox]').is(':checked')){
				$('#modalAgentChange').find('input[name=status_checkbox]').prop('checked', true);
			}

			// Form submit
			$('#modalAgentChange').find('form').submit();
		});

		// Make AJAX send from modalAgentChange form submit
		$(document).off('submit', '#modalAgentChange form');
		$(document).on('submit', '#modalAgentChange form', function(e){
			e.preventDefault();

			var form = $(this);
			var Form_Data = new FormData(form[0]);

			// Append existent last_update value
			Form_Data.append('ticketList', '{{ $ticketList }}');

			$.ajax({
				processData: false,
				contentType: false,
				type: "POST",
				url: form.prop('action'),
				data: Form_Data,

				success: function( response ) {
					popover_success(response);
                }
			});

		});

		// Priority change: Popover menu submit
		$(document).off('click', '.submit_priority_popover');
		$(document).on('click', '.submit_priority_popover', function(e){
			e.preventDefault();

			$.ajax({
				type: "POST",
				url: "{{ route($setting->grab('main_route').'-change.priority') }}",
				data: {
					_token: "{{ csrf_token() }}",
					ticket_id: $(this).attr('data-ticket-id'),
					priority_id: $(this).parent('div').find('input[name='+$(this).attr('data-ticket-id')+'_priority]:checked').val(),
					ticketList: '{{ $ticketList }}'
				},

				success: function( response ) {
					popover_success(response);
                }
			});
		});


		/*
		 * Common AJAX success response for ticket changes within datatable
		*/
		function popover_success(response)
		{
			// Show bottom message
			$('#bottom_toast').empty().append('<div class="alert alert-' + (response.result == 'ok' ? 'info' : 'danger') + '">' + response.message + '</div>');
			$('#bottom_toast').addClass('show');
			
			// If datatable needs a reload
			if (last_update != response.last_update){
				// Apply new last update refference
				last_update = response.last_update;

				// Hide any existent popover
				$(".jquery_popover").popover('hide');

				// Reload datatable
				datatable.ajax.reload();
			}

			// Restart check interval
			init_check_last_update();

			// Hide bottom message
			setInterval(function(){ $('#bottom_toast').removeClass('show'); }, 2000);
		}

		// Agent: Tooltip when there is only 1 agents
		$(".tooltip-info").tooltip();
	});

	@yield('footer_jquery')
});
</script>
