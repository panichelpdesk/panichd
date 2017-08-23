@section('content')
<div class="modal fade" id="modal-attachment-edit" tabindex="-1" role="dialog" aria-labelledby="modal-attachment-edit-Label">
    <div class="modal-dialog model-lg" role="document">
        <div class="modal-content">			
			<div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">{{ trans('ticketit::lang.flash-x') }}</span></button>
                <h4 class="modal-title" id="ticket-comment-modal-Label">{{ trans('ticketit::lang.attachment-edit') }}</h4>
            </div>
            <div class="modal-body">
				<fieldset class="form-horizontal">					
					<div class="form-group">
						{!! CollectiveForm::label('original_filename', trans('ticketit::lang.attachment-edit-original-filename') . trans('ticketit::lang.colon'), ['class' => 'col-md-3 control-label']) !!}
						<div class="col-md-9">
							<span id="modal_attachment_original_filename"></span>
						</div>
					</div>
										
					<div class="form-group">
						{!! CollectiveForm::label('new_filename', trans('ticketit::lang.attachment-edit-new-filename') . trans('ticketit::lang.colon'), ['class' => 'col-md-3 control-label']) !!}
						<div class="col-md-9">
							{!! CollectiveForm::text('new_filename', null , [
								'id' => 'modal_attachment_new_filename',
								'class' => 'form-control',
								]) !!} 
						</div>						 
					</div>
					
					<div class="form-group">
						{!! CollectiveForm::label('description', trans('ticketit::lang.description') . trans('ticketit::lang.colon'), ['class' => 'col-md-3 control-label']) !!}
						<div class="col-md-9">
							{!! CollectiveForm::text('description', null , [
								'id' => 'modal_attachment_description',
								'class' => 'form-control',
								]) !!} 
						</div>						 
					</div>
					
					<div class="text-right col-md-12">
						{!! CollectiveForm::button( trans('ticketit::lang.btn-submit'), [
							'type' => 'button',
							'id' => 'modal_attachment_edit_submit',
							'class' => 'btn btn-primary',
							'data-prefix' => ''
						]) !!}
					</div>
				</fieldset>
			</div>			
        </div>
    </div>
</div>
@append

@section('footer')
<script type="text/javascript">
$(function(){
	$( "#modal-attachment-edit" ).on('show.bs.modal', function (e) {
		var button = $(e.relatedTarget);
		var prefix = $(button).data('prefix');

		$(this).find('#modal_attachment_original_filename').text($(button).data('original_filename'));
		$(this).find('#modal_attachment_new_filename').val($('#'+prefix+'new_filename').val());
		$(this).find('#modal_attachment_description').val($('#'+prefix+'description').val());
		$(this).find('#modal_attachment_edit_submit').attr('data-prefix', prefix);
		
		//alert($(this).find('#modal_attachment_edit_submit').data('prefix'));
	});
	
	$('#modal_attachment_edit_submit').click(function(e){
		var prefix = $(this).data('prefix');
		var original_filename = $('#modal-attachment-edit #modal_attachment_original_filename').text();
		var new_filename = $('#modal-attachment-edit #modal_attachment_new_filename').val();
		
		// Fields
		$('#'+prefix+'new_filename').val(new_filename);		
		$('#'+prefix+'description').val($('#modal-attachment-edit #modal_attachment_description').val());
		
		// Display values
		if (original_filename == new_filename){
			$('#'+prefix+'display_original_filename').text('');
		}else{
			$('#'+prefix+'display_original_filename').text(' - '+original_filename);
		}
		$('#'+prefix+'display_new_filename').text(new_filename);
		if ($('#modal-attachment-edit #modal_attachment_description').val() != ""){
			$('#'+prefix+'display_description').text($('#modal-attachment-edit #modal_attachment_description').val());
		}else{
			$('#'+prefix+'display_description').text($('#'+prefix+'display_description').data('mimetype'));
		}
		
		$('#modal-attachment-edit').modal('hide');
		e.preventDefault();
	});
});
</script>
@append