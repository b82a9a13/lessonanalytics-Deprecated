<?php
/**
 * @package   local_lessonanalytics
 * @author    Robert Tyrone Cullen
 */

use local_lessonanalytics\form\addlessons;
use local_lessonanalytics\manager;

require_once(__DIR__ . '/../../config.php');

require_login();
$context = context_system::instance();
require_capability('local/lessonanalytics:lessonanalytics', $context);

$PAGE->set_url(new moodle_url('/local/lessonanalytics/addlessons.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('add_lessons', 'local_lessonanalytics'));
$PAGE->set_heading(get_string('add_lessons', 'local_lessonanalytics'));
//Used to include LHS navigation
$PAGE->set_pagelayout('admin');

$mform = new addlessons();
$manager = new manager();

if ($mform->is_cancelled()) {
    redirect($CFG->wwwroot . '/local/lessonanalytics/manage.php', get_string('add_lessons_cancelled', 'local_lessonanalytics'));
} else if ($fromform = $mform->get_data()){
    $records = $manager->get_all_courses();
    if ($fromform->selectall == true){
        foreach($records as $record){
            $manager->add_course($record->id);
        }
    } else {
        foreach($records as $record){
            $name = 'lessonid' . $record->id;
            if($fromform->$name == true){
                $manager->add_course($record->id);
            }
        }
        redirect($CFG->wwwroot . '/local/lessonanalytics/manage.php', get_string('add_lessons_success', 'local_lessonanalytics'));
    }
    redirect($CFG->wwwroot . '/local/lessonanalytics/manage.php', get_string('add_all_lessons_success', 'local_lessonanalytics'));
}

echo $OUTPUT->header();

$mform->display();

echo $OUTPUT->footer();