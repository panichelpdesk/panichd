@extends($master)
@section('page')
	Search results
@endsection

@include('panichd::shared.common')

@include('panichd::tickets.datatable.assets')

@section('content')
    @include('panichd::tickets.search.form')
    <div id="search_results" class="card bg-light" style="display: none">
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
	@include('panichd::tickets.partials.form_scripts')
	@include('panichd::tickets.search.scripts')
	@include('panichd::tickets.partials.tags_footer_script')
@append
