<?php
// This file is part of Moodle - http://moodle.org/local/imsexport/pages/result/
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

require_login();

global $USER, $DB;

$eklavyaobj = $DB->get_record_sql("SELECT apiurl, examapikey,timezone1 FROM {local_lmsexport}");

$apiurl = $eklavyaobj->apiurl;


if (isset($_POST['CourseID'])) {

    $courseid = $_POST['CourseID'];



    $datatosend = array("obj" => array("CourseID" => $courseid));

    $posturl = $apiurl . '/SplashService.svc/GetMoodleExamNameWebAPI';

    $responsearray = get_api_response_withdata($posturl, $datatosend);

    $examslist = $responsearray["GetMoodleExamNameWebAPIResult"];

?>

    <div>
        <?php if (!empty($examslist)) { ?>
            <div style="margin:5px ;">
                <label>Select Exam</label>
                <select name="examid" id="examid" class="form-control" onchange="show_shedule();">
                    <option value="">Select</option>

                    <?php foreach ($examslist['MoodleExamObj'] as $singleexams) {
                    ?>
                        <option value="<?php echo $singleexams['ExamID']; ?>" <?php if (isset($_POST['ExamID'])) {
                            if ($_POST['ExamID'] == $singleexams['ExamID']) {
                                echo 'selected="selected"';
                            } else {
                                echo "";
                            }
                                       } ?>><?php echo $singleexams['ExamName']; ?></option>
                    <?php }  ?>

                </select>
            </div>
            <?php
        } else {
        ?>
            <div style="margin:5px ;">
                No Exams Found!
            </div>
            <?php

        }

        ?>


    </div>


    <?php
} else {
    echo 'Please Select Course Properly.';
}
