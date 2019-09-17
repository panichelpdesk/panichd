@extends($master)
@section('page')
	{{ trans('panichd::lang.searchform-title') }}
@endsection

@include('panichd::shared.common')

@include('panichd::tickets.datatable.assets')

@section('content')
    @include('panichd::tickets.search.form')
    <div id="search_results" class="card bg-light" @if(!isset($search_fields)) style="display: none" @endif>
        <div class="card-body">
            <h5 class="card-title mb-4">{{ trans('panichd::lang.searchform-results-title') }}
                <button type="button" id="edit_search" class="btn btn-default btn-sm ml-4">{{ trans('panichd::lang.searchform-btn-edit') }}</button>
                <a id="copy_search_URL" class="btn btn-default btn-sm ml-2 tooltip-info" href="{!! url()->current() !!}" title="{{ trans('panichd::lang.searchform-help-btn-web') }}">{{ trans('panichd::lang.searchform-btn-web') }}</a>
            </h5>
            @include('panichd::tickets.datatable.header')
        </div>
    </div>
    @include('panichd::tickets.partials.modal_agent')
@endsection

@include('panichd::shared.datetimepicker')

@include('panichd::tickets.partials.data_reload')

@section('footer')
    @include('panichd::tickets.datatable.loader')
    @include('panichd::tickets.datatable.events')
	@include('panichd::tickets.partials.form_scripts')
    @include('panichd::tickets.partials.tags_footer_script')
    @include('panichd::tickets.search.scripts')
@append
