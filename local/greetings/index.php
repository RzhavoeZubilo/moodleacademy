<?php
/**
 * User: densh
 * Date: 01.07.2024
 * Time: 22:33
 */

/**
 * @package     local_greetings
 * @category    string
 * @copyright   2024 Denis <you@example.com>
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


echo $OUTPUT->header();

//$uname = "testing test";
//echo '<input type="text" name="uname" value="' . s($uname) . '">';
//echo format_string($uname);
//echo format_text($post->content, $post->contentformat, ['noclean' => true, 'context' => $context]);

//echo html_writer::tag('input', '', [
//    'type' => 'text',
//    'name' => 'username',
//    'placeholder' => get_string('typeyourname', 'local_greetings'),
//]);



//isloggedin(); определяет, вошел ли пользователь в систему.
//isguestuser(); Определяет, вошел ли пользователь в систему как реальный гостевой пользователь с именем пользователя guest.
//require_login(); Эта функция проверяет, вошел ли текущий пользователь в систему. Если они не вошли в систему, то он перенаправляет их на вход на сайт.
//fullname(); Эта функция возвращает полное имя человека.


if (isloggedin()) {
    local_greetings_get_greeting($USER);
} else {
    echo get_string('greetinguser', 'local_greetings');
}

$now = time();
echo userdate($now);
echo userdate($now, get_string('strftimedaydate', 'core_langconfig')); //без времени

echo $OUTPUT->footer();