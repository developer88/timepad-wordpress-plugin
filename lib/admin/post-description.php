<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'TimepadEvents_Admin_Post_Description' ) ) :
    
    class TimepadEvents_Admin_Post_Description extends TimepadEvents_Admin_Base {

        /**
         * @var array The plugin data array
         */
        protected $_event = array();

        /**
         * Initializes the class
         * 
         * @since  1.1.5.1000
         * @param  array $event
         * @access public
         * @return object 
         */
        function __construct( $event ) {
            $this->_event = $event;
            parent::__construct();
        }

        /**
         * Returns description with all necessary information 
         *   from timePad event
         * 
         * @since  1.1.5.1000
         * @param  string $path
         * @access public
         * @return string Post description with details
         */
        public function render() {
            $content = '';
            if ( isset( $this->_event['description_short'] ) && !empty( $this->_event['description_short'] ) ) {
                $content .= $this->_event['description_short'];
            }
            if ( isset( $this->_event['description_html'] ) && !empty( $this->_event['description_html'] ) ) {
                $content .= $this->_event['description_html'];
            }
            if ( !empty( $content ) ) {
                $content .= self::render_details();
            }

            return $content;
        }

        public static function render_details() {
            $content = '<div class="timepad-event-details">';
                $content .= '<div class="timepad-event-details-title">Детали события</div>';
                $content .= self::render_date();
                $content .= self::render_location();
            $content .= '</div>';

            return $content;
        }

        public static function render_date() {
            $moscow_time = self::moscow_date_time_str();
            $content .= '<div class="timepad-event-details-date">';
                $content .= '<i class="font-icon-post fa fa-clock-o"></i> ';
                $content .= '<span>' . date("j.m.o", $moscow_time['starts_at']);
                    $content .=  $moscow_time['use_ends_at']  ?  " c " : " в ";
                    $content .=  date("G:i", $moscow_time['starts_at']);
                    if ($use_end_time) {
                        $content .=  " до " . date("G:i", $moscow_time['ends_at']);
                    }
                $content .= '</span>';
            $content .= '</div>';

            return $content;
        }

        public static function moscow_date_time_str() {
            $moscow_offset = '3 hours';
            $starts_at_moscow = strtotime($this->_event['starts_at'] . ' + ' . $moscow_offset);
            $ends_at_moscow = strtotime($this->_event['ends_at'] . ' + ' . $moscow_offset);
            $use_end_time = (!empty( $this->_event['ends_at'] ) && $starts_at_moscow != $ends_at_moscow);
            
            return array(
                'starts_at' => $starts_at_moscow,
                'ends_at' => $ends_at_moscow,
                'use_ends_at' => $use_end_time
            );
        }

        public static function render_location() {
            $location_string = implode( ', ', array($this->_event['location']['country'], $this->_event['location']['city'], $this->_event['location']['address']) );  
            $content = '<div class="timepad-event-details-location">';
                $content .= '<div class="timepad-event-details-location-address"><i class="font-icon-post fa fa-home"></i> ' . $location_string . '</div>';
                $content .= '<div class="timepad-event-details-location-map">';
                    $content .= '<iframe width="600" height="450" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=' . urlencode($location_string) . '&key=' . $this->_secrets['google_maps_api'] . '" allowfullscreen></iframe>';
                $content .= '</div>';
            $content .= '</div>';

            return $content;           
        }

    }

endif;