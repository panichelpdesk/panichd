<div class="panel panel-default">
    <div id="ticket-body" class="panel-body">        
		<div class="row" style="margin-bottom: 0.2em;">
			<div class="col-md-8">				
				<h2 style="margin: 0em 0em 0.5em 0em;">
				@if ($ticket->completed_at)
					<span class="text-success"><span class="glyphicon glyphicon-ok-circle" title="tiquet completat" style="cursor: help"></span> {{ $ticket->subject }}</span>
				@else
					<span class="text-warning"><span class="glyphicon glyphicon-file" title="tiquet obert" style="cursor: help"></span> {{ $ticket->subject }}</span>
				@endif
				</h2>
			</div>
			<div class="col-md-4 text-right">
				@if ($ticket->updated_at!=$ticket->created_at)
					<span class="tooltip-info" data-toggle="tooltip" data-placement="auto top" title="{{ trans('ticketit::lang.date-info-updated') }}" style="color: #aaa; cursor: help">
						<span class="glyphicon glyphicon-pencil"></span> {{ $ticket->updated_at->diffForHumans() }}
					</span>
				@endif
				<span class="tooltip-info" data-toggle="tooltip" data-placement="auto top" title="{{ trans('ticketit::lang.date-info-created') }}" style="color: #aaa; cursor: help">
					<span class="glyphicon glyphicon-certificate"></span> {{ $ticket->created_at->diffForHumans() }}
				</span>&nbsp;
								
				@if($u->isAdmin())
					@if($setting->grab('delete_modal_type') == 'builtin')
						{!! link_to_route(
										$setting->grab('main_route').'.destroy', trans('ticketit::lang.btn-delete'), $ticket->id,
										[
										'class' => 'btn btn-default deleteit',
										'form' => "delete-ticket-$ticket->id",
										"node" => $ticket->subject
										])
						!!}
					@elseif($setting->grab('delete_modal_type') == 'modal')
					{{-- // OR; Modal Window: 1/2 --}}
						{!! CollectiveForm::open(array(
								'route' => array($setting->grab('main_route').'.destroy', $ticket->id),
								'method' => 'delete',
								'style' => 'display:inline'
						   ))
						!!}
						<button type="button"
								class="btn btn-default"
								data-toggle="modal"
								data-target="#confirmDelete"
								data-title="{!! trans('ticketit::lang.show-ticket-modal-delete-title', ['id' => $ticket->id]) !!}"
								data-message="{!! trans('ticketit::lang.show-ticket-modal-delete-message', ['subject' => $ticket->subject]) !!}"
						 >
						  {{ trans('ticketit::lang.btn-delete') }}
						</button>
					@endif
						{!! CollectiveForm::close() !!}
					{{-- // END Modal Window: 1/2 --}}
				@endif
			</div>
		</div>

		<div class="row">
			<div class="col-lg-2 col-sm-3">				
				<p>
				<strong>{{ trans('ticketit::lang.ticket') }}</strong>{{ trans('ticketit::lang.colon') . trans('ticketit::lang.table-id') . $ticket->id }}
				@if ($u->currentLevel() > 1)
					@if ($ticket->user_id != $ticket->creator_id)
						<br /><strong>{{ trans('ticketit::lang.show-ticket-creator') }}</strong>{{ trans('ticketit::lang.colon') . $ticket->creator->name }}<br />
					@endif
					
					<br /><strong>{{ trans('ticketit::lang.owner') }}</strong>{{ trans('ticketit::lang.colon') . $ticket->user->name }}
					@if ($setting->grab('departments_feature'))
						@if ($ticket->department)
							<br /><strong>{{ trans('ticketit::lang.department') }}</strong>{{ trans('ticketit::lang.colon') . ucwords(mb_strtolower($ticket->department)) }}
						@endif
						@if ($ticket->sub1)
							<br /><strong>{{ trans('ticketit::lang.dept_sub1') }}</strong>{{ trans('ticketit::lang.colon') . ucwords(mb_strtolower($ticket->sub1)) }}
						@endif
					@endif
				@endif
				
				<br /><strong>{{ trans('ticketit::lang.status') }}</strong>{{ trans('ticketit::lang.colon') }}
					@if( $ticket->isComplete() && ! $setting->grab('default_close_status_id') )
						<span style="color: blue">Complete</span>
					@else
						<span style="color: {{ $ticket->status->color }}">{{ $ticket->status->name }}</span>
					@endif
					
				@if ($u->currentLevel() > 1)
					<br /><strong>{{ trans('ticketit::lang.priority') }}</strong>{{ trans('ticketit::lang.colon') }}
					<span style="color: {{ $ticket->priority->color }}">
						{{ $ticket->priority->name }}
					</span>
					@php
						\Carbon\Carbon::setLocale(config('app.locale'));
					@endphp
					<br /><strong>{{ trans('ticketit::lang.start-date') }}</strong>{{ trans('ticketit::lang.colon') .  $ticket->getDateForHumans($ticket->start_date) }}
					@if ($ticket->limit_date != "")
						<br /><strong>{{ trans('ticketit::lang.limit-date') }}</strong>{{ trans('ticketit::lang.colon') . $ticket->getDateForHumans($ticket->limit_date) }}
					@endif
					</p><p>					
				@endif
				
				<strong>{{ trans('ticketit::lang.category') }}</strong>{{ trans('ticketit::lang.colon') }}
				<span style="color: {{ $ticket->category->color }}">
					{{ $ticket->category->name }}
				</span>
				
				@if ($u->currentLevel() > 1)
					<br /><strong>{{ trans('ticketit::lang.responsible') }}</strong>{{ trans('ticketit::lang.colon') }}{{ $ticket->agent->name }}
				@endif
								
				@if ($u->currentLevel() > 1 and $ticket->has('tags'))
					<br /><strong>{{ trans('ticketit::lang.tags') }}</strong>{{ trans('ticketit::lang.colon') }}
					@foreach ($ticket->tags as $i=>$tag)
						<button class="btn btn-default btn-sm" style="pointer-events: none; color: {{$tag->text_color}}; background: {{$tag->bg_color}}">{{$tag->name}}</button>
					@endforeach					
				@endif
				</p>				
			</div>
			<div class="col-lg-10 col-sm-9">
				<div class="row row-eq-height">
					<div class="description-col {{ $ticket->intervention_html ? 'col-md-6' : 'col-md-12'}}">
						<div>
							<b>{{ trans('ticketit::lang.description') }}</b>
						</div>
						<p> {!! $ticket->html !!} </p>
					</div>
					@if ($ticket->intervention_html)
						<div class="intervention-col col-md-6">
							<div>
								<b>{{ trans('ticketit::lang.intervention') }}</b>
							</div>
							<p> {!! $ticket->intervention_html !!} </p>
						</div>
					@endif					
				</div>
				
				@if($setting->grab('ticket_attachments_feature') && $ticket->attachments->count() > 0)
					<div class="row row-ticket-attachments" style="">							
						<div class="col-xs-12"><b style="display: block; margin: 0em 0em 0.5em 0em;">{{ trans('ticketit::lang.attachments') }}</b></div>
						<div class="col-xs-12">
							<div id="attached" class="panel-group grouped_check_list deletion_list">
													
							@foreach($ticket->attachments as $attachment)							
								@include('ticketit::tickets.partials.attachment', ['template'=>'view'])
							@endforeach
							</div>
						</div>
					</div>					
				@endif
			</div>
		</div>
		
		@if(! $ticket->completed_at && $close_perm == 'yes')			
			<button type="submit" class="btn btn-default" data-toggle="modal" data-target="#ticket-complete-modal" data-status_id="{{ $setting->grab('default_close_status_id') }}">{{ trans('ticketit::lang.btn-mark-complete') }}</button>						
		@elseif($ticket->completed_at && $reopen_perm == 'yes')
			{!! link_to_route($setting->grab('main_route').'.reopen', trans('ticketit::lang.reopen-ticket'), $ticket->id,
									['class' => 'btn btn-default']) !!}
		@endif
		@if($u->currentLevel() > 1)
			{!! link_to_route($setting->grab('main_route').'.edit', trans('ticketit::lang.btn-edit'), $ticket->id,
									['class' => 'btn btn-default']) !!}
			<div class="visible-xs"><br /></div>
		@endif
        
        {!! CollectiveForm::open([
                        'method' => 'DELETE',
                        'route' => [
                                    $setting->grab('main_route').'.destroy',
                                    $ticket->id
                                    ],
                        'id' => "delete-ticket-$ticket->id"
                        ])
        !!}
        {!! CollectiveForm::close() !!}
    </div>
</div>

{{-- // OR; Modal Window: 2/2 --}}
    @if($u->isAdmin())
        @include('ticketit::tickets.partials.modal-delete-confirm')
    @endif
{{-- // END Modal Window: 2/2 --}}
