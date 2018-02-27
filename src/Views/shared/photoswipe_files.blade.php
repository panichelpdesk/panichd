@section('content')
	<link rel="StyleSheet" href="{{asset('vendor/panichd/css/photoswipe.css')}}">
	<link rel="StyleSheet" href="{{asset('vendor/panichd/css/photoswipe-default-skin/default-skin.css')}}">
	
	<!-- Root element of PhotoSwipe. Must have class pswp. -->
	<div class="pswp" tabindex="-1" role="dialog" aria-hidden="true">

		<!-- Background of PhotoSwipe. 
			 It's a separate element as animating opacity is faster than rgba(). -->
		<div class="pswp__bg"></div>

		<!-- Slides wrapper with overflow:hidden. -->
		<div class="pswp__scroll-wrap">

			<!-- Container that holds slides. 
				PhotoSwipe keeps only 3 of them in the DOM to save memory.
				Don't modify these 3 pswp__item elements, data is added later on. -->
			<div class="pswp__container">
				<div class="pswp__item"></div>
				<div class="pswp__item"></div>
				<div class="pswp__item"></div>
			</div>

			<!-- Default (PhotoSwipeUI_Default) interface on top of sliding area. Can be changed. -->
			<div class="pswp__ui pswp__ui--hidden">

				<div class="pswp__top-bar">

					<!--  Controls are self-explanatory. Order can be changed. -->

					<div class="pswp__counter"></div>

					<button class="pswp__button pswp__button--close" title="Close (Esc)"></button>					

					<button class="pswp__button pswp__button--fs" title="Toggle fullscreen"></button>

					<button class="pswp__button pswp__button--zoom" title="Zoom in/out"></button>

					<!-- Preloader demo http://codepen.io/dimsemenov/pen/yyBWoR -->
					<!-- element will get class pswp__preloader--active when preloader is running -->
					<div class="pswp__preloader">
						<div class="pswp__preloader__icn">
						  <div class="pswp__preloader__cut">
							<div class="pswp__preloader__donut"></div>
						  </div>
						</div>
					</div>
				</div>

				<div class="pswp__share-modal pswp__share-modal--hidden pswp__single-tap">
					<div class="pswp__share-tooltip"></div> 
				</div>

				<button class="pswp__button pswp__button--arrow--left" title="Previous (arrow left)">
				</button>

				<button class="pswp__button pswp__button--arrow--right" title="Next (arrow right)">
				</button>

				<div class="pswp__caption">
					<div class="pswp__caption__center" style="font-size: 18px;"></div>
				</div>

			</div>

		</div>

	</div>
	
@append

@section('footer')
	<script src="{{ asset('vendor/panichd/js/photoswipe.min.js') }}"></script>
	<script src="{{ asset('vendor/panichd/js/photoswipe-ui-default.min.js') }}"></script>
	<script>
	$(function(){
		// Modify link for each image in ticket to launch PhotoSwipe
		$('.pwsp_gallery_link, .summernote_thumbnail_link').click(function(e){
			var openpid = $(this).data('pwsp-pid');
			if (typeof openpid === 'undefined' || openpid == ""){
				var parts = $(this).prop('href').split('/');
				openpid = Number(parts[parts.length -1]);
			}
			
			var openindex = 0;
			
			for (var i = 0, len = pswpItems.length; i < len; i++) {
				if (pswpItems[i].pid === openpid){
					openindex = i;
					break;
				}
			}
			
			var options = {
				bgOpacity: 0.8,
				index: openindex
			};
			
			// PhotoSwipe gallery
			var pswpElement = document.querySelectorAll('.pswp')[0];
			
			// Initializes PhotoSwipe
			var gallery = new PhotoSwipe( pswpElement, PhotoSwipeUI_Default, pswpItems, options);
			gallery.init();
			
			e.preventDefault();
		});	
	});
	</script>
@append