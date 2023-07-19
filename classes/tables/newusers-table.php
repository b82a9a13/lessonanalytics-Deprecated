<?php
$start = strtotime('- 8 days');
$end = date('U');
$newusers = $manager->newusers($start, $end);
$usersheadings = [
    '#',
    'Learner',
    'Start Date'
];
$userstemplate = (object)[
    'data' => array_values($newusers),
    'title' => 'New User History',
    'startdate' => date('Y-m-d', $start),
    'enddate' => date('Y-m-d', $end),
    'headings' => array_values(array($usersheadings))
];
echo("<div class='newusers_table table-section' hidden>");
    echo $OUTPUT->render_from_template('local_lessonanalytics/newusers', $userstemplate);
echo('</div>');
?>
<script src="./classes/js/newusers-table.js"></script>