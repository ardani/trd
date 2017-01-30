<?php
/**
 * Created by PhpStorm.
 * User: ardani
 * Date: 1/23/17
 * Time: 10:05 PM
 */

function form_value($var,$name) {
    return isset($var) ? $var : old($name);
}

function safe_array($array,$key) {
    return $array ? $array[$key] : null;
}

function alerts($type, $text) {
    $html = '
        <div class="alert alert-'.$type.' alert-no-border alert-close alert-dismissible fade in" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            '.$text.'
        </div>';
    return $html;
}