<?php
/**
 * User: densh
 * Date: 01.07.2024
 * Time: 23:24
 */

function local_greetings_get_greeting($user)
{
    if ($user == null) {
        return get_string('greetinguser', 'local_greetings');
    }

    $country = $user->country;
    switch ($country) {
        case 'ES':
            $langstr = 'greetinguseres';
            break;
        default:
            $langstr = 'greetingloggedinuser';
            break;
    }

    return get_string($langstr, 'local_greetings', fullname($user));
}

/**
 * Insert a link to index.php on the site front page navigation menu.
 * Вставьте ссылку на index.php в меню навигации на главной странице сайта.
 * lib/navigationlib.php
 *
 * @param navigation_node $frontpage Node representing the front page in the navigation tree.
 */
function local_greetings_extend_navigation_frontpage(navigation_node $frontpage)
{
    if (!isguestuser()) {
        $frontpage->add(
            get_string('pluginname', 'local_greetings'),
            new moodle_url('/local/greetings/index.php'),
            navigation_node::TYPE_CUSTOM
        );
    }
}

/** добавляет пункт в меня в мудл версии ниже 4 */
function local_greetings_extend_navigation(global_navigation $root)
{
    $node = navigation_node::create(
        get_string('pluginname', 'local_greetings'),
        new moodle_url('/local/greetings/index.php')
    );

    $node->showinflatnavigation = true;
    $root->add_node($node);
}