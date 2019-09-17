<!-- Modal Dialog -->
<div class="modal fade" id="modalAgentChange" role="dialog" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
		<div class="modal-header">
			<h4 class="modal-title">{{ trans('panichd::lang.table-change-agent') }}</h4>
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">{{ trans('panichd::lang.flash-x') }}</button>
		</div>
		<div class="modal-body">
		{!! CollectiveForm::open([
			'route' => [$setting->grab('main_route').'.ajax.agent'],
			'method' => 'POST'
		]) !!}
		{!! CollectiveForm::hidden('ticket_id', '',['id'=>'agent_ticket_id_field'] ) !!}
		@foreach ($a_cat_agents as $category)
			<div id="category_{{ $category->id}}_agents" class="categories_agent_change" style="display: none">
			<ul style="list-style-type: none">
			@foreach ($category->agents as $agent)
				<li><label><input type="radio" name="agent_id" value="{{ $agent->id }}"> {{ $agent->name }}</label></li>
			@endforeach
			</ul>
			</div>
		@endforeach

    @if ($setting->grab('use_default_status_id'))
     <label><input type="checkbox" name="status_checkbox" value="yes">{{ trans('panichd::lang.table-agent-status-check') }}</label>
     @endif
    </div>
		<div class="modal-footer">
		<button type="submit" class="btn btn-danger">{{ trans('panichd::lang.btn-change') }}</button>
		</div>
		{!! CollectiveForm::close() !!}
    </div>
  </div>
</div>
