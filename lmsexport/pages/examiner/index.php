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

$pagesize = 5;

if (isset($_SESSION['examinerlistpageno'])) {
    $pageno = $_SESSION['examinerlistpageno'];
} else {
    $pageno = 1;
}



$datatosend = array("obj" => array("Offset" => $pageno, "PageSize" => $pagesize));


$posturl = $apiurl . '/SplashService.svc/GetExaminersListWebAPI';

$examinerlist = get_api_response($posturl, $datatosend);

$apiresultarray = $examinerlist["GetExaminersListWebAPIResult"];
$examinerlisttableobject = $apiresultarray["ExaminersListObj"];
$mxp = $apiresultarray["TotalPages"];



echo $OUTPUT->header();


?>

<div class="main_content">

    <?php require('../ekl_nav.php');  ?>

    <div id="title_ui" class="col-md-12 pt-4 auth_form">
        <h3> Examiner(s) >> Management</h3>
    </div>
    <div class="col-sm-12 pt-4">

        <a href="newexaminer.php"><button class="btn btn-outline-primary">New Examiner </button> </a>
        <a href="assign/index.php"><button class="btn btn-outline-primary"> Examiner Assignment </button></a>

    </div>    

    <div id="content" class="col-sm-12 col-xs-12 pt-4">

    </div>

</div>

<?php
echo $OUTPUT->footer();

?>




<script>
    $(document).ready(function() {

      



        $.ajax({
            url: "select.php",
            method: "POST",
            data: {
                pageno: <?php echo $pageno; ?>,
                pagesize: <?php echo $pagesize; ?>
            },
            success: function(dataabc) {                
                $("#content").html(dataabc);                
            }
        });





    });
</script>



<script>
    function show_page(no) {
        
        $.ajax({
            url: "select.php",
            method: "POST",
            data: {
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
    function del_rec(no) {

        let confirmAction = confirm("Are you sure ?");
        if (confirmAction) {            

            var pgn = $("#curpageno").val();

            $.ajax({
                url: "changests.php",
                method: "POST",
                data: {
                    EmailID: no,
                    Action: 0,
                    pageno: pgn
                },
                success: function(dataabc) {
            

                    show_page(pgn);
                    alert(dataabc);
            
                }
            });


        } else {
            
        }


    }

    function undel_rec(no) {
        let confirmAction = confirm("Are you sure ?");
        if (confirmAction) {


            var pgn = $("#curpageno").val();

            $.ajax({
                url: "changests.php",
                method: "POST",
                data: {
                    EmailID: no,
                    Action: 1,
                    pageno: pgn
                },
                success: function(dataabc) {
            
                    show_page(pgn);
                    alert(dataabc);

            
                }
            });

        } else {
            
        }


    }
</script>
