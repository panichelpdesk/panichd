<?php	
	$dom = new DomDocument();
	$dom->loadHtml( mb_convert_encoding($html_field, 'HTML-ENTITIES', "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD);

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