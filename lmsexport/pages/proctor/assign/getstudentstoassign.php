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

if (isset($_POST['courseid']) && isset($_POST['ExamID']) && isset($_POST['ScheduleID'])) {


    $studlist = enrol_get_course_users($_POST['courseid']);

    $datatosend = array("obj" => array("CourseID" => $_POST['courseid'], "ExamID" => $_POST['ExamID'],
    "ScheduleID" => $_POST['ScheduleID']));

    $posturl = $apiurl . '/SplashService.svc/GetStudentDetailsWebAPI';

    $responsearray = get_api_response_withdata($posturl, $datatosend);

    $apiresultarray = $responsearray["GetStudentDetailsWebAPIResult"];

    $asprstudents = $apiresultarray["StudentDetailsListObj"];
} else {
    echo 'No Data Avaliable! to Assignment.';
}





$datatosend = array("obj" => array());

$posturl = $apiurl . '/SplashService.svc/GetProctorsListWebAPI';

$responsearray = get_api_response_withdata($posturl, $datatosend);

$apiresultarray = $responsearray["GetProctorsListWebAPIResult"];
$proctorlist = $apiresultarray["ProctorsListObj"];



?>

<div class="col-sm-12 table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <th> # </th>
            <th> User Name </th>
            <th> First Name </th>
            <th> Last Name </th>
            <th> Email ID </th>
            <th> Is Proctor Assigned </th>
            <th> Proctor Name </th>
            <th> Proctor Email ID </th>

        </tr>

        <?php

        foreach ($studlist as $row) {
            if (user_has_role_assignment($row->id, 5)) {
                $isaspr = 0;
                $aspremail = '';
                $asprprid = '';
                $asprprname = '';
                if (count($asprstudents) > 0) {
                    foreach ($asprstudents as $asprst) {
                        if ($row->id == $asprst['StudentID']) {
                            if (isset($asprst['IsProctorAssigned'])) {
                                if ($asprst['IsProctorAssigned']) {
                                    $isaspr = 1;
                                } else {
                                    $isaspr = 0;
                                }
                            } else {
                                $isaspr = 0;
                            }
                            $aspremail = $asprst['ProctorEmailID'];
                            $asprprid = $asprst['ProctorID'];
                            $asprprname = $asprst['ProctorName'];
                            break;
                        }
                    }
                } else {
                    $isaspr = 0;
                    $aspremail = '';
                    $asprprid = '';
                    $asprprname = '';
                }

                echo '<TR>';


                if ($isaspr != 1) {
                    if (in_array($row->id, $_POST['rcbx'])) {
                        echo '<td><input type="checkbox" name="rcbx[]" id="rcbx"  value="' .
                        $row->id . '" checked /></td>';
                    } else {
                        echo '<td><input type="checkbox" name="rcbx[]" id="rcbx"  value="' .
                        $row->id . '" /></td>';
                    }
                } else {
                    echo '<td>-</td>';
                }

                echo '<td>' . $row->username . '</td>';
                echo '<td>' . $row->firstname . '</td>';
                echo '<td>' . $row->lastname . '</td>';
                echo '<td>' . $row->email . '</td>';
                if ($isaspr) {
                    echo '<td>Yes</td>';
                } else {
                    echo '<td>No</td>';
                }

                echo '<td>' . $asprprname . '</td>';
                echo '<td>' . $aspremail . '</td>';

                echo '</TR>';
            } else {
                echo "Not a Student";
            }
        }

        ?>
    </table>
    <div class="col-sm-12 text-center">
        <input type="button" id="submitbtn" name="continue" class="m-element-button btn btn-primary" 
        value="Assign" onclick="show_proclist();" />
    </div>
</div>
