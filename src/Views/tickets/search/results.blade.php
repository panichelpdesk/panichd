@extends($master)
@section('page')
	Search results
@endsection

@include('panichd::shared.common')

@include('panichd::tickets.datatable.assets')

@section('content')
    @include('panichd::tickets.search.form')
    <div class="card bg-light">
        <div class="card-body">
            <h5 class="card-title mb-4">Search results
                <button type="button" id="edit_search" class="btn btn-default btn-sm ml-4">Edit search</button>
            </h5>
            @include('panichd::tickets.datatable.header')
        </div>
    </div>
@endsection

@include('panichd::shared.datetimepicker')

@section('footer')
    @include('panichd::tickets.datatable.loader')
    @include('panichd::tickets.datatable.events')
    <script type="text/javascript">
        $(function(){
            $('#edit_search').click(function(e){
                e.preventDefault();

                $(this).hide();
                $('#search_form').slideDown();
            });
        });
    </script>
    @include('panichd::tickets.partials.form_scripts')
	@include('panichd::tickets.partials.tags_footer_script')
@append