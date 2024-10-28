<?php

namespace Engramium\Accesswise\App;

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 * Application base class
 *
 * @author sayedulsayem
 * @since 1.0.0
 */
class Base {

    use \Engramium\Accesswise\Traits\Singleton;

    /**
     * initialization function
     *
     * @return void
     * @since 1.0.0
     */
    public function init() {
        Controller\Base::instance()->init();
        RegisterAssets::instance()->init();
        AjaxHandler::instance()->init();
    }
}