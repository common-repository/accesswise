<?php

namespace Engramium\Accesswise\Dashboard;

// If this file is called directly, abort.
defined('ABSPATH') || exit;

/**
 * Dashboard base class
 *
 * @author sayedulsayem
 * @since 1.0.0
 */
class Settings {

    use \Engramium\Accesswise\Traits\Singleton;

    public $settings_key = "accesswise_settings";

    public function get_settings() {
        $defaults = $this->get_default_settings();
        $quick_toggles = get_option($this->settings_key, []);

        if (empty($quick_toggles)) {
            return $defaults;
        }

        return $quick_toggles;
    }

    public function update_settings($data) {
        $data = $this->sanitize_inputs($data);
        $quick_toggles = update_option($this->settings_key, $data, true);
        return $quick_toggles;
    }

    public function sanitize_inputs($inputs) {
        foreach ($inputs as $key => &$value) {
            if (is_array($value) || is_object($value)) {
                $value = $this->sanitize_inputs($value);
            } else {
                $value = sanitize_text_field($value);
            }
        }
        return $inputs;
    }

    public function get_default_settings() {
        return [
            'generals' => [
                'toolbar' => ['show_for_admins', 'show_for_non_admins'],
                'redirection_after_login' => 'default',
                'redirection_after_logout' => 'default',
                'private_website' => [],
                'when_last_login' => [],
                'right_click' => [],
            ],
        ];
    }
}
