<?php
defined('ABSPATH') or die('You can\'t access this file.');

if (!function_exists('pgch_analytics_cpt_custom_fields')) {
    add_action('add_meta_boxes', 'pgch_analytics_cpt_custom_fields');
    function pgch_analytics_cpt_custom_fields($post)
    {
        add_meta_box('pgch_analytics_statistics_builder_meta', 'Statistics Builder', 'pgch_analytics_statistics_builder_meta', 'pgch_analytic', 'normal', 'low');
        if (pgch_cpt_status('edit')) {
            add_meta_box('pgch_analytics_statistics_viewer_meta', 'Statistics', 'pgch_analytics_statistics_viewer_meta', 'pgch_analytic', 'normal', 'low');
        }
    }
    function pgch_analytics_statistics_builder_meta($post)
    {
        $stats_type = get_post_meta($post->ID, '_stats_type_meta', true);
        if (empty($stats_type)) {
            $stats_type = 'campaign';
        }
        $camp_id = get_post_meta($post->ID, '_camp_id_meta', true);
        $proc_id = get_post_meta($post->ID, '_proc_id_meta', true);
        $temp_id = get_post_meta($post->ID, '_temp_id_meta', true);
        ?>

        <div class="row mb-4 mt-4">
            <div class="col-4">
                <label for="stats_type">Statistics Type
                    <span class="d-inline-block" tabindex="0" data-toggle="tooltip" title="What type to create statistics for?">
                        <i class="fas fa-question-circle"></i>
                    </span>
                </label>
                <div id="stats_type">
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="mr-2">
                                <input type="radio" id="camp_stats" name="stats_type" value="campaign" <?= checked($stats_type, 'campaign'); ?>>
                            </div>
                        </div>
                        <label for="camp_stats" class="mb-0">Campaign</label>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="mr-2">
                                <input type="radio" id="proc_stats" name="stats_type" value="process" <?= checked($stats_type, 'process'); ?>>
                            </div>
                        </div>
                        <label for="proc_stats" class="mb-0">Process</label>
                    </div>
                    <div class="input-group mb-3">
                        <div class="input-group-prepend">
                            <div class="mr-2">
                                <input type="radio" id="temp_stats" name="stats_type" value="template" <?= checked($stats_type, 'template'); ?>>
                            </div>
                        </div>
                        <label for="temp_stats" class="mb-0">Template</label>
                    </div>
                </div>
            </div>
            <div class="col-6 offset-2 d-flex">
                <div id="camp_ls" class="justify-content-center align-self-center col-12">
                    <select class="form-control" id="camp_id" name="camp_id">
                        <?php
                                $query = new WP_Query(array(
                                    'post_type' => 'pgch_campaign',
                                    'post_status' => 'publish',
                                    'posts_per_page' => -1,
                                ));

                                while ($query->have_posts()) {
                                    $query->the_post();
                                    $post_id = get_the_ID();
                                    $post_title = get_the_title(); ?>
                            <option value="<?= $post_id; ?>" <?php selected($camp_id, $post_id); ?>><?= $post_title; ?>
                            </option>
                        <?php
                                } ?>
                    </select>
                </div>
                <div id="proc_ls" class="justify-content-center align-self-center col-12">
                    <select class="form-control" id="proc_id" name="proc_id">
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
                            <option value="<?= $post_id; ?>" <?php selected($proc_id, $post_id); ?>><?= $post_title; ?>
                            </option>
                        <?php
                                } ?>
                    </select>
                </div>
                <div id="temp_ls" class="justify-content-center align-self-center col-12">
                    <select class="form-control" id="temp_id" name="temp_id">
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
                            <option value="<?= $post_id; ?>" <?php selected($temp_id, $post_id); ?>><?= $post_title; ?>
                            </option>
                        <?php
                                } ?>
                    </select>
                </div>
            </div>
        </div>
        <script>
            jQuery(document).ready(function($) {
                function toggle_upload() {
                    if ($('input[name="stats_type"]:checked').val() == 'campaign') {
                        $('#proc_ls').css('display', 'none');
                        $('#temp_ls').css('display', 'none');
                        $('#camp_ls').css('display', 'block');
                    } else if ($('input[name="stats_type"]:checked').val() == 'process') {
                        console.log("ASD");
                        $('#proc_ls').css('display', 'block');
                        $('#temp_ls').css('display', 'none');
                        $('#camp_ls').css('display', 'none');
                    } else if ($('input[name="stats_type"]:checked').val() == 'template') {
                        $('#proc_ls').css('display', 'none');
                        $('#temp_ls').css('display', 'block');
                        $('#camp_ls').css('display', 'none');
                    }
                }
                toggle_upload();
                $('input[name="stats_type"]').on('change', function() {
                    toggle_upload();
                });
            });
        </script>
    <?php
        }
        function pgch_analytics_statistics_viewer_meta($post)
        {
            $stats_type = get_post_meta($post->ID, '_stats_type_meta', true);

            if ($stats_type === 'campaign') {
                $post_id = get_post_meta($post->ID, '_camp_id_meta', true);
            } elseif ($stats_type === 'process') {
                $post_id = get_post_meta($post->ID, '_proc_id_meta', true);
            } elseif ($stats_type === 'template') {
                $post_id = get_post_meta($post->ID, '_temp_id_meta', true);
            }

            $analytics = pgch_get_stats($stats_type, $post_id, 'percentage');

            $all_emails = $analytics[0];
            $sent_emails = $analytics[1];
            $opened_emails = $analytics[2];
            $answered_emails = $analytics[3];

            $all_f_emails = $analytics[4];
            $sent_f_emails = $analytics[5];
            $opened_f_emails = $analytics[6];

            ?>
        <div class="row mb-4 mt-4">
            <div class="col-4 mb-4">
                <h1>First Emails</h1>
            </div>
            <div class="col-4 mb-4">
                <div class="bg-white rounded-lg p-4 shadow">
                    <h2 class="h6 font-weight-bold text-center mb-2">All Emails</h2>
                    <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                        <div class="h2 font-weight-bold"><?= $all_emails; ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4 mt-4">
            <div class="col-4 mb-4">
                <div class="bg-white rounded-lg p-4 shadow">
                    <h2 class="h6 font-weight-bold text-center mb-2">Sent Emails</h2>
                    <div class="progress mx-auto stats" data-value='<?= $sent_emails; ?>'>
                        <span class="progress-left">
                            <span class="progress-bar border-primary"></span>
                        </span>
                        <span class="progress-right">
                            <span class="progress-bar border-primary"></span>
                        </span>
                        <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                            <div class="h2 font-weight-bold"><?= $sent_emails; ?><sup class="small">%</sup></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 mb-4">
                <div class="bg-white rounded-lg p-4 shadow">
                    <h2 class="h6 font-weight-bold text-center mb-2">Opened Emails</h2>
                    <div class="progress mx-auto stats" data-value='<?= $opened_emails; ?>'>
                        <span class="progress-left">
                            <span class="progress-bar border-warning"></span>
                        </span>
                        <span class="progress-right">
                            <span class="progress-bar border-warning"></span>
                        </span>
                        <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                            <div class="h2 font-weight-bold"><?= $opened_emails; ?><sup class="small">%</sup></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 mb-4">
                <div class="bg-white rounded-lg p-4 shadow">
                    <h2 class="h6 font-weight-bold text-center mb-2">Answered Emails</h2>
                    <div class="progress mx-auto stats" data-value='<?= $answered_emails; ?>'>
                        <span class="progress-left">
                            <span class="progress-bar border-success"></span>
                        </span>
                        <span class="progress-right">
                            <span class="progress-bar border-success"></span>
                        </span>
                        <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                            <div class="h2 font-weight-bold"><?= $answered_emails; ?><sup class="small">%</sup></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dropdown-divider"></div>
        <div class="row mb-4 mt-4">
            <div class="col-4 mb-4">
                <h1>Follow Up Emails</h1>
            </div>
            <div class="col-4 mb-4">
                <div class="bg-white rounded-lg p-4 shadow">
                    <h2 class="h6 font-weight-bold text-center mb-2">All Follow Up Emails</h2>
                    <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                        <div class="h2 font-weight-bold"><?= $all_f_emails; ?></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mb-4 mt-4">
            <div class="col-4 offset-2 mb-4">
                <div class="bg-white rounded-lg p-4 shadow">
                    <h2 class="h6 font-weight-bold text-center mb-2">Sent Follow Up Emails</h2>
                    <div class="progress mx-auto stats" data-value='<?= $sent_f_emails; ?>'>
                        <span class="progress-left">
                            <span class="progress-bar border-primary"></span>
                        </span>
                        <span class="progress-right">
                            <span class="progress-bar border-primary"></span>
                        </span>
                        <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                            <div class="h2 font-weight-bold"><?= $sent_f_emails; ?><sup class="small">%</sup></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-4 mb-4">
                <div class="bg-white rounded-lg p-4 shadow">
                    <h2 class="h6 font-weight-bold text-center mb-2">Opened Follow Up Emails</h2>
                    <div class="progress mx-auto stats" data-value='<?= $opened_f_emails; ?>'>
                        <span class="progress-left">
                            <span class="progress-bar border-warning"></span>
                        </span>
                        <span class="progress-right">
                            <span class="progress-bar border-warning"></span>
                        </span>
                        <div class="progress-value w-100 h-100 rounded-circle d-flex align-items-center justify-content-center">
                            <div class="h2 font-weight-bold"><?= $opened_f_emails; ?><sup class="small">%</sup></div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    <?php
        }
        function pgch_stat_save_postdata($post_id)
        {
            if (array_key_exists('stats_type', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_stats_type_meta',
                    $_POST['stats_type']
                );
                if ($_POST['stats_type'] == 'campaign') {
                    if (array_key_exists('camp_id', $_POST)) {
                        update_post_meta(
                            $post_id,
                            '_camp_id_meta',
                            $_POST['camp_id']
                        );
                    }
                } elseif ($_POST['stats_type'] == 'process') {
                    if (array_key_exists('proc_id', $_POST)) {
                        update_post_meta(
                            $post_id,
                            '_proc_id_meta',
                            $_POST['proc_id']
                        );
                    }
                } elseif ($_POST['stats_type'] == 'template') {
                    if (array_key_exists('temp_id', $_POST)) {
                        update_post_meta(
                            $post_id,
                            '_temp_id_meta',
                            $_POST['temp_id']
                        );
                    }
                }
            }
        }
        add_action('save_post', 'pgch_stat_save_postdata');

        function my_edit_stats_columns($columns)
        {

            $columns = array(
                'cb' => '&lt;input type="checkbox" />',
                'title' => __('Title'),
                'type' => __('Type'),
                'source' => __('Source'),
                'date' => __('Date')
            );

            return $columns;
        }
        add_filter('manage_edit-pgch_analytic_columns', 'my_edit_stats_columns');

        function my_manage_stats_columns($column, $post_id)
        {

            switch ($column) {
                case "type":
                    $type = get_post_meta($post_id, '_stats_type_meta', true);
                    echo $type;
                    break;
                case "source":
                    $type = get_post_meta($post_id, '_stats_type_meta', true);
                    if ($type === 'campaign') {
                        $source = get_post_meta($post_id, '_camp_id_meta', true);
                    } elseif ($type === 'process') {
                        $source = get_post_meta($post_id, '_proc_id_meta', true);
                    } elseif ($type === 'template') {
                        $source = get_post_meta($post_id, '_temp_id_meta', true);
                    }
                    $stitle = get_the_title($source);
                    $slink = get_edit_post_link($source);
                    printf('<a href="%s">%s</a>', $slink, $stitle);
                    break;
            }
        }
        add_action('manage_pgch_analytic_posts_custom_column', 'my_manage_stats_columns', 10, 2);

        function my_sortable_analytics_column($columns)
        {
            $columns['type'] = 'type';
            $columns['source'] = 'source';
            return $columns;
        }
        add_filter('manage_edit-pgch_analytic_sortable_columns', 'my_sortable_analytics_column');
    }
