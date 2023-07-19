<?php
/**
 * @package   local_lessonanalytics
 * @author    Robert Tyrone Cullen
 * @var stdClass $plugin
 */
namespace local_lessonanalytics;

use dml_exception;
use stdClass;

class manager{
    // Used to get all records from course database
    public function get_all_courses(): array{
        global $DB;
        $records = $DB->get_records('course');
        $array = [];
        foreach($records as $record){
            array_push($array, [$record->fullname, $record->id]);
        }
        asort($array);
        $finalarray = [];
        $int = 0;
        foreach($array as $arr){
            $finalarray[$int] = $records[$arr[1]];
            $int++;
        }
        return $finalarray;
    }

    // Adds course to course_tracked database if it is not already included
    public function add_course($courseid): bool{
        global $DB;
        global $USER;
        $course = $DB->get_record('course', [$DB->sql_compare_text('id') => $courseid]);
        $record_to_insert = new stdClass();
        $record_to_insert->shortname = $course->shortname;
        $record_to_insert->activitynumber = $course->id;
        $record_to_insert->fullname = $course->fullname;
        $record_to_insert->userid = $USER->id;
        if(!$DB->record_exists('course_tracked', [$DB->sql_compare_text('activitynumber') => $record_to_insert->activitynumber, $DB->sql_compare_text('userid') => $record_to_insert->userid]) && $record_to_insert->activitynumber <> 1){
            try {
                $DB->insert_record('course_tracked', $record_to_insert, false);
                //Used to add a log by triggering the created_course_tracked_record event
                \local_lessonanalytics\event\created_course_tracked_record::create(array('contextid' => 1, 'contextlevel' => 10, 'relateduserid'=>$USER->id))->trigger();
                return true;
            } catch (dml_exception $e) {
                return false;
            }
        } else {
            return false;
        }
    }

    // Get all tracked courses
    public function get_tracked_courses(): array{
        global $DB;
        global $USER;
        return $DB->get_records('course_tracked', [$DB->sql_compare_text('userid') => $USER->id]);
    }

    // Remove tracked course
    public function remove_tracked_course($activitynumber){
        global $DB;
        global $USER;
        if($DB->record_exists('course_tracked', [$DB->sql_compare_text('activitynumber') => $activitynumber, $DB->sql_compare_text('userid') => $USER->id])){
            try{
                $DB->delete_records('course_tracked', [$DB->sql_compare_text('activitynumber') => $activitynumber, $DB->sql_compare_text('userid') => $USER->id]);
                //used to add a log by triggering the remove_course_tracked_record event
                \local_lessonanalytics\event\remove_course_tracked_record::create(array('contextid' => 1, 'contextlevel' => 10, 'relateduserid' => $USER->id))->trigger();
                return;
            } catch (dml_exception $e) {
                return $e;
            }
        } else {
            return;
        }
    }

    // Remove all records tracked course DB
    public function remove_all_tracked(){
        global $DB;
        global $USER;
        if($DB->record_exists('course_tracked', [$DB->sql_compare_text('userid') => $USER->id])){
            $DB->delete_records('course_tracked', [$DB->sql_compare_text('userid') => $USER->id]);
            //used to add a log by triggering the remove_course_tracked_records event
            \local_lessonanalytics\event\remove_course_tracked_records::create(array('contextid' => 1, 'contextlevel' => 10, 'relateduserid' => $USER->id))->trigger();
        }
        return;
    }

    //Used for Updating the Company section
    public function query($username, $surname, $firstname, $email, $city, $company): array{
        global $DB;
        $query = '';
        $array = [
            array('username', $username), 
            array('lastname' ,$surname), 
            array('firstname' ,$firstname), 
            array('email', $email), 
            array('city', $city), 
            array('institution', $company)
        ];
        $userdetails = [];
        $pos = 0;
        $intt = 0;
        for($int = 0; $int < 6; $int++){
            if(!empty($array[$int][1])){
                $userdetails[$intt] = "%".strtolower($array[$int][1])."%";
                $intt++;
                if($pos == 0){
                    $query .= ' WHERE lower('.$array[$int][0].') LIKE ? ';
                    $pos++;
                } elseif ($pos > 0){
                    $query .= 'AND lower('.$array[$int][0].') LIKE ? ';
                    $pos++;
                }
            }
        }
        $users = $DB->get_records_sql('SELECT username, lastname, firstname, email, city, institution, id, deleted FROM {user}'.$query, $userdetails);
        $results = [];
        $number = 1;
        foreach($users as $user){
            if($user->deleted == 0){
                $results[$number-1] = array(
                    array('#', $number),
                    array('Username', $user->username),
                    array('Lastname', $user->lastname),
                    array('Firstname', $user->firstname),
                    array('Email', $user->email),
                    array('city', $user->city),
                    array('Company', $user->institution),
                    array('id', $user->id)
                );
                $number++;
            }
        }
        return $results;
    }

