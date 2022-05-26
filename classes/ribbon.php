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
 * Environment Ribbon object
 * @package    tool_ribbons
 * @copyright  2020 onwards Conn Warwicker
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_ribbons;

use core\notification;
use curl;

defined('MOODLE_INTERNAL') || die;

/**
 * Environment Ribbon object
 * @package    tool_ribbons
 * @copyright  2020 onwards Conn Warwicker
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class ribbon {

    /**
     * Array of all the possible ribbon types which are supported.
     */
    const TYPES = ['static', 'script'];

    /**
     * Array of the possible ribbon positions.
     */
    const POSITIONS = ['left-top', 'right-top', 'left-bottom', 'right-bottom'];

    /**
     * @var int ID of the database record
     */
    protected $id = 0;

    /**
     * @var string Position of the ribbon
     */
    protected $position;

    /**
     * @var string Type of ribbon
     */
    protected $type;

    /**
     * @var string Hyperlink
     */
    protected $link;

    /**
     * @var string Background colour
     */
    protected $colourbg;

    /**
     * @var string Text colour
     */
    protected $colourtext;

    /**
     * @var mixed Data to load into the ribbon text
     */
    protected $data;

    /**
     * @var int Is the ribbon enabled?
     */
    protected $enabled = 1;

    /**
     * Ribbon constructor.
     * @param int $id
     */
    public function __construct(int $id) {

        global $DB;

        $record = $DB->get_record('tool_ribbons_ribbons', ['id' => $id]);
        if ($record) {
            $this->id = $record->id;
            $this->position = $record->position;
            $this->type = $record->type;
            $this->data = $record->data;
            $this->link = $record->link;
            $this->colourbg = $record->colourbg;
            $this->colourtext = $record->colourtext;
            $this->enabled = $record->enabled;
        }

    }

    /**
     * Check if the ribbon exists in the database.
     * @return bool
     */
    public function exists() : bool {
        return ($this->id > 0);
    }

    /**
     * Get the ribbon id
     * @return int
     */
    public function get_id() : int {
        return $this->id;
    }

    /**
     * Get the ribbon type
     * @return string|null
     */
    public function get_type() : ?string {
        return $this->type;
    }

    /**
     * Get the ribbon position
     * @return string|null
     */
    public function get_position() : ?string {
        return $this->position;
    }

    /**
     * Get the ribbon data
     * @return string|null
     */
    public function get_data() : ?string {
        return $this->data;
    }

    /**
     * Get the ribbon hyperlink
     * @return string|null
     */
    public function get_link() : ?string {
        return $this->link;
    }

    /**
     * Get the ribbon background colour
     * @return string|null
     */
    public function get_colourbg() : ?string {
        return $this->colourbg;
    }

    /**
     * Get the ribbon text colour
     * @return string|null
     */
    public function get_colourtext() : ?string {
        return $this->colourtext;
    }

    /**
     * Check if the ribbon is enabled.
     * @return bool
     */
    public function is_enabled() : bool {
        return ((int)$this->enabled === 1);
    }

    /**
     * Set the ribbon type
     * @param string $type
     * @return ribbon
     */
    public function set_type(string $type) : ribbon {
        $this->type = $type;
        return $this;
    }

    /**
     * Set the ribbon position
     * @param string $position
     * @return ribbon
     */
    public function set_position(string $position) : ribbon {
        $this->position = $position;
        return $this;
    }

    /**
     * Set the ribbon data
     * @param string $data
     * @return ribbon
     */
    public function set_data(string $data) : ribbon {
        $this->data = $data;
        return $this;
    }

    /**
     * Set the ribbon hyperlink
     * @param string $link
     * @return ribbon
     */
    public function set_link(string $link) : ribbon {
        $this->link = $link;
        return $this;
    }

    /**
     * Set ribbon background colour.
     * @param string $colour
     * @return ribbon
     */
    public function set_colour_bg(string $colour) : ribbon {
        $this->colourbg = $colour;
        return $this;
    }

    /**
     * Set ribbon text colour.
     * @param string $colour
     * @return ribbon
     */
    public function set_colour_text(string $colour) : ribbon {
        $this->colourtext = $colour;
        return $this;
    }

    /**
     * Set the enabled value
     * @param bool $value
     * @return ribbon
     */
    public function set_enabled(bool $value) : ribbon {
        $this->enabled = (int)$value;
        return $this;
    }

    /**
     * Get the text to display, depending on the ribbon type
     * @return string
     */
    protected function get_display_text() : string {

        global $CFG;

        switch ($this->type) {
            case 'static':
                return $this->data;
            break;
            case 'script':
                // Try to get the contents of the script, but don't try for more than a few seconds if it's taking too long.
                $curl = new curl();
                $result = $curl->get(static::convert_placeholders($this->data));
                return ($result) ? $result :
                    // If we have debugging enabled, display the ribbon with the text as 'No data'. Otherwise don't display at all.
                    (($CFG->debugdisplay) ? get_string('nodata', 'tool_ribbons') : '');
            break;
        }

    }

    /**
     * Display the ribbon.
     * @return string
     */
    public function display() : string {

        $url = (trim($this->link) !== '') ? $this->link : false;
        $text = s($this->get_display_text());

        // If there is no data, do not display the ribbon.
        if (!strlen($text)) {
            return '';
        }

        $attributes = [
            'id' => 'tool-ribbons-ribbon-' . $this->id,
            'class' => 'tool-ribbons-fork-ribbon ' . $this->position . ' fixed',
            'data-ribbon' => $text,
            'title' => $text
        ];

        if ($url) {
            return \html_writer::link($url, $text, $attributes);
        } else {
            return \html_writer::span($text, '', $attributes);
        }

    }

    /**
     * Generate css to change colours
     * @return string
     */
    public function css() : string {

        $bg = ($this->colourbg && $this->colourbg !== '') ? 'background-color: ' . $this->colourbg . ';' : '';
        $text = ($this->colourtext && $this->colourtext !== '') ? 'color: ' . $this->colourtext . ';' : '';

        return <<<CSS
            #tool-ribbons-ribbon-{$this->id}:before {
                {$bg}
            }
            #tool-ribbons-ribbon-{$this->id}:after {
                {$text}
            }
