<!-- Modal Dialog -->
<div class="modal fade" id="agentChange" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
		<div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-hidden="true">{{ trans('ticketit::lang.flash-x') }}</button>
		<h4 class="modal-title">Canviar agent assignat</h4>
		</div>
		<div class="modal-body">
		<p><b>Tiquet</b>: #<span id="agent_ticket_id_text"></span> <span id="ticket_subject"></span></p>

		{!! CollectiveForm::open([
			'route' => [$setting->grab('main_route').'-change.agent'],
			'method' => 'PATCH',
			'class' => 'form-horizontal'
			]) !!}
		{!! CollectiveForm::hidden('ticket_id', '',['id'=>'agent_ticket_id_field'] ) !!}
		<legend>De <span id="current_agent"></span> a</legend>
		@foreach ($a_cat_agents as $category)
			<div id="category_{{ $category->id}}_agents" class="categories_agent_change" style="display: none">
			<ul style="list-style-type: none">
			@foreach ($category->agents as $agent)
				<li><label><input type="radio" name="agent_id" value="{{ $agent->id }}"> {{ $agent->name }}</label></li>
			@endforeach
			</ul>
			</div>
		@endforeach
		 
		</div>
		<div class="modal-footer">
		<button type="submit" class="btn btn-danger">Canviar</button>
		</div>
		{!! CollectiveForm::close() !!}
    </div>
  </div>
</div>
