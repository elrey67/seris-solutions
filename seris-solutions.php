<?php
/*
 * Plugin Name: Seris Solutions - GP Slider
 * Description: Custom posts slider for GeneratePress with widget & shortcode support.
 * Version: 1.0
 * Author: Faveren Caleb
 * License: GPL-2.0+
 * Text Domain: seris-solutions
 */

// Exit if accessed directly
defined('ABSPATH') || exit;

if (!class_exists('Seris_Solutions_Slider')) {
    class Seris_Solutions_Slider {

        public function __construct() {
            // Load assets
            add_action('wp_enqueue_scripts', [$this, 'load_assets']);

            // Register shortcode
            add_shortcode('seris_slider', [$this, 'render_slider']);

            // Register widget
            add_action('widgets_init', [$this, 'register_widget']);
        }

        /**
         * Load CSS & JS only when needed
         */
        public function load_assets() {
            if ($this->is_slider_needed()) {
                wp_enqueue_style(
                    'seris-slider-css',
                    plugin_dir_url(__FILE__) . 'assets/css/slider.css',
                    [],
                    filemtime(plugin_dir_path(__FILE__) . 'assets/css/slider.css')
                );

                wp_enqueue_script(
                    'seris-slider-js',
                    plugin_dir_url(__FILE__) . 'assets/js/slider.js',
                    ['jquery'],
                    filemtime(plugin_dir_path(__FILE__) . 'assets/js/slider.js'),
                    true
                );
            }
        }

        /**
         * Check if slider assets should load
         */
        private function is_slider_needed() {
            global $post;

            // Check for shortcode
            if (is_a($post, 'WP_Post') && has_shortcode($post->post_content, 'seris_slider')) {
                return true;
            }

            // Check if widget is active
            if (is_active_widget(false, false, 'seris_slider_widget')) {
                return true;
            }

            return false;
        }

        /**
         * Slider shortcode
         */
      public function render_slider($atts) {
    $atts = shortcode_atts([
        'category'       => '',
        'posts_per_page' => 3,
        'image_size'    => 'large', // Changed to 'large' for better quality
        'excerpt_length' => 12
    ], $atts);

    ob_start();
    
    $query = new WP_Query([
        'post_type'      => 'post',
        'posts_per_page' => $atts['posts_per_page'],
        'category_name'  => $atts['category']
    ]);

    if ($query->have_posts()) : ?>
        <div class="seris-slider-container">
            <div class="seris-slider-wrapper">
                <?php while ($query->have_posts()) : $query->the_post(); ?>
                    <div class="seris-slide">
                        <a href="<?php the_permalink(); ?>" class="seris-slide-image">
                            <?php the_post_thumbnail($atts['image_size']); ?>
                        </a>
                        <div class="seris-slide-content">
                            <h3 class="seris-slide-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h3>
                            <div class="seris-slide-excerpt">
                                <?php echo wp_trim_words(get_the_excerpt(), $atts['excerpt_length']); ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        </div>
    <?php endif;

    wp_reset_postdata();
    return ob_get_clean();
}

        /**
         * Register widget
         */
        public function register_widget() {
            require_once plugin_dir_path(__FILE__) . 'includes/class-seris-slider.php';
            register_widget('Seris_Slider_Widget');
        }
    }

    // Initialize plugin
    new Seris_Solutions_Slider();
}