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


$datatosend = array("obj" => array());

$posturl = $apiurl . '/SplashService.svc/GetExaminersListWebAPI';

$responsearray = get_api_response_withdata($posturl, $datatosend);

$apiresultarray = $responsearray["GetExaminersListWebAPIResult"];
$examinerlist = $apiresultarray["ExaminersListObj"];

?>

<div style="vertical-align:center ; align-content:center;  width: 350px; position: relative; left: 50%; top: 50%;
                margin-left: -210px; margin-top: -255px; background:white; box-shadow: 1px 1px 5px #444444;
                padding: 20px 40px 40px 40px;">
    <div>
        <?php if (!empty($examinerlist)) { ?>
            <div style="margin:5px ;">
                <label>Select examiner</label>
                <select name="ExaminerID" id="ExaminerID" class="form-control">
                    <option value="">Select</option>
                    <?php foreach ($examinerlist as $singleproc) {
                        ?>
                        <option value="<?php echo $singleproc['ExaminerID']; ?>" <?php if (isset($_POST['ExaminerID'])) {
                            if ($_POST['ExaminerID'] == $singleproc['ExaminerID']) {
                                echo 'selected="selected"';
                            }
                                       } ?>> <?php echo $singleproc['ExaminerName']; ?></option>
                    <?php }  ?>
                </select>
            </div>
            <?php
        } else {
        ?>
            <div style="margin:5px ;">
                No examiner Found!
            </div>
            <?php

        }

        ?>

    </div>
    <div>
        <div class="text-center">
            <hr />
            <input type="button" id="assignexaminer_submitbtn" name="continue" 
            class="m-element-button btn btn-primary" value="Assign " width="80%" 
            onclick="assign_examiner();" />

            <input type="button" id="assignexaminer_cancelbtn" name="continue" 
            class="m-element-button btn btn-danger" value="Cancel " width="80%" 
            onclick="assign_examiner_cancel();" />
        </div>
    </div>

</div>
