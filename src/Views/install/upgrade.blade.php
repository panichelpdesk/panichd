@extends('panichd::install.partials.html')

@section('current_status')
	@if ($isUpdated)
		<span class="text-success"><span class="fa fa-check" style="font-size: 1.5em; padding: 0em 0.5em 0em 0em;"></span>{!! trans('panichd::install.status-updated') !!}</span>
	@else
		<span class="text-warning"><span class="fa fa-exclamation-triangle" style="font-size: 1.5em; padding: 0em 0.5em 0em 0em;"></span>{!! trans('panichd::install.status-out-of-date') !!}</span>
	@endif
@stop

@section('content')
    <h3 style="margin: 0.8em 1em 0.8em 0em;">{!! trans('panichd::install.about-to-update') !!}</h3>
	<p>{!! trans('panichd::install.about-to-update-description') !!}</p>
    
	<ol>
	@if (count($inactive_migrations) == 0)
		<li>{{ trans('panichd::install.all-tables-migrated') }}</li>
	@else
		<li>{!! trans('panichd::install.setup-list-migrations', ['num' =>count($inactive_migrations)]) !!} <a href="#" class="slide_button" data-slide="migrations_list" data-on-text="{{ trans('panichd::install.setup-less-info') }}" data-off-text="{{ trans('panichd::install.setup-more-info') }}">{{ trans('panichd::install.setup-more-info') }}</a></li>
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
		<li>{!! trans('panichd::install.public-folder-will-be-replaced') !!}</li>
	</ol>
    
	<form action="{{url('/panichd/upgrade') }}" method="post">
	{{ csrf_field() }}
	<h4>{{ trans('panichd::install.optional-config') }}</h4>
	<ul>
	<li>{{ trans('panichd::install.choose-public-folder-action') }}</li>
	<div style="margin: 0em 0em 0em 2em;">
	<label style="display: block; font-weight: normal"><input type="radio" name="folder-action" value="destroy" checked> {{ trans('panichd::install.public-folder-destroy') }}</label>
	<label style="display: block; font-weight: normal"><input type="radio" name="folder-action" value="backup"> {{ trans('panichd::install.public-folder-backup') }}</label>
	</div>
	</ul>
	
	
    <p><button class="btn btn-lg btn-primary" type="submit">
        {{ trans('panichd::install.upgrade-now') }}
    </button></p>
	</form>
@stop