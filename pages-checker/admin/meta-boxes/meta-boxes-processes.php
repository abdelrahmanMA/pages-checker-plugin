<?php
defined('ABSPATH') or die('You can\'t access this file.');

if (!function_exists('pgch_processess_cpt_custom_fields')) {
    add_action('add_meta_boxes', 'pgch_processess_cpt_custom_fields');
    function pgch_processess_cpt_custom_fields($post)
    {
        add_meta_box('pgch_processess_meta', 'Process Settings', 'pgch_processess_meta', 'pgch_process', 'normal', 'low');
    }
    function pgch_processess_meta($post)
    {
        $send_email = get_post_meta($post->ID, '_send_email_meta', true);
        $email_interval_number = get_post_meta($post->ID, '_email_interval_number_meta', true);
        $email_interval_number2 = get_post_meta($post->ID, '_email_interval_number2_meta', true);

        $follow_email = get_post_meta($post->ID, '_follow_email_meta', true);
        $follow_up_number = get_post_meta($post->ID, '_follow_up_number_meta', true);
        $follow_up_type = get_post_meta($post->ID, '_follow_up_type_meta', true);

        $send_template = get_post_meta($post->ID, '_send_template_meta', true);

        $opened_template = get_post_meta($post->ID, '_opened_template_meta', true);
        $not_opened_template = get_post_meta($post->ID, '_not_opened_template_meta', true);

        $answered_template = get_post_meta($post->ID, '_answered_template_meta', true); ?>
        <div class="row">
            <div class="form-group col-sm-6">
                <label for="email_interval_number">Email
                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Which email account to use to send the first email">
                        <i class="fas fa-question-circle"></i>
                    </span>
                </label>
                <select required name="send_email" id="send_email" class="custom-select">
                    <?php
                            $query = new WP_Query(array(
                                'post_type' => 'pgch_email',
                                'post_status' => 'publish',
                                'posts_per_page' => -1,
                            ));

                            while ($query->have_posts()) {
                                $query->the_post();
                                $post_id = get_the_ID();
                                $post_title = get_the_title(); ?>
                        <option value="<?= $post_id; ?>" <?php selected($send_email, $post_id); ?>><?= $post_title; ?></option>
                    <?php
                            }

                            wp_reset_query(); ?>
                </select>
            </div>
            <div class="form-group col-sm-6">
                <label for="email_interval_number">Random interval between emails
                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="A random number is calculated from min and max value, in seconds, to be waited before sending another email">
                        <i class="fas fa-question-circle"></i>
                    </span>
                </label>
                <div class="row">
                    <div class="form-group col-sm-3">
                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Minimum Value">
                            <input required id="email_interval_number" type="number" name="email_interval_number" min="0" class="form-control" value=<?= $email_interval_number; ?>>
                        </span>
                    </div>
                    <div class="form-group col-sm-3">
                        <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Maximum Value">
                            <input required id="email_interval_number2" type="number" name="email_interval_number2" min="0" class="form-control" value=<?= $email_interval_number2; ?>>
                        </span>

                    </div>

                </div>
            </div>
        </div>

        <div class="row">
            <div class="form-group col-sm-6">
                <label for="email_interval_number">Follow up Email
                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Which email account to use to send the follow up email">
                        <i class="fas fa-question-circle"></i>
                    </span>
                </label>
                <select required name="follow_email" id="follow_email" class="custom-select">
                    <?php
                            $query = new WP_Query(array(
                                'post_type' => 'pgch_email',
                                'post_status' => 'publish',
                                'posts_per_page' => -1,
                            ));

                            while ($query->have_posts()) {
                                $query->the_post();
                                $post_id = get_the_ID();
                                $post_title = get_the_title(); ?>
                        <option value="<?= $post_id; ?>" <?php selected($follow_email, $post_id); ?>><?= $post_title; ?></option>
                    <?php
                            }

                            wp_reset_query(); ?>
                </select>
            </div>

            <div class="form-group col-sm-6">
                <label class="col-sm-12" for="follow_up_number">Set Follow up wait
                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Time to be waited before sending follow up emails">
                        <i class="fas fa-question-circle"></i>
                    </span>
                </label>
                <div class="row">
                    <div class="form-group col-sm-3">
                        <input required id="follow_up_number" type="number" name="follow_up_number" min="0" class="form-control" value=<?= $follow_up_number; ?>>
                    </div>
                    <div class="form-group col-sm-4">
                        <select required name="follow_up_type" id="follow_up_type" class="custom-select">
                            <option value="seconds" <?php selected($follow_up_type, 'seconds'); ?>>Seconds</option>
                            <option value="minutes" <?php selected($follow_up_type, 'minutes'); ?>>Minutes</option>
                            <option value="hours" <?php selected($follow_up_type, 'hours'); ?>>Hours</option>
                            <option value="days" <?php selected($follow_up_type, 'days'); ?>>Days</option>
                        </select>
                    </div>

                </div>
            </div>
        </div>
        <div class="row">
            <div class="form-group col-sm-6">
                <label for="send_template">Template
                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Which template to use to send the first email">
                        <i class="fas fa-question-circle"></i>
                    </span>
                </label>
                <select required name="send_template" id="send_template" class="custom-select">
                    <?php
                            $query = new WP_Query(array(
                                'post_type' => 'pgch_template',
                                'post_status' => 'publish',
                                'posts_per_page' => -1,
                            ));

                            while ($query->have_posts()) {
                                $query->the_post();
                                $post_id = get_the_ID();
                                $post_title = get_the_title(); ?>
                        <option value="<?= $post_id; ?>" <?php selected($opened_template, $post_id); ?>><?= $post_title; ?></option>
                    <?php
                            }

                            wp_reset_query(); ?>
                </select>
            </div>
            <div class="form-group col-sm-6">
                <label for="opened_template">If only opened use
                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Which template to use if the email was opened but not answered">
                        <i class="fas fa-question-circle"></i>
                    </span>
                </label>
                <select required name="opened_template" id="opened_template" class="custom-select">
                    <?php
                            $query = new WP_Query(array(
                                'post_type' => 'pgch_template',
                                'post_status' => 'publish',
                                'posts_per_page' => -1,
                            ));

                            while ($query->have_posts()) {
                                $query->the_post();
                                $post_id = get_the_ID();
                                $post_title = get_the_title(); ?>
                        <option value="<?= $post_id; ?>" <?php selected($opened_template, $post_id); ?>><?= $post_title; ?></option>
                    <?php
                            }

                            wp_reset_query(); ?>
                </select>
            </div>


        </div>

        <div class="row">
            <div class="form-group col-sm-6">
                <label for="not_opened_template">If not opened use
                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Which template to use if the email was not opened nor answered">
                        <i class="fas fa-question-circle"></i>
                    </span>
                </label>
                <select required name="not_opened_template" id="not_opened_template" class="custom-select">
                    <?php
                            $query = new WP_Query(array(
                                'post_type' => 'pgch_template',
                                'post_status' => 'publish',
                                'posts_per_page' => -1,
                            ));

                            while ($query->have_posts()) {
                                $query->the_post();
                                $post_id = get_the_ID();
                                $post_title = get_the_title(); ?>
                        <option value="<?= $post_id; ?>" <?php selected($not_opened_template, $post_id); ?>><?= $post_title; ?>
                        </option>
                    <?php
                            }

                            wp_reset_query(); ?>
                </select>
            </div>
            <div class="form-group col-sm-6">
                <label for="answered_template">If answered use
                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="Which template to use if the email was answered or do nothing if answered">
                        <i class="fas fa-question-circle"></i>
                    </span>
                </label>
                <select required name="answered_template" id="answered_template" class="custom-select">
                    <option value="Nothing">Nothing</option>
                    <?php
                            $query = new WP_Query(array(
                                'post_type' => 'pgch_template',
                                'post_status' => 'publish',
                                'posts_per_page' => -1,
                            ));

                            while ($query->have_posts()) {
                                $query->the_post();
                                $post_id = get_the_ID();
                                $post_title = get_the_title(); ?>
                        <option value="<?= $post_id; ?>" <?php selected($answered_template, $post_id); ?>><?= $post_title; ?>
                        </option>
                    <?php
                            }

                            wp_reset_query(); ?>
                </select>
            </div>

        </div>

        <script>
            jQuery(document).ready(function($) {
                $('#email_interval_number').on('change', function(e) {
                    $('#email_interval_number2').attr('min', $(this).val());
                    if ($(this).val() > $('#email_interval_number2').val())
                        $('#email_interval_number2').val(Number($(this).val()) + 1);
                });
            });
        </script>
    <?php
        }
        function pgch_process_save_postdata($post_id)
        {
            if (array_key_exists('send_email', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_send_email_meta',
                    $_POST['send_email']
                );
            }
            if (array_key_exists('email_interval_number', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_email_interval_number_meta',
                    $_POST['email_interval_number']
                );
            }
            if (array_key_exists('email_interval_number2', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_email_interval_number2_meta',
                    $_POST['email_interval_number2']
                );
            }
            if (array_key_exists('follow_email', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_follow_email_meta',
                    $_POST['follow_email']
                );
            }
            if (array_key_exists('follow_up_number', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_follow_up_number_meta',
                    $_POST['follow_up_number']
                );
            }
            if (array_key_exists('follow_up_type', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_follow_up_type_meta',
                    $_POST['follow_up_type']
                );
            }
            if (array_key_exists('send_template', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_send_template_meta',
                    $_POST['send_template']
                );
            }
            if (array_key_exists('opened_template', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_opened_template_meta',
                    $_POST['opened_template']
                );
            }
            if (array_key_exists('not_opened_template', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_not_opened_template_meta',
                    $_POST['not_opened_template']
                );
            }
            if (array_key_exists('answered_template', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_answered_template_meta',
                    $_POST['answered_template']
                );
            }
        }
        add_action('save_post', 'pgch_process_save_postdata');

        function my_edit_processes_columns($columns)
        {

            $columns = array(
                'cb' => '&lt;input type="checkbox" />',
                'title' => __('Title'),
                'interval' => __('Interval'),
                'follow_up' => __('Follow Up Wait'),
                'not_open' => __('Not Open'),
                'open' => __('Open'),
                'answered' => __('Answered'),
                'date' => __('Date')
            );

            return $columns;
        }
        add_filter('manage_edit-pgch_process_columns', 'my_edit_processes_columns');

        function my_manage_processes_columns($column, $post_id)
        {

            switch ($column) {
                case "interval":
                    $intr1 = get_post_meta($post_id, '_email_interval_number_meta', true);
                    $intr2 = get_post_meta($post_id, '_email_interval_number2_meta', true);
                    echo $intr1 . '-' . $intr2;
                    break;
                case "follow_up":
                    $follown = get_post_meta($post_id, '_follow_up_number_meta', true);
                    $followt = get_post_meta($post_id, '_follow_up_type_meta', true);
                    echo $follown . ' ' . $followt;
                    break;
                case "not_open":
                    $ntemplate = get_post_meta($post_id, '_not_opened_template_meta', true);
                    $ntitle = get_the_title($ntemplate);
                    $nlink = get_edit_post_link($ntemplate);
                    printf('<a href="%s">%s</a>', $nlink, $ntitle);
                    break;
                case "open":
                    $otemplate = get_post_meta($post_id, '_opened_template_meta', true);
                    $otitle = get_the_title($otemplate);
                    $olink = get_edit_post_link($otemplate);
                    printf('<a href="%s">%s</a>', $olink, $otitle);
                    break;
                case "answered":
                    $atemplate = get_post_meta($post_id, '_answered_template_meta', true);
                    if ($atemplate !== 'Nothing') {
                        $atitle = get_the_title($atemplate);
                        $alink = get_edit_post_link($atemplate);
                        printf('<a href="%s">%s</a>', $alink, $atitle);
                    } else {
                        echo "Nothing";
                    }
                    break;
            }
        }
        add_action('manage_pgch_process_posts_custom_column', 'my_manage_processes_columns', 10, 2);

        function my_sortable_processes_column($columns)
        {
            $columns['not_open'] = 'not_open';
            $columns['open'] = 'open';
            $columns['answered'] = 'answered';
            return $columns;
        }
        add_filter('manage_edit-pgch_process_sortable_columns', 'my_sortable_processes_column');
    }
