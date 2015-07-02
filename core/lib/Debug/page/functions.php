<?php

/**
 * Created by Prowect
 * Author: Raffael Kessler
 * Date: 31.01.15 - 17:24.
 */

/**
 * returns code snippet from file $path on line $line
 * it will return html string.
 *
 * @param $path
 * @param $line
 *
 * @return string
 */
function getCode($path, $line)
{
    $snippet = 7;
    $code = str_replace('>', '&gt', str_replace('<', '&lt;', file_get_contents($path)));
    $codesplit = explode("\n", $code);
    $lines = count($codesplit);
    $from = $line < $snippet ? 0 : $line - $snippet;
    $to = $lines < $line + $snippet ? $lines + 1 : $line + $snippet;
    $new = array();
    for ($i = $from; $i < $to - 1; $i++) {
        if ($i + 1 == $line) {
            $new[] = "<span class='highlight'>".($i + 1).' '.$codesplit[$i].'</span>';
        } else {
            $new[] = ($i + 1).' '.$codesplit[$i];
        }
    }

    return '<pre><code>'.implode("\n", $new)."\n</pre></code>";
}