    //Used to update the comapny field of a record
    public function update_company($company, $id){
        global $DB;
        $obj = new stdClass();
        $obj->id = $id;
        $obj->institution = $company;
        $DB->update_record('user', $obj);
        //Adds log to reports logs - Log states user profile has been updated
        \core\event\user_updated::create_from_userid($id)->trigger();
    }

    //Get users for a re-attempt at inputting company name
    public function get_user_company($id): array{
        global $DB;
        $users = $DB->get_record('user', ['id'=>$id]);
        $array = [$users->username, $users->lastname, $users->firstname, $users->email, $users->city, $users->institution, $users->id];
        return $array;
    }

    //Get all enrolled users (STUDENTS ONLY!)
    public function user_course(): array{
        global $DB;

        $courses = $DB->get_records('course');
        $context = $DB->get_records('context');
        //(Course id)
        $ccarray = [];
        foreach($courses as $course){
            foreach($context as $cont){
                if($course->id == $cont->instanceid){
                    array_push($ccarray, [$cont->id, $course->id]);
                }
            }
        }

        //(user id, courseid)
        $rarray = [];
        $roleassigns = $DB->get_records('role_assignments');
        foreach($roleassigns as $roleassign){
            foreach($ccarray as $carray){
                if($roleassign->contextid == $carray[0] && $roleassign->roleid == 5){
                    array_push($rarray, [$roleassign->userid, $carray[1]]);
                }
            }
        }
        //(userid, courseid) Get all users who arent suspended
        $resulting = [];
        $enrols = $DB->get_records('enrol');
        $userenrolments = $DB->get_records('user_enrolments');
        foreach($enrols as $enr){
            foreach($userenrolments as $useren){
                if($useren->enrolid == $enr->id && $useren->status <> 1){
                    array_push($resulting, [$useren->userid, $enr->courseid]);
                }
            }
        }

        //Don't included suspended users in array
        $endresult1 = [];
        foreach($rarray as $res){
            foreach($resulting as $resu){
                if($res == $resu){
                    array_push($endresult1, $resu[1]);
                }
            }
        }

        //(course full name)
        global $USER;
        $courses = $DB->get_records('course_tracked', [$DB->sql_compare_text('userid') => $USER->id]);
        $result = [];
        foreach($courses as $course){
            foreach($endresult1 as $rarr){
                if($rarr == $course->activitynumber){
                    array_push($result, $course->fullname);
                }
            }
        }
        $result = array_count_values($result);

        //(number of enrolled students, course name)
        $endresult = [];
        foreach($courses as $cou){
            if(isset($result[$cou->fullname])){
                array_push($endresult, [$cou->fullname, $result[$cou->fullname]]);
            }
        }
        $finalresult = [];
        foreach($endresult as $endar){
            array_push($finalresult, [$endar[1], $endar[0]]);
        }
        arsort($finalresult);
        return $finalresult;
    }

