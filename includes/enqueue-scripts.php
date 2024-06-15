<?php
// Enqueue styles and scripts
function wcdpo_enqueue_scripts() {
    wp_enqueue_style('wcdpo-style', plugin_dir_url(__FILE__) . '../assets/css/style.css');
    wp_enqueue_script('wcdpo-script', plugin_dir_url(__FILE__) . '../assets/js/script.js', array('jquery'), null, true);

    // Localize script to pass AJAX URL
    wp_localize_script('wcdpo-script', 'wcdpo_ajax', array(
        'ajax_url' => admin_url('admin-ajax.php'),
        'nonce' => wp_create_nonce('wcdpo_nonce')
    ));
}
add_action('wp_enqueue_scripts', 'wcdpo_enqueue_scripts');
