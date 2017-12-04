<div class="modal fade comment-modal" id="modal-comment-new" tabindex="-1" role="dialog" aria-labelledby="modal-comment-new-Label">
    <div class="modal-dialog model-lg" role="document">
        <div class="modal-content">			
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">{{ trans('ticketit::lang.flash-x') }}</span></button>
                <h4 class="modal-title" id="modal-comment-new-Label">{{ trans('ticketit::lang.show-ticket-add-comment') }}</h4>
            </div>
            <div class="modal-body">

				<fieldset id="new_comment_modal_comment" class="fieldset-for-comment">
				{!! CollectiveForm::open([
					'method' => 'POST',
					'route' => $setting->grab('main_route').'-comment.store',
					'class' => 'form-horizontal',
					'enctype' => 'multipart/form-data'
				]) !!}
					{!! CollectiveForm::hidden('ticket_id', $ticket->id ) !!}
			
					@if ($u->canManageTicket($ticket->id))
						<div class="form-group">
							{!! CollectiveForm::label('type', trans('ticketit::lang.show-ticket-add-comment-type') . trans('ticketit::lang.colon'), ['class' => 'col-lg-2 control-label']) !!}
							<div class="col-lg-10">
								<button type="button" class="btn btn-default btn-info btn-sm response_type" id="popup_comment_btn_note" data-type="note" data-active-class="btn-info"><span  aria-hidden="true" class="glyphicons glyphicon glyphicon-pencil"></span> {{ trans('ticketit::lang.show-ticket-add-comment-note') }}</button>&nbsp;
								<button type="button" class="btn btn-default btn-sm response_type" id="popup_comment_btn_reply" data-type="reply"data-active-class="btn-warning"><span aria-hidden="true" class="glyphicons glyphicon glyphicon-envelope"></span> {{ trans('ticketit::lang.show-ticket-add-comment-reply') }}</button>
								{!! CollectiveForm::hidden('response_type', 'note',['id'=>'response_type'] ) !!}
							</div>
						</div>
					@endif					
					<div class="form-group">
						<div class="col-lg-12 summernote-text-wrapper">
							{!! CollectiveForm::textarea('content', null, ['class' => 'form-control summernote-editor', 'rows' => "3"]) !!}
						</div>
					</div>
					
					@if($setting->grab('ticket_attachments_feature'))
						<div class="form-group">
							{!! CollectiveForm::label('attachments', trans('ticketit::lang.attachments') . trans('ticketit::lang.colon'), [
								'class' => 'col-lg-2 control-label'
							]) !!}
							<div class="col-lg-10">
								<ul class="list-group">							
									@include('ticketit::shared.attach_files_button', ['attach_id' => 'comment_attached'])			
									<div id="comment_attached" class="panel-group grouped_check_list deletion_list attached_list"  data-new-attachment-edit-div="new_comment_modal_attachment" data-new-attachment-back-div="new_comment_modal_comment">
									
									</div>								
								</ul>
							</div>
						</div>						
					@endif
					
					@if ($u->canManageTicket($ticket->id))
						<div class="form-group">
							<div class="col-lg-12">
							<label><input type="checkbox" name="add_to_intervention" value="yes"> {{ trans('ticketit::lang.show-ticket-add-com-check-intervention') }}</label>
							</div>
							@if ($u->canCloseTicket($ticket->id))
								<div class="col-lg-12">
								<label><input type="checkbox" name="complete_ticket" value="yes" {{ ($ticket->comments->count()>0) ? '' : 'checked="checked"'}}> {{ trans('ticketit::lang.show-ticket-add-com-check-resolve') . trans('ticketit::lang.colon')}}</label>
								&nbsp;{!! CollectiveForm::select('status_id', $status_lists, $setting->grab('default_close_status_id'), []) !!}
								</div>
							@endif
						</div>
					@endif
					<div class="text-right col-md-12">
						{!! CollectiveForm::submit( trans('ticketit::lang.btn-submit'), ['class' => 'btn btn-primary']) !!}
					</div>
				{!! CollectiveForm::close() !!}
				</fieldset>
				
				<!-- Div edit attachment -->
				<fieldset id="new_comment_modal_attachment"  class="fieldset-for-attachment form-horizontal" style="display: none">		
					@include('ticketit::tickets.partials.attachment_form_fields')
					<button class="btn btn-default div-discard-attachment-update" data-edit-div="new_comment_modal_attachment" data-back-div="new_comment_modal_comment">{{ trans('ticketit::lang.discard') }}</button>
					<button class="btn btn-primary attachment_form_submit pull-right" data-edit-div="new_comment_modal_attachment" data-back-div="new_comment_modal_comment">{{ trans('ticketit::lang.update') }}</button>
				</fieldset>
			</div>
			
        </div>
    </div>
</div>