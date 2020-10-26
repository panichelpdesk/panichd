<div class="modal fade" id="CategoriesPopupAgent{{ $agent->id }}" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
  <div class="modal-dialog" role="document">
	<div class="modal-content">
	  <div class="modal-header">
		<h4 class="modal-title" id="myModalLabel">{{ trans('panichd::admin.agent-edit-title',['agent'=>$agent->name]) }}</h4>
	  	<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
	  </div>
	  <div class="modal-body">								  
		{!! CollectiveForm::open([
				'method' => 'PATCH',
				'route' => [
						$setting->grab('admin_route').'.agent.update',
						$agent->id
						],
				]) !!}
		<table class="table table-hover table-striped">
			<thead><th>{{ trans('panichd::admin.agent-edit-table-category') }}</th>
			<th>{{ trans('panichd::admin.agent-edit-table-agent') }}</th>
			<th>{{ trans('panichd::admin.agent-edit-table-autoassign') }}</th></thead>
			<tbody>
			@foreach($categories as $agent_cat)
				<tr>
				<td>{{ $agent_cat->name }}</td>
				<td><input id="checkbox_agent_{!!$agent->id!!}_cat_{!! $agent_cat->id !!}" class="jquery_agent_cat{!! (count($agent->categories->whereIn('id',$agent_cat->id))>0) ? " checked" : ""  !!}" name="agent_cats[]"
			   type="checkbox"
			   value="{{ $agent_cat->id }}"
			   {!! (count($agent->categories->whereIn('id',$agent_cat->id))>0) ? "checked=\"checked\"" : ""  !!}
			   ></td>
				<td><input id="checkbox_agent_{!!$agent->id!!}_cat_{!! $agent_cat->id !!}_auto" name="agent_cats_autoassign[]"
			   type="checkbox"
			   value="{{ $agent_cat->id }}" {!! ((!$agent->categories->first(function($q) use($agent_cat) { return $q->id == $agent_cat->id;}) || $agent->categories->whereIn('id',$agent_cat->id)->first()['pivot']['autoassign']==0)) ? "" : "checked=\"checked\""  !!} 
			   {!! (count($agent->categories->whereIn('id',$agent_cat->id)) == 0) ? "disabled=\"disabled\"" : ""  !!}
			   
			   ></td>
				</tr>
			@endforeach
			</tbody>
		</table>
	  </div>
	  <div class="modal-footer">
		{!! CollectiveForm::submit( trans('panichd::admin.btn-update') , ['class' => 'btn btn-info']) !!}
	  </div>
	  
		{!! CollectiveForm::close() !!}
	</div>
  </div>
</div>