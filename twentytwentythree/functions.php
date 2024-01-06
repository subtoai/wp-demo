<?php

defined('ABSPATH') or die('oops. not working');



if (file_exists(dirname(__FILE__) . '/vendor/autoload.php')) {
    require_once dirname(__FILE__) . '/vendor/autoload.php';
}

require get_template_directory() . '/inc/theme-support.php';

if (class_exists('Import\\Init')) {
    Import\Init::register_services();
}
