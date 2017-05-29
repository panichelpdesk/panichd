<div class="modal fade" id="email-resend-modal" tabindex="-1" role="dialog" >
    <div class="modal-dialog model-lg" role="document">
        <div class="modal-content">			
			{!! CollectiveForm::open(['method' => 'POST', 'route' => $setting->grab('main_route').'-notification.resend', 'class' => 'form-horizontal']) !!}
			{!! CollectiveForm::hidden('comment_id', $comment->id, ['id'=>'comment_id']) !!}
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">{{ trans('ticketit::lang.flash-x') }}</span></button>
                <h4 class="modal-title">Reenviar notificacions per correu</h4>
            </div>
            <div class="modal-body">
				<fieldset>
					<div class="form-group">
						<div class="col-lg-12">
						<label><input type="checkbox" name="to_agent" value="yes"> A l'agent {{$ticket->agent->name}}</label>
						</div>
					</div>
					<div class="form-group">
						<div class="col-lg-12">
						<label><input type="checkbox" name="to_owner" value="yes" checked="checked"> A l'usuari <span id="owner"></span></label>
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