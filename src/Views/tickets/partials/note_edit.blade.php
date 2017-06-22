<div class="modal fade jquery_panel_hightlight" id="comment-modal-edit-{{$comment->id}}" tabindex="-1" role="dialog" aria-labelledby="comment-modal-edit-{{$comment->id}}-Label">
    <div class="modal-dialog model-lg" role="document">
        <div class="modal-content">			
			{!! CollectiveForm::open(['method' => 'PATCH', 'route' => [$setting->grab('main_route').'-comment.update',$comment->id], 'class' => 'form-horizontal']) !!}
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