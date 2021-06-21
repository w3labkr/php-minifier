<?php
/**
 * Combine & compress CSS files with PHP
 * 
 * @link https://wp-mix.com/combine-compress-css-files-php/
 * @link https://gist.github.com/brokyzz/469f896cde74ed0248b2
 */

header('Content-type: text/css');
ob_start("compress");

function compress($buffer) {
    /* Remove Comments */
    $buffer = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $buffer);
    /* Remove tabs, spaces, newlines, etc. */
    $buffer = str_replace(array("\r\n", "\r", "\n", "\t", '  ', '    ', '    '), '', $buffer);
    /* remove other spaces before/after ) */
    $buffer = preg_replace(array('(( )+\))','(\)( )+)'), ')', $buffer);
    return $buffer;
}

/* Set path */
$HOME_URI = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') .'://'. $_SERVER['HTTP_HOST'];
$HOME_DIR = rtrim($_SERVER['DOCUMENT_ROOT'], '/');

/* files for combining */
$files = explode(',', $_GET['minified']);
foreach ($files as $file) {
    $file = str_replace($HOME_URI, $HOME_DIR, $file);
    include($file);
}

ob_end_flush();