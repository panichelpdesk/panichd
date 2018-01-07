@extends('panichd::install.partials.html')

@section('content')
	<!--<h3>{{ trans('panichd::install.initial-setup') }}</h3>-->
	<h3 style="margin: 0.8em 1em 0.8em 0em;">{!! $title !!}</h3>
	<p>{{ $description }}</p>
	@if (isset($options) && is_array($options))
		<ul>
		@foreach($options as $opt)
			<li>{!! $opt !!}</li>
		@endforeach
		</ul>
	@endif
@stop