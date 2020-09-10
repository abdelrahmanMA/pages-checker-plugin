<?php

/**
 * Fired during plugin activation
 *
 * @link       https://abdelrahmanma.com
 * @since      1.0.0
 *
 * @package    Pages_Checker
 * @subpackage Pages_Checker/includes
 */

/**
 * Fired during plugin activation.
 *
 * This class defines all code necessary to run during the plugin's activation.
 *
 * @since      1.0.0
 * @package    Pages_Checker
 * @subpackage Pages_Checker/includes
 * @author     Abdelrahman Muhammad <contact@abdelrahmanma.com>
 */
class Pages_Checker_Activator
{

    /**
     * Short Description. (use period)
     *
     * Long Description.
     *
     * @since    1.0.0
     */

    public static function activate()
    {
        Pages_Checker_Activator::create_stats_table();
        flush_rewrite_rules();
    }

    public static function create_stats_table()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'pgch_statistics';
        $charset_collate = $wpdb->get_charset_collate();

        $sql = "CREATE TABLE $table_name (
                `id` INT NOT NULL AUTO_INCREMENT,
                `email` VARCHAR(320) NOT NULL,
                `camp_id` INT NOT NULL,
                `proc_id` INT NOT NULL,
                `temp_id` INT NOT NULL,
                `follow_temp_id` INT NOT NULL,
                `sent` TINYINT(1) NOT NULL DEFAULT 0,
                `opened` TINYINT(1) NOT NULL DEFAULT 0,
                `answered` TINYINT(1) NOT NULL DEFAULT 0,
                `follow_flag` TINYINT(1) NOT NULL DEFAULT 0,
                `follow_sent` TINYINT(1) NOT NULL DEFAULT 0,
                `follow_opened` TINYINT(1) NOT NULL DEFAULT 0,
                PRIMARY KEY (id),
                UNIQUE INDEX id_UNIQUE (id ASC)
            ) $charset_collate;";
        //UNIQUE INDEX all_uni (email ASC, camp_id ASC, proc_id ASC, temp_id ASC, follow_temp_id ASC, `sent` ASC, opened ASC, answered ASC, follow_flag ASC, follow_sent ASC, follow_opened ASC)
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
}
