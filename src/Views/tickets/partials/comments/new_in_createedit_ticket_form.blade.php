<div class="mt-4 mb-3">
    <h3 style="margin-top: 0em;">{{ trans('panichd::lang.comments') }}
        <button type="button" id="add_comment" class="btn btn-light btn-default btn-sm">{{ trans('panichd::lang.show-ticket-add-comment') }}</button>
    </h3>
</div>
<div id="comments"></div>

<div id="comment_template" class="card bg-light mb-3 comment_block" style="display: none;">
    <div class="card-header pt-1 pr-3 pb-0 pl-2">
        <button type="button" class="pull-right btn btn-light btn-sm delete_comment" title="{{ trans('panichd::lang.show-ticket-delete-comment') }}">
        <span class="fa fa-times" aria-label="{{ trans('panichd::lang.btn-delete') }}" style="color: gray"></span></button>
        <h6 class="mt-1">
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
                    <i class="fas fa-exchange-alt"></i> <span class="text">{{ trans('panichd::lang.create-ticket-switch-to-comment') }}</span>
                </button>
            </span>
        </h6>
    </div>
    <div class="card-body">
        <input type="hidden" class="input_comment_num" name="form_comments[]" value="">
        <input type="hidden" class="input_response_type" name="response_x" value="note" disabled="disabled">
        <textarea style="display: none" rows="5" class="form-control input_comment_text" name="comment_x" cols="50" disabled="disabled"></textarea>
        <div class="jquery_error_text"></div>
    </div>
</div>
