<script>
$(function(){


    // Ticket List: Change ticket agent
	$('#tickets-table').on('draw.dt', function(e){
        @if ($ticketList == 'newest' && $setting->grab('newest_list_reload_seconds'))

            var modal_countdown = ({{ $setting->grab('newest_list_reload_seconds') }} - 5)*1000;

            setTimeout(function(){ $('#modal-page-reload').modal('show'); }, modal_countdown);
            var reload_secs = 6;
            var page_reload = "";

            $('#modal-page-reload').on('show.bs.modal', function () {

                $('#modal-page-reload #text_countdown').show();
                $('#modal-page-reload #text_reloading').hide();

                $('#modal-page-reload #counter').text(reload_secs-1);

                // Update the count down every 1 second
                page_reload = setInterval(function() {
                    reload_secs = reload_secs - 1;

                    // Output the result in an element with id="demo"
                    $('#modal-page-reload #counter').text(reload_secs);

                    // If the count down is over, write some text
                    if (reload_secs == 0) {
                        $('#modal-page-reload #text_countdown').hide();
                        $('#modal-page-reload #text_reloading').show();
                        clearInterval(page_reload);
                        window.location.reload(false);
                    }
                }, 1000);
            });

            $('#modal-page-reload').on('hidden.bs.modal', function () {
                clearInterval(page_reload);

                setTimeout(function(){ $('#modal-page-reload').modal('show'); }, modal_countdown);
                page_reload = "";
                reload_secs = 6;
            });

            $('#modal-page-reload #confirm').click(function(e){
                $('#modal-page-reload #text_countdown').hide();
                $('#modal-page-reload #text_reloading').show();
                window.location.reload(false);
            });
        @endif

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

			if ($(this).parent('div').find('input[name=' + $(this).attr('data-ticket-id') + '_status_checkbox]').is(':checked')){
				$('#modalAgentChange').find('input[name=status_checkbox]').prop('checked', true);
			}

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