    //All Learners (Students Only!)
    public function learner_course(): array{
        global $DB;

        $courses = $DB->get_records('course');
        $context = $DB->get_records('context');
        //(Course id)
        $ccarray = [];
        foreach($courses as $course){
            foreach($context as $cont){
                if($course->id == $cont->instanceid){
                    array_push($ccarray, [$cont->id, $course->id]);
                }
            }
        }

        //(user id, courseid)
        $rarray = [];
        $roleassigns = $DB->get_records('role_assignments');
        foreach($roleassigns as $roleassign){
            foreach($ccarray as $carray){
                if($roleassign->contextid == $carray[0] && $roleassign->roleid == 5){
                    array_push($rarray, [$roleassign->userid, $carray[1]]);
                }
            }
        }

        //(userid, course full name)
        global $USER;
        $courses = $DB->get_records('course_tracked', [$DB->sql_compare_text('userid') => $USER->id]);
        $result = [];
        foreach($courses as $course){
            foreach($rarray as $rarr){
                if($rarr[1] == $course->activitynumber){
                    array_push($result, [$rarr[0], $course->activitynumber]);
                }
            }
        }

        //(userid, courseid, timestart)
        $userenrols = $DB->get_records('user_enrolments');
        $enrols = $DB->get_records('enrol');
        $enrolarray = [];
        foreach($userenrols as $uenrol){
            foreach($enrols as $enro){
                if($enro->id == $uenrol->enrolid && $uenrol->status <> 1){
                    array_push($enrolarray, [$uenrol->userid, $enro->courseid]);
                }
            }
        }

        //Only add users which ar learners to array
        $middlearray = [];
        foreach($result as $res){
            foreach($enrolarray as $enrarray){
                if($res[0] == $enrarray[0] && $res[1] == $enrarray[1]){
                    array_push($middlearray, [$enrarray[0]]);
                }
            }
        }

        //Get username and institution and create a new array with all the new values
        $lastarray = [];
        $users = $DB->get_records('user');
        foreach($users as $user){
            foreach($middlearray as $midarray){
                if($user->id == $midarray[0]){
                    if(!in_array([$user->firstaccess, $user->firstname.' '.$user->lastname, $user->institution, $user->lastaccess, $user->id], $lastarray)){
                        array_push($lastarray, [$user->firstaccess, $user->firstname.' '.$user->lastname, $user->institution, $user->lastaccess, $user->id]);
                    }
                }
            }
        }
        //Sort by start date desending 
        arsort($lastarray);
        $int = 0;
        $finalarray = [];
        foreach($lastarray as $lastar){
            if(!isset($lastar[4])){
                $lastar[4] = '';
            }
            $tempDate1 = date('d/m/Y',$lastar[0]);
            if($lastar[0] == 0){
                $tempDate1 = 'N/A';
            }
            $tempDate2 = date('d/m/Y', $lastar[3]);
            if($lastar[3] == 0){
                $tempDate2 = 'N/A';
            }
            array_push($finalarray,[$int + 1, $tempDate1, $lastar[1], $lastar[2], $tempDate2, $lastar[4]]);
            $int++;
        }
        return $finalarray;
    }

    //Get groups info for course totals table
    public function groups_info(): array{
        global $DB;
        $courses = $DB->get_records('course');
        $context = $DB->get_records('context');
        //(Course id)
        $ccarray = [];
        foreach($courses as $course){
            foreach($context as $cont){
                if($course->id == $cont->instanceid){
                    array_push($ccarray, [$cont->id, $course->id]);
                }
            }
        }
        //(userid, courseid)
        $rarray = [];
        $roleassigns = $DB->get_records('role_assignments');
        foreach($roleassigns as $roleassign){
            foreach($ccarray as $carray){
                if($roleassign->contextid == $carray[0] && $roleassign->roleid == 5){
                    array_push($rarray, [$roleassign->userid, $carray[1]]);
                }
            }
        }

        //(userid, courseid) Get all users who arent suspended
        $resulting = [];
        $enrols = $DB->get_records('enrol');
        $userenrolments = $DB->get_records('user_enrolments');
        foreach($enrols as $enr){
            foreach($userenrolments as $useren){
                if($useren->enrolid == $enr->id && $useren->status <> 1){
                    array_push($resulting, [$useren->userid, $enr->courseid]);
                }
            }
        }

        //Don't included suspended users in array
        $endresult1 = [];
        foreach($rarray as $res){
            foreach($resulting as $resu){
                if($res == $resu){
                    array_push($endresult1, $resu[1]);
                }
            }
        }
        $rarray = array_count_values($endresult1);

        global $USER;
        $courses = $DB->get_records('course_tracked', [$DB->sql_compare_text('userid') => $USER->id]);
        $endresult = [];
        foreach($courses as $cour){
            $usernumber = 0;
            if(isset($rarray[$cour->activitynumber])){
                $usernumber = $rarray[$cour->activitynumber];
            }
            array_push($endresult, [$usernumber, $cour->fullname, $cour->activitynumber]);
        }
        //Sort by course
        arsort($endresult);
        $int = 0;
        $endresult2 = [];
        foreach($endresult as $endres){
            array_push($endresult2, [$int+1, $endres[1], $endres[0], $endres[2]]);
            $int++;
        }
        return $endresult2;
    }

