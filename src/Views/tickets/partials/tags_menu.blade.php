@foreach ($categories as $id=>$category)
	<?php $cat_tags = $tag_lists;
	$cat_tags = $cat_tags->filter(function($q) use($id){
		return $q->id==$id;
	})->first();?>
	
	@if($cat_tags)
		<select id="jquery_tag_category_{{$id}}" class="jquery_tag_category  select2-multiple" name="category_{{$id}}_tags[]" multiple="multiple" style="display: none; width: 100%">
			@foreach ($cat_tags->tags()->get() as $i => $tag)
				<option value="{{$tag->id}}" {{ in_array($tag->id, $a_tags_selected)?' selected="selected"':' style=""'}} data-bg_color="{{ $tag->bg_color }}" data-text_color="{{ $tag->text_color }}">{{$tag->name}}</option>
			@endforeach
		</select>
	@endif
@endforeach