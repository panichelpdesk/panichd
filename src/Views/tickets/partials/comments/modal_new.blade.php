<div class="modal fade comment-modal" id="modal-comment-new" tabindex="-1" role="dialog" aria-labelledby="modal-comment-new-Label">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
			<div class="modal-header">
                <h4 class="modal-title" id="modal-comment-new-Label">{{ trans('panichd::lang.show-ticket-add-comment') }}</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">{{ trans('panichd::lang.flash-x') }}</span></button>
            </div>
            <div class="modal-body">
				<div id="new_comment_errors" class="alert alert-danger" style="display: none;">
					<button type="button" class="close" data-dismiss="alert">{{ trans('panichd::lang.flash-x') }}</button>
					<ul></ul>
				</div>

				<fieldset id="new_comment_modal_comment" class="fieldset-for-comment">
				{!! CollectiveForm::open([
					'method' => 'POST',
					'route' => $setting->grab('main_route').'-comment.store',
					'enctype' => 'multipart/form-data'
				]) !!}
					{!! CollectiveForm::hidden('ticket_id', $ticket->id ) !!}

					@if ($u->currentLevel() > 1 && $u->canManageTicket($ticket->id))
						<div id="comment-type-buttons" class="form-group row">
							{!! CollectiveForm::label('type', trans('panichd::lang.show-ticket-add-comment-type') . trans('panichd::lang.colon'), ['class' => 'col-lg-2 col-form-label']) !!}
							<div class="col-lg-10">
								<button type="button" class="btn btn-light bg-info text-white btn-sm response_type" id="popup_comment_btn_note" data-type="note" data-active-class="bg-info text-white"><span  aria-hidden="true" class="fas fa-pencil-alt"></span> {{ trans('panichd::lang.show-ticket-add-comment-note') }}</button>&nbsp;
								<button type="button" class="btn btn-light btn-sm response_type" id="popup_comment_btn_reply" data-type="reply"data-active-class="bg-info text-white"><span aria-hidden="true" class="fas fa-comment"></span> {{ trans('panichd::lang.show-ticket-add-comment-reply') }}</button>
								{!! CollectiveForm::hidden('response_type', 'note',['id'=>'response_type'] ) !!}
							</div>
						</div>

                        @if($setting->grab('custom_recipients'))
                            <div class="form-group row">
                                <label class="col-lg-2 col-form-label">{{ trans('panichd::lang.show-ticket-add-comment-notificate') . trans('panichd::lang.colon') }}</label>
                                <div class="col-lg-10">
                                    <select name="reply_recipients[]" id="reply_recipients" class="form-control" multiple="multiple" style="display: none; width: 100%">
                                        @foreach ($c_members as $member)
                                            <option value="{{ $member->id }}" {{ in_array($member->id, $a_notifications['reply']) ? 'selected="selected"' : '' }}>{{ $member->name . ($member->email == "" ? ' ' . trans('panichd::lang.ticket-owner-no-email') : ' - ' . $member->email) }}
                                            @if ($setting->grab('departments_notices_feature'))
                                                @if ($member->ticketit_department == '0')
                                                    {{ ' - ' . trans('panichd::lang.create-ticket-notices') . ' ' . trans('panichd::lang.all-depts')}}
                                                @elseif ($member->ticketit_department != "")
                                                    {{ ' - ' . trans('panichd::lang.create-ticket-notices') . ' ' . $member->userDepartment->getFullName() }}
                                                @endif
                                            @endif
                                            </option>
                                        @endforeach
                                    </select>

                                    <select name="note_recipients[]" id="note_recipients" class="form-control" multiple="multiple" style="display: none; width: 100%">
                                        @foreach ($c_members as $member)
                                            <option value="{{ $member->id }}" {{ in_array($member->id, $a_notifications['note']) ? 'selected="selected"' : '' }}>{{ $member->name . ($member->email == "" ? ' ' . trans('panichd::lang.ticket-owner-no-email') : ' - ' . $member->email) }}
                                            @if ($setting->grab('departments_notices_feature'))
                                                @if ($member->ticketit_department == '0')
                                                    {{ ' - ' . trans('panichd::lang.create-ticket-notices') . ' ' . trans('panichd::lang.all-depts')}}
                                                @elseif ($member->ticketit_department != "")
                                                    {{ ' - ' . trans('panichd::lang.create-ticket-notices') . ' ' . $member->userDepartment->getFullName() }}
                                                @endif
                                            @endif
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        @endif
					@endif
					<div class="form-group row">
						<div class="col-lg-12 summernote-text-wrapper">
							{!! CollectiveForm::textarea('content', null, ['class' => 'form-control modal-summernote-editor', 'style' => 'display: none', 'rows' => "3"]) !!}
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
									@include('panichd::shared.attach_files_button', ['attach_id' => 'comment_attached'])
									<div id="comment_attached" class="panel-group grouped_check_list deletion_list attached_list"  data-new-attachment-edit-div="new_comment_modal_attachment" data-new-attachment-back-div="new_comment_modal_comment">

									</div>
								</ul>
							</div>
						</div>
					@endif
					@if ($u->currentLevel() > 1)
                        <div class="form-row" @if(!$setting->grab('custom_recipients')) style="display: none" @endif>
                            <div class="form-check">
    		                      <label><input type="checkbox" id="add_in_user_notification_text" name="add_in_user_notification_text" value="yes" @if(!$setting->grab('custom_recipients')) disabled="disabled" @endif> {{ trans('panichd::lang.show-ticket-add-com-check-email-text') }}</label>
    						</div>
                        </div>

                        @if ($u->canManageTicket($ticket->id))
                            <div class="form-row" style="display: none;">
                                <div class="form-check">
                                    <label><input type="checkbox" id="add_to_intervention" name="add_to_intervention" value="yes" disabled> {{ trans('panichd::lang.show-ticket-add-com-check-intervention') }}</label>
                                </div>
                            </div>
                        @endif


                        @if ($u->canManageTicket($ticket->id) && $u->canCloseTicket($ticket->id) && !$ticket->isComplete())
                            <div class="form-row align-items-center">
                                <div class="form-check">
                                   <label class="mb-0"><input type="checkbox" class="" name="complete_ticket" value="yes"> {{ trans('panichd::lang.show-ticket-add-com-check-resolve') . trans('panichd::lang.colon')}}</label>
                                &nbsp;
                                </div>
                                <div class="form-group col-auto mb-0">
                                    {!! CollectiveForm::select('status_id', $status_lists, $setting->grab('default_close_status_id'), [
                                        'class' => 'form-control'
                                    ]) !!}
                            </div>
                            </div>
                        @endif
					@endif

					<div class="text-right col-md-12">
						{!! CollectiveForm::submit( trans('panichd::lang.btn-add'), [
							'id' => 'new_comment_submit',
							'class' => 'btn btn-primary',
							'data-errors_div' => 'new_comment_errors'
						]) !!}
					</div>
				{!! CollectiveForm::close() !!}
				</fieldset>

				<!-- Div edit attachment -->
				<fieldset id="new_comment_modal_attachment"  class="fieldset-for-attachment" style="display: none">
					@include('panichd::tickets.partials.attachment_form_fields')
          <div class="mt-4 d-flex">
            <button class="btn btn-light div-discard-attachment-update mr-auto" data-edit-div="new_comment_modal_attachment" data-back-div="new_comment_modal_comment">{{ trans('panichd::lang.discard') }}</button>
	          <button class="btn btn-primary attachment_form_submit" data-edit-div="new_comment_modal_attachment" data-back-div="new_comment_modal_comment">{{ trans('panichd::lang.update') }}</button>
          </div>
        </fieldset>
			</div>

        </div>
    </div>
</div>
