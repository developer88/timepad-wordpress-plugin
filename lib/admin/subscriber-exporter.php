<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'TimepadEvents_Admin_Subscribers_Exporter' ) ) :

    require_once plugin_dir_path( __DIR__ ) . '/newsletter/includes/controls.php';
    
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
            //TimepadEvents_Helpers::debug('Event id=' . $event['id']);
            $orders = $this->_get_request_array( str_replace('{event_id}', $event['id'], $this->_config['orders_url']), 'get' );
        }



        // public function export_subscribers( $event ) {
        //     TimepadEvents_Helpers::debug('Event id=' . $event['id']);
        //     TimepadEvents_Helpers::debug(print_r($event, true));
        //     $orders = $this->_get_request_array( str_replace('{event_id}', $event['id'], $this->_config['orders_url']), 'get' );
        //     foreach ($orders['values'] as $order) {
        //         if ( isset($order['mail']) && strlen($order['mail']) > 0 && $order['status']['name'] == NUL!!!! ) {
        //             // TODO: save $order['mail'] if new
        //             TimepadEvents_Helpers::debug('__DIR__= ' . __DIR__);   
        //             TimepadEvents_Helpers::debug('plugin_dir_path( __DIR__ )= ', plugin_dir_path( __DIR__ ));               
        //             //require_once plugin_dir_path( __DIR__ ) . '/newsletter/includes/controls.php';
        // //             $controls = new NewsletterControls();
        // //             $module = NewsletterUsers::instance();
        // //             $options_profile = get_option('newsletter_profile');
        // //             // Builds a subscriber data structure
        // // $email = $newsletter->normalize_email($data[0]);
        // // if (empty($email)) {
        // //     continue;
        // // }

        // // if (!$newsletter->is_email($email)) {
        // //     $results .= '[INVALID EMAIL] ' . $line . "\n";
        // //     $error_count++;
        // //     continue;
        // // }

        // // $subscriber = $module->get_user($email, ARRAY_A);
        // // if ($subscriber == null) {
        // //     $subscriber = array();
        // //     $subscriber['email'] = $email;
        // //     if (isset($data[1])) {
        // //         $subscriber['name'] = $module->normalize_name($data[1]);
        // //     }
        // //     if (isset($data[2])) {
        // //         $subscriber['surname'] = $module->normalize_name($data[2]);
        // //     }
        // //     if (isset($data[3])) {
        // //         $subscriber['sex'] = $module->normalize_sex($data[3]);
        // //     }
        // //     $subscriber['status'] = $controls->data['import_as'];
        // //     foreach ($controls->data['preferences'] as $i) {
        // //         $subscriber['list_' . $i] = 1;
        // //     }
        // //     $module->save_user($subscriber);
        // //     $results .= '[ADDED] ' . $line . "\n";
        // //     $added_count++;
        //         }
        //     }
        // }

    }

endif;