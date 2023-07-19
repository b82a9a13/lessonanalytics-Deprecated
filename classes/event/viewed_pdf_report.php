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

class viewed_pdf_report extends base {
    protected function init(){
        $this->data['crud'] = 'r';
        $this->data['edulevel'] = self::LEVEL_OTHER;
    }
    public static function get_name(){
        return 'PDF report viewed';
    }
    public function get_description(){
        return "The user with id '".$this->userid."' view the pdf report.";
    }
    public function get_url(){
        return new \moodle_url('/local/lessonanalytics/pdf.php');
    }
    public function get_id(){
        return $this->objectid;
    }
}