@extends($master)
@section('page', trans('ticketit::admin.category-edit-title', ['name' => ucwords($category->name)]))

@section('content')
    @include('ticketit::shared.header')
    <style type="text/css">
	.jquery_tag_group_unchecked .glyphicon-ok, .jquery_tag_group_checked .glyphicon-remove {
		display: none;
	}
	.jquery_tag_group_checked .glyphicon-ok, .jquery_tag_group_unchecked .glyphicon-remove {
		display: inline !important;
	}	
	.jquery_tag_group_unchecked .jquery_tag_text {
		text-decoration: none;
		background-color: white;
	}
	.jquery_tag_group_checked .jquery_tag_text {
		text-decoration: line-through;
		background-color: #ff9999;
	}
	</style>
	<div class="well bs-component">
        {!! CollectiveForm::model($category, [
			'route' => [$setting->grab('admin_route').'.category.update', $category->id],
			'method' => 'PATCH',
			'class' => 'form-horizontal'
			]) !!}
        <legend>{{ trans('ticketit::admin.category-edit-title', ['name' => ucwords($category->name)]) }}</legend>
        @include('ticketit::admin.category.form', ['update', true])
        {!! CollectiveForm::close() !!}
    </div>
	@include('ticketit::admin.category.tags_script')
@stop
