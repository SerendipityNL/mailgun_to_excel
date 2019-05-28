<?php

// Mailgun:
// https://github.com/mailgun/mailgun-php
// https://github.com/mailgun/mailgun-php/blob/master/doc/index.md
// https://documentation.mailgun.com/en/latest/api_reference.html

// PHPoffice
// https://phpspreadsheet.readthedocs.io/en/latest/
//
// Tailwind:
// https://tailwindcss.com/docs/installation

error_reporting(E_ALL);

require_once 'vendor/autoload.php';

if ( ! file_exists(__DIR__.'/config.php')) {

    if (isset($_POST['API_KEY']) && isset($_POST['DOMAIN'])) {
        $config_example = __DIR__.'/config.example.php';
        $file           = fopen($config_example, 'r');
        $content        = fread($file, filesize($config_example));

        $content = str_replace(['{{API_KEY}}', '{{DOMAIN}}'], [$_POST['API_KEY'], $_POST['DOMAIN']], $content);

        $config_file = fopen(__DIR__.'/config.php', 'w') or die('Can\'t write to file...');
        fwrite($config_file, $content);
        fclose($config_file);

        header('Location: '.$_SERVER['REQUEST_URI']);
    } else {
        require_once 'install.php';
    }

    exit();
}

require_once 'config.php';
require_once 'common.php';

require_once 'output.php';
