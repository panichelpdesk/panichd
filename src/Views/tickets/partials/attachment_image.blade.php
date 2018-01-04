<?php 
	$size = $attachment->bytes/1024;
	$size = $size < 1024 ? number_format($size)." KB" : number_format($size/1024, 1)." MB";
?>
<a href="{{ URL::route($setting->grab('main_route').'.view-attachment', [$attachment->id]) }}" title="{{ $attachment->new_filename . ($attachment->description == "" ? ' - '.$size : trans('panichd::lang.colon') . $attachment->description) }}" class="tooltip-show pwsp_gallery_link" data-pwsp-pid="{{ $attachment->id }}" data-toggle="tooltip" data-placement="auto top">
<div class="panel panel-default" style="display: inline-block; width: 70px; height: 70px; margin: 5px">
	<div class="panel-body">
		@if (\File::exists(storage_path('app'.DIRECTORY_SEPARATOR.'public'.DIRECTORY_SEPARATOR.$setting->grab('thumbnails_path').DIRECTORY_SEPARATOR).basename($attachment->file_path)))
			<img src="{{ URL::to('/').'/storage/'.$setting->grab('thumbnails_path').'/'.basename($attachment->file_path) }}" class="ximg-responsive">
		@else
			<i class="fa fa-file-image-o fa-2x" aria-hidden="true"></i>
		@endif
	</div>
</div>
</a>
