<?php
defined('ABSPATH') or die('You can\'t access this file.');

if (!function_exists('pgch_processes_cpt')) {
    function pgch_processes_cpt()
    {
        $labels = array(
            'name' => _x('Processes', 'Post Type General Name', 'pages-checker'),
            'singular_name' => _x('Process', 'Post Type Singular Name', 'pages-checker'),
            'menu_name' => __('Processes', 'pages-checker'),
            'parent_item_colon' => __('Parent Process:', 'pages-checker'),
            'all_items' => __('Processes', 'pages-checker'),
            'view_item' => __('View Process', 'pages-checker'),
            'add_new_item' => __('Add New Process', 'pages-checker'),
            'add_new' => __('New Process', 'pages-checker'),
            'edit_item' => __('Edit Process', 'pages-checker'),
            'update_item' => __('Update Process', 'pages-checker'),
            'search_items' => __('Search Processes', 'pages-checker'),
            'not_found' => __('No Processes found', 'pages-checker'),
            'not_found_in_trash' => __('No Processes found in trash', 'pages-checker'),
        );
        $args = array(
            'label' => __('Process', 'pages-checker'),
            'description' => __('Processes', 'pages-checker'),
            'labels' => $labels,
            'supports' => array('title'),
            'hierarchical' => false,
            'public' => false,
            'show_ui' => true,
            'show_in_nav_menus' => false,
            'show_in_menu' => 'pages-checker-dashboard',
            'show_in_admin_bar' => true,
            'menu_position' => 3,
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'capability_type' => 'post',
            'query_var' => false,
        );
        register_post_type('pgch_process', $args);
    }
    add_action('init', 'pgch_processes_cpt', 10);
}
