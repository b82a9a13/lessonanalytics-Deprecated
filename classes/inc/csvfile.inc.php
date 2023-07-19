<?php
require_once(__DIR__.'/../../../../config.php');
use local_lessonanalytics\manager;
$manager = new manager();

if(isset($_POST['submit'])){
    if(!empty($_FILES['csvfile']['name'])){
        $filename = $_FILES['csvfile']['name'];
        $filetmpname = $_FILES['csvfile']['tmp_name'];
        $filesize = $_FILES['csvfile']['size'];
        $fileerror = $_FILES['csvfile']['error'];
        $filetype = $_FILES['csvfile']['type'];
        $fileext = explode('.', $filename);
        $fileactualext = strtolower(end($fileext));
        $allowed = array('csv');
        if(in_array($fileactualext, $allowed)){
            if($fileerror === 0){
                $values = preg_split("/[\s,]+/", file_get_contents($_FILES['csvfile']['tmp_name']));
                //print_r($values);
                //15 values per row
                $arrayvalues = [];
                $length = 15;
                $i = 0;
                $y = 0;
                foreach($values as $value){
                    if($i < 15){
                        $arrayvalues[$y][$i] = $value;
                        $i++;
                    } else {
                        $i = 0;
                        $y++;
                        $arrayvalues[$y][$i] = $value;
                        $i++;
                    }
                }
                //Check if the headers of the csv is correct
                if(
                    $arrayvalues[0][0] == '﻿profile_field_LearnerReferenceNumber' &&
                    $arrayvalues[0][1] == 'username' &&
                    $arrayvalues[0][2] == 'profile_field_UniqueLearnerNumber' && 
                    $arrayvalues[0][3] == 'lastname' &&
                    $arrayvalues[0][4] == 'firstname' &&
                    $arrayvalues[0][5] == 'profile_field_DateOfBirth' &&
                    $arrayvalues[0][6] == 'profile_field_Postcode' &&
                    $arrayvalues[0][7] == 'profile_field_AimSequenceNumber' &&
                    $arrayvalues[0][8] == 'profile_field_LearningAimReference' &&
                    $arrayvalues[0][9] == 'profile_field_LearningAimTitle' &&
                    $arrayvalues[0][10] == 'profile_field_StandardCode' &&
                    $arrayvalues[0][11] == 'profile_field_LearningStartDate' &&
                    $arrayvalues[0][12] == 'profile_field_LearningPlannedEndDate' &&
                    $arrayvalues[0][13] == 'profile_field_CompletionStatus' &&
                    $arrayvalues[0][14] == 'suspended'
                ){
                    $int = 1;
                    $uservalues = [];
                    $error = false;
                    while($int < count($arrayvalues)-1){
                        //[username, aimSequenceNumber, learningAimReference, learningAimTitle, learningStartDate, learningPlannedEndDate, completionStatus]
                        $title = str_replace('.',',',$arrayvalues[$int][9]);
                        $title = str_replace('-',' ', $title);
                        if(!preg_match("/^[0-9]*$/", $arrayvalues[$int][2])){ $error = true; }
                        if(!preg_match("/^[0-9]*$/", $arrayvalues[$int][7])){ $error = true; }
                        if(!preg_match("/^[0-9A-Z]*$/", $arrayvalues[$int][8])){ $error = true; }
                        if(!preg_match("/^[a-z A-Z,0-9()]*$/", $title)){ $error = true; }
                        if(!preg_match("/^[0-9]*$/", strtotime($arrayvalues[$int][11]))){ $error = true; }
                        if(!preg_match("/^[0-9]*$/", strtotime($arrayvalues[$int][12]))){ $error = true; }
                        if(!preg_match("/^[0-9]*$/", $arrayvalues[$int][13])){ $error = true; }
                        if(!preg_match("/^[0-9]*$/", $arrayvalues[$int][10])){ $error = true; }
                        array_push($uservalues, [$arrayvalues[$int][1], $arrayvalues[$int][7], $arrayvalues[$int][8], $title, strtotime($arrayvalues[$int][11]), strtotime($arrayvalues[$int][12]), $arrayvalues[$int][13], $arrayvalues[$int][10]]);
                        $int++;
                    }
                    if($error == true){
                        $_SESSION['csvfile'] = 'Invalid Data In CSV';
                    } else {
                        $manager->addarchivedata($uservalues);
                        $_SESSION['csvfile'] = 'Data Added';
                    }
                } else {
                    $_SESSION['csvfile'] = 'Invalid CSV Headers';
                }
            } else {
                $_SESSION['csvfile'] = 'File Error';
            }
        } else {
            $_SESSION['csvfile'] = 'File Type Not Allowed';
        }
    } else {
        $_SESSION['csvfile'] = 'No File Uploaded';
    }
    header("Location: ./../../archived.php");
}