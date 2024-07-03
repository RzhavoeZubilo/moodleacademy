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

global $PAGE, $OUTPUT, $USER, $CFG, $DB;

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

require_login();

if (isguestuser()) {
    throw new moodle_exception('noguest');
}

$allowpost = has_capability('local/greetings:postmessages', $context); //добавлять сообщения
$deleteanypost = has_capability('local/greetings:deleteanymessage', $context); //удалять все сообщения
$deletepost = has_capability('local/greetings:deleteownmessage', $context); // удалять только свои сообщения

$action = optional_param('action', '', PARAM_TEXT);

if ($action === 'del') {
    $id = required_param('id', PARAM_TEXT);

    if ($deleteanypost || $deletepost) {
        require_sesskey();
        $params = array('id' => $id);
        // Пользователи без разрешения должны удалять только свои собственные сообщения.
        if (!$deleteanypost) {
            $params += ['userid' => $USER->id];
        }
        $DB->delete_records('local_greetings_messages', $params);

        redirect($PAGE->url);
    }
}

$messageform = new \local_greetings\form\message_form();

if ($data = $messageform->get_data()) {
    $message = required_param('message', PARAM_TEXT);
    //$message = required_param('message', PARAM_RAW);

    if (!empty($message)) {
        $record = new stdClass;
        $record->message = $message;
        $record->timecreated = time();
        $record->userid = $USER->id;

        $DB->insert_record('local_greetings_messages', $record);

        redirect($PAGE->url);
    }
}

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

if ($allowpost) {
    $messageform->display();
}

// получаем ФИО пользователя
$userfields = \core_user\fields::for_name()->with_identity($context);
$userfieldssql = $userfields->get_sql('u');

$sql = "SELECT m.id, m.message, m.timecreated, m.userid {$userfieldssql->selects}
        FROM {local_greetings_messages} m
        LEFT JOIN {user} u ON u.id = m.userid
        ORDER BY timecreated DESC";

$messages = $DB->get_records_sql($sql);

echo $OUTPUT->box_start('card-columns');
$cardbackgroundcolor = get_config('local_greetings', 'messagecardbgcolor');

foreach ($messages as $m) {
    echo html_writer::start_tag('div', ['class' => 'card', 'style' => "background: $cardbackgroundcolor"]);
    echo html_writer::start_tag('div', array('class' => 'card-body'));
    //echo html_writer::tag('p', $m->message, array('class' => 'card-text'));
    echo html_writer::tag('p', format_text($m->message, FORMAT_PLAIN), array('class' => 'card-text'));
    echo html_writer::tag('p', get_string('postedby', 'local_greetings', $m->firstname), array('class' => 'card-text'));

    echo html_writer::start_tag('p', array('class' => 'card-text'));
    echo html_writer::tag('small', userdate($m->timecreated), array('class' => 'text-muted'));
    echo html_writer::end_tag('p');

    if ($deleteanypost || ($deletepost && $m->userid === $USER->id)) {
        echo html_writer::start_tag('p', array('class' => 'footer text-center'));

        echo html_writer::link(
            new moodle_url(
                '/local/greetings/edit.php',
                array('action' => 'edit', 'id' => $m->id)
            ),
            $OUTPUT->pix_icon('i/edit', get_string('edit')), ['role' => 'button']
        );

        echo html_writer::link(
            new moodle_url(
                '/local/greetings/index.php',
                array('action' => 'del', 'id' => $m->id, 'sesskey' => sesskey())
            ),
            $OUTPUT->pix_icon('t/delete', get_string('delete')), ['role' => 'button']
        );
        echo html_writer::end_tag('p');
    }
    echo html_writer::end_tag('div');
    echo html_writer::end_tag('div');
}

echo $OUTPUT->box_end();



echo $OUTPUT->footer();