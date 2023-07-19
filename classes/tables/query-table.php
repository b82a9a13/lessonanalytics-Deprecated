<?php
// Query table 
$querytemplate = (object)[
    'company' => get_string('company', 'local_lessonanalytics'),
    'surname' => get_string('surname', 'local_lessonanalytics'),
    'firstname' => get_string('firstname', 'local_lessonanalytics'),
    'email' => get_string('email', 'local_lessonanalytics'),
    'city' => get_string('city', 'local_lessonanalytics'),
    'filter' => get_string('filter', 'local_lessonanalytics'),
    'username' => get_string('username', 'local_lessonanalytics'),
    'title' => get_string('search_l', 'local_lessonanalytics'),
    'info' => get_string('search_li', 'local_lessonanalytics')
];
echo $OUTPUT->render_from_template('local_lessonanalytics/query', $querytemplate);
$headings = [
    '#', 
    get_string('username', 'local_lessonanalytics'), 
    get_string('surname', 'local_lessonanalytics'), 
    get_string('firstname', 'local_lessonanalytics'), 
    get_string('email', 'local_lessonanalytics'),
    get_string('city', 'local_lessonanalytics'), 
    get_string('company', 'local_lessonanalytics'), 
    get_string('id', 'local_lessonanalytics')
];
$filtertemplate = (object)[
    'headings' => array_values(array($headings)),
    'button' => get_string('update_btn', 'local_lessonanalytics')
];
echo $OUTPUT->render_from_template('local_lessonanalytics/querytable', $filtertemplate);
?>
<script src="./classes/js/query-table.js"></script>