<div id="email-resend-modal-{{$comment->id}}" class="modal fade jquery_panel_hightlight notification-resend-modal" tabindex="-1" role="dialog" >
    <div class="modal-dialog model-lg" role="document">
        <div class="modal-content">
			{!! CollectiveForm::open(['method' => 'POST', 'route' => $setting->grab('main_route').'-notification.resend']) !!}
			{!! CollectiveForm::hidden('comment_id', $comment->id, ['id'=>'comment_id']) !!}
			<div class="modal-header">
                <h4 class="modal-title">{{ trans('panichd::lang.show-ticket-email-resend') }}</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">{{ trans('panichd::lang.flash-x') }}</span></button>
            </div>
            <div class="modal-body">
				<fieldset>
                    @foreach($a_resend_notifications[$comment->id] as $recipient)
                        <div class="form-group form-check">
                            <label><input type="checkbox" class="form-check-input" name="recipients[]" value="{{ ($recipient->member_id == "" ? $recipient->email : $recipient->member_id) }}"> {{ $recipient->name . ' - ' . $recipient->email }}
                            @if($recipient->email == $ticket->agent->email)
                                {{ trans('panichd::lang.show-ticket-email-resend-agent') }}
                            @elseif($recipient->email == $ticket->owner->email)
                                {{ trans('panichd::lang.show-ticket-email-resend-owner') }}
                            @endif
                            </label>
                        </div>
                    @endforeach

					<div class="text-right">
						{!! CollectiveForm::submit( trans('panichd::lang.btn-submit'), ['class' => 'btn btn-primary']) !!}
					</div>

				</fieldset>
			</div>
			{!! CollectiveForm::close() !!}
        </div>
    </div>
</div>
