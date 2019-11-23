<?php
/*
 * To change this license header, chose license headers in project properties
 * To change this  template file, chose tools | templates
 * and open the template in the editor
*/

/*
    #Plugin Name: My Own Metabox
    #Description: This is a simple plugin for learning
    #Version: 1.0.0
    #Author: Nacho
*/
# Function register to create metaboxes
function my_own_custom_init_cpt() {
    $args = array(
      'public' => true,
      'label'  => 'Books',
      'supports' => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'comments' )
    );
    register_post_type( 'book', $args );
}
add_action( 'init', 'my_own_custom_init_cpt' );

function my_own_register_metabox() {

    // Metabox for pages functions
    // add_meta_box($id, $title, $callback, $page, $context, $priority, $callback_args);
    add_meta_box("my-own-page-id", "My owm page metabox", "my_pages_metabox_function", "page", "normal", "high");

    // Metabox for posts functions
    add_meta_box('my-own-post-id', 'My own post metabox', 'my_posts_metabox_function', 'post', 'side', 'high');
}

# Register the action hook: add_action( 'add_meta_boxes', 'callback function' );
add_action('add_meta_boxes', 'my_own_register_metabox');

//Register a metabox Custom Post Type
function my_own_register_metabox_cpt() {
    // Metabox for Custom Post Types functions
    add_meta_box('my-own-cpt-id', 'My own CPT Metabox', 'my_cpt_metabox_function', 'book', 'side', 'high');
}
//add_action('add_meta_boxes_{custom_post_type}', 'callback-function');
add_action('add_meta_boxes_book', 'my_own_register_metabox_cpt');

/* *** Adding Meta Boxes in the dashboard **** */
add_action('wp_dashboard_setup', 'my_register_metabox_dashboard');

if(!function_exists('my_register_metabox_dashboard')):
    function my_register_metabox_dashboard() {
        add_meta_box('wp-dashboard-id', 'My dashboard Metabox', 'wp_dashboard_function', 'dashboard', 'normal', 'high');
    }
endif;

if(!function_exists('remove_dashboard_metabox')):
    function remove_dashboard_metabox() {
        //remove_meta_box($id, $screen, $context);
        remove_meta_box('dashboard_right_now', 'dashboard', 'normal');
        remove_meta_box('dashboard_activity', 'dashboard', 'normal');
        remove_meta_box('dashboard_quick_press', 'dashboard', 'side');
    }
endif;
add_action('wp_dashboard_setup', 'remove_dashboard_metabox');