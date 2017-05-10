<script type="text/javascript">
$(function(){
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
});
</script>