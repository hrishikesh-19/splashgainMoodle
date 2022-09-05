<?php
// This file is part of Moodle - http://moodle.org/local/imsexport/pages/examiner/
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

global $USER, $DB;

error_reporting(1);

require_login();

$eklavyaobj = $DB->get_record_sql("SELECT apiurl, examapikey,timezone1 FROM {local_lmsexport}");

$apiurl = $eklavyaobj->apiurl;

if (isset($_POST['pageno'])) {
    $pageno = $_POST['pageno'];
    $_SESSION['examinerlistpageno'] = $pageno;
}

if (isset($_SESSION['examinerlistpageno'])) {
    $pageno = $_POST['pageno'];
}


if (isset($_POST['pagesize'])) {
    $pagesize = $_POST['pagesize'];
} else {
    $pagesize = 10;
}


$datatosend = array("obj" => array("Offset" => $pageno, "PageSize" => $pagesize));
$posturl = $apiurl . '/SplashService.svc/GetExaminersListWebAPI';

$examinerlist = get_api_response_withdata($posturl, $datatosend);

$apiresultarray = $examinerlist["GetExaminersListWebAPIResult"];
$examinerlisttableobject = $apiresultarray["ExaminersListObj"];
$mxp = $apiresultarray["TotalPages"];

if (count($examinerlisttableobject) >= 0) {

?>
    <div class="col-sm-12 table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <th><B> Examiner Name </B> </th>
                <th><B> Examiner Email ID </B> </th>
                <th><B> Assigned Exams Count </B> </th>
                <th><B> Status </B> </th>
                <th><B> Actions </B> </th>
            </tr>
            <?php

            foreach ($examinerlisttableobject as $row) {
                echo '<tr>';
                echo '<td>' . $row['ExaminerName'] . '</td>';
                echo '<td>' . $row['ExaminerEmailID'] . '</td>';
                echo '<td>' . $row['AssignedExamsCount'] . '</td>';

                if ($row['IsActive']) {
                    echo '<td>Active</td>';
                } else {
                    echo '<td>Inactive</td>';
                }

                echo '<td>';
            ?>
                <form action="<?php echo new moodle_url('/local/lmsexport/pages/examiner/editexaminer.php'); ?>" method="POST">
                    <input type="hidden" id="current_page_no" name="current_page_no" value="<?php echo $pageno; ?>">
                    <input type="hidden" id="editrecid" name="editrecid" value="<?php echo $row['ExaminerEmailID']; ?>">
                    <input type="submit" id="editrec" value="Edit" class="btn btn-primary">
                    <?php
                    if ($row['IsActive']) {
                    ?>
                        <input type='button' value='Disable' class="btn btn-danger" 
                        onclick='del_rec("<?php echo $row["ExaminerEmailID"]; ?>");'>
                        <?php
                    } else {
                    ?>
                        <input type='button' value='Enable' class="btn-success btn" 
                        onclick='undel_rec("<?php echo $row["ExaminerEmailID"]; ?>");'>
                        <?php
                    }
                    ?>

                </form>

                <?php
                echo '</td>';
                echo '</tr>';
            }

            ?>
        </table>
    </div>
    <div class="col-sm-12 text-center mt-3 mb-3">

        <?php

        $i = 1;
        while ($i <= $mxp) {

            if ($i == $pageno) {

                echo "<input type='button' id='curpageno' ".
                "class='selected_pagination btn btn-icon btn-outline-info' value='" . $i .
                "' onclick='show_page(" . $i . ");'>";
            } else {
                echo "<input type='button' value='" . $i .
                "' class='btn btn-icon btn-outline-info' onclick='show_page(" . $i . ");'>";
            }

            $i++;
        }


        ?>


    </div>

    <?php
} else {
    echo 'No Data Found!';
}
