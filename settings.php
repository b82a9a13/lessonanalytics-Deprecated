<?php
/**
* Adds Admin settings for the plugin
* @package     local_lessonanalytics
* @author      Robert Tyrone Cullen
*/

defined('MOODLE_INTERNAL') || die();

if($hassiteconfig){
    //Adds new category to local_plugins
    $ADMIN->add('localplugins', new admin_category('local_lessonanalytics', get_string('pluginname', 'local_lessonanalytics')));

    //Adds a hyperlink to the manage page
    $ADMIN->add('local_lessonanalytics', new admin_externalpage('local_lessonanalytic', get_string('manage', 'local_lessonanalytics'), $CFG->wwwroot . '/local/lessonanalytics/manage.php'));

    //Adds a hyperlink to the search for learner page
    $ADMIN->add('local_lessonanalytics', new admin_externalpage('local_lessonanalytics', 'Search for Learner', $CFG->wwwroot.'/local/lessonanalytics/learners.php'));

    //Adds a hyperlink to the search archived learners page
    $ADMIN->add('local_lessonanalytics', new admin_externalpage('local_lessonanalytics', 'Search Archived Learners', $CFG->wwwroot.'/local/lessonanalytics/archived.php'));

}
