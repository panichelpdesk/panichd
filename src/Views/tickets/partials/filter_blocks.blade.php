@if($ticketList == 'complete')
	<div class="title year">{{ trans('panichd::lang.year') }}</div>
	<div class="select2_filter" style="width: 150px;">
		<select id="select_year" style="width: 150px; display: none;">
		@php
			$current_year = date('Y');
			
			if (session()->has('panichd_filter_year')){
				$selected_year = session('panichd_filter_year');
			}else{
				$selected_year = date('Y');
			}
		@endphp
		<option value="/filter/year/all" {!! $selected_year == 'all' ? 'selected="selected"' : '' !!}>{{ trans('panichd::lang.filter-year-all') }}</option>

		@foreach ($counts['years'] as $year=>$count)
			<option value="/filter/year/{{ $year == $current_year ? 'remove' : $year }}" {{ $year==$selected_year ? 'selected="selected"' : '' }}>{{ $year }} ({{ $count }})</option>
		@endforeach
		
		</select>
	</div>
@else
	@include('panichd::tickets.partials.filter_calendar')
@endif

<div class="title category">{{ trans('panichd::lang.filter-category') }}</div> 
@if (count($filters['category'])==1)
	<?php $cat_color = $filters['category']{0}->color;?>
	<span class="btn-category" style="color: {{ $cat_color }}">{{$filters['category']{0}->name}}</span>
@else		
	<?php $text_cat = "";
	$category_name = "All";
	$cat_color = "gray";?>
	@foreach ($filters['category'] as $cat)			
		<?php $text_cat.='<a class="dropdown-item" href="'.url($setting->grab('main_route').'/filter/category/'.$cat->id).'">';?>
		@if ($cat->id==session('panichd_filter_category'))
			<?php $category_name='<span style="color: '.$cat->color.'">'.$cat->name.'</span> <span class="badge" style="background-color: '.$cat->color.'">'.$counts['category'][$cat->id].'</span>';
			$cat_color=$cat->color;?>
		@endif
		<?php $text_cat.='<span style="color: '.$cat->color.'">'.$cat->name.'</span> <span class="badge" style="background-color: '.$cat->color.'">'.$counts['category'][$cat->id].'</span></a>';?>
	@endforeach
	<div class="dropdown" style="display: inline-block;">
	<button class="btn btn-light btn-sm btn-category dropdown-toggle" type="button" data-toggle="dropdown" style="border: none;">{!! $category_name=="All" ? trans('panichd::lang.filter-category-all') : $category_name !!}
	<span class="caret" style="color: {{ $cat_color }}"></span></button>
	<ul class="dropdown-menu">
	@if ($category_name!="All")
		<a class="dropdown-item" href="{{ action('\PanicHD\PanicHD\Controllers\TicketsController@index') }}/filter/category/remove">{{ trans('panichd::lang.filter-category-all') }}</a>
	@endif
	{!! $text_cat !!}</ul>
	</div>
@endif

@if (session()->has('panichd_filter_owner'))
	<div class="title owner">
		{{ trans('panichd::lang.owner') }}<b></b>
	</div>
	
	<div class="dropdown" style="display: inline-block;">
	<button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" style="border: none;"><span class="">{{ \PanicHDMember::find(session('panichd_filter_owner'))->name }} <span class="badge">{{ $counts['owner'] }}</span>
	<span class="caret"></span></button>
	<ul class="dropdown-menu">
	<a class="dropdown-item" href="{{ action('\PanicHD\PanicHD\Controllers\TicketsController@index') }}/filter/owner/remove">{{ trans('panichd::lang.filter-owner-all') }}</a>
	</ul>
	</div>
	
	
@endif

<div class="title agent">{{ trans('panichd::lang.filter-agent') }}</div> 
@if (count($filters['agent'])>$setting->grab('max_agent_buttons'))
	
	<div id="select_agent_container" class="select2_filter {{ session('panichd_filter_agent')=="" ? 'all' : 'single'}}">
		<select id="select_agent" style="width: 200px; display: none;">
		<option value="/filter/agent/remove">{{ trans('panichd::lang.filter-agent-all') }}</option>
		@foreach ($filters['agent'] as $ag)			
			<option value="/filter/agent/{{$ag->id}}"
			@if ($ag->id==session('panichd_filter_agent'))
				selected="selected"
			@endif
			>{{$ag->name}} ({!! $counts['agent'][$ag->id] !!})</option>
		@endforeach
		</select>
	</div>
@else	
	<?php $agent_button_size='';?>
	@if(count($filters['agent'])==1)
		<button class="btn btn-light btn-sm {{ $agent_button_size }} agent-current">{{$filters['agent']{0}->name}}</button>
	@else
		@if (session('panichd_filter_agent')!="")
			<a href="{{ action('\PanicHD\PanicHD\Controllers\TicketsController@index') }}/filter/agent/remove" class="btn btn-light btn-sm agent-link {{ $agent_button_size }}">{{ trans('panichd::lang.filter-agent-all') }}</a>
		@elseif(count($filters['agent'])>1)
			<button class="btn btn-info btn-sm {{ $agent_button_size }} agent-current">{{ trans('panichd::lang.filter-agent-all') }}</button>
		@endif
	
		@foreach ($filters['agent'] as $ag)
			@if ($ag->id==session('panichd_filter_agent'))
				<button class="btn btn-light btn-sm {{ $agent_button_size }} agent-current"><span>{{$ag->name}}</span> <span class="badge">{!! $counts['agent'][$ag->id] !!}</span></button>
			@else
				<a href="{{ action('\PanicHD\PanicHD\Controllers\TicketsController@index') }}/filter/agent/{{$ag->id}}" class="btn btn-light btn-sm agent-link {{ $agent_button_size }}">{{$ag->name}} <span class="badge">{!! $counts['agent'][$ag->id] !!}</span></a>
			@endif			
		@endforeach	
	@endif
	
@endif

<script type="text/javascript">
@section('footer_jquery')
	// Filter menu year change
	$('#select_year').select2({"id": "prova_sel2"}).on("change", function (e) {				
		window.location.href="{{ URL::to('/').'/'.$setting->grab('main_route') }}"+$(this).val();				
	});
	
	// Filter menu Agent change
	$('#select_agent').select2({"id": "prova_sel2"}).on("change", function (e) {				
		window.location.href="{{ URL::to('/').'/'.$setting->grab('main_route') }}"+$(this).val();				
	});
	
	$('#select_agent_container .select2-selection__rendered').css("color", "{{ $cat_color }}");
	$('#select_agent_container .select2-selection__arrow b')
		.css("border-top-color", "{{ $cat_color }}")
		.css("border-bottom-color", "{{ $cat_color }}");
@append
</script>