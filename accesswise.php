<?php
/*
Plugin Name: AccessWise
Plugin URI: https://wordpress.org/plugins/accesswise/
Description: Power to restrict your website and it's content
Version: 1.0.0
Author: Engramium
Author URI: https://engramium.com
License: GPL-3.0-or-later
License URI: https://www.gnu.org/licenses/gpl-3.0.html
Text Domain: accesswise
Domain Path: /i18n
*/

namespace Engramium\Accesswise;

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;


if (!class_exists(Accesswise::class) && is_readable(__DIR__ . '/vendor/autoload.php')) {
    /** @noinspection PhpIncludeInspection */
    require_once __DIR__ . '/vendor/autoload.php';
}

class_exists(Accesswise::class) && Accesswise::instance()->init();
