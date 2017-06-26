<div class="modal fade" id="reason-edit-modal" tabindex="-1" role="dialog">
    <div class="modal-dialog model-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">{{ trans('ticketit::lang.flash-x') }}</span></button>
                <h4 class="modal-title" id="tag-edit-modal-Label">{{ trans('ticketit::admin.category-edit-reason') }}</h4>
            </div>
            <div class="modal-body">
                
				<div class="form-group">
					{!! CollectiveForm::label('reason', trans('ticketit::admin.category-edit-reason-label') . trans('ticketit::admin.colon'), ['class' => 'col-lg-2 control-label']) !!}
					
					<div class="col-lg-10">{!! CollectiveForm::text('text', null, [
							'id'=>'jquery_popup_reason_input',
							'class' => 'form-control', 'required'
						]) !!}
					</div>
				</div>
				<div class="clearfix"></div>
				<br />
				
				

				<div class="modal-footer">					
					{!! CollectiveForm::button(trans('ticketit::admin.btn-save'), ['id'=>'jquery_popup_reason_submit', 'class' => 'btn btn-primary']) !!}
				</div>
				
			</div>
		</div>
	</div>
</div>