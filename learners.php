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
$PAGE->set_url(new moodle_url('/local/lessonanalytics/learners.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title('Search for Learners');
$PAGE->set_heading('Search for Learners');
//Used to include LHS navigation
$PAGE->set_pagelayout('admin');

$manager = new manager();

echo $OUTPUT->header();
//Navigation
include("./classes/navigation/navigation.php");
//Query Table
include('./classes/tables/query-table.php'); // Company Table
?>
<script defer src="./classes/js/heading-clicked-query.js"></script>
<?php
echo $OUTPUT->footer();