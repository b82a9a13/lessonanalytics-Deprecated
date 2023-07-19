<?php
/**
 * @package   local_lessonanalytics
 * @author    Robert Tyrone Cullen
 */
use local_lessonanalytics\form\removelessons;
use local_lessonanalytics\manager;

require_once(__DIR__. '/../../config.php');

require_login();
$context = context_system::instance();
require_capability('local/lessonanalytics:lessonanalytics', $context);

// Setting page properties
$PAGE->set_url(new moodle_url('/local/lessonanalytics/removelessons.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('remove_lessons', 'local_lessonanalytics'));
$PAGE->set_heading(get_string('remove_lessons', 'local_lessonanalytics'));
//Used to include LHS navigation
$PAGE->set_pagelayout('admin');

$mform = new removelessons();
$manager = new manager();
// if cancelled redirect to manage page
if($mform->is_cancelled()){
    redirect($CFG->wwwroot . '/local/lessonanalytics/manage.php', get_string('remove_lessons_cancelled', 'local_lessonanalytics'));
} else if ($fromform = $mform->get_data()){
    // if select all is true add all lessons to database
    if ($fromform->selectall == true){
        $manager->remove_all_tracked('course_tracked');
        redirect($CFG->wwwroot . '/local/lessonanalytics/manage.php', get_string('remove_all_lessons_success', 'local_lessonanalytics'));
    } else {
        // else use indiviually selected lessons to add to database
        $records = $manager->get_tracked_courses();
        foreach($records as $record){
            $name = 'lessonid'.$record->activitynumber;
            if($fromform->$name == true){
                $manager->remove_tracked_course($record->activitynumber);
            }
        }
        redirect($CFG->wwwroot . '/local/lessonanalytics/manage.php', get_string('remove_selected_lessons', 'local_lessonanalytics'));
    }   
}

echo $OUTPUT->header();

$mform->display();

echo $OUTPUT->footer();