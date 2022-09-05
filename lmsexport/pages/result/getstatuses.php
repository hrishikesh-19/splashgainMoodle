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

?>

<div style="margin:5px ;">
    <label>Select Exam Status</label>
    <select id="status" name="status" class="form-control" onchange='show_result_table();'>
        <option value="">Select</option>
        <option value="0" <?php if (isset($_POST['status'])) {
            if ($_POST['status'] == 0) {
                echo 'selected="selected"';
            }
                          } ?>>All</option>
        <option value="1" <?php if (isset($_POST['status'])) {
            if ($_POST['status'] == 1) {
                echo 'selected="selected"';
            }
                          } ?>>Yet To Start</option>
        <option value="2" <?php if (isset($_POST['status'])) {
            if ($_POST['status'] == 2) {
                echo 'selected="selected"';
            }
                          } ?>>Resume</option>
        <option value="3" <?php if (isset($_POST['status'])) {
            if ($_POST['status'] == 3) {
                echo 'selected="selected"';
            }
                          } ?>>Completed</option>
        <option value="4" <?php if (isset($_POST['status'])) {
            if ($_POST['status'] == 4) {
                echo 'selected="selected"';
            }
                          } ?>>Expired</option>
    </select>
</div>
