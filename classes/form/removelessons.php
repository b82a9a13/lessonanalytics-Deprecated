<?php
/**
 * @package   local_lessonanalytics
 * @author    Robert Tyrone Cullen
 */

namespace local_lessonanalytics\form;
use moodleform;
use local_lessonanalytics\manager;

require_once("$CFG->libdir/formslib.php");

class removelessons extends moodleform {
    public function definition(){
        $mform = $this->_form;
        $manager = new manager();
        $records = $manager->get_tracked_courses();
        $recordgroup = [];
        // Goes through each record in the database
        foreach($records as $record){
            $recordgroup[] = $mform->createElement('advcheckbox', 'lessonid'.$record->activitynumber, $record->fullname);
        }
        // Creating group for form
        $mform->addGroup($recordgroup, '', get_string('choose_lessons_remove', 'local_lessonanalytics'), '<br>');
        // Add element to form
        $mform->addElement('advcheckbox', 'selectall', get_string('remove_all', 'local_lessonanalytics'), 'yes');
        $this->add_action_buttons();
    }
}