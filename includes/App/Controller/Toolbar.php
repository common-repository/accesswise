<?php

namespace Engramium\Accesswise\App\Controller;

// If this file is called directly, abort.
defined( 'ABSPATH' ) || exit;

/**
 * Application base class
 *
 * @author sayedulsayem
 *
 * @since 1.0.0
 */
class Toolbar {

    use \Engramium\Accesswise\Traits\Singleton;

    private $general_settings;

    /**
     * initialization function
     *
     * @since 1.0.0
     *
     * @return void
     */
    public function init() {
        $this->general_settings = Base::instance()->settings['generals'];
        add_action( 'after_setup_theme', [$this, 'hide_admin_bar'], PHP_INT_MAX );
        add_action( 'admin_bar_menu', [$this, 'customize_admin_bar_for_public'], PHP_INT_MAX );
    }

    public function hide_admin_bar() {
        if ( empty( $this->general_settings['toolbar'] ) ) {
            add_filter( 'show_admin_bar', '__return_false' );
        } else {
            if ( is_array( $this->general_settings['toolbar'] ) ) {
                if ( current_user_can( 'administrator' ) && ! in_array( 'show_for_admins', $this->general_settings['toolbar'] ) ) {
                    add_filter( 'show_admin_bar', '__return_false' );
                }
                if ( ! current_user_can( 'administrator' ) && ! in_array( 'show_for_non_admins', $this->general_settings['toolbar'] ) ) {
                    add_filter( 'show_admin_bar', '__return_false' );
                }
                if ( ! is_user_logged_in() && in_array( 'show_for_public', $this->general_settings['toolbar'] ) ) {
                    add_filter( 'show_admin_bar', '__return_true' );
                }
            }
        }
    }

    public function customize_admin_bar_for_public( $wp_admin_bar ) {
        if ( ! is_user_logged_in() ) {
            $wp_admin_bar->add_node( [
                'id'    => 'wp-admin-bar-login',
                'title' => esc_html__( 'Log In', 'accesswise' ),
                'href'  => wp_login_url()
            ] );
            if ( get_option( 'users_can_register' ) ) {
                $wp_admin_bar->add_node( [
                    'id'    => 'wp-admin-bar-register',
                    'title' => esc_html__( 'Register', 'accesswise' ),
                    'href'  => wp_registration_url()
                ] );
            }
        }
    }
}
