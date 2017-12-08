@if($editor_enabled)

@if($codemirror_enabled)
    <script src="{{asset('vendor/panichd/js/codemirror/codemirror.min.js')}}"></script>
    <script src="{{asset('vendor/panichd/js/codemirror/mode/xml.min.js')}}"></script>
	
@endif

<script src="{{ asset('vendor/panichd/js/summernote/summernote.min.js') }}"></script>
@if($editor_locale)
    <script src="{{ asset('vendor/panichd/js/summernote/lang/summernote-'.$editor_locale.'.min.js') }}"></script>
@endif
<script>


    $(function() {

        var options = $.extend(true, {lang: '{{$editor_locale}}' {!! $codemirror_enabled ? ", codemirror: {theme: '{$codemirror_theme}', mode: 'text/html', htmlMode: true, lineWrapping: true}" : ''  !!} } , {!! $editor_options !!});

        $("textarea.summernote-editor").summernote(options);

        $("label[for=content]").click(function () {
            $("#content").summernote("focus");
        });
    });


</script>
@endif