<?php

/**
 * The file that defines the core plugin class.
 *
 * A class definition that includes attributes and functions used across both the
 * public-facing side of the site and the admin area.
 *
 * @see       https://abdelrahmanma.com
 * @since      1.0.0
 */

/**
 * The core plugin class.
 *
 * This is used to define internationalization, admin-specific hooks, and
 * public-facing site hooks.
 *
 * Also maintains the unique identifier of this plugin as well as the current
 * version of the plugin.
 *
 * @since      1.0.0
 *
 * @author     Abdelrahman Muhammad <contact@abdelrahmanma.com>
 */
class Pages_Checker
{
    /**
     * The loader that's responsible for maintaining and registering all hooks that power
     * the plugin.
     *
     * @since    1.0.0
     *
     * @var Pages_Checker_Loader maintains and registers all hooks for the plugin
     */
    protected $loader;

    /**
     * The unique identifier of this plugin.
     *
     * @since    1.0.0
     *
     * @var string the string used to uniquely identify this plugin
     */
    protected $Pages_Checker;

    /**
     * The current version of the plugin.
     *
     * @since    1.0.0
     *
     * @var string the current version of the plugin
     */
    protected $version;

    /**
     * Define the core functionality of the plugin.
     *
     * Set the plugin name and the plugin version that can be used throughout the plugin.
     * Load the dependencies, define the locale, and set the hooks for the admin area and
     * the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function __construct()
    {
        if (defined('Pages_Checker_VERSION')) {
            $this->version = Pages_Checker_VERSION;
        } else {
            $this->version = '1.0.0';
        }
        $this->Pages_Checker = 'pages-checker';

        $this->load_dependencies();
        $this->define_admin_hooks();
    }

    /**
     * Load the required dependencies for this plugin.
     *
     * Include the following files that make up the plugin:
     *
     * - Pages_Checker_Loader. Orchestrates the hooks of the plugin.
     * - Pages_Checker_Admin. Defines all hooks for the admin area.
     * - Pages_Checker_Public. Defines all hooks for the public side of the site.
     *
     * Create an instance of the loader which will be used to register the hooks
     * with WordPress.
     *
     * @since    1.0.0
     */
    private function load_dependencies()
    {
        /**
         * The class responsible for orchestrating the actions and filters of the
         * core plugin.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/class-pages-checker-loader.php';

        /**
         * The class responsible for defining all actions that occur in the admin area.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/class-pages-checker-admin.php';

        /**
         * The Library responsible for reading the Excel or CSV files.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/phpoffice/vendor/autoload.php';

        /**
         * The Custom Post Types.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/cpt/cpt.php';

        /**
         * The Custom Meta-boxes for Custom Post Types.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'admin/meta-boxes/autoloader.php';

        /**
         * Mailer Class
         */
        // require_once ABSPATH . WPINC . '/class-phpmailer.php';
        require_once ABSPATH . WPINC . '/PHPMailer/PHPMailer.php';

        /**
         * Helper Functions.
         */
        require_once plugin_dir_path(dirname(__FILE__)) . 'includes/functions/autoloader.php';


        $this->loader = new Pages_Checker_Loader();
    }

    /**
     * Register all of the hooks related to the admin area functionality
     * of the plugin.
     *
     * @since    1.0.0
     */
    private function define_admin_hooks()
    {
        $plugin_admin = new Pages_Checker_Admin($this->get_Pages_Checker(), $this->get_version());

        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_styles');
        $this->loader->add_action('admin_enqueue_scripts', $plugin_admin, 'enqueue_scripts');
        $this->loader->add_action('admin_menu', $plugin_admin, 'register_pgch_dashboard');
        $this->loader->add_action('admin_menu', $plugin_admin, 'register_pgch_settings_page');
        $this->loader->add_action('admin_init', $plugin_admin, 'register_pgch_settings');
        $this->loader->add_action('page_checker_send_mail_camp', $plugin_admin, 'camp_send_mail', 10, 7);
        $this->loader->add_action('page_checker_follow_flag_camp', $plugin_admin, 'camp_followUp_flag', 10, 3);
        $this->loader->add_action('page_checker_follow_up_camp', $plugin_admin, 'camp_followUp_mail', 10, 10);
        $this->loader->add_action('admin_notices', $plugin_admin, 'admin_notices');
        $this->loader->add_action('wp_trash_post', $plugin_admin, 'delete_cpt');
        $this->loader->add_filter('admin_body_class', $plugin_admin, 'camp_classes');
    }

    /**
     * Run the loader to execute all of the hooks with WordPress.
     *
     * @since    1.0.0
     */
    public function run()
    {
        $this->loader->run();
    }

    /**
     * The name of the plugin used to uniquely identify it within the context of
     * WordPress and to define internationalization functionality.
     *
     * @since     1.0.0
     *
     * @return string the name of the plugin
     */
    public function get_Pages_Checker()
    {
        return $this->Pages_Checker;
    }

    /**
     * The reference to the class that orchestrates the hooks with the plugin.
     *
     * @since     1.0.0
     *
     * @return Pages_Checker_Loader orchestrates the hooks of the plugin
     */
    public function get_loader()
    {
        return $this->loader;
    }

    /**
     * Retrieve the version number of the plugin.
     *
     * @since     1.0.0
     *
     * @return string the version number of the plugin
     */
    public function get_version()
    {
        return $this->version;
    }
}
