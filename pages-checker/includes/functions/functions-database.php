<?php
if (!function_exists('pgch_database_insert_item')) {
    function pgch_database_insert_item($data)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'pgch_statistics';
        unset($data['message_type']);
        if ($wpdb->insert($table_name, $data)) {
            return $wpdb->insert_id;
        }
        return FALSE;
    }
}
if (!function_exists('pgch_database_get_items')) {
    function pgch_database_get_items($fields, $where)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'pgch_statistics';

        unset($where['message_type']);
        $where_clause = 'WHERE ';

        if (is_array($where)) {
            foreach ($where as $field => $value) {
                if ($field === 'email')
                    $value = "'$value'";
                $where_clause .= "$field = $value AND ";
            }
            if (!empty($where_clause)) {
                $where_clause = substr_replace($where_clause, '', -5);
            }
        } elseif (is_string($where)) {
            $where_clause = $where;
        } else {
            $where_clause = '';
        }

        if (is_array($fields))
            $fields = implode(',', $fields);

        $query = "Select $fields FROM $table_name $where_clause";

        return $wpdb->get_results($query);
    }
}

if (!function_exists('pgch_database_update_item')) {
    function pgch_database_update_item($data, $where, $format = null, $where_format = null)
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'pgch_statistics';
        unset($where['message_type']);
        if ($wpdb->update($table_name, $data, $where, $format, $where_format)) {
            return $wpdb->insert_id;
        }
        return FALSE;
    }
}

if (!function_exists('pgch_get_stats')) {
    function pgch_get_stats($stats_type, $post_id, $return_type = '')
    {

        $all_emails = 0;
        $sent_emails = 0;
        $opened_emails = 0;
        $answered_emails = 0;

        $all_f_emails = 0;
        $sent_f_emails = 0;
        $opened_f_emails = 0;

        $WHERE_CLAUSE = '';

        if ($stats_type === 'campaign') {
            $WHERE_CLAUSE = array('camp_id' => $post_id);
        } elseif ($stats_type === 'process') {
            $WHERE_CLAUSE = array('proc_id' => $post_id);
        } elseif ($stats_type === 'template') {
            $WHERE_CLAUSE = array('temp_id' => $post_id);
        }

        $analytics = pgch_database_get_items('*', $WHERE_CLAUSE);

        foreach ($analytics as $analytic) {
            $all_emails += 1;
            if ($analytic->sent) {
                $sent_emails += 1;
            }
            if ($analytic->opened) {
                $opened_emails += 1;
            }
            if ($analytic->answered) {
                $answered_emails += 1;
            }
            if ($analytic->follow_flag) {
                $all_f_emails += 1;
            }
            if ($analytic->follow_sent) {
                $sent_f_emails += 1;
            }
            if ($analytic->follow_opened) {
                $opened_f_emails += 1;
            }
        }

        if ($stats_type === 'template') {

            $all_f_emails = 0;
            $sent_f_emails = 0;
            $opened_f_emails = 0;

            $WHERE_CLAUSE = array('follow_temp_id' => $post_id);

            $analytics = pgch_database_get_items('*', $WHERE_CLAUSE);

            foreach ($analytics as $analytic) {
                if ($analytic->follow_flag) {
                    $all_f_emails += 1;
                }
                if ($analytic->follow_sent) {
                    $sent_f_emails += 1;
                }
                if ($analytic->follow_opened) {
                    $opened_f_emails += 1;
                }
            }
        }
        if ($return_type === 'percentage') {
            if ($all_emails === 0) {
                $temp_all_emails = 1;
            } else {
                $temp_all_emails = $all_emails;
            }

            if ($all_f_emails === 0) {
                $temp_all_f_emails = 1;
            } else {
                $temp_all_f_emails = $all_f_emails;
            }

            $sent_emails = round($sent_emails * 100 / $temp_all_emails, 1);
            $opened_emails = round($opened_emails * 100 / $temp_all_emails, 1);
            $answered_emails = round($answered_emails * 100 / $temp_all_emails, 1);

            $sent_f_emails = round($sent_f_emails * 100 / $temp_all_f_emails, 1);
            $opened_f_emails = round($opened_f_emails * 100 / $temp_all_f_emails, 1);
        }

        return array(
            $all_emails,
            $sent_emails,
            $opened_emails,
            $answered_emails,
            $all_f_emails,
            $sent_f_emails,
            $opened_f_emails
        );
    }
}
