<?php
require_once(__DIR__.'/../../../../config.php');
if(isset($_POST['downloaded'])){
    $downloaded = $_POST['downloaded'];
    if($downloaded == 'true'){
        \local_lessonanalytics\event\csv_downloaded_archived::create(array('contextid' => 1, 'contextlevel' => 10))->trigger();
    }
}