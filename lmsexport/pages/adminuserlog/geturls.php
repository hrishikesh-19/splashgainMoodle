<?php
// This file is part of Moodle - http://moodle.org/local/imsexport/pages/adminuserlog/
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

error_reporting(1);

global $USER, $DB;

$eklavyaobj = $DB->get_record_sql("SELECT apiurl, examapikey,timezone1 FROM {local_lmsexport}");

$apiurl = $eklavyaobj->apiurl;

require_login();

$eml = $USER->email;



$datatosend = array("obj" => array("EmailID" => $eml));

$posturl = $apiurl . '/SplashService.svc/GetInstituteUserLogsWebAPI';

$response = get_api_response_withdata($posturl, $datatosend);

$apiresultarray = $response["GetInstituteUserLogsWebAPIResult"];

$responsecandidatelogsurl = $apiresultarray["CandidateLogsURL"];
$responseexaminerlogsurl = $apiresultarray["ExaminerLogsURL"];
$responseproctorlogsurl = $apiresultarray["ProctorLogsURL"];

?>
<div class="col-md-12 row" style=" margin-Bottom:20px;">
    <div class="col-md-3 text-right">
        Proctor Logs :
    </div>
    <div class="col-md-6">
        <?php
        if (!empty($responseproctorlogsurl)) {
        ?>
            <a href="<?php echo $responseproctorlogsurl; ?>">
                <button class="btn btn-outline-primary" style="width: 100%;">View Proctor Logs </button>
            </a>
        <?php } else {
            ?>
            No Proctor Logs
            <?php
        }
        ?>

    </div>
    <div class="col-md-3">

    </div>
</div>


<div class="col-md-12 row" style=" margin-Bottom:20px;">
    <div class="col-md-3 text-right">
        Examiner Logs :
    </div>
    <div class="col-md-6">
        <?php if (!empty($responseexaminerlogsurl)) { ?>
            <a href="<?php echo $responseexaminerlogsurl; ?>">
                <button class="btn btn-outline-primary" style="width: 100%;">View Examiner Logs </button>
            </a>
        <?php } else {
        ?>
            No Examiner Logs Found!
            <?php
        }
        ?>

    </div>
    <div class="col-md-3">

    </div>
</div>


<div class="col-md-12 row" style=" margin-Bottom:20px;">
    <div class="col-md-3 text-right">
        Candidate Logs :
    </div>
    <div class="col-md-6 ">

        <?php
        if (!empty($responsecandidatelogsurl)) {
        ?>
            <a href="<?php echo $responsecandidatelogsurl; ?>">
                <button class="btn btn-outline-primary" style="width: 100%;">View Candidate Logs </button> 
            </a>
            <?php
        } else {
        ?>
            No Candidate Logs Found!
            <?php
        }
        ?>

    </div>
    <div class="col-md-3">

    </div>
</div>
