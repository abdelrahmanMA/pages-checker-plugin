<?php
defined('ABSPATH') or die('You can\'t access this file.');
if (!function_exists('pgch_analytics_cpt')) {
    function pgch_analytics_cpt()
    {
        $labels = array(
            'name' => _x('Analytics', 'Post Type General Name', 'pages-checker'),
            'singular_name' => _x('Analytic', 'Post Type Singular Name', 'pages-checker'),
            'menu_name' => __('Analytics', 'pages-checker'),
            'parent_item_colon' => __('Parent Analytic:', 'pages-checker'),
            'all_items' => __('Analytics', 'pages-checker'),
            'view_item' => __('View Statistics', 'pages-checker'),
            'add_new_item' => __('Add New Statistic', 'pages-checker'),
            'add_new' => __('New Statistic', 'pages-checker'),
            'edit_item' => __('Edit Statistic', 'pages-checker'),
            'update_item' => __('Update Statistic', 'pages-checker'),
            'search_items' => __('Search Statistic', 'pages-checker'),
            'not_found' => __('No Statistic found', 'pages-checker'),
            'not_found_in_trash' => __('No Statistic found in trash', 'pages-checker'),
        );
        $args = array(
            'label' => __('Analytic', 'pages-checker'),
            'description' => __('Analytics', 'pages-checker'),
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
        register_post_type('pgch_analytic', $args);
    }
    add_action('init', 'pgch_analytics_cpt', 10);
}
