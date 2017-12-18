@section('panichd_errors')
	<div class="container-fluid">
	<div class="alert alert-danger" id="form_errors" style="{{ $errors->first() == '' ? 'display: none;' : '' }}">
		<button type="button" class="close" data-dismiss="alert">{{ trans('panichd::lang.flash-x') }}</button>
		<div style="font-weight: bold">{{ trans('panichd::lang.validation-error') . trans('panichd::lang.colon') }}</div>
		<ul>
			@foreach ($errors->all() as $error)
				<li>{{ $error }}</li>
			@endforeach
		</ul>
	</div>
	@if(Session::has('warning'))
		<div class="alert alert-danger">
			<button type="button" class="close" data-dismiss="alert">{{ trans('panichd::lang.flash-x') }}</button>
			{!! session('warning') !!}
		</div>
	@endif
	@if(Session::has('status'))
		<div class="alert alert-success">
			<button type="button" class="close" data-dismiss="alert">{{ trans('panichd::lang.flash-x') }}</button>
			{!! session('status') !!}
		</div>
	@endif
	</div>
@stop
