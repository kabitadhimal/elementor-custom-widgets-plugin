<?php
/*
Plugin Name: Elementor Featured Post
Plugin URI: http://mehimali.com.np/
Description: Creates Featured Post Block
Author: Kabita
Version: 1.0
Author URI: http://mehimali.com.np/
*/

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

final class App_Elementor_Featured_Post {

    const VERSION = '1.0';
    const MINIMUM_ELEMENTOR_VERSION = '2.0.0';
    const MINIMUM_PHP_VERSION = '7.0';

    private static $_instance = null;

    public static function instance() {
        if ( is_null( self::$_instance ) ) {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    public function __construct() {
        add_action( 'init', [ $this, 'i18n' ] );
        add_action( 'plugins_loaded', [ $this, 'init' ] );
        add_action( 'wp_enqueue_scripts', [ $this, 'enqueue_scripts' ], 11 );
    }

    public function init() {
        // Check if Elementor installed and activated
        if ( ! did_action( 'elementor/loaded' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_missing_main_plugin' ] );
            return;
        }

        // Check for required Elementor version
        if ( ! version_compare( ELEMENTOR_VERSION, self::MINIMUM_ELEMENTOR_VERSION, '>=' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_elementor_version' ] );
            return;
        }

        // Check for required PHP version
        if ( version_compare( PHP_VERSION, self::MINIMUM_PHP_VERSION, '<' ) ) {
            add_action( 'admin_notices', [ $this, 'admin_notice_minimum_php_version' ] );
            return;
        }

        // Add Plugin actions
        add_action( 'elementor/widgets/widgets_registered', [ $this, 'init_widgets' ] );
    }

    public function i18n() {
        load_plugin_textdomain( 'app-elementor-featured-post' );
    }

    public function admin_notice_missing_main_plugin() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor */
            esc_html__( '"%1$s" requires "%2$s" to be installed and activated.', 'app-elementor-featured-post' ),
            '<strong>' . esc_html__( 'Elementor', 'app-elementor-featured-post' ) . '</strong>'
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    public function admin_notice_minimum_elementor_version() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
        /* translators: 1: Plugin name 2: Elementor 3: Required Elementor version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'app-elementor-featured-post' ),
            '<strong>' . esc_html__( 'Elementor', 'app-elementor-featured-post' ) . '</strong>',
            self::MINIMUM_ELEMENTOR_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    public function admin_notice_minimum_php_version() {
        if ( isset( $_GET['activate'] ) ) unset( $_GET['activate'] );

        $message = sprintf(
        /* translators: 1: Plugin name 2: PHP 3: Required PHP version */
            esc_html__( '"%1$s" requires "%2$s" version %3$s or greater.', 'app-elementor-featured-post' ),
            '<strong>' . esc_html__( 'PHP 7.0', 'app-elementor-featured-post' ) . '</strong>',
            self::MINIMUM_PHP_VERSION
        );

        printf( '<div class="notice notice-warning is-dismissible"><p>%1$s</p></div>', $message );
    }

    public function init_widgets() {

        // Include Widget files
        require_once( __DIR__ . '/widgets/App-featured-post-widget.php' );

        // Register widget
        \Elementor\Plugin::instance()->widgets_manager->register_widget_type( new \App_Featured_Post_Widget() );

    }

    public function enqueue_scripts() {


        wp_register_style( "app-slick-css",  plugin_dir_url( __FILE__ ).'assets/slick/slick.css', '', '1.0', false);
        wp_enqueue_style( "app-slick-css" );

        wp_register_style( "app-slick-theme-css",  plugin_dir_url( __FILE__ ).'assets/slick/slick-theme.css' , '', '1.0', false);
        wp_enqueue_style( "app-slick-theme-css" );

         wp_register_style( "app-custom-css",  plugin_dir_url( __FILE__ ).'assets/css/custom.css' , '', '1.0', false);
        wp_enqueue_style( "app-custom-css" );


        wp_register_script("app-slick-min", plugin_dir_url( __FILE__ )."assets/slick/slick.min.js", '', '1.0', true);
        wp_enqueue_script("app-slick-min");

        wp_register_script("app-main-js", plugin_dir_url( __FILE__ )."assets/js/main.js", '', '1.1', true);
        wp_enqueue_script("app-main-js");

    }
}
App_Elementor_Featured_Post::instance();