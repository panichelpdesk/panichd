<div class="modal fade" id="CreateAgentModal" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h4 class="modal-title" id="myModalLabel">{{ trans('panichd::admin.agent-index-create-new') }}</h4>
	  	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  </div>
	  <div class="modal-body">								  
		{!! CollectiveForm::open([
				'method' => 'POST',
				'route' => [
						$setting->grab('admin_route').'.agent.store'
						],
				]) !!}
		<div class="form-group row">
		<label for="usuari" class="col-form-label col-sm-2">{{ trans('panichd::admin.agent-create-form-agent') }}</label>
		<div class="col-sm-10">
			<select class="generate_default_select2" style="width: 100%" name="agent_id">
				@foreach ($not_agents as $newagent)
					<option value="{{ $newagent->id }}">{!! $newagent->name . ($newagent->email == "" ? ' ' . trans('panichd::lang.ticket-owner-no-email') : ' - ' . $newagent->email) !!}</option>
				@endforeach
			</select>
		</div>
		</div>
		<br /><br /><br />
		<table class="table table-hover table-striped">
			<thead><th>{{ trans('panichd::admin.agent-edit-table-category') }}</th>
			<th>{{ trans('panichd::admin.agent-edit-table-agent') }}</th>
			<th>{{ trans('panichd::admin.agent-edit-table-autoassign') }}</th></thead>
			<tbody>
			@foreach($categories as $category)
				<tr>
				<td>{{ $category->name }}</td>
				<td><input type="checkbox" id="checkbox_agent_new_cat_{!! $category->id !!}" class="jquery_agent_cat" name="agent_cats[]" value="{{ $category->id }}"></td>
				<td><input type="checkbox" id="checkbox_agent_new_cat_{!! $category->id !!}_auto" name="agent_cats_autoassign[]" value="{{ $category->id }}" disabled="disabled"></td>			   
				</tr>
			@endforeach
			</tbody>
		</table>
	  </div>
	  <div class="modal-footer">
		{!! CollectiveForm::submit( trans('panichd::lang.btn-add') , ['class' => 'btn btn-info']) !!}
	  </div>
	  
		{!! CollectiveForm::close() !!}
	</div>
  </div>
</div>