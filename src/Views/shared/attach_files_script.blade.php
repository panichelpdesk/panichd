@section('footer')
<script type="text/javascript">
$(function(){
	// Attach files button
	$('.btn_attach').on('click', function(e){
		
		var elem = $('<input type="file" name="attachments[]" class="full_file_inputs" data-attach-id="'+$(this).data('attach-id')+'" style="display: none" multiple>').prop('id', 'full_input_'+$('.full_file_inputs').length);	
		
		$(elem).insertAfter('#'+$(this).data('attach-id'));
		
		elem.trigger('click');
	});

	// Add each attached file name to list when selected
	$(document).on('change', '.full_file_inputs', function() {
		var input = $(this),
			files = $(this).prop('files'),
			numFiles = input.get(0).files ? input.get(0).files.length : 1;	
		
		var list_i = $('#'+$(this).data('attach-id')+' .panel').length;
		
		for(var i=0,file;file=files[i];i++) {
			var num = list_i+i;
			var html = '<div class="panel panel-default text-warning check_parent unchecked check_related_bg"><div class="panel-body"><div class="media">'
				
				// Upload icon
				+'<div class="media-left"><span class="media-object glyphicon glyphicon-upload" title="{{ trans('ticketit::lang.pending-attachment') }}" style="cursor: help"></span></div>'
				
				// Filename
				+'<div class="media-body check_related_text">'
				+'<div><span id="new_attachment_'+num+'_display_new_filename">'+file.name+'</span> <s id="new_attachment_'+num+'_display_original_filename"></s>'
				
				// Button
				+'<button type="button" role="button" class="btn btn-default btn-xs edit_attachment"';
				
			if ($('#'+$(this).data('attach-id')).data('new-attachment-modal-id')){
				html += ' data-modal-id="'+$('#'+$(this).data('attach-id')).data('new-attachment-modal-id')+'"';
			}else{
				html += ' data-edit-div="'+$('#'+$(this).data('attach-id')).data('new-attachment-edit-div')+'" data-back-div="'+$('#'+$(this).data('attach-id')).data('new-attachment-back-div')+'"';
			}		
				
				html +=' data-original_filename="'+file.name+'" data-prefix="new_attachment_'+num+'_" style="margin: 0em 0em 0em 1em;">{{ trans('ticketit::lang.btn-edit') }}</button>'
				+'<input type="hidden" id="new_attachment_'+num+'_new_filename" name="attachment_new_filenames[]" value="'+file.name+'">'
				+'<input type="hidden" id="new_attachment_'+num+'_description" name="attachment_descriptions[]" value="">'
				+'</div>'
				
				// Description
				+'<span id="new_attachment_'+num+'_display_description" class="text-muted" data-mimetype=""></span>'
				+'</div>'
				
				// Block button
				+'<div class="media-right media-middle">'					
				+'<a href="#" class="check_button" data-delete_id="delete_new_attachment_check_'+num+'"><span class="media-object pull-right glyphicon glyphicon-remove" aria-hidden="true"></span><span class="media-object  pull-right glyphicon glyphicon-ok" aria-hidden="true" style="display: none"></span></a>'
				+'<input type="checkbox" id="delete_new_attachment_check_'+num+'" name="block_file_names[]" value="'+file.name+'" checked="checked" style="display: none" disabled="disabled"></div>'
				+'</div></div></div>';
			
			$('#'+$(this).data('attach-id')).append($(html));
		}	
	});

	// Edit attachment button
	$(document).on('click', '.edit_attachment', function(e){
		var editdiv = '#' + ($(this).attr('data-modal-id') ? $(this).data('modal-id') : $(this).data('edit-div'));		
		var prefix = $(this).data('prefix');
		
		$(editdiv).find('#attachment_form_original_filename').text($(this).data('original_filename'));
		$(editdiv).find('#attachment_form_new_filename').val($('#'+prefix+'new_filename').val());
		$(editdiv).find('#attachment_form_description').val($('#'+prefix+'description').val());
		$(editdiv).find('#attachment_form_prefix').val(prefix);			
		
		if ($(this).attr('data-modal-id')){
			$('#'+$(this).data('modal-id')).modal('show');
		}else{
			$(editdiv).show();
			$('#'+$(this).data('back-div')).hide();
		}
		
		e.preventDefault();
	});
	
	// Discard attachment modifications in div
	$(document).on('click', '.div-discard-attachment-update', function(e){
		$('#'+$(this).data('edit-div')).hide();
		$('#'+$(this).data('back-div')).show();
	});
	
	// Update attachment button
	$(document).on('click', '.attachment_form_submit', function(e){
		
		var fieldset = $(this).closest('fieldset');
		
		var prefix = $(fieldset).find('#attachment_form_prefix').val();		
		
		var original_filename = $(fieldset).find('#attachment_form_original_filename').text();
		var new_filename = $(fieldset).find('#attachment_form_new_filename').val();
		
		// Fields
		$('#'+prefix+'new_filename').val(new_filename);		
		$('#'+prefix+'description').val($(fieldset).find('#attachment_form_description').val());
		
		// Display values
		if (original_filename == new_filename){
			$('#'+prefix+'display_original_filename').text('');
		}else{
			$('#'+prefix+'display_original_filename').text(' - '+original_filename);
		}
		$('#'+prefix+'display_new_filename').text(new_filename);
		if ($(fieldset).find('#attachment_form_description').val() != ""){
			$('#'+prefix+'display_description').text($(fieldset).find('#attachment_form_description').val());
		}else{
			$('#'+prefix+'display_description').text($('#'+prefix+'display_description').data('mimetype'));
		}		
		
		if ($(fieldset).find('#hide_modal_id').length){
			$('#'+$(fieldset).find('#hide_modal_id').val()).modal('hide');
		}else{
			$('#'+$(this).data('edit-div')).hide();
			$('#'+$(this).data('back-div')).show();
		}		
				
		e.preventDefault();
	});
});
</script>
@include('ticketit::shared.grouped_check_list')
@append