CSS;

    }

    /**
     * Save the ribbon record.
     * @return bool|int
     */
    public function save() {

        global $DB;

        $params = [
            'position' => $this->position,
            'type' => $this->type,
            'data' => $this->data,
            'link' => $this->link,
            'colourbg' => $this->colourbg,
            'colourtext' => $this->colourtext,
            'enabled' => (int)$this->enabled,
        ];

        if ($this->id > 0) {
            $params['id'] = $this->id;
            return $DB->update_record('tool_ribbons_ribbons', $params);
        } else {
            return $DB->insert_record('tool_ribbons_ribbons', $params);
        }

    }

    /**
     * Run the chosen action on this ribbon.
     * @param string $action
     * @return bool|int
     */
    public function run(string $action) {

        global $PAGE;

        $confirmed = optional_param('confirmed', 0, PARAM_INT);

        if ($action == 'toggle') {
            $this->toggle();
            redirect($PAGE->url);
        } else if ($action == 'delete' && $confirmed) {
            $this->delete();
            notification::success(get_string('ribbon:deleted', 'tool_ribbons'));
            redirect($PAGE->url);
        }

    }

    /**
     * Delete the ribbon from the database
     * @return bool
     */
    protected function delete() {
        global $DB;
        return $DB->delete_records('tool_ribbons_ribbons', ['id' => $this->id]);
    }

    /**
     * Toggle the value of the enabled property to enable/disable.
     * @return bool|int
     */
    protected function toggle() {
        $this->set_enabled(!$this->is_enabled());
        return $this->save();
    }

    /**
     * Cvonert the ribbon object to an array for use in mustache templates.
     * @return array
     */
    public function to_array() : array {

        $return = [];
        $return['id'] = $this->id;
        $return['position'] = get_string($this->position, 'tool_ribbons');
        $return['type'] = get_string($this->type, 'tool_ribbons');
        $return['data'] = $this->data;
        $return['link'] = $this->link;
        $return['colourbg'] = $this->colourbg;
        $return['colourtext'] = $this->colourtext;
        $return['enabled'] = $this->enabled;
        return $return;

    }

    /**
     * Get an array of un-used ribbon positions.
     * @return array
     */
    public static function get_positions() : array {

        $positions = [];

        foreach (static::POSITIONS as $position) {
            $positions[$position] = get_string($position, 'tool_ribbons');
        }

        return $positions;

    }

    /**
     * Get an array of all the possible types of ribbon, with their lang string names. Useful for select menus.
     * @return array
     */
    public static function get_types() : array {

        $types = [];

        foreach (static::TYPES as $type) {
            $types[$type] = get_string('ribbon:type:' . $type, 'tool_ribbons');
        }

        return $types;

    }

    /**
     * Convert placeholders in the given text.
     * @param string $text
     * @return string
     */
    public static function convert_placeholders(string $text) : string {

        global $CFG, $USER;

        // Convert {www} to the wwwroot of the site.
        $text = str_replace('{www}', $CFG->wwwroot, $text);

        // Convert {userid} to USER id.
        $text = str_replace('{userid}', $USER->id, $text);

        return $text;

    }

    /**
     * Get all of the ribbons being used.
     * @param bool|null $enabled Do we only want the enabled ribbons?
     * @return array
     */
    public static function all(bool $enabled = null) : array {

        global $DB;

        $ribbons = [];
        $params = [];

        // Do we only want the enabled ones?
        if ($enabled === true) {
            $params['enabled'] = 1;
        }

        $records = $DB->get_records('tool_ribbons_ribbons', $params, null, 'id');
        foreach ($records as $record) {
            $ribbons[] = new ribbon($record->id);
        }

        return $ribbons;

    }

}