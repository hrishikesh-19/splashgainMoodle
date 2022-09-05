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


require_once("../../../config.php");
require_once("../conf.php");
require_once("pgnfunc.php");

require_login();

error_reporting(1);



global $USER, $DB;


$eklavyaobj = $DB->get_record_sql("SELECT apiurl, examapikey,timezone1 FROM {local_lmsexport}");

$apiurl = $eklavyaobj->apiurl;



// ---------------------------------------------


if (is_siteadmin()) {
    $courses = get_courses();
} else {
    $courses = enrol_get_all_users_courses($USER->id);
}


// Get Data From API.

if (count($courses) > 0) {


    $datatosend = array("obj" => array("CourseID" => 0));
    $posturl = $apiurl . '/SplashService.svc/GetDashBoardDataWebAPI';

    $result = get_api_response_withdata($posturl, $datatosend);

    $apiresultarray = $result["GetDashBoardDataWebAPIResult"];

?>

    <!-- 
            <div class="card" style="width: 18rem; margin:20px">
                <div class="card-header">
                    Dashboard
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Registered Users : <span class="text-right">
                         <b> <?php echo $apiresultarray['StudentCount']; ?> </b>
                     </span> </li>
                    <li class="list-group-item">Total Exam Count : 
                        <span class="text-right"> <b> <?php echo $apiresultarray['TotalExamsByInstituteID']; ?></b>
                     </span> </li>                    
                </ul>
            </div> -->


    <hr>

    <div id="title_ui" class="col-md-12 pt-4">
        <h4> Candidate(s) Exam Status </h4>
    </div>

    <div class="d-flex flex-wrap">



        <?php


        foreach ($courses as $singlecourse) {


            $datatosend = array("obj" => array("CourseID" => $singlecourse->id));
            $posturl = $apiurl . '/SplashService.svc/GetDashBoardDataWebAPI';

            $result = get_api_response_withdata($posturl, $datatosend);

            $apiresultarray = $result["GetDashBoardDataWebAPIResult"];


        ?>


            <div class="card" style="width: 18rem; margin:20px">
                <div class="card-header">
                    <b>
                        <?php echo $singlecourse->fullname; ?>
                    </b>
                </div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item">Total Exams : <span class="text-right"> 
                        <b> <?php echo $apiresultarray['TotalExamsByMoodleCategoryID']; ?></b> 
                    </span> </li>
                    <li class="list-group-item">Completed : <span class="text-right text-bold"> 
                        <b> <?php echo $apiresultarray['CompletedCount']; ?> </b> 
                    </span> </li>
                    <li class="list-group-item">In Progress : <span class="text-right"> <b>
                         <?php echo $apiresultarray['InProgressCount']; ?> </b> 
                        </span> </li>
                    <li class="list-group-item">Not Started : <span class="text-right"> 
                        <b> <?php echo $apiresultarray['NotStartedCount']; ?> </b>
                    </span> </li>

                </ul>
            </div>

            <?php
        }
        ?>
    </div>

    <?php
}
