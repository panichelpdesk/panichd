<script type="text/javascript">
/*
 * Color select2 tags in ticket edition
 *
 * This is used after select2 init + every select2:select event + every select2:unselect event
*/
function paint_ticket_tags ()
{
	$('.select2-selection__choice').each(function(elem){
		_colors_elem = $(this).find('.j_tag_colors');
		
		// Set tag colors within select2
		$(this).css('cssText', 'color: ' + _colors_elem.data('text_color') + ' !important;'
			+ ' background-color: ' + _colors_elem.data('bg_color') + ' !important;'
			+ ' border-color: ' + _colors_elem.data('bg_color') + ' !important;');

		// Set remove icon style
		$(this).find('.select2-selection__choice__remove').addClass('mr-2');
		$(this).find('.select2-selection__choice__remove').css('cssText', 'color: ' + _colors_elem.data('text_color') + ' !important;');
	});
}
$(function(){
	// Tags Select2 add color as properties at init
	$('.jquery_tag_category').select2({
		@if(isset($new_tags_allowed)) tags: true, @else tags: false, @endif
			
		templateSelection: function(params){
			_bg_color = params.element.attributes.getNamedItem('data-bg_color').value;
			_text_color = params.element.attributes.getNamedItem('data-text_color').value;

			// Specify tag colors as data attributes
			_option = $('<span class="j_tag_colors" data-bg_color="' + _bg_color + '" data-text_color="' + _text_color + '">' + params.text + '</span>');
			return _option;
		},
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

	// Paint select2 init tags
	paint_ticket_tags();

	$('.jquery_tag_category').on('select2:select', function (e) {
		// Paint select2 tags when selecting within select2
		paint_ticket_tags();
	});

	$('.jquery_tag_category').on('select2:unselect', function (e) {
		// Paint select2 tags when unselecting (click on any times icon) within select2
		paint_ticket_tags();
	});
	
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