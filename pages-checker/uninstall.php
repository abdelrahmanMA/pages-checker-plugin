<?php

/**
 * Fired when the plugin is uninstalled.
 *
 * When populating this file, consider the following flow
 * of control:
 *
 * - This method should be static
 * - Check if the $_REQUEST content actually is the plugin name
 * - Run an admin referrer check to make sure it goes through authentication
 * - Verify the output of $_GET makes sense
 * - Repeat with other user roles. Best directly by using the links/query string parameters.
 * - Repeat things for multisite. Once for a single site in the network, once sitewide.
 *
 * This file may be updated more in future version of the Boilerplate; however, this is the
 * general skeleton and outline for how the file should work.
 *
 * For more information, see the following discussion:
 * https://github.com/tommcfarlin/WordPress-Plugin-Boilerplate/pull/123#issuecomment-28541913
 *
 * @see       https://abdelrahmanma.com
 * @since      1.0.0
 */

// If uninstall not called from WordPress, then exit.
if (!defined('WP_UNINSTALL_PLUGIN')) {
    exit;
}
unregister_post_type('pgch_email');
unregister_post_type('pgch_template');
unregister_post_type('pgch_process');
unregister_post_type('pgch_campaign');
unregister_post_type('pgch_analytic');

delete_option('pgch_sfconnect');
delete_option('pgch_sfkey');
delete_option('pgch_sfsecret');
delete_option('pgch_sfuser');
delete_option('pgch_sfpass');
delete_option('pgch_tool');

global $wpdb;
$wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}pgch_statistics");
