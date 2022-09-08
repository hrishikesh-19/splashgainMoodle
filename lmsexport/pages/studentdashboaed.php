<?php
// This file is part of Moodle - http://moodle.org/local/imsexport/pages/
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

require_once("../../../config.php");
require_once("../conf.php");
require_once("pgnfunc.php");

require_login();

error_reporting(1);

global $USER, $DB;

$eklavyaobj = $DB->get_record_sql("SELECT apiurl, examapikey,timezone1 FROM {local_lmsexport}");

$apiurl = $eklavyaobj->apiurl;

$fullusername = $USER->firstname . ' ' . $USER->lastname;
$myipadrs = get_client_ip();


$sqls = "SELECT c.*
FROM " . $CFG->prefix . "course c
JOIN " . $CFG->prefix . "enrol en ON en.courseid = c.id
JOIN " . $CFG->prefix . "user_enrolments ue ON ue.enrolid = en.id
WHERE ue.userid =" . $USER->id;
$courses = $DB->get_records_sql($sqls);
unset($examslist);
$examslist = array();
if (isset($_POST['quiz']) && $_POST['quiz'] != '') {
    require_once($CFG->libdir . '/questionlib.php');
    require_once($CFG->dirroot . '/question/format.php');

    $questiondata = array();
    if ($questions = $DB->get_records('quiz_slots', array('quizid' => $_POST['quiz']))) {
        foreach ($questions as $question) {
            array_push($questiondata, question_bank::load_question_data($question->questionid));
        }
    }
    exit;
}

if (isset($_POST['withoutsection']) && $_POST['withoutsection'] != '') {
    require_once($CFG->libdir . '/questionlib.php');
    require_once($CFG->dirroot . '/question/format.php');

    $questiondata = array();
    if ($questions = $DB->get_records('quiz_slots', array('quizid' => $_POST['withoutsection']))) {
        foreach ($questions as $question) {
            array_push($questiondata, question_bank::load_question_data($question->questionid));
        }
    }
    exit;
}
echo $OUTPUT->header();

function get_exams_data($userid) {
    global $USER, $DB;

    $eklavyaobj = $DB->get_record_sql("SELECT apiurl, examapikey,timezone1 FROM {local_lmsexport}");

    $apiurl = $eklavyaobj->apiurl;
    $examapikey = $eklavyaobj->examapikey;
    $courseid = $_REQUEST['courseid'];
    $status = $_REQUEST['status'];
    $eklavyaobj = $DB->get_record_sql("SELECT apiurl, examapikey,timezone1 FROM {local_lmsexport}");

    $apiurl = $eklavyaobj->apiurl;
    $rollno = $USER->id;
    $firstname = $USER->firstname;
    $lastname = $USER->lastname;
    $email = $USER->email;
    $phone = $USER->phone1;
    if (trim($phone) == '') {
        $phone = $USER->phone2;
    }

    $url = $apiurl . '/SplashService.svc/GetMoodleExamDetailsWebAPI';

    $data = array("obj" => array("RollNo" => $rollno, "CourseID" => $courseid,
    "FirstName" => $firstname, "LastName" => $lastname, "EmailID" => $email,
    "MobileNo" => $phone, "Status" => $status));
    $header = array(
        'Accept: application/json',
        'Content-Type: application/json',
        'authenticationkey:' . $examapikey
    );
    $postdata = json_encode($data);
    $curl = curl_init();

    curl_setopt_array($curl, array(
        CURLOPT_URL => $url,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS => $postdata,
        CURLOPT_HTTPHEADER => array(
            'authenticationkey:' . $examapikey,
            'Content-Type: application/json'
        ),
    ));
    $response = curl_exec($curl);

    curl_close($curl);
    return json_decode($response, true);
}
$studentdata = get_exams_data($USER->id);

$examslist = $studentdata['GetMoodleExamDetailsWebAPIResult'];


