<?php
// This file is part of Moodle - http://moodle.org/local/imsexport/pages/exam/
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

require_login();


?>




<?php


$eklavyaobj = $DB->get_record_sql("SELECT apiurl, examapikey,timezone1 FROM {local_lmsexport}");

$apiurl = $eklavyaobj->apiurl;



if (isset($_POST['courseid'])) {
    $courseid = $_POST['courseid'];
} else {
    $courseid = 0;
}

$_SESSION['examlist_courseid'] = $courseid;



if (isset($_POST['pageno'])) {
    $pageno = $_POST['pageno'];
    $_SESSION['examlist_pageno'] = $pageno;
} else {

    if (isset($_SESSION['examlist_pageno'])) {
        $pageno = $_SESSION['examlist_pageno'];
    } else {
        $pageno = 1;
        $_SESSION['examlist_pageno'] = $pageno;
    }
}


if (isset($_POST['pagesize'])) {
    $pagesize = $_POST['pagesize'];
} else {
    $pagesize = 10;
}

if ($pageno) {

    $datatosend = array("obj" => array("CourseID" => $courseid, "Offset" => $pageno, "PageSize" => $pagesize));
    $posturl = $apiurl . '/SplashService.svc/GetMoodleExamDetailsForEditWebAPI';

    $response = get_api_response_withdata($posturl, $datatosend);


    $mxp = $response['GetMoodleExamDetailsForEditWebAPIResult']["TotalPages"];
    $examslist = $response['GetMoodleExamDetailsForEditWebAPIResult']['MoodleExamObj'];

}

