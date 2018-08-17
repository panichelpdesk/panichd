<?php
    $dom = new \DomDocument();

    if ($dom->loadHtml( mb_convert_encoding('<div>' . $html_field . '</div>', 'HTML-ENTITIES', "UTF-8"), LIBXML_HTML_NOIMPLIED | LIBXML_HTML_NODEFDTD)){

        $container = $dom->getElementsByTagName('div')->item(0);
        $container = $container->parentNode->removeChild($container);
        while ($dom->firstChild) {
            $dom->removeChild($dom->firstChild);
        }

        while ($container->firstChild ) {
            $dom->appendChild($container->firstChild);
        }

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

    }else{
        \Log::warning('HMTL could not be processed for ' . $field . ' field in ticket #' . $ticket->id);
        echo $html_field;
    }
?>