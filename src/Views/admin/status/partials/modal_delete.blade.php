@section('content')
<div class="modal fade" id="modal-status-delete" tabindex="-1" role="dialog" aria-labelledby="modal-status-delete-Label">
    <div class="modal-dialog model-lg" role="document">
        <div class="modal-content">			
			<div class="modal-header">
                <h4 class="modal-title">Delete Status</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">{{ trans('panichd::lang.flash-x') }}</span></button>
            </div>
            <div class="modal-body">
				<div class="alert alert-warning">
					<button type="button" class="close" data-dismiss="alert">{{ trans('panichd::lang.flash-x') }}</button>
					{!! trans('panichd::admin.status-delete-warning') !!}
				</div>

				<div class="form-group row"><!-- SUBJECT -->
					{!! CollectiveForm::label('status_id', trans('panichd::lang.status') . trans('panichd::lang.colon'), [
						'class' => 'col-form-label col-lg-3',
					]) !!}
					<div class="col-lg-9">
						<?php
						foreach($statuses_list as $key=>$value){
							$a_list = array_diff_key($statuses_list, [$key=>$value]);

							echo CollectiveForm::select('status_id', $a_list, null, [
								'style' => 'display: none',
								'id' => 'select_status_without_'.$key,
								'class' => 'form-control modal-status-select'
							]);
						}
						?>
					</div>
				</div>

				<div class="text-right col-md-12">
					{!! CollectiveForm::hidden('modal-status-id', null) !!}
					{!! CollectiveForm::button( trans('panichd::admin.btn-delete'), [
						'type' => 'button',
						'id' => 'submit_status_delete_modal',
						'class' => 'btn btn-primary'
					]) !!}
				</div>
			</div>			
        </div>
    </div>
</div>
@append