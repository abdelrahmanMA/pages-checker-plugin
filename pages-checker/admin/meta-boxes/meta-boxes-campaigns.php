<?php
defined('ABSPATH') or die('You can\'t access this file.');

if (!function_exists('pgch_campaigns_cpt_custom_fields')) {
    add_action('add_meta_boxes', 'pgch_campaigns_cpt_custom_fields');
    function pgch_campaigns_cpt_custom_fields($post)
    {
        if (pgch_cpt_status('edit')) {
            add_meta_box('pgch_campaigns_quick_overview_meta', 'Quick Overview', 'pgch_campaigns_quick_overview_meta', 'pgch_campaign', 'normal', 'low');
            add_meta_box('pgch_campaigns_operations_meta', 'Campaign Operations', 'pgch_campaigns_operations_meta', 'pgch_campaign', 'side', 'low');
        }
        add_meta_box('pgch_campaigns_settings_meta', 'Campaign Settings', 'pgch_campaigns_settings_meta', 'pgch_campaign', 'normal', 'low');
    }
    function pgch_campaigns_quick_overview_meta($post)
    {
        $analytics = pgch_get_stats('campaign', $post->ID, 'percentage');

        $sent_emails = $analytics[1];
        $follow_emails = $analytics[5];
        ?>
        <div class="row mb-4 mt-4">
            <div class="col-lg-6 mb-4">
                <div class="bg-white rounded-lg p-5 shadow">
                    <h2 class="h6 font-weight-bold text-center mb-4">Emails Sent</h2>

                    <!-- Progress bar 1 -->
                    <div class="progress mx-auto" data-value='<?= $sent_emails; ?>'>
                        <span class="progress-left">
                            <span class="progress-bar border-success"></span>
                        </span>
                        <span class="progress-right">
                            <span class="progress-bar border-success"></span>
                        </span>
                        <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                            <div class="h2 font-weight-bold"><?= $sent_emails; ?><sup class="small">%</sup></div>
                        </div>
                    </div>
                    <!-- END -->
                </div>
            </div>
            <div class="col-lg-6 mb-4">
                <div class="bg-white rounded-lg p-5 shadow">
                    <h2 class="h6 font-weight-bold text-center mb-4">Follow Up Emails</h2>

                    <!-- Progress bar 2 -->
                    <div class="progress mx-auto" data-value='<?= $follow_emails; ?>'>
                        <span class="progress-left">
                            <span class="progress-bar border-warning"></span>
                        </span>
                        <span class="progress-right">
                            <span class="progress-bar border-warning"></span>
                        </span>
                        <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                            <div class="h2 font-weight-bold"><?= $follow_emails; ?><sup class="small">%</sup></div>
                        </div>
                    </div>
                    <!-- END -->
                </div>
            </div>
        </div>
    <?php
        }
        function pgch_campaigns_settings_meta($post)
        {
            $email_subject = get_post_meta($post->ID, '_email_subject_meta', true);
            $follow_subject = get_post_meta($post->ID, '_follow_subject_meta', true);
            $email_max = get_post_meta($post->ID, '_email_max_meta', true);
            $follow_max = get_post_meta($post->ID, '_follow_max_meta', true);
            $working_hours_start = get_post_meta($post->ID, '_working_hours_start_meta', true);
            $working_hours_end = get_post_meta($post->ID, '_working_hours_end_meta', true);
            $upload_type = get_post_meta($post->ID, '_upload_type_meta', true);
            if (empty($upload_type)) {
                $upload_type = 'CSV';
            }
            $upload_source = get_post_meta($post->ID, '_upload_source_meta', true);
            $salesforce_sent = get_post_meta($post->ID, '_salesforce_sent_meta', true);
            $salesforce_open = get_post_meta($post->ID, '_salesforce_open_meta', true);
            $salesforce_answered = get_post_meta($post->ID, '_salesforce_answered_meta', true);
            $process = get_post_meta($post->ID, '_process_meta', true);
            ?>
        <div class="row mb-4 mt-4">
            <div class="col-4">
                <label for="upload_type">Email Addresses Source
                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Which source to get emails from">
                        <i class="fas fa-question-circle"></i>
                    </span>
                </label>
                <div id="upload_type">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="mr-2">
                                <input type="radio" id="csv_upload" name="upload_type" value="CSV" <?= checked($upload_type, 'CSV'); ?>>
                            </div>
                        </div>
                        <label for="csv_upload" class="mb-0">CSV/Excel</label>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="mr-2">
                                <input type="radio" id="salesforce_upload" name="upload_type" value="Salesforce" <?= checked($upload_type, 'Salesforce'); ?>>
                            </div>
                        </div>
                        <label for="salesforce_upload" class="mb-0">Salesforce</label>
                    </div>
                </div>
            </div>
            <div class="col-6 offset-2 d-flex">
                <div id="CSV" class="justify-content-center align-self-center col-12">
                    <div class="input-group">
                        <?php if (empty($upload_source)) { ?>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="CSV_file" type="file" name="CSV_file" accept=".csv, .xls, .xlsx, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
                                <label class="custom-file-label" for="CSV_file" id="csv_file_label">Choose file</label>
                            </div>
                        <?php } else { ?>
                            <a href="<?= pgch_abs_path_to_url($upload_source); ?>"><label for="CSV_file"><?= basename($upload_source) ?></label></a>
                        <?php } ?>
                    </div>
                </div>
                <div id="Salesforce" class="justify-content-center align-self-center col-12">
                    <select class="form-control" id="Salesfore_file" name="Salesfore_file">
                        <option <?= selected($upload_source, 'Open - Not Contacted'); ?> value="Open - Not Contacted">Open - Not Contacted</option>
                        <option <?= selected($upload_source, 'Working - Contacted'); ?> value="Working - Contacted">Working - Contacted</option>
                    </select>
                </div>
            </div>
        </div>
        <div id="salesforce_advanced">
            <div class="dropdown-divider"></div>
            <div class="row mb-4 mt-4">
                <div class="form-group col-sm-12">
                    <label for="salesforce_open">When Email is Sent
                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="What process to move email to if the email is sent">
                            <i class="fas fa-question-circle"></i>
                        </span>
                    </label>
                    <select name="salesforce_sent" id="salesforce_sent" class="custom-select">
                        <option <?= selected($salesforce_sent, 'Working - Contacted'); ?> value="Working - Contacted">Working - Contacted</option>
                        <option <?= selected($salesforce_sent, 'Open - Not Contacted'); ?> value="Open - Not Contacted">Open - Not Contacted</option>
                    </select>
                </div>
                <!-- <div class="form-group col-sm-4">
            <label for="salesforce_open">If only opened
                <span class="d-inline-block" tabindex="0" data-toggle="tooltip"
                    title="What process to move email to if the email was opened but not answered">
                    <i class="fas fa-question-circle"></i>
                </span>
            </label>
            <select name="salesforce_open" id="salesforce_open" class="custom-select">
                <option value="Nothing">Nothing</option>
                <option <?= selected($salesforce_open, 'Open - Not Contacted'); ?> value="Open - Not Contacted">Open - Not Contacted</option>
                <option <?= selected($salesforce_open, 'Working - Contacted'); ?> value="Working - Contacted">Working - Contacted</option>
            </select>
        </div>
        <div class="form-group col-sm-4">
            <label for="salesforce_answered">If answered
                <span class="d-inline-block" tabindex="0" data-toggle="tooltip"
                    title="What process to move email to if the email was answered">
                    <i class="fas fa-question-circle"></i>
                </span>
            </label>
            <select required name="salesforce_answered" id="salesforce_answered" class="custom-select">
                <option value="Nothing">Nothing</option>
                <option <?= selected($salesforce_answered, 'Open - Not Contacted'); ?> value="Open - Not Contacted">Open - Not Contacted</option>
                <option <?= selected($salesforce_answered, 'Working - Contacted'); ?> value="Working - Contacted">Working - Contacted</option>
            </select>
        </div> -->
            </div>
        </div>
        <div class="dropdown-divider"></div>
        <div class="row mb-4 mt-4">
            <div class="form-group col-6">
                <label for="email_subject">Email Subject
                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Subject field of the first emails">
                        <i class="fas fa-question-circle"></i>
                    </span>
                </label>
                <input required id="email_subject" name="email_subject" class="form-control" value="<?= $email_subject; ?>" placeholder="Enter Email Subject" />
            </div>
            <div class="form-group col-6">
                <label for="follow_subject">Follow Up Email Subject
                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Subject field of the follow up emails">
                        <i class="fas fa-question-circle"></i>
                    </span>
                </label>
                <input required id="follow_subject" name="follow_subject" class="form-control" value="<?= $follow_subject; ?>" placeholder="Enter Follow Up Subject" />
            </div>
        </div>
        <div class="dropdown-divider"></div>
        <div class="row mb-4 mt-4">
            <div class="form-group col-6">
                <label for="email_max">Emails Per Day
                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Number of maximum emails to be sent per day not including follow up emails.">
                        <i class="fas fa-question-circle"></i>
                    </span>
                </label>
                <input id="follow_max" name="email_max" class="form-control" type="number" min="1" value="<?= $email_max; ?>" placeholder="Enter Maximum Emails Per Day" />
            </div>
            <div class="form-group col-6">
                <label for="follow_max">Follow Up Emails Per Day
                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Number of maximum emails to be sent per day not including follow up emails.">
                        <i class="fas fa-question-circle"></i>
                    </span>
                </label>
                <input id="follow_max" name="follow_max" class="form-control" type="number" min="1" value="<?= $follow_max; ?>" placeholder="Enter Maximum Follow Up Emails Per Day" />
            </div>
        </div>
        <div class="dropdown-divider"></div>
        <div class="row mb-4 mt-4">
            <div class="col-6">
                <label for="working_hours">Working Time
                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Interval of time to send emails and follow up emails within">
                        <i class="fas fa-question-circle"></i>
                    </span>
                    <br>
                    <small>Current time:</small> <small id="hours"><?= date('H'); ?></small>:<small id="minutes"><?= date('i'); ?></small> <small id="tt"><?= date('A'); ?></small>
                </label>
                <div class="row">
                    <div class="form-group col-5">
                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Interval Start Time">
                            <input id="working_hours_start" name="working_hours_start" class="form-control" type="time" value="<?= $working_hours_start; ?>" />
                        </span>
                    </div>
                    <div class="form-group">
                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Interval End Time">
                            <input id="working_hours_end" name="working_hours_end" class="form-control" type="time" value="<?= $working_hours_end; ?>" />
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-6">
                <label for="process">Process
                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Which process to use">
                        <i class="fas fa-question-circle"></i>
                    </span>
                </label>
                <select required name="process" id="process" class="custom-select mt-4">
                    <?php
                            $query = new WP_Query(array(
                                'post_type' => 'pgch_process',
                                'post_status' => 'publish',
                                'posts_per_page' => -1,
                            ));

                            while ($query->have_posts()) {
                                $query->the_post();
                                $post_id = get_the_ID();
                                $post_title = get_the_title(); ?>
                        <option value="<?= $post_id; ?>" <?php selected($process, $post_id); ?>><?= $post_title; ?>
                        </option>
                    <?php
                            } ?>
                </select>
            </div>
        </div>
        <script>
            jQuery(document).ready(function($) {
                var hoursLabel = document.getElementById("hours");
                var minutesLabel = document.getElementById("minutes");
                var ttLabel = document.getElementById("tt");
                var totalSeconds = parseInt(<?= date('s') ?>);
                var totalMinutes = parseInt(minutesLabel.innerHTML);
                var totalHours = parseInt(hoursLabel.innerHTML);
                setInterval(setTime, 1000);

                function setTime() {
                    ++totalSeconds;
                    if (totalSeconds >= 60) {
                        totalSeconds = 0;
                        ++totalMinutes;
                    }
                    if (totalMinutes >= 60) {
                        totalMinutes = 0;
                        ++totalHours;
                    }
                    minutesLabel.innerHTML = pad(totalMinutes);
                    hoursLabel.innerHTML = pad(totalHours, true);
                }

                function pad(val, tt = false) {
                    var valString = val + "";
                    if (valString.length < 2) {
                        return "0" + valString;
                    } else {
                        if (tt) {
                            if (val > 12) {
                                val -= 12;
                                toggle_tt(ttLabel.innerHTML);
                                valString = pad(val);
                            }
                        }
                        return valString;
                    }
                }

                function toggle_tt(val) {
                    if (val == 'PM') {
                        ttLabel.innerHTML = 'AM';
                    }
                    ttLabel.innerHTML = 'PM';
                }

                function toggle_upload() {
                    if ($('input[name="upload_type"]:checked').val() == 'Salesforce') {
                        $('#CSV').css('display', 'none');
                        $('#Salesforce').css('display', 'block');
                        $('#salesforce_advanced').css('display', 'block');
                    } else {
                        $('#Salesforce').css('display', 'none');
                        $('#salesforce_advanced').css('display', 'none');
                        $('#CSV').css('display', 'block');
                    }
                }
                toggle_upload();
                $('input[name="upload_type"]').on('change', function() {
                    toggle_upload();
                });

                $('#CSV_file').on('change', function() {
                    var path = $(this).val();
                    var file = path.split('\\').pop();
                    $('#csv_file_label').html(file);
                });
            });
        </script>
    <?php
        }
        function pgch_campaigns_operations_meta($post)
        {

            $sched_running = get_post_meta($post->ID, '_sched_running_meta', true);
            if ($sched_running == 'running') {
                $badge = 'success';
                $badge_text = 'Running';
            } else {
                $badge = 'danger';
                $badge_text = 'Not Running';
            }
            ?>
        <div class="form-group mt-3 mb-2">
            <span class="d-inline-block col-12" tabindex="0" data-toggle="tooltip" title="The Campaign is <?= $badge_text; ?>">

                <span class="badge badge-<?= $badge; ?> col" style="font-size: 18px; line-height:1.3;"><?= $badge_text; ?></span>
            </span>
            <?php if ($sched_running !== 'running') { ?>
                <span class="d-inline-block col-12" tabindex="0" data-toggle="tooltip" title="Start the campaign over">
                    <button id="restart_camp" class="btn btn-primary mt-2 col" <?= disabled($sched_running, 'running') ?>>Restart Campaign
                    </button>
                </span>
            <?php } else { ?>
                <span class="d-inline-block col-12" tabindex="0" data-toggle="tooltip" title="Stop the campaign">

                    <button id="stop_camp" class="btn btn-danger mt-2 col" <?= disabled($sched_running, 'not_running') ?>>Stop Campaign
                    </button>
                </span>
            <?php } ?>
            <script>
                (function($) {
                    $(document).ready(function() {
                        $('#restart_camp').on('click', function(e) {
                            e.preventDefault();
                            $(this).attr('disabled', true);
                            var data = {
                                'action': 'pgch_campaign_operations',
                                'operation': 'restart',
                                'camp_id': <?= $post->ID ?>
                            };
                            $.when($.post("<?= admin_url('admin-ajax.php'); ?>", data)).done(function(x) {
                                window.location.reload();
                            });
                        });
                        $('#stop_camp').on('click', function(e) {
                            e.preventDefault();
                            $(this).attr('disabled', true);
                            var data = {
                                'action': 'pgch_campaign_operations',
                                'operation': 'stop',
                                'camp_id': <?= $post->ID ?>
                            };
                            $.when($.post("<?= admin_url('admin-ajax.php'); ?>", data)).done(function(x) {
                                window.location.reload();
                            });
                        });
                    });
                })(jQuery);
            </script>
        </div>
    <?php
        }
        function pgch_campaign_save_postdata($post_id, $post, $update)
        {
            if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {
                if (!current_user_can('edit_page', $post_id)) {
                    return $post_id;
                }
            } else {
                if (!current_user_can('edit_page', $post_id)) {
                    return $post_id;
                }
            }

            if (array_key_exists('upload_type', $_POST)) {

                update_post_meta(
                    $post_id,
                    '_upload_type_meta',
                    $_POST['upload_type']
                );
                if ($_POST['upload_type'] == 'CSV') {
                    if (array_key_exists('CSV_file', $_FILES)) {

                        add_filter('upload_dir', 'pgch_upload_dir');

                        if (!function_exists('wp_handle_upload')) {
                            require_once(ABSPATH . 'wp-admin/includes/file.php');
                        }

                        $uploadedfile = $_FILES['CSV_file'];

                        $upload_overrides = array('test_form' => false);

                        $movefile = wp_handle_upload($uploadedfile, $upload_overrides);

                        if ($movefile && !isset($movefile['error'])) {
                            update_post_meta(
                                $post_id,
                                '_upload_source_meta',
                                $movefile['file']
                            );
                        } else {
                            wp_die("Couldn't upload file");
                        }


                        remove_filter('upload_dir', 'pgch_upload_dir');
                    }
                } elseif ($_POST['upload_type'] == 'Salesforce') {
                    if (array_key_exists('Salesfore_file', $_POST)) {
                        update_post_meta(
                            $post_id,
                            '_upload_source_meta',
                            $_POST['Salesfore_file']
                        );
                    }
                    if (array_key_exists('salesforce_sent', $_POST)) {
                        update_post_meta(
                            $post_id,
                            '_salesforce_sent_meta',
                            $_POST['salesforce_sent']
                        );
                    }
                    if (array_key_exists('salesforce_open', $_POST)) {
                        update_post_meta(
                            $post_id,
                            '_salesforce_open_meta',
                            $_POST['salesforce_open']
                        );
                    }
                    if (array_key_exists('salesforce_answered', $_POST)) {
                        update_post_meta(
                            $post_id,
                            '_salesforce_answered_meta',
                            $_POST['salesforce_answered']
                        );
                    }
                }
            }
            if (array_key_exists('email_subject', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_email_subject_meta',
                    $_POST['email_subject']
                );
            }
            if (array_key_exists('follow_subject', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_follow_subject_meta',
                    $_POST['follow_subject']
                );
            }
            if (array_key_exists('email_max', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_email_max_meta',
                    $_POST['email_max']
                );
            }
            if (array_key_exists('follow_max', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_follow_max_meta',
                    $_POST['follow_max']
                );
            }
            if (array_key_exists('working_hours_start', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_working_hours_start_meta',
                    $_POST['working_hours_start']
                );
            }
            if (array_key_exists('working_hours_end', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_working_hours_end_meta',
                    $_POST['working_hours_end']
                );
            }
            if (array_key_exists('process', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_process_meta',
                    $_POST['process']
                );
                if (!$update && $post->post_status == "auto-draft") {

                    // $camp_send_hook = "page_checker_send_mail_camp_$post_id";
                    // add_action( $camp_send_hook,  'pgch_send_mail', 10, 7);

                    // $camp_follow_flag_hook = "page_checker_follow_flag_camp_$post_id";
                    // add_action( $camp_follow_flag_hook,  'pgch_followUp_flag', 10, 2);

                    // $camp_follow_hook = "page_checker_follow_up_camp_$post_id";
                    add_action($camp_follow_hook,  'pgch_followUp_mail', 10, 9);
                    pgch_run_campaign($post_id);
                }
            }
        }
        add_action('save_post', 'pgch_campaign_save_postdata', 10, 3);
        function my_edit_campaigns_columns($columns)
        {

            $columns = array(
                'cb' => '&lt;input type="checkbox" />',
                'title' => __('Title'),
                'sent' => __('Emails Sent'),
                'source' => __('Emails Source'),
                'subject' => __('Email Subject'),
                'process' => __('Process'),
                'status' => __('Status'),
                'date' => __('Date')
            );

            return $columns;
        }
        add_filter('manage_edit-pgch_campaign_columns', 'my_edit_campaigns_columns');

        function my_manage_campaigns_columns($column, $post_id)
        {

            switch ($column) {
                case "sent":
                    $sent_emails = (int) get_post_meta($post_id, '_quick_view_sent_meta', true);
                    $all_send_emails = (int) get_post_meta($post_id, '_all_send_meta', true);
                    set_error_handler(function () {
                        throw new Exception('Ach!');
                    });
                    try {
                        $sent_emails = $sent_emails * 100 / $all_send_emails;
                    } catch (Exception $e) {
                        $sent_emails = 0;
                    }
                    restore_error_handler();
                    echo $sent_emails . '%';
                    break;
                case "source":
                    $upload_type = get_post_meta($post_id, '_upload_type_meta', true);
                    echo $upload_type;
                    break;
                case "subject":
                    $email_subject = get_post_meta($post_id, '_email_subject_meta', true);
                    echo $email_subject;
                    break;
                case "process":
                    $process = get_post_meta($post_id, '_process_meta', true);
                    $ntitle = get_the_title($process);
                    $nlink = get_edit_post_link($process);
                    printf('<a href="%s">%s</a>', $nlink, $ntitle);
                    break;
                case "status":
                    $sched_running = get_post_meta($post_id, '_sched_running_meta', true);
                    if ($sched_running == 'running') {
                        $badge_text = 'Running';
                        $badge = 'success';
                    } else {
                        $badge_text = 'Not Running';
                        $badge = 'danger';
                    }
                    printf('<span class="badge badge-%s" style="font-size: 14px; line-height:1.3;">%s</span>', $badge, $badge_text);
                    break;
            }
        }
        add_action('manage_pgch_campaign_posts_custom_column', 'my_manage_campaigns_columns', 10, 2);

        function my_sortable_campaigns_column($columns)
        {
            $columns['sent'] = 'sent';
            $columns['source'] = 'source';
            $columns['process'] = 'process';
            $columns['status'] = 'status';
            return $columns;
        }
        add_filter('manage_edit-pgch_campaign_sortable_columns', 'my_sortable_campaigns_column');

        function pgch_campagin_hide_quick_edit($actions, $post)
        {
            if ('pgch_campaign' === $post->post_type) {
                unset($actions['inline hide-if-no-js']);
            }

            return $actions;
        }
        add_filter('post_row_actions', 'pgch_campagin_hide_quick_edit', 10, 2);
    }
