<?php
if (!function_exists('pgch_campaign_operations')) {
    add_action('wp_ajax_pgch_campaign_operations', 'pgch_campaign_operations');
    function pgch_campaign_operations()
    {
        if (isset($_POST['operation'])) {
            $operation = $_POST['operation'];
        } else {
            $operation = FALSE;
        }
        if (isset($_POST['camp_id'])) {
            $camp_id = $_POST['camp_id'];
        } else {
            $camp_id = FALSE;
        }
        if ($operation === 'restart' && $camp_id) {
            pgch_stop_campaign($camp_id);
            pgch_run_campaign($camp_id);
        } elseif ($operation === 'stop' && $camp_id) {
            pgch_stop_campaign($camp_id);
        }
    }
}
if (!function_exists('pgch_stop_campaign')) {
    function pgch_stop_campaign($camp_id)
    {
        $camp_send_hook = "page_checker_send_mail_camp";
        $camp_follow_flag_hook = "page_checker_follow_flag_camp";
        $camp_follow_hook = "page_checker_follow_up_camp";

        $crons = _get_cron_array();
        foreach ($crons as $timestamp => $cronhooks) {
            foreach ($cronhooks as $hook => $keys) {
                if ($hook === $camp_send_hook || $hook === $camp_follow_flag_hook || $hook === $camp_follow_hook) {
                    foreach ($keys as $k => $v) {
                        if ($hook === $camp_send_hook) {
                            $n = 3;
                        } elseif ($hook === $camp_follow_flag_hook) {
                            $n = 2;
                        } elseif ($hook === $camp_follow_hook) {
                            $n = 8;
                        }
                        if ($v['args'][$n]['camp_id'] == $camp_id) {
                            wp_unschedule_event($timestamp, $hook, $v['args']);
                        }
                    }
                }
            }
        }
        update_post_meta(
            $camp_id,
            '_sched_running_meta',
            'not_running'
        );
    }
}
