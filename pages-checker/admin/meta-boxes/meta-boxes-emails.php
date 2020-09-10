<?php
defined('ABSPATH') or die('You can\'t access this file.');

if (!function_exists('pgch_emails_cpt_custom_fields')) {
    add_action('add_meta_boxes', 'pgch_emails_cpt_custom_fields');
    function pgch_emails_cpt_custom_fields($post)
    {
        add_meta_box('pgch_emails_meta', 'Email Account Settings', 'pgch_emails_meta', 'pgch_email', 'normal', 'low');
    }
    function pgch_emails_meta($post)
    {
        $imap_mail_server = get_post_meta($post->ID, '_imap_mail_server_meta', true);
        $smtp_mail_server = get_post_meta($post->ID, '_smtp_mail_server_meta', true);
        $smtp_encrypt = get_post_meta($post->ID, '_smtp_encrypt_meta', true);
        $smtp_port = get_post_meta($post->ID, '_smtp_port_meta', true);
        $mail_username = get_post_meta($post->ID, '_mail_username_meta', true);
        $mail_password = get_post_meta($post->ID, '_mail_password_meta', true); ?>

        <div class="form-group">
            <label for="imap_mail_server">IMAP Mail Server</label>
            <input required id="imap_mail_server" name="imap_mail_server" class="form-control" value="<?= $imap_mail_server; ?>" placeholder="Enter IMAP mail server" />
        </div>
        <div class="row">
            <div class="form-group col-6">
                <label for="smtp_mail_server">SMTP Mail Server</label>
                <input required id="smtp_mail_server" name="smtp_mail_server" class="form-control" value="<?= $smtp_mail_server; ?>" placeholder="Enter SMTP mail server" />
            </div>
            <div class="form-group col-3">
                <label for="smtp_mail_server">SMTP Encryption</label>
                <select id="smtp_encrypt" name="smtp_encrypt">
                    <option value="TLS" <?= selected($smtp_encrypt, 'TLS') ?>>TLS</option>
                    <option value="SSL" <?= selected($smtp_encrypt, 'SSL') ?>>SSL</option>
                </select>
            </div>
            <div class="form-group col-3">
                <label for="smtp_port">SMTP Port</label>
                <input required id="smtp_port" name="smtp_port" type="number" min="1" class="form-control" value="<?= (!empty($smtp_port) ? $smtp_port : '587'); ?>" placeholder="Enter SMTP Port" />
            </div>
        </div>
        <div class="form-group">
            <label for="mail_username">Username</label>
            <input required id="mail_username" name="mail_username" class="form-control" value="<?= $mail_username; ?>" autocomplete="off" placeholder="Enter email address" />
        </div>
        <div class="form-group">
            <label for="mail_password">Password</label>
            <input required id="mail_password" name="mail_password" type="password" class="form-control" value="<?= $mail_password; ?>" autocomplete="new-password" placeholder="Enter password" />
        </div>
        <div class="row">
            <div class="form-group col-lg-3 col-sm-4 offset-3 offset-lg-0">
                <button id="test_imap_settings" class="btn btn-success">Test IMAP Settings</button>
            </div>
            <div class="form-group col-lg-3 col-sm-4 offset-3 offset-lg-0">
                <button id="test_smtp_settings" class="btn btn-success">Test SMTP Settings</button>
            </div>
        </div>
        <script>
            function isEmptyOrSpaces(str) {
                return str === null || str.match(/^ *$/) !== null;
            }
            jQuery(document).ready(function($) {
                $("#test_imap_settings").on('click', function(e) {
                    e.preventDefault();
                    var data = {
                        'action': 'checkmail_cred',
                        'imap_mail_server': $('#imap_mail_server').val(),
                        'mail_username': $('#mail_username').val(),
                        'mail_password': $('#mail_password').val(),
                        'server': 'imap'
                    };
                    var url = "<?= admin_url('admin-ajax.php'); ?>";
                    jQuery.ajax({
                        type: "POST",
                        url: url,
                        data: data,
                        timeout: 3000,
                        error: function(jqXHR, textStatus, errorThrown) {
                            if (textStatus === "timeout") {
                                alert("Timeout");
                            }
                        },
                        success: function(response) {
                            alert(response);
                        }
                    });
                });
                $("#test_smtp_settings").on('click', function(e) {
                    e.preventDefault();
                    var to_email = prompt('A Test Email Will be Sent to check connectivity, please enter an email address');
                    var data = {
                        'action': 'checkmail_cred',
                        'smtp_mail_server': $('#smtp_mail_server').val(),
                        'smtp_encrypt': $('#smtp_encrypt').val(),
                        'smtp_port': $('#smtp_port').val(),
                        'mail_username': $('#mail_username').val(),
                        'mail_password': $('#mail_password').val(),
                        'to_email': to_email,
                        'server': 'smtp'
                    };
                    if (!isEmptyOrSpaces(to_email)) {
                        $('#test_smtp_settings').attr('disabled', true);
                        jQuery.post("<?= admin_url('admin-ajax.php'); ?>", data, function(response) {
                            alert(response);
                            $('#test_smtp_settings').attr('disabled', false);
                        });
                    }
                });
                $('#smtp_encrypt').on('change', function(e) {
                    var selected_val = $("#smtp_encrypt option:selected").val();
                    if (selected_val == 'TLS') {
                        $('#smtp_port').val('587');
                    } else if (selected_val == 'SSL') {
                        $('#smtp_port').val('465');
                    }
                });
            });
        </script>
    <?php
        }
        function pgch_mail_save_postdata($post_id)
        {
            if (array_key_exists('imap_mail_server', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_imap_mail_server_meta',
                    $_POST['imap_mail_server']
                );
            }
            if (array_key_exists('smtp_mail_server', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_smtp_mail_server_meta',
                    $_POST['smtp_mail_server']
                );
            }
            if (array_key_exists('smtp_encrypt', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_smtp_encrypt_meta',
                    $_POST['smtp_encrypt']
                );
            }
            if (array_key_exists('smtp_port', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_smtp_port_meta',
                    $_POST['smtp_port']
                );
            }
            if (array_key_exists('mail_username', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_mail_username_meta',
                    $_POST['mail_username']
                );
            }
            if (array_key_exists('mail_password', $_POST)) {
                update_post_meta(
                    $post_id,
                    '_mail_password_meta',
                    $_POST['mail_password']
                );
            }
        }
        add_action('save_post', 'pgch_mail_save_postdata');

        function my_edit_emails_columns($columns)
        {

            $columns = array(
                'cb' => '&lt;input type="checkbox" />',
                'title' => __('Title'),
                'email' => __('Username'),
                'date' => __('Date')
            );

            return $columns;
        }
        add_filter('manage_edit-pgch_email_columns', 'my_edit_emails_columns');

        function my_manage_emails_columns($column, $post_id)
        {

            switch ($column) {
                case "email":
                    $email = get_post_meta($post_id, '_mail_username_meta', true);
                    echo $email;
                    break;
            }
        }
        add_action('manage_pgch_email_posts_custom_column', 'my_manage_emails_columns', 10, 2);
    }
