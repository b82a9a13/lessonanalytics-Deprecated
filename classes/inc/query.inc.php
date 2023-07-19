<?php
require_once(__DIR__.'/../../../../config.php');
use local_lessonanalytics\manager;
$manager = new manager();
if (
    isset($_POST['username']) ||
    isset($_POST['surname']) ||
    isset($_POST['firstname']) ||
    isset($_POST['email']) ||
    isset($_POST['city']) ||
    isset($_POST['company'])
    ){
        $username = $_POST['username'];
        $surname = $_POST['surname'];
        $firstname = $_POST['firstname'];
        $email = $_POST['email'];
        $city = $_POST['city'];
        $company = $_POST['company'];
        $error = false;
        $errors = [];
        if(empty($username) && empty($surname) && empty($firstname) && empty($email) && empty($city) && empty($company)){
            $error = true;
        } else{
            if(!preg_match("/^[a-zA-Z@. -]*$/", $username) && !empty($username)){
                $error = true;
                $errors['username'][0] = true;
                $errors['username'][1] = preg_replace("/[a-zA-Z@. -]/","",$username);
            }
            if(!preg_match("/^[a-zA-Z -]*$/", $surname) && !empty($surname)){
                $error = true;
                $errors['surname'][0] = true;
                $errors['surname'][1] = preg_replace("/[a-zA-Z -]/","",$surname);
            }
            if(!preg_match("/^[a-zA-Z -]*$/", $firstname) && !empty($firstname)){
                $error = true;
                $errors['firstname'][0] = true;
                $errors['firstname'][1] = preg_replace("/[a-zA-Z -]/","",$firstname);
            }
            if(!preg_match("/^[a-zA-Z -]*$/", $city) && !empty($city)){
                $error = true;
                $errors['city'][0] = true;
                $errors['city'][1] = preg_replace("/[a-zA-Z -]/","",$city);
            }
            if(!preg_match("/^[a-zA-Z - ()]*$/", $company) && !empty($company)){
                $error = true;
                $errors['company'][0] = true;
                $errors['company'][1] = preg_replace("/[a-zA-Z - ().,:;@]/","",$company);
            }
            if(!preg_match("/^[a-zA-Z0-9 - _ .]*$/", $email) && !empty($email)){
                $error = true;
                $errors['email'][0] = true;
            }
            if($error === true){
                print_r(json_encode($errors));
            } elseif($error === false){
                $output = $manager->query($username, $surname, $firstname, $email, $city, $company);
                print_r(json_encode($output));
            }
        }
    } else {
        //Empty Inputs
}
