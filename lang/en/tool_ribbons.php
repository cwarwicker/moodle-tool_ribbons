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
 * Language strings
 * @package    tool_ribbons
 * @copyright  2020 onwards Conn Warwicker
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

// Capabilities.
$string['ribbons:config_ribbons'] = 'Configure environment ribbons';

// Errors.
$string['error:colour'] = 'Invalid hex colour';
$string['error:link'] = 'Invalid URL';
$string['error:maxlength'] = 'Data cannot be more than {$a} characters long.';
$string['error:required'] = 'This field is required';
$string['error:ribbon:position'] = 'Invalid position';
$string['error:ribbon:position:used'] = 'This position already has a ribbon associated with it';
$string['error:ribbon:type'] = 'Invalid type';

// Standard strings.
$string['data'] = 'Data';
$string['delete:sure'] = 'Are you sure you want to delete this <b>{$a}</b>';
$string['disable'] = 'Disable';
$string['enable'] = 'Enable';
$string['nodata'] = 'No data';
$string['pluginname'] = 'Environment ribbons';
$string['ribbon'] = 'Environment ribbon';
$string['ribbons'] = 'Environment ribbons';
$string['ribbon:colour:bg'] = 'Background colour';
$string['ribbon:colour:bg_help'] = 'This must be a hex code. E.g. #000000';
$string['ribbon:colour:text'] = 'Text Colour';
$string['ribbon:colour:text_help'] = 'This must be a hex code. E.g. #fff';
$string['ribbon:deleted'] = 'Environment ribbon deleted';
$string['ribbon:link'] = 'Hyperlink';
$string['ribbon:new'] = 'New environment ribbon';
$string['ribbon:position'] = 'Position';
$string['ribbon:saved'] = 'Environment ribbon saved';
$string['ribbon:type:script_help'] = 'This URL will be opened and the text response passed into the ribbon. it is recommended that the script should not return more than 20 characters, or the ribbon will become difficult to read.<br><br><b>Available Placeholders:</b><br>{www} - This will be converted to the root url of your site.<br>{userid} - This will be converted to the ID of the currently logged in user.';
$string['ribbon:type:script'] = 'Script URL';
$string['ribbon:type:static'] = 'Static text';
$string['type'] = 'Type';

// Positions.
$string['left-top'] = 'left-top';
$string['right-top'] = 'right-top';
$string['left-bottom'] = 'left-bottom';
$string['right-bottom'] = 'right-bottom';

// Types.
$string['static'] = 'static';
$string['script'] = 'script';

// Privacy.
$string['privacy:metadata'] = 'The Environment ribbons plugin does not store any personal data.';
