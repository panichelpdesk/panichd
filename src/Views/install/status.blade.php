@extends('panichd::install.partials.html')

@section('content')
	<h3 style="margin: 0.8em 1em 0.8em 0em;">{!! $title !!}</h3>
	@if(isset($description))
		<p>{!! $description !!}</p>
	@endif
	@if(!isset($button) || (isset($button) && $button != 'hidden'))
		<a href="{{ url($setting->grab('admin_route_path').'/dashboard') }}" class="btn btn-lg btn-primary" style="margin: 2em 0em 0em 0em;">{{ trans('panichd::install.continue-to-main-menu') }}</a>
	@endif
@stop