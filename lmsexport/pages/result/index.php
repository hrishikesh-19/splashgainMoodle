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


echo $OUTPUT->header();

?>

<div class="main_content">
    <?php require('../ekl_nav.php');  ?>

    <div id="selector_ui">
    
    <div  class="col-sm-12 row" >  
            <div id="title_ui" class="col-md-12 pt-4 auth_form " >
                <h3>Result(s) >> View Exam Result  </h3>
            </div>          
    </div>

     
    <div  class="col-sm-12 row" >
                                    
            <div id="course_ui" class="col-md-3" >
        
                

            </div>

            <div id="exam_ui" class="col-md-3" >
        
                

            </div>

            
            <div id="examschedule_ui" class="col-md-3" >
        
                

            </div>


            <div id="examstatus_ui" class="col-md-3"  >
        
                

            </div>
        

        </div>

    </div>

    <div style="height:50px ;">
    
    </div>

    <div id="table_content">

    


    

    </div>

    <div id="rslt"></div>
    
</div>


<?php
echo $OUTPUT->footer();

?>




<script>
$(document).ready(function(){

    $("#course_ui").html('Please Wait!');
    $("#exam_ui").html('');
    $("#examschedule_ui").html('');
    $("#examstatus_ui").html('');
    $("#table_content").html('');


$.ajax({url:"getcourses.php",
    method:"POST",
    data:{CourseID:0},
    success:function(dataabc){

    $("#course_ui").html(dataabc);
    $("#proctorlist_ui").hide();

}});

});
</script>


<script>
function show_exm()
{
    $("#exam_ui").html('Please Wait!');
    $("#examschedule_ui").html('');
    $("#examstatus_ui").html('');
    $("#table_content").html('');

    var no=$("#courseid").val();

    $.ajax({url:"getexams.php",
        method:"POST",
        data:{CourseID:no},
    success:function(dataabc){

        $("#exam_ui").html(dataabc);
        
    }});
    
}
</script>


<script>
function show_shedule()
{
    $("#examschedule_ui").html('Please Wait!');
    $("#examstatus_ui").html('');
    $("#table_content").html('');

    var no=$("#examid").val();

    $.ajax({url:"getschedules.php",
        method:"POST",
        data:{ExamID:no},
    success:function(dataabc){

        $("#examschedule_ui").html(dataabc);
        
    }});
    
}
</script>




<script> 
    function show_examstatus()
    {
        $("#examstatus_ui").html('Please Wait!');
        $("#table_content").html('');

        $.ajax({url:"getstatuses.php",

    success:function(dataabc){

        $("#examstatus_ui").html(dataabc);

    }});

        
    }
</script>



<script>
function show_result_table()
{    
    
        $("#table_content").html('Please Wait!')
        var coid=$("#courseid").val();
        var exid=$("#examid").val();
        var scid=$("#ScheduleID").val();
        var stsid=$("#status").val();        


        $.ajax({url:"getresultlist.php",
        method:"POST",
        data:{courseid:coid,ExamID:exid,ScheduleID:scid,StatusID:stsid},
    success:function(dataabc){
           
        $("#table_content").html(dataabc);
        
    }});
   
    
}
</script>

<script>
function updategradebook()
{        
        var coid=$("#courseid").val();
        var exid=$("#examid").val();
        var scid=$("#ScheduleID").val();
        var stsid=$("#status").val();        
        var mdlexamid=$("#mdlexamid").val();
        var ct=$("#grade_csv_text").val();
   
        $.ajax({url:"gradebookupdate.php",
        method:"POST",
        data:{
            courseid:coid,
            ExamID:exid,
            ScheduleID:scid,            
            StatusID:stsid,
            MoodleExamID:mdlexamid,
            csvstr:ct
        },
    success:function(return_data){
            
        alert(return_data);
        
    }});
   
    
}
</script>



