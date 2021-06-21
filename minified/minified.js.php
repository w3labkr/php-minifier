<?php 
/**
 * Combine & compress JS files with PHP
 *
 * @link https://wp-mix.com/combine-compress-css-files-php/
 * @link https://www.progclub.org/blog/2012/01/10/compressing-javascript-in-php-no-comments-or-whitespace/
 */

header('Content-type: text/javascript');
ob_start("compress");

function compress($buffer) {

    // JavaScript compressor by John Elliot <jj5@jj5.net>
    $replace = array(
        '#\'([^\n\']*?)/\*([^\n\']*)\'#' => "'\1/'+\'\'+'*\2'", // remove comments from ' strings
        '#\"([^\n\"]*?)/\*([^\n\"]*)\"#' => '"\1/"+\'\'+"*\2"', // remove comments from " strings
        '#/\*.*?\*/#s'            => "",      // strip C style comments
        '#[\r\n]+#'               => "\n",    // remove blank lines and \r's
        '#\n([ \t]*//.*?\n)*#s'   => "\n",    // strip line comments (whole line only)
        '#([^\\])//([^\'"\n]*)\n#s' => "\\1\n",
                                              // strip line comments
                                              // (that aren't possibly in strings or regex's)
        '#\n\s+#'                 => "\n",    // strip excess whitespace
        '#\s+\n#'                 => "\n",    // strip excess whitespace
        '#(//[^\n]*\n)#s'         => "\\1\n", // extra line feed after any comments left
                                              // (important given later replacements)
        '#/([\'"])\+\'\'\+([\'"])\*#' => "/*" // restore comments in strings
    );

    $search = array_keys($replace);
    $script = preg_replace($search, $replace, $buffer);

    $replace = array(
        "&&\n" => "&&",
        "||\n" => "||",
        "(\n"  => "(",
        ")\n"  => ")",
        "[\n"  => "[",
        "]\n"  => "]",
        "+\n"  => "+",
        ",\n"  => ",",
        "?\n"  => "?",
        ":\n"  => ":",
        ";\n"  => ";",
        "{\n"  => "{",
        // "}\n"  => "}", //(because I forget to put semicolons after function assignments)
        "\n]"  => "]",
        "\n)"  => ")",
        "\n}"  => "}",
        "\n\n" => "\n"
    );

    $search = array_keys($replace);
    $script = str_replace($search, $replace, $script);
    $buffer = trim($script);

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