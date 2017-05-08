@foreach ($categories as $id=>$category)
	<select id="jquery_tag_category_{{$id}}" class="jquery_tag_category  select2-multiple" name="category_{{$id}}_tags[]" multiple="multiple" style="display: none; width: 100%">
	
	<?php $cat_tags = $tag_lists;
	$cat_tags = $cat_tags->filter(function($q) use($id){
		return $q->id==$id;
	})->first()->tags()->get();
	
	?>				
	@foreach ($cat_tags as $i=>$tag)
		<option value="{{$tag->id}}" {{ array_key_exists($tag->id,$ticket_tags)?' selected="selected"':' style=""'}}>{{$tag->name}}</option>
	@endforeach
	
	</select>
@endforeach