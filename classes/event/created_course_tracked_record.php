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

class created_course_tracked_record extends base {
    protected function init(){
        $this->data['crud'] = 'c';
        $this->data['edulevel'] = self::LEVEL_OTHER;
        $this->data['objecttable'] = 'course_tracked';
    }
    public static function get_name(){
        return 'New record created in the course_tracked table';
    }
    public function get_description(){
        return "A record for the user with id '".$this->relateduserid."' has been created by user with id '".$this->userid."'.";
    }
    public function get_url(){
        return new \moodle_url('/local/lessonanalytics/manage.php');
    }
    public function get_id(){
        return $this->objectid;
    }
}