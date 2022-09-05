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

$csvstr = 'userid,score' . "\r\n";



if (isset($_POST['courseid'])) {

    $courseid = $_POST['courseid'];

    $datatosend = array("obj" => array(
        "CourseID" => $_POST['courseid'],
        "ExamID" => $_POST['ExamID'], "ScheduleID" => $_POST['ScheduleID'],
        "Status" => $_POST['StatusID']
    ));

    $posturl = $apiurl . '/SplashService.svc/GetMoodleExamResultWebAPI';

    $responsearray = get_api_response_withdata($posturl, $datatosend);

    $resultlist = $responsearray["GetMoodleExamResultWebAPIResult"];


    if (count($resultlist) > 0) {



?>

        <div class="col-sm-12 pt-4">
            <button class="btn btn-outline-primary" onclick="updategradebook();">
                Update Moodle GradeBook </button>
            <br />
            <br />
            <br />

        </div>
        <div class="col-sm-12 table-responsive">

            <table class="table table-bordered table-hover table-striped">
                <tr>
                    <th>Sr. No.</th>
                    <th>Name</th>
                    <th>Roll No</th>
                    <th>Finished Time</th>
                    <th>Score</th>
                    <th>Score Card</th>
                    <th>Certificate</th>
                    <th>Exam Analysis</th>
                    <th>Exam Status</th>
                    <th>Exam Review</th>
                    <th>Exam Time</th>
                    <th>Candidate <br>Images </th>
                    <th>Candidate <br>Audio</th>
                    <th>Candidate Video</th>
                    <th>Candidate Screen <br>Streaming</th>

                    <!-- <th>Proctor Report</th> -->
                </tr>
                <?php if ($resultlist['ResultObj']) {
                    $ii = 1;
                    foreach ($resultlist['ResultObj'] as $singleresult) {

                        $mdlexamid = $singleresult['MoodleExamID'];

                ?>

                        <tr>
                            <td><?php echo $ii; ?></td>
                            <td><?php echo $singleresult['CandidateName']; ?></td>
                            <td><?php echo $singleresult['RollNo']; ?></td>
                            <td><?php echo $singleresult['FinishExamDateTime']; ?></td>
                            <td><?php echo $singleresult['Score']; ?></td>
                            <td><?php if ($singleresult['ResultURL'] != 'NA') { ?>
                                    <a target="_blank" href="<?php echo $singleresult['ResultURL']; ?>">
                                        Score Card</a>
                                <?php } else {
                                        echo 'NA';
                                }
                                ?>
                            </td>

                            <td><?php if ($singleresult['Certificate'] != 'NA') { ?>
                                    <a target="_blank" href="<?php echo $singleresult['Certificate']; ?>">
                                        Certificate</a>
                                <?php } else {
                                    echo 'NA';
                                } ?>
                            </td>


                            <td><?php if ($singleresult['AnalysisURL'] != 'NA') {  ?>
                                    <a target="_blank" href="<?php echo $singleresult['AnalysisURL']; ?>">
                                        Result Analysis</a>
                                <?php } else {
                                    echo 'NA';
                                } ?>
                            </td>

                            <td><?php echo $singleresult['Status']; ?></td>

                            <td><?php if ($singleresult['ExamReviewURL'] != 'NA') {  ?>
                                    <a target="_blank" href="<?php echo $singleresult['ExamReviewURL']; ?>">
                                        Exam Review</a>
                                <?php } else {
                                    echo 'NA';
                                } ?>
                            </td>

                            <td><?php echo $singleresult['ExamDuration']; ?></td>

                            <td><?php if ($singleresult['CandidateImages'] != 'NA') {  ?>
                                    <a target="_blank" href="<?php echo $singleresult['CandidateImages']; ?>">
                                        Candidate Images</a>
                                <?php } else {
                                    echo 'NA';
                                } ?>
                            </td>

                            <td><?php if ($singleresult['CandidateAudio'] != 'NA') {  ?>
                                    <a target="_blank" href="<?php echo $singleresult['CandidateAudio']; ?>">
                                        Candidate Audio</a>
                                <?php } else {
                                    echo 'NA';
                                } ?>
                            </td>

                            <td><?php if ($singleresult['CandidateVideo'] != 'NA') {  ?>
                                    <a target="_blank" href="<?php echo $singleresult['CandidateVideo']; ?>">
                                        Candidate Video</a>
                                <?php } else {
                                    echo 'NA';
                                } ?>
                            </td>

                            <td><?php if ($singleresult['CandidateScreenStreaming'] != 'NA') {  ?>
                                    <a target="_blank" href="<?php echo $singleresult['CandidateScreenStreaming']; ?>">
                                        Candidate Screen Streaming</a>
                                <?php } else {
                                    echo 'NA';
                                } ?>
                            </td>


                        </tr>
                        <?php
                        $ii = $ii + 1;

                        // Code to Prepare Result csv for grade update.
                        $csvstr = $csvstr . $singleresult['RollNo'] . ',' . $singleresult['Score'] . "\n";
                    }
                } else { ?>
                    <tr>
                        <td colspan="16">No Records Found </td>
                    </tr>
                <?php } ?>
            </table>



            <input type="hidden" id="mdlexamid" value="<?php echo $mdlexamid; ?>">
            <input type="hidden" id="grade_csv_text" value="<?php echo $csvstr; ?>">





        </div>







        <?php
    } else {
        echo 'No Result Found.';
    }
} else {
    echo 'No Result Found.';
}
