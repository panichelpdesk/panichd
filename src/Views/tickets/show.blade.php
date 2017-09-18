@extends($master)
@section('page', trans('ticketit::lang.show-ticket-title') . trans('ticketit::lang.colon') . $ticket->subject)
@section('content')
        @include('ticketit::shared.header')
        @include('ticketit::tickets.partials.ticket_body')
		@include('ticketit::tickets.partials.modal_complete')
		
        <div style="margin-top: 2em;">
        	<h2 style="margin-top: 0em;">{{ trans('ticketit::lang.comments') }}
				<button type="button" class="btn btn-default" data-toggle="modal" data-target="#modal-comment-new">{{ trans('ticketit::lang.show-ticket-add-comment') }}</button>
			</h2>
        </div>
        @include('ticketit::tickets.partials.comments')
        {!! $comments->render() !!}
        @include('ticketit::tickets.partials.modal_comment_new')
		@if ($setting->grab('ticket_attachments_feature'))
			@include('ticketit::shared.attach_files_script')			
		@endif
@endsection

@include('ticketit::shared.photoswipe_files')
@include('ticketit::shared.jcrop_files')

@section('footer')
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
						title: '{{ $attachment->new_filename  . ($attachment->description == "" ? '' : trans('ticketit::lang.colon').$attachment->description) }}'							
					},
				@endif
			@endforeach
		];
	
		var category_id=<?=$ticket->category_id;?>;
        $(document).ready(function() {
			// Tooltips
			$('.tooltip-info, .tooltip-show').tooltip();
			
            $( ".deleteit" ).click(function( event ) {
                event.preventDefault();
                if (confirm("{!! trans('ticketit::lang.show-ticket-js-delete') !!}" + $(this).attr("node") + " ?"))
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
			
			// Complete modal submit button
			$('#complete_form_submit').click(function(e){				
				@if ($u->canManageTicket($ticket->id))
					// Agent / Admin
					@if (!$ticket->intervention_html)
						if (!$('#blank_intervention_check').prop('checked')){
							alert('{{ trans('ticketit::lang.show-ticket-complete-blank-intervention-alert') }}');
							return false;
						}
					@endif
				@else
					// User Level
					if (!$("#complete-ticket-form input[name='reason_id']:checked").val()) {
						alert('{{ trans('ticketit::lang.show-ticket-modal-complete-blank-reason-alert') }}');
						return false;
					}
				@endif
				$('#complete-ticket-form').submit();				
			});			
			
			// When opening a comment modal, 
			$('.comment-modal').on('show.bs.modal', function (e) {
                $(this).find('.fieldset-for-comment').show();
				$(this).find('.fieldset-for-attachment').hide();
			});
			
			// Comment form: Response type (reply or note)
			$('.response_type').click(function(){
				var type = $(this).attr('data-type');				
				$('#modal-comment-new #response_type').val(type);
				$(this).addClass($(this).attr('data-active-class'));
				
				var alt = type == 'note' ? 'reply' : 'note';
				$('#popup_comment_btn_'+alt).removeClass($('#popup_comment_btn_'+alt).attr('data-active-class'));
				
			});

			// Highlight related comment when showing related modal
			$('.jquery_panel_hightlight').on('show.bs.modal', function (e) {
                $(e.relatedTarget).closest('div.panel').addClass('panel-highlight');				
			});
			
			$('.jquery_panel_hightlight').on('hidden.bs.modal', function (e) {
                $('div.panel').removeClass('panel-highlight');
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
    @include('ticketit::tickets.partials.summernote')
	@include('ticketit::tickets.partials.tags_footer_script')
@append
