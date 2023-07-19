<?php 
//Set template data
$archivedtemplate = (object)[
    'username' => get_string('username', 'local_lessonanalytics'),
    'search_al' => get_string('search_al', 'local_lessonanalytics'),
    'used_archive' => get_string('used_archive', 'local_lessonanalytics'),
    'lastname' => get_string('lastname', 'local_lessonanalytics'),
    'firstname' => get_string('firstname', 'local_lessonanalytics'),
    'email' => get_string('email', 'local_lessonanalytics'),
    'postcode' => get_string('postcode', 'local_lessonanalytics'),
    'dob' => get_string('dob', 'local_lessonanalytics'),
    'unique_ln' => get_string('unique_ln', 'local_lessonanalytics'),
    'learn_rn' => get_string('learn_rn', 'local_lessonanalytics'),
    'aim_sn' => get_string('aim_sn', 'local_lessonanalytics'),
    'learn_ar' => get_string('learn_ar', 'local_lessonanalytics'),
    'learn_at' => get_string('learn_at', 'local_lessonanalytics'),
    'standard_c' => get_string('standard_c', 'local_lessonanalytics'),
    'learn_sd' => get_string('learn_sd', 'local_lessonanalytics'),
    'learn_ped' => get_string('learn_ped', 'local_lessonanalytics'),
    'comp_stat' => get_string('comp_stat', 'local_lessonanalytics'),
    'search' => get_string('search', 'local_lessonanalytics')
];
//Session variable for csvfile 
if(isset($_SESSION['csvfile'])){
    if($_SESSION['csvfile'] == 'Data Added'){
        $archivedtemplate->csvcolor = 'green';
    } else {
        $archivedtemplate->csvcolor = 'red';
    }
    $archivedtemplate->csvresponse = $_SESSION['csvfile'];
    unset($_SESSION['csvfile']);
}
//Echo template form
echo $OUTPUT->render_from_template('local_lessonanalytics/archived', $archivedtemplate);
//Javascript for archive form
?>
<script src="./classes/js/archived-table.js"></script>
<style>
    #archivedform input{
        border: 1px solid black;
    }
</style>