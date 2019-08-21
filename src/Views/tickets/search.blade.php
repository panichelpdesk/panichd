@extends($master)
@section('page')
	Search tickets
@endsection

@include('panichd::shared.common')

@section('content')
	@include('panichd::tickets.search.form')
@endsection

@include('panichd::shared.datetimepicker')

@section('footer')
	@include('panichd::tickets.partials.form_scripts')
	@include('panichd::tickets.partials.tags_footer_script')
@append
