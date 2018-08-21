@extends('panichd::install.partials.html')

@section('current_status')
	<span class="fa fa-exclamation-triangle" style="font-size: 1.5em; padding: 0em 0.5em 0em 0em;"></span>{!! trans('panichd::install.not-yet-installed') !!}
@stop

@section('content')
	<h3 style="margin: 0.8em 1em 0.8em 0em;">{!! trans('panichd::install.welcome') !!}</h3>
	<p>{!! trans('panichd::install.setup-list') !!}</p>
	<ol>
	<li>{!! trans('panichd::install.setup-list-migrations', ['num' =>count($inactive_migrations)]) !!} <a href="#" class="slide_button" data-slide="migrations_list" data-on-text="{{ trans('panichd::install.setup-less-info') }}" data-off-text="{{ trans('panichd::install.setup-more-info') }}">{{ trans('panichd::install.setup-more-info') }}</a></li>
	<ul id="migrations_list" style="display: none; margin: 0em 0em 1em 0em;">
		@foreach($inactive_migrations as $mig)
			<li>{{ $mig }}</li>
		@endforeach
	</ul>
	@if ($previous_ticketit)
		<li>{{ trans('panichd::install.setup-list-ticketit-settings') }} <a href="#" class="slide_button" data-slide="updated_settings_list" data-on-text="{{ trans('panichd::install.setup-less-info') }}" data-off-text="{{ trans('panichd::install.setup-more-info') }}">{{ trans('panichd::install.setup-more-info') }}</a></li>
		<ul id="updated_settings_list" style="display: none; margin: 0em 0em 1em 0em;">
			<li>{{ trans('panichd::install.setup-list-ticketit-admin_route') }}</li>
			<li>{{ trans('panichd::install.setup-list-ticketit-template') }}</li>
			<li>{{ trans('panichd::install.setup-list-ticketit-routes') }}</li>
		</ul>
	@endif
	<li>{{ trans('panichd::install.setup-list-settings') }}</li>
	<li>{{ trans('panichd::install.setup-list-folders') }}</li>
	<li>{!! trans('panichd::install.setup-list-admin', ['name' => auth()->user()->name, 'email' => auth()->user()->email]) !!}</li>
	<li>{!! trans('panichd::install.setup-list-public-assets') !!}
	</ol>
	<form class="form-signin" action="{{url('/panichd/install') }}" method="post" style="margin-top: 2em;">
	{{ csrf_field() }}
	<h4>{{ trans('panichd::install.configurable-parameters') }}</h4>
	@if($quickstart)
		<label style="font-weight: normal;"><input type="checkbox" name="quickstart"> {!! trans('panichd::install.optional-quickstart-data') !!}</label>
	@else
		<p>{!! trans('panichd::install.existent-parameters') !!}</p>
	@endif
	<p><button id="install_now" class="btn btn-lg btn-primary" type="submit">
		{{ trans('panichd::install.install-now') }}
	</button></p>
	</form>
@stop