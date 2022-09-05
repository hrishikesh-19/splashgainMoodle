<?php
// This file is part of Moodle - http://moodle.org/local/imsexport/pages/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Local plugin "Eklavvya Moodle Proctoring"
 *
 * @package   local_lmsexport
 * @copyright 2022 Splashgain Technology Solutions Pvt. Ltd., India.
 * @license   https://www.eklavvya.com/terms-of-use/
 */

defined('MOODLE_INTERNAL') || die();

require_login();

error_reporting(1);

global $USER, $DB;

$eklavyaobj = $DB->get_record_sql("SELECT apiurl, examapikey,timezone1 FROM {local_lmsexport}");

$apiurl = $eklavyaobj->apiurl;


function get_api_response_withdata($posturl, $datatosend) {

    global $USER, $DB;

    $tokres = $DB->get_record_sql("SELECT token  FROM {external_tokens} WHERE userid = " . $USER->id);
    $tokenresr = $tokres->token;

    $records = $DB->get_record('local_lmsexport', array(), $fields = '*', $ignoremultiple = false);
    $eklavyaobj = $DB->get_record_sql("SELECT apiurl, examapikey,timezone1 FROM {local_lmsexport}");

    $apiurl = $eklavyaobj->apiurl;
    $examapikey = $eklavyaobj->examapikey;
    $timezone = $eklavyaobj->timezone1;
    $postdata1 = json_encode($datatosend);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $posturl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $headers  = [
        'authenticationkey: ' . $examapikey,
        'Content-Type: application/json'
    ];

    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    $serveroutput = curl_exec($ch);

    $response = json_decode($serveroutput, true);

    curl_close($ch);

    return $response;
}


function get_api_response($posturl, $datatosend) {
    global $USER, $DB;
    $tokres = $DB->get_record_sql("SELECT token  FROM {external_tokens} WHERE userid = " . $USER->id);
    $tokenresr = $tokres->token;
    $records = $DB->get_record('local_lmsexport', array(), $fields = '*', $ignoremultiple = false);
    $eklavyaobj = $DB->get_record_sql("SELECT apiurl, examapikey,timezone1 FROM {local_lmsexport}");
    $apiurl = $eklavyaobj->apiurl;
    $examapikey = $eklavyaobj->examapikey;
    $timezone = $eklavyaobj->timezone1;
    $postdata1 = json_encode($datatosend);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $posturl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $postdata1);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $headers  = [
        'authenticationkey: ' . $examapikey,
        'Content-Type: application/json'
    ];
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    $serveroutput = curl_exec($ch);
    curl_close($ch);
    return $serveroutput;
}



function get_page_of_array($dataary, $pgno, $pgsize) {
    $totlrec = count($dataary);
    $totalpages = ceil($totlrec / $pgsize);
    $rv = array();
    $skiprec = ($pgno - 1) * $pgsize;
    $lstrec = $skiprec + $pgsize;
    if ($pgno <= $totalpages) {
        $i = $skiprec;
        while ($i < $lstrec) {
            if ($i <= $totlrec) {
                array_push($rv, $dataary[$i]);
                $i++;
            } else {
                break;
            }
        }
    }
    return $rv;
}


function get_max_page_no_of_array($dataary, $pgsize) {
    $rv = 0;
    $totlrec = count($dataary);
    if ($totlrec > $pgsize) {
        $rv = ceil($totlrec / $pgsize);
    } else {
        $rv = 1;
    }
}

function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP')) {
        $ipaddress = getenv('HTTP_CLIENT_IP');
    } else if (getenv('HTTP_X_FORWARDED_FOR')) {
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    } else if (getenv('HTTP_X_FORWARDED')) {
        $ipaddress = getenv('HTTP_X_FORWARDED');
    } else if (getenv('HTTP_FORWARDED_FOR')) {
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    } else if (getenv('HTTP_FORWARDED')) {
        $ipaddress = getenv('HTTP_FORWARDED');
    } else if (getenv('REMOTE_ADDR')) {
        $ipaddress = getenv('REMOTE_ADDR');
    } else {
        $ipaddress = 'UNKNOWN';
    }
    return $ipaddress;
}
