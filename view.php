<?php

// This file is part of Moodle - http://moodle.org/
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
 * Logs a user into Learnium
 *
 * @package    mod_learnium
 * @copyright  2014 Learnium Limited <rebecca@learnium.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once(dirname(dirname(dirname(__FILE__))).'/config.php');
require_once(dirname(dirname(dirname(__FILE__))).'/lib/adminlib.php');
if(!class_exists('JWT')){
    require_once(dirname(__FILE__).'/JWT.php');
}
require_once(dirname(__FILE__).'/lib.php');

$id = optional_param('id', 0, PARAM_INT); // course_module ID, or
$n  = optional_param('n', 0, PARAM_INT);  // learnium instance ID - it should be named as the first character of the module
$next  = optional_param('next', '', PARAM_TEXT);

if ($id) {
    $cm         = get_coursemodule_from_id('learnium', $id, 0, false, MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $cm->course), '*', MUST_EXIST);
    $learnium  = $DB->get_record('learnium', array('id' => $cm->instance), '*', MUST_EXIST);
} elseif ($n) {
    $learnium  = $DB->get_record('learnium', array('id' => $n), '*', MUST_EXIST);
    $course     = $DB->get_record('course', array('id' => $learnium->course), '*', MUST_EXIST);
    $cm         = get_coursemodule_from_instance('learnium', $learnium->id, $course->id, false, MUST_EXIST);
} else {
    error('You must specify a course_module ID or an instance ID');
}

require_login($course, true, $cm);
$context = context_module::instance($cm->id);

add_to_log($course->id, 'learnium', 'view', "view.php?id={$cm->id}", $learnium->name, $cm->id);

// Print the page header
$PAGE->set_url('/mod/learnium/view.php', array('id' => $cm->id));
$PAGE->set_title(format_string($learnium->name));
$PAGE->set_heading(format_string($course->fullname));
$PAGE->set_context($context);

// Output starts here
echo $OUTPUT->header();

// Get the course context
$course_context = context_course::instance($course->id);
$postback_url = new moodle_url("/mod/learnium/postback.php", array("id" => $learnium->id));

// Get the data we want to send to Learnium
$data = array(
    "user" => array(
        "id" => $USER->id,
        "firstname" => $USER->firstname,
        "lastname" => $USER->lastname,
        "email" => $USER->email,
    ),
    "course" => array(
        "id" => $course->id,
        "name" => $course->fullname,
    ),
    "is_staff" => has_capability('moodle/course:viewhiddensections', $course_context),
    "group_gbid" => $learnium->groupid,
    "endpoints" => array(
        "user:postback" => $postback_url->out(true),
    ),
    "source_vle" => $CFG->release,
    "bridge_version" => "MDL_" . get_component_version("mod_learnium"),
    "next_url" => $next,
);

// Begin Learnium SSO
$form_target = $learnium->site;
echo $OUTPUT->heading('Logging into Learnium...');

// Write form
?>
<!--<iframe name="my_iframe" style="border: none;width: 100%;height: 900px;"></iframe>-->
<form id="learnium_form" method="post" action="<? print($form_target); ?>" target="_parent" encType="application/x-www-form-urlencoded">
    <input type="hidden" name="bridgeid" value="<? print($learnium->bridgeid); ?>" />
    <input type="hidden" name="data" value="<? print(JWT::encode($data, $learnium->secret)); ?>" />
</form>
<script type="text/javascript">
document.getElementById("learnium_form").submit();
</script>
<?php

// Finish the page
echo $OUTPUT->footer();
