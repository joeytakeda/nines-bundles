<?php

/**
 * @file
 *
 * Text processing serivce.
 */

namespace Nines\UtilBundle\Services;

use Monolog\Logger;
use Psr\Log\LoggerInterface;

/**
 * Various text mangling functions for twig and for other symfony stuff.
 */
class Text {

    /**
     * Monolog logger.
     *
     * @var Logger
     */
    private $logger;

    private $defaultTrimLenth;

    public function __construct($defaultTrimLength, LoggerInterface $logger) {
        $this->defaultTrimLenth = $defaultTrimLength;
        $this->logger = $logger;
    }

    /**
     * Build a plain, searchable version of a marked up text.
     */
    public function plain($content) {
        $plain = strip_tags($content);
        $converted = html_entity_decode($plain, ENT_HTML5, 'UTF-8');
        $trimmed = preg_replace("/(^\s+)|(\s+$)/u", "", $converted);
        // \xA0 is the result of converting nbsp.
        $normalized = preg_replace("/[[:space:]\x{A0}]/su", " ", $trimmed);
        return $normalized;
    }

    /**
     * Find the keyword in the plain text and highlight it. Returns a list
     * of the higlights as KWIC results.
     *
     * @param string $content
     * @param string $keyword
     * @return array
     */
    public function searchHighlight($content, $keyword) {
        $text = $this->plain($content);
        $i = stripos($text, $keyword);
        $regex = preg_quote($keyword);
        $results = array();
        while($i !== false) {
            $s = substr($text, max([0, $i - 60]), 120);
            $results[] = preg_replace("/($regex)/i", '<mark>$1</mark>', $s);
            $i = stripos($text, $keyword, $i+1);
        }
        return array_unique($results);
    }

    /**
     * Create a URL-friendly slug from a string.
     *
     * Drops leading/trailing spaces, transliterates digraphs, lowercases,
     * and replaces non letter/digit characters to the separator. Periods at
     * the end of the string are removed.
     *
     * @param string $string
     * @param string  $separator
     * @return string
     */
    public function slug($string, $separator = '-') {
        // trim spaces and periods.
        $s = preg_replace('/(^[\s.]*)|([\s.]*$)/u', '', $string);

        // transliterate digraphs and accents
        $s = iconv('utf-8', 'us-ascii//TRANSLIT', $s);

        // lowercase
        $s = mb_convert_case($s, MB_CASE_LOWER, 'UTF-8');

        // strip non letter/digit/space chars
        $s = preg_replace('/[^-_a-z0-9 ]/u', '', $s);

        // transform spaces and runs of separators to separator.
        $quoted = preg_quote($separator, '/');
        $s = preg_replace("/(\s|$quoted)+/u", $separator, $s);

        return $s;
    }

    /**
     * Strip tags from HTML and then trim it to a number of words.
     *
     * @param string $string
     * @param string $length
     * @param string $suffix
     * @return string
     */
    public function trim($string, $length = null, $suffix = '...') {
        if($length === null) {
            $length = $this->defaultTrimLenth;
        }
        $plain = strip_tags($string);
        $converted = html_entity_decode($plain, ENT_COMPAT | ENT_HTML401, 'UTF-8');
        $trimmed = preg_replace("/(^\s+)|(\s+$)/u", "", $converted);
        // \xA0 is the result of converting nbsp. Requires the /u flag.
        $normalized = preg_replace("/[[:space:]\x{A0}]/su", " ", $trimmed);
        $words = preg_split('/\s+/u', $normalized, $length+1, PREG_SPLIT_NO_EMPTY);

        if(count($words) <= $length) {
            return implode(' ', $words);
        }
        return implode(' ', array_slice($words, 0, $length)) . $suffix;
    }

}
