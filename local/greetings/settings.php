<?php
defined('MOODLE_INTERNAL') || die;


if ($hassiteconfig) {
    $settings = new admin_settingpage('local_greetings', get_string('pluginname', 'local_greetings'));
    $ADMIN->add('localplugins', $settings);

    if ($ADMIN->fulltree) {
        require_once($CFG->dirroot . '/local/greetings/lib.php');

        $settings->add(new admin_setting_configtext(
            'local_greetings/messagecardbgcolor',
            get_string('messagecardbgcolor', 'local_greetings'),
            get_string('messagecardbgcolordesc', 'local_greetings'),
            '#FFFFFF',
        ));
    }
}