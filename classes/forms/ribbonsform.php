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
 * Environment Ribbons form
 * @package    tool_ribbons
 * @copyright  2020 onwards Conn Warwicker
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_ribbons\forms;

use tool_ribbons\ribbon;
use moodleform;

defined('MOODLE_INTERNAL') || die;

require_once($CFG->dirroot . '/lib/formslib.php');

/**
 * Environment Ribbons form
 * @package    tool_ribbons
 * @copyright  2020 onwards Conn Warwicker
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class ribbonsform extends moodleform {

    /**
     * Define the elements of the environment ribbons form.
     * @return void
     */
    protected function definition() {

        $ribbon = $this->_customdata;

        // Hidden fields.
        $this->_form->addElement('hidden', 'id');
        $this->_form->setType('id', PARAM_INT);

        // Position.
        $this->_form->addElement('select', 'position', get_string('ribbon:position', 'tool_ribbons'),
            ribbon::get_positions());
        $this->_form->addRule('position', get_string('error:required', 'tool_ribbons'), 'required');

        // Type of ribbon.
        $this->_form->addElement('select', 'type', get_string('type', 'tool_ribbons'), ribbon::get_types());
        $this->_form->addRule('type', get_string('error:required', 'tool_ribbons'), 'required');

        // Static text.
        $this->_form->addElement('text', 'text', get_string('ribbon:type:static', 'tool_ribbons'));
        $this->_form->setType('text', PARAM_TEXT);
        $this->_form->hideIf('text', 'type', 'neq', 'static');

        // Script end point.
        $this->_form->addElement('text', 'script', get_string('ribbon:type:script', 'tool_ribbons'));
        $this->_form->setType('script', PARAM_TEXT);
        $this->_form->hideIf('script', 'type', 'neq', 'script');
        $this->_form->addHelpButton('script', 'ribbon:type:script', 'tool_ribbons');

        // Colours.
        $this->_form->addElement('text', 'colourbg', get_string('ribbon:colour:bg', 'tool_ribbons'));
        $this->_form->addElement('text', 'colourtext', get_string('ribbon:colour:text', 'tool_ribbons'));
        $this->_form->setType('colourbg', PARAM_TEXT);
        $this->_form->setType('colourtext', PARAM_TEXT);
        $this->_form->addHelpButton('colourbg', 'ribbon:colour:bg', 'tool_ribbons');
        $this->_form->addHelpButton('colourtext', 'ribbon:colour:text', 'tool_ribbons');

        // Hyperlink.
        $this->_form->addElement('text', 'link', get_string('ribbon:link', 'tool_ribbons'));
        $this->_form->setType('link', PARAM_TEXT);
        $this->_form->addRule('type', get_string('error:maxlength', 'tool_ribbons', 255), 'maxlength', 255);

        $this->add_action_buttons();

        // Add default data if we are editing an existing ribbon.
        if ($ribbon) {
            $this->_form->setDefault('id', $ribbon->get_id());
            $this->_form->setDefault('position', $ribbon->get_position());
            $this->_form->setDefault('type', $ribbon->get_type());

            if ($ribbon->get_type() == 'static') {
                $this->_form->setDefault('text', $ribbon->get_data());
            } else if ($ribbon->get_type() == 'script') {
                $this->_form->setDefault('script', $ribbon->get_data());
            }

            $this->_form->setDefault('colourbg', $ribbon->get_colourbg());
            $this->_form->setDefault('colourtext', $ribbon->get_colourtext());
            $this->_form->setDefault('link', $ribbon->get_link());
        }

    }

    /**
     * Validate the form for errors.
     * @param array $data
     * @param array $files
     * @return array
     */
    public function validation($data, $files) {

        global $DB;

        // Before checking URLs, convert any placeholders.
        if (isset($data['script'])) {
            $data['script'] = ribbon::convert_placeholders($data['script']);
        }

        if (isset($data['link'])) {
            $data['link'] = ribbon::convert_placeholders($data['link']);
        }

        $errors = [];

        // Check that the type is valid.
        if (!isset($data['type']) || !in_array($data['type'], ribbon::TYPES)) {
            $errors['type'] = get_string('error:ribbon:type', 'tool_ribbons');
        }

        // Make sure that the position is valid.
        if (!isset($data['position']) || !in_array($data['position'], ribbon::POSITIONS)) {
            $errors['position'] = get_string('error:ribbon:position', 'tool_ribbons');
        } else {

            // Make sure that the position is not already taken.
            $check = $DB->get_record('tool_ribbons_ribbons', ['position' => $data['position']]);
            if ($check && $check->id <> $data['id']) {
                $errors['position'] = get_string('error:ribbon:position:used', 'tool_ribbons');
            }

        }

        // Check that the colours are valid.
        $regex = '/^#([A-F0-9]{6}|[A-F0-9]{3})$/i';

        // Background.
        if (isset($data['colourbg']) && $data['colourbg'] !== '' && !preg_match($regex, $data['colourbg'])) {
            $errors['colourbg'] = get_string('error:colour', 'tool_ribbons');
        }

        // Text.
        if (isset($data['colourtext']) && $data['colourtext'] !== '' && !preg_match($regex, $data['colourtext'])) {
            $errors['colourtext'] = get_string('error:colour', 'tool_ribbons');
        }

        // Check that the hyperlink is valid.
        if (isset($data['link']) && $data['link'] !== '' && !filter_var($data['link'], FILTER_VALIDATE_URL)) {
            $errors['link'] = get_string('error:link', 'tool_ribbons');
        }

        // If script URL is set, make sure it's a valid url.
        if (isset($data['script']) && $data['script'] !== '' && !filter_var($data['script'], FILTER_VALIDATE_URL)) {
            $errors['script'] = get_string('error:link', 'tool_ribbons');
        }

        return $errors;

    }

}
