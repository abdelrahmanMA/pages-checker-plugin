<?php

define('SHORTINIT', true);

preg_match('/^(.+)wp-content\/.*/', dirname(__FILE__), $path);
require_once($path[1] . 'wp-load.php');
require_once('../functions-database.php');

if (isset($_GET['id'])) {
    $id = (int) urldecode($_GET['id']);
} else {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    return 0;
}
if (isset($_GET['email'])) {
    $email = $_GET['email'];
} else {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    return 0;
}
if (isset($_GET['message_type'])) {
    $message_type = urldecode($_GET['message_type']);
} else {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    return 0;
}
if (isset($_GET['temp_id'])) {
    $temp_id = (int) urldecode($_GET['temp_id']);
} else {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    return 0;
}
if (isset($_GET['proc_id'])) {
    $proc_id = (int) urldecode($_GET['proc_id']);
} else {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    return 0;
}
if (isset($_GET['camp_id'])) {
    $camp_id = (int) urldecode($_GET['camp_id']);
} else {
    header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");
    return 0;
}
$tracker_fields = array(
    'id' => $id,
    'temp_id' => $temp_id,
    'proc_id' => $proc_id,
    'camp_id' => $camp_id
);
if ($message_type === 'first_email') {
    pgch_database_update_item(array('opened' => TRUE), $tracker_fields);
} elseif ($message_type === 'follow_up') {
    pgch_database_update_item(array('follow_opened' => TRUE), $tracker_fields);
}
header("Content-Type: image/jpeg");
readfile((dirname(__FILE__)) . '/track.jpeg');
