<?php

namespace Engramium\Accesswise\App\Controller;

use Engramium\Accesswise\Dashboard\Settings;

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 * Application base class
 *
 * @author sayedulsayem
 *
 * @since 1.0.0
 */
class Base {

    use \Engramium\Accesswise\Traits\Singleton;

    public $settings;

    /**
     * initialization function
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function init() {
        $this->settings = Settings::instance()->get_settings();
        LastLogin::instance()->init();
        Redirection::instance()->init();
        Toolbar::instance()->init();
        Protection::instance()->init();
    }
}
