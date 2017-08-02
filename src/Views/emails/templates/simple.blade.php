<?php $notification_owner = unserialize($notification_owner);?>
<?php $ticket = unserialize($ticket);?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
  <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style type="text/css">
      @import url(http://fonts.googleapis.com/css?family=Droid+Sans);

    body {
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
{!! trans('ticketit::email/simple.salutation') !!}
<?php	
	$dom = new DomDocument();
	$dom->loadHtml( mb_convert_encoding($ticket->html, 'HTML-ENTITIES', "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

	$images = $dom->getElementsByTagName('img');	
	
	// foreach <img> in the email html
	$i = 0;
	foreach($images as $img){
		$src = $img->getAttribute('src');
		
		// if the img source is 'data-url'
		if(preg_match('/data:image\/png;base64,/', $src)){
			
			$src = str_replace('data:image/png;base64,', '', $src);			
			$img->removeAttribute('src');
			$img->setAttribute('src', $message->embedData(base64_decode($src), "embed".$i.".png"));
					
			$i++;
		}
	}
	
	echo $dom->saveHTML();
		
?>

{!! trans('ticketit::email/simple.closing') !!}
{{ $setting->grab('email.signature') }}
</body>
</html>