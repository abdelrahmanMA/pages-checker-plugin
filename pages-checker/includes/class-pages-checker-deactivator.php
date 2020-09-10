<?php

/**
 * Fired during plugin deactivation
 *
 * @link       https://abdelrahmanma.com
 * @since      1.0.0
 *
 * @package    Pages_Checker
 * @subpackage Pages_Checker/includes
 */

/**
 * Fired during plugin deactivation.
 *
 * This class defines all code necessary to run during the plugin's deactivation.
 *
 * @since      1.0.0
 * @package    Pages_Checker
 * @subpackage Pages_Checker/includes
 * @author     Abdelrahman Muhammad <contact@abdelrahmanma.com>
 */
class Pages_Checker_Deactivator
{

	/**
	 * Short Description. (use period)
	 *
	 * Long Description.
	 *
	 * @since    1.0.0
	 */
	public static function deactivate()
	{
		$query = new WP_Query(array(
			'post_type' => 'pgch_campaign',
			'post_status' => 'any',
			'posts_per_page' => -1,
		));

		while ($query->have_posts()) {
			$query->the_post();
			$camp_id = get_the_ID();
			pgch_stop_campaign($camp_id);
		}
		flush_rewrite_rules();
	}
}
