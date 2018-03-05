<?php 
	$notification_owner = unserialize($notification_owner);
	$ticket = unserialize($ticket);
	$email_from = unserialize($email_from);
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style type="text/css">
      @import url(http://fonts.googleapis.com/css?family=Droid+Sans);

    body {
        font-family: sans-serif;
		-webkit-font-smoothing:antialiased;
        -webkit-text-size-adjust:none;
        width: 100%;
        height: 100%;        
        background: #ffffff;
        font-size: 16px;
    }
</style>

<style type="text/css" media="screen">
  @media screen {
    td, h1, h2, h3 {
      font-family: 'Droid Sans', 'Helvetica Neue', 'Arial', 'sans-serif' !important;
  }
}
</style>

<style type="text/css" media="only screen and (max-width: 480px)">
    @media only screen and (max-width: 480px) {

      table[class="w320"] {
        width: 320px !important;
    }
}
</style>
</head>
<body class="body" style="padding:0; margin:0; display:block; background:#fff; -webkit-text-size-adjust:none" bgcolor="#fff">
<p>{{ trans('panichd::email/globals.salutation') }}</p>

@include('panichd::emails.partial.html_field', ['html_field' => $ticket->html])

<p>{{ trans('panichd::email/globals.complimentary_close') }}</p>  
<p><b>{{ $email_from->email_name }}</b></p>
<p>{{ $setting->grab('email.footer') }}</p>
</body>
</html>