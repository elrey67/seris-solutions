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
            
            // Add ad zones
            add_action('wp_head', [$this, 'register_ad_zones'], 5);
        }

        /**
         * Load CSS & JS only when needed
         */
        public function load_assets() {
            if ($this->is_slider_needed()) {
                // Verify file locations
                $css_path = plugin_dir_path(__FILE__) . 'assets/css/slider.css';
                $js_path = plugin_dir_path(__FILE__) . 'assets/js/slider.js';
                
                if (file_exists($css_path)) {
                    wp_enqueue_style(
                        'seris-slider-css',
                        plugin_dir_url(__FILE__) . 'assets/css/slider.css',
                        [],
                        filemtime($css_path)
                    );
                }
                
                if (file_exists($js_path)) {
                    wp_enqueue_script(
                        'seris-slider-js',
                        plugin_dir_url(__FILE__) . 'assets/js/slider.js',
                        ['jquery'],
                        filemtime($js_path),
                        true
                    );
                }
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
            // Sanitize attributes
            $atts = shortcode_atts([
                'category' => '',
                'posts_per_page' => 3,
                'image_size' => 'large',
                'excerpt_length' => 12
            ], $atts);

            // Query posts
            $query = new WP_Query([
                'post_type' => 'post',
                'posts_per_page' => absint($atts['posts_per_page']),
                'category_name' => sanitize_title($atts['category'])
            ]);

            // If no posts found, return early
            if (!$query->have_posts()) {
                return '<div class="seris-slider-container"><p>No posts found.</p></div>';
            }

            ob_start(); ?>
            
            <div class="seris-slider-container">
                <div class="seris-slider-wrapper">
                    <?php while ($query->have_posts()) : $query->the_post(); ?>
                        <div class="seris-slide">
                            <!-- Top Ad Zone -->
                          <!-- <div class="seris-approved-adspot" 
                                 data-ad-location="top"
                                 data-ad-network="adsense,ezoic"></div> -->
                            
                            <!-- Image -->
                            <div class="seris-slide-image-container">
                                <a href="<?php echo esc_url(get_permalink()); ?>">
                                    <?php echo wp_get_attachment_image(
                                        get_post_thumbnail_id(),
                                        esc_attr($atts['image_size']),
                                        false,
                                        ['class' => 'seris-slide-img']
                                    ); ?>
                                </a>
                            </div>
                            
                            <!-- Content -->
                            <div class="seris-slide-content">
                                <h3 class=" seris-slide-title" ><a href="<?php echo esc_url(get_permalink()); ?>"><?php echo esc_html(get_the_title()); ?></a></h3>
                                <p><?php echo esc_html(wp_trim_words(
                                    get_the_excerpt(), 
                                    absint($atts['excerpt_length'])
                                )); ?></p>
                                <div class="seris-slide-meta">
                                    <span class="seris-slide-author">by <?php echo esc_html(get_the_author()); ?></span>
                                </div>
                            </div>
                            
                            <!-- Bottom Ad Zone -->
                          <!--  <div class="seris-approved-adspot" 
                                 data-ad-location="bottom"
                                 data-ad-network="adsense,ezoic"></div> -->
                        </div>
                    <?php endwhile; wp_reset_postdata(); ?>
                </div>
                
                <!-- Pagination Dots -->
                <div class="seris-slider-pagination"></div>
            </div>
            
            <?php
            return ob_get_clean();
        }
        
        /**
         * Register ad zones
         */
        public function register_ad_zones() {
            if (!is_admin()) {
                echo '<!-- Seris Solutions Ad Zones -->';
                echo '<meta name="seris-ad-zones" content="approved">';
                
                // For AdSense
                echo '<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-1074859157597727&amp;host=ca-host-pub-2644536267352236" crossorigin="anonymous"></script>';
                
                // For Ezoic (if needed)
               // echo '<script id="ezoic-placeholder">';
               // echo 'var ezstandalone = ezstandalone || {};';
               // echo 'ezstandalone.cmd = ezstandalone.cmd || [];';
                //echo '</script>';
            }
        }

        /**
         * Register widget
         */
        public function register_widget() {
            $widget_file = plugin_dir_path(__FILE__) . 'includes/class-seris-slider-widget.php';
            if (file_exists($widget_file)) {
                require_once $widget_file;
                register_widget('Seris_Slider_Widget');
            }
        }
    }

    // Initialize plugin
    new Seris_Solutions_Slider();
}