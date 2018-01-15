@extends($master)

@section('page')
    {{ trans('panichd::admin.index-title') }}
@stop

@include('panichd::shared.common')

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading"><h3>{!! trans('panichd::install.pending-settings') !!}</h3></div>
		<div class="panel-body">
		<p>{!! trans('panichd::install.pending-settings-description') !!}</p>
		</div>
	</div>
@stop