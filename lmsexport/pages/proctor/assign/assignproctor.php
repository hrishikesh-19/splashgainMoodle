<?php
// This file is part of Moodle - http://moodle.org/local/imsexport/pages/proctor/assign/
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


$myuseremailid = $USER->email;
$fullusername = $USER->firstname . ' ' . $USER->lastname;
$myipadrs = get_client_ip();


if (isset($_POST['courseid']) && isset($_POST['ExamID']) && isset($_POST['ScheduleID']) &&
isset($_POST['ProctorID']) && isset($_POST['rcbx'])) {

    $studlist = enrol_get_course_users($_POST['courseid']);



    $objary = array();

    foreach ($studlist as $row) {
        $testvar = $_POST['rcbx'];



        if (in_array($row->id, $_POST['rcbx'])) {

            $phone = $USER->phone1;
            if (trim($phone) == '') {
                $phone = $USER->phone2;
            }

            if (trim($phone) == '') {
                $phone = " ";
            }

            $rec = array(
                "ProctorID" => $_POST['ProctorID'],
                "ExamID" => $_POST['ExamID'],
                "ScheduleID" => $_POST['ScheduleID'],
                "RollNo" => $row->id,
                "StudentID" => $row->id,
                "FirstName" => $row->firstname,
                "LastName" => $row->lastname,
                "MobileNo" => $phone,
                "EmailID" => $row->email,
                "CourseID" => $_POST['courseid'],
                "UserEmailID" => $myuseremailid,
                "UserName" => $fullusername,
                "IPAddress" => $myipadrs
            );

            array_push($objary, $rec);
        }
    }

    $datatosend = array("obj" => $objary);


    $posturl = $apiurl . '/SplashService.svc/AssignProctorToStudentWebAPI';

    $responsearray = get_api_response_withdata($posturl, $datatosend);

    $apiresultarray = $responsearray["AssignProctorToStudentWebAPIResult"];

    $responsemsg = $apiresultarray[0]["Message"];
    $status = ($response['AssignProctorToStudentWebAPIResult'][0]['objStatusCodes']);

    ?>
    <script>
        alert(" <?php echo $responsemsg; ?> ");
    </script>
    <?php


    echo $responsemsg;
}
