
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Ticketit Upgrade</title>

    <!-- Latest compiled and minified CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!-- Custom styles for this template -->
    <link href="http://getbootstrap.com/examples/signin/signin.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>

<body>

<div class="container">
    <h1 style="text-align: center">{{ trans('panichd::install.upgrade') }}</h1>
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
    <div class="well small" style="border: 1px solid #ccc">
        @if(!empty($inactive_settings))
            <b>{{ trans('panichd::install.settings-to-be-installed') }}</b>
            <ul>
                @foreach($inactive_settings as $slug => $value)
                    <li>{{ $slug }} => {!! is_array($value) ? print_r($value) : $value !!}</li>
                @endforeach
            </ul>
        @else
            <b>{{ trans('panichd::install.all-settings-installed') }}</b>
        @endif
    </div>
    <br>
    <a href="/tickets-upgrade" class="btn btn-lg btn-primary btn-block" type="submit">
        {{ trans('panichd::install.proceed') }}
    </a>

</div> <!-- /container -->
</body>
</html>
