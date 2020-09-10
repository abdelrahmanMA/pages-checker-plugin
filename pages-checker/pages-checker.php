<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://abdelrahmanma.com/
 * @since             1.0.0
 * @package           Pages_Checker
 *
 * @wordpress-plugin
 * Plugin Name:       Pages Checker
 * Plugin URI:        https://abdelrahmanma.com/
 * Description:       Custom plugin built to check pages and then send a report of the result.
 * Version:           1.0.0
 * Author:            Abdelrahman Muhammad
 * Author URI:        https://abdelrahmanma.com/
 * License:           GPL-2.0+
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       pages-checker
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - httpss://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('Pages_Checker_VERSION', '1.0.0');
define('SALESFORCE_API_VERSION', '39.0');

define('EXCEL_UPLOADS', plugin_dir_path(__FILE__) . 'admin/excel_uploads');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-pages-checker-activator.php
 */
function activate_Pages_Checker()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-pages-checker-activator.php';
	Pages_Checker_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-pages-checker-deactivator.php
 */
function deactivate_Pages_Checker()
{
	require_once plugin_dir_path(__FILE__) . 'includes/class-pages-checker-deactivator.php';
	Pages_Checker_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_Pages_Checker');
register_deactivation_hook(__FILE__, 'deactivate_Pages_Checker');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-pages-checker.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_Pages_Checker()
{

	$plugin = new Pages_Checker();
	$plugin->run();
}
run_Pages_Checker();