if ($courses) {

?>
    <div class="auth_form">
        <div class="nav-area">
            <div class="logo">
                <img style="max-width: 37%;">
            </div>
            <ul class="page-nav11">
            </ul>
        </div>
        <form class="m-element-confirmation text-left" id="course_form" action="" method="POST">
            <div class="container">
                <div class="row rowStyle" style="width:100%;text-align:left">
                    <div class="col-md-4">
                        <label>Select Course</label>
                        <select name="courseid" id="courseid" class="form-control">
                            <option value="">Select</option>
                            <?php foreach ($courses as $singlecourse) {
                                if ($singlecourse->format != "site") {
                            ?>
                                    <option value="<?php echo $singlecourse->id; ?>" <?php if (isset($_POST['courseid'])) {
                                        if ($_POST['courseid'] == $singlecourse->id) {
                                            echo 'selected="selected"';
                                        }
                                                   } ?>><?php echo $singlecourse->fullname; ?></option>
                                <?php }
                            } ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label>Select Status</label>
                        <select name="status" id="status" onchange="this.form.submit()" class="form-control">
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
                    <div class="col-md-4"></div>
                </div>


                <?php

                $ytscount = 0;
                $resumecount = 0;
                $completedcount = 0;
                $expiredcount = 0;

                foreach ($examslist['ExamObj'] as $ssi) {
                    if (trim($ssi['Status']) == '1') {
                        $ytscount = $ytscount + 1;
                    }

                    if (trim($ssi['Status']) == '2') {
                        $resumecount = $resumecount + 1;
                    }
                    if (trim($ssi['Status']) == '3') {
                        $completedcount = $completedcount + 1;
                    }
                    if (trim($ssi['Status']) == '4') {
                        $expiredcount = $expiredcount + 1;
                    }
                }
                $allcount = $ytscount + $resumecount + $completedcount + $expiredcount;
                ?>
                <div class="status-bar">
                    <div class="status-bar" style="display:none">
                        <div class="course-status"><span class="box-roun brown">
                            <?php echo $allcount; ?></span> <span>All</span></div>
                        <div class="course-status"><span class="box-roun green">
                            <?php echo $ytscount; ?></span><span>Yet To Start</span></div>
                        <div class="course-status"><span class="box-roun skyblue">
                            <?php echo $resumecount; ?></span><span>Resume</span></div>
                        <div class="course-status"><span class="box-roun peach">
                            <?php echo $completedcount; ?></span><span> Completed</span></div>
                        <div class="course-status"><span class="box-roun pink">
                            <?php echo $expiredcount; ?></span><span>Expired</span></div>

                    </div>

                    <div class="student-box-area">
                        <?php
                        if ($examslist['ExamObj']) {
                            foreach ($examslist['ExamObj'] as $singleexams) {
                                if (trim($singleexams['Status']) == '1') {
                                     ?> <div class="student-box1">
                                <?php } else if (trim($singleexams['Status']) == '2') {
                                        ?> <div class="student-box2">
                                <?php } else if (trim($singleexams['Status']) == '3') {
                                            ?> <div class="student-box3">
                                <?php } else if (trim($singleexams['Status']) == '4') {
                                                ?> <div class="student-box4">
                                <?php } ?>
                                                <div class="box-head">
                                                    <p><?php echo $singleexams['ExamName']; ?></p>
                                                </div>
                                                <div class="box-content">
                                                    <p>Start: <?php echo $singleexams['ScheduleStartDate']; ?></p>
                                                    <p>Expired: <?php echo $singleexams['ScheduleEndDate']; ?></p>
                                                    <p>Time Zone: <?php echo $singleexams['TimeZone']; ?></p>
                                                </div>
                                                <div class="box-btn">
                                                    <?php if (trim($singleexams['Status']) == '3') {
                                                        ?>
                                                        <a style="background:#009688" target="_blank" 
                                                        href="<?php echo $singleexams['ResultURL']; ?>">
                                                        <i class="fa fa-list-alt" aria-hidden="true"></i> Result</a> &nbsp; 
                                                        <a style="background:#6761b5" target="_blank"
                                                        href="<?php echo $singleexams['AnalysisURL']; ?>">Analysis</a>
                                                    <?php } else if (trim($singleexams['Status']) == '1') {
                                                        ?>

                                                        <a onclick="register_log('<?php echo $examslist['CandidateID']; ?>',
                                                        '<?php echo $fullusername; ?>','<?php echo $myipadrs; ?>',
                                                        '<?php echo $singleexams['ExamURL']; ?>');" 
                                                        href="<?php echo $singleexams['ExamURL']; ?>">
                                                        <i class="fa fa-play-circle" aria-hidden="true"></i> Proceed</a>


                                                    <?php } else if (trim($singleexams['Status']) == '2') {
                                                        ?>
                                                        <a target="_blank" href="<?php echo $singleexams['ExamURL']; ?>">
                                                        <i class="fa fa-play-circle" aria-hidden="true"></i>Resume</a>
                                                    <?php } else if (trim($singleexams['Status']) == '4') {
                                                        ?>
                                                        <a href="#"><i class="fa fa-stop-circle" aria-hidden="true"></i> Expired</a>

                                                    <?php } ?>

                                                </div>
                                                </div>
                                            <?php
                            }
                        } else { ?>
                                            <div class="row rowStyle" style="width:100%;text-align:left">
                                                <div class="col-md-4"></div>
                                                <div class="col-md-4">
                                                    <label style="color:red">No Record Found</label>
                                                </div>
                                                <div class="col-md-4"></div>
                                            </div>
                        <?php } ?>
                                            </div>

                                        </div>
                                        <footer class="copyright-area"> </footer>

        </form>
    </div>
    <?php
}
echo $OUTPUT->footer();
?>
<style>
    .student-box-area .student-box2,
    .student-box4,
    .student-box-area .student-box3,
    .student-box-area .student-box1 {
        margin-bottom: 2em;
    }

    .status-bar {
        margin: 2em 2%;
    }

    span.box-roun.pink {
        background: #e91e63;
    }

    span.box-roun.peach {
        background: #009688;
    }

    span.box-roun.skyblue {
        background: #00bcd4;
    }

    span.box-roun.skyblue {
        background: #00bcd4;
    }

    span.box-roun.green {
        background: #4caf50;
    }

    span.box-roun.brown {
        background: #795548;
    }

    .course-status {
        display: flex;
        float: left;
        margin-right: 30px;
        margin-bottom: 2em;
    }

    span.box-roun {
        color: #fff;
        width: 35px;
        height: 35px;
        text-align: center;
        padding: 9px;
        border-radius: 50%;
        margin-right: 10px !important;
    }

    .course-status span {
        margin: auto;
    }

    .student-box-area {
        display: flex;
        width: 100%;
        float: left;
        flex-wrap: wrap;
    }

    .search-container input {
        border-left: navajowhite;
        border-top: navajowhite;
        border-right: navajowhite;
        border-color: #cccccca3;
        outline: none;
    }

    .search-container button {
        background: #fff;
        border-radius: 50%;
        width: 35px;
        height: 35px;
        box-shadow: 0px 1px 2px 3px #efefef;
        border-color: transparent;
    }

    .search-container i.fa.fa-search {
        color: #474a4e;
    }

    .search-container {
        float: right;
        margin-bottom: 2em;
    }

    .student-box1 {}

    .student-box1 .box-head {
        background: #4caf50;
        color: #fff;
        text-align: center;
        padding: 10px 5px;
        margin-bottom: 1em;
        width: 100%;
        float: left;
        border-radius: 7px 7px 0px 0px;
    }

    .student-box1 .box-btn a {
        width: max-content;
        background: #4caf50;
        color: #fff;
        padding: 10px 13px;
        margin: auto;
        clear: both;
        float: left;
        border-radius: 5px;
        font-size: 14px;
    }

    .student-box2 .box-head {
        background: #00BCD4;
        color: #fff;
        text-align: center;
        padding: 10px 5px;
        margin-bottom: 1em;
        width: 100%;
        float: left;
        border-radius: 7px 7px 0px 0px;
    }

    .student-box2 .box-btn a {
        width: max-content;
        background: #00BCD4;
        color: #fff;
        padding: 10px 13px;
        margin: auto;
        clear: both;
        float: left;
        border-radius: 5px;
        font-size: 14px;
    }

    .student-box2 .box-btn a:last-child {
        margin: auto auto auto 0;
        background: #00BCD4;
        margin-left: 5px;
    }

    .student-box2 .box-btn a:first-child {
        margin: auto 0 auto auto;
    }

    .student-box3 .box-head {
        background: #009688;
        color: #fff;
        text-align: center;
        padding: 10px 5px;
        margin-bottom: 1em;
        width: 100%;
        float: left;
        border-radius: 7px 7px 0px 0px;
    }

    .student-box3 .box-btn a {
        width: max-content;
        background: #009688;
        color: #fff;
        padding: 10px 13px;
        margin: auto;
        clear: both;
        float: left;
        border-radius: 5px;
        font-size: 14px;
    }

    .student-box4 .box-head {
        background: #e91e63;
        color: #fff;
        text-align: center;
        padding: 10px 5px;
        margin-bottom: 1em;
        width: 100%;
        float: left;
        border-radius: 7px 7px 0px 0px;
    }

    .student-box4 .box-btn a {
        width: max-content;
        background: #e91e63;
        color: #fff;
        padding: 10px 13px;
        margin: auto;
        clear: both;
        float: left;
        border-radius: 5px;
        font-size: 14px;
    }

    .student-box1,
    .student-box2,
    .student-box3,
    .student-box4 {
        width: 23%;
        float: left;
        box-shadow: 0 0 4px 2px #ccc;
        border-radius: 7px;
        padding-bottom: 60px;
        position: relative;
        margin-left: 1.8%;
    }

    .box-btn {
        float: left;
        text-align: center;
        margin: auto;
        display: flex;
        justify-content: center;
        width: 100%;
        position: absolute;
        bottom: 10px;
        padding-top: 10px;
        border-top: 1px solid #cccccc;
    }

    .box-content {
        width: 100%;
        float: left;
        padding: 10px 10px 20px;
        font-size: 14px;
    }

    .box-content p {
        line-height: 1.5;
    }

    @media screen and (max-width: 991px) {

        .student-box1,
        .student-box2,
        .student-box3,
        .student-box4 {
            width: 47%;
            margin-bottom: 2em;
        }
    }
