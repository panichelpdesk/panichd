@extends($master)

@section('page')
    {{ trans('panichd::lang.index-title') }}
@stop

@include('panichd::shared.common')

@section('content')
@if($n_notices == 0)
	<div class="panel panel-default">
		<div class="panel-body" style="text-align: center">{{ trans('panichd::lang.ticket-notices-empty') }}</div>
	</div>
@else
	<div class="panel panel-default">
		<div class="panel-heading">{{ trans('panichd::lang.ticket-notices-title') . ' (' . $a_notices->count() . ')' }}</div>
		<div class="panel-body">
			<table class="table table-hover table-striped">
				<thead>
					<tr>                        
						<td>{{ trans('panichd::lang.table-id') }}</td>
						<td>{{ trans('panichd::lang.table-status') }}</td>
						<td>{{ trans('panichd::lang.table-calendar') }}</td>
						<td>{{ trans('panichd::lang.table-subject') }}</td>
						<td>{{ trans('panichd::lang.table-description') }}</td>
						<td>{{ trans('panichd::lang.table-intervention') }}</td>
						<td>{{ trans('panichd::lang.table-tags') }}</td>
					</tr>
				</thead>
				<tbody>
				@php
					\Carbon\Carbon::setLocale(config('app.locale'));
				@endphp
				@foreach ($a_notices as $notice)
					<tr>
					<td>{{ $notice->id }}</td>
					<td style="color: {{ $notice->status->color }}">{{ $notice->status->name }}</td>
					<td style="width: 14em;">{!! $notice->getCalendarInfo(false, 'description') !!}</td>
					<td>{{ link_to_route($setting->grab('main_route').'.show', $notice->subject, $notice->id) }}</td>
					<td>{{ $notice->content }}</td>
					<td>{{ $notice->intervention }}</td>

					<td>
					@foreach ($notice->tags as $tag)
						<button class="btn btn-default btn-tag btn-xs" style="pointer-events: none; background-color: {{ $tag->bg_color }}; color: {{ $tag->text_color }}">{{ $tag->name }}</button>
					@endforeach
					</td>
					</tr>				
				@endforeach
				</tbody>
			</table>
		</div>
	</div>
@endif
@append