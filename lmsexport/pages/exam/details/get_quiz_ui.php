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



if (isset($_POST['courseid'])) {

    $sql = "Select cm.id as cmid, mq.*, cm.instance, cm.module as cm_module, cm.section as cm_section," .
        "module.name as module_name, cs.name as coursesectionsname, c.format from " . $CFG->prefix .
        "course c INNER JOIN " . $CFG->prefix . "course_modules cm ON c.id=cm.course INNER JOIN " . $CFG->prefix .
        "modules module ON module.id=cm.module INNER JOIN " . $CFG->prefix .
        "course_sections cs ON cs.id=cm.section INNER JOIN " . $CFG->prefix .
        "quiz mq on cm.instance=mq.id where module.name='quiz' and c.id=" . $_POST['courseid'] .
        " and  cm.deletioninprogress=0 limit 0,100 ";

    $topics = $DB->get_records_sql($sql);

    $section = [];
    $withoutsect = [];
    foreach ($topics as $singletop) {
        if (!$singletop->coursesectionsname) {
            $withoutsect[] = $singletop;
        } else {

            if (array_key_exists("sec_" . $singletop->cm_section, $section)) {
                $section["sec_" . $singletop->cm_section][] = [
                    'name' => $singletop->coursesectionsname,
                    'section_id' => $singletop->cm_section,
                    'data' => $singletop
                ];
            } else {
                $section["sec_" . $singletop->cm_section][] = [
                    'name' => $singletop->coursesectionsname,
                    'section_id' => $singletop->cm_section,
                    'data' => $singletop
                ];
            }
        }
    }
}


if (isset($_POST['aso'])) {
    $quizby = $_POST['aso'];
} else {
    $quizby = 0;
}

if ($quizby == 1) {
?>
    <div class="row m-t-1 text-right">

        <div class="col-md-3"><label class="text center">Select quiz</label></div>
        <div class="col-md-6" style="margin:5px ;">
            <select name="withoutsection" id="withoutsection" class="form-control" onchange="changewithoutsection();">
                <option value="">Select</option>
                <?php foreach ($withoutsect as $singlecoursewithoutsect) { ?>
                    <option value="<?php echo $singlecoursewithoutsect->id; ?>" <?php if (isset($_POST['withoutsection'])) {
                        if ($_POST['withoutsection'] == $singlecoursewithoutsect->id) {
                            echo 'selected="selected"';
                        }
                                   } ?>><?php echo $singlecoursewithoutsect->name; ?></option>
                <?php } ?>
            </select>
        </div>

    </div>
    <?php
} else if ($quizby == 2) {
?>
    <div class="row m-t-1 text-right">

        <div class="col-md-3"><label class="text center">Select quiz</label></div>
        <div class="col-md-6" style="margin:5px ;">
            <select name="quiz" id="quiz" class="form-control" onchange="changequiz();">
                <option value="">Select</option>
                <?php foreach ($section["sec_" . $_POST['section']] as $singlecoursesection) {
                    ?>
                    <option <?php if ($_REQUEST['quiz'] == $singlecoursesection['data']->id) {
                        ?> 
                        selected 
                            <?php } ?> 
                        value="<?php echo $singlecoursesection['data']->id; ?>">
                        <?php echo  $singlecoursesection['data']->name; ?></option>
                <?php } ?>
            </select>
        </div>

    </div>


    <?php

}
