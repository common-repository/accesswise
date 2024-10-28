<?php

namespace Engramium\Accesswise;

// If this file is called directly, abort.
defined('ABSPATH') || exit;

/**
 * Plugin initiator class
 *
 * @author sayedulsayem
 * @since 1.0.0
 */
class Accesswise {
    use \Engramium\Accesswise\Traits\Singleton;

    /**
     * initialization function
     *
     * @return void
     * @since 1.0.0
     */
    public function init() {
        $this->define_constants();

        register_activation_hook(ACCESSWISE_PATH . 'accesswise.php', [$this, 'activate']);
        register_deactivation_hook(ACCESSWISE_PATH . 'accesswise.php', [$this, 'deactivate']);
        register_uninstall_hook(ACCESSWISE_PATH . 'accesswise.php', [__CLASS__, 'uninstall']);

        add_action('plugins_loaded', array($this, 'init_plugin'));
    }

    /**
     * define plugin constants function
     *
     * @return void
     * @since 1.0.0
     */
    public function define_constants() {
        define('ACCESSWISE_VERSION', defined('ACCESSWISE_DEV') ? time() : '1.0.0');
        define('ACCESSWISE_PATH', \plugin_dir_path(__DIR__));
        define('ACCESSWISE_URL',  \plugin_dir_url(__DIR__));
    }

    /**
     * plugins loaded hook init function
     *
     * @return void
     * @since 1.0.0
     */
    public function init_plugin() {
        do_action('accesswise/before_plugin_load');
        $this->includes();
        $this->init_hooks();
        do_action('accesswise/after_plugin_load');
    }

    /**
     * includes plugin base files function
     *
     * @return void
     * @since 1.0.0
     */
    public function includes() {
        Dashboard\Base::instance()->init();
        App\Base::instance()->init();
    }

    /**
     * init hook function
     *
     * @return void
     * @since 1.0.0
     */
    public function init_hooks() {
        add_action('init', array($this, 'localization_setup'));
        add_filter('plugin_action_links_accesswise/accesswise.php', [$this, 'plugin_action_link_modify']);
    }

    /**
     * localize plugin text domain for translation function
     *
     * @return void
     * @since 1.0.0
     */
    public function localization_setup() {
        load_plugin_textdomain('accesswise', false, ACCESSWISE_PATH . 'i18n/');
    }

    /**
     * modify plugin action links function
     *
     * @param array $links
     *
     * @return array
     * @since 1.0.0
     */
    public function plugin_action_link_modify($links) {
        if (current_user_can('manage_options')) {
            $settings  = '<a href="' . admin_url('admin.php?page=accesswise-settings') . '">';
            $settings .= esc_html__('Settings', 'accesswise');
            $settings .= '</a>';

            array_unshift($links, $settings);
        }

        return $links;
    }

    /**
     * Plugin activation hook function
     *
     * @return void
     * @since 1.0.0
     */
    public function activate() {
        $installed = get_option('accesswise_installed');

        if (!$installed) {
            update_option('accesswise_installed', time());
        }

        update_option('accesswise_version', ACCESSWISE_VERSION);
    }

    /**
     * plugin deactivation hook function
     *
     * @return void
     * @since 1.0.0
     */
    public function deactivate() {
        // do something on deactivation
    }

    /**
     * plugin uninstall hook function
     *
     * @return void
     * @since 1.0.0
     */
    public static function uninstall() {
        // clear database
    }
}
