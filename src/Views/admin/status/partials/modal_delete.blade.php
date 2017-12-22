@section('content')
<div class="modal fade" id="modal-status-delete" tabindex="-1" role="dialog" aria-labelledby="modal-status-delete-Label">
    <div class="modal-dialog model-lg" role="document">
        <div class="modal-content">			
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">{{ trans('panichd::lang.flash-x') }}</span></button>
                <h4 class="modal-title">Delete Status</h4>
            </div>
            <div class="modal-body">
				<div class="col-lg-12">x tiquets requereixen un nou estat</div>
				<fieldset class="form-horizontal">					
					
					<div class="form-group"><!-- SUBJECT -->
					{!! CollectiveForm::label('status_id', trans('panichd::lang.status') . trans('panichd::lang.colon'), [
						'class' => 'control-label col-lg-3',
					]) !!}
					<div class="col-lg-9">
						{!! CollectiveForm::select('status_id', $statuses_list, null, ['id' => 'select_status', 'class' => 'form-control']) !!}
					</div>
				</div>
					
					<div class="text-right col-md-12">						
						{!! CollectiveForm::hidden('tickets_new_status_id', null) !!}
						{!! CollectiveForm::hidden('status_id', null) !!}
						{!! CollectiveForm::button( trans('panichd::admin.btn-delete'), [
							'type' => 'button',
							'id' => 'submit_status_delete_modal',
							'class' => 'btn btn-primary'
						]) !!}
					</div>
				</fieldset>
			</div>			
        </div>
    </div>
</div>
@append