<?php
// This file is part of Moodle - http://moodle.org/local/imsexport/pages/proctor/assign/
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


$pagesize = 10;

if (isset($_SESSION['assignproctor_pageno'])) {
    $pageno = $_SESSION['assignproctor_pageno'];
} else {
    $pageno = 1;
}


echo $OUTPUT->header();

?>

<div class="main_content">
   
    <?php require('../../ekl_nav.php');  ?>


    <div id="selector_ui">

        <div class="col-sm-12 row">
            <!-- title -->

            <div id="title_ui" class="col-md-6 pt-4 text-left">
                <h3> Proctor(s) >> Change Assignment</h3>
            </div>


            <div class="col-sm-12 row">


            </div>


            <!-- Courses Combobox -->
            <div id="course_ui" class="col-md-3">



            </div>

            <!-- exams Combobox -->
            <div id="exam_ui" class="col-md-3">



            </div>




            <!-- exam shedule Combobox -->
            <div id="examschedule_ui" class="col-md-3">



            </div>


        </div>

    </div>

    <div style="height:50px ;">
    </div>

    <div id="table_content" style="margin:20px ;">

    </div>


    <div class="text-right">
        <a href="index.php"><button type="button" class="m-element-button btn btn-primary text-right">Go Back</button></a>
    </div>


</div>

<div id="proctorlist_ui" style="background-color: rgba(0,0,0,0.4); position:absolute;left:0;top:0; height:100%; width:100%; ">



</div>

<?php
echo $OUTPUT->footer();



?>



<script>
    $(document).ready(function() {

      
        $.ajax({
            url: "getcourses.php",
            method: "POST",
            data: {
                courseid: 0
            },
            success: function(dataabc) {

                $("#course_ui").html(dataabc);
                $("#proctorlist_ui").hide();
                
            }
        });

    });
</script>


<script>
    function show_exm() {
        $("#table_content").html('');
        $("#examschedule_ui").html('');
        $("#exam_ui").html('please wait');

        var no = $("#courseid").val();        
        $.ajax({
            url: "getexams.php",
            method: "POST",
            data: {
                courseid: no
            },
            success: function(dataabc) {

                $("#exam_ui").html(dataabc);
                
            }
        });

    }
</script>


<script>
    function show_shedule() {
        $("#table_content").html('');
        $("#examschedule_ui").html('please wait');
        var no = $("#examid").val();

        $.ajax({
            url: "getschedules.php",
            method: "POST",
            data: {
                ExamID: no
            },
            success: function(dataabc) {

                $("#examschedule_ui").html(dataabc);


            }
        });

    }
</script>




<script>
    function show_students() {
        $("#table_content").html('Please Wait');
        
        var coid = $("#courseid").val();
        var exid = $("#examid").val();
        var scid = $("#ScheduleID").val();

        $.ajax({
            url: "getstudentstochangeassign.php",
            method: "POST",
            data: {
                courseid: coid,
                ExamID: exid,
                ScheduleID: scid,
                pagesize: <?php echo $pagesize; ?>
            },
            success: function(dataabc) {
                
                $("#table_content").html(dataabc);
                
            }
        });


    }
</script>



<script>
    function show_proclist() {

        var coid = $("#courseid").val();
        var exid = $("#examid").val();
        var scid = $("#ScheduleID").val();

        var selarray = [];
        $("input:checkbox[id=rcbx]:checked").each(function() {
            selarray.push($(this).val());
        });



        if (selarray.length > 0) {
           
            var no = $("#ProctorID").val();
            $.ajax({
                url: "getproclist.php",
                method: "POST",
                data: {
                    ProctorID: no
                },
                success: function(dataabc) {
                
                    $("#proctorlist_ui").html(dataabc);
                    $("#proctorlist_ui").show();
                }
            });


        } else {
            alert('Please Select atleast one Student.');
        }



    }
</script>


<script>
    function assign_proctor() {


        var coid = $("#courseid").val();
        var exid = $("#examid").val();
        var scid = $("#ScheduleID").val();
        var prid = $("#ProctorID").val();




        var selarray = [];
        $("input:checkbox[id=rcbx]:checked").each(function() {
            selarray.push($(this).val());
        });

        //alert(selarray);

        if (prid > 0) {

            $.ajax({
                url: "assignproctor.php",
                method: "POST",
                data: {
                    courseid: coid,
                    ExamID: exid,
                    ScheduleID: scid,
                    ProctorID: prid,
                    rcbx: selarray,
                    pagesize: <?php echo $pagesize; ?>
                },
                success: function(dataabc) {                    
                    $("#table_content").html(dataabc);

                    $("#proctorlist_ui").hide();

                    show_students();



                }
            });



        } else {
            alert('Please Select Proctor.');
        }




    }


    function assign_proctor_cancel() {

        $("#proctorlist_ui").hide();


    }
</script>
