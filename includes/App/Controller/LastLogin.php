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
class LastLogin {

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
        if ( isset( $this->general_settings['when_last_login'] ) && is_array( $this->general_settings['when_last_login'] ) && in_array( 'show_last_login', $this->general_settings['when_last_login'] ) ) {
            add_action( 'wp_login', [$this, 'track_last_login'], 10, 2 );
            add_filter( 'manage_users_columns', [$this, 'add_last_login_column'] );
            add_action( 'manage_users_custom_column', [$this, 'add_last_login_column_data'], 10, 3 );
            add_filter( 'manage_users_sortable_columns', [$this, 'make_last_login_column_sortable'] );
            add_action( 'pre_get_users', [$this, 'sort_by_last_login'] );
        }
    }

    public function track_last_login( $login, $user ) {
        update_user_meta( $user->ID, 'aw_last_login', current_time( 'mysql' ) );
    }

    public function add_last_login_column( $columns ) {
        $columns['aw_last_login'] = esc_html__( 'Last Login', 'accesswise' );

        return $columns;
    }

    public function add_last_login_column_data( $value, $column_name, $user_id ) {
        if ( 'aw_last_login' == $column_name ) {
            $last_login = get_user_meta( $user_id, 'aw_last_login', true );
            if ( $last_login ) {
                $date_format = get_option( 'date_format' );
                $time_format = get_option( 'time_format' );

                return date_i18n( $date_format . ' ' . $time_format, strtotime( $last_login ) );
            } else {
                return 'Never';
            }
        }

        return $value;
    }

    public function make_last_login_column_sortable( $columns ) {
        $columns['aw_last_login'] = 'aw_last_login';

        return $columns;
    }

    public function sort_by_last_login( $query ) {
        if ( ! is_admin() ) {
            return;
        }

        $orderby = $query->get( 'orderby' );
        if ( 'aw_last_login' == $orderby ) {
            $query->set( 'meta_key', 'aw_last_login' );
            $query->set( 'orderby', 'meta_value' );
        }
    }

}
