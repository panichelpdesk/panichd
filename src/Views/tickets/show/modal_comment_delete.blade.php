<!-- Modal Dialog -->
<div class="modal fade jquery_panel_hightlight" id="modal-comment-delete" role="dialog" tabindex="-1">
  <div class="modal-dialog model-lg" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ trans('panichd::lang.show-ticket-delete-comment') }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">{{ trans('panichd::lang.flash-x') }}</button>
      </div>
      <div class="modal-body">
        <p>{{ trans('panichd::lang.show-ticket-delete-comment-msg') }}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light" data-dismiss="modal">{{ trans('panichd::lang.btn-cancel') }}</button>
        <button type="button" class="btn btn-danger" id="delete-comment-submit">{{ trans('panichd::lang.btn-delete') }}</button>
      </div>
    </div>
  </div>
</div>
