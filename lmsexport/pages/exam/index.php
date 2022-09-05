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

global $USER, $DB;

error_reporting(1);

require_login();

$eklavyaobj = $DB->get_record_sql("SELECT apiurl, examapikey,timezone1 FROM {local_lmsexport}");

$apiurl = $eklavyaobj->apiurl;


$pagesize = 5;

if (isset($_SESSION['proctorlist_pageno'])) {
    $pageno = $_SESSION['proctorlist_pageno'];
} else {
    $pageno = 1;
}





$datatosend = array("obj" => array("Offset" => $pageno, "PageSize" => $pagesize));


$posturl = $apiurl . '/SplashService.svc/GetProctorsListWebAPI';

$proctorlist = get_api_response($posturl, $datatosend);

$apiresultarray = $proctorlist["GetProctorsListWebAPIResult"];
$proctorlisttableobject = $apiresultarray["ProctorsListObj"];
$mxp = $apiresultarray["TotalPages"];




if (isset($_SESSION['examlist_courseid'])) {
    $courseid = $_SESSION['examlist_courseid'];
}

$PAGE->requires->jquery();

echo $OUTPUT->header();



?>

<div class="my_main_content">

    <?php require('../ekl_nav.php');  ?>


    <div id="titleui" class="col-md-12 pt-4 auth_form">
        <h3> Exam(s) >> Management demo</h3>
    </div>

    <div id="debugtest">

    </div>

    <div id="formfields">

        <input type="hidden" id="formcourseid" name="formcourseid" value="<?php echo $courseid; ?>">

    </div>

    <div class="col-sm-12 pt-4">
        <a href="details/index.php"><button class="btn btn-outline-primary">New Exam </button> </a>
    </div>
    <!-- title -->

    <div id="courseui">


    </div>


    <div id="content" class="col-sm-12 col-xs-12 pt-4">

    </div>

</div>

<?php
echo $OUTPUT->footer();

?>




<script>
    $(document).ready(function() {

        
        $("#page-header").hide();

      

        var ci = $("#formcourseid").val();
        $("#courseui").html('');
        $.ajax({
            url: "getcourses.php",
            method: "POST",
            data: {
                courseid: ci
            },
            success: function(dataabc) {
                
                $("#courseui").html(dataabc);
                show_page();
            }
        });


        


    });
</script>



<script>
    function show_page(no) {
        var ci = $("#courseid").val();
             
        $("#content").html('');
        $.ajax({
            url: "select.php",
            method: "POST",
            data: {
                courseid: ci,
                pageno: no,
                pagesize: <?php echo $pagesize; ?>
            },
            success: function(dataabc) {
        
                $("#content").html(dataabc);
        
            }
        });


    }
</script>

<script>
    function delexam(examid, exmname) {

        $("#debugtest").html('');

        let confirmAction = confirm("Are you sure ?");
        if (confirmAction) {

            $.ajax({
                url: "exam_delete.php",
                method: "POST",
                data: {
                    ExamID: examid,
                    ExamName: exmname
                },
                success: function(dataabc) {
                    var data = JSON.parse(dataabc);

                    // $("#debug_test").html(data['test']);
                    //alert(data['msg1']);
                    if (data['isok'] > 0) {
                        //alert('Registered Suc');

                        alert(data['msg1']);



                        // window.location.href="../index.php";

                    } else {

                        // not sucess

                    }

                    show_page();
                    //alert(dataabc);
                }
            });
        }

    }
</script>
