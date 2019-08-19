@section('content')
<div class="modal fade" id="modal-attachment-edit" tabindex="-1" role="dialog" aria-labelledby="modal-attachment-edit-Label">
    <div class="modal-dialog model-lg" role="document">
        <div class="modal-content">			
			<div class="modal-header">
                <h4 class="modal-title">{{ trans('panichd::lang.attachment-edit') }}</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">{{ trans('panichd::lang.flash-x') }}</span></button>
            </div>
            <div class="modal-body">
                <!-- Div edit attachment -->
				<fieldset id="edit_ticket_attachment">
                    @include('panichd::tickets.partials.attachment_form_fields')

                    {!! CollectiveForm::hidden(null, 'modal-attachment-edit', ['id'=>'hide_modal_id']) !!}


                    <div class="text-right col-md-12">
                        {!! CollectiveForm::button( trans('panichd::lang.update'), [
                            'type' => 'button',
                            'class' => 'btn btn-primary attachment_form_submit',
                            'data-back-div' => 'ticket_attached'
                        ]) !!}
                    </div>
				</fieldset>
			</div>			
        </div>
    </div>
</div>
@append