@if (!isset($only) || (isset($only) && $only=="button"))
	<button type="button" class="btn btn-default btn_attach" data-attach-id="{{ $attach_id }}" style="margin: 0em 0em 1em 0em;"><span class="glyphicon glyphicon-file" aria-hidden="true"></span> {{ trans('ticketit::lang.attach-files') }}</span></button>
@endif

@if (!isset($only) || (isset($only) && $only=="script"))
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
				var check = $('<div class="panel panel-default text-warning check_parent unchecked check_related_bg"><div class="panel-body"><div class="media">'
					
					// Upload icon
					+'<div class="media-left"><span class="media-object glyphicon glyphicon-upload" title="{{ trans('ticketit::lang.pending-attachment') }}" style="cursor: help"></span></div>'
					
					// Filename
					+'<div class="media-body check_related_text">'
					+'<div><span id="new_attachment_'+num+'_display_new_filename">'+file.name+'</span> <s id="new_attachment_'+num+'_display_original_filename"></s>'
					
					// Button
					+'<button type="button" role="button" class="btn btn-default btn-xs" data-toggle="modal" data-target="#modal-attachment-edit" data-original_filename="'+file.name+'" data-prefix="new_attachment_'+num+'_" style="margin: 0em 0em 0em 1em;">{{ trans('ticketit::lang.btn-edit') }}</button>'
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
					+'</div></div></div>');
				
				$('#'+$(this).data('attach-id')).append(check);
			}	
		});
	});
	</script>
	@include('ticketit::shared.grouped_check_list')
@append
@endif