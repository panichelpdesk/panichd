<div class="modal fade" id="CreateAgentModal" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title" id="myModalLabel">{{ trans('ticketit::admin.agent-create-title') }}</h4>
	  </div>
	  <div class="modal-body">								  
		{!! CollectiveForm::open([
				'method' => 'POST',
				'route' => [
						$setting->grab('admin_route').'.agent.store'
						],
				]) !!}
		<div class="form-group">
		<label for="usuari" class="control-label col-sm-2">{{ trans('ticketit::admin.agent-create-form-agent') }}</label>
		<div class="col-sm-10">
			<select class="generate_default_select2" style="width: 100%" name="agent_id">
				@foreach ($not_agents as $newagent)
					<option value="{{ $newagent->id }}">{!! $newagent->name !!}</option>
				@endforeach
			</select>
		</div>
		</div>
		<br /><br /><br />
		<table class="table table-hover table-striped">
			<thead><th>{{ trans('ticketit::admin.agent-edit-table-category') }}</th>
			<th>{{ trans('ticketit::admin.agent-edit-table-active') }}</th>
			<th>{{ trans('ticketit::admin.agent-edit-table-autoassign') }}</th></thead>
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
		<button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
		{!! CollectiveForm::submit('Update', ['class' => 'btn btn-info']) !!}
	  </div>
	  
		{!! CollectiveForm::close() !!}
	</div>
  </div>
</div>