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
 * Hook callbacks for tool_ribbons.
 * @package    tool_ribbons
 * @copyright  2020 onwards Conn Warwicker
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace tool_ribbons;

use core\hook\output\before_standard_head_html_generation;
use core\hook\output\before_standard_top_of_body_html_generation;

defined('MOODLE_INTERNAL') || die;

/**
 * Hook callbacks for tool_ribbons.
 * @package    tool_ribbons
 * @copyright  2020 onwards Conn Warwicker
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class hook_callbacks {

    /**
     * Adds the markup for the ribbons to the top of the page body.
     *
     * @param \core\hook\output\before_standard_top_of_body_html_generation $hook
     */
    public static function before_standard_top_of_body_html(before_standard_top_of_body_html_generation $hook): void {
        tool_ribbons_before_standard_top_of_body_html_callback($hook);
    }

    /**
     * Adds the CSS for the ribbons in a <style> tag to the page header.
     *
     * @param \core\hook\output\before_standard_head_html_generation $hook
     */
    public static function before_standard_head_html(before_standard_head_html_generation $hook): void {
        tool_ribbons_before_standard_html_head_callback($hook);
    }
}