    public function innactive(): array{
        global $DB;
        $users = $DB->get_records('user');
        $array = [];
        foreach($users as $user){
            if($user->firstaccess == 0 && $user->id <> 1 && $user->deleted == 0 && $user->suspended == 0){
                array_push($array, [$user->timecreated, $user->firstname.' '.$user->lastname, $user->id]);
            }
        }
        $int = 0;
        asort($array);
        $lastarray = [];
        foreach($array as $ar){
            array_push($lastarray, [$int+1, $ar[1], date('d/m/Y', $ar[0]), $ar[2]]);
            $int++;
        }
        return $lastarray;
    }
    
    public function pastenroltime($start, $end){
        global $DB;
        $enrolments = $DB->get_records('user_enrolments');
        $recentenrol = [];
        //(userid, enrolid, timecreated)
        foreach($enrolments as $enrolment){
            if($enrolment->timecreated >= $start && $enrolment->timecreated <= $end){
                array_push($recentenrol, [$enrolment->userid, $enrolment->enrolid, $enrolment->timecreated]);
            }
        }

        //(userid, courseid, timecreated)
        $enrols = $DB->get_records('enrol');
        $recentcourseid = [];
        foreach($enrols as $enrol){
            foreach($recentenrol as $recent){
                if($enrol->id == $recent[1]){
                    array_push($recentcourseid, [$recent[0], $enrol->courseid, $recent[2]]);
                }
            }
        }

        //(userid, course full name, timecreated)
        global $USER;
        $courses = $DB->get_records('course_tracked', [$DB->sql_compare_text('userid') => $USER->id]);
        $recenttracked = [];
        foreach($courses as $course){
            foreach($recentcourseid as $recentc){
                if($recentc[1] == $course->activitynumber){
                    array_push($recenttracked, [$recentc[0], $course->fullname, $recentc[2], $course->activitynumber]);
                }
            }
        }

        //(username, course full name, timecreated)
        $users = $DB->get_records('user');
        $recentusers = [];
        foreach($users as $user){
            foreach($recenttracked as $recentt){
                if($user->id == $recentt[0]){
                    array_push($recentusers, [$recentt[2], $recentt[1], $user->firstname.' '.$user->lastname, $user->id, $recentt[3]]);
                }
            }
        }
        arsort($recentusers);

        $int = 0;
        $endarray = [];
        foreach($recentusers as $recentu){
            array_push($endarray, [$int+1, $recentu[1], $recentu[2], date('d/m/Y', $recentu[0]), $recentu[3], $recentu[4]]);
            $int++; 
        }
        return $endarray;
    }

    //Start of code for archived learners
    //Get all profile field data for users
    public function archivedusers(){
        global $DB;
        $data = $DB->get_records_sql("SELECT * FROM {user_info_field} INNER JOIN {user_info_data} ON {user_info_field}.id = {user_info_data}.fieldid WHERE shortname in (            
            'UniqueLearnerNumber',
            'LearnerReferenceNumber',
            'DateOfBirth',
            'Postcode')
        ");
        return $data;
    }

    //Get all archived users for a full search
    public function allarchivedusers($all){
        global $DB;
        //Get unique user id
        $data = $this->archivedusers();
        $array = [];
        $int = 0;
        foreach($data as $dat){
            $array[$int] = $dat->userid;
            $int++;
        }
        $array = array_unique($array);
        $finarray = [];
        $int = 0;
        foreach($array as $arra){
            $finarray[$int] = $arra;
            $int++;
        }
        $array = $finarray;
        //Get user data
        $user = $DB->get_records_sql('SELECT id, username, firstname, lastname, email FROM {user}');
        $userarray = [];
        $int = 0;
        foreach($user as $use){
            foreach($array as $arr){
                if($arr == $use->id){
                    if($DB->record_exists('archived_user_data', [$DB->sql_compare_text('userid') => $use->id])){
                        $extradata = $DB->get_records_sql('SELECT * FROM {archived_user_data} WHERE userid = ?', [$use->id]);
                        $extraarray = [];
                        foreach($extradata as $exdata){
                            array_push($extraarray, [$exdata->aimseqnumber, $exdata->learnaimref, $exdata->learnaimtitle, date('d-m-Y',$exdata->learnstartdate), date('d-m-Y',$exdata->learnplanenddate), $exdata->completionstate, $exdata->standardcode]);
                        }
                        array_push($userarray, [$use->id, $int, $use->username, $use->lastname, $use->firstname, $use->email, $extraarray]);
                    } else {
                        array_push($userarray, [$use->id, $int, $use->username, $use->lastname, $use->firstname, $use->email]);
                    }
                    $int++;
                }
            }
        }
        //Add in extra profile fields to userarray
        $int = 0;
        foreach($userarray as $uarray){
            foreach($data as $da){
                if($uarray[0] == $da->userid){
                    if($da->shortname == 'UniqueLearnerNumber'){
                        $userarray[$int][9] = $da->data;
                    } elseif ($da->shortname == 'LearnerReferenceNumber'){
                        $userarray[$int][10] = $da->data;
                    } elseif ($da->shortname == 'DateOfBirth'){
                        $userarray[$int][8] = date('d-m-Y',$da->data);
                    } elseif ($da->shortname == 'Postcode'){
                        $userarray[$int][7] = $da->data;
                    }
                }
            }
            $int++;
        }
        //Sort the arrays alphabetically by lastname
        $sortarray = [];
        foreach($userarray as $userarr){
            array_push($sortarray, [$userarr[3], $userarr[0], $userarr[1], $userarr[2], $userarr[4], $userarr[5], $userarr[6], $userarr[7], $userarr[8], $userarr[9], $userarr[10]]);
        }
        asort($sortarray);
        $userarray = [];
        $int = 0;
        foreach($sortarray as $sarray){
            array_push($userarray, [$sarray[1], $int, $sarray[3], $sarray[0], $sarray[4], $sarray[5], $sarray[6], $sarray[7], $sarray[8], $sarray[9], $sarray[10]]);
            $int++;
        }
        //Used to add a log by triggering the search_all_archived event
        if($all === false){
            \local_lessonanalytics\event\searched_all_archived::create(array('contextid' => 1, 'contextlevel' => 10))->trigger();
        }
        return $userarray;
    }

