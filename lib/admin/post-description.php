<?php
if ( ! defined( 'ABSPATH' ) ) exit;

if ( ! class_exists( 'TimepadEvents_Admin_Post_Description' ) ) :
    
    class TimepadEvents_Admin_Post_Description extends TimepadEvents_Admin_Base {
    
        /**
         * Returns description with all necessary information 
         *   from timePad event
         * 
         * @since  1.1.5.1000
         * @param  string $path
         * @access public
         * @return string Post description with details
         */
        public static function render( $event ) {
            $content = '';
            if ( isset( $event['description_short'] ) && !empty( $event['description_short'] ) ) {
                $content .= $event['description_short'];
            }
            if ( isset( $event['description_html'] ) && !empty( $event['description_html'] ) ) {
                $content .= $event['description_html'];
            }
            if ( !empty( $content ) ) {
                $content .= self::render_details($event);
            }

            return $content;
        }

        public static function render_details( $event ) {
            $content = '<div class="timepad-event-details">';
                $content .= '<div class="timepad-event-details-title">Детали события</div>';
                $content .= self::render_date($event);
                $content .= self::render_location($event);
            $content .= '</div>';

            return $content;
        }

        public static function render_date( $event ) {
            $moscow_time = self::moscow_date_time_str( $event );
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

        public static function moscow_date_time_str( $event ) {
            $moscow_offset = '3 hours';
            $starts_at_moscow = strtotime($event['starts_at'] . ' + ' . $moscow_offset);
            $ends_at_moscow = strtotime($event['ends_at'] . ' + ' . $moscow_offset);
            $use_end_time = (!empty( $event['ends_at'] ) && $starts_at_moscow != $ends_at_moscow);
            
            return array(
                'starts_at' => $starts_at_moscow,
                'ends_at' => $ends_at_moscow,
                'use_ends_at' => $use_end_time
            );
        }

        public static function render_location( $event ) {
            $location_string = implode( ', ', array($event['location']['country'], $event['location']['city'], $event['location']['address']) );  
            $content = '<div class="timepad-event-details-location">';
                $content .= '<div class="timepad-event-details-location-address"><i class="font-icon-post fa fa-home"></i> ' . $location_string . '</div>';
                $content .= '<div class="timepad-event-details-location-map">';
                    $content .= '<iframe width="600" height="450" frameborder="0" style="border:0" src="https://www.google.com/maps/embed/v1/place?q=' . urlencode($location_string) . '&key=AIzaSyBn9nH320gjn8oAw1tNzuf-nUsXKJ5V2FY" allowfullscreen></iframe>';
                $content .= '</div>';
            $content .= '</div>';

            return $content;           
        }

    }

endif;