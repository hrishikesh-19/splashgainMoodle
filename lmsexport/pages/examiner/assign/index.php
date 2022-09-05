<?php
// This file is part of Moodle - http://moodle.org/local/imsexport/pages/examiner/assign/
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

if (isset($_SESSION['assignexaminerpageno'])) {
    $pageno = $_SESSION['assignexaminerpageno'];
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


            <div id="error_ui" class="col-md-6 pt-4 text-left">

            </div>


            <div class="col-sm-12 row">

                <div id="title_ui" class="col-md-6 pt-4 text-left">
                    <h3> Examiner(s) >> Examiner Assignment</h3>
                </div>

                <div class="col-sm-6 pt-4 text-right">



                </div>


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

    <!-- examiner list Combobox -->

    <div class="text-right">
        <a href="../index.php"><button type="button" class="m-element-button btn btn-primary text-right">Go Back</button></a>
    </div>



</div>

<div id="examinerlist_ui" style="background-color: rgba(0,0,0,0.4); position:absolute;left:0;top:0; height:100%; width:100%; ">



</div>

<?php
echo $OUTPUT->footer();



?>




<script>
    $(document).ready(function() {
        $("#course_ui").html('please wait');
        $("#exam_ui").html('');
        $("#examschedule_ui").html('');
        $("#table_content").html('');

        $.ajax({
            url: "../ekl_nav.php",
         
            success: function(dataabc) {
              
                $("#eklnav").html(dataabc);
              
            }
        });

        $.ajax({
            url: "getcourses.php",
            method: "POST",
            data: {
                courseid: 0
            },
            success: function(dataabc) {
                //console.log(dataabc);
                $("#course_ui").html(dataabc);
                $("#examinerlist_ui").hide();
                //alert(dataabc);
            }
        });

    });
</script>


<script>
    function show_exm() {
        $("#exam_ui").html('Please Wait');
        $("#examschedule_ui").html('');
        $("#table_content").html('');

        var no = $("#courseid").val();
        //alert('OK');
        $.ajax({
            url: "getexams.php",
            method: "POST",
            data: {
                courseid: no
            },
            success: function(dataabc) {
                //console.log(dataabc);
                $("#exam_ui").html(dataabc);

                //alert(dataabc);
            }
        });

    }
</script>


<script>
    function show_shedule() {
        $("#examschedule_ui").html('Please Wait');
        $("#table_content").html('');


        var no = $("#examid").val();
     
        $.ajax({
            url: "getschedules.php",
            method: "POST",
            data: {
                examid: no
            },
            success: function(dataabc) {
                //console.log(dataabc);
                $("#examschedule_ui").html(dataabc);

                //alert(dataabc);
            }
        });

    }
</script>

<script>
    function show_students() {
        $("#table_content").html('please wait');
        //alert(no);
        var coid = $("#courseid").val();
        var exid = $("#examid").val();
        var scid = $("#ScheduleID").val();

        $.ajax({
            url: "getstudentstoassign.php",
            method: "POST",
            data: {
                courseid: coid,
                ExamID: exid,
                ScheduleID: scid
            },
            success: function(dataabc) {
                //console.log(dataabc);
                $("#table_content").html(dataabc);
                //alert(dataabc);
            }
        });


    }
</script>



<script>
    function getexaminerlist() {

        var coid = $("#courseid").val();
        var exid = $("#examid").val();
        var scid = $("#ScheduleID").val();

        var selarray = [];
        $("input:checkbox[id=rcbx]:checked").each(function() {
            selarray.push($(this).val());
        });

        //alert(selarray);
        if (selarray.length > 0) {

            //alert('OK');
            //var no=0;
            var no = $("#ExaminerID").val();



            $.ajax({
                url: "getexaminarlist.php",
                method: "POST",
                data: {
                    ExaminerID: no
                },

                success: function(dataabc) {

                    //alert(dataabc);

                    //console.log(dataabc);
                    $("#examinerlist_ui").html(dataabc);
                    //alert(dataabc);
                    $("#examinerlist_ui").show();
                }
            });


        } else {
            alert('Please Select atleast one Student.');
        }




    }
</script>


<script>
    function assign_examiner() {

        var coid = $("#courseid").val();
        var exid = $("#examid").val();
        var scid = $("#ScheduleID").val();
        var prid = $("#ExaminerID").val();

        var selarray = [];
        $("input:checkbox[id=rcbx]:checked").each(function() {
            selarray.push($(this).val());
        });

        if (prid > 0) {

            $.ajax({
                url: "assignexaminer.php",
                method: "POST",
                data: {
                    courseid: coid,
                    ExamID: exid,
                    ScheduleID: scid,
                    ExaminerID: prid,
                    rcbx: selarray,
                    pagesize: <?php echo $pagesize; ?>
                },
                success: function(dataabc) {
                    //console.log(dataabc);
                    alert(dataabc);

                    //$("#error_ui").html(dataabc);

                    $("#table_content").html(dataabc);

                    $("#examinerlist_ui").hide();

                    show_students();



                }
            });



        } else {
            alert('Please Select examiner.');
        }




    }


    function assign_examiner_cancel() {

        $("#examinerlist_ui").hide();


    }
</script>
