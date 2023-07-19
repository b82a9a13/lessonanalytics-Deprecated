<?php
//Groups table
$first = $manager->groups_info();
$groupheadings = [
    '#',
    get_string('course', 'local_lessonanalytics'),
    get_string('t_enrolled', 'local_lessonanalytics')
];
$groupstemplate = (object)[
    'data' => array_values($first),
    'headings' => array_values(array($groupheadings)),
    'title' => get_string('course_t', 'local_lessonanalytics')
];
echo("<div class='group_table table-section' hidden>");
    echo $OUTPUT->render_from_template('local_lessonanalytics/groups', $groupstemplate);
echo("</div>");
?>
<script src="./classes/js/group-table.js"></script>