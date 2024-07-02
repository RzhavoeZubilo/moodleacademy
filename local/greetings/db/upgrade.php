<?php

/**
 * Plugin upgrade script.
 *
 * @package     local_greetings
 * @copyright   2024 Denis mymoodle@mymoodle.com
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */


/**
 * Define upgrade steps to be performed to upgrade the plugin from the old version to the current one.
 *
 * @param int $oldversion Version number the plugin is being upgraded from.
 */
function xmldb_local_greetings_upgrade($oldversion)
{
    global $DB;
    $dbman = $DB->get_manager();

    if ($oldversion < 2024070200) {

        // Define field userid to be added to local_greetings_messages.
        $table = new xmldb_table('local_greetings_messages');
        $field = new xmldb_field('userid', XMLDB_TYPE_INTEGER, '10', null, XMLDB_NOTNULL, null, '1', 'timecreated');

        // Conditionally launch add field userid.
        if (!$dbman->field_exists($table, $field)) {
            $dbman->add_field($table, $field);
        }

        // Define key greetings-user-foreigen-key (foreign) to be added to local_greetings_messages.
        $table = new xmldb_table('local_greetings_messages');
        $key = new xmldb_key('greetings-user-foreigen-key', XMLDB_KEY_FOREIGN, ['userid'], 'user', ['id']);

        // Launch add key greetings-user-foreigen-key.
        $dbman->add_key($table, $key);

        // Greetings savepoint reached.
        upgrade_plugin_savepoint(true, 2024070200, 'local', 'greetings');
    }

    return true;
}