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

if (isset($_POST['pageno'])) {
    $pageno = $_POST['pageno'];
    $_SESSION['proctorlistpageno'] = $pageno;
} else {
    $pageno = 1;
}


if (isset($_POST['EmailID'])) {

    $emailid = $_POST['EmailID'];
    if ($_POST['Action'] == "1") {
        $action = 1;
    } else {
        $action = 0;
    }

    $usrobj = array("EmailID" => $emailid, "UserType" => 8, "Action" => $action);

    $usaryobj = array();
    array_push($usaryobj, $usrobj);

    $sendobjary = array("UserDetailsInputListObj" => $usaryobj);

    $datatosend = array("EnableDisableUserWebAPIInputObj" => $sendobjary);

    $posturl = $apiurl . '/SplashService.svc/EnableDisableUserWebAPI';



    $response = get_api_response_withdata($posturl, $datatosend);

    echo $response['EnableDisableUserWebAPIResult']['UserDetailsOutputListObj'][0]['Message'];
} else {
    echo 'Something Went Worng' . $s;
}
