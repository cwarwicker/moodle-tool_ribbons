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
 * Allow the management of environment ribbons.
 *
 * @package    tool_ribbons
 * @copyright  2020 onwards Conn Warwicker
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

require_once('../../../config.php');

// Must be logged in.
require_login();

// Need the capability to configure the plugin.
$context = context_system::instance();
require_capability('tool/ribbons:config_ribbons', $context);

// Possible parameters we may be passing through.
$id = optional_param('id', 0, PARAM_INT);

$title = get_string('ribbons', 'tool_ribbons') . ' : ' . get_string('delete');

// Set up the page.
$PAGE->set_context($context);
$PAGE->set_url( new moodle_url('/admin/tool/ribbons/delete.php') );
$PAGE->set_title( $title );

// Load the renderer object.
$renderer = $PAGE->get_renderer('tool_ribbons');

echo $OUTPUT->header();
echo $OUTPUT->heading($title);

echo $renderer->render_confirm_delete(get_string('ribbon', 'tool_ribbons'), new moodle_url('/admin/tool/ribbons/index.php', [
    'action' => 'delete',
    'id' => $id,
    'confirmed' => 1,
    'sesskey' => sesskey()
]), new moodle_url('/admin/tool/ribbons/index.php'));

echo $OUTPUT->footer();
