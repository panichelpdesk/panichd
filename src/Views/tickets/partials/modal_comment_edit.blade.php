<div class="modal fade jquery_panel_hightlight" id="comment-modal-edit-{{$comment->id}}" tabindex="-1" role="dialog" aria-labelledby="comment-modal-edit-{{$comment->id}}-Label">
    <div class="modal-dialog model-lg" role="document">
        <div class="modal-content">			
			{!! CollectiveForm::open([
				'method' => 'PATCH',
				'route' => [$setting->grab('main_route').'-comment.update',$comment->id],
				'class' => 'form-horizontal',
				'enctype' => 'multipart/form-data'
			]) !!}
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">{{ trans('ticketit::lang.flash-x') }}</span></button>
                <h4 class="modal-title" id="ticket-comment-modal-Label">{{ trans('ticketit::lang.show-ticket-edit-comment') }}</h4>
            </div>
            <div class="modal-body">
				<fieldset>
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
									@include('ticketit::shared.attach_files', [
										'only' => 'button',
										'attach_id' => 'comment_'.$comment->id.'_attached'
									])			
									<div id="comment_{{ $comment->id }}_attached" class="panel-group grouped_check_list deletion_list attached_list">
									@foreach($comment->attachments as $attachment)
										@include('ticketit::tickets.partials.attachment', ['template'=>'createedit'])
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
				</fieldset>
			</div>
			{!! CollectiveForm::close() !!}
        </div>
    </div>
</div>