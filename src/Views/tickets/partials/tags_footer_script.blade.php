<script type="text/javascript">
$(function(){
	// Select2 init for tags select 
	$('.jquery_tag_category').select2({
		tags: @if($u->isAdmin() && isset($new_tags_allowed)) true @else false @endif,
		tokenSeparators: [','],
	});

	// Element select
	@if($u->isAdmin() && isset($new_tags_allowed))
		$('.jquery_tag_category').on('select2:selecting', function (e) {
			var _data = e.params.args.data;

			// Is it a new tag?
			if (_data.id == _data.text){
				// Prevent from adding it into select2
				e.preventDefault();
				
				

				// Generate new tag HTML
				var tag_id = embed_new_tag_html();

				// Fill new tag parameters
				update_tag_properties(tag_id, {
					'text' : _data.text,
					'bg_color' : '#c9daf8',
					'text_color' : '#ffffff'
				});

				// Show new tags form element
				$('#new_tags_container').closest('.form-group').slideDown();

				// Clear select2 search input
				$('.jquery_tag_category option[value="' + _data.text + '"]').first().closest('select').select2('close');
			}
		});
	@endif
	
	var category_id = @if(isset($category_id)) '{{ $category_id }}' @else '' @endif;

	// Show tags select for current category only
	$('.jquery_tag_category').each(function(index){
		if (String($(this).prop('id').replace('jquery_tag_category_',''))!=String(category_id)){
			$(this).next().hide();
		}else{
			$(this).next().show();
		}
	});
});
</script>