    //Get selection of archived users
    public function selectarchivedusers($data){
        global $DB;
        $profilefields = $this->allarchivedusers(true);
        $allusers = [];
        //Used to filter user details dependant on their inputs, for the user database
        if(!empty($data[0]) || !empty($data[1]) || !empty($data[2]) || !empty($data[3])){
            $userdetails = [];
            $string = '';
            $int = 0;
            $pos = 0;
            $i = 0;
            $arguments = ['username', 'lastname', 'firstname', 'email'];
            while($i < 4){
                if(!empty($data[$i])){
                    $userdetails[$int] = "%".strtolower($data[$i])."%";
                    $int++;
                    if($pos == 0){
                        $string .= ' WHERE lower('.$arguments[$i].') LIKE ? ';
                        $pos++;
                    } elseif ($pos > 0){
                        $string .= 'AND lower('.$arguments[$i].') LIKE ? ';
                        $pos++;
                    }
                }
                $i++;
            }
            $users = $DB->get_records_sql('SELECT id FROM {user}'.$string, $userdetails);
            //Used to filter selected users dependant on the inputs
            foreach($profilefields as $profield){
                foreach($users as $user){
                    if($user->id == $profield[0]){
                        array_push($allusers, $profield);
                    }
                }
            }
        } else {
            $allusers = $profilefields;
        }
        //Used to filter all profile fields dependant on inputs
        if(
        !empty($data[4]) ||
        !empty($data[5]) || 
        !empty($data[6]) || 
        !empty($data[7]) || 
        !empty($data[8]) ||
        !empty($data[9]) ||
        !empty($data[10]) ||
        !empty($data[11]) ||
        !empty($data[12]) ||
        !empty($data[13]) ||
        !empty($data[14]) ||
        !empty($data[15]) ||
        !empty($data[16])
        ){
            $i = 4;
            while($i < 15){
                if(!empty($data[$i])){
                    $temparray = [];
                    foreach($allusers as $alluser){
                        if($i == 5){
                            if($alluser[8] == date('d-m-Y',$data[$i])){
                                array_push($temparray, $alluser);
                            }
                        } elseif($i == 4){
                            if(strpos(strtolower($alluser[7]), strtolower($data[$i])) !== false){
                                array_push($temparray, $alluser);
                            }
                        } elseif($i == 6){
                            if(strpos(strval($alluser[9]), strval($data[$i])) !== false){
                                array_push($temparray, $alluser);
                            }
                        } elseif($i == 7){
                            if(strpos(strval($alluser[10]), strval($data[$i])) !== false){
                                array_push($temparray, $alluser);
                            }
                        //Checking the extra data values against the users input
                        } elseif($i == 8 || $i == 9 || $i == 10 || $i == 11 || $i == 12 || $i == 13 || $i == 14){
                            $extralength = count($alluser[6]);
                            $place = 0;
                            while($place < $extralength){
                                $startTemp = strtotime($alluser[6][$place][3]);
                                $endTemp = strtotime($alluser[6][$place][4]);
                                if(
                                    ($alluser[6][$place][0] == $data[$i] && $i == 8) || 
                                    (strpos(strtolower(strval($alluser[6][$place][1])), strtolower(strval($data[$i]))) !== false && $i == 9) ||
                                    (strpos(strtolower(strval($alluser[6][$place][2])), strtolower(strval($data[$i]))) !== false && $i == 10) ||
                                    ($startTemp >= $data[$i] && $startTemp <= $data[15] && $i == 11 && $data[$i] >= 1 && $data[15] >= 1) ||
                                    ($startTemp >= $data[$i] && $i == 11 && $data[15] <= 0 && $data[$i] > 0) ||
                                    ($endTemp >= $data[$i] && $endTemp <= $data[16] && $i == 12 && $data[$i] >= 1 && $data[16] >= 1) ||
                                    ($endTemp >= $data[$i] && $i == 12 && $data[16] <= 0 && $data[$i] > 0) ||
                                    ($alluser[6][$place][5] == $data[$i] && $i == 13) ||
                                    ($alluser[6][$place][6] == $data[$i] && $i == 14)
                                ){}else {
                                    unset($alluser[6][$place]);
                                }
                                $place++;
                            }
                            //Reset the positions of the array values
                            $reposition = [];
                            foreach($alluser[6] as $allu){
                                array_push($reposition, $allu);
                            }
                            $alluser[6] = $reposition;
                            if(count($alluser[6]) != 0){
                                array_push($temparray, $alluser);
                            }
                        }
                    }
                    $allusers = $temparray;
                }
                $i++;
            }
        }
        //Correct id number for array
        $int = 0;
        foreach($allusers as $alluser){
            $allusers[$int][1] = $int;
            $int++;
        }
        //Used to add a log by triggering the search_all_archived event
        \local_lessonanalytics\event\searched_select_archived::create(array('contextid' => 1, 'contextlevel' => 10))->trigger();
        return $allusers;
    }

