
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
	<div class="alert alert-warning">{!! trans('panichd::install.not-yet-installed') !!}</div>
	<p>{{ trans('panichd::install.installation-description') }}</p>
	<ol><li></li>
  <form class="form-signin" action="{{url('/panichd/install') }}" method="post" style="max-width: 500px">
          <input type="hidden" name="_token" value="{{ csrf_token() }}" />

        <h3 class="form-signin-heading">{{ trans('panichd::install.admin-select') }}</h3>
        <select id="admin_id" name="admin_id" class="form-control" required autofocus>
            @foreach($users_list as $id => $name)
                <option value="{{ $id }}">{{ $name }}</option>
            @endforeach
        </select>
        <span id="helpBlock" class="help-block">
            {{ trans('panichd::install.admin-select-help-block') }}
        </span>
        <br>

        <div class="well small" style="border: 1px solid #ccc">
            @if(!empty($inactive_migrations))
                <b>{{ trans('panichd::install.migrations-to-be-installed') }}</b>
                <ul>
                    @foreach($inactive_migrations as $mig)
                        <li>{{ $mig }}</li>
                    @endforeach
                </ul>
            @else
                <b>{{ trans('panichd::install.all-tables-migrated') }}</b>
            @endif
        </div>
        <br>
        <button class="btn btn-lg btn-primary btn-block" type="submit">
            {{ trans('panichd::install.proceed') }}
        </button>
    </form>

</div> <!-- /container -->

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>

<!-- IE10 viewport hack for Surface/desktop Windows 8 bug -->
<script src="http://getbootstrap.com/assets/js/ie10-viewport-bug-workaround.js"></script>
<script>
    $('#master').change(function() {
        opt = $(this).val();
        if (opt=="another") {
            $('#other-path-group').show();
        } else {
            $('#other-path-group').hide();
        }
    });
</script>
</body>
</html>
