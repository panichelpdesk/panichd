<div class="panel panel-default">
    <div class="panel-body">        
		<div class="row" style="margin-bottom: 0.2em;">
			<div class="col-md-8">				
				<h2 style="margin: 0em 0em 0.5em 0em;">
				@if ($ticket->completed_at)
					<span class="text-success"><span class="glyphicon glyphicon-ok-circle" title="tiquet completat" style="cursor: help"></span><span style="margin: 0em 1em 0em 0.2em">{{ "#".$ticket->id }}</span><span>{{ $ticket->subject }}</span></span>
				@else
					<span class="text-warning"><span class="glyphicon glyphicon-file" title="tiquet obert" style="cursor: help"></span><span style="margin: 0em 1em 0em 0.2em">{{ "#".$ticket->id }}</span><span>{{ $ticket->subject }}</span></span>
				@endif
				</h2>
			</div>
			<div class="col-md-4 text-right">
				@if ($ticket->updated_at!=$ticket->created_at)
				<span class="glyphicon glyphicon-pencil" title="darrer canvi" style="color: #aaa; cursor: help"></span> {{ $ticket->updated_at->diffForHumans() }} 
				@endif
				<span class="glyphicon glyphicon-certificate" title="data de creació" style="color: #aaa; cursor: help"></span> {{ $ticket->created_at->diffForHumans() }}&nbsp;
								
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
			<div class="col-lg-3 col-sm-4">				
				<p>
				@if ($u->maxLevel > 1)
					<strong>{{ trans('ticketit::lang.owner') }}</strong>{{ trans('ticketit::lang.colon') }}{{ $ticket->user->name }}<br />
				@endif
				<strong>{{ trans('ticketit::lang.status') }}</strong>{{ trans('ticketit::lang.colon') }}
					@if( $ticket->isComplete() && ! $setting->grab('default_close_status_id') )
						<span style="color: blue">Complete</span>
					@else
						<span style="color: {{ $ticket->status->color }}">{{ $ticket->status->name }}</span>
					@endif
				@if ($u->maxLevel > 1)
					<br /><strong>{{ trans('ticketit::lang.priority') }}</strong>{{ trans('ticketit::lang.colon') }}
					<span style="color: {{ $ticket->priority->color }}">
						{{ $ticket->priority->name }}
					</span>
					</p><p>
					<strong>{{ trans('ticketit::lang.responsible') }}</strong>{{ trans('ticketit::lang.colon') }}{{ $ticket->agent->name }}
				@endif
				<br /><strong>{{ trans('ticketit::lang.category') }}</strong>{{ trans('ticketit::lang.colon') }}
				<span style="color: {{ $ticket->category->color }}">
					{{ $ticket->category->name }}
				</span>
				
				@if ($u->maxLevel > 1 and $ticket->has('tags'))
					<br /><strong>Etiquetes</strong>{{ trans('ticketit::lang.colon') }}
					@foreach ($ticket->tags as $i=>$tag)
						<button class="btn btn-default btn-tag btn-sm" style="pointer-events: none; color: {{$tag->text_color}}; background: {{$tag->bg_color}}">{{$tag->name}}</button>
					@endforeach
					
				@endif
				</p>				
			</div>
			<div class="col-lg-9 col-sm-8">
				<div class="row">
					<div class="{{ $ticket->intervention_html ? 'col-md-6' : 'col-md-12'}}">
						<div class="panel panel-default">
						<div class="panel-body">
						<div>
							<b>Descripció</b>
						</div>
						<p> {!! $ticket->html !!} </p>
						</div></div>
					</div>
					@if ($ticket->intervention_html)
						<div class="col-md-6">
							<div class="panel panel-default">
							<div class="panel-body">
							<div>
								<b>Actuació</b>
							</div>
							<p> {!! $ticket->intervention_html !!} </p>
							</div></div>
						</div>
					@endif
				</div>
			</div>
		</div>
		@if(! $ticket->completed_at && $close_perm == 'yes')
			{!! link_to_route($setting->grab('main_route').'.complete', trans('ticketit::lang.btn-mark-complete'), $ticket->id,
									['class' => 'btn btn-default']) !!}
		@elseif($ticket->completed_at && $reopen_perm == 'yes')
			{!! link_to_route($setting->grab('main_route').'.reopen', trans('ticketit::lang.reopen-ticket'), $ticket->id,
									['class' => 'btn btn-default']) !!}
		@endif
		@if($u->isAgent() || $u->isAdmin())
			<button type="button" class="btn btn-default" data-toggle="modal" data-target="#ticket-edit-modal">
				{{ trans('ticketit::lang.btn-edit')  }}
			</button>
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

    @if($u->isAgent() || $u->isAdmin())
        @include('ticketit::tickets.edit')
    @endif

{{-- // OR; Modal Window: 2/2 --}}
    @if($u->isAdmin())
        @include('ticketit::tickets.partials.modal-delete-confirm')
    @endif
{{-- // END Modal Window: 2/2 --}}
