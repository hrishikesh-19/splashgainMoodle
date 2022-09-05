<?php
// This file is part of Moodle - http://moodle.org/local/imsexport/pages/adminuserlog/
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


error_reporting(1);


require_login();


global $USER, $DB;

$eklavyaobj = $DB->get_record_sql("SELECT apiurl, examapikey, timezone1 FROM {local_lmsexport}" );

$apiurl = $eklavyaobj->apiurl;


echo $OUTPUT->header();

?>

<div class="main_content1">

    <?php require('../ekl_nav.php');  ?>
    
    <div id="selector_ui">

    <!-- title -->
    <div  class="col-sm-12 row" >  
        <div id="title_ui" class="col-md-12 pt-4 auth_form " >
                <h3>Admin User Log(s) </h3>
        </div>     
    </div>

    <div id="LinksUI" >
            
                    

    </div>
    

</div>


<?php
echo $OUTPUT->footer();

?>




<script>
$(document).ready(function(){

 

$.ajax({url:"geturls.php",
    method:"POST",
    
    success:function(dataabc){
$("#LinksUI").html(dataabc);
    // alert(dataabc);
}});

});
</script>





