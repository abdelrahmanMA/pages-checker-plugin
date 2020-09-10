<?php
defined('ABSPATH') or die('You can\'t access this file.');

if (!function_exists('pgch_campaigns_cpt')) {
    function pgch_campaigns_cpt()
    {
        $labels = array(
            'name' => _x('Campaigns', 'Post Type General Name', 'pages-checker'),
            'singular_name' => _x('Campaign', 'Post Type Singular Name', 'pages-checker'),
            'menu_name' => __('Campaigns', 'pages-checker'),
            'parent_item_colon' => __('Parent Campaign:', 'pages-checker'),
            'all_items' => __('Campaigns', 'pages-checker'),
            'view_item' => __('View Campaign', 'pages-checker'),
            'add_new_item' => __('Add New Campaign', 'pages-checker'),
            'add_new' => __('New Campaign', 'pages-checker'),
            'edit_item' => __('Edit Campaign', 'pages-checker'),
            'update_item' => __('Update Campaign', 'pages-checker'),
            'search_items' => __('Search Campaigns', 'pages-checker'),
            'not_found' => __('No Campaigns found', 'pages-checker'),
            'not_found_in_trash' => __('No Campaigns found in trash', 'pages-checker'),
        );
        $args = array(
            'label' => __('Campaign', 'pages-checker'),
            'description' => __('Campaigns', 'pages-checker'),
            'labels' => $labels,
            'supports' => array('title'),
            'hierarchical' => false,
            'public' => false,
            'show_ui' => true,
            'show_in_nav_menus' => false,
            'show_in_menu' => 'pages-checker-dashboard',
            'show_in_admin_bar' => true,
            'menu_position' => 1,
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'capability_type' => 'post',
            'query_var' => false,
        );
        register_post_type('pgch_campaign', $args);
    }
    add_action('init', 'pgch_campaigns_cpt', 10);
}
