<div id="comments" class="jquery_level2_show"></div>

<div id="comment_template" class="card bg-light mb-3 comment_block" style="display: none;">
    <div class="card-header pt-1 pr-3 pb-0 pl-2">
        <h6 class="mt-1 d-flex align-items-center">
            <span class="note_title">
                <span class="text-info mr-1"><i class="fas fa-pencil-alt" aria-hidden="true"></i></span>
                {{ trans('panichd::lang.note') }}
            </span>
            <span class="comment_title" style="display: none">
                <span class="text-info mr-1"><i class="fas fa-comment" aria-hidden="true"></i></span>
                {{ trans('panichd::lang.comment') }}
            </span>
            <span class="ml-2">
                <button class="btn btn-light btn-xs switch_response_type" data-note-text="{{ trans('panichd::lang.create-ticket-switch-to-comment') }}" data-comment-text="{{ trans('panichd::lang.create-ticket-switch-to-note') }}">
                    <i class="fas fa-comment"></i> <span class="text">{{ trans('panichd::lang.create-ticket-switch-to-comment') }}</span>
                </button>
            </span>
            <button type="button" class="ml-auto btn btn-light btn-sm delete_comment" title="{{ trans('panichd::lang.show-ticket-delete-comment') }}">
            <span class="fa fa-times" aria-label="{{ trans('panichd::lang.btn-delete') }}" style="color: gray"></span></button>
        </h6>
    </div>
    <div class="card-body">
        <input type="hidden" class="jquery_level2_enable input_comment_num" name="form_comments[]" value="">
        <input type="hidden" class="jquery_level2_enable input_response_type" name="response_x" value="note" disabled="disabled">

        @if($setting->grab('custom_recipients'))
            <div class="form-group row">
                <label class="col-lg-2 col-form-label">{{ trans('panichd::lang.show-ticket-add-comment-notificate') . trans('panichd::lang.colon') }}</label>
                <div class="col-lg-10">
                    <select class="form-control note_recipients" name="comment_x_recipients[]" multiple="multiple" style="display: none; width: 100%" disabled="disabled">
                        @foreach ($c_members->filter(function($q)use($u){ return $q->id != $u->id; }) as $member)
                            <option value="{{ $member->id }}">{{ $member->name . ($member->email == "" ? ' ' . trans('panichd::lang.ticket-owner-no-email') : ' - ' . $member->email) }}
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
                    <select class="form-control reply_recipients" name="comment_x_recipients[]" class="form-control" multiple="multiple" style="display: none; width: 100%" disabled="disabled">
                        @foreach ($c_members->filter(function($q)use($u){ return $q->id != $u->id; }) as $member)
                            <option value="{{ $member->id }}">{{ $member->name . ($member->email == "" ? ' ' . trans('panichd::lang.ticket-owner-no-email') : ' - ' . $member->email) }}
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
        <div>
            <textarea style="display: none" rows="5" class="form-control jquery_level2_enable input_comment_text" name="comment_x" cols="50" disabled="disabled"></textarea>
            <div class="jquery_error_text"></div>
        </div>
        @if($setting->grab('ticket_attachments_feature'))
        <div class="form-group row mt-2">
            {!! CollectiveForm::label('attachments', trans('panichd::lang.attachments') . trans('panichd::lang.colon'), [
                'class' => 'col-lg-2 col-form-label'
            ]) !!}
            <div class="col-lg-10">
                <ul class="list-group">
                    @include('panichd::shared.attach_files_button', ['attach_id' => 'comment_template_attached'])
                    <div id="comment_template_attached" class="panel-group grouped_check_list deletion_list attached_list" data-new-attachment-modal-id="modal-attachment-edit">

                    </div>
                </ul>
            </div>
        </div>
    @endif
        <label class="mt-2" @if(!$setting->grab('custom_recipients')) style="display: none" @endif><input type="checkbox" class="input_comment_notification_text" name="comment_x_notification_text" value="yes" @if(!$setting->grab('custom_recipients')) disabled="disabled" @endif> {{ trans('panichd::lang.show-ticket-add-com-check-email-text') }}</label>
    </div>
</div>
