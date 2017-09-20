<link rel="StyleSheet" href="{{asset('vendor/ticketit/css/dataTables.bootstrap.css')}}">
<link rel="StyleSheet" href="{{asset('vendor/ticketit/css/responsive.bootstrap.min.css')}}">
@if($editor_enabled)
	<link rel="StyleSheet" href="{{asset('vendor/ticketit/css/summernote/summernote.css')}}">
	@if($include_font_awesome)
		<link rel="StyleSheet" href="{{asset('vendor/ticketit/css/font-awesome.min.css')}}">
	@endif
	@if($codemirror_enabled)
		<link rel="StyleSheet" href="{{asset('vendor/ticketit/css/codemirror/codemirror.min.css')}}">	
		<link rel="StyleSheet" href="{{asset('vendor/ticketit/css/codemirror/'.$codemirror_theme.'.min.css')}}">
	@endif
@endif