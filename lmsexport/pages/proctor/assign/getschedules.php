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


if (isset($_POST['ExamID'])) {

    $examid = $_POST['ExamID'];

    $$datatosend = array("obj" => array("ExamID" => $examid));

    $posturl = $apiurl . '/SplashService.svc/GetMoodleExamScheduleWebAPI';

    $responsearray = get_api_response_withdata($posturl, $$datatosend);

    $shedulelist = $responsearray["GetMoodleExamScheduleWebAPIResult"];
?>

    <div>
        <?php if (!empty($shedulelist)) { ?>
            <div style="margin:5px ;">
                <label>Select Exam Schedule</label>
                <select name="ScheduleID" id="ScheduleID" class="form-control" onchange=" show_students();">
                    <option value="">Select</option>
                    <?php foreach ($shedulelist['MoodleExamScheduleObj'] as $singlesch) {
                    ?>
                        <option value="<?php echo $singlesch['ScheduleID']; ?>" <?php if (isset($_POST['ScheduleID'])) {
                            if ($_POST['ScheduleID'] == $singlesch['ScheduleID']) {
                                echo 'selected="selected"';
                            }
                                       } ?>><?php echo $singlesch['ScheduleDate']; ?></option>
                    <?php } ?>

                </select>
            </div>
            <?php
        } else {
        ?>
            <div style="margin:5px ;">
                No Exam Schedule Found!
            </div>
            <?php

        }

        ?>


    </div>


    <?php
} else {
    echo 'Please Select Course Properly.';
}

