<div class="title calendar">{{ trans('panichd::lang.filter-calendar') }}</div>
<?php $text_cld = "";
$calendar_name = "All";
$cld_class = "";

$cld_options = [
	'expired' => [
		'text_class' => 'text-danger',
		'icon' => 'fa fa-exclamation-circle',
		'badge_class' => 'text-white bg-danger'
	],
	'today' => [
		'text_class' => 'text-warning',
		'icon' => 'fa fa-exclamation-triangle',
		'badge_class' => 'text-white bg-warning'
	],
	'tomorrow' => [
		'text_class' => 'text-info',
		'icon' => 'fa fa-clock',
        'badge_class' => 'text-white bg-info'
	]
];

if ($setting->grab('calendar_month_filter')){
	$cld_options['week'] = [
		'text_class' => 'text-info',
		'icon' => 'fa fa-calendar',
        'badge_class' => 'text-white bg-info'
	];
	$cld_options['month'] = [
		'text_class' => 'text-info',
		'icon' => 'fa fa-calendar',
        'badge_class' => 'text-white bg-info'
	];
}else{
	$cld_options['within-7-days'] = [
		'text_class' => 'text-info',
		'icon' => 'fa fa-calendar',
        'badge_class' => 'text-white bg-info'
	];
	$cld_options['within-14-days'] = [
		'text_class' => 'text-info',
		'icon' => 'fa fa-calendar',
        'badge_class' => 'text-white bg-info'
	];
}

$cld_options['not-scheduled'] = [
	'text_class' => 'text-secondary',
	'icon' => 'fa fa-file',
    'badge_class' => 'text-white bg-secondary'
];

?>
@foreach ($counts['calendar'] as $cld=>$count)			
	<?php $text_cld.='<a class="dropdown-item" href="'.url($setting->grab('main_route').'/filter/calendar/'.$cld).'">';

	$text_class = isset($cld_options[$cld]['text_class']) ? $cld_options[$cld]['text_class'] : "";
	$icon = isset($cld_options[$cld]['icon']) ? '<span class="'.$cld_options[$cld]['icon'].'"></span> ' : '';
    $badge_class = isset($cld_options[$cld]['badge_class']) ? $cld_options[$cld]['badge_class'] : "";

	$this_cld = '<span class="' . $text_class . '">' . $icon . trans('panichd::lang.filter-calendar-'.$cld).' <span class="badge ' . $badge_class . '">'.$count.'</span></span>';
	?>
	@if ($cld==session('panichd_filter_calendar'))
		<?php $calendar_name = $this_cld;
		$cld_class = isset($cld_options[$cld]['text_class']) ? $cld_options[$cld]['text_class'] : "";?>
	@endif
	<?php $text_cld.= $this_cld . '</a>';?>
@endforeach
<div class="dropdown" style="display: inline-block;">
<button class="btn btn-light btn-sm dropdown-toggle" type="button" data-toggle="dropdown" style="border: none;"><span class="">{!! $calendar_name=="All" ? trans('panichd::lang.filter-calendar-all') : $calendar_name !!}
<span class="caret {{ $cld_class }}"></span></button>
<ul class="dropdown-menu">
@if ($calendar_name!="All")
<a class="dropdown-item" href="{{ action('\PanicHD\PanicHD\Controllers\TicketsController@index') }}/filter/calendar/remove">{{ trans('panichd::lang.filter-calendar-all') }}</a>
@endif
{!! $text_cld !!}</ul>
</div>