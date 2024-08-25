<?php
/**
 * Plugin Name: Aliving Product
 * Description: Elementor custom widgets from Eessential Web Apps.
 * Plugin URI: "https://growforwardjp.com/"
 * Version: 1.0.0
 * Author: Grow Forward;
 * Author URI: "https://growforwardjp.com/"
 * Text Domain: essential-elementor-widget
 *
 */

if (!defined('ABSPATH')) {
    exit; // Exit if accessed directly
}

add_action('elementor/widgets/widgets_registered', function ($widgets_manager) {
    require_once(__DIR__ . '/widget/widget-alivingproduct.php');
    require_once(__DIR__ . '/widget/widget-fullalivingproduct.php');
    $widgets_manager->register(new \Elementor_AlivingProduct_Widget());
    $widgets_manager->register(new \Elementor_Full_AlivingProduct_Widget());
});



function aliving_product_enqueue_styles()
{
    wp_enqueue_style('aliving-product-style', plugin_dir_url(__FILE__) . 'css/common.css');
}
add_action('wp_enqueue_scripts', 'aliving_product_enqueue_styles');

