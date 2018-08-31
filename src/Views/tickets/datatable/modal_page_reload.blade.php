<!-- Modal Dialog -->
<div class="modal fade" id="modal-page-reload" role="dialog" tabindex="-1">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title">{{ trans('panichd::lang.page-reload-modal-title') }}</h4>
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">{{ trans('panichd::lang.flash-x') }}</button>
      </div>
      <div class="modal-body">
        <p id="text_countdown">{!! trans('panichd::lang.page-reload-modal-countdown', ['num' => '5']) !!}</p>
        <p id="text_reloading" style="display: none">{!! trans('panichd::lang.page-reload-modal-reloading') !!}</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-light mr-auto" data-dismiss="modal">{{ trans('panichd::lang.btn-cancel') }}</button>
        <button type="button" class="btn btn-light" id="confirm">{{ trans('panichd::lang.page-reload-modal-button-now') }}</button>
      </div>
    </div>
  </div>
</div>
