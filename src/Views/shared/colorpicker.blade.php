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

@section('footer')
	<script type="text/javascript" src="{{asset('vendor/panichd/js/bootstrap-colorpicker.min.js')}}"></script>
	<script type="text/javascript" src="{{asset('vendor/panichd/js/bootstrap-colorpicker-plus.js')}}"></script>
@append