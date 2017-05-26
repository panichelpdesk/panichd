@if(!$comments->isEmpty())
    @foreach($comments as $comment)
        <div class="panel {!! $comment->user->tickets_role ? "panel-info" : "panel-default" !!}">
            <div class="panel-heading">
                <h3 class="panel-title">
                    <span class="glyphicons {{ $comment->type=='note' ? 'glyphicon glyphicon-pencil text-info' : 'glyphicon glyphicon-envelope text-warning'}}" aria-hidden="true"></span> {!! $comment->user->name !!}
                    <span class="pull-right">
					@if ($comment->created_at!=$comment->updated_at)
						<span class="glyphicon glyphicon-pencil" aria-hidden="true" style="color: gray"></span>
					@endif
					{!! $comment->updated_at->diffForHumans() !!} 
					</span>
                </h3>
            </div>
            <div class="panel-body">
                <div class="content">
                    <p id="jquery_comment_edit_{{$comment->id}}"> {!! $comment->html !!} </p>
					@if ($u->canManageTicket($ticket->id))
						@include('ticketit::tickets.partials.note_edit')
					@endif
                </div>
				@if ($u->canManageTicket($ticket->id) and $comment->type=='note')
					<button type="button" class="btn btn-default btn-default btn-sm" data-toggle="modal" data-target="#comment-modal-edit-{{$comment->id}}">Editar</button>
				@endif
            </div>
        </div>
    @endforeach
@endif