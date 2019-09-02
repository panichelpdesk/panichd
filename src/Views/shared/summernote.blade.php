@section('panichd_assets')
    @if($editor_enabled)
        <link rel="StyleSheet" href="{{asset('vendor/panichd/summernote/summernote-' . PanicHD\PanicHD\Helpers\Cdn::Summernote .'.bs4.css')}}">
        @if($codemirror_enabled)
            <link rel="StyleSheet" href="{{asset('vendor/panichd/codemirror/codemirror-' . PanicHD\PanicHD\Helpers\Cdn::CodeMirror . '.css')}}">
            <link rel="StyleSheet" href="{{asset('vendor/panichd/codemirror/codemirror-' . PanicHD\PanicHD\Helpers\Cdn::CodeMirror . '-' . $codemirror_theme . '.css')}}">
        @endif
    @endif
@append

@section('footer')
    @if($codemirror_enabled)
        <script src="{{asset('vendor/panichd/codemirror/codemirror-' . PanicHD\PanicHD\Helpers\Cdn::CodeMirror . '.js')}}"></script>
    @endif

    <script src="{{ asset('vendor/panichd/summernote/summernote-' . PanicHD\PanicHD\Helpers\Cdn::Summernote .'.bs4.min.js') }}"></script>
    @if($editor_locale)
        <script src="{{ asset('vendor/panichd/summernote/lang/summernote-'.$editor_locale.'.js') }}"></script>
    @endif
@append