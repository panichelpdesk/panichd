
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PanicHD</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="container-fluid">
    
	<div class="page-header">
		<h1>{{ trans('panichd::install.main-title') }}</h1>
	</div>
	<div class="text-warning" style="padding: 1em 0em 2em 0em;"><span class="glyphicon glyphicon-alert" style="font-size: 1.5em; padding: 0em 0.5em 0em 0em;"></span>{!! trans('panichd::install.not-yet-installed') !!}</div>
	
	<div class="panel panel-default">
		<div class="panel-body">
			<h3>{{ trans('panichd::install.initial-setup') }}</h3>
			<p>{!! trans('panichd::install.installation-description') !!}</p>
			<ol>
			<li>{!! trans('panichd::install.setup-list-migrations', ['num' =>count($inactive_migrations)]) !!} <a href="#" id="show_migrations">{{ trans('panichd::install.setup-migrations-more-info') }}</a><a href="#" id="hide_migrations" style="display: none">{{ trans('panichd::install.setup-migrations-less-info') }}</a></li>
			<ul id="migrations_list" style="display: none; margin: 0em 0em 1em 0em;">
				@foreach($inactive_migrations as $mig)
					<li>{{ $mig }}</li>
				@endforeach
			</ul>
			<li>{{ trans('panichd::install.setup-list-settings') }}</li>
			<li>{!! trans('panichd::install.setup-list-admin', ['name' => auth()->user()->name, 'email' => auth()->user()->email]) !!}</li>
			<li>{!! trans('panichd::install.setup-list-public-assets') !!}
			</ol>
			<form class="form-signin" action="{{url('/panichd/install') }}" method="post" style="margin-top: 2em;">
			{{ csrf_field() }}
			<h3>{{ trans('panichd::install.optional-config') }}</h3>
			<label style="font-weight: normal;"><input type="checkbox" name="quickstart"> {!! trans('panichd::install.optional-quickstart-data') !!}</label>
			<p><button class="btn btn-lg btn-primary" type="submit">
				{{ trans('panichd::install.install-now') }}
			</button></p>
			</form>
		</div>
	</div>
</div> <!-- /container -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="http://getbootstrap.com/assets/js/ie10-viewport-bug-workaround.js"></script>
<script>
$(function(){
	$('#show_migrations').click(function(e){
		e.preventDefault();
		$('#migrations_list').slideDown();
		$(this).hide();
		$('#hide_migrations').show();
	});
	$('#hide_migrations').click(function(e){
		e.preventDefault();
		$('#migrations_list').slideUp();
		$(this).hide();
		$('#show_migrations').show();
	});
	
	
	
});
</script>
</body>
</html>
