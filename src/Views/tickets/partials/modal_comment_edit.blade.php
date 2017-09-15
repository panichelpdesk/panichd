<div class="modal fade jquery_panel_hightlight comment-modal" id="comment-modal-edit-{{$comment->id}}" tabindex="-1" role="dialog" aria-labelledby="comment-modal-edit-{{$comment->id}}-Label">
    <div class="modal-dialog model-lg" role="document">
        <div class="modal-content">			
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">{{ trans('ticketit::lang.flash-x') }}</span></button>
                <h4 class="modal-title">{{ trans('ticketit::lang.show-ticket-edit-comment') }}</h4>
            </div>
            <div class="modal-body">
				<fieldset id="edit_comment_{{ $comment->id }}_comment" class="fieldset-for-comment">
					{!! CollectiveForm::open([
						'method' => 'PATCH',
						'route' => [$setting->grab('main_route').'-comment.update',$comment->id],
						'class' => 'form-horizontal',
						'enctype' => 'multipart/form-data'
					]) !!}
					<div class="form-group">
						<div class="col-lg-12">
							{!! CollectiveForm::textarea('content', $comment->html, ['class' => 'form-control summernote-editor', 'rows' => "3"]) !!}
						</div>
					</div>
					@if($setting->grab('ticket_attachments_feature'))
						<div class="form-group">
							{!! CollectiveForm::label('attachments', trans('ticketit::lang.attachments') . trans('ticketit::lang.colon'), [
								'class' => 'col-lg-2 control-label'
							]) !!}
							<div class="col-lg-10">
								<ul class="list-group">							
									@include('ticketit::shared.attach_files_button', ['attach_id' => 'comment_'.$comment->id.'_attached'])			
									<div id="comment_{{ $comment->id }}_attached" class="panel-group grouped_check_list deletion_list attached_list" data-new-attachment-edit-div="edit_comment_{{ $comment->id  }}_attachment" data-new-attachment-back-div="edit_comment_{{ $comment->id }}_comment">
									@foreach($comment->attachments as $attachment)
										@include('ticketit::tickets.partials.attachment', [
											'template'=>'createedit',
											'edit_div' => 'edit_comment_'.$comment->id.'_attachment',
											'back_div' => 'edit_comment_'.$comment->id.'_comment',
											'attach_id' => 'comment_'.$comment->id.'_attached'
										])
									@endforeach
									</div>
								</ul>
							</div>
						</div>						
					@endif
					<div class="form-group">
						<div class="col-lg-12">
						<label><input type="checkbox" name="add_to_intervention" value="yes"> {{ trans('ticketit::lang.show-ticket-edit-com-check-int') }}</label>
						</div>
					</div>
					<div class="text-right col-md-12">
						{!! CollectiveForm::submit( 'Desar', ['class' => 'btn btn-primary']) !!}
					</div>
					{!! CollectiveForm::close() !!}
				</fieldset>
				
				<!-- Div edit attachment -->
				<fieldset id="edit_comment_{{ $comment->id }}_attachment"  class="fieldset-for-attachment form-horizontal" style="display: none">		
					@include('ticketit::tickets.partials.attachment_form_fields')
					<button class="btn btn-default div-discard-attachment-update" data-edit-div="edit_comment_{{ $comment->id }}_attachment" data-back-div="edit_comment_{{ $comment->id }}_comment">{{ trans('ticketit::lang.discard') }}</button>
					<button class="btn btn-primary attachment_form_submit pull-right" data-edit-div="edit_comment_{{ $comment->id }}_attachment" data-back-div="edit_comment_{{ $comment->id }}_comment">{{ trans('ticketit::lang.update') }}</button>
				</fieldset>
			</div>
			
        </div>
    </div>
</div>