<?php
// This file is part of Moodle - http://moodle.org/local/imsexport/pages/proctor/
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



if (isset($_POST['pageno'])) {
    $pageno = $_POST['pageno'];
    $_SESSION['proctorlist_pageno'] = $pageno;
}

if (isset($_SESSION['proctorlist_pageno'])) {
    $pageno = $_POST['pageno'];
}


if (isset($_POST['pagesize'])) {
    $pagesize = $_POST['pagesize'];
} else {
    $pagesize = 10;
}


$datatosend = array("obj" => array("Offset" => $pageno, "PageSize" => $pagesize));
$posturl = $apiurl . '/SplashService.svc/GetProctorsListWebAPI';

$proctorlist = get_api_response_withdata($posturl, $datatosend);

$apiresultarray = $proctorlist["GetProctorsListWebAPIResult"];
$proctorlisttableobject = $apiresultarray["ProctorsListObj"];
$mxp = $apiresultarray["TotalPages"];





if (count($proctorlisttableobject) >= 0) {

?>
    <div class="col-sm-12 table-responsive">
        <table class="table table-bordered table-hover table-striped">
            <tr>
                <th><B> Proctor Name </B> </th>
                <th><B> Proctor Email ID </B> </th>
                <th><B> Assigned Exams Count </B> </th>
                <th><B> Status </B> </th>
                <th><B> Actions </B> </th>
            </tr>
            <?php


            foreach ($proctorlisttableobject as $row) {
                echo '<tr>';
                echo '<td>' . $row['ProctorName'] . '</td>';
                echo '<td>' . $row['ProctorEmailID'] . '</td>';
                echo '<td>' . $row['AssignedExamsCount'] . '</td>';

                if ($row['IsActive']) {
                    echo '<td>Active</td>';
                } else {
                    echo '<td>Inactive</td>';
                }
                echo '<td>';
            ?>
                <form action="<?php echo new moodle_url('/local/lmsexport/pages/proctor/editproctor.php'); ?>"
                 method="POST">
                    <input type="hidden" id="current_page_no" name="current_page_no" value="<?php echo $pageno; ?>">
                    <input type="hidden" id="edit_rec_id" name="edit_rec_id" 
                    value="<?php echo $row['ProctorEmailID']; ?>">
                    <input type="submit" id="editrec" value="Edit" class="btn btn-primary">
                    <?php
                    if ($row['IsActive']) {
                    ?>
                        <input type='button' value='Disable' class="btn btn-danger"
                        onclick='del_rec("<?php echo $row["ProctorEmailID"]; ?>");'>
                        <?php
                    } else {
                    ?>
                        <input type='button' value='Enable' class="btn-success btn"
                        onclick='undel_rec("<?php echo $row["ProctorEmailID"]; ?>");'>
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

                echo "<input type='button' id='curpageno' class='selected_pagination btn btn-icon
                btn-outline-info' value='" . $i . "' onclick='show_page(" . $i . ");'>";
            } else {
                echo "<input type='button' value='" . $i . "' class='btn btn-icon btn-outline-info'
                onclick='show_page(" . $i . ");'>";
            }

            $i++;
        }

        ?>


    </div>

    <?php
} else {
    echo 'No Data Found!';
}
