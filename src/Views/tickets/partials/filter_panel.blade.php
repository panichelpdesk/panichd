<div class="title category">{{ trans('ticketit::lang.table-category') }}</div> 
@if (count($counts['category'])==1)
	<?php $cat_color = $counts['category']{0}->color;?>
	<span class="btn-category" style="color: {{ $cat_color }}">{{$counts['category']{0}->name}}</span>
@else		
	<?php $text_cat = "";
	$category_name = "All";
	$cat_color = "gray";?>
	@foreach ($counts['category'] as $cat)			
		<?php $text_cat.='<li><a href="'.url($setting->grab('main_route').'/filter/category/'.$cat->id).'">';?>
		@if ($cat->id==session('ticketit_filter_category'))
			<?php $category_name='<span style="color: '.$cat->color.'">'.$cat->name.'</span> <span class="badge" style="background-color: '.$cat->color.'">'.$cat->tickets_count.'</span>';
			$cat_color=$cat->color;?>
		@endif
		<?php $text_cat.='<span style="color: '.$cat->color.'">'.$cat->name.'</span> <span class="badge" style="background-color: '.$cat->color.'">'.$cat->tickets_count.'</span></a></li>';?>		
	@endforeach
	<div class="dropdown" style="display: inline-block;">
	<button class="btn btn-default btn-category dropdown-toggle" type="button" data-toggle="dropdown" style="border: none;">{!! $category_name=="All" ? trans('ticketit::lang.filter-category-all') : $category_name !!}
	<span class="caret" style="color: {{ $cat_color }}"></span></button>
	<ul class="dropdown-menu">
	@if ($category_name!="All")
	<li><a href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@index') }}/filter/category/remove">{{ trans('ticketit::lang.filter-category-all') }}</a></li>
	@endif
	{!! $text_cat !!}</ul>
	</div>
@endif

<div class="title agent">Agent</div> 
@if (count($counts['agent'])>4)
	
	<div id="select_agent_container" class="{{ session('ticketit_filter_agent')=="" ? 'all' : 'single'}}">
		<select id="select_agent" style="width: 200px">
		<option value="/filter/agent/remove">Tots</option>
		@foreach ($counts['agent'] as $ag)			
			<option value="/filter/agent/{{$ag->id}}"
			@if ($ag->id==session('ticketit_filter_agent'))
				selected="selected"
			@endif
			>{{$ag->name}} ({!!$ag->agent_total_tickets_count !!})</option>
		@endforeach
		</select>
	</div>
@else	
	@if(count($counts['agent'])==1)
		<button class="btn btn-default btn-sm agent-current" style="color: {{ $cat_color }}">{{$counts['agent']{0}->name}}</button>
	@else
		@if (session('ticketit_filter_agent')!="")
			<a href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@index') }}/filter/agent/remove" class="btn btn-default agent-link btn-sm">Tots</a>
		@elseif(count($counts['agent'])>1)
			<button class="btn btn-info btn-sm agent-current" style="color: {{ $cat_color }}">Tots</button>		
		@endif
	
		@foreach ($counts['agent'] as $ag)
			@if ($ag->id==session('ticketit_filter_agent'))
				<button class="btn btn-default btn-sm agent-current"><span style="color: {{ $cat_color }}">{{$ag->name}}</span> <span class="badge" style="background: {{ $cat_color }}">{!!$ag->agent_total_tickets_count !!}</span></button>				
			@else
				<a href="{{ action('\Kordy\Ticketit\Controllers\TicketsController@index') }}/filter/agent/{{$ag->id}}" class="btn btn-default agent-link btn-sm">{{$ag->name}} <span class="badge">{!!$ag->agent_total_tickets_count !!}</span></a>
			@endif			
		@endforeach	
	@endif
	
@endif

{{--@if( isset($counts['owner']))
	<div class="title owner">Propietari</div>
	<a href="{{ session('ticketit_filter_owner')==''?'#':action('\Kordy\Ticketit\Controllers\TicketsController@index').'/filter/owner/remove' }}" class="btn btn-default {{ session('ticketit_filter_owner')==''?'owner-current':'owner-link' }} btn-sm">Tots</a>
	 <a href="{{ session('ticketit_filter_owner')=='me'?'#':action('\Kordy\Ticketit\Controllers\TicketsController@index').'/filter/owner/me' }}" class="btn btn-default {{ session('ticketit_filter_owner')=='me'?'owner-current':'owner-link' }} btn-sm">Jo</a>		
@endif--}}

{!! link_to_route($setting->grab('main_route').'.create', trans('ticketit::lang.btn-create-new-ticket'), null, ['class' => 'btn btn-default pull-right']) !!}

<script type="text/javascript">
@section('footer_jquery')
	// Filter menu agent change
	$('#select_agent').select2().on("change", function (e) {				
		window.location.href="{{ URL::to('/').'/'.$setting->grab('main_route') }}"+$(this).val();				
	});
	
	$('#select_agent_container .select2-selection__rendered').css("color", "{{ $cat_color }}");
	$('#select_agent_container .select2-selection__arrow b')
		.css("border-top-color", "{{ $cat_color }}")
		.css("border-bottom-color", "{{ $cat_color }}");
@append
</script>