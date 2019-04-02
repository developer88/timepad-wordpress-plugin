<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'TimepadEvents_Admin_Subscribers_Exporter' ) ) :

    require_once plugin_dir_path( __DIR__ ) . '../../newsletter/includes/controls.php';
    
    class TimepadEvents_Admin_Subscribers_Exporter extends TimepadEvents_Admin_Base {
    
        /**
         * Save email addresses of event participants
         *   from timePad event to Newsletter plugin
         * 
         * @since  1.1.5.1000
         * @param  string $path
         * @param  object $object
         * @param  string $token
         * @access public
         * @return null
         */
        public static function export( $event, $object, $token ) {
            TimepadEvents_Helpers::debug('__DIR__= ' . __DIR__);   
            TimepadEvents_Helpers::debug('plugin_dir_path( __DIR__ )= ', plugin_dir_path( __DIR__ )); 
            TimepadEvents_Helpers::debug('Exporting subscribers. Getting orders');

            $orders = $object->_get_request_array( str_replace('{event_id}', $event['id'], $object->_config['orders_url']) . '?token=' . $token, 'get' );
            TimepadEvents_Helpers::debug('Exporting subscribers. Received orders');
            TimepadEvents_Helpers::debug($orders);
            $controls = new NewsletterControls();
            $module = NewsletterUsers::instance();
            $options_profile = get_option('newsletter_profile');            
            TimepadEvents_Helpers::debug('Exporting subscribers. Processing orders');
            foreach ($orders['values'] as $order) {
                if( self::order_complete($order) === true ) {
                    self::export_subscriber($order, $module);
                }
            }
        }

        public static function export_subscriber( $order, $module ) {
            foreach ($order['tickets'] as $ticket) {
                $email = $newsletter->normalize_email($ticket['answers']['mail']);

                if (empty($email)) {
                    TimepadEvents_Helpers::debug('Exporting subscribers. Empty email');  
                    continue;
                }
                
                $first_name = $ticket['answers']['name'];
                $last_name = $ticket['answers']['surname'];                

                if (!$newsletter->is_email($email)) {
                    TimepadEvents_Helpers::debug('Exporting subscribers. Invalid email' . $email);  
                    continue;
                }

                $subscriber = $module->get_user($email, ARRAY_A);
                if ($subscriber != null) {
                    TimepadEvents_Helpers::debug('Exporting subscribers. User exists for ' . $email); 
                    continue;
                }

                $subscriber = array();
                $subscriber['email'] = $email;
                if (isset($first_name)) {
                    $subscriber['name'] = $module->normalize_name($first_name);
                }
                if (isset($last_name)) {
                    $subscriber['surname'] = $module->normalize_name($last_name);
                }
                // S - not confirmed, C - confirmed
                $subscriber['status'] = 'S';
                TimepadEvents_Helpers::debug('Exporting subscribers. Saving user with email ' . $email); 
                $module->save_user($subscriber);
            }            
        }

        public static function order_complete ( $order ) {
            TimepadEvents_Helpers::debug('Order status to check if order complete');
            TimepadEvents_Helpers::debug(var_dump($order['status'], true));
            // TODO: this is not final.
            if ( $order['status']['name'] == 'ok' ) {
                return true;
            } else {
                return false;
            }
        }

    }

endif;