@section('panichd_assets')
    <link rel="StyleSheet" href="{{ asset('vendor/panichd/css/datatables/datatables-dt-' . PanicHD\PanicHD\Helpers\Cdn::DataTables . '-r-' . PanicHD\PanicHD\Helpers\Cdn::DataTablesResponsive . '.min.css') }}">
@append

@section('footer')
    <script src="{{ asset('vendor/panichd/js/datatables/datatables-dt-' . PanicHD\PanicHD\Helpers\Cdn::DataTables . '-r-' . PanicHD\PanicHD\Helpers\Cdn::DataTablesResponsive . '.min.js') }}"></script>
@append