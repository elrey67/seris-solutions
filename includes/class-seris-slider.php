<?php
class Seris_Slider_Widget extends WP_Widget {

    public function __construct() {
        parent::__construct(
            'seris_slider_widget',
            __('Seris Posts Slider', 'seris-solutions'),
            ['description' => __('Display recent posts in a slider', 'seris-solutions')]
        );
    }

    public function widget($args, $instance) {
        echo $args['before_widget'];

        if (!empty($instance['title'])) {
            echo $args['before_title'] . esc_html($instance['title']) . $args['after_title'];
        }

        echo do_shortcode('[seris_slider]');
        echo $args['after_widget'];
    }

    public function form($instance) {
        $title = !empty($instance['title']) ? $instance['title'] : __('Latest Posts', 'seris-solutions');
        ?>
        <p>
            <label for="<?php echo esc_attr($this->get_field_id('title')); ?>">
                <?php esc_html_e('Title:', 'seris-solutions'); ?>
            </label>
            <input 
                type="text" 
                class="widefat" 
                id="<?php echo esc_attr($this->get_field_id('title')); ?>" 
                name="<?php echo esc_attr($this->get_field_name('title')); ?>" 
                value="<?php echo esc_attr($title); ?>"
            >
        </p>
        <?php
    }

    public function update($new_instance, $old_instance) {
        $instance = [];
        $instance['title'] = sanitize_text_field($new_instance['title']);
        return $instance;
    }
}