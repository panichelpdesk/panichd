@section('content')
<div class="modal fade" id="modal-attachment-edit" tabindex="-1" role="dialog" aria-labelledby="modal-attachment-edit-Label">
    <div class="modal-dialog model-lg" role="document">
        <div class="modal-content">			
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">{{ trans('ticketit::lang.flash-x') }}</span></button>
                <h4 class="modal-title">{{ trans('ticketit::lang.attachment-edit') }}</h4>
            </div>
            <div class="modal-body">
				<fieldset class="form-horizontal">					
					@include('ticketit::tickets.partials.attachment_form_fields')
					
					{!! CollectiveForm::hidden(null, 'modal-attachment-edit', ['id'=>'hide_modal_id']) !!}
					
					
					<div class="text-right col-md-12">						
						{!! CollectiveForm::button( trans('ticketit::lang.update'), [
							'type' => 'button',
							'class' => 'btn btn-primary attachment_form_submit'
						]) !!}
					</div>
				</fieldset>
			</div>			
        </div>
    </div>
</div>
@append