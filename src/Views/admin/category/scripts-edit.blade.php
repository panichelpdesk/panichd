<script type="text/javascript">
$(function(){

	$('#tag-edit-modal').on('show.bs.modal', function (e)
	{
		var button=$(e.relatedTarget);
		
		// Text to modal
		$(this).find('#jquery_popup_tag_title').text(button.data('tag_name'));
		$(this).find('#jquery_popup_tag_input').val(button.data('tag_name'));
		
		// Element identifier to modal
		elem_i=button.data('i');
		
		// Colors to modal
		var a_colors=$('#jquery_tag_color_'+elem_i).val().split("_");
		$('#tag-edit-modal #pick_bg .colorpicker-element').val(a_colors[0]);
		$('#tag-edit-modal #pick_text .colorpicker-element').val(a_colors[1]);
		$('#tag-edit-modal #jquery_popup_tag_input').css('background',a_colors[0]).css('color',a_colors[1]);
		
	});
	
	$('#jquery_popup_tag_submit').click(function(e)
	{
		// Text change
		var disable=true;
		var modaltext=$('#tag-edit-modal #jquery_popup_tag_input').val();
		if ($('#tag_text_'+elem_i).data('tag_name') != modaltext){
			disable=false;
			$('#jquery_tag_name_'+elem_i).val(modaltext);
			$('#tag_text_'+elem_i).find('.name').text(modaltext);
		} 	
		$('#jquery_tag_name_'+elem_i).prop('disabled', disable);
		
		// Color change
		var bg_color = $('#tag-edit-modal #pick_bg .colorpicker-element').val();
		var text_color = $('#tag-edit-modal #pick_text .colorpicker-element').val();
		$('#tag_text_'+elem_i)
			.css('background-color', bg_color)
			.css('color', text_color);
		
		if ($('#jquery_tag_color_'+elem_i).val()!=bg_color+"_"+text_color){
			$('#jquery_tag_color_'+elem_i).prop('disabled',false);
		}
		$('#jquery_tag_color_'+elem_i).val(bg_color+"_"+text_color);
		
		$('#tag-edit-modal').modal('hide');
	});
	
	// Tag POPUP color Picker
	var tagColorPicker = $('#tag-edit-modal .colorpickerplus-embed .colorpickerplus-container');
	tagColorPicker.colorpickerembed();
	tagColorPicker.on('changeColor', function(e, color){
	var paintTarget = $(e.target).parent().prop('id') == "pick_bg" ? 'background-color' : 'color';
	if(color==null)
	  $('#tag-edit-modal #jquery_popup_tag_input').css(paintTarget, '#fff');//tranparent
	else
	  $('#tag-edit-modal #jquery_popup_tag_input').css(paintTarget, color);
	});

});		
</script>