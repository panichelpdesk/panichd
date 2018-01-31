<div class="title calendar">{{ trans('panichd::lang.filter-calendar') }}</div>
<?php $text_cld = "";
$calendar_name = "All";
$cld_class = "";

$cld_options = [
	'expired' => [
		'class' => 'text-danger',
		'icon' => 'glyphicon-exclamation-sign',
	],
	'today' => [
		'class' => 'text-warning',
		'icon' => 'glyphicon-warning-sign',
	],
	'tomorrow' => [
		'class' => 'text-info',
		'icon' => 'glyphicon-time',
	],
	'week' => [
		'class' => 'text-info',
		'icon' => 'glyphicon-calendar',
	],
	'month' => [
		'class' => 'text-info',
		'icon' => 'glyphicon-calendar',
	],

];

?>
@foreach ($counts['calendar'] as $cld=>$count)			
	<?php $text_cld.='<li><a href="'.url($setting->grab('main_route').'/filter/calendar/'.$cld).'">';
	
	$this_cld = '<span class="'.(isset($cld_options[$cld]['class']) ? $cld_options[$cld]['class'] : "").'">'.( isset($cld_options[$cld]['icon']) ? '<span class="glyphicon '.$cld_options[$cld]['icon'].'"></span> ' : '').trans('panichd::lang.filter-calendar-'.$cld).' <span class="badge">'.$count.'</span></span>';
	?>
	@if ($cld==session('panichd_filter_calendar'))
		<?php $calendar_name = $this_cld;
		$cld_class = isset($cld_options[$cld]['class']) ? $cld_options[$cld]['class'] : "";?>
	@endif
	<?php $text_cld.= $this_cld . '</a></li>';?>		
@endforeach
<div class="dropdown" style="display: inline-block;">
<button class="btn btn-default dropdown-toggle" type="button" data-toggle="dropdown" style="border: none;"><span class="">{!! $calendar_name=="All" ? trans('panichd::lang.filter-calendar-all') : $calendar_name !!}
<span class="caret {{ $cld_class }}"></span></button>
<ul class="dropdown-menu">
@if ($calendar_name!="All")
<li><a href="{{ action('\PanicHD\PanicHD\Controllers\TicketsController@index') }}/filter/calendar/remove">{{ trans('panichd::lang.filter-calendar-all') }}</a></li>
@endif
{!! $text_cld !!}</ul>
</div>




<div class="title category">{{ trans('panichd::lang.filter-category') }}</div> 
@if (count($filters['category'])==1)
	<?php $cat_color = $filters['category']{0}->color;?>
	<span class="btn-category" style="color: {{ $cat_color }}">{{$filters['category']{0}->name}}</span>
@else		
	<?php $text_cat = "";
	$category_name = "All";
	$cat_color = "gray";?>
	@foreach ($filters['category'] as $cat)			
		<?php $text_cat.='<li><a href="'.url($setting->grab('main_route').'/filter/category/'.$cat->id).'">';?>
		@if ($cat->id==session('panichd_filter_category'))
			<?php $category_name='<span style="color: '.$cat->color.'">'.$cat->name.'</span> <span class="badge" style="background-color: '.$cat->color.'">'.$cat->tickets_count.'</span>';
			$cat_color=$cat->color;?>
		@endif
		<?php $text_cat.='<span style="color: '.$cat->color.'">'.$cat->name.'</span> <span class="badge" style="background-color: '.$cat->color.'">'.$cat->tickets_count.'</span></a></li>';?>		
	@endforeach
	<div class="dropdown" style="display: inline-block;">
	<button class="btn btn-default btn-category dropdown-toggle" type="button" data-toggle="dropdown" style="border: none;">{!! $category_name=="All" ? trans('panichd::lang.filter-category-all') : $category_name !!}
	<span class="caret" style="color: {{ $cat_color }}"></span></button>
	<ul class="dropdown-menu">
	@if ($category_name!="All")
	<li><a href="{{ action('\PanicHD\PanicHD\Controllers\TicketsController@index') }}/filter/category/remove">{{ trans('panichd::lang.filter-category-all') }}</a></li>
	@endif
	{!! $text_cat !!}</ul>
	</div>
@endif

<div class="title agent">{{ trans('panichd::lang.filter-agent') }}</div> 
@if (count($filters['agent'])>$setting->grab('max_agent_buttons'))
	
	<div id="select_agent_container" class="select2_filter {{ session('panichd_filter_agent')=="" ? 'all' : 'single'}}">
		<select id="select_agent" style="width: 200px">
		<option value="/filter/agent/remove">{{ trans('panichd::lang.filter-agent-all') }}</option>
		@foreach ($filters['agent'] as $ag)			
			<option value="/filter/agent/{{$ag->id}}"
			@if ($ag->id==session('panichd_filter_agent'))
				selected="selected"
			@endif
			>{{$ag->name}} ({!!$ag->agent_total_tickets_count !!})</option>
		@endforeach
		</select>
	</div>
@else	
	<?php $agent_button_size='';?>
	@if(count($filters['agent'])==1)
		<button class="btn btn-default {{ $agent_button_size }} agent-current">{{$filters['agent']{0}->name}}</button>
	@else
		@if (session('panichd_filter_agent')!="")
			<a href="{{ action('\PanicHD\PanicHD\Controllers\TicketsController@index') }}/filter/agent/remove" class="btn btn-default agent-link {{ $agent_button_size }}">{{ trans('panichd::lang.filter-agent-all') }}</a>
		@elseif(count($filters['agent'])>1)
			<button class="btn btn-info {{ $agent_button_size }} agent-current">{{ trans('panichd::lang.filter-agent-all') }}</button>		
		@endif
	
		@foreach ($filters['agent'] as $ag)
			@if ($ag->id==session('panichd_filter_agent'))
				<button class="btn btn-default {{ $agent_button_size }} agent-current"><span>{{$ag->name}}</span> <span class="badge">{!!$ag->agent_total_tickets_count !!}</span></button>				
			@else
				<a href="{{ action('\PanicHD\PanicHD\Controllers\TicketsController@index') }}/filter/agent/{{$ag->id}}" class="btn btn-default agent-link {{ $agent_button_size }}">{{$ag->name}} <span class="badge">{!!$ag->agent_total_tickets_count !!}</span></a>
			@endif			
		@endforeach	
	@endif
	
@endif

<script type="text/javascript">
@section('footer_jquery')
	// Filter menu agent change
	$('#select_agent').select2({"id": "prova_sel2"}).on("change", function (e) {				
		window.location.href="{{ URL::to('/').'/'.$setting->grab('main_route') }}"+$(this).val();				
	});
	
	$('#select_agent_container .select2-selection__rendered').css("color", "{{ $cat_color }}");
	$('#select_agent_container .select2-selection__arrow b')
		.css("border-top-color", "{{ $cat_color }}")
		.css("border-bottom-color", "{{ $cat_color }}");
@append
</script>