if (count($examslist) >= 0) {

?>
  <div class="col-sm-12 table-responsive">
    <table class="table table-bordered table-hover table-striped">
      <tr>
        <th><strong> <b>Exam Name </b> </strong></th>
        <th> <b>Exam Shedule </b></th>
        <th> <b>Duration </b></th>
        <th> <b>Allow Proctoring </b></th>
        <th> <b># </b></th>
      </tr>
      <?php

        foreach ($examslist as $row) {

            echo '<TR>';

            echo '<td>' . $row['ExamName'] . '</td>';
            echo '<td>' . $row['ScheduleDate'] . '</td>';
            echo '<td>' . $row['ExamDuration'] . '</td>';

            if ($row['AllowRemoteProctoring'] == 1) {
                echo '<td>Yes</td>';
                $arp = 'Yes';
            } else {
                echo '<td>No</td>';
                $arp = 'No';
            }


            if ($row['AudioStreaming'] == 1) {
                $audstrm = 'Yes';
            } else {
                $audstrm = 'No';
            }

            if ($row['ScreenStreaming'] == 1) {
                $scrnstrm = 'Yes';
            } else {
                $scrnstrm = 'No';
            }

            if ($row['CaptureCandidatePhoto'] == 1) {
                $photocap = 'Yes';
            } else {
                $photocap = 'No';
            }


            if ($row['IsCreatedFromMoodle'] == "Yes") {

                echo '<td>';

        ?>
              <form action="<?php echo new moodle_url('/local/lmsexport/pages/exam/details/index.php'); ?>" method="POST">
                <input type="hidden" id="edit_rec_id" name="edit_rec_id" value="<?php echo $row['ExamID']; ?>">

                <input type="hidden" id="courseid" name="courseid" value="<?php echo $courseid; ?>">

                <?php
                if ($row['MoodleTopicID'] > 0) {
                ?>
                    <input type="hidden" id="associated" name="associated" value="2">
                    <?php
                } else {
                ?>
                    <input type="hidden" id="associated" name="associated" value="1">
                    <?php
                }

                ?>

                <input type="hidden" id="associatedq1" name="associatedq1" value="<?php echo $arp; ?>">
                <input type="hidden" id="associatedq2" name="associatedq2" value="<?php echo $audstrm; ?>">
                <input type="hidden" id="associatedq3" name="associatedq3" value="<?php echo $scrnstrm; ?>">
                <input type="hidden" id="associatedq4" name="associatedq4" value="<?php echo $photocap; ?>">
                <input type="hidden" id="txt1" name="txt1" value="<?php echo $row['ImageCaptureTime']; ?>">
                <?php
                if ($row['MoodleTopicID'] > 0) {
                ?>
                    <input type="hidden" id="section" name="section" value="<?php echo $row['MoodleTopicID']; ?>">
                    <input type="hidden" id="quiz" name="quiz" value="<?php echo $row['MoodleExamID']; ?>">
                    <?php
                } else {
                ?>

                    <input type="hidden" id="withoutsection" name="withoutsection" value="<?php echo $row['MoodleExamID']; ?>">

                    <?php
                }
                ?>
                <input type="hidden" id="ScheduleID" name="ScheduleID" value="<?php echo $row['ScheduleID']; ?>">
                <!-- New Flages -->

                <?php if ($row['AllowInstantScoreView'] == 'true') {
                ?>
                    <input type="hidden" id="flag05" name="flag05" value="1">
                    <?php
                } else {
                    ?>
                    <input type="hidden" id="flag05" name="flag05" value="0">
                    <?php
                }

                if ($row['AllowReviewExam'] == 'true') {
                    ?>
                    <input type="hidden" id="flag06" name="flag06" value="1">
                    <?php
                } else {
                    ?>
                    <input type="hidden" id="flag06" name="flag06" value="0">
                    <?php
                }

                if ($row['AllowQuestionNavigation'] == 'true') {
                    ?>
                    <input type="hidden" id="flag07" name="flag07" value="1">
                    <?php
                } else {
                    ?>
                    <input type="hidden" id="flag07" name="flag07" value="0">
                    <?php
                }

                if ($row['RandomizeQuestion'] == 'true') {
                    ?>
                    <input type="hidden" id="flag08" name="flag08" value="1">
                    <?php
                } else {
                    ?>
                    <input type="hidden" id="flag08" name="flag08" value="0">
                    <?php
                }

                if ($row['ShuffleQuestion'] == 'true') {
                    ?>
                    <input type="hidden" id="flag09" name="flag09" value="1">
                    <?php
                } else {
                    ?>
                    <input type="hidden" id="flag09" name="flag09" value="0">
                    <?php
                }

                if ($row['IsSectionWise'] == 'true') {
                    ?>
                    <input type="hidden" id="flag10" name="flag10" value="1">
                    <?php
                } else {
                    ?>
                    <input type="hidden" id="flag10" name="flag10" value="0">
                    <?php
                }

                if ($row['IsSectionWise'] == 'true') {
                    ?>
                    <input type="hidden" id="flag11" name="flag11" value="1">
                    <?php
                } else {
                    ?>
                    <input type="hidden" id="flag11" name="flag11" value="0">
                    <?php
                }

                if ($row['IsTopicWiseSections'] == 'true') {
                    ?>
                    <input type="hidden" id="flag11" name="flag11" value="1">
                    <?php
                } else {
                    ?>
                    <input type="hidden" id="flag11" name="flag11" value="0">
                    <?php
                }

                if ($row['NegativeMarking'] == 'true') {
                    ?>
                    <input type="hidden" id="flag12" name="flag12" value="1">
                    <?php
                } else {
                    ?>
                    <input type="hidden" id="flag12" name="flag12" value="0">
                    <?php
                }

                if ($row['IsOptionSuffling'] == 'true') {
                    ?>
                    <input type="hidden" id="flag13" name="flag13" value="1">
                    <?php
                } else {
                    ?>
                    <input type="hidden" id="flag13" name="flag13" value="0">
                    <?php
                }

                if ($row['IsCalculatorAllowed'] == 'true') {
                    ?>
                    <input type="hidden" id="flag14" name="flag14" value="1">
                    <?php
                } else {
                    ?>
                    <input type="hidden" id="flag14" name="flag14" value="0">
                    <?php
                }

                if ($row['IsShowMarks'] == 'true') {
                    ?>
                    <input type="hidden" id="flag15" name="flag15" value="1">
                    <?php
                } else {
                    ?>
                    <input type="hidden" id="flag15" name="flag15" value="0">
                    <?php
                }

                    ?>
                    <input type="hidden" id="txt4" name="txt4" 
                    value="<?php echo $row['NegativeMarks']; ?>">

                    <input type="hidden" id="txt3" name="txt3" 
                    value="<?php echo $row['NumberOfAlert']; ?>">

                    <input type="hidden" id="txt2" name="txt2" 
                    value="<?php echo $row['BlinkTimerTime']; ?>">
                

                <!-- / New Flages -->


                
                <input type="submit" id="editrec" name="editrec" value="Edit" class="btn btn-primary">

                <input type="button" id="delrecbtn" value="Delete" class="btn btn-danger"
                onclick="delexam(<?php echo $row['MoodleExamID']; ?>,'<?php echo $row['ExamName']; ?>');">
              </form>

                <?php
                echo '</td>';
            } else {
                echo '<td></td>';
            }

            echo '</TR>';
        }

      ?>
    </table>
  </div>
  <div class="col-sm-12 text-center mt-3 mb-3">

    <?php

    $i = 1;
    while ($i <= $mxp) {

        if ($i == $pageno) {

            echo "<input type='button' id='curpageno' class='selected_pagination btn btn-icon btn-outline-info' value='" .
            $i . "' onclick='show_page(" . $i . ");'>";
        } else {
            echo "<input type='button' value='" . $i . "' class='btn btn-icon btn-outline-info' onclick='show_page(" . $i . ");'>";
        }

        $i++;
    }

    ?>


  </div>

    <?php
} else {
      echo 'Data Not Found!';
}
