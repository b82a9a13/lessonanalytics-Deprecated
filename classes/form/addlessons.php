<?php
/**
 * @package   local_lessonanalytics
 * @author    Robert Tyrone Cullen
 * @var stdClass $plugin
 */
namespace local_lessonanalytics\form;
use moodleform;
use local_lessonanalytics\manager;

require_once("$CFG->libdir/formslib.php");

class addlessons extends moodleform {
    public function definition(){
        global $CFG;
        $mform = $this->_form;
        
        $manager = new manager();
        $records = $manager->get_all_courses();
        $recordgroup = [];
        foreach($records as $record){
            if($record->id <> 1){
                $recordgroup[] = $mform->createElement('advcheckbox', 'lessonid'.$record->id, $record->fullname);
            }
        }
        $mform->addGroup($recordgroup, '', get_string('choose_lesson', 'local_lessonanalytics'), '<br>');
        
        $mform->addElement('advcheckbox', 'selectall', get_string('select_all', 'local_lessonanalytics'), 'yes');

        $this->add_action_buttons();
    }
    function validation($data, $files){
        return array();
    }
}