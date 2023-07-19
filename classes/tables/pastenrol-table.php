<?php
$start = strtotime('- 8 days');
$end = date('U');
$pastenrol = $manager->pastenroltime($start, $end);
$pastheadings = [
    '#',
    'Course',
    'Learner',
    'Start Date'
];

$pastenroltemplate = (object)[
    'data' => array_values($pastenrol),
    'headings' => array_values(array($pastheadings)),
    'title' => 'Enrolment History',
    'startdate' => date('Y-m-d', $start),
    'enddate' => date('Y-m-d', $end)
];
echo("<div class='pastenrol_table table-section' hidden>");
    echo $OUTPUT->render_from_template('local_lessonanalytics/pastenrol', $pastenroltemplate);
echo('</div>');
?>
<script src="./classes/js/pastenrol-table.js"></script>