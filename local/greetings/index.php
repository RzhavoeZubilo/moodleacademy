<?php
// This file is part of Moodle - https://moodle.org/
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
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

/**
 *  Main file to view greetings
 *
 * @package     local_greetings
 * @copyright   2024 Denis mymoodle@mymoodle.com
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

global $PAGE, $OUTPUT, $USER, $CFG;

require_once ('../../config.php');

require_once($CFG->dirroot. '/local/greetings/lib.php');

$context = context_system::instance();
$PAGE->set_context($context);
$PAGE->set_url(new moodle_url('/local/greetings/index.php'));
$PAGE->set_pagelayout('standard');

//Заголовок страницы (имя вкладки)
$PAGE->set_title(get_string('pluginname', 'local_greetings'));
//основной заголовок на самой странице
$PAGE->set_heading(get_string('pluginname', 'local_greetings'));



$coursenode = $PAGE->navigation->find(1, navigation_node::TYPE_COURSE);
$thingnode = $coursenode->add(
    get_string('thingname'),
    new moodle_url('/a/link/if/you/want/one.php')
);
$thingnode->make_active();


echo $OUTPUT->header();

//$uname = "testing test";
//echo '<input type="text" name="uname" value="' . s($uname) . '">';
//echo format_string($uname);
//echo format_text($post->content, $post->contentformat, ['noclean' => true, 'context' => $context]);

//isloggedin(); определяет, вошел ли пользователь в систему.
//isguestuser(); Определяет, вошел ли пользователь в систему как реальный гостевой пользователь с именем пользователя guest.
//require_login(); Эта функция проверяет, вошел ли текущий пользователь в систему. Если они не вошли в систему, то он перенаправляет их на вход на сайт.
//fullname(); Эта функция возвращает полное имя человека.
//$now = time();
//echo userdate($now);
//echo userdate($now, get_string('strftimedaydate', 'core_langconfig')); //без времени


if (isloggedin()) {
    echo  local_greetings_get_greeting($USER);
} else {
    echo get_string('greetinguser', 'local_greetings');
}

$messageform = new \local_greetings\form\message_form();
$messageform->display();

if ($data = $messageform->get_data()) {
    $message = required_param('message', PARAM_TEXT);

    echo $OUTPUT->heading($message, 4);
}

echo $OUTPUT->footer();