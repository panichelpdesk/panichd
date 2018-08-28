@extends($master)

@section('page')
    {{ trans('panichd::admin.index-title') }}
@stop

@include('panichd::shared.common')

@section('content')
	<div class="card bg-light">
		<div class="card-header"><h3>{!! trans('panichd::install.pending-settings') !!}</h3></div>
		<div class="card-body">
		<p>{!! trans('panichd::install.pending-settings-description') !!}</p>
		</div>
	</div>
@stop