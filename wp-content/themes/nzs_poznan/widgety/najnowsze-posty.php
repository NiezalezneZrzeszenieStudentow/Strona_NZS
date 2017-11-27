<?php

class Najnowsze_Posty extends WP_Widget {

    /**
     * Sets up the widgets name etc
     */
   public function __construct() {
	$widget_id = 'najnowsze_posty';
	$widget_name = __('Najnowsze Posty Moje', 'NajnowszePosty');
	$widget_opt = array('description'=>'Ten widget wyświetla najnowsze posty.');

	parent::__construct($widget_id, $widget_name, $widget_opt);
}

    /**
     * Outputs the content of the widget
     *
     * @param array $args
     * @param array $instance
     */
    public function widget( $args, $instance ) {
        // outputs the content of the widget
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form( $instance ) {
        $instance = wp_parse_args( (array) $instance, array( 'title' => 'Najnowsze Posty' ) );
    }

    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     */
    public function update( $new_instance, $old_instance ) {
        return $new_instance;
    }
}

