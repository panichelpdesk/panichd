<script type="text/javascript">
var elem_i="";
$(function(){	
	$('.tooltip-info').tooltip();
	
	$('.grouped_check_list').sortable();
	
	// Category color picker
	var catColorPicker = $('#category_color_picker');
	catColorPicker.colorpickerplus();
	catColorPicker.on('changeColor', function(e, color){
		if(color==null) {
			//when select transparent color
			$('.color-fill-icon', $(this)).addClass('colorpicker-color');
			$('#category_color').val('#000000');
		} else {
			$('.color-fill-icon', $(this)).removeClass('colorpicker-color');
			$('.color-fill-icon', $(this)).css('background-color', color);
			$('#category_color').val(color);
		}
	});
	
	// Click on existing reason text
	$('#reason-edit-modal').on('show.bs.modal', function (e)
		{
			var button=$(e.relatedTarget);
			
			// Element identifier to modal
			elem_i=button.data('i');
			
			// Text to modal
			$(this).find('#jquery_popup_reason_text').val($('#jquery_reason_text_'+elem_i).val());
			$(this).find('#jquery_popup_select_status').val($('#jquery_reason_status_id_'+elem_i).val());						
		});
	
	// Click inside reason modal on submit button
	$('#jquery_popup_reason_submit').click(function(e)
	{
		var modal_text=$('#reason-edit-modal #jquery_popup_reason_text').val();
		var modal_status_id = $('#reason-edit-modal #jquery_popup_select_status').val();
		var modal_status_text = $('#reason-edit-modal #jquery_popup_select_status option[value=\''+modal_status_id+'\']').text();
		
		if (modal_text != ""){
			if (elem_i == 'new'){
				
				new_i = $('#reasons_count').val();
				$('#reasons_count').val(parseInt(new_i)+1);
				
				
				newreason = $('#reason_template').clone()
					.attr('id','reason_wrap_'+new_i).css('display','block');
				
				// Button element
				newreason.find('#reason_tempnum')
					.attr('id','reason_'+new_i)						
					.attr('data-i', new_i)
					.attr('data-text', modal_text);
				
				// Text element
				newreason.find('.reason_text').text(modal_text);
				newreason.find('.reason_status').text(modal_status_text);
				
				// Hidden inputs
				newreason.find('input:hidden').each(function(elem){
					$(this).attr('id',$(this).attr('id').replace('tempnum',new_i));
					$(this).attr('name',$(this).attr('name').replace('tempnum',new_i));
					$(this).prop('disabled', false);
				});
				newreason.find('#jquery_delete_reason_'+new_i).val(new_i).prop('disabled', true);
				newreason.find('#jquery_reason_ordering_'+new_i).val(new_i);
				newreason.find('#jquery_reason_text_'+new_i).val(modal_text);
				newreason.find('#jquery_reason_status_id_'+new_i).val(modal_status_id);
				
				// Append to DOM
				$('#reason_list').append(newreason);

			}else{
				// Text change
				var disable=true;				
				
				if ($('#reason_'+elem_i).data('reason_text') != modal_text){
					disable=false;
					$('#jquery_reason_text_'+elem_i).val(modal_text);
					$('#reason_'+elem_i).find('.reason_text').text(modal_text);
				} 	
				$('#jquery_reason_text_'+elem_i).prop('disabled', disable);
				
				// Status change		
				if ($('#jquery_reason_status_id_'+elem_i).val()!=modal_status_id){
					$('#jquery_reason_status_id_'+elem_i).val(modal_status_id);
					$('#reason_'+elem_i).find('.reason_status').text(modal_status_text);
				
					$('#jquery_reason_status_id_'+elem_i).prop('disabled',false);
				}
				
			}
		}		
		
		$('#reason-edit-modal').modal('hide');
	});
	
	// General Grouped Check List check / uncheck
	$('.grouped_check_list').on('click', '.check_button', function(e)
	{
		var i = $(this).parent('.btn-group').find('.text_button').data('i');
		var delete_id = $(this).parent('.btn-group').find('.text_button').data('delete_id');
				
		if ($(this).parent('.btn-group').hasClass('unchecked')){
			// Check tag to delete it
			$(this).parent('.btn-group').removeClass('unchecked').addClass('checked');
			
			$('#'+delete_id+i).prop('disabled',false);
		}else{
			// Uncheck tag to keep it
			$(this).parent('.btn-group').removeClass('checked').addClass('unchecked');
			
			$('#'+delete_id+i).prop('disabled',true);
		}
		
		e.preventDefault();			
	});
	
	
	
	// NEW Tags select2
	$('#admin-select2-tags').select2({
		tags: true,
		tokenSeparators: [',']
	});
	
	// Existing Tags check / uncheck
	$('.jquery_tag_check').click(function(e)
	{
		var i=$(this).prop('id').replace('jquery_tag_check_','');
		
		if ($(this).parent('.btn-group').hasClass('jquery_tag_group_unchecked')){
			// Check tag to delete it
			$(this).parent('.btn-group').removeClass('jquery_tag_group_unchecked').addClass('jquery_tag_group_checked');
			
			$('#jquery_delete_tag_'+i).prop('disabled',false);
		}else{
			// Uncheck tag to keep it
			$(this).parent('.btn-group').removeClass('jquery_tag_group_checked').addClass('jquery_tag_group_unchecked');
			
			$('#jquery_delete_tag_'+i).prop('disabled',true);
		}
		
		e.preventDefault();			
	});
	
	
});
</script>