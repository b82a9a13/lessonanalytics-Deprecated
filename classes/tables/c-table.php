<?php
// c - Company Table
//Used for creating a table for (Course, Leanrer, Date)
$clearner = $manager->learner_course();
$learnertemplate = (object)[
    'data' => array_values($clearner),
    'date' => get_string('date', 'local_lessonanalytics'),
    'course' => get_string('course', 'local_lessonanalytics'),
    'learner' => get_string('learner', 'local_lessonanalytics'),
    'company' => get_string('company', 'local_lessonanalytics'),
    'title' => get_string('all_learners', 'local_lessonanalytics'),
    'last_access' => get_string('last_access', 'local_lessonanalytics')
];
echo("<div class='c-table table-section' hidden>");
    echo $OUTPUT->render_from_template('local_lessonanalytics/learner', $learnertemplate);
echo("</div>")
?>
<script src="./classes/js/c-table.js"></script>