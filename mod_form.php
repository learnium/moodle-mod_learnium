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
 * The main learnium configuration form
 *
 * @package    mod_learnium
 * @copyright  2014 Learnium Limited <rebecca@learnium.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot.'/course/moodleform_mod.php');

/**
 * Module instance settings form
 */
class mod_learnium_mod_form extends moodleform_mod {

    /**
     * Defines forms elements
     */
    public function definition() {

        $mform = $this->_form;

        //-------------------------------------------------------------------------------
        // Adding the "general" fieldset, where all the common settings are showed
        $mform->addElement('header', 'general', get_string('general', 'form'));

        // Adding the standard "name" field
        $mform->addElement('text', 'name', 'Activity Name', array('size'=>'64'));
        if (!empty($CFG->formatstringstriptags)) {
            $mform->setType('name', PARAM_TEXT);
        } else {
            $mform->setType('name', PARAM_CLEAN);
        }
        $mform->addRule('name', null, 'required', null, 'client');
        $mform->addRule('name', get_string('maximumchars', '', 255), 'maxlength', 255, 'client');
        $mform->addHelpButton('name', 'learniumname', 'learnium');

        // Adding the standard "intro" and "introformat" fields
        $this->add_intro_editor();

        //-------------------------------------------------------------------------------
        // Adding the rest of learnium settings, spreeading all them into this fieldset
        // or adding more fieldsets ('header' elements) if needed for better logic
        $mform->addElement('text', 'bridgeid', get_string('bridgeid', 'learnium'), array('size'=>'64'));
        $mform->addElement('text', 'secret', get_string('secret', 'learnium'), array('size'=>'64'));

        $mform->addElement('header', 'learniumfieldset', get_string('additionalsettings', 'learnium'));
        $mform->addElement('text', 'groupid', get_string('groupid', 'learnium'), array('size'=>'64'));

        $env_options = array("https://www.learnium.net/auth/sso/bridge/" => get_string('production', 'learnium'),
                             "https://www.alpha.learnium.net/auth/sso/bridge/" => get_string('uat', 'learnium'),
                             "http://www.mysandbox.learnium.net:8000/auth/sso/bridge/" => get_string('sandbox', 'learnium'));
        $mform->addElement('select', 'site', get_string('env', 'learnium'), $env_options);

        //-------------------------------------------------------------------------------
        // add standard elements, common to all modules
        $this->standard_coursemodule_elements();
        //-------------------------------------------------------------------------------
        // add standard buttons, common to all modules
        $this->add_action_buttons();
    }
}
