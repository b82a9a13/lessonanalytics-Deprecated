<?php
require_once(__DIR__.'/../../../../config.php');
use local_lessonanalytics\manager;
$manager = new manager();

if(isset($_POST['start']) && isset($_POST['end'])){
    $start = $_POST['start'];
    $end = $_POST['end'];
    if($start <> null){
        $start = new DateTime($start);
        $start = $start->format('U');
    }
    if($end <> null){
        $end = new DateTime($end);
        $end = $end->format('U');
    }
    $error = false;
    if(!preg_match("/^[0-9]*$/",$start) || empty($start)){
        $error = true;
    }
    if(!preg_match("/^[0-9]*$/", $end) || empty($end)){
        $error = true;
    }
    if($error == false){
        $newusers = $manager->newusers($start, $end);
        print_r(json_encode($newusers));
    }
}