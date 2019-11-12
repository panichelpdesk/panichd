<?php

namespace PanicHD\PanicHD\Traits;

use Mews\Purifier\Facades\Purifier;
use PanicHD\PanicHD\Models\Setting;

trait Purifiable
{
    /**
     * Returns an array with both filtered and excluded html.
     *
     * @param string $rawHtml
     *
     * @return array
     */
    public function purifyHtml($rawHtml)
    {
        $a_html['content'] = trim(Purifier::clean($this->getInlineReady($this->getReplaced($rawHtml)), ['HTML.Allowed' => '']), chr(0xC2).chr(0xA0)." \t\n\r\0\x0B");
        $a_html['html'] = trim(Purifier::clean($this->getReplaced($rawHtml), Setting::grab('purifier_config')), chr(0xC2).chr(0xA0)." \t\n\r\0\x0B");

        return $a_html;
    }

    /**
     * Returns an array with both filtered and excluded html.
     *
     * @param string $rawHtml
     *
     * @return array
     */
    public function purifyInterventionHtml($rawHtml)
    {
        $a_html['intervention'] = trim(Purifier::clean($this->getInlineReady($this->getReplaced($rawHtml)), ['HTML.Allowed' => '']), chr(0xC2).chr(0xA0)." \t\n\r\0\x0B");
        $a_html['intervention_html'] = trim(Purifier::clean($this->getReplaced($rawHtml), Setting::grab('purifier_config')), chr(0xC2).chr(0xA0)." \t\n\r\0\x0B");

        return $a_html;
    }

    /**
     * Replace HTML input using html_replacements setting.
     *
     * @param string $html
     *
     * @return string
     */
    public function getReplaced($html)
    {
        return str_replace(array_keys(Setting::grab('html_replacements')), array_values(Setting::grab('html_replacements')), $html);
    }

    /**
     * Add some punctuation signs to let the text be inline readable (in ticket list).
     *
     * @param string $html
     *
     * @return string
     */
    public function getInlineReady($html)
    {
        $a_adds = [
            '</p><p>'   => '</p> <p>',
            '<ul>'      => ' ',
            '<ol>'      => ' ',
            '</li><li>' => '</li>, <li>',
            '</ul>'     => '. ',
            '</ol>'     => '. ',
        ];

        return str_replace(array_keys($a_adds), array_values($a_adds), $html);
    }
}
