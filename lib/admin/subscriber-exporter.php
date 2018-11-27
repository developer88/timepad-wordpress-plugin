<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'TimepadEvents_Admin_Subscribers_Exporter' ) ) :
    
    class TimepadEvents_Admin_Subscribers_Exporter extends TimepadEvents_Admin_Base {
    
        /**
         * Save email addresses of event participants
         *   from timePad event to Newsletter plugin
         * 
         * @since  1.1.5.1000
         * @param  string $path
         * @access public
         * @return null
         */
        public static function export( $event ) {
            
        }

    }

endif;