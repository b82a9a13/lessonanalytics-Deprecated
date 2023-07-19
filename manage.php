<?php

/**
 * @package     local_lessonanalytics
 * @author      Robert Tyrone Cullen
 * @var stdClass $plugin
 */

require_once(__DIR__ . '/../../config.php');

use local_lessonanalytics\manager;

require_login();
$context = context_system::instance();
require_capability('local/lessonanalytics:lessonanalytics', $context);

// Set page properties
$PAGE->set_url(new moodle_url('/local/lessonanalytics/manage.php'));
$PAGE->set_context(\context_system::instance());
$PAGE->set_title(get_string('manage_lesson', 'local_lessonanalytics'));
$PAGE->set_heading(get_string('manage_lesson', 'local_lessonanalytics'));
//Used to include LHS navigation
$PAGE->set_pagelayout('admin');

// Get tracked messages
$manager = new manager();
$courses = $manager->get_tracked_courses();
// Output data to page
echo $OUTPUT->header();
//Navigation
include("./classes/navigation/navigation.php");
// context template
$templatecontext = (object)[
    'courses' => array_values($courses),
    'pluginname' => get_string('pluginname', 'local_lessonanalytics'),
    'addlessonsurl' => new moodle_url('/local/lessonanalytics/addlessons.php'),
    'removelessonsurl' => new moodle_url('/local/lessonanalytics/removelessons.php'),
];
// Render page
echo $OUTPUT->render_from_template('local_lessonanalytics/manage', $templatecontext);
?>
<script>
    document.getElementById('showorhide').addEventListener('click', function(){
        let courselist = document.getElementById('courselist');
        if(courselist.style.display == 'none'){
            document.getElementById('showorhide').innerHTML = 'Hide List'
            courselist.style.display = 'block'
        } else {
            document.getElementById('showorhide').innerHTML = 'Show List'
            courselist.style.display = 'none'
        }
    });
</script>
<div class="individual-section-chart" id="chart_section" style="display: inline-block;">
    <?php
    //Includes the buttons file for the charts
    include('./classes/charts/chart_buttons.php');
    //Include file which creates and renders chart
    include('./classes/charts/eu_chart.php'); // Enrolled Users
    ?>
</div><br><br>
<div class="individual-section">
    <?php
    //Includes the button file for the tables
    include('./classes/tables/table-buttons.php'); // Course Total Table
    //Inlcude File which creates and renders 
    include('./classes/tables/group-table.php'); // Course Total Table
    include('./classes/tables/c-table.php'); // Company Table
    include('./classes/tables/innac-table.php'); // Innactive users Table
    include('./classes/tables/pastenrol-table.php'); // Enrolment History
    include('./classes/tables/newusers-table.php'); // New users
    ?>
</div>
<?php
//Print btn for reports
$print = get_string('print', 'local_lessonanalytics');
$link = $CFG->wwwroot . '/local/lessonanalytics/pdf.php';
print("<br><br><a href='$link' target='_blank'><button class='btn btn-primary'>$print</button></a>");

?>
<script defer src="./classes/js/heading-clicked-tables.js"></script>
<style>
    .individual-section-chart, .individual-section, .table-section{
        border: 2px solid #95287A;
        border-radius: 5px;
        padding: .5rem;
    }
    .individual-section{
        display: inline-block;
    }
    .individual-section-chart, .individual-section{
        background: #F5F5F5;
    }
    .table-section{
        background: #FCFCFC;
        margin-top: .5rem;
    }
</style>
<?php
echo $OUTPUT->footer();

//Used to add a log by triggering the viewed_analytics event
\local_lessonanalytics\event\viewed_analytics::create(array('contextid' => 1, 'contextlevel' => 10))->trigger();