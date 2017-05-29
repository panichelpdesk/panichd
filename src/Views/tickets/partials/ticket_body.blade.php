<div class="panel panel-default">
    <div class="panel-body">        
		<div class="row" style="margin-bottom: 0.2em;">
			<div class="col-md-8">				
				<h2 style="margin: 0em 0em 0.5em 0em;">
				@if ($ticket->completed_at)
					<span class="glyphicon glyphicon-ok-circle text-success" title="tiquet completat" style="cursor: help"></span> <span class="text-success">{{ $ticket->subject }}</span>
				@else
					<span class="glyphicon glyphicon-file text-warning" title="tiquet obert" style="cursor: help"></span> <span class="text-warning">{{ $ticket->subject }}</span>
				@endif
				</h2>
			</div>
			<div class="col-md-4 text-right">
				@if(! $ticket->completed_at && $close_perm == 'yes')
							{!! link_to_route($setting->grab('main_route').'.complete', trans('ticketit::lang.btn-mark-complete'), $ticket->id,
												['class' => 'btn btn-success']) !!}
					@elseif($ticket->completed_at && $reopen_perm == 'yes')
							{!! link_to_route($setting->grab('main_route').'.reopen', trans('ticketit::lang.reopen-ticket'), $ticket->id,
												['class' => 'btn btn-success']) !!}
					@endif
					@if($u->isAgent() || $u->isAdmin())
						<button type="button" class="btn btn-info" data-toggle="modal" data-target="#ticket-edit-modal">
							{{ trans('ticketit::lang.btn-edit')  }}
						</button>
					@endif
					@if($u->isAdmin())
						@if($setting->grab('delete_modal_type') == 'builtin')
							{!! link_to_route(
											$setting->grab('main_route').'.destroy', trans('ticketit::lang.btn-delete'), $ticket->id,
											[
											'class' => 'btn btn-danger deleteit',
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
									class="btn btn-danger"
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
			<div class="col-lg-3 col-sm-4">
				<p><strong>{{ trans('ticketit::lang.owner') }}</strong>{{ trans('ticketit::lang.colon') }}{{ $ticket->user->name }}<br />
				<strong>{{ trans('ticketit::lang.status') }}</strong>{{ trans('ticketit::lang.colon') }}
					@if( $ticket->isComplete() && ! $setting->grab('default_close_status_id') )
						<span style="color: blue">Complete</span>
					@else
						<span style="color: {{ $ticket->status->color }}">{{ $ticket->status->name }}</span>
					@endif
				<br /><strong>{{ trans('ticketit::lang.priority') }}</strong>{{ trans('ticketit::lang.colon') }}
					<span style="color: {{ $ticket->priority->color }}">
						{{ $ticket->priority->name }}
					</span>
				</p>
				<p><strong>{{ trans('ticketit::lang.created') }}</strong>{{ trans('ticketit::lang.colon') }}{{ $ticket->created_at->diffForHumans() }}<br />
				<strong>{{ trans('ticketit::lang.last-update') }}</strong>{{ trans('ticketit::lang.colon') }}{{ $ticket->updated_at->diffForHumans() }}</p>
				
				<p><strong>{{ trans('ticketit::lang.responsible') }}</strong>{{ trans('ticketit::lang.colon') }}{{ $ticket->agent->name }}<br />
				<strong>{{ trans('ticketit::lang.category') }}</strong>{{ trans('ticketit::lang.colon') }}
					<span style="color: {{ $ticket->category->color }}">
						{{ $ticket->category->name }}
					</span>				
				@if ($ticket->has('tags'))
					<br /><strong>Etiquetes</strong>{{ trans('ticketit::lang.colon') }}
					@foreach ($ticket->tags as $i=>$tag)
						<button class="btn btn-default btn-tag btn-sm" style="pointer-events: none; color: {{$tag->text_color}}; background: {{$tag->bg_color}}">{{$tag->name}}</button>
					@endforeach
					
				@endif
				</p>
			</div>
			<div class="col-lg-9 col-sm-8">
				<div class="row">
					<div class="col-md-6">
						<div class="panel panel-default">
						<div class="panel-body">
						<div>
							<b>Descripció</b>
						</div>
						<p> {!! $ticket->html !!} </p>
						</div></div>
					</div>
					<div class="col-md-6">
						<div class="panel panel-default">
						<div class="panel-body">
						<div>
							<b>Actuació</b>
						</div>
						<p> {!! $ticket->intervention_html !!} </p>
						</div></div>
					</div>
				</div>
			</div>
		</div>
        
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

    @if($u->isAgent() || $u->isAdmin())
        @include('ticketit::tickets.edit')
    @endif

{{-- // OR; Modal Window: 2/2 --}}
    @if($u->isAdmin())
        @include('ticketit::tickets.partials.modal-delete-confirm')
    @endif
{{-- // END Modal Window: 2/2 --}}
