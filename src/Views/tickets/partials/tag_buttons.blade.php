<div class="btn-group-panel">
@foreach ($tag_lists as $i=>$tag)
	<div id="btn-group-{{$i}}" class="btn-group {!! $tag->tickets->count()>0?'checked':'unchecked'!!}{!! $tag->categories->count()>0?' jquery_tag_category jquery_tag_category_'.$tag->categories->first()->id:''!!}"{!! ($tag->categories->count()>0 and $tag->categories->first()->id!=$category_id)?' style="display: none"':''!!}>
		<a href="#" role="button" id="tag_uncheck_{{$i}}" class="btn btn-default jquery_button_uncheck_tag" title="Unassign tag '{{$tag->name}}'" aria-label="Unassign tag '{{$tag->name}}'" {!! $tag->tickets->count()>0?'':'style="display: none"'!!}><span class="glyphicon glyphicon-ok" aria-hidden="true"></span></a>
		<a href="#" role="button" id="tag_check_{{$i}}" class="btn btn-default jquery_button_check_tag" title="Assign tag {{$tag->name}}" aria-label="Assign tag '{{$tag->name}}'"  {!! $tag->tickets->count()>0?'style="display: none"':''!!}><span class="glyphicon glyphicon-unchecked" aria-hidden="true"></span></a>
		<button type="button" class="btn btn-default btn-text" aria-label="Etiqueta {{$tag->name}}" title="Etiqueta '{{$tag->name}}'" style="pointer-events: none">{{$tag->name}}</button>
	</div> 
	<input type="hidden" id="jquery_tag_id_{{$i}}" name="jquery_tag_input_{{$i}}" value="{{$tag->id}}" {!! $tag->tickets->count()>0?'':'disabled="disabled"'!!}>
@endforeach
</div>
<input type="hidden" name="tags_count" value="<?=isset($i)?$i+1:0;?>">