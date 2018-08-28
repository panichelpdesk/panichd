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
    
	<div class="page-header" style="margin: 20px 0px 10px 0px;">
		<div class="row">
			<div class="col-md-6 col-sm-5"><h1>{{ trans('panichd::install.main-title') }}</h1></div>
			<div class="col-md-6 col-sm-7"><div class="pull-right" style="margin: 2em 0em 0em 0em;">@yield('current_status')</div></div>
		</div>
		
	</div>
	
	
	<div class="card bg-light">
		<div class="card-body">
			@yield('content')
			
		
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
	$('.slide_button').click(function(e){
		e.preventDefault();
		var list = $('#'+$(this).data('slide'));
		if (list.is(':visible')){
			list.slideUp();
			$(this).text($(this).data('off-text'));
		}else{
			list.slideDown();
			$(this).text($(this).data('on-text'));
		}
	});
	
	$('.btn-primary').click(function(){
		$(this).prop('disabled', true);
		
		$(this).closest('form').submit();
	});
	
});
</script>
</body>
</html>