<?php
// This file is part of Moodle - http://moodle.org/local/imsexport/pages/exam/details/
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

$courses = array();

if (isset($_POST['courseid'])) {
    $courseid = $_POST['courseid'];
}

if (is_siteadmin()) {
    $courses = get_courses();
} else {
    $courses = enrol_get_all_users_courses($USER->id);
}


?>
<div class="row m-t-1 text-right">

    <?php if (!empty($courses)) { ?>
        <div class="col-md-3"><label class="text center">Select Course</label></div>
        <div class="col-md-6" style="margin:5px ;">
            <select name="courseid" id="courseid" class="form-control" onchange="changecourse();">
                <option value="">Select</option>
                <?php foreach ($courses as $singlecourse) {
                    if ($singlecourse->format != "site") {
                ?>
                        <option value="<?php echo $singlecourse->id; ?>" <?php if (isset($courseid)) {
                            if ($courseid == $singlecourse->id) {
                                                                                    echo 'selected="selected"';
                            }
                                       } ?>><?php echo $singlecourse->fullname; ?></option>
                            <?php
                    }
                } ?>
            </select>
        </div>
        <?php
    } else {
    ?>
        <div style="margin:5px ;">
            No Courses Found!
        </div>
        <?php

    }

    ?>
</div>
