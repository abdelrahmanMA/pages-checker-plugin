<?php
defined('ABSPATH') or die('You can\'t access this file.');

if (!function_exists('check_salesforce_cred')) {
    add_action('wp_ajax_check_salesforce_cred', 'check_salesforce_cred');
    function check_salesforce_cred()
    {
        if (isset($_POST['user'])) {
            $user = $_POST['user'];
        } else {
            $user = '';
        }

        if (isset($_POST['pass'])) {
            $pass = $_POST['pass'];
        } else {
            $pass = '';
        }

        if (isset($_POST['key'])) {
            $key = $_POST['key'];
        } else {
            $key = '';
        }

        if (isset($_POST['secret'])) {
            $secret = $_POST['secret'];
        } else {
            $secret = '';
        }

        if (pgch_sf_get_token($key, $secret, $user, $pass)) {
            echo "Connection was Successful";
        } else {
            echo "Failed to Connect to Salseforce";
        }
        wp_die();
    }
}
if (!function_exists('save_salesforce_conn')) {
    add_action('wp_ajax_save_salesforce_conn', 'save_salesforce_conn');
    function save_salesforce_conn()
    {
        if (isset($_POST['conn'])) {
            $conn = ($_POST['conn'] === 'true');
        }
        update_option('pgch_sfconnect', $conn);
        wp_die();
    }
}
if (!function_exists('pgch_sf_get_token')) {
    function pgch_sf_get_token($CLIENT_ID = '', $CLIENT_SECRET = '', $USER_NAME = '', $PASSWORD = '')
    {
        $loginurl = "https://login.salesforce.com";

        $params = "grant_type=password"
            . "&client_id=" . $CLIENT_ID
            . "&client_secret=" . $CLIENT_SECRET
            . "&username=" . $USER_NAME
            . "&password=" . $PASSWORD;

        $token_url = $loginurl . "/services/oauth2/token";

        $curl = curl_init($token_url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $params);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        curl_close($curl);

        if ($status != 200) {
            return FALSE;
        }

        $response = json_decode($json_response, true);

        return $response;
    }
}
if (!function_exists('pgch_sf_get_items')) {
    function pgch_sf_get_items($token, $instance_url, $fields, $table, $where, $version = SALESFORCE_API_VERSION)
    {
        $fields = implode(',', $fields);
        $where_clause = '+WHERE+';

        if (is_array($where)) {
            foreach ($where as $field => $value) {
                $where_clause .= "$field+=+$value+AND+";
            }
            if (!empty($where_clause)) {
                $where_clause = substr_replace($where_clause, '', -5);
            }
        } elseif (is_string($where)) {
            $where_clause = $where;
        } else {
            $where_clause = '';
        }
        $access_url = "$instance_url/services/data/v$version/query/?q=SELECT+$fields+FROM+$table$where_clause";
        $access_header = 'Authorization: Bearer ' . $token;
        $curl = curl_init($access_url);

        curl_setopt($curl, CURLOPT_HTTPHEADER, array(
            $access_header
        ));
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($status != 200) {
            die("Error: call to access URL $access_url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }

        curl_close($curl);
        $response = json_decode($json_response, true);

        return $response;
    }
}
if (!function_exists('pgch_sf_update_item')) {
    function pgch_sf_update_item($token, $instance_url, $required_fields, $table, $contents, $version = SALESFORCE_API_VERSION)
    {

        $access_url = "$instance_url/services/data/v$version/sobjects/$table/$required_fields";

        $access_header = array(
            'Authorization: Bearer ' . $token,
            'Content-type: application/json',
            'Accept-type: application/json'
        );

        $curl = curl_init($access_url);
        curl_setopt($curl, CURLOPT_HEADER, false);
        curl_setopt($curl, CURLOPT_HTTPHEADER,  $access_header);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "PATCH");
        curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($contents));

        $json_response = curl_exec($curl);

        $status = curl_getinfo($curl, CURLINFO_HTTP_CODE);

        if ($status != 204) {
            die("Error: call to update URL $access_url failed with status $status, response $json_response, curl_error " . curl_error($curl) . ", curl_errno " . curl_errno($curl));
        }

        curl_close($curl);
        $response = json_decode($json_response, true);

        return $response;
    }
}
