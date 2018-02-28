<link rel="StyleSheet" href="{{asset('vendor/panichd/css/bootstrap/bootstrap-3.3.7.min.css')}}">
<link rel="StyleSheet" href="{{asset('vendor/panichd/css/dataTables.bootstrap.css')}}">
<link rel="StyleSheet" href="{{asset('vendor/panichd/css/responsive.bootstrap.min.css')}}">
@if($editor_enabled)
	<link rel="StyleSheet" href="{{asset('vendor/panichd/css/summernote/summernote.css')}}">
	@if($include_font_awesome)
		<link rel="StyleSheet" href="{{asset('vendor/panichd/css/font-awesome.min.css')}}">
	@endif
	@if($codemirror_enabled)
		<link rel="StyleSheet" href="{{asset('vendor/panichd/css/codemirror/codemirror.min.css')}}">	
		<link rel="StyleSheet" href="{{asset('vendor/panichd/css/codemirror/'.$codemirror_theme.'.min.css')}}">
	@endif
@endif

<link rel="StyleSheet" href="{{asset('vendor/panichd/css/select2.min.css')}}">
<style type="text/css">
.select2-selection__choice {
	background-color: #cfe2f3 !important;
	border-color: #6fa8dc !important;
}

.select2-selection__choice, .select2-selection__choice__remove {
	color: #0b5394 !important;
}

</style>

<link href="{{ asset('/vendor/panichd/css/panichd.css') }}?v=20180228" rel="stylesheet">

<script type="text/javascript" src="{{asset('vendor/panichd/js/jQuery/jquery-3.2.1.min.js')}}"></script>
<script type="text/javascript" src="{{asset('vendor/panichd/js/jQuery/jquery-ui-1.12.1.min.js')}}"></script>
<script type="text/javascript" src="{{asset('vendor/panichd/js/bootstrap/bootstrap-3.3.7.min.js')}}"></script>

<script type="text/javascript" src="{{asset('vendor/panichd/js/select2/select2.min.js')}}"></script>
<script type="text/javascript" src="{{asset('vendor/panichd/js/select2/i18n/'.App::getLocale().'.js')}}"></script>