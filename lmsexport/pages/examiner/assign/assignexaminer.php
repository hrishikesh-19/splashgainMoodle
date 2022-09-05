<?php
// This file is part of Moodle - http://moodle.org/local/imsexport/pages/examiner/assign/
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

require_once("../../../../../config.php");
require_once("../../../conf.php");
require_once("../../pgnfunc.php");

require_login();

error_reporting(1);

global $USER, $DB;

$eklavyaobj = $DB->get_record_sql("SELECT apiurl, examapikey,timezone1 FROM {local_lmsexport}");

$apiurl = $eklavyaobj->apiurl;

$myuserumailid = $USER->email;
$fullusername = $USER->firstname . ' ' . $USER->lastname;
$myipadrs = get_client_ip();


if (isset($_POST['courseid']) &&
    isset($_POST['ExamID']) &&
    isset($_POST['ScheduleID']) &&
    isset($_POST['ExaminerID']) &&
    isset($_POST['rcbx'])) {

    $objary = array();

    foreach ($_POST['rcbx'] as $sid) {

        $rec = array(
            "ExamID" => $_POST['ExamID'],
            "ScheduleID" => $_POST['ScheduleID'],
            "StudentID" => $sid,
            "ExaminerID" => $_POST['ExaminerID'],
            "MoodleUserID" => $USER->id,
            "UserEmailID" => $myuserumailid,
            "UserName" => $fullusername,
            "IPAddress" => $myipadrs
        );
        array_push($objary, $rec);
    }

    $datatosend = array("obj" => $objary);


    $posturl = $apiurl . '/SplashService.svc/AssignExaminerWebAPI';

    $responsearray = get_api_response_withdata($posturl, $datatosend);

    $apiresultarray = $responsearray["AssignExaminerWebAPIResult"];

    $responsemsg = $apiresultarray[0]["Message"];
    $status = ($response['AssignExaminerWebAPIResult'][0]['objStatusCodes']);

    echo $responsemsg;
}
