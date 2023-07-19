<?php

//Creates table for innactive accounts
$innactive = $manager->innactive();
$head = [
    '#',
    'Username',
    'Account Creation Date'
];
$innactivetemplate = (object)[
    'data' => array_values($innactive),
    'headings' => array_values(array($head)),
    'title' => 'Never Accessed Users'
];
echo("<div class='innac-table table-section' hidden>");
echo $OUTPUT->render_from_template('local_lessonanalytics/innactive', $innactivetemplate);
echo("</div>");
?>
<script src="./classes/js/innac-table.js"></script>