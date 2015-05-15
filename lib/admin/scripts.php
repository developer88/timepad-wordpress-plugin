<?php
if ( ! defined( 'ABSPATH' ) ) exit;
/**
 * Scripts Admin Class
 *
 * Class includes TimePad admin scripts
 *
 * @class       TimepadEvents_Admin_Scripts
 * @version     1.0.0
 * @package     TimePad/Admin
 * @author      TimePad Team
 * @extends     TimepadEvents_Admin_Base
 * @category    Admin Class
 */
if ( ! class_exists( 'TimepadEvents_Admin_Scripts' ) ) :
    
    class TimepadEvents_Admin_Scripts extends TimepadEvents_Admin_Base {
        
        /**
         * This @var is array of WordPress core scripts handlers 
         * that need to be included in TimePad admin screen
         * 
         * @access private
         * @var array
         */
        private static $_jsHandlesInsidePluginAdmin = array(
            'jquery'
        );
        
        public function __construct() {
            parent::__construct();
        }
        
        /**
         * Init function that calls at TimepadEvents_Setup_Admin class
         * 
         * @access public
         * @return void
         */
        public function init() {
            add_action( 'admin_enqueue_scripts', array( $this, 'admin_init_scripts' ), 9999 );
        }
        
        /**
         * Function for admin_enqueue_scripts WordPress core hook 
         * to include TimePad Admin JS scripts
         * 
         * @access public
         * @param type $hook
         */
        public function admin_init_scripts( $hook ) {
            foreach ( self::$_jsHandlesInsidePluginAdmin as $handle ) {
                if ( $handle == 'media-upload' ) {
                    if ( function_exists( 'wp_enqueue_media' ) ) {
                        wp_enqueue_media();
                    }
                }
                wp_enqueue_script( $handle );
            }

            wp_register_script( 'timepad-jquery-cookie', plugins_url( 'assets/js/admin/jquery.cookie.js', TIMEPADEVENTS_FILE ), self::$_jsHandlesInsidePluginAdmin, null/*, true*/ );
            wp_enqueue_script( 'timepad-jquery-cookie' );
            
            //NetMolis admin script
            wp_register_script( 'timepad-admin-js', plugins_url( 'assets/js/admin/admin.js', TIMEPADEVENTS_FILE ), self::$_jsHandlesInsidePluginAdmin, null/*, true*/ );
            wp_enqueue_script( 'timepad-admin-js' );
            
            //JavaScript Array to transfer data from backend/php/wp to frontend
            $this->_timepadevents_js_array = array(
                '_site_url' => TIMEPADEVENTS_SITEURL
            );
            
            wp_localize_script( 'timepad-admin-js', 'timepad', $this->_timepadevents_js_array );
        }
    }
endif;