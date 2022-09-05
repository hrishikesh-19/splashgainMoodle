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


if (isset($_POST['edit_rec_id'])) {
    $emailid = $_POST['edit_rec_id'];

    $datatosend = array("obj" => array("EmailID" => $emailid));
    $posturl = $apiurl . '/SplashService.svc/GetProctorDetailsByEmailIDWebAPI';

    $responsearray = get_api_response_withdata($posturl, $datatosend);

    $procdetailsresponse = $responsearray['GetProctorDetailsByEmailIDWebAPIResult'];

    $firstname = $procdetailsresponse['FirstName'];

    $lastname = $procdetailsresponse['LastName'];

    $mobileno = $procdetailsresponse['MobileNo'];

    $emailid = $procdetailsresponse['ProctorEmailID'];

    $paswrd = $procdetailsresponse['Password'];

    $confirmpasswrd = $paswrd;
} else {
    $emailid = '';

?>
    <script>
        alert('Invalid Proctor to Edit');
        window.location.href = "index.php";
    </script>
    <?php

}






echo $OUTPUT->header();


?>

<div class="main_content">

    <?php require('../ekl_nav.php');  ?>

    <div id="title_ui" class="col-md-12 pt-4">
        <h3> Proctor(s) >> Editing Proctor</h3>
    </div>

    <div class="container">

        <!-- First Name Field -->
        <div class="row rowStyle" style="width:100%;text-align:left">
            <div class="col-md-3"> <label>*First Name :</label></div>
            <div class="col-md-6">

                <input type="text" id="firstname" name="firstname" class="form-control" value="<?php if (isset($firstname)) {
                                                                                                   echo $firstname;
                                                                                               } ?>" required />
            </div>
            <div class="col-md-3"><label id="firstname_err" class="ErrorMsg"></label></div>
        </div>


        <!-- Last Name Field -->
        <div class="row rowStyle" style="width:100%;text-align:left">
            <div class="col-md-3"> <label>*Last Name : </label></div>
            <div class="col-md-6">

                <input type="text" id="lastname" name="lastname" class="form-control" value="<?php if (isset($lastname)) {
                                                                                                 echo $lastname;
                                                                                             } ?>" />
            </div>
            <div class="col-md-3"><label id="lastname_err" class="ErrorMsg"></label></div>
        </div>


        <!-- Mobile  Field -->
        <div class="row rowStyle" style="width:100%;text-align:left">
            <div class="col-md-3"> <label>*Mobile Number :</label></div>
            <div class="col-md-6">

                <input type="text" id="mobileno" name="mobileno" class="form-control" 
                required title="Enter valid mobile no" pattern="/^[0-9]{10,14}+$/" minlength="10" 
                maxlength="14" value="<?php if (isset($mobileno)) {
                                          echo $mobileno;
                                      } ?>" />
            </div>
            <div class="col-md-3"><label id="mobileno_err" class="ErrorMsg"></label></div>
        </div>

        <!-- eMail Field -->
        <div class="row rowStyle" style="width:100%;text-align:left">
            <div class="col-md-3"> <label>*Email ID :</label></div>
            <div class="col-md-6">

                <input type="text" id="emailid" name="emailid" class="form-control" 
                required title="enter valid email address" ; disabled value="<?php if (isset($emailid)) {
                                                                                echo $emailid;
                                                                             } ?>" />
            </div>
            <div class="col-md-3"><label id="emailid_err" class="ErrorMsg"></label></div>
        </div>


        <!-- Password Field -->
        <div class="row rowStyle" style="width:100%;text-align:left">
            <div class="col-md-3"> <label>*Password :</label></div>
            <div class="col-md-6">

                <input type="password" id="paswrd" name="paswrd" class="form-control"
                 required title="Must contain at least one number and one uppercase and lowercase letter,
                  and at least 8 or more characters" value="<?php if (isset($paswrd)) {
                                                                echo $paswrd;
                                                            } ?>" />
                Password must be 8-15 characters long with at least one numeric, 
                one upper case, one lower case and one special character.
            </div>
            <div class="col-md-3"><label id="paswrd_err" class="ErrorMsg"></label></div>
        </div>

        <!-- Confirm Password Field -->
        <div class="row rowStyle" style="width:100%;text-align:left">
            <div class="col-md-3"> <label>*Confirm Password :</label></div>
            <div class="col-md-6">

                <input type="password" id="confirmpasswrd" name="confirmpasswrd" 
                class="form-control" value="<?php if (isset($paswrd)) {
                                                echo $paswrd;
                                            } ?>" />
            </div>
            <div class="col-md-3"><label id="confirmpasswrd_err" class="ErrorMsg"></label></div>
        </div>

        <!-- Submit Field -->
        <div class="row rowStyle">
            <div class="col-md-3"></div>
            <div class="col-md-6" style="text-align:center;margin-top:2%!important">
                <input type="button" id="submitbtn" name="continue" 
                class="m-element-button btn btn-primary" value="Submit" onclick="submit_page();" />
                <a href="<?php echo new moodle_url('/local/lmsexport/pages/proctor/index.php'); ?>">
                    <input type="button" id="resetbtn" name="Cancel" 
                    class="m-element-button btn btn-primary" value="Cancel" />
                </a>
            </div>
            <div class="col-md-3"> </div>
        </div>
    </div>
</div>
<?php
echo $OUTPUT->footer();


?>

<script>
    $(document).ready(function() {

      

    });
</script>

<script>
    function submit_page(no) {
        //alert(no);

        $.ajax({
            url: "update.php",
            method: "POST",
            data: {
                firstname: $("#firstname").val(),
                lastname: $("#lastname").val(),
                mobileno: $("#mobileno").val(),
                emailid: $("#emailid").val(),
                paswrd: $("#paswrd").val(),
                confirmpasswrd: $("#confirmpasswrd").val()
            },
            success: function(dataabc) {
                //console.log(dataabc);
                //$("#content").html(dataabc);
                var data = JSON.parse(dataabc);
                if (data['isok'] == 1) {
                    //alert('Registered Suc');
                    alert(data['msg']);

                    window.location.href = "index.php";
                } else {


                    $("#firstname_err").html(data['firstnameerrormsg'])
                    $("#lastname_err").html(data['lastnameerrormsg'])
                    $("#mobileno_err").html(data['mobilenoerrormsg'])
                    $("#emailid_err").html(data['emailiderrormsg'])
                    $("#paswrd_err").html(data['paswrderrormsg'])
                    $("#confirmpasswrd_err").html(data['confirmpasswrderrormsg'])
                    // $("#Errors_ui").html(dataabc);
                    //alert(data['msg']);
                }

            }
        });


    }
</script>
