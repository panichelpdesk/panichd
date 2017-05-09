<div class="row">
	<div class="col-sm-5">
	<div class="form-group">
		{!! CollectiveForm::label('name', trans('ticketit::admin.category-create-name') . trans('ticketit::admin.colon'), ['class' => 'col-lg-2 control-label']) !!}
		<div class="col-lg-10">
			{!! CollectiveForm::text('name', isset($category->name) ? $category->name : null, ['class' => 'form-control']) !!}
		</div>
	</div>
	<div class="form-group">
		{!! CollectiveForm::label('color', trans('ticketit::admin.category-create-color') . trans('ticketit::admin.colon'), ['class' => 'col-lg-2 control-label']) !!}
		<div class="col-lg-10">
			{!! CollectiveForm::custom('color', 'color', isset($category->color) ? $category->color : "#000000", ['class' => 'form-control']) !!}
		</div>
	</div>
	</div>
	<div class="col-sm-offset-1 col-sm-6">	
	<div class="form-group">
		<label class="control-label col-sm-2" for="admin-select2-tags">New tags</label>
		<div class="col-sm-9">
		<select id="admin-select2-tags" class="select2-multiple" name="new_tags[]" multiple="multiple" style="display: none; width: 100%"></select></div>
		
	</div>
	<h4>Tag list</h4>
	@if (isset($category) and $category->has('tags'))		
		<div id="tag-panel" class="btn-group-panel">
			@foreach ($category->tags as $i=>$tag)		
				<div class="btn-group jquery_tag_group_unchecked">				
				<a href="#" role="button" id="jquery_tag_check_{{$i}}" class="btn btn-default jquery_tag_check" title="Eliminar etiqueta {{$tag->name}}" aria-label="Eliminar etiqueta {{$tag->name}}"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span><span class="glyphicon glyphicon-ok" aria-hidden="true" style="display: none"></span></a>								
				<a href="#" role="button" id="tag_text_{{$i}}" class="btn btn-default jquery_tag_text" aria-label="Etiqueta {{$tag->name}}" title="Etiqueta '{{$tag->name}}' conté {{$tag->tickets_count}} tiquets relacionats" data-toggle="modal" data-target="#tag-edit-modal" data-tag_name="{{$tag->name}}" data-tag_i="{{$i}}" style="color: {{$tag->text_color}}; background: {{$tag->bg_color}}"><span class="name">{{$tag->name}}</span> ({{$tag->tickets_count}})</a>
				
				</div>
				<input type="hidden" id="jquery_delete_tag_{{$i}}" name="jquery_delete_tag_{{$i}}" value="{{$tag->id}}" disabled="disabled">
				<input type="hidden" id="jquery_tag_id_{{$i}}" name="jquery_tag_id_{{$i}}" value="{{$tag->id}}">
				<input type="hidden" id="jquery_tag_name_{{$i}}" name="jquery_tag_name_{{$i}}" value="{{$tag->name}}" disabled="disabled">
				<input type="hidden" id="jquery_tag_color_{{$i}}" name="jquery_tag_color_{{$i}}" value="{{$tag->bg_color.'_'.$tag->text_color}}" disabled="disabled">
			@endforeach
		</div>
		<input type="hidden" name="tags_count" value="<?=isset($i)?$i+1:0;?>}}">
	@endif
	</div>
</div>

<div class="row">	
	<div class="col-lg-10 col-lg-offset-2">
		{!! link_to_route($setting->grab('admin_route').'.category.index', trans('ticketit::admin.btn-back'), null, ['class' => 'btn btn-default']) !!}
		@if(isset($category))
			{!! CollectiveForm::submit(trans('ticketit::admin.btn-update'), ['class' => 'btn btn-primary']) !!}
		@else
			{!! CollectiveForm::submit(trans('ticketit::admin.btn-submit'), ['class' => 'btn btn-primary']) !!}
		@endif
	</div>
</div>