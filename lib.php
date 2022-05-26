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
 * Generate HTML to display the ribbons.
 * @return string
 */
function tool_ribbons_before_standard_top_of_body_html() : string {

    $output = '';

    // Load the ribbons.
    $ribbons = ribbon::all(true);

    // Display them on the page.
    foreach ($ribbons as $ribbon) {
        $output .= $ribbon->display();
    }

    return $output;

}

/**
 * Generate CSS to add to the page to style the ribbons.
 * @return string
 */
function tool_ribbons_before_standard_html_head() : string {

    $output = '<style type="text/css">';

    // Load the ribbons.
    $ribbons = ribbon::all(true);

    // Display them on the page.
    foreach ($ribbons as $ribbon) {
        $output .= $ribbon->css();
    }

    $output .= '</style>';

    return $output;

}
