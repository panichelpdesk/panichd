<div class="alert alert-danger" id="form_errors" style="{{ $errors->first() == '' ? 'display: none;' : '' }}">
	<button type="button" class="close" data-dismiss="alert">{{ trans('ticketit::lang.flash-x') }}</button>
	<ul>
		@foreach ($errors->all() as $error)
			<li>{{ $error }}</li>
		@endforeach
	</ul>
</div>
@if(Session::has('warning'))
    <div class="alert alert-danger">
        <button type="button" class="close" data-dismiss="alert">{{ trans('ticketit::lang.flash-x') }}</button>
        {!! session('warning') !!}
    </div>
@endif
@if(Session::has('status'))
    <div class="alert alert-success">
        <button type="button" class="close" data-dismiss="alert">{{ trans('ticketit::lang.flash-x') }}</button>
        {!! session('status') !!}
    </div>
@endif
