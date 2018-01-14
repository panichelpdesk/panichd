@extends($master)

@section('page')
    {{ trans('panichd::admin.index-title') }}
@stop

@include('panichd::shared.common')

@section('content')
	<div class="panel panel-default">
		<div class="panel-heading"><h3>{!! $title !!}</h3></div>
		<div class="panel-body">
		@if(isset($description))
			<p>{!! $description !!}</p>
		@endif
		</div>
	</div>
@stop