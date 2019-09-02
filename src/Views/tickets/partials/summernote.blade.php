@if($editor_enabled)
    @include('panichd::shared.summernote')

    @section('footer')
        <script src="{{asset('vendor/panichd/codemirror/mode/xml-' . PanicHD\PanicHD\Helpers\Cdn::CodeMirror . '.js')}}"></script>
        <script>
            var summernote_options = $.extend(true, {lang: '{{$editor_locale}}' {!! $codemirror_enabled ? ", codemirror: {theme: '{$codemirror_theme}', mode: 'text/html', htmlMode: true, lineWrapping: true}" : ''  !!} } , {!! $editor_options !!});

            $(function() {
                // Usage within HTML Body
                $("textarea.summernote-editor").summernote(summernote_options);

                $("label[for=content]").click(function () {
                    $("#content").summernote("focus");
                });
            });
        </script>
    @append
@endif
