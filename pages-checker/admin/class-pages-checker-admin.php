<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @see       http://abdelrahmanma.com
 * @since      1.0.0
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @author     Abdelrahman Muhammad <contact@abdelrahmanma.com>
 */
class Pages_Checker_Admin
{
    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     *
     * @var string the ID of this plugin
     */
    private $Pages_Checker;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     *
     * @var string the current version of this plugin
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     *
     * @param string $Pages_Checker the name of this plugin
     * @param string $version       the version of this plugin
     */
    public function __construct($Pages_Checker, $version)
    {
        $this->Pages_Checker = $Pages_Checker;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_styles($hook)
    {
        /*
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Pages_Checker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Pages_Checker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        $cpts = array('pgch_email', 'pgch_process', 'pgch_campaign', 'pgch_analytic');
        global $post_type;
        if (!in_array($post_type, $cpts) && $hook != 'pages-checker_page_pages-checker-settings') {
            return;
        }

        // wp_enqueue_style($this->Pages_Checker, plugin_dir_url(__FILE__).'css/bootstrap-grid.min.css', array(), $this->version, 'all');
        // wp_enqueue_style($this->Pages_Checker, plugin_dir_url(__FILE__).'css/bootstrap-reboot.min.css', array(), $this->version, 'all');
        wp_enqueue_style('bootstrap', plugin_dir_url(__FILE__) . 'css/bootstrap.min.css', array(), $this->version, 'all');
        wp_enqueue_style($this->Pages_Checker, plugin_dir_url(__FILE__) . 'css/pages-checker-admin.css', array(), $this->version, 'all');
        wp_enqueue_style('font-awesome', 'https://use.fontawesome.com/releases/v5.7.0/css/all.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the admin area.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts($hook)
    {
        /*
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Pages_Checker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Pages_Checker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */
        $cpts = array('pgch_email', 'pgch_process', 'pgch_campaign', 'pgch_analytic');
        global $post_type;
        if (!in_array($post_type, $cpts) && $hook != 'pages-checker_page_pages-checker-settings') {
            return;
        }

        // wp_enqueue_script($this->Pages_Checker, plugin_dir_url(__FILE__).'js/bootstrap.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script('bootstrap', plugin_dir_url(__FILE__) . 'js/bootstrap.bundle.min.js', array('jquery'), $this->version, false);
        // wp_enqueue_script($this->Pages_Checker, plugin_dir_url(__FILE__).'js/popper.min.js', array('jquery'), $this->version, false);
        wp_enqueue_script($this->Pages_Checker, plugin_dir_url(__FILE__) . 'js/pages-checker-admin.js', array('jquery'), $this->version, false);
    }

    public function register_pgch_dashboard()
    {
        add_menu_page('Pages Checker', 'Pages Checker', 'manage_options', 'pages-checker-dashboard', '', 'dashicons-admin-page', 28);
    }

    public function register_pgch_settings_page()
    {
        add_submenu_page('pages-checker-dashboard', 'Pages Checker Settings', 'Settings', 'manage_options', 'pages-checker-settings', array($this, 'pgch_settings_page'));
    }

    public function register_pgch_settings()
    {
        register_setting('pgch_settings', 'pgch_sfuser');
        register_setting('pgch_settings', 'pgch_sfpass');
        register_setting('pgch_settings', 'pgch_sfkey');
        register_setting('pgch_settings', 'pgch_sfsecret');
        register_setting('pgch_settings', 'pgch_tool', array('default' => 'https://chrismangunza.com/review/$1'));
        register_setting('pgch_settings', 'pgch_smtp', array('type' => 'bool', 'default' => True));
        register_setting('pgch_settingsconn', 'pgch_sfconnect', array('type' => 'bool', 'default' => FALSE));
    }
    public function register_pgch_custom_hooks()
    {

        do_action('pgch_custom_hooks');
    }

    public function pgch_settings_page()
    {
        ?>
        <div class="wrap">
            <h1><?php _e('Pages Checker Settings', 'pages-checker'); ?></h1>
            <br>
            <h3><?php _e('Salesforce API', 'pages-checker'); ?></h3>
            <form id="pgch_form" autocomplete="off" method="post" action="options.php">
                <?php settings_fields('pgch_settings'); ?>
                <?php do_settings_sections('pgch_settings'); ?>
                <table class="form-table">
                    <tr valign="top">
                        <th scope="row">Username</th>
                        <td><input class="form-control col-6" type="text" id="sfuser" name="pgch_sfuser" value="<?php echo esc_attr(get_option('pgch_sfuser')); ?>" /></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">password</th>
                        <td><input autocomplete="new-password" class="form-control col-6" type="password" id="sfpass" name="pgch_sfpass" value="<?php echo esc_attr(get_option('pgch_sfpass')); ?>" /></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">Customer Key</th>
                        <td><input class="form-control col-6" type="text" id="sfkey" name="pgch_sfkey" value="<?php echo esc_attr(get_option('pgch_sfkey')); ?>" /></td>
                    </tr>

                    <tr valign="top">
                        <th scope="row">Customer Secret</th>
                        <td><input class="form-control col-6" type="password" id="sfsecret" name="pgch_sfsecret" value="<?php echo esc_attr(get_option('pgch_sfsecret')); ?>" /></td>

                    <tr valign="top">
                        <th scope="row">Tool's URL<small><br>use "$1" to indicate each website</small></th>
                        <td><input class="form-control col-6" type="text" id="sftool" name="pgch_tool" value="<?php echo esc_attr(get_option('pgch_tool')); ?>" /></td>
                    </tr>

                    </tr>
                    <tr valign="top">
                        <th scope="row">Use SMTP Settings <small><br>Instead of normal mail function</small></th>
                        <td><input class="form-control col-6" type="checkbox" id="pgch_smtp" name="pgch_smtp" <?= checked(get_option('pgch_smtp'), 'on'); ?>" /></td>
                    </tr>
                </table>
                <div class="form-group">
                    <button id="test_api_credentials" class="btn btn-success">Test Credentials</button>
                </div>
                <?php submit_button(NULL, 'primary', 'subm'); ?>
            </form>
        </div>
        <script>
            (function($) {
                function check_cred() {
                    var data = {
                        'action': 'check_salesforce_cred',
                        'key': $('#sfkey').val(),
                        'secret': $('#sfsecret').val(),
                        'user': $('#sfuser').val(),
                        'pass': $('#sfpass').val()
                    };
                    return jQuery.post("<?= admin_url('admin-ajax.php'); ?>", data);
                }
                $(document).ready(function() {

                    $('#test_api_credentials').on('click', function(e) {
                        e.preventDefault();
                        $.when(check_cred()).done(function(response) {
                            alert(response);
                        });
                    });
                    $('#pgch_form').one('submit', function(e) {
                        e.preventDefault();
                        $.when(check_cred()).done(function(response) {
                            response = (response == 'Connection was Successful');
                            var data = {
                                'action': 'save_salesforce_conn',
                                'conn': response
                            };
                            jQuery.post("<?= admin_url('admin-ajax.php'); ?>", data, function(res) {
                                $('#pgch_form').submit();
                            });

                        });
                    });
                });
            })(jQuery);
        </script>
        <?php
            }
            public function admin_notices()
            {
                global $post_type;
                $cpts = array('pgch_email', 'pgch_process', 'pgch_campaign', 'pgch_analytic');
                if (in_array($post_type, $cpts) && !get_option('pgch_sfconnect', FALSE)) {
                    ?>
            <div class="notice notice-warning is-dismissible">
                <p>You didn't setup Salesforce API, or you have Wrong Credentials.</p>
            </div>
<?php
        }
    }
    public function delete_cpt($post_id)
    {
        global $post_type;
        if ($post_type != 'pgch_campaign') return;
        pgch_stop_campaign($post_id);
    }
    public function camp_classes($classes)
    {
        global $post;
        if (isset($post)) {
            if ('pgch_campaign' === $post->post_type) {
                $sched_running = get_post_meta($post->ID, '_sched_running_meta', true);
                $classes .= "camp_$sched_running";
            }
        }
        return $classes;
    }
    public function camp_send_mail($send_message, $subject, $send_email, $tracker_fields, $salesforce_sent_fields, $salesforce_sent, $lead_id)
    {
        if (pgch_prepare_and_send_email($send_message, $subject, $send_email, $tracker_fields)) {
            pgch_database_update_item(array('sent' => TRUE), $tracker_fields);
            if ($salesforce_sent_fields) {
                $temp_sf = $salesforce_sent_fields;
                pgch_sf_update_item($temp_sf[0], $temp_sf[1], $lead_id, $temp_sf[2], array('Status' => $salesforce_sent));
            }
        }
    }

    public function camp_followUp_mail($followup_template_ids, $followup_template, $subject, $follow_subject, $to_replace, $replace_with, $send_email, $follow_email, $tracker_fields, $last_element_flag)
    {
        $opened = (bool) pgch_database_get_items('opened', $tracker_fields)[0]->opened;;
        $toEmail = $tracker_fields['email'];
        $criteria = "FROM {$toEmail} SUBJECT " . '"' . $subject . '"';
        if (!pgch_check_inbox($criteria, $send_email)) {
            if ($opened) {
                $followup_template = $followup_template['only_open'];
                $followup_template_ids = (int) $followup_template_ids['only_open'];
            } else {
                $followup_template = $followup_template['not_open'];
                $followup_template_ids = (int) $followup_template_ids['not_open'];
            }
            $follow_message = str_ireplace($to_replace, $replace_with, $followup_template);
            if (pgch_prepare_and_send_email($follow_message, $follow_subject, $follow_email, $tracker_fields)) {
                pgch_database_update_item(array('follow_sent' => TRUE), $tracker_fields);
            }
            pgch_database_update_item(array('follow_temp_id' => $followup_template_ids), $tracker_fields);
        } else {
            if (isset($followup_template['answered'])) {
                $follow_message = str_ireplace($to_replace, $replace_with, $followup_template['answered']);
                if (pgch_prepare_and_send_email($follow_message, $follow_subject, $follow_email, $tracker_fields)) {
                    // pgch_database_update_item(array('answered_sent' => TRUE), $tracker_fields);
                }
            }
            pgch_database_update_item(array('answered' => TRUE), $tracker_fields);
            pgch_database_update_item(array('follow_flag' => FALSE), $tracker_fields);
        }
        if ($last_element_flag) {
            update_post_meta((int) $tracker_fields['camp_id'], '_sched_running_meta', 'not_running');
        }
    }

    public function camp_followUp_flag($subject, $send_email, $tracker_fields)
    {
        $toEmail = $tracker_fields['email'];
        $criteria = "FROM {$toEmail} SUBJECT " . '"' . $subject . '"';
        if (!pgch_check_inbox($criteria, $send_email)) {
            pgch_database_update_item(array('follow_flag' => TRUE), $tracker_fields);
        }
    }
}
