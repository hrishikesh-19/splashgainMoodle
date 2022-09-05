<?php
// This file is part of Moodle - http://moodle.org/local/imsexport/
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

use local_lmsexport\lmsexport;

require(__DIR__ . '/../../config.php');
require_once($CFG->libdir.'/adminlib.php');

global $PAGE, $CFG, $OUTPUT;

admin_externalpage_setup('local_lmsexport');

$title = get_string('pluginname', 'local_lmsexport');
$PAGE->set_title($title);
$PAGE->set_heading($title);

$manager = new lmsexport();
$manager->process_form();

echo $OUTPUT->header();
echo $OUTPUT->heading($title);
echo $OUTPUT->footer();
