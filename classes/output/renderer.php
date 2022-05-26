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
 * Output renderer for the environment ribbons plugin.
 * @package    tool_ribbons
 * @copyright  2020 onwards Conn Warwicker
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_ribbons\output;

use moodle_url;
use plugin_renderer_base;

defined('MOODLE_INTERNAL') || die;

/**
 * Output renderer for the environment ribbons plugin.
 * @package    tool_ribbons
 * @copyright  2020 onwards Conn Warwicker
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class renderer extends plugin_renderer_base {

    /**
     * Render the navigation breadcrumbs.
     * @param string $page
     */
    public function render_navbar(string $page) {

        $this->page->navbar->add(get_string('pluginname', 'tool_ribbons'));

    }

    /**
     * Render a general confirmation box for deleting something.
     *
     * @param string $name The name/title of the "thing" being deleted.
     * @param moodle_url $yesurl
     * @param moodle_url $nourl
     * @return mixed
     */
    public function render_confirm_delete(string $name, moodle_url $yesurl, moodle_url $nourl) {
        return $this->output->confirm(get_string('delete:sure', 'tool_ribbons', $name), $yesurl, $nourl);
    }

}
