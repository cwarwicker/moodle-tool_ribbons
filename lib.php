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
 * Global library functions
 * @package    tool_ribbons
 * @copyright  2020 onwards Conn Warwicker
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

use tool_ribbons\ribbon;

defined('MOODLE_INTERNAL') || die();

/**
 * Callback to add HTML content to the top of the page body.
 * Here, we use it to add the markup for the ribbons.
 *
 * This function is implemented here and used from two locations:
 * - tool_ribbons_before_standard_top_of_body_html() in lib.php (for releases up to Moodle 4.3)
 * - tool_ribbons\hook_callbacks::before_standard_top_of_body_html() in classes/hook_callbacks.php (for Moodle 4.4+).
 *
 * @param \core\hook\output\before_standard_top_of_body_html_generation $hook If the hook is passed, then the hook
 *                                                                            implementation will be used. If not, then
 *                                                                            legacy implementation will be used.
 * @return string|void The legacy implementation returns a string, the hook implementation returns nothing.
 */
function tool_ribbons_before_standard_top_of_body_html_callback(&$hook = null) {
    $output = '';

    // Load the ribbons.
    $ribbons = ribbon::all(true);

    // Display them on the page.
    foreach ($ribbons as $ribbon) {
        $output .= $ribbon->display();
    }

    if ($hook) {
        // Pass the markup to the hook.
        $hook->add_html($output);
    } else {
        // Return the markup.
        return $output;
    }
}


/**
 * Callback to add elements to the page <head> html tag.
 * Here, we use it to add the CSS for the ribbons in a <style> to the page header.
 *
 * This function is implemented here and used from two locations:
 * - tool_ribbons_before_standard_html_head() in lib.php (for releases up to Moodle 4.3)
 * - tool_ribbons\hook_callbacks::before_standard_head_html_generation() in classes/hook_callbacks.php (for Moodle 4.4+).
 *
 * @param \core\hook\output\before_standard_head_html_generation $hook If the hook is passed, then the hook
 *                                                                            implementation will be used. If not, then
 *                                                                            legacy implementation will be used.
 * @return string|void The legacy implementation returns a string, the hook implementation returns nothing.
 */
function tool_ribbons_before_standard_html_head_callback(&$hook = null) {
    $output = '<style type="text/css">';

    // Load the ribbons.
    $ribbons = ribbon::all(true);

    // Display them on the page.
    foreach ($ribbons as $ribbon) {
        $output .= $ribbon->css();
    }

    $output .= '</style>';

    if ($hook) {
        // Pass the markup to the hook.
        $hook->add_html($output);
    } else {
        // Return the markup.
        return $output;
    }
}


/**
 * Legacy callback that adds the markup for the ribbons to the top of the page body.
 * @return string
 */
function tool_ribbons_before_standard_top_of_body_html() : string {
    return tool_ribbons_before_standard_top_of_body_html_callback();
}

/**
 * Legacy callback that adds CSS for the ribbons in a <style> tag to the page header.
 * @return string
 */
function tool_ribbons_before_standard_html_head() : string {
    return tool_ribbons_before_standard_html_head_callback();
}
