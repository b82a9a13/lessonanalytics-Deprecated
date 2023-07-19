<?php
require_once(__DIR__.'/../../../../config.php');
use local_lessonanalytics\manager;
$manager = new manager();

//This file is used to check the inputs provided by the user
if(
    isset($_POST['fusername']) || 
    isset($_POST['flastname']) || 
    isset($_POST['ffirstname']) ||
    isset($_POST['femail']) || 
    isset($_POST['postcode']) ||
    isset($_POST['dob']) || 
    isset($_POST['ulnumber']) ||
    isset($_POST['lrnumber']) || 
    isset($_POST['asnumber']) ||
    isset($_POST['lareference']) ||
    isset($_POST['latitle']) ||
    isset($_POST['standardcode']) ||
    isset($_POST['lstartdate']) ||
    isset($_POST['lpenddate']) ||
    isset($_POST['compstatus']) ||
    isset($_POST['lstartdateto']) ||
    isset($_POST['lpenddateto'])
    ){
    $errors = [];
    $username = $_POST['fusername'];
    if(!preg_match("/^[a-zA-Z0-9@._ -]*$/", $username) && isset($_POST['fusername'])){
        $errors['username'][0] = true;
        $errors['username'][1] = preg_replace("/[a-z A-Z 0-9@._ -]/","", $username);
    }
    $lastname = $_POST['flastname'];
    if(!preg_match("/^[a-z A-Z -]*$/", $lastname) && isset($_POST['flastname'])){
        $errors['lastname'][0] = true;
        $errors['lastname'][1] = preg_replace("/[a-zA-Z -]/","",$lastname);
    }
    $firstname = $_POST['ffirstname'];
    if(!preg_match("/^[a-z A-Z -]*$/", $firstname) && isset($_POST['ffirstname'])){
        $errors['firstname'][0] = true;
        $errors['firstname'][1] = preg_replace("/[a-zA-Z -]/","",$firstname);
    }
    $email = $_POST['femail'];
    if(!preg_match("/^[a-zA-Z0-9@._ -]*$/", $email) && isset($_POST['femail'])){
        $errors['email'][0] = true;
        $errors['email'][1] = preg_replace("/[a-zA-Z0-9@._ -]/","",$email);
    }
    $postcode = $_POST['postcode'];
    if(!preg_match("/^[a-zA-Z 0-9]*$/", $postcode) && isset($_POST['postcode'])){
        $errors['postcode'][0] = true;
        $errors['postcode'][1] = preg_replace("/[a-zA-Z 0-9]/","",$postcode);
    }
    $dob = $_POST['dob'];
    if(isset($_POST['dob']) && !empty($_POST['dob'])){
        $dob = new DateTime($dob);
        $dob = $dob->format('U');
        if(!preg_match("/^[0-9]*$/", $dob)){
            $errors['dob'] = true;
        }
    }
    $ulnumber = $_POST['ulnumber'];
    if(!preg_match("/^[0-9]*$/", $ulnumber) && isset($_POST['ulnumber'])){
        $errors['ulnumber'][0] = true;
        $errors['ulnumber'][1] = preg_replace("/[0-9]/","",$ulnumber);
    }
    $lrnumber = $_POST['lrnumber'];
    if(!preg_match("/^[0-9]*$/", $lrnumber) && isset($_POST['lrnumber'])){
        $errors['lrnumber'][0] = true;
        $errors['lrnumber'][1] = preg_replace("/[0-9]/","",$lrnumber);
    }
    $asnumber = $_POST['asnumber'];
    if(!preg_match("/^[0-9]*$/", $asnumber) && isset($_POST['asnumber'])){
        $errors['asnumber'][0] = true;
        $errors['asnumber'][1] = preg_replace("/[0-9]/","",$asnumber);
    }
    $lareference = $_POST['lareference'];
    if(!preg_match("/^[0-9A-Za-z]*$/", $lareference) && isset($_POST['lareference'])){
        $errors['lareference'][0] = true;
        $errors['lareference'][1] = preg_replace("/[0-9A-Za-z]/","",$lareference);
    }
    $latitle = $_POST['latitle'];
    if(!preg_match("/^[0-9a-z A-Z,()]*$/", $latitle) && isset($_POST['latitle'])){
        $errors['latitle'][0] = true;
        $errors['latitle'][1] = preg_replace("/[0-9a-z A-Z,()]/","",$latitle);
    }
    $standardcode = $_POST['standardcode'];
    if(!preg_match("/^[0-9]*$/", $standardcode) && isset($_POST['standardcode'])){
        $errors['standardcode'][0] = true;
        $errors['standardcode'][1] = preg_replace("/[0-9]/","",$standardcode);
    }
    $lstartdate = $_POST['lstartdate'];
    if(isset($_POST['lstartdate']) && !empty($_POST['lstartdate'])){
        $lstartdate = new DateTime($lstartdate);
        $lstartdate = $lstartdate->format('U');
        if(!preg_match("/^[0-9]*$/", $lstartdate)){
            $errors['lstartdate'] = true;
        }
    }
    $lpenddate = $_POST['lpenddate'];
    if(isset($_POST['lpenddate']) && !empty($_POST['lpenddate'])){
        $lpenddate = new DateTime($lpenddate);
        $lpenddate = $lpenddate->format('U');
        if(!preg_match("/^[0-9]*$/", $lpenddate)){
            $errors['lpenddate'] = true;
        }
    }

    $compstatus = $_POST['compstatus'];
    if(!preg_match("/^[0-9]*$/", $compstatus) && isset($_POST['compstatus'])){
        $errors['compstatus'][0] = true;
        $errors['compstatus'][1] = preg_replace("/[0-9]/","",$compstatus);
    }

    $lstartdateto = $_POST['lstartdateto'];
    if(isset($_POST['lstartdateto']) && !empty($_POST['lstartdateto'])){
        $lstartdateto = new DateTime($lstartdateto);
        $lstartdateto = $lstartdateto->format('U');
        if(!preg_match("/^[0-9]*$/", $lstartdateto)){
            $errors['lstartdateto'] = true;
        }
    }
    $lpenddateto = $_POST['lpenddateto'];
    if(isset($_POST['lpenddateto']) && !empty($_POST['lpenddateto'])){
        $lpenddateto = new DateTime($lpenddateto);
        $lpenddateto = $lpenddateto->format('U');
        if(!preg_match("/^[0-9]*$/", $lpenddateto)){
            $errors['lpenddateto'] = true;
        }
    }

    if(in_array(true, $errors)){
        print_r(json_encode($errors));
    } else {
        $array = [
            $username,
            $lastname,
            $firstname,
            $email,
            $postcode,
            $dob,
            $ulnumber,
            $lrnumber,
            $asnumber,
            $lareference,
            $latitle,
            $lstartdate,
            $lpenddate,
            $compstatus,
            $standardcode,
            $lstartdateto,
            $lpenddateto
        ];
        $empty = true;
        $int = 0;
        foreach($array as $arr){
            if(!empty($arr)){
                $empty = false;
            }
            $int++;
        }
        if($empty == false){
            print_r(json_encode($manager->selectarchivedusers($array)));
        } elseif($empty == true){
            print_r(json_encode($manager->allarchivedusers(false)));
        }
    }

}
