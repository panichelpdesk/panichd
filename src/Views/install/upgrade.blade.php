@extends('panichd::install.partials.html')

@section('current_status')
	<span class="glyphicon glyphicon-alert" style="font-size: 1.5em; padding: 0em 0.5em 0em 0em;"></span>{!! trans('panichd::install.status-out-of-date') !!}
@stop

@section('content')
    <h3 style="margin: 0.8em 1em 0.8em 0em;">{!! trans('panichd::install.about-to-update') !!}</h3>
	<p>{!! trans('panichd::install.about-to-update-description') !!}</p>
    
	<ol>
	@if (count($inactive_migrations) == 0)
		<li>{{ trans('panichd::install.all-tables-migrated') }}</li>
	@else
		<li>{!! trans('panichd::install.setup-list-migrations', ['num' =>count($inactive_migrations)]) !!} <a href="#" id="show_migrations">{{ trans('panichd::install.setup-migrations-more-info') }}</a><a href="#" id="hide_migrations" style="display: none">{{ trans('panichd::install.setup-migrations-less-info') }}</a></li>
		<ul id="migrations_list" style="display: none; margin: 0em 0em 1em 0em;">
			@foreach($inactive_migrations as $mig)
				<li>{{ $mig }}</li>
			@endforeach
		</ul>
	@endif
    
	@if(empty($inactive_settings))
		<li>{{ trans('panichd::install.all-settings-installed') }}</li>
	@else
		<li>{{ trans('panichd::install.settings-to-be-installed') }}</li>
		<ul>
			@foreach($inactive_settings as $slug => $value)
				<li>{{ $slug }} => {!! is_array($value) ? print_r($value) : $value !!}</li>
			@endforeach
		</ul>
	@endif
	</ol>
    
	<form class="form-horizontal" action="{{url('/panichd/upgrade') }}" method="post">
	{{ csrf_field() }}
    <p><button class="btn btn-lg btn-primary" type="submit">
        {{ trans('panichd::install.upgrade-now') }}
    </button></p>
	</form>
@stop