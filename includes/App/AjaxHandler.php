<?php

namespace Engramium\Accesswise\App;

use Engramium\Accesswise\Dashboard\Settings;

// If this file is called directly, abort.
defined('ABSPATH') || exit;

/**
 * Ajax Handler class
 *
 * @author sayedulsayem
 * @since 1.0.0
 */
class AjaxHandler {

    use \Engramium\Accesswise\Traits\Singleton;

    /**
     * initialization function
     *
     * @return void
     * @since 1.0.0
     */
    public function init() {
        add_action('wp_ajax_accesswise_update_settings', [$this, 'update_settings']);
        add_action('wp_ajax_accesswise_get_settings', [$this, 'get_settings']);
    }

    public function check_nonce() {
        $status = check_ajax_referer('accesswise_nonce', 'nonce');
        if (!$status) {
            wp_send_json([
                'status' => true,
                'msg' => 'Unauthorized Request',
                'data' => [],
            ]);
        }
    }

    public function update_settings() {
        $this->check_nonce();
        $request = $_REQUEST;
        unset($request['action']);
        unset($request['nonce']);
        $status = Settings::instance()->update_settings($request);
        $msg = $status ? 'Settings updated.' : 'Settings nothing to update/ failed.';
        wp_send_json([
            'status' => $status,
            'msg' => $msg,
            'data' => $request,
        ]);
    }

    public function get_settings() {
        $this->check_nonce();
        wp_send_json([
            'status' => true,
            'msg' => 'Settings get.',
            'data' => Settings::instance()->get_settings(),
        ]);
    }
}
