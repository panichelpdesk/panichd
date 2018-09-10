@section('panichd_assets')
	<!-- COLOR PICKER -->
	<link rel="StyleSheet" href="{{asset('vendor/panichd/css/bootstrap-colorpicker.min.css')}}">
	<link rel="StyleSheet" href="{{asset('vendor/panichd/css/bootstrap-colorpicker-plus.css')}}">
	<style type="text/css">
	.color-fill-icon{
		display:inline-block;width:16px;height:16px;border:1px solid #000;background-color:#fff;margin: 2px;
	}
	.dropdown-color-fill-icon{
		position:relative;float:left;margin-left:0;margin-right: 0
	}
	</style>
	<!-- /COLOR PICKER -->
@append

@section('colorpicker_snippet')
	<button class="btn btn-light" id="colorpicker_snippet" type="button"><span class="color-fill-icon dropdown-color-fill-icon" style="background-color: #000000"></span>&nbsp;<b class="caret"></b></button>
	<input type="hidden" id="colorpicker_snippet_color" name="color" value="#000000">
@stop

@section('footer')
	<script type="text/javascript" src="{{asset('vendor/panichd/js/bootstrap-colorpicker.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('vendor/panichd/js/bootstrap-colorpicker-plus.js')}}"></script>
	@if(isset($include_colorpickerplus_script) && $include_colorpickerplus_script)
		<script type="text/javascript">
		// Category color picker
		var catColorPicker = $('#colorpicker_snippet');
		catColorPicker.colorpickerplus();
		catColorPicker.on('changeColor', function(e, color){
			if(color==null) {
				//when select transparent color
				$('.color-fill-icon', $(this)).addClass('colorpicker-color');
				$('#colorpicker_snippet_color').val('#000000');
			} else {
				$('.color-fill-icon', $(this)).removeClass('colorpicker-color');
				$('.color-fill-icon', $(this)).css('background-color', color);
				$('#colorpicker_snippet_color').val(color);
			}
		});

		@if (isset($input_color) && $input_color != "")
			$('#colorpicker_snippet .color-fill-icon').css('background-color', '{{ $input_color }}');
			$('#colorpicker_snippet_color').val('{{ $input_color }}');
		@endif
		</script>
	@endif
@append
