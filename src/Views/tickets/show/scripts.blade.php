<script type="text/javascript">
	// PhotoSwipe items array (load before jQuery .pwsp_gallery_link click selector)
	var pswpItems = [
		@foreach($ticket->allAttachments()->images()->get() as $attachment)
			@if($attachment->image_sizes != "")
				<?php
					$sizes = explode('x', $attachment->image_sizes);
				?>
				{
					src: '{{ URL::route($setting->grab('main_route').'.view-attachment', [$attachment->id]) }}',
					w: {{ $sizes[0] }},
					h: {{ $sizes[1] }},
					pid: {{ $attachment->id }},
					title: '{{ $attachment->new_filename  . ($attachment->description == "" ? '' : trans('panichd::lang.colon').$attachment->description) }}'
				},
			@endif
		@endforeach
	];

	var category_id=<?=$ticket->category_id;?>;
	$(document).ready(function() {
		// Mark ticket as read / unread
		$(document).on('click', '.unread_toggle', function(e){
			e.preventDefault();

			$.ajax({
				type: "POST",
				url: '{{ route($setting->grab('main_route').'.ajax.read') }}',
				data: {
					_token: "{{ csrf_token() }}",
					ticket_id: $(this).attr('data-ticket_id')
				},

				success: function( response ) {
					if (response.result == "ok"){
						if (response.read_by_agent == "1"){
							// Mark ticket subject
							$('#ticket-body h2').removeClass('unread_ticket_text');

							// Change icon
							$('.unread_toggle i.fas').removeClass().addClass('fas').addClass('fa-user');
						}else{
							$('#ticket-body h2').addClass('unread_ticket_text');
							$('.unread_toggle i.fas').removeClass().addClass('fas').addClass('fa-user-lock');
						}

						// Update button title
						$('.unread_toggle').blur().tooltip('dispose');
						$('.unread_toggle').prop('title', response.read_by_agent == "2" ? '{{ trans('panichd::lang.mark-as-read') }}' : '{{ trans('panichd::lang.mark-as-unread') }}');
						$('.unread_toggle').tooltip();
					}
				}
			});
		});
		
		// Delete ticket
		$( ".deleteit" ).click(function( event ) {
			event.preventDefault();
			if (confirm("{!! trans('panichd::lang.show-ticket-js-delete') !!}" + $(this).attr("node") + " ?"))
			{
				var form = $(this).attr("form");
				$("#" + form).submit();
			}

		});
		$('#category_id').change(function(){
			// Update agent list
			var loadpage = "{!! route($setting->grab('main_route').'agentselectlist') !!}/" + $(this).val() + "/{{ $ticket->id }}";
			$('#agent_id').load(loadpage);

			// Update tag list
			$('#tag_list_container .select2-container').hide();
			$('#jquery_tag_category_'+$(this).val()).next().show();
		});

		$('#agent_id').change(function(){
			if ($('#status_id').val()=="{!! $setting->grab('default_status_id') !!}"){
				$('#status_id').val("{!! $setting->grab('default_reopen_status_id') !!}")
			}
		});
		$('#confirmDelete').on('show.bs.modal', function (e) {
			$message = $(e.relatedTarget).attr('data-message');
			$(this).find('.modal-body p').text($message);
			$title = $(e.relatedTarget).attr('data-title');
			$(this).find('.modal-title').text($title);

			// Pass form reference to modal for submission on yes/ok
			var form = $(e.relatedTarget).closest('form');
			$(this).find('.modal-footer #confirm').data('form', form);
		});

		<!-- Form confirm (yes/ok) handler, submits form -->
		$('#confirmDelete').find('.modal-footer #confirm').on('click', function(){
			$(this).data('form').submit();
		});

		// Complete modal status_id change
        $('#ticket-complete-modal #status_id').change(function(){
           var url = '{{ route($setting->grab('main_route').'.edit', ['ticket' => $ticket->id]) . '/complete/yes/status_id/' }}';
            $('#ticket-complete-modal #edit-with-values')
               .prop('href', url + $(this).val());
        });

		// Complete modal submit button
		$('#complete_form_submit').click(function(e){
			@if ($u->currentLevel() > 1 && $u->canManageTicket($ticket->id))
				// Agent / Admin
				@if (!$ticket->intervention_html)
					if (!$('#blank_intervention_check').prop('checked')){
						alert('{{ trans('panichd::lang.show-ticket-complete-blank-intervention-alert') }}');
						return false;
					}
				@endif
			@else
				// User Level
				if (!$("#complete-ticket-form input[name='reason_id']:checked").val()) {
					alert('{{ trans('panichd::lang.show-ticket-modal-complete-blank-reason-alert') }}');
					return false;
				}
			@endif
			$('#complete-ticket-form').submit();
		});
	});
</script>
