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
global $CFG, $PAGE, $COURSE, $USER;

require_login();

function local_lmsexport_extend_navigation(global_navigation $navigation) {
    global $CFG, $PAGE, $COURSE;
    local_lmsexport_main_menu();
    local_lmsexport_render_navbar_output();
}

function lmsexport_custom_menu_item(custom_menu_item $menunode, $parent, $pmasternode, $title, $url) {
    global $PAGE, $CFG;
    static $submenucount = 0;
    $masternode = $PAGE->navigation->add($title, $url, navigation_node::TYPE_CONTAINER, null,
    null, new pix_icon('a/r_breadcrumb', 'play'));
    $masternode->title($menunode->get_title());
    $masternode->isexpandable = true;
    $masternode->showinflatnavigation = true;
}
function local_lmsexport_main_menu() {
    global $USER;
    $allmenu = array();

    if (is_siteadmin()) {

        $allmenu[] = ['menutitle' => "Proctoring Settings", "menu_url" => new moodle_url('/local/lmsexport/pages/index.php')];
    }

    if (user_has_role_assignment($USER->id, 5)) {

        $allmenu[] = ['menutitle' => "Proctored Exam", "menu_url" => new moodle_url('/local/lmsexport/pages/studentdashboaed.php')];
    }

    if (user_has_role_assignment($USER->id, 3)) {

        $allmenu[] = ['menutitle' => "Proctoring Settings", "menu_url" => new moodle_url('/local/lmsexport/pages/index.php')];
    }

    if ($allmenu) {
        foreach ($allmenu as $singlemenu) {
            lmsexport_custom_menu_item(new custom_menu($singlemenu['menutitle'],
            current_language()), 0, null, $singlemenu['menutitle'], $singlemenu['menu_url']);
        }
    }
}
function local_lmsexport_get_string($string) {
    $title = $string;
    $text = explode(',', $string, 2);
    if (count($text) == 2) {
        // Check the validity of the identifier part of the string.
        if (clean_param($text[0], PARAM_STRINGID) !== '') {
            // Treat this as atext language string.
            $title = get_string($text[0], $text[1]);
        }
    }
    return $title;
}
function local_lmsexport_render_navbar_output() {
}

function local_lmsexport_course_edit_definition(course_edit_form $formwrapper, MoodleQuickForm $mform) {

    echo 'called course_edit_definition';
    exit;

    $mform->addElement('header', 'testheader', 'new header');

    $mform->addElement('text', 'testfield', 'text field 1 (number > 100)');

    $mform->setType('testfield', PARAM_INT);

    $mform->addElement('text', 'testfield2', 'text field 2');

    $mform->setType('testfield2', PARAM_ALPHA);
}
