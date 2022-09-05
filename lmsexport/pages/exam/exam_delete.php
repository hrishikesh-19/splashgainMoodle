<?php
// This file is part of Moodle - http://moodle.org/local/imsexport/pages/exam/
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

require_once("../../../../config.php");
require_once("../../conf.php");
require_once("../pgnfunc.php");

global $USER, $DB;

require_login();


$eklavyaobj = $DB->get_record_sql("SELECT apiurl, examapikey,timezone1 FROM {local_lmsexport}");

$apiurl = $eklavyaobj->apiurl;


$fullusername = $USER->firstname . ' ' . $USER->lastname;
$myipadrs = get_client_ip();

$rv = array();

$rv['isok'] = 0;
$rv['msg1'] = '';
$rv['msg2'] = '';
$rv['test'] = '';


if (isset($_POST['ExamID'])) {


    $objary = array("MoodleExamID" => $_POST['ExamID'], 'UserEmailID' => $USER->email,
    "UserName" => $fullusername, "IPAddress" => $myipadrs, "MoodleExamName" => $_POST['ExamName']);

    $datatosend = array("Obj" => $objary);


    $posturl = $apiurl . '/SplashService.svc/DeleteMoodleExamWebAPI';

    $responsearray = get_api_response_withdata($posturl, $datatosend);

    $apiresultarray = $responsearray["DeleteMoodleExamWebAPIResult"];

    $responsemsg = $apiresultarray["Message"];

    $rv['isok'] = 1;
    $rv['msg1'] = $responsemsg;
    $rv['test'] = json_encode($datatosend);

    echo json_encode($rv);
}
