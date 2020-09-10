<?php
defined('ABSPATH') or die('You can\'t access this file.');

if (!function_exists('pgch_post_edit_form_tag')) {
    add_action('post_edit_form_tag', 'pgch_post_edit_form_tag');

    function pgch_post_edit_form_tag($post)
    {
        if ($post->post_type === 'pgch_campaign')
            echo ' enctype="multipart/form-data"';
    }
}

if (!function_exists('pgch_upload_dir')) {
    function pgch_upload_dir($dirs)
    {
        $dirs['subdir'] = '/excel_uploads';
        $dirs['path'] = $dirs['basedir'] . '/excel_uploads';
        $dirs['url'] = $dirs['baseurl'] . '/excel_uploads';

        return $dirs;
    }
}


if (!function_exists('pgch_abs_path_to_url')) {
    function pgch_abs_path_to_url($path = '')
    {
        $url = str_replace(
            wp_normalize_path(untrailingslashit(ABSPATH)),
            site_url(),
            wp_normalize_path($path)
        );
        return esc_url_raw($url);
    }
}


/**
 * pgch_cpt_status
 * function to check if the current page is a post edit page
 *
 *
 *
 * @param  string  $new_edit what page to check for accepts new - new post page ,edit - edit post page, null for either
 * @return boolean
 */

if (!function_exists('pgch_cpt_status')) {
    function pgch_cpt_status($new_edit = null)
    {
        global $pagenow;
        //make sure we are on the backend
        if (!is_admin()) return false;


        if ($new_edit == "edit")
            return in_array($pagenow, array('post.php',));
        elseif ($new_edit == "new") //check for new post page
            return in_array($pagenow, array('post-new.php'));
        else //check for either new or edit
            return in_array($pagenow, array('post.php', 'post-new.php'));
    }
}

/**
 * pgch_addhttp
 * function to return http or https to an input url
 *
 *
 *
 * @param  string  $url url to check it's protocol
 * @param  string  $s whither to add 's' to http or not
 * @return string
 */

if (!function_exists('pgch_addhttp')) {
    function pgch_addhttp($url, $s = 's')
    {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url)) {
            $url = "http$s://" . $url;
        }
        return $url;
    }
}


