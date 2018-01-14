@extends('panichd::install.partials.html')

@section('content')
	<h3 style="margin: 0.8em 1em 0.8em 0em;">{!! $title !!}</h3>
	@if(isset($description))
		<p>{!! $description !!}</p>
	@endif
	{!! link_to_route(
		'dashboard',
		isset($button_text) ? $button_text : trans('panichd::install.continue-to-main-menu'), null,
		['class' => 'btn btn-lg btn-primary',
		'style' => 'margin: 2em 0em 0em 0em;'])
	!!}
@stop