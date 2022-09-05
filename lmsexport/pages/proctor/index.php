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



echo $OUTPUT->header();


?>

<div class="main_content">
    <?php require('../ekl_nav.php');  ?>
    
    <div id="title_ui" class="col-md-12 pt-4 auth_form">
        <h3> Proctor(s) >> Proctor Management</h3>
    </div>
    <div class="col-sm-12 pt-4">

        <a href="newproctor.php"><button class="btn btn-outline-primary">New Proctor </button> </a>
        <a href="assign/index.php"><button class="btn btn-outline-primary"> Proctor Assignment </button></a>

    </div>
    <!-- title -->

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
                //console.log(dataabc);
                $("#content").html(dataabc);
                //alert(dataabc);
            }
        });





    });
</script>



<script>
    function show_page(no) {
        //alert(no);        
        $.ajax({
            url: "select.php",
            method: "POST",
            data: {
                pageno: no,
                pagesize: <?php echo $pagesize; ?>
            },
            success: function(dataabc) {
                //console.log(dataabc);
                $("#content").html(dataabc);
                //alert(dataabc);
            }
        });


    }
</script>

<script>
    function del_rec(no) {
        let confirmAction = confirm("Are you sure ?");
        if (confirmAction) {
            //alert("Action successfully executed");

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
                    //console.log(dataabc);        

                    show_page(pgn);
                    alert(dataabc);
                    //alert(dataabc);
                }
            });


        } else {
            //alert("Action canceled");
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
                    //console.log(dataabc);        

                    //show_page(<?php echo $pageno; ?>);
                    show_page(pgn);
                    alert(dataabc);

                    //alert(dataabc);
                }
            });

        } else {
            //alert("Action canceled");
        }


    }
</script>
