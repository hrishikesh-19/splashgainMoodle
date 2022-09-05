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


echo $OUTPUT->header();
?>

<div class="auth_form">
    
    <?php require('ekl_nav.php');  ?>
           
    <div id="content" class="col-sm-12 col-xs-12 pt-4" >

    </div>



</div>

<?php

echo $OUTPUT->footer();

?>


<script>
$(document).ready(function(){       

    $("#content").html('Please Wait...');   


    $.ajax({url:"dashboard_getdata.php",
        method:"POST",
        data:{mypost:123},
    success:function(dataabc){      
        $("#content").html(dataabc);
        
    }});


});
</script>
