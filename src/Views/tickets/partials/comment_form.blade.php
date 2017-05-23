<div class="modal fade" id="ticket-comment-modal" tabindex="-1" role="dialog" aria-labelledby="ticket-comment-modal-Label">
    <div class="modal-dialog model-lg" role="document">
        <div class="modal-content">			
			{!! CollectiveForm::open(['method' => 'POST', 'route' => $setting->grab('main_route').'-comment.store', 'class' => 'form-horizontal']) !!}
			{!! CollectiveForm::hidden('ticket_id', $ticket->id ) !!}
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">{{ trans('ticketit::lang.flash-x') }}</span></button>
                <h4 class="modal-title" id="ticket-comment-modal-Label">Nou comentari</h4>
            </div>
            <div class="modal-body">
				<fieldset>
					<div class="form-group">
						<div class="col-lg-12">
							{!! CollectiveForm::textarea('content', null, ['class' => 'form-control summernote-editor', 'rows' => "3"]) !!}
						</div>
					</div>

					<div class="text-right col-md-12">
						{!! CollectiveForm::submit( trans('ticketit::lang.btn-submit'), ['class' => 'btn btn-primary']) !!}
					</div>

				</fieldset>
			</div>
			{!! CollectiveForm::close() !!}
        </div>
    </div>
</div>