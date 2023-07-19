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

class csv_downloaded_archived extends base {
    protected function init(){
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_OTHER;
    }
    public static function get_name(){
        return 'Archived users downloaded as csv';
    }
    public function get_description(){
        return "The user with id '".$this->userid."' downloaded archived users as a csv";
    }
    public function get_url(){
        return new \moodle_url('/local/lessonanalytics/archived.php');
    }
    public function get_id(){
        return $this->objectid;
    }
}