    public function addarchivedata($array){
        global $DB;
        //[username, aimSequenceNumber, learningAimReference, learningAimTitle, learningStartDate, learningPlannedEndDate, completionStatus]
        foreach($array as $arr){
            $userid = $DB->get_record_sql('SELECT id FROM {user} WHERE username = ?', [$arr[0]])->id;
            if(!empty($userid)){
                if(!$DB->record_exists_sql('SELECT * FROM {archived_user_data} WHERE userid = ? AND
                    aimseqnumber = ? AND learnaimref = ? AND learnaimtitle = ? AND learnstartdate = ? AND
                    learnplanenddate = ? AND completionstate = ? AND standardcode = ?', 
                    [$userid, $arr[1], $arr[2], $arr[3], $arr[4], $arr[5], $arr[6], $arr[7]])
                ){
                    $record = new stdClass();
                    $record->userid = $userid;
                    $record->aimseqnumber = $arr[1];
                    $record->learnaimref = $arr[2];
                    $record->learnaimtitle = $arr[3];
                    $record->learnstartdate = $arr[4];
                    $record->learnplanenddate = $arr[5];
                    $record->completionstate = $arr[6];
                    $record->standardcode = $arr[7];
                    $DB->insert_record('archived_user_data', $record, false);
                    //Used to add a log by triggering the created_archived_record event
                    \local_lessonanalytics\event\created_archived_record::create(array('contextid' => 1, 'contextlevel' => 10, 'relateduserid'=>$userid))->trigger();
                }
            }
        }
    }
    //End of code for archived learners

    public function newusers($start, $end){
        global $DB;
        $users = $DB->get_records_sql('SELECT * FROM {user} WHERE suspended = ?',[0]);
        $array = [];
        foreach($users as $user){
            if($user->firstaccess == 0 && $user->id != 1){
                array_push($array, [$user->timemodified, $user->firstname, $user->lastname, $user->id]);
            } else {
                array_push($array, [$user->firstaccess, $user->firstname, $user->lastname, $user->id]);
            }
        }
        asort($array);
        $lastarray = [];
        $int = 1;
        foreach($array as $arr){
            if($arr[0] >= $start && $arr[0] <= $end){
                array_push($lastarray, [$int, $arr[1]." ".$arr[2], date('d/m/Y',$arr[0]), $arr[3]]);
                $int++;
            }
        }
        return $lastarray;
    }
}