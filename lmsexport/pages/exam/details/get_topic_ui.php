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

// ---------------------------------------------

if (isset($_POST['section']) && ($_POST['section'] != '')) {
    $sec = $_POST['section'];
} else {
    $sec = 0;
}




if (isset($_POST['courseid'])) {
    $sql = "Select cm.id as cmid, mq.*, cm.instance, cm.module as cm_module, ".
    "cm.section as cm_section, module.name as module_name, cs.name as course_sections_name, ".
    "c.format from " . $CFG->prefix . "course c INNER JOIN " . $CFG->prefix .
    "course_modules cm ON c.id=cm.course INNER JOIN " . $CFG->prefix .
    "modules module ON module.id=cm.module INNER JOIN " . $CFG->prefix .
    "course_sections cs ON cs.id=cm.section INNER JOIN " . $CFG->prefix .
    "quiz mq on cm.instance=mq.id where module.name='quiz' and c.id=" .
    $_POST['courseid'] . " and  cm.deletioninprogress=0 limit 0,100 ";

    $topics = $DB->get_records_sql($sql);

    $section = [];
    $withoutsect = [];
    foreach ($topics as $singletop) {
        if (!$singletop->course_sections_name) {
            $withoutsect[] = $singletop;
        } else {

            if (array_key_exists("sec_" . $singletop->cm_section, $section)) {
                $section["sec_" . $singletop->cm_section][] = [
                    'name' => $singletop->course_sections_name,
                    'sectionid' => $singletop->cm_section,
                    'data' => $singletop
                    ];
            } else {
                $section["sec_" . $singletop->cm_section][] = [
                    'name' => $singletop->course_sections_name,
                    'sectionid' => $singletop->cm_section,
                    'data' => $singletop
                ];
            }
        }
    }
}

if (!empty($section)) {
?>
    <div class="row m-t-1 text-right">

        <div class="col-md-3"><label class="text center">Select Topic</label></div>
        <div class="col-md-6" style="margin:5px ;">

            <select name="section" id="section" class="form-control" onchange="changetopic();">
                <option value="">Select</option>
                <?php foreach ($section as $singlecoursesection) { ?>
                    <option value="<?php echo $singlecoursesection[0]['sectionid']; ?>" <?php if (isset($_POST['section'])) {
                        if ($_POST['section'] == $singlecoursesection[0]['sectionid']) {
                            echo 'selected="selected"';
                        }
                                   } ?>><?php echo $singlecoursesection[0]['name']; ?></option>
                <?php } ?>
            </select>
        </div>

    </div>
    <?php
} else {
?>
    <div class="row m-t-1 text-left">

        <div class="col-md-3"><label class="text center"></label></div>
        <div class="col-md-6">
            Topic not Found!
        </div>
    </div>
    <div class="row m-t-1 text-right">

    </div>
    <?php

}
