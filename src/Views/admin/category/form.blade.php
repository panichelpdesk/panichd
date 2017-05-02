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
		<select id="admin-select2-tags" class="select2-multiple" name="new_tags[]" multiple="multiple" style="width: 100%"></select></div>
		
	</div>
	<h4>Tag list</h4>
	@if (isset($category) and $category->has('tags'))		
		<div class="btn-group-panel">
			@foreach ($category->tags as $i=>$tag)		
				<div class="btn-group">
				<a href="#" role="button" id="tag_delete_{{$i}}" class="btn btn-default jquery_button_delete_tag" title="Eliminar etiqueta {{$tag->name}}" aria-label="Eliminar etiqueta {{$tag->name}}"><span class="glyphicon glyphicon-remove" aria-hidden="true"></span></a>
				<a href="#" role="button" id="tag_keep_{{$i}}" class="btn btn-default jquery_button_keep_tag" title="Mantenir etiqueta {{$tag->name}}" aria-label="Mantenir etiqueta {{$tag->name}}" style="display: none"><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a>
				<button type="button" id="tag_text_{{$i}}" class="btn btn-default" aria-label="Etiqueta {{$tag->name}}" title="Etiqueta '{{$tag->name}}' conté {{$tag->tickets_count}} tiquets relacionats" style="pointer-events: none">{{$tag->name}} ({{$tag->tickets_count}})</button>
				</div>
				<input type="hidden" id="jquery_delete_tag_{{$i}}" name="jquery_delete_tag_{{$i}}" value="{{$tag->id}}" disabled="disabled">
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
