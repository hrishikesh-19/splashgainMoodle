<?php
// This file is part of Moodle - http://moodle.org/local/imsexport/pages/exam/details/
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


if (isset($_POST['aso']) && ($_POST['aso'] != '')) {
    $aso = $_POST['aso'];
}
?>

<div class="row m-t-1 text-left">
    <div class="col-md-3"><label class="text center"></label></div>
    <div class="col-md-6" style="margin:5px ;">
        <div class="form-check">
            <input type="radio" id="quiz_by_radio" name="quiz_by_radio" 
            class="form-check-input" <?php if ($aso == 1) {
                echo 'checked="checked"';
                                     } ?>
            value="course" onchange="changeassociated(1);">
            <label class="form-check-label">Do you want to select quiz associated to course? </label>

        </div>
    </div>
</div>
<div class="row m-t-1 text-left">
    <div class="col-md-3"><label class="text center"></label></div>
    <div class="col-md-6" style="margin:5px ;">
        <div class="form-check">
            <input type="radio" id="quiz_by_radio" name="quiz_by_radio"
            class="form-check-input" <?php if ($aso == 2) {
                echo 'checked="checked"';
                                     } ?>
            value="topic" onchange="changeassociated(2);">
            <label class="form-check-label">Do you want to select quiz associated to topic?</label>
        </div>
    </div>
</div>
