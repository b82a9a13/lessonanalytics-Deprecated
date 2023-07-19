<?php
// This file is part of Moodle Lesson Analytics Plugin
/**
 * @package     local_lessonanalytics
 * @author      Robert Tyrone Cullen
 * @var stdClass $plugin
 */

namespace local_lessonanalytics\event;

use core\event\base;

defined('MOODLE_INTERNAL') || die();

class searched_all_archived extends base {
    protected function init(){
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_OTHER;
    }
    public static function get_name(){
        return 'All archived users search';
    }
    public function get_description(){
        return "The user with id '".$this->userid."' searched for all archived users.";
    }
    public function get_url(){
        return new \moodle_url('/local/lessonanalytics/archived.php');
    }
    public function get_id(){
        return $this->objectid;
    }
}