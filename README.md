# Minifier
Combine and minify CSS and JS files with pure PHP.

## Structure
```
o
|-- minified/
|   |-- minified.css.php
|   `-- minified.js.php
|-- minifier.php
|-- CHANGELOG
`-- README.md
```

## Usage
```
include_once(/path/to/minifier.php);
```

### Style
```
$opts = array(
    'condition' => true,
    'version' => '',
    'type' => 'style',
    'path' => array(,
        'https://example.com/path/to/style.css'
    )
);

new Minifier($opts);
```

### Script   
```
$opts = array(
    'condition' => true,
    'version' => '',
    'type' => 'script',
    'path' => array(
        'https://example.com/path/to/script.js'
    )
);

new Minifier($opts);
```

## Changelog   
Please see [CHANGELOG](CHANGELOG) for more information what has changed recently.

## License
This software is licensed under the [MIT LICENSE](LICENSE)