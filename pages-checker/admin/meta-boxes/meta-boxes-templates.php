<?php
defined('ABSPATH') or die('You can\'t access this file.');

if (!function_exists('pgch_templates_cpt_custom_fields')) {
    add_action('add_meta_boxes', 'pgch_templates_cpt_custom_fields');
    function pgch_templates_cpt_custom_fields($post)
    {
        add_meta_box('pgch_templates_meta', 'Possible Dynamic Options', 'pgch_templates_meta', 'pgch_template', 'normal', 'low');
    }
    function pgch_templates_meta($post)
    {
        ?>
        <div>
            <p>Here is a list of all possible dynamic options that you can use in the template</p>
            <ul>
                <li>{{Website}}</li>
                <li>{{Email}}</li>
                <li>{{Response_code}}</li>
                <li>{{Response_message}}</li>
            </ul>
            <p>A list of possible options when using Salesforce</p>
            <p>In addtion to previous option you can use the following</p>
            <table>
                <tr>
                    <td>{{AnnualRevenue}}</td>
                    <td>{{Company}}</td>
                    <td>{{Description}}</td>
                    <td>{{Fax}}</td>
                </tr>
                <tr>
                    <td>{{Industry}}</td>
                    <td>{{Status}}</td>
                    <td>{{Phone}}</td>
                    <td>{{Title}}</td>
                </tr>
                <tr>
                    <td>{{MobilePhone}}</td>
                    <td>{{Salutation}}</td>
                    <td>{{LastName}}</td>
                    <td>{{FirstName}}</td>
                </tr>
                <tr>
                    <td>{{Country}}</td>
                    <td>{{State}}</td>
                    <td>{{City}}</td>
                    <td>{{Street}}</td>
                    <td>{{PostalCode}}</td>
                </tr>
            </table>
            <h2>Notes: <br>1- These options are case-insensitive<br> 2- Salesforce option will not be replaced when you use "Excel" as an input file.</h2>
        </div>
<?php
    }
}