if (!function_exists('pgch_run_campaign')) {
    function pgch_run_campaign($campaign_id)
    {
        update_post_meta(
            $campaign_id,
            '_sched_running_meta',
            'running'
        );
        $counter_email = 0;
        $counter_f_email = 0;
        $process_id = get_post_meta($campaign_id, '_process_meta', true);
        $send_email_id = get_post_meta($process_id, '_send_email_meta', true);
        $send_email = pgch_get_email_settings($send_email_id);
        $send_template_id = get_post_meta($process_id, '_send_template_meta', true);
        $send_template = get_post_field('post_content', $send_template_id);
        $follow_email_id = get_post_meta($process_id, '_follow_email_meta', true);
        $follow_email = pgch_get_email_settings($follow_email_id);
        $followup_template_ids = array(
            'not_open' => get_post_meta($process_id, '_not_opened_template_meta', true),
            'only_open' => get_post_meta($process_id, '_opened_template_meta', true),
            'answered' => get_post_meta($process_id, '_answered_template_meta', true)
        );
        $followup_template = array(
            'not_open' => get_post_field('post_content', $followup_template_ids['not_open']),
            'only_open' => get_post_field('post_content', $followup_template_ids['only_open'])
        );
        if ('Nothing' != $followup_template_ids['answered']) {
            $followup_template['answered'] = get_post_field('post_content', $followup_template_ids['answered']);
        }
        $subject_email = get_post_meta($campaign_id, '_email_subject_meta', true);
        $subject_follow = get_post_meta($campaign_id, '_follow_subject_meta', true);
        $max_emails = (int) get_post_meta($campaign_id, '_email_max_meta', true);
        $max_f_emails = (int) get_post_meta($campaign_id, '_follow_max_meta', true);
        $start_time = strtotime(get_post_meta($campaign_id, '_working_hours_start_meta', true));
        $end_time = strtotime(get_post_meta($campaign_id, '_working_hours_end_meta', true));
        $current_time = time();
        $upload_type = get_post_meta($campaign_id, '_upload_type_meta', true);
        $upload_source = get_post_meta($campaign_id, '_upload_source_meta', true);
        $salesforce_sent = FALSE;
        $salesforce_sent_fields = FALSE;
        $random_interval = array(
            (int) get_post_meta($process_id, '_email_interval_number_meta', true),
            (int) get_post_meta($process_id, '_email_interval_number2_meta', true)
        );


        if ($random_interval[0] > $random_interval[1])
            $random_interval[1] += $random_interval[0];

        $follow_type = get_post_meta($process_id, '_follow_up_type_meta', true);

        switch ($follow_type) {
            case 'seconds':
                $wait = 1;
                break;
            case 'days':
                $wait = 86400;
                break;
            case 'hours':
                $wait = 3600;
                break;
            case 'minutes':
                $wait = 60;
                break;
            default:
                $wait = 86400;
                break;
        }

        $follow_number = (int) get_post_meta($process_id, '_follow_up_number_meta', true) * $wait;

        if ($end_time < $start_time) {
            $end_time += 86400;
        }

        if ($current_time > $start_time && $current_time < $end_time) {
            $run_time = $current_time + 60;
        } elseif ($current_time < $start_time) {
            $run_time = $start_time;
        } elseif ($current_time > $end_time) {
            $start_time += 86400;
            $run_time = $start_time;
            $end_time += 86400;
        }

        $start_time_e = $start_time;
        $start_time_f = $start_time;

        $end_time_email = $end_time;
        $end_time_f_email = $end_time;

        $tracker_fields = array(
            'temp_id' => $send_template_id,
            'proc_id' => $process_id,
            'camp_id' => $campaign_id
        );
        if ($upload_type === 'CSV') {
            $to_replace = array('{{Email}}', '{{Website}}', '{{Response_code}}', '{{Response_message}}');
            $excelFile = $upload_source;
            $spreadsheet = \PhpOffice\PhpSpreadsheet\IOFactory::load($excelFile);
            $worksheet = $spreadsheet->getActiveSheet();
            $row_iterator = $worksheet->getRowIterator();
        } elseif ($upload_type === 'Salesforce') {
            $salesforce_api = pgch_sf_get_token(
                get_option('pgch_sfkey'),
                get_option('pgch_sfsecret'),
                get_option('pgch_sfuser'),
                get_option('pgch_sfpass')
            );
            $salesforce_fields = array(
                'ID',
                'AnnualRevenue',
                'Company',
                'Description',
                'Email',
                'Fax',
                'Industry',
                'Status',
                'Phone',
                'Title',
                'Website',
                'MobilePhone',
                'Salutation',
                'LastName',
                'FirstName',
                'City',
                'Country',
                'State',
                'Street',
                'PostalCode'
            );
            $to_replace = array();
            foreach ($salesforce_fields as $field) {
                $to_replace[] = "{{{$field}}}";
            }
            $salesforce_where = array('Status' => urlencode("'$upload_source'"));
            $row_iterator = pgch_sf_get_items(
                $salesforce_api['access_token'],
                $salesforce_api['instance_url'],
                $salesforce_fields,
                'Lead',
                $salesforce_where
            )["records"];
            $salesforce_sent = get_post_meta($campaign_id, '_salesforce_sent_meta', true);
            $salesforce_sent_fields = array(
                $salesforce_api['access_token'],
                $salesforce_api['instance_url'],
                'Lead'
            );
        }

        $follow_run_time = $run_time + $follow_number;

        $last_element = end($row_iterator);
        if ($upload_type === 'Salesforce') {
            unset($last_element['attributes']);
            unset($last_element['Id']);
        } elseif ($upload_type === 'CSV') {
            $last_element = (int) $last_element;
        }
        $last_element_flag = FALSE;

        foreach ($row_iterator as $row) {
            $row_index = FALSE;
            $row_id = FALSE;
            if ($upload_type === 'CSV') {
                if ($row->getRowIndex() == 1)
                    continue;
                $row_index = $row->getRowIndex();
                $email_add = $worksheet->getCellByColumnAndRow(1, $row_index)->getValue();
                $website = str_replace('$1', $worksheet->getCellByColumnAndRow(2, $row_index)->getValue(), get_option('pgch_tool'));
            } elseif ($upload_type === 'Salesforce') {
                $row_id = $row['Id'];
                unset($row['attributes']);
                unset($row['Id']);
                $email_add = $row['Email'];
                $website = str_replace('$1', $row['Website'], get_option('pgch_tool'));
            }
            if (!empty($websie)) {
                continue;
            }
            $tracker_fields['message_type'] = 'first_email';
            $tracker_fields['email'] = $email_add;
            unset($tracker_fields['id']);
            $tracker_fields['id'] = pgch_database_insert_item($tracker_fields);


            $response = wp_remote_get(pgch_addhttp($website));
            $response_code = wp_remote_retrieve_response_code($response);
            if ($response_code == 0) {
                $response = wp_remote_get(pgch_addhttp($website, ''));
                $response_code = wp_remote_retrieve_response_code($response);
            }
            $response_message = wp_remote_retrieve_response_message($response);

            if ($upload_type === 'CSV') {
                $replace_with = array($email_add, $website, $response_code, $response_message);
            } elseif ($upload_type === 'Salesforce') {
                $replace_with = $row;
            }

            $camp_send_hook = "page_checker_send_mail_camp";
            // add_action( $camp_send_hook,  'pgch_send_mail', 10, 7);

            $camp_follow_flag_hook = "page_checker_follow_flag_camp";
            // add_action( $camp_follow_flag_hook,  'pgch_followUp_flag', 10, 3);

            $camp_follow_hook = "page_checker_follow_up_camp";
            // add_action( $camp_follow_hook,  'pgch_followUp_mail', 10, 10);

            $send_message = str_ireplace($to_replace, $replace_with, $send_template);

            if ($last_element === $row || $last_element === $row_index) {
                $last_element_flag = TRUE;
            }

            $r = wp_schedule_single_event(
                $run_time,
                $camp_send_hook,
                array(
                    $send_message,
                    $subject_email,
                    $send_email,
                    $tracker_fields,
                    $salesforce_sent_fields,
                    $salesforce_sent,
                    $row_id
                )
            );
            $f_flag = wp_schedule_single_event(
                $run_time + $follow_number,
                $camp_follow_flag_hook,
                array(
                    $subject_email,
                    $send_email,
                    $tracker_fields
                )
            );
            $tracker_fields['message_type'] = 'follow_up';
            $f = wp_schedule_single_event(
                $follow_run_time,
                $camp_follow_hook,
                array(
                    $followup_template_ids,
                    $followup_template,
                    $subject_email,
                    $subject_follow,
                    $to_replace,
                    $replace_with,
                    $send_email,
                    $follow_email,
                    $tracker_fields,
                    $last_element_flag
                )
            );

            $rand_interval = rand($random_interval[0], $random_interval[1]);
            $run_time +=  $rand_interval;
            $follow_run_time += $rand_interval;

            $counter_email += 1;
            $counter_f_email += 1;

            if (date('d H', $run_time) > date('d H', $end_time_email)) {
                $start_time_e += 86400;
                $run_time = $start_time_e;
                $end_time_email += 86400;
                $counter_email = 0;
            }

            if (date('d H', $follow_run_time) > date('d H', $end_time_f_email)) {
                $start_time_f += 86400;
                $follow_run_time = $start_time_f + $follow_number;
                $end_time_f_email += 86400;
                $counter_f_email = 0;
            }

            if ($run_time < $start_time + $follow_number)
                $max_emails_edited = $max_emails + $max_f_emails;
            else
                $max_emails_edited = $max_emails;

            if ($counter_email >= $max_emails_edited) {
                $run_time += 86400;
                $counter_email = 0;
            }
            if ($counter_f_email >= $max_f_emails) {
                $follow_run_time += 86400;
                $counter_f_email = 0;
            }
        }
    }
}