</style>
<style>
    @import url(https://fonts.googleapis.com/css?family=Droid+Serif:400,400italic|Montserrat:400,700);

    html,
    body,
    div,
    span,
    applet,
    object,
    iframe,
    h1,
    h2,
    h3,
    h4,
    h5,
    h6,
    p,
    blockquote,
    pre,
    a,
    abbr,
    acronym,
    address,
    big,
    cite,
    code,
    del,
    dfn,
    em,
    img,
    ins,
    kbd,
    q,
    s,
    samp,
    small,
    strike,
    strong,
    sub,
    sup,
    tt,
    var,
    b,
    u,
    i,
    center,
    dl,
    dt,
    dd,
    ol,
    ul,
    li,
    fieldset,
    form,
    label,
    legend,
    table,
    caption,
    tbody,
    tfoot,
    thead,
    tr,
    th,
    td,
    article,
    aside,
    canvas,
    details,
    embed,
    figure,
    figcaption,
    footer,
    header,
    hgroup,
    menu,
    nav,
    output,
    ruby,
    section,
    summary,
    time,
    mark,
    audio,
    video {
        margin: 0;
        padding: 0;
        border: 0;
        font: inherit;
        font-size: 100%;
        vertical-align: baseline;
    }

    ol,
    ul {
        list-style: none;
    }

    table {
        border-collapse: collapse;
        border-spacing: 0;
    }

    caption,
    th,
    td {
        text-align: left;
        font-weight: normal;
        vertical-align: middle;
    }

    q,
    blockquote {
        quotes: none;
    }

    q:before,
    q:after,
    blockquote:before,
    blockquote:after {
        content: "";
        content: none;
    }

    a img {
        border: none;
    }

    article,
    aside,
    details,
    figcaption,
    figure,
    footer,
    header,
    hgroup,
    main,
    menu,
    nav,
    section,
    summary {
        display: block;
    }

    * {
        box-sizing: border-box;
    }

    body {
        color: #333;
        -webkit-font-smoothing: antialiased;
        font-family: "Droid Serif", serif;
    }

    img {
        max-width: 100%;
    }

    .cf:before,
    .cf:after {
        content: " ";
        display: table;
    }

    .cf:after {
        clear: both;
    }

    .cf {
        *zoom: 1;
    }

    .wrap {
        width: 75%;
        max-width: 100%;
        margin: 0 auto;
        padding: 5% 0;
        margin-bottom: 5em;
    }

    .projTitle {
        font-family: "Montserrat", sans-serif;
        font-weight: bold;
        text-align: center;
        font-size: 2em;
        padding: 1em 0;
        border-bottom: 1px solid #dadada;
        letter-spacing: 3px;
        text-transform: uppercase;
    }

    .projTitle span {
        font-family: "Droid Serif", serif;
        font-weight: normal;
        font-style: italic;
        text-transform: lowercase;
        color: #777;
    }

    .heading {
        padding: 1em 0;
        border-bottom: 1px solid #D0D0D0;
    }

    .heading h1 {
        font-family: "Droid Serif", serif;
        font-size: 2em;
        float: left;
        margin: 0;
    }

    .heading a.continue:link,
    .heading a.continue:visited {
        text-decoration: none;
        font-family: "Montserrat", sans-serif;
        letter-spacing: -.015em;
        font-size: .75em;
        padding: 1em;
        color: #fff;
        background: #950505;
        font-weight: bold;
        border-radius: 50px;
        float: right;
        text-align: right;
        -webkit-transition: all 0.25s linear;
        -moz-transition: all 0.25s linear;
        -ms-transition: all 0.25s linear;
        -o-transition: all 0.25s linear;
        transition: all 0.25s linear;
    }

    .heading a.continue:after {
        content: "\276f";
        padding: .5em;
        position: relative;
        right: 0;
        -webkit-transition: all 0.15s linear;
        -moz-transition: all 0.15s linear;
        -ms-transition: all 0.15s linear;
        -o-transition: all 0.15s linear;
        transition: all 0.15s linear;
    }

    .heading a.continue:hover,
    .heading a.continue:focus,
    .heading a.continue:active {
        background: #f69679;
    }

    .heading a.continue:hover:after,
    .heading a.continue:focus:after,
    .heading a.continue:active:after {
        right: -10px;
    }

    .tableHead {
        display: table;
        width: 100%;
        font-family: "Montserrat", sans-serif;
        font-size: .75em;
    }

    .tableHead li {
        display: table-cell;
        padding: 1em 0;
        text-align: center;
    }

    .tableHead li.prodHeader {
        text-align: left;
    }

    .cart {
        padding: 1em 0;
    }

    .cart .items {
        display: block;
        width: 100%;
        vertical-align: middle;
        padding: 1.5em;
        border-bottom: 1px solid #fafafa;
    }

    .cart .items.even {
        background: #fafafa;
    }

    .cart .items .infoWrap {
        display: table;
        width: 100%;
    }

    .cart .items .cartSection {
        display: table-cell;
        vertical-align: middle;
    }

    .cart .items .cartSection .itemNumber {
        font-size: .75em;
        color: #777;
        margin-bottom: .5em;
    }

    .cart .items .cartSection h3 {
        font-size: 1em;
        font-family: "Montserrat", sans-serif;
        font-weight: bold;
        text-transform: uppercase;
        letter-spacing: .025em;
    }

    .cart .items .cartSection p {
        display: inline-block;
        font-size: .85em;
        color: #777777;
        font-family: "Montserrat", sans-serif;
    }

    .cart .items .cartSection p .quantity {
        font-weight: bold;
        color: #333;
    }

    .cart .items .cartSection p.stockStatus {
        color: #82CA9C;
        font-weight: bold;
        padding: .5em 0 0 1em;
        text-transform: uppercase;
    }

    .cart .items .cartSection p.stockStatus.out {
        color: #F69679;
    }

    .cart .items .cartSection .itemImg {
        width: 4em;
        float: left;
    }

    .cart .items .cartSection.qtyWrap,
    .cart .items .cartSection.prodTotal {
        text-align: center;
    }

    .cart .items .cartSection.qtyWrap p,
    .cart .items .cartSection.prodTotal p {
        font-weight: bold;
        font-size: 1.25em;
    }

    .cart .items .cartSection input.qty {
        width: 3em;
        text-align: center;
        font-size: 1em;
        padding: .25em;
        margin: 1em .5em 0 0;
    }

    .cart .items .cartSection .itemImg {
        width: 8em;
        display: inline;
        padding-right: 1em;
    }

    .special {
        display: block;
        font-family: "Montserrat", sans-serif;
    }

    .special .specialContent {
        padding: 1em 1em 0;
        display: block;
        margin-top: .5em;
        border-top: 1px solid #dadada;
    }

    .special .specialContent:before {
        content: "\21b3";
        font-size: 1.5em;
        margin-right: 1em;
        color: #6f6f6f;
        font-family: helvetica, arial, sans-serif;
    }

    a.remove {
        text-decoration: none;
        font-family: "Montserrat", sans-serif;
        color: #ffffff;
        font-weight: bold;
        background: #f20606;
        padding: 4px 6px;
        font-size: 16px;
        display: inline-block;
        border-radius: 100%;
        line-height: .85;
        -webkit-transition: all 0.25s linear;
        -moz-transition: all 0.25s linear;
        -ms-transition: all 0.25s linear;
        -o-transition: all 0.25s linear;
        transition: all 0.25s linear;
    }

    a.remove:hover {
        background: #f30;
    }

    .promoCode {
        border: 2px solid #efefef;
        float: left;
        width: 35%;
        padding: 2%;
    }

    .promoCode label {
        display: block;
        width: 100%;
        font-style: italic;
        font-size: 1.15em;
        margin-bottom: .5em;
        letter-spacing: -.025em;
    }

    .promoCode input {
        width: 85%;
        font-size: 1em;
        padding: .5em;
        float: left;
        border: 1px solid #dadada;
    }

    .promoCode input:active,
    .promoCode input:focus {
        outline: 0;
    }

    .promoCode a.btn {
        float: left;
        width: 15%;
        padding: .75em 0;
        border-radius: 0 1em 1em 0;
        text-align: center;
    }

    .promoCode a.btn:hover {
        border: 1px solid #f69679;
        background: #f69679;
    }

    .btn:link,
    .btn:visited {
        text-decoration: none;
        font-family: "Montserrat", sans-serif;
        letter-spacing: -.015em;
        font-size: 1em;
        padding: 1em 3em;
        color: #fff;
        background: #950505;
        font-weight: bold;
        border-radius: 50px;
        float: right;
        text-align: right;
        -webkit-transition: all 0.25s linear;
        -moz-transition: all 0.25s linear;
        -ms-transition: all 0.25s linear;
        -o-transition: all 0.25s linear;
        transition: all 0.25s linear;
    }

    .btn:after {
        content: "\276f";
        padding: .5em;
        position: relative;
        right: 0;
        -webkit-transition: all 0.15s linear;
        -moz-transition: all 0.15s linear;
        -ms-transition: all 0.15s linear;
        -o-transition: all 0.15s linear;
        transition: all 0.15s linear;
    }

    .btn:hover,
    .btn:focus,
    .btn:active {
        background: #f69679;
    }

    .btn:hover:after,
    .btn:focus:after,
    .btn:active:after {
        right: -10px;
    }

    .promoCode .btn {
        font-size: .85em;
        paddding: .5em 2em;
    }

    /* TOTAL AND CHECKOUT  */
    .subtotal {
        float: right;
        width: 35%;
    }

    .subtotal .totalRow {
        padding: .5em;
        text-align: right;
    }

    .subtotal .totalRow.final {
        font-size: 1.25em;
        font-weight: bold;
    }

    .subtotal .totalRow span {
        display: inline-block;
        padding: 0 0 0 1em;
        text-align: right;
    }

    .subtotal .totalRow .label {
        font-family: "Montserrat", sans-serif;
        font-size: .85em;
        text-transform: uppercase;
        color: #777;
    }

    .subtotal .totalRow .value {
        letter-spacing: -.025em;
        width: 35%;
    }

    @media only screen and (max-width: 39.375em) {
        .wrap {
            width: 98%;
            padding: 2% 0;
        }

        .projTitle {
            font-size: 1.5em;
            padding: 10% 5%;
        }

        .heading {
            padding: 1em;
            font-size: 90%;
        }

        .cart .items .cartSection {
            width: 90%;
            display: block;
            float: left;
        }

        .cart .items .cartSection.qtyWrap {
            width: 10%;
            text-align: center;
            padding: .5em 0;
            float: right;
        }

        .cart .items .cartSection.qtyWrap:before {
            content: "QTY";
            display: block;
            font-family: "Montserrat", sans-serif;
            padding: .25em;
            font-size: .75em;
        }

        .cart .items .cartSection.prodTotal,
        .cart .items .cartSection.removeWrap {
            display: none;
        }

        .cart .items .cartSection .itemImg {
            width: 25%;
        }

        .promoCode,
        .subtotal {
            width: 100%;
        }

        a.btn.continue {
            width: 100%;
            text-align: center;
        }
    }


    .page-nav11 {
        list-style-type: none;
        margin: 0;
        padding: 0;
        overflow: hidden;
        /*background-color: #333;*/
        width: max-content;
        margin: auto;
    }

    .logo {
        padding: 10px;
        margin: auto 0;
    }

    .nav-area {
        width: 100%;
        margin: auto;
        background: #6761b5;
        margin-bottom: 2em;
        display: flex;

    }

    .page-nav11 li {
        float: left;
    }

    .page-nav11 li a {
        display: block;
        color: white;
        text-align: center;
        padding: 14px 16px;
        text-decoration: none;
    }

    .page-nav11 li a:hover {
        background-color: #111;
    }

    .form-style {
        /*width: max-content;*/
        margin: auto;
        /*border: 2px solid #ccc;*/
        padding: 2%;
    }

    .form-style input.m-element-button {
        border: none;
        height: 40px;
    }

    .form-style input.m-element-button {
        padding: 0 25px;
        /*margin-left: 10px;*/
    }

    .auth_form h3 {
        margin-bottom: 1em;
    }

    .auth_form select {
        height: 35px;
        /*border-color: #efefef;*/
        padding: 0 20px;
        outline: none;
        margin-bottom: 0.5rem;
        border: 2px solid #efefef;
    }

    input[type="date"],
    input[type="text"] {
        height: 35px;
    }

    form#course_form input.m-element-button {
        background: #009688;
        color: #fff;
        margin-left: 28%;
    }

    .course-box1,
    .course-box2,
    .course-box3 {
        width: 33%;
        float: left;
        background: #595959;
        padding: 2%;
    }

    .flex11 {
        display: flex;
        /*background: #595959;*/
        /*padding: 10px;*/
        color: #fff;
    }

    .course-box2,
    .course-box3 {
        margin-left: 1%;
    }

    footer.copyright-area {
        width: 100%;
        background: #6761b5;
        margin-top: 2em;
        color: #fff;
        padding: 10px;
        float: left;
        margin-left: 0px;
        margin-right: 0px;
    }

    .Schedule {
        color: #111;
        margin-bottom: 1em;
        width: 100%;
    }

    section#region-main {
        padding: 0;
    }

    body#page-local-lmsexport-pages-student-exam-list header#page-header {
        display: none;
    }
</style>

<script>
    function register_log(cid, usnm, myip, exmurl) {
        //alert(cid);
        //alert(usnm);
        //alert(myip);
        //alert(exmurl);
        //alert('test');            
        $.ajax({
            url: "InsertStudentLog.php",
            method: "POST",
            data: {
                StudentID: cid,
                StudentName: usnm,
                IPAddress: myip
            },
            success: function(dataabc) {
                //console.log(dataabc);

                // alert(dataabc);            

            }
        });


    }


    // Remove Items From Cart
    $('a.remove').click(function() {
        event.preventDefault();
        $(this).parent().parent().parent().hide(400);

    })

    // Just for testing, show all items
    // $('a.btn.continue').click(function(){
    //   $('li.items').show(400);
    // })
</script>
