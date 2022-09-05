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


require_once($CFG->libdir . '/gradelib.php');
require_once($CFG->dirroot . '/grade/lib.php');
require_once($CFG->dirroot . '/grade/import/lib.php');
require_once($CFG->libdir . '/csvlib.class.php');


global $USER, $DB;

$eklavyaobj = $DB->get_record_sql("SELECT apiurl, examapikey,timezone1 FROM {local_lmsexport}");

$apiurl = $eklavyaobj->apiurl;


if (isset($_POST['courseid'])) {
    $courseidvar = $_POST['courseid'];
} else {
    $courseidvar = 0;
}


if (isset($_POST['MoodleExamID'])) {
    $moodleexamidvar = $_POST['MoodleExamID'];
} else {
    $moodleexamidvar = 0;
}


if (isset($_POST['csvstr'])) {
    $retcsvstrvar = $_POST['csvstr'];
} else {
    $retcsvstrvar = '';
}

$gradeitem = $DB->get_record_sql("SELECT * FROM {grade_items} where courseid=" .
$courseidvar . " AND iteminstance=" . $moodleexamidvar . " ");

$examid = $gradeitem->id;



$id = $courseidvar;
$verbosescales = 1;
$iid           = null;
$importcode    = '';
$forceimport   = true;

require_login($course);
$context = context_course::instance($id);
require_capability('moodle/grade:import', $context);
require_capability('gradeimport/direct:view', $context);

$separatemode = (groups_get_course_groupmode($COURSE) == SEPARATEGROUPS and
    !has_capability('moodle/site:accessallgroups', $context));
$currentgroup = groups_get_course_group($course);

$prvrow = 0;
$text = $retcsvstrvar;
$csvimport = new gradeimport_csv_load_data();
$csvimport->load_csv_content($text, 'UTF-8', 'comma', $prvrow);
$csvimporterror = $csvimport->get_error();


$iid = $csvimport->get_iid();

$csvimport = new csv_import_reader($iid, 'grade');
$header = $csvimport->get_columns();

if (empty($importcode)) {
    $importcode = get_new_importcode();
}


$tempstr = '{"mapfrom":"0","mapto":"userid","mapping_0":"0","mapping_1":"' .
    $examid . '","map":1,"id":7,"iid":' . $importcode . ',"importcode":"' .
    $importcode . '","verbosescales":"' . $importcode .
    '","groupid":false,"forceimport":1,"submitbutton":"Upload grades"}';

$formdata = json_decode($tempstr);

$gradeimport = new gradeimport_csv_load_data();
$status = $gradeimport->prepare_import_grade_data(
    $header,
    $formdata,
    $csvimport,
    $course->id,
    $separatemode,
    $currentgroup,
    $verbosescales
);

if ($status) {

    grade_import_commit($courseidvar, $importcode);

    ob_end_clean();

    echo 'Grades Updated!';
} else {

    echo 'ERROR!';

}
