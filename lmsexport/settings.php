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

defined('MOODLE_INTERNAL') || die();

if ($hassiteconfig) {
    $settingspage = new admin_externalpage('local_lmsexport', new lang_string('pluginname', 'local_lmsexport'),
                                           new moodle_url('/local/lmsexport/index.php'), 'moodle/site:config');
    $ADMIN->add('accounts', $settingspage);
}
