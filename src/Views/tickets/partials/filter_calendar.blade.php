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
	]
];

if ($setting->grab('calendar_month_filter')){
	$cld_options['week'] = [
		'class' => 'text-info',
		'icon' => 'glyphicon-calendar',
	];
	$cld_options['month'] = [
		'class' => 'text-info',
		'icon' => 'glyphicon-calendar',
	];
}else{
	$cld_options['within-7-days'] = [
		'class' => 'text-info',
		'icon' => 'glyphicon-calendar',
	];
	$cld_options['within-14-days'] = [
		'class' => 'text-info',
		'icon' => 'glyphicon-calendar',
	];
}

$cld_options['not-scheduled'] = [
	'class' => 'text-default',
	'icon' => 'glyphicon-file'
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
<button class="btn btn-light dropdown-toggle" type="button" data-toggle="dropdown" style="border: none;"><span class="">{!! $calendar_name=="All" ? trans('panichd::lang.filter-calendar-all') : $calendar_name !!}
<span class="caret {{ $cld_class }}"></span></button>
<ul class="dropdown-menu">
@if ($calendar_name!="All")
<li><a href="{{ action('\PanicHD\PanicHD\Controllers\TicketsController@index') }}/filter/calendar/remove">{{ trans('panichd::lang.filter-calendar-all') }}</a></li>
@endif
{!! $text_cld !!}</ul>
</div>