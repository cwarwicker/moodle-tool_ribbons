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

use core\output\notification;
use tool_ribbons\forms\ribbonsform;
use tool_ribbons\ribbon;

require_once('../../../config.php');

// Need to be logged in to view this page.
require_login();

// Need the capability to configure the plugin.
$context = context_system::instance();
require_capability('tool/ribbons:config_ribbons', $context);

// Possible parameters we may be passing through.
$page = optional_param('page', 'list', PARAM_TEXT);
$id = optional_param('id', 0, PARAM_INT);
$action = optional_param('action', false, PARAM_TEXT);

// Set up the page.
$PAGE->set_context($context);
$PAGE->set_url( new moodle_url('/admin/tool/ribbons/index.php') );
$PAGE->set_title( get_string('ribbons', 'tool_ribbons') );
$PAGE->set_heading( get_string('ribbons', 'tool_ribbons') );

// Are there any actions we want to run on this page?
if ($action && $id && confirm_sesskey()) {

    $ribbon = new ribbon($id);
    if ($ribbon->exists()) {
        $ribbon->run($action);
    }

}

// Load the renderer object.
$renderer = $PAGE->get_renderer('tool_ribbons');
$renderer->render_navbar($page);

// Are we viewing the list of ribbons?
switch ($page) {

    case 'list':

        $list = [];
        $ribbons = ribbon::all();
        foreach ($ribbons as &$ribbon) {

            // Convert the object to an array for easy use in mustache template.
            $array = $ribbon->to_array();

            // Now add some properties to store the action links and icons, etc... for displaying.
            $array['toggle_url'] = new moodle_url('/admin/tool/ribbons/index.php', [
                'action' => 'toggle',
                'id' => $ribbon->get_id(),
                'sesskey' => sesskey()
            ]);
            $array['toggle_icon'] = ($ribbon->is_enabled()) ? 'eye' : 'eye-slash';
            $array['toggle_title'] = ($ribbon->is_enabled()) ? get_string('disable', 'tool_ribbons') : get_string('enable',
                'tool_ribbons');

            $array['delete_url'] = new moodle_url('/admin/tool/ribbons/delete.php', [
                'id' => $ribbon->get_id(),
            ]);

            $array['edit_url'] = new moodle_url('/admin/tool/ribbons/index.php', [
                'page' => 'edit',
                'id' => $ribbon->get_id(),
            ]);

            $list[] = $array;

        }

        $content = $renderer->render_from_template('tool_ribbons/ribbons-list', [
            'new_ribbon_url' => new moodle_url('/admin/tool/ribbons/index.php', ['page' => 'new']),
            'list' => $list,
        ]);

        break;

    case 'new':
    case 'edit':

        $ribbon = new ribbon($id);

        $form = new ribbonsform( new moodle_url('/admin/tool/ribbons/index.php', ['page' => 'new']), $ribbon );
        $form->set_data([
            'id' => $ribbon->get_id(),
            'type' => $ribbon->get_type(),
            'data' => $ribbon->get_data()
        ]);

        // Process submitted form.
        if ($data = $form->get_data()) {

            $ribbon->set_type($data->type);
            $ribbon->set_position($data->position);
            $ribbon->set_link($data->link);
            $ribbon->set_colour_bg($data->colourbg);
            $ribbon->set_colour_text($data->colourtext);

            // If it's a static text, the data is in the 'text' field.
            if ($data->type == 'static') {
                $ribbon->set_data($data->text);
            } else if ($data->type == 'script') {
                $ribbon->set_data($data->script);
            }

            $ribbon->save();
            redirect($PAGE->url, get_string('ribbon:saved', 'tool_ribbons'), 5,
                notification::NOTIFY_SUCCESS );

        } else if ($form->is_cancelled()) {
            redirect($PAGE->url);
        } else {
            $content = $form->render();
        }

        break;

}

echo $OUTPUT->header();

echo $content;

echo $OUTPUT->footer();
