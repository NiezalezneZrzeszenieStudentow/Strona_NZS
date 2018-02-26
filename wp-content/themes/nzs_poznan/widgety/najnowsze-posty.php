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
         $title = apply_filters('widget_title', $instance['title']);
        echo $args['before_widget'];
        if(!empty($title)) echo $args['before_title'].$title.$args['after_title'];
        echo '<p>Brawo! Widget działa!</p>';
        echo $args['after_widget'];
         
    }

    /**
     * Outputs the options form on admin
     *
     * @param array $instance The widget options
     */
    public function form( $instance ) {
       if(isset($instance['title'])) $title = $instance['title'];
            else $title = 'Mój widget';
            echo '<p><label for="'.$this->get_field_id('title').'">'.__('Title:').'</label><input class="widefat" id="'.$this->get_field_id('title').'" name="'.$this->get_field_name('title').'" type="text" value="'.esc_attr($title).'" /></p>';
    }

    /**
     * Processing widget options on save
     *
     * @param array $new_instance The new options
     * @param array $old_instance The previous options
     */
    public function update( $new_instance, $old_instance ) {
        $instance = array();
        $instance['title'] = (!empty($new_instance['title'])) ? strip_tags($new_instance['title']) : '';
        return $instance;
    }
}

