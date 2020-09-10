<?php
defined('ABSPATH') or die('You can\'t access this file.');

if (!function_exists('checkmail_cred')) {
    add_action('wp_ajax_checkmail_cred', 'checkmail_cred');
    function checkmail_cred()
    {
        if (isset($_POST['imap_mail_server'])) {
            $hostname = $_POST['imap_mail_server'];
        } else {
            $hostname = '';
        }
        $hostname = '{' . $hostname . ':993/imap/ssl/novalidate-cert}INBOX';

        if (isset($_POST['smtp_mail_server'])) {
            $hostname = $_POST['smtp_mail_server'];
        } elseif ($_POST['server'] === 'smtp') {
            $hostname = '';
        }

        if (isset($_POST['smtp_encrypt'])) {
            $smtp_encrypt = $_POST['smtp_encrypt'];
        } else {
            $smtp_encrypt = '';
        }

        if (isset($_POST['smtp_port'])) {
            $smtp_port = (is_numeric($_POST['smtp_port']) ? (int) $_POST['smtp_port'] : 0);
        } else {
            $smtp_port = 0;
        }

        if (isset($_POST['to_email'])) {
            $to_email = $_POST['to_email'];
        } else {
            $to_email = '';
        }

        if (isset($_POST['mail_username'])) {
            $username = $_POST['mail_username'];
        } else {
            $username = '';
        }

        if (isset($_POST['mail_password'])) {
            $password = $_POST['mail_password'];
        } else {
            $password = '';
        }
        if (isset($_POST['server'])) {
            if ($_POST['server'] === 'imap') {
                $inbox = imap_open($hostname, $username, $password) or die('Cannot connect to email: ' . imap_last_error());
                echo 'Mail Connected Successfully';
            } elseif ($_POST['server'] == 'smtp') {
                $mail = pgch_mailer_config($hostname, $username, $password, $smtp_encrypt, $smtp_port, TRUE);
                try {
                    pgch_send_email($mail, 'Pages Checker Test Email', 'This is a Test Email', $to_email);
                    echo 'Email Was Sent';
                } catch (Exception $e) {
                    echo "Message could not be sent. Mailer Error: {$mail->ErrorInfo}\r\n If you are sure that the configuration you provided is correct, then you're are highly recommended to disable \"Use SMTP Settings\" in the settings page";
                }
            }
        }
        wp_die();
    }
}
if (!function_exists('pgch_mailer_config')) {
    function pgch_mailer_config($host, $username, $password, $smtp_enrypt = 'TLS', $smtp_port = 587, $exceptions = FALSE)
    {
        $mail = new PHPMailer($exceptions);

        // Settings
        $mail->IsSMTP();
        $mail->CharSet = 'UTF-8';

        $mail->Host       = $host; // SMTP server example
        $mail->SMTPDebug  = 0;                     // enables SMTP debug information (for testing)
        $mail->SMTPAuth   = true;                  // enable SMTP authentication
        $mail->SMTPSecure = $smtp_enrypt;                                  // Enable TLS encryption, `ssl` also accepted
        $mail->Port       = $smtp_port;                    // set the SMTP port for the GMAIL server
        $mail->Username   = $username; // SMTP account username example
        $mail->Password   = $password;        // SMTP account password example
        $mail->setFrom($username, $username);

        $mail->isHTML(true);                                  // Set email format to HTML
        return $mail;
    }
}
if (!function_exists('pgch_send_email')) {
    function pgch_send_email($mail, $subject, $body, $toAddress, $tracker_fields = FALSE)
    {
        $mail->Subject = $subject;
        if ($tracker_fields) {
            $body = pgch_add_tracker($body, $tracker_fields);
        }
        $mail->Body = $body;
        $mail->addAddress($toAddress);

        $mail->send();
    }
}
if (!function_exists('pgch_add_tracker')) {
    function pgch_add_tracker($body, $tracker_fields)
    {
        $tracker = '?';

        if (is_array($tracker_fields)) {
            foreach ($tracker_fields as $field => $value) {
                $tracker .= "$field=" . urlencode($value) . "&";
            }
            if (!empty($tracker)) {
                $tracker = substr_replace($tracker, '', -1);
            }
        } elseif (is_string($tracker_fields)) {
            $tracker = $tracker_fields;
        } else {
            $tracker = '';
        }
        $body .= '<img data-imagetype="External" src="' . plugin_dir_url(dirname(__FILE__)) . 'functions' . '/tracker/function-tracker.php' . $tracker . '" style="width:1px; height:1px;">';
        return $body;
    }
}
if (!function_exists('pgch_check_inbox')) {
    function pgch_check_inbox($criteria, $email)
    {
        $imap_host = $email['imap_mail_server'];
        $username = $email['mail_username'];
        $password = $email['mail_password'];

        $hostname = '{' . $imap_host . ':993/imap/ssl/novalidate-cert}INBOX';

        /* try to connect */
        $inbox = imap_open($hostname, $username, $password) or die('Cannot connect to Mail Server: ' . imap_last_error());
        /* grab emails */
        $emails = imap_search($inbox, $criteria);
        /* if emails are returned, cycle through each... */

        if ($emails) {
            $found = true;
        } else {
            $found = false;
        }

        imap_close($inbox);

        return $found;
    }
}
if (!function_exists('pgch_prepare_and_send_email')) {
    function pgch_prepare_and_send_email($message, $subject, $email, $tracker_fields)
    {
        $headers = array('Content-Type: text/html; charset=UTF-8', "From: {$email['mail_username']}");
        $toEmail = $tracker_fields['email'];
        $wpm = FALSE;
        if (get_option('pgch_smtp')) {
            $smtp_mail_server = $email['smtp_mail_server'];
            $smtp_encrypt = $email['smtp_encrypt'];
            $smtp_port = $email['smtp_port'];
            $mail_username = $email['mail_username'];
            $mail_password = $email['mail_password'];

            try {
                $mail = pgch_mailer_config($smtp_mail_server, $mail_username, $mail_password, $smtp_encrypt, $smtp_port, True);
                pgch_send_email($mail, $subject, $message, $toEmail, $tracker_fields);
                $wpm = TRUE;
            } catch (Exception $e) {
                $wpm = wp_mail($toEmail, $subject, $message, $headers);
            }
        } else {
            $wpm = wp_mail($toEmail, $subject, $message, $headers);
        }
        return $wpm;
    }
}
if (!function_exists('pgch_get_email_settings')) {
    function pgch_get_email_settings($email_id)
    {
        $imap_mail_server = get_post_meta($email_id, '_imap_mail_server_meta', true);
        $smtp_mail_server = get_post_meta($email_id, '_smtp_mail_server_meta', true);
        $smtp_encrypt = get_post_meta($email_id, '_smtp_ecrypt_meta', true);
        $smtp_port = get_post_meta($email_id, '_smtp_port_meta', true);
        $mail_username = get_post_meta($email_id, '_mail_username_meta', true);
        $mail_password = get_post_meta($email_id, '_mail_password_meta', true);
        $email = array();
        $email['imap_mail_server'] = $imap_mail_server;
        $email['smtp_mail_server'] = $smtp_mail_server;
        $email['smtp_encrypt'] = $smtp_encrypt;
        $email['smtp_port'] = $smtp_port;
        $email['mail_username'] = $mail_username;
        $email['mail_password'] = $mail_password;
        return $email;
    }
}
