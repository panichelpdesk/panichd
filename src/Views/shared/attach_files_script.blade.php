@section('footer')
<script type="text/javascript">
$(function(){
	// Attach files button
	$(document).on('click', '.btn_attach', function(e){

		var elem = $('<input type="file" name="' + $(this).data('attachments_prefix') + 'attachments[]" class="full_file_inputs" data-attach-id="' + $(this).data('attach-id') + '" data-attachments_prefix="' + $(this).data('attachments_prefix') + '" style="display: none" multiple>').prop('id', 'full_input_'+$('.full_file_inputs').length);

		$('#'+$(this).data('attach-id')).append(elem);

		elem.trigger('click');
	});

	// Add each attached file name to list when selected
	$(document).on('change', '.full_file_inputs', function() {
		var input = $(this),
			files = $(this).prop('files');

		var n_existent = $('.jquery_attachment_block').length;

		for(var i=0,file;file=files[i];i++) {
			var num = n_existent + i;
			var html = '<div><div id="' + $(this).data('attachments_prefix') + 'attachment_block_'+num+'" class="jquery_attachment_block card bg-default text-warning check_parent unchecked check_related_bg"><div class="card-body"><div class="media">'

				// Upload icon
				+'<div class="media-left mr-3"><span class="media-object fa fa-upload text-warning" style="font-size: 30px" title="{{ trans('panichd::lang.pending-attachment') }}" style="cursor: help"></span></div>'

				// Filename
				+'<div class="media-body check_related_text">'
				+'<div><span id="new_attachment_'+num+'_display_new_filename">'+file.name+'</span> <s id="new_attachment_'+num+'_display_original_filename"></s>'

				// Edit button
				+'<button type="button" role="button" class="btn btn-light btn-xs edit_attachment"';

			if ($('#'+$(this).data('attach-id')).data('new-attachment-modal-id')){
				html += ' data-modal-id="'+$('#'+$(this).data('attach-id')).data('new-attachment-modal-id')+'"';
			}else{
				html += ' data-edit-div="'+$('#'+$(this).data('attach-id')).data('new-attachment-edit-div')+'" data-back-div="'+$('#'+$(this).data('attach-id')).data('new-attachment-back-div')+'"';
			}

				html +=' data-original_filename="'+file.name+'" data-prefix="new_attachment_'+num+'_" style="margin: 0em 0em 0em 1em;">{{ trans('panichd::lang.btn-edit') }}</button>'
				+'<input type="hidden" id="new_attachment_'+num+'_new_filename" name="' + $(this).data('attachments_prefix') + 'attachment_new_filenames[]" value="'+file.name+'">'
				+'<input type="hidden" id="new_attachment_'+num+'_description" name="' + $(this).data('attachments_prefix') + 'attachment_descriptions[]" value="">'
				+'</div>'

				// Description
				+'<span id="new_attachment_'+num+'_display_description" class="text-muted" data-mimetype=""></span>'
				+'</div>'

				// Block button
				+'<div class="media-right media-middle">'
				+'<a href="#" class="check_button" data-delete_id="delete_new_attachment_check_'+num+'"><span class="media-object fa fa-times" aria-hidden="true"></span><span class="media-object fa fa-check" aria-hidden="true" style="display: none"></span></a>'
				+'<input type="checkbox" id="delete_new_attachment_check_'+num+'" name="' + $(this).data('attachments_prefix') + 'block_file_names[]" value="'+file.name+'" checked="checked" style="display: none" disabled="disabled"></div>'
				+'</div></div></div>'
				+'<div class="jquery_error_text"></div></div>';

			$('#'+$(this).data('attach-id')).append($(html));
		}
	});

	// Edit attachment button
	var jcrop_api;

	$(document).on('click', '.edit_attachment', function(e){
		var editdiv = '#' + ($(this).attr('data-modal-id') ? $(this).data('modal-id') : $(this).data('edit-div'));
		var prefix = $(this).data('prefix');

		$(editdiv).find('#attachment_form_original_filename').text($(this).data('original_filename'));
		$(editdiv).find('#attachment_form_new_filename').val($('#'+prefix+'new_filename').val());
		$(editdiv).find('#attachment_form_description').val($('#'+prefix+'description').val());

		// Image
		$(editdiv).find('#attachment_form_image_row .image_wrap').children().remove();

		if ($(this).attr('data-image-url')){
			// Calculate sizes for max width 560px and max height 400px
			var sizes = $(this).data('image-sizes').split('x');
			var width_o = sizes[0], width = sizes[0], height_o = sizes[1], height = sizes[1];

			if (width_o > 560){
				width = 560;
				height = height_o*(560/width_o);
			}
			if (height > 400){
				height = 400;
				width = width_o*(400/height_o);
			}

			// Show image and enable Jcrop
			$(editdiv).find('#attachment_form_image_row').show();
			$(editdiv).find('#attachment_form_image_row .image_wrap').append('<img src="'+$(this).data('image-url')+'" >');
			$(editdiv).find('#attachment_form_image_row .image_wrap img').addClass('pull-center');


			$(editdiv).find('#attachment_form_image_row .image_wrap img').Jcrop({
					trueSize: [width_o, height_o],
					maxSize: [560, 400],
					boxWidth: 560, boxHeight: 400
				}, function(){
						jcrop_api = this;
					});

			if ($('#'+prefix+'image_crop').val() != ""){
				var sdata = $('#'+prefix+'image_crop').val().split(',');

				jcrop_api.setSelect([
					sdata[0],sdata[1],sdata[2],sdata[3]
				]);
			}

			$(editdiv).find('img, .jcrop-holder').css({
				'width' : width+'px',
				'height' : height+'px'
			});

			// .jcrop-tracker at least in Chrome didn't change sizes by css() method
			$(editdiv).find('.jcrop-tracker').width(width+'px').height(height+'px');
		}else{
			$(editdiv).find('#attachment_form_image_row').hide();
		}


		$(editdiv).find('#attachment_form_prefix').val(prefix);

		if ($(this).attr('data-modal-id')){
			// Showing modal
			$('#'+$(this).data('modal-id')).modal('show');

			// Pass back-div
			$(editdiv).find('.attachment_form_submit').attr('data-back-div', $(this).closest('.attached_list').prop('id'));
		}else{
			// Switching visible form inside modal
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

		// Attachment edition form
		var fieldset = $(this).closest('fieldset');

		// Related id's in DOM for form submit
		var prefix = $(fieldset).find('#attachment_form_prefix').val();

		var original_filename = $(fieldset).find('#attachment_form_original_filename').text();
		var new_filename = $(fieldset).find('#attachment_form_new_filename').val();

		// Fields
		$('#'+prefix+'new_filename').val(new_filename);
		$('#'+prefix+'description').val($(fieldset).find('#attachment_form_description').val());

		// Image crop field
		if ($(fieldset).find('#attachment_form_image_row .jcrop-holder').children().first().width() > 0 && $(fieldset).find('#attachment_form_image_row .jcrop-holder').children().first().height()){

			var select = jcrop_api.tellSelect();

			$('#'+prefix+'image_crop').val(
				select.x
				+','+select.y
				+','+select.x2
				+','+select.y2
			);

			if ($('#'+prefix+'image_crop').closest('.media').find('.fa fa-scissors').length == 0){
				$('#'+prefix+'image_crop').closest('.media').find('.jquery_scissors_previous').after('<span class="fa fa-scissors pull-center" style="margin-left: 0.5em; color: orange;" aria-hidden="true" title="{{ trans('panichd::lang.crop-image') }}"></span>');
			}
		}else{
			$('#'+prefix+'image_crop').val('');
			$('#'+prefix+'image_crop').closest('.media').find('.fa fa-scissors').remove();
		}

		// Display values
		if (original_filename == new_filename){
			$('#'+prefix+'display_original_filename').text('');
		}else{
			$('#'+prefix+'display_original_filename').text(' - '+original_filename);
		}

		$('#'+$(this).attr('data-back-div')).find('#'+prefix+'display_new_filename').text(new_filename);
		
		if ($(fieldset).find('#attachment_form_description').val() != ""){
			$('#'+$(this).attr('data-back-div')).find('#'+prefix+'display_description').text($(fieldset).find('#attachment_form_description').val());
		}else{
			$('#'+$(this).attr('data-back-div')).find('#'+prefix+'display_description').text($('#'+prefix+'display_description').data('mimetype'));
		}

		if ($(fieldset).find('#hide_modal_id').length){
			$('#'+$(fieldset).find('#hide_modal_id').val()).modal('hide');
		}else{
			$('#'+$(this).attr('data-edit-div')).hide();
			$('#'+$(this).attr('data-back-div')).show();
		}

		e.preventDefault();
	});
});
</script>
@include('panichd::shared.grouped_check_list')
@append
