<!-- filter panel --><div id="ticketit_filter_panel" class="panel panel-default">	
	@if (isset($counts['agent']))
		<div class="panel-body">
			@include('ticketit::tickets.partials.filter_panel')
		</div>
	@else
		<div class="panel-body">
			<h2 class="text-center" style="margin: 0em;">{!! trans('ticketit::lang.index-my-tickets') !!}
			{!! link_to_route($setting->grab('main_route').'.create', trans('ticketit::lang.btn-create-new-ticket'), null, ['class' => 'btn btn-primary pull-right']) !!}</h2>
		</div>
	@endif
</div><!-- /filter panel -->

<div class="panel panel-default">
    <div class="panel-body">
        <div id="message"></div>

        @include('ticketit::tickets.partials.datatable')
    </div>

</div>
