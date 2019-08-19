<div class="modal fade jquery_panel_hightlight comment-modal" id="comment-modal-edit-{{$comment->id}}" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">{{ trans('panichd::lang.edit-internal-note-title') }}</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">{{ trans('panichd::lang.flash-x') }}</span></button>
            </div>
            <div class="modal-body">
				<div id="edit_comment_errors" class="alert alert-danger" style="display: none;">
					<button type="button" class="close" data-dismiss="alert">{{ trans('panichd::lang.flash-x') }}</button>
					<ul></ul>
				</div>

				<fieldset id="edit_comment_{{ $comment->id }}_comment" class="fieldset-for-comment">
					{!! CollectiveForm::open([
						'method' => 'PATCH',
						'route' => [$setting->grab('main_route').'-comment.update',$comment->id],
						'enctype' => 'multipart/form-data'
					]) !!}
					<div class="form-group row">
						<div class="col-lg-12 summernote-text-wrapper">
							{!! CollectiveForm::textarea('content', $comment->html, ['class' => 'form-control modal-summernote-editor', 'style' => 'display: none', 'rows' => "3"]) !!}
							<div class="jquery_error_text"></div>
						</div>
					</div>
					@if($setting->grab('ticket_attachments_feature'))
						<div class="form-group row">
							{!! CollectiveForm::label('attachments', trans('panichd::lang.attachments') . trans('panichd::lang.colon'), [
								'class' => 'col-lg-2 col-form-label'
							]) !!}
							<div class="col-lg-10">
								<ul class="list-group">
									@include('panichd::shared.attach_files_button', ['attach_id' => 'comment_'.$comment->id.'_attached'])
									<div id="comment_{{ $comment->id }}_attached" class="panel-group grouped_check_list deletion_list attached_list" data-new-attachment-edit-div="edit_comment_{{ $comment->id  }}_attachment" data-new-attachment-back-div="edit_comment_{{ $comment->id }}_comment">
									@foreach($comment->attachments as $attachment)
										@include('panichd::tickets.partials.attachment', [
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

					<div class="text-right col-md-12">
						{!! CollectiveForm::submit( trans('panichd::lang.update'), [
							'class' => 'btn btn-primary ajax_form_submit',
							'data-errors_div' => 'edit_comment_errors'
						]) !!}
					</div>
					{!! CollectiveForm::close() !!}
				</fieldset>

				<!-- Div edit attachment -->
				<fieldset id="edit_comment_{{ $comment->id }}_attachment"  class="fieldset-for-attachment" style="display: none">
					@include('panichd::tickets.partials.attachment_form_fields')
          <div class="mt-4 d-flex">
            <button class="btn btn-light div-discard-attachment-update mr-auto" data-edit-div="edit_comment_{{ $comment->id }}_attachment" data-back-div="edit_comment_{{ $comment->id }}_comment">{{ trans('panichd::lang.discard') }}</button>
  					<button class="btn btn-primary attachment_form_submit" data-edit-div="edit_comment_{{ $comment->id }}_attachment" data-back-div="edit_comment_{{ $comment->id }}_comment">{{ trans('panichd::lang.update') }}</button>
          </div>
				</fieldset>
			</div>

        </div>
    </div>
</div>
