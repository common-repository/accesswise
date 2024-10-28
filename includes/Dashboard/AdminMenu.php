<?php

namespace Engramium\Accesswise\Dashboard;

// If this file is called directly, abort.
defined('ABSPATH') || exit;

/**
 * Add dashboard menu class
 *
 * @author sayedulsayem
 * @since 1.0.0
 */
class AdminMenu {

    use \Engramium\Accesswise\Traits\Singleton;

    private $slug = 'accesswise-settings';

    /**
     * initialization function
     *
     * @return void
     * @since 1.0.0
     */
    public function init() {
        add_action('admin_menu', [$this, 'admin_menu']);
        add_action('in_admin_header', [$this, 'remove_all_notices'], PHP_INT_MAX);
    }

    /**
     * add admin menu function
     *
     * @return void
     * @since 1.0.0
     */
    public function admin_menu() {
        global $submenu;

        $capability = 'manage_options';

        $hook = add_menu_page(
            __('AccessWise', 'accesswise'),
            __('AccessWise', 'accesswise'),
            $capability,
            $this->slug,
            [$this, 'render_page'],
            'data:image/svg+xml;base64,' . base64_encode( file_get_contents( ACCESSWISE_PATH . 'public/icons/menu-icon.svg' ) ),
            30
        );

        if (current_user_can($capability)) {
            $submenu[$this->slug][] = array(__('Welcome', 'accesswise'), $capability, 'admin.php?page=' . $this->slug . '#/');
            $submenu[$this->slug][] = array(__('Settings', 'accesswise'), $capability, 'admin.php?page=' . $this->slug . '#/settings');
        }
    }

    /**
     * render html in menu function
     *
     * @return void
     * @since 1.0.0
     */
    public function render_page() {
        echo '<div class="wrap"><div id="accesswise-dashboard-app"></div></div>';
    }

    /**
     * remove all notices from menu function
     *
     * @return void
     * @since 1.0.0
     */
    public function remove_all_notices() {
        if (!$this->is_page()) return;
        remove_all_actions('admin_notices');
        remove_all_actions('all_admin_notices');
        remove_all_actions('user_admin_notices');
    }

    /**
     * is menu page check function
     *
     * @return boolean
     * @since 1.0.0
     */
    public function is_page() {
        return (isset($_GET['page']) && (sanitize_text_field($_GET['page']) === $this->slug));
    }
}
