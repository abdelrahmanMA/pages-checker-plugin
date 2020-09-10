<?php
defined('ABSPATH') or die('You can\'t access this file.');

if (!function_exists('pgch_templates_cpt')) {
	function pgch_templates_cpt()
	{
		$labels = array(
			'name'                => _x('Templates', 'Post Type General Name', 'pages-checker'),
			'singular_name'       => _x('Template', 'Post Type Singular Name', 'pages-checker'),
			'menu_name'           => __('Templates', 'pages-checker'),
			'parent_item_colon'   => __('Parent Template:', 'pages-checker'),
			'all_items'           => __('Templates', 'pages-checker'),
			'view_item'           => __('View Template', 'pages-checker'),
			'add_new_item'        => __('Add New Template', 'pages-checker'),
			'add_new'             => __('New Template', 'pages-checker'),
			'edit_item'           => __('Edit Template', 'pages-checker'),
			'update_item'         => __('Update Template', 'pages-checker'),
			'search_items'        => __('Search Templates', 'pages-checker'),
			'not_found'           => __('No Templates found', 'pages-checker'),
			'not_found_in_trash'  => __('No Templates found in trash', 'pages-checker'),
		);
		$args = array(
			'label'               => __('Template', 'pages-checker'),
			'description'         => __('Templates', 'pages-checker'),
			'labels'              => $labels,
			'supports'            => array('title', 'editor', 'revisions'),
			'hierarchical'        => false,
			'public'              => false,
			'show_ui'             => true,
			'show_in_nav_menus'   => false,
			'show_in_menu'		  => 'pages-checker-dashboard',
			'show_in_admin_bar'   => true,
			'menu_position'       => 1,
			'can_export'          => true,
			'has_archive'         => false,
			'exclude_from_search' => true,
			'publicly_queryable'  => false,
			'capability_type'     => 'post',
			'query_var'			  => false,
		);
		register_post_type('pgch_template', $args);
	}
	add_action('init', 'pgch_templates_cpt', 10);
}
