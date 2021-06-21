<?php 
/**
 * Cartax Combined & Minified CSS and JS files
 */
class Optimizer {

    public $_opts;

    public function __construct($opts=array()){
        $this->_opts = $opts;
        $this->init();
    }

    public function options() {

        $options = $this->_opts;

        $defaults = array(
            'condition' => true, // (boolean)
            'version' => '',     // '' is auto update version daily, false is remove version parameter and string is update version manual
            'type' => 'style',   // (string) style or script
            'path' => array()
        );

        $settings = ( is_array($options) ) ? array_replace_recursive($defaults, $options) : $defaults;

        return $settings;
    }

    public function init() {

        $opts = $this->options();
        $uri = $this->get_minified_uri();
        $file = implode(',', $opts['path']);

        switch ($opts['type']) {
            case 'style':
                $this->view_style($opts, $uri, $file);
                break;
            case 'script':
                $this->view_script($opts, $uri, $file);
                break;
        }

    }

    public function view_style($opts, $uri, $file) {
        $html = "";
        if ($opts['condition']) {
            $html .= "<link ";
            $html .= "rel=\"stylesheet\" ";
            $html .= "href=\"{$uri}/minified.css.php";
            $html .= "?minified={$file}";
            $html .= $this->view_version($opts['version'], '&');
            $html .= "\" />\n";
        } 
        else {
            $count = 0;
            foreach ($opts['path'] as $path) {
                if ( $count == 0 ) {
                    $html .= "<link ";
                    $html .= "rel=\"stylesheet\" ";
                    $html .= "href=\"{$path}";
                    $html .= $this->view_version($opts['version'], '?');
                    $html .= "\" />\n";
                } else {
                    $html .= "\t<link ";
                    $html .= "rel=\"stylesheet\" ";
                    $html .= "href=\"{$path}";
                    $html .= $this->view_version($opts['version'], '?');
                    $html .= "\" />\n";
                }
                $count++;
            }
        }
        echo $html;
    }

    public function view_script($opts, $uri, $file) {
        $html = "";
        if ($opts['condition']) {
            $html .= "<script ";
            $html .= "src=\"{$uri}/minified.js.php";
            $html .= "?minified={$file}";
            $html .= $this->view_version($opts['version'], '&');
            $html .= "\"></script>\n";
        }
        else {
            $count = 0;
            foreach ($opts['path'] as $path) {
                if ( $count == 0 ) {
                    $html .= "<script ";
                    $html .= "src=\"{$path}";
                    $html .= $this->view_version($opts['version'], '?');
                    $html .= "\"></script>\n";
                } else {
                    $html .= "\t<script ";
                    $html .= "src=\"{$path}";
                    $html .= $this->view_version($opts['version'], '?');
                    $html .= "\"></script>\n";
                }
                $count++;
            }
        }
        echo $html;
    }

    public function view_version($version='', $delimiter='') {
        if ( $version === false ) { return; }
        return empty($version) ? "{$delimiter}ver=". date('Ymd', filemtime(__FILE__)) ."" : "{$delimiter}ver={$version}";
    }

    public function get_minified_uri() {
        return str_replace($this->get_root(), $this->get_host(), __DIR__).'/minified';
    }

    public function get_minified_dir() {
        return __DIR__.'/minified';
    }

    public function get_host() {
        return (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https' : 'http') .'://'. $_SERVER['HTTP_HOST'];
    }

    public function get_root() {
        return rtrim($_SERVER['DOCUMENT_ROOT'], '/');
    }

}