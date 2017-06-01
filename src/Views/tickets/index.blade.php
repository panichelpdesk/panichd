<div id="ticketit_filter_panel" class="panel panel-default">	
	@if (isset($counts['agent']))
	<div class="panel-body text-left">
    @if( isset($counts['owner']))
		<div class="title owner">Propietari</div>
		<a href="{{ session('ticketit_filter_owner')==''?'#':action('\Kordy\Ticketit\Controllers\TicketsController@index').'/filter/owner/remove' }}" class="btn btn-default {{ session('ticketit_filter_owner')==''?'owner-current':'owner-link' }} btn-sm">Tots <span class="badge">{{ $counts['owner']['all'] }}</span></a>
		 <a href="{{ session('ticketit_filter_owner')=='me'?'#':action('\Kordy\Ticketit\Controllers\TicketsController@index').'/filter/owner/me' }}" class="btn btn-default {{ session('ticketit_filter_owner')=='me'?'owner-current':'owner-link' }} btn-sm">Jo <span class="badge">{{ $counts['owner']['me'] }}</span></a>		
	@endif

	<div class="title category">Categoria</div> 
	@if (count($counts['category'])==1)
		<span class="btn-category" style="color: {{$counts['category']{0}->color}}">{{$counts['category']{0}->name}}</span> <span class="badge" style="background-color: {{$counts['category']{0}->color}}">{{$counts['category']{0}->tickets_count}}</span>
	@else		
		<?php $text_cat=""; $category_name="All";
		$all_categories='Totes <span class="badge">'.$counts['total_category'].'</span>';
		$caret_color="gray";?>
		@foreach ($counts['category'] as $cat)			
			<?php $text_cat.='<li><a href="'.url($setting->grab('main_route').'/filter/category/'.$cat->id).'">';?>
			@if ($cat->id==session('ticketit_filter_category'))
				<?php $category_name='<span style="color: '.$cat->color.'">'.$cat->name.'</span> <span class="badge" style="background-color: '.$cat->color.'">'.$cat->tickets_count.'</span>';
				$caret_color=$cat->color;?>
			@endif
			<?php $text_cat.='<span style="color: '.$cat->color.'">'.$cat->name.'</span> <span class="badge" style="background-color: '.$cat->color.'">'.$cat->tickets_count.'</span></a></li>';?>		
		@endforeach
		<div class="dropdown" style="display: inline-block;">
		<button class="btn btn-default btn-category dropdown-toggle" type="button" data-toggle="dropdown" style="border: none;">{!! $category_name=="All" ? $all_categories : $category_name !!}
		<span class="caret" style="color: {{ $caret_color }}"></span></button>
		<ul class="dropdown-menu">
		@if ($category_name!="All")
		<li><a href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@index') }}/filter/category/remove">{!!$all_categories!!}</a></li>
		@endif
		{!! $text_cat !!}</ul>
		</div>
	@endif
	
	<div class="title agent">Agent</div> 
	@if (count($counts['agent'])>3)
		<select class="nav_filter_select" style="width: 200px">
		<option value="/filter/agent/remove">Tots ({{$counts['total_agent']}})</option>
		@foreach ($counts['agent'] as $ag)			
			<option value="/filter/agent/{{$ag->id}}"
			@if ($ag->id==session('ticketit_filter_agent'))
				selected="selected"
			@endif
			>{{$ag->name}} ({!!$ag->agent_total_tickets_count !!})</option>		
		@endforeach
		</select>
	@else
		@if (session('ticketit_filter_agent')!="")
			<a href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@index') }}/filter/agent/remove" class="btn btn-default agent-link btn-sm">Tots <span class="badge">{{$counts['total_agent']}}</span></a>
		@else
			<button class="btn btn-info btn-sm agent-current">Tots <span class="badge">{{$counts['total_agent']}}</span></button>
		@endif	
		
		@foreach ($counts['agent'] as $ag)
			@if ($ag->id==session('ticketit_filter_agent'))
				<button class="btn btn-default btn-sm agent-current">{{$ag->name}} <span class="badge">{!!$ag->agent_total_tickets_count !!}</span></button>				
			@else
				<a href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@index') }}/filter/agent/{{$ag->id}}" class="btn btn-default agent-link btn-sm">{{$ag->name}} <span class="badge">{!!$ag->agent_total_tickets_count !!}</span></a>
			@endif			
		@endforeach
	@endif
	
	{!! link_to_route($setting->grab('main_route').'.create', trans('ticketit::lang.btn-create-new-ticket'), null, ['class' => 'btn btn-primary pull-right']) !!}
	
	</div><!-- /panel-body -->
	@else
	<div class="panel-body">
		<h2 class="text-center" style="margin: 0em;">{!! trans('ticketit::lang.index-my-tickets') !!}
		{!! link_to_route($setting->grab('main_route').'.create', trans('ticketit::lang.btn-create-new-ticket'), null, ['class' => 'btn btn-primary pull-right']) !!}</h2>
	</div>
	@endif
</div><!-- /panel -->


<div class="panel panel-default">
    <div class="panel-body">
        <div id="message"></div>

        @include('ticketit::tickets.partials.datatable')
    </div>

</div>
