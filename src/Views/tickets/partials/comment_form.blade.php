<div class="modal fade" id="ticket-comment-modal" tabindex="-1" role="dialog" aria-labelledby="ticket-comment-modal-Label">
    <div class="modal-dialog model-lg" role="document">
        <div class="modal-content">			
			{!! CollectiveForm::open(['method' => 'POST', 'route' => $setting->grab('main_route').'-comment.store', 'class' => 'form-horizontal']) !!}
			{!! CollectiveForm::hidden('ticket_id', $ticket->id ) !!}
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">{{ trans('ticketit::lang.flash-x') }}</span></button>
                <h4 class="modal-title" id="ticket-comment-modal-Label">Afegir comentari</h4>
            </div>
            <div class="modal-body">

				<fieldset>
					@if ($u->canManageTicket($ticket->id))
						<div class="form-group">
							{!! CollectiveForm::label('type', 'Tipus' . trans('ticketit::lang.colon'), ['class' => 'col-lg-2 control-label']) !!}
							<div class="col-lg-10">
								<button type="button" class="btn btn-default btn-info btn-sm response_type" id="popup_comment_btn_note" data-type="note" data-active-class="btn-info">Nota interna</button>&nbsp;
								<button type="button" class="btn btn-default btn-sm response_type" id="popup_comment_btn_reply" data-type="reply"data-active-class="btn-warning">Resposta a usuari</button>
								{!! CollectiveForm::hidden('response_type', 'note',['id'=>'response_type'] ) !!}
							</div>
						</div>
					@endif					
					<div class="form-group">
						<div class="col-lg-12">
							{!! CollectiveForm::textarea('content', null, ['class' => 'form-control summernote-editor', 'rows' => "3"]) !!}
						</div>
					</div>
					@if ($u->canManageTicket($ticket->id))
						<div class="form-group">
							<div class="col-lg-12">
							<label><input type="checkbox" name="add_to_intervention" value="yes" checked="checked"> Afegir aquesta resposta al camp actuació</label>
							</div>
							@if ($u->canCloseTicket($ticket->id))
								<div class="col-lg-12">
								<label><input type="checkbox" name="complete_ticket" value="yes" checked="checked"> Resoldre el tiquet amb estat</label>
								&nbsp;{!! CollectiveForm::select('status_id', $status_lists, $setting->grab('default_close_status_id'), []) !!}
								</div>
							@endif
						</div>
					@endif

					<div class="text-right col-md-12">
						{!! CollectiveForm::submit( trans('ticketit::lang.btn-submit'), ['class' => 'btn btn-primary']) !!}
					</div>

				</fieldset>
			</div>
			{!! CollectiveForm::close() !!}
        </div>
    </div>
</div>