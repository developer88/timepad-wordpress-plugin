<?php

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly

if ( ! class_exists( 'TimepadEvents_Admin_Base' ) ) :

    /**
     * Base Admin abstract class
     *
     * @class       TimepadEvents_Admin_Base
     * @since       1.0.0
     * @package     TimepadEvents/Admin
     * @author      Igor Sazonov <sovletig@yandex.ru>
     * @category    Admin Base Abstract class
     * @abstract
     */
    abstract class TimepadEvents_Admin_Base extends TimepadEvents_Base {
        
        /**
         * @var array The plugin data array
         */
        protected $_data = array();

        /**
         * @var array Plugin Admin JavaScript variables
         */
        protected $_timepadevents_js_array = array();
        
        /**
         * @var int Current native WordPress User ID
         */
        protected $_current_user_id;
        
        /**
         * @var string|float Current site WordPress version
         */
        protected $_wp_version;

        public function __construct() {
            parent::__construct();
            
            $this->_data = get_option( $this->_config['optionkey'] );
            
            $this->_current_user_id = get_current_user_id();
            
            $this->requirements = array(
                'php' => $this->_config['php_min']
                ,'wp' => $this->_config['wp_min']
            );
            
            global $wp_version;
            $this->_wp_version = $wp_version;
        }

        /**
         * This function returns current post/item object by given post id
         *
         * @uses   get_post() function
         * @link   http://codex.wordpress.org/Function_Reference/get_post
         * @since  1.0.0
         * @author Igor Sazonov <sovletig@yandex.ru>
         * @access protected
         * @return object Post object by given id
         */
        protected function _get_post() {
            return get_post( $this->_id );
        }
        
        /**
         * 
         * @since  1.1
         * @global string $pagenow WordPress native global var about admin page now
         * @return boolean
         */
        protected function _is_plugin_admin_page() {
            if ( is_admin() ) {
                global $pagenow;
                
                if ( $pagenow == 'post.php' ) {
                    if ( $this->_get_post()->post_type == $this->_config['type'] ) {
                        return true;
                    }
                }
                
                if ( $pagenow == 'edit.php' && isset( $_GET['post_type'] ) && $_GET['post_type'] == TIMEPADEVENTS_POST_TYPE ) {
                    return true;
                }
            }

            return false;
        }
        
        /**
         * Check for base plugin requirements.
         * If errors found, the message(s) will be displayed
         * 
         * @since  1.1
         * @author Igor Sazonov <sovletig@yandex.ru>
         * @access protected
         * @return string Plugin requirements errors string
         */
        protected function _requirements_messages() {
            $errors = array();
            if ( version_compare( phpversion(), $this->requirements['php'], '<' ) ) {
                $errors[] = '- ' . sprintf( __( 'Your PHP version is less than %s. TimePad Events plugin requires at least PHP 5.4 and can work with errors on older versions. We recommed you update your PHP version - you can do this by contacting your system administrator or to your hosting support.', 'timepad' ), '<strong>' . $this->requirements['php'] . '</strong>' );
            }
            
            if ( version_compare( $this->_wp_version, $this->requirements['wp'], '<' ) ) {
                $errors[] = '- ' . sprintf( __( 'Your website WordPress version is less than %s. TimePad Events plugin requires at least WordPress 4.0 and can work with errors on older versions. We recommend you update your WordPress to the latest version.', 'timepad' ), '<strong>' . $this->requirements['wp'] . '</strong>' );
            }
            
            if ( !empty( $errors ) ) {
                array_unshift( $errors, __( 'TimePad Events error messages:', 'timepad' ) );
                return implode( '<br />', $errors );
            }
            
            return '';
        }
        
        /**
         * Unsyncronize the event from TimePad API
         * 
         * @since  1.1
         * @param  type $post_id Internal WordPress post ID
         * @param  type $event_id Internal TimePad event ID
         * @param  type $post_type Needle post type. Default is 'post'
         * @access protected
         * @return boolean
         */
        public function unsyncronize_event_to_post( $post_id, $event_id, $organization_id, $post_type = 'post' ) {
            $unsyncronized_events = TimepadEvents_Helpers::get_excluded_from_api_events();
            if ( !isset( $unsyncronized_events[$post_id] ) ) {
                $post = get_post( $post_id );
                if ( $post->post_type == TIMEPADEVENTS_POST_TYPE && $post_type != TIMEPADEVENTS_POST_TYPE ) {
                    $post->post_type = $post_type;
                    if ( wp_update_post( $post ) ) {
                        if ( delete_post_meta( $post_id, 'timepad_meta' ) ) {
                            $unsyncronized_events[intval( $event_id )] = $post_id;
                            unset( $this->_data['events'][$organization_id][$event_id] );
                            if ( TimepadEvents_Helpers::update_option_key( $this->_config['optionkey'], isset( $this->_data['events'] ) ? $this->_data['events'] : array(), 'events' ) ) {
                                if ( update_option( 'timepad_excluded_from_api', $unsyncronized_events ) ) {
                                    return true;
                                }
                            }
                        }
                    }
                }
            }
            
            return false;
        }
        
        /**
         * AJAX version of function unsyncronize_event_to_post
         * 
         * @since 1.1
         * @uses  unsyncronize_event_to_post
         * return void
         */
        public function unsyncronize_event_to_post_ajax() {
            check_ajax_referer( $this->_config['security_nonce'], 'security' );
            $post_id         = intval( $_POST['post_id'] );
            $event_id        = intval( $_POST['event_id'] );
            $organization_id = intval( $_POST['organization_id'] );
            
            $this->unsyncronize_event_to_post( $post_id, $event_id, $organization_id );
            wp_die(1);
        }

    }

endif;