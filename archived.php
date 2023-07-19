<?php

/**
 * @package     local_lessonanalytics
 * @author      Robert Tyrone Cullen
 * @var stdClass $plugin
 */

require_once(__DIR__.'/../../config.php');

use local_lessonanalytics\manager;

require_login();
$context = context_system::instance();
require_capability('local/lessonanalytics:lessonanalytics', $context);

//Set page properties
$PAGE->set_url(new moodle_url('/local/lessonanalytics/archived.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Manage Archived Learners');
$PAGE->set_heading('Manage Archived Learners');
//Used to include LHS navigation
$PAGE->set_pagelayout('admin');

$manager = new manager();

echo $OUTPUT->header();
/*
//Navigation
include("./classes/navigation/navigation.php");
//Archived Learners
include("./classes/tables/archived-table.php");
?>
<script defer src="./classes/js/heading-clicked-archive.js"></script>
<style>
    .c-pointer{
        cursor: pointer;
    }
</style>
<?php
*/
echo $OUTPUT->footer();
