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

defined('MOODLE_INTERNAL') || die();

require_login();

?>
<link href="<?php echo new moodle_url('/local/lmsexport/CSS/Custom.css'); ?>" rel="stylesheet" />

<div class="nav-area">
  <div class="logo"><img src="<?php echo new moodle_url('/local/lmsexport/pages/eklavvya.png'); ?>" style="max-width: 37%;"></div>
  <ul class="page-nav11">
    <li><a href="<?php echo new moodle_url('/local/lmsexport/pages/index.php'); ?>">Home</a></li>
    <?php
    if (is_siteadmin()) {
    ?>
      <li><a class="active" href="<?php echo new moodle_url('/local/lmsexport/pages/adminexport.php'); ?>">API Settings</a></li>
        <?php
    }
    ?>

    <?php
    if (is_siteadmin() || (user_has_role_assignment($USER->id, 3))) {
    ?>
      <li><a href="<?php echo new moodle_url('/local/lmsexport/pages/exam/index.php'); ?>">Exam(s) </a></li>
      <li><a href="<?php echo new moodle_url('/local/lmsexport/pages/proctor/index.php'); ?>">Proctor(s)</a></li>
      <li><a href="<?php echo new moodle_url('/local/lmsexport/pages/examiner/index.php'); ?>">Examiner(s)</a></li>
      <li><a href="<?php echo new moodle_url('/local/lmsexport/pages/result/index.php'); ?>">Result</a></li>

        <?php
        if (is_siteadmin()) {
        ?>
        <li><a class="active" href="<?php echo new moodle_url('/local/lmsexport/pages/adminuserlog/index.php'); ?>">
            User Logs</a></li>
            <?php
        }
    }
    ?>
  </ul>
</div>
