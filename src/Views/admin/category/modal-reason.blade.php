<div class="modal fade" id="reason-edit-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog model-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="tag-modal-Label">{{ trans('panichd::admin.category-edit-reason') }}</h4>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">{{ trans('panichd::lang.flash-x') }}</span></button>
            </div>
            <div class="modal-body">
                
				
				<form action="">
					<div class="form-group row">
						{!! CollectiveForm::label('reason', trans('panichd::admin.category-edit-reason-label') . trans('panichd::admin.colon'), ['class' => 'col-lg-2 col-form-label']) !!}
						
						<div class="col-lg-10">{!! CollectiveForm::text('text', null, [
								'id'=>'jquery_popup_reason_text',
								'class' => 'form-control', 'required'
							]) !!}
						</div>
					</div>
					
					<div class="form-group row">
						{!! CollectiveForm::label('status', trans('panichd::admin.category-edit-reason-status') . trans('panichd::admin.colon'), ['class' => 'col-lg-2 col-form-label']) !!}
						
						<div class="col-lg-10">{!! CollectiveForm::select('status_id', $status_lists, $setting->grab('default_close_status_id'), ['id' => 'jquery_popup_select_status', 'class' => 'form-control']) !!}
						</div>
					</div>
				</form>			

				<div class="modal-footer">					
					{!! CollectiveForm::button(trans('panichd::admin.btn-change'), ['id'=>'jquery_popup_reason_submit', 'class' => 'btn btn-primary']) !!}
				</div>
				
			</div>
		</div>
	</div>
</div>