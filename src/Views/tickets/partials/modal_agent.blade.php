<!-- Modal Dialog -->
<div class="modal fade" id="agentChange" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">{{ trans('ticketit::lang.flash-x') }}</button>
        <h4 class="modal-title">Canviar agent assignat</h4>
      </div>
      <div class="modal-body">
      {!! CollectiveForm::model($category, [
			'route' => [$setting->grab('main_route').'.category.update', $category->id],
			'method' => 'PATCH',
			'class' => 'form-horizontal'
			]) !!}
        <legend>Agents possibles</legend>
        
      {!! CollectiveForm::close() !!} 
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">{{ trans('ticketit::lang.btn-cancel') }}</button>
        <button type="button" class="btn btn-danger" id="confirm">Canviar</button>
      </div>
    </div>
  </div>
</div>
