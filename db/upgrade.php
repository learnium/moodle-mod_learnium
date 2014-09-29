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
 * This file keeps track of upgrades to the learnium module
 *
 * @package    mod_learnium
 * @copyright  2014 Learnium Limited <rebecca@learnium.net>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Execute learnium upgrade from the given old version
 *
 * @param int $oldversion
 * @return bool
 */
function xmldb_learnium_upgrade($oldversion) {
    global $DB;

    // Load DDL manager and XMLDB classes
    $dbman = $DB->get_manager();

    // Final return of upgrade result (true, all went good) to Moodle.
    return true;
}
