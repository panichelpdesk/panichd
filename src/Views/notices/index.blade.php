@extends($master)

@section('page')
    {{ trans('panichd::lang.ticket-notices-title') }}
@stop

@include('panichd::shared.common')

@section('content')
@if($n_notices == 0)
	<div class="card bg-light">
		<div class="card-body" style="text-align: center">{{ trans('panichd::lang.ticket-notices-empty') }}</div>
	</div>
@else
	<div class="card bg-light">
		<div class="card-header">{{ trans('panichd::lang.ticket-notices-title') . ' (' . $a_notices->count() . ')' }}</div>
		<div class="card-body">
			<table class="table table-hover table-striped">
				<thead>
					<tr>                        
						<td>{{ trans('panichd::lang.table-id') }}</td>
						<td>{{ trans('panichd::lang.table-status') }}</td>
						<td>{{ trans('panichd::lang.table-calendar') }}</td>
						<td>{{ trans('panichd::lang.table-subject') }}</td>
						<td>{{ trans('panichd::lang.table-description') }}</td>
						<td>{{ trans('panichd::lang.table-intervention') }}</td>
						@if($u->currentLevel() > 1)
							<td>{{ trans('panichd::lang.table-department') }}</td>
						@endif
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
					<td style="width: 14em;">
					<span class="tooltip-info" title="{{ trans('panichd::lang.datetime-text', [
						'date' => date(trans('panichd::lang.date-format'), strtotime(is_null($notice->limit_date) ? $notice->start_date : $notice->limit_date)),
						'time' => $notice->getTime('limit_date')
					]) }}" data-toggle="tooltip" data-placement="bottom"><span class="fa fa-calendar"></span> {!! $notice->getDateForHumans('limit_date', true) !!}</span>
					</td>
					<td>{{ link_to_route($setting->grab('main_route').'.show', $notice->subject, $notice->id) }}</td>
					<td>{{ $notice->content }}
					@if ($notice->all_attachments_count>0)
						<br />{{ $notice->all_attachments_count }} <span class="fa fa-paperclip tooltip-info attachment" title="{{ trans('panichd::lang.table-info-attachments-total', ['num' => $notice->all_attachments_count]) }}"></span>
					@endif
					</td>
					<td>{{ $notice->intervention }}</td>
					@if($u->currentLevel() > 1)
						<td><span title="{{ $u->currentLevel() > 2 ? trans('panichd::lang.show-ticket-creator') . trans('panichd::lang.colon') . $notice->owner->name : '' }}">{{ $notice->owner->ticketit_department == 0 ? trans('panichd::lang.all-depts') : $notice->owner->userDepartment->resume(true) }}</span></td>
					@endif
					<td>
					@foreach ($notice->tags as $tag)
						<button class="btn btn-light btn-tag btn-xs" style="pointer-events: none; background-color: {{ $tag->bg_color }}; color: {{ $tag->text_color }}">{{ $tag->name }}</button>
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