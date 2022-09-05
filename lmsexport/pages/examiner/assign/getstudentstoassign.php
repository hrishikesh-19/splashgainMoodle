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





if (isset($_POST['courseid']) && isset($_POST['ExamID']) && isset($_POST['ScheduleID'])) {

    $datatosend = array("obj" => array(
        "CourseID" => $_POST['courseid'],
        "ExamID" => $_POST['ExamID'],
        "ScheduleID" => $_POST['ScheduleID']
        ));

    $posturl = $apiurl . '/SplashService.svc/GetStudentDetailsForExaminerWebAPI';

    $responsearray = get_api_response_withdata($posturl, $datatosend);

    $apiresultarray = $responsearray["GetStudentDetailsForExaminerWebAPIResult"];
    $asprstudents = $apiresultarray["StudentDetailsListObj"];
} else {
    echo 'No Data Avaliable! to Assignment.';
}



$datatosend = array("obj" => array());

$posturl = $apiurl . '/SplashService.svc/GetExaminersListWebAPI';

$responsearray = get_api_response_withdata($posturl, $datatosend);

$apiresultarray = $responsearray["GetExaminersListWebAPIResult"];
$examinerlist = $apiresultarray["ExaminersListObj"];

?>

<div class="col-sm-12 table-responsive">
    <table class="table table-bordered table-hover table-striped">
        <tr>
            <th> # </th>
            <th> Student Name </th>
            <th> Email ID </th>
            <th> Is Examiner Assigned </th>
            <th> Examiner Name </th>
            <th> Examiner Email ID </th>

        </tr>

        <?php

        foreach ($asprstudents as $row) {

            echo '<TR>';
            if ($row['IsExaminerAssigned'] == 1) {
                $isaspr = 1;
            } else {
                $isaspr = 0;
            }

            if ($isaspr != 1) {
                if (in_array($row['StudentID'], $_POST['rcbx'])) {
                    echo '<td><input type="checkbox" name="rcbx[]" id="rcbx"  value="' . $row['StudentID'] . '" checked /></td>';
                } else {
                    echo '<td><input type="checkbox" name="rcbx[]" id="rcbx"  value="' . $row['StudentID'] . '" /></td>';
                }
            } else {
                echo '<td>-</td>';
            }

            echo '<td>' . $row['StudentName'] . '</td>';
            echo '<td>' . $row['EmailID'] . '</td>';
            if ($isaspr) {
                echo '<td>Yes</td>';
            } else {
                echo '<td>No</td>';
            }

            echo '<td>' . $row['ExaminerName'] . '</td>';
            echo '<td>' . $row['ExaminerEmailID'] . '</td>';

            echo '</TR>';
        }

        ?>
    </table>
    <div class="col-sm-12 text-center">
        <input type="button" id="submitbtn" name="continue" class="m-element-button btn btn-primary" 
        value="Assign" onclick="getexaminerlist();" />
    </div>
</div>

<?php
