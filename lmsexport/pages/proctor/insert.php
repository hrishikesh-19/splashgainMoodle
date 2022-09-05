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

$rv = array();

$rv['isok'] = 0;
$rv['msg'] = '';
$rv['firstnameerrormsg'] = '';
$rv['lastnameerrormsg'] = '';
$rv['mobilenoerrormsg'] = '';
$rv['emailiderrormsg'] = '';
$rv['paswrderrormsg'] = '';
$rv['confirmpasswrderrormsg'] = '';

$isok = 1;

$firstnameerror = 0;

$lastnameerror = 0;

$mobilenoerror = 0;

$emailiderror = 0;

$paswrderror = 0;

$confirmpasswrderror = 0;



if (isset($_POST['firstname'])) {
    $firstname = $_POST['firstname'];
    if (strlen($firstname) <= 0) {
        $firstnameerror = 1;
    }
} else {
    $firstnameerror = 1;
}


if (isset($_POST['lastname'])) {
    $lastname = $_POST['lastname'];
    if (strlen($firstname) <= 0) {
        $lastnameerror = 1;
    }
} else {
    $lastnameerror = 1;
}


if (isset($_POST['mobileno'])) {
    $mobileno = trim(strval($_POST['mobileno']));


    if (preg_match('/^[0-9]{10,14}+$/', $mobileno)) {
        $mobilenoerror = 0;
    } else {
        $mobilenoerror = 1;
    }
}



if (isset($_POST['emailid'])) {
    $emailid = $_POST['emailid'];


    if (preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $emailid)) {
        $emailiderror = 0;
    } else {
        $emailiderror = 1;
    }
}


if (isset($_POST['paswrd'])) {
    $paswrd = strval($_POST['paswrd']);

    if (preg_match("/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[#$^+=!*()@%&]).{8,15}$/", $paswrd)) {
        $paswrderror = 0;
    } else {
        $paswrderror = 1;
    }
}



if (isset($_POST['confirmpasswrd'])) {
    $confirmpasswrd = $_POST['confirmpasswrd'];
}

if (isset($_POST['paswrd']) && isset($_POST['confirmpasswrd'])) {
    if ($paswrd != $confirmpasswrd) {
        $confirmpasswrderror = 1;
    }
}

$s = $firstnameerror . ' - ' . $lastnameerror . ' - ' . $mobilenoerror . ' - ' .
$emailiderror . ' - ' . $paswrderror . ' - ' . $confirmpasswrderror;


if ($firstnameerror || $lastnameerror || $mobilenoerror || $emailiderror || $paswrderror ||
$confirmpasswrderror) {
    $isok = 0;
} else {
    $isok = 1;
}



if ($isok) {


    $formfieldarray = array("FirstName" => $firstname, "LastName" => $lastname,
    "EmailID" => $emailid, "MobileNo" => $mobileno, "Password" => $paswrd, "IsEdit" => 0);
    $datatosend = array("CreateProctorInputObj" => $formfieldarray);


    $posturl = $apiurl . '/SplashService.svc/CreateProctorWebAPI';

    $response = get_api_response_withdata($posturl, $datatosend);
    $rv['isok'] = $response['CreateProctorWebAPIResult']['objStatusCodes'];
    $rv['msg'] = $response['CreateProctorWebAPIResult']['Message'];
} else {

    if ($firstnameerror) {
        $rv['firstnameerrormsg'] = 'Required';
    }

    if ($lastnameerror) {

        $rv['lastnameerrormsg'] = 'Required';
    }

    if ($mobilenoerror) {
        $rv['mobilenoerrormsg'] = 'Please Enter Valid Mobile Number';
    }


    if ($emailiderror) {
        $rv['emailiderrormsg'] = 'Please Enter Valid Email Address';
    }


    if ($paswrderror) {
        $rv['paswrderrormsg'] = 'Invalid Password';
    }

    if ($confirmpasswrderror) {
        $rv['confirmpasswrderrormsg'] = 'Password Not Match';
    }
}


echo json_encode($rv);
