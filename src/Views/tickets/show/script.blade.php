<script>
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
			$('#jquery_select2_container .select2-container').hide();
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
           var url = '{{ route($setting->grab('main_route').'.edit', ['id' => $ticket->id]) . '/complete/yes/status_id/' }}';
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

		// When opening a comment modal,
		$('.comment-modal').on('show.bs.modal', function (e) {
			$('.comment-modal .alert-danger').hide();
			$(this).find('.fieldset-for-comment').show();
			$(this).find('.fieldset-for-attachment').hide();
		});

		$('.comment-modal').on('shown.bs.modal', function (e) {
		    $(this).find('.modal-title').text($(e.relatedTarget).text());
			if ($(e.relatedTarget).data('add-comment') == 'no') $(this).find('#comment-type-buttons').hide();

            $(this).find("textarea.modal-summernote-editor").summernote(summernote_options);
		});

        $('.comment-modal').on('hidden.bs.modal', function (e) {
            $(this).find("textarea.modal-summernote-editor").summernote('destroy');
        });

		// Comment form: Click on response type buttons (reply or note)
		$('.response_type').click(function(){
			var type = $(this).attr('data-type');
			$('#modal-comment-new #response_type').val(type);
			$(this).addClass($(this).attr('data-active-class'));

			if (type == 'reply'){
				$('#add_in_user_notification_text, #add_to_intervention').prop('disabled', false);
				$('#add_in_user_notification_text, #add_to_intervention').closest('div').show();
			}else{
				$('#add_in_user_notification_text, #add_to_intervention').prop('disabled', true);
				$('#add_in_user_notification_text, #add_to_intervention').closest('div').hide();
			}

			var alt = type == 'note' ? 'reply' : 'note';
			$('#popup_comment_btn_'+alt).removeClass($('#popup_comment_btn_'+alt).attr('data-active-class'));
		});

		// Highlight related comment when showing related modal
		$('.jquery_panel_hightlight').on('show.bs.modal', function (e) {
			$(e.relatedTarget).closest('div.card').addClass('card-highlight');
		});

		$('.jquery_panel_hightlight').on('hidden.bs.modal', function (e) {
			$('div.card').removeClass('card-highlight');
		});

		$('#new_comment_submit').click(function(e){
			e.preventDefault();

			@if($u->currentLevel() > 1 && !$ticket->intervention_html != "")
				if ($(this).closest('form').find('input[name="add_to_intervention"]').is(':checked') == false && $(this).closest('form').find('input[name="complete_ticket"]').is(':checked')){
					if(!confirm('{!! trans('panichd::lang.add-comment-confirm-blank-intervention') !!}')) return false;
				}
			@endif

			ajax_form_submit($(this));
		});

		// Click "X" to delete comment
		$('#modal-comment-delete').on('show.bs.modal', function (e) {
			if ($('#delete-comment-form').attr('data-default-action') == ''){
				$('#delete-comment-form').attr('data-default-action',$('#delete-comment-form').attr('action'));
			}

			// Add value to form
			$('#delete-comment-form').attr('action',$('#delete-comment-form').attr('action').replace('action_comment_id',$(e.relatedTarget).attr('data-id')));
		});

		// Dismiss comment deletion
		$('#modal-comment-delete').on('hidden.bs.modal', function (e) {
			$('#delete-comment-form').attr('action',$('#delete-comment-form').attr('data-default-action'));
		});

		// Comment confirm delete
		$( "#delete-comment-submit" ).click(function( event ) {
			event.preventDefault();
			$("#delete-comment-form").submit();
		});


		// Comment (reply) notifications resend modal
		$( "#email-resend-modal" ).on('show.bs.modal', function (e) {
			var button = $(e.relatedTarget);
			$(this).find('#owner').text($(button).attr('data-owner'));
			$(this).find('#comment_id').val($(button).attr('data-id'))
		});
	});
</script>
