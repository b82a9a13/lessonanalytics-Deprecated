<?php
// EU - Enrolled Users Chart

// Get data for courses chart
$usercourse = $manager->user_course();
$userenrolled = [];
$courseid = [];
$num = 0;
foreach($usercourse as $cid){
    $userenrolled[$num] = $cid[0];
    $courseid[$num] = $cid[1];
    $num++;
}
//Courses chart
$coursesnumber= new \core\chart_series(get_string('enrolled_chart', 'local_lessonanalytics'), $userenrolled);
$courseslabels = $courseid;
$courseschart = new \core\chart_pie();
$courseschart->set_title(get_string('enrolled_chart', 'local_lessonanalytics'));
$courseschart->add_series($coursesnumber);
$courseschart->set_labels($courseslabels);
echo ('<div class="eu_chart" hidden>');
echo $OUTPUT->render($courseschart);
echo ('</div>')
?>
<script>
    function eu_click(){
        if(document.querySelector('.eu_chart').hidden == false){
            document.querySelector('.eu_chart').hidden = true;
            document.querySelector('.eu_chart_button').innerHTML = '<?php echo(get_string('eu_chart_s', 'local_lessonanalytics')) ?>';
            document.getElementById('eu_chart_button').className = 'btn-primary mb-2 mr-2 p-2 eu_chart_button';
            document.getElementById('chart_section').style.display = 'inline-block';
        } else {
            document.querySelector('.eu_chart').hidden = false;
            document.querySelector('.eu_chart_button').innerHTML = '<?php echo(get_string('eu_chart_h', 'local_lessonanalytics')) ?>';
            document.getElementById('eu_chart_button').className = 'btn-secondary mb-2 mr-2 p-2 eu_chart_button';
            document.getElementById('chart_section').style.display = 'block';
        }
    }
</script>