<?php
require_once(__DIR__.'/../../../../config.php');
use local_lessonanalytics\manager;
$manager = new manager();
if(isset($_POST['total'])){
    $values = [];
    $errors = [];
    $total = $_POST['total'];
    $successInt = 0;
    $errorInt = 0;
    for($i = 1; $i <= $total; $i++){
        $error = false;
        $company = $_POST['company'.$i];
        $id = $_POST['id'.$i];
        if(!preg_match("/^[0-9]*$/", $id) && !empty($id)){
            $error = true;
        }
        if(!preg_match("/^[0-9A-Za-z - ()]*$/", $company) && !empty($company)){
            $error = true;
            $errors['errors']['company'][$errorInt] = [$id, true, preg_replace("/[0-9A-Za-z - ()]/","",$company)];
            $errorInt++;
        } else {
            $errors['success']['company'][$successInt] = [$id, true];
            $successInt++;
        }
        if($error == false){
            $manager->update_company($_POST['company'.$i], $_POST['id'.$i]);
        }
    }
    print_r(json_encode($errors));
}