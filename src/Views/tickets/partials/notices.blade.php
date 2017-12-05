<div class="panel panel-default">
	<div class="panel-heading">{{ trans('ticketit::lang.ticket-notices-title') . ' (' . $a_notices->count() . ')' }}</div>
	<div class="panel-body">
		<table class="table table-hover table-striped">
			<thead>
				<tr>                        
					<td>{{ trans('ticketit::lang.table-id') }}</td>
					<td>{{ trans('ticketit::lang.table-subject') }}</td>
					<td>{{ trans('ticketit::lang.table-description') }}</td>
					<td>{{ trans('ticketit::lang.table-intervention') }}</td>
					<td>{{ trans('ticketit::lang.table-status') }}</td>
					<td>{{ trans('ticketit::lang.table-department') }}</td>
					<td>{{ trans('ticketit::lang.table-category') }}</td>
					<td>{{ trans('ticketit::lang.table-tags') }}</td>
				</tr>
			</thead>
			<tbody>
			@foreach ($a_notices as $notice)
				<tr>
				<td>{{ $notice->id }}</td>
				<td>{{ link_to_route($setting->grab('main_route').'.show', $notice->subject, $notice->id) }}</td>
				<td>{{ $notice->content }}</td>
				<td>{{ $notice->intervention }}</td>
				<td style="color: {{ $notice->status->color }}">{{ $notice->status->name }}</td>
				<td><span title="{{ $u->currentLevel() > 2 ? trans('ticketit::lang.show-ticket-creator') . trans('ticketit::lang.colon') . $notice->owner->name : '' }}">{{ $notice->owner->ticketit_department == 0 ? trans('ticketit::lang.all-depts') : $notice->owner->userDepartment->resume(true) }}</span></td>
				<td><span style="color: {{ $notice->category->color }}">{{ $notice->category->name }}</span></td>
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