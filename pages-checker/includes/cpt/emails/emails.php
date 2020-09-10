<?php
defined('ABSPATH') or die('You can\'t access this file.');

if (!function_exists('pgch_emails_cpt')) {
    function pgch_emails_cpt()
    {
        $labels = array(
            'name' => _x('Email Accounts', 'Post Type General Name', 'pages-checker'),
            'singular_name' => _x('Email Account', 'Post Type Singular Name', 'pages-checker'),
            'menu_name' => __('Email Accounts', 'pages-checker'),
            'parent_item_colon' => __('Parent Email Account:', 'pages-checker'),
            'all_items' => __('Email Accounts', 'pages-checker'),
            'view_item' => __('View Email Account', 'pages-checker'),
            'add_new_item' => __('Add New Email Account', 'pages-checker'),
            'add_new' => __('New Email Account', 'pages-checker'),
            'edit_item' => __('Edit Email Account', 'pages-checker'),
            'update_item' => __('Update Email Account', 'pages-checker'),
            'search_items' => __('Search Email Accounts', 'pages-checker'),
            'not_found' => __('No Email Accounts found', 'pages-checker'),
            'not_found_in_trash' => __('No Email Accounts found in trash', 'pages-checker'),
        );
        $args = array(
            'label' => __('Email Account', 'pages-checker'),
            'description' => __('Email Accounts', 'pages-checker'),
            'labels' => $labels,
            'supports' => array('title'),
            'hierarchical' => false,
            'public' => false,
            'show_ui' => true,
            'show_in_nav_menus' => false,
            'show_in_menu' => 'pages-checker-dashboard',
            'show_in_admin_bar' => true,
            'menu_position' => null,
            'can_export' => true,
            'has_archive' => false,
            'exclude_from_search' => true,
            'publicly_queryable' => false,
            'capability_type' => 'post',
            'query_var' => false,
        );
        register_post_type('pgch_email', $args);
    }
    add_action('init', 'pgch_emails_cpt', 10);
}
