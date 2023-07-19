<?php
require_once(__DIR__ . '/../../config.php');
use local_lessonanalytics\manager;
require_login();
$context = context_system::instance();
require_capability('local/lessonanalytics:lessonanalytics', $context);

//Include tcpdf file
require_once($CFG->libdir.'/filelib.php');
require_once($CFG->libdir.'/tcpdf/tcpdf.php');

//Extends the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF{
    public function Header(){
        $this->Image('classes/img/ntalogo.png', $this->GetPageWidth() - 32, $this->GetPageHeight() - 22, 30, 20, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
    }
    public function Footer(){
        //Set position from botton
        $this->setY(-15);
        //Set font
        $this->SetFont('Times', 'B', 12);
        //Page number
        $this->Cell(0, 0, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'C', 0, '', 0, false, 'T', 'M');
    }
}

//Create new pdf
$pdf = new MyPDF('P','mm','A4');
//id, course, learner, date, company
$manager = new manager();

//Get strings
$course = get_string('course', 'local_lessonanalytics');
$tcourse = get_string('t_course', 'local_lessonanalytics');
$rct = get_string('r_course_title', 'local_lessonanalytics');
$eut = get_string('r_eu_title', 'local_lessonanalytics');
$alt = get_string('r_al_title', 'local_lessonanalytics');
$mtpct = get_string('r_mtpc_title', 'local_lessonanalytics');
$cmtt = get_string('r_cmt_title', 'local_lessonanalytics');
$tmct = get_string('r_tmc_title', 'local_lessonanalytics');
$gpct = get_string('r_gpc_title', 'local_lessonanalytics');
$naut = get_string('r_nau_title', 'local_lessonanalytics');
$pet = get_string('r_pet_title', 'local_lessonanalytics');

//Cell height
$cheight = 10;

//decare font
$font = 'Times';
//Set colors
$cmykdual = [array(21, 27, 0, 0), array(4, 6, 0, 0)];

//Font Sizes
$titlesize = 32;
$sixteen = 16;
$tablehead = 13;
$tabletext = 12;

//Front Page
$pdf->setPrintHeader(false);
$pdf->AddPage('L');
$pdf->setFont($font, 'B', 64);
$pdf->Image('classes/img/ntalogo.png', ($pdf->GetPageWidth() / 2 )- 27, 5, 54, 36, 'PNG', '', '', true, 150, '', false, false, 0, false, false, false);
$pdf->Cell(0, 0, '', 0, 0, 'C', 0, '', 0);
$pdf->Ln();
$pdf->Ln();
$pdf->Cell(0, 0, get_string('report_head', 'local_lessonanalytics'), 0, 0, 'C', 0, '', 0);
$pdf->setFont($font, '', $titlesize);
$pdf->Ln();
$pdf->Cell(0, 20, get_string('from', 'local_lessonanalytics').': '.date('d/m/Y', strtotime('-8 days')). '  '. get_string('to', 'local_lessonanalytics').': '.date('d/m/Y', strtotime('-1 days')), 0, 0, 'C', 0, '', 0);

//Pie sector x and y and w for width
$piex = $pdf->getPageWidth() / 2 + 70;
$piey = 117.5;
$piew = 75;

//2nd Page
$indexw = 70;
$conheight = 12;
$pdf->setPrintHeader(true);
$pdf->AddPage('L');
$pdf->setFont($font, 'B', $titlesize);
$pdf->Cell(0, 0, get_string('r_index_title', 'local_lessonanalytics').':', 0, 0, 'L', 0, '', 0);
$pdf->setFont($font, '', $sixteen);
$pdf->Ln();
$pdf->Cell(0, $cheight, get_string('r_index_info', 'local_lessonanalytics'), 0, 0, 'L', 0, '', 0);
$pdf->setFont($font, 'B', $sixteen);
$pdf->Ln();
$pdf->Cell($indexw, $cheight, 'Overall Reports:', 0, 0, 'L', 0, '', 0);
$pdf->Ln();
$pdf->setFont($font, '', $sixteen);

$pdf->Cell($indexw, $cheight, '1) '.$rct, 0, 0, 'L', 0, '', 0);
$pdf->Cell(0, $conheight, '- '. get_string('r_course_info', 'local_lessonanalytics'), 0, 0, 'L', 0, '', 0);
$pdf->Ln();
$pdf->Cell($indexw, $cheight, '2) '.$alt, 0, 0, 'L', 0, '', 0);
$pdf->Cell(0, $conheight, '- '. get_string('r_al_info', 'local_lessonanalytics'), 0, 0, 'L', 0, '', 0);
$pdf->Ln();
$pdf->Cell($indexw, $cheight, '3) '.$naut, 0, 0, 'L', 0, '', 0);
$pdf->Cell(0, $conheight, '- '. get_string('r_nau_info', 'local_lessonanalytics'), 0, 0, 'L', 0, '', 0);
$pdf->Ln();
$pdf->Cell($indexw, $cheight, '4) '.$pet, 0, 0, 'L', 0, '', 0);
$pdf->Cell(0, $conheight, '- '. get_string('r_pet_info', 'local_lessonanalytics'), 0, 0, 'L', 0, '', 0);
$pdf->Ln();

//Add new page
$pdf->addPage('L');
$pdf->setFont($font, 'B', $sixteen);
$pdf->Cell(0, 0, 'Overall Report: 1', 0, 0, 'L', 0, '', 0);
$pdf->Ln();
//Set the title
$pdf->setFont($font, 'B', $titlesize);
$pdf->Cell(0, 0, $rct, 0, 0, 'C', 0, '', 0);
$pdf->setFont($font, '', $sixteen);
$pdf->Ln();
$pdf->Cell(0, $cheight, 'A table of all the courses showing total values for each one (sorted by users in descending order)', 0, 0, 'C', 0, '', 0);
$pdf->Ln();
//Set the font
$pdf->setFont($font, 'B', $sixteen);
//Add cell to page
$pdf->Cell(275, $cheight, $rct, 1, 0, 'C', 0, '', 0);
//Add new line
$pdf->Ln();

$courselength = 275/3;
$pdf->setFont($font, 'B', $tablehead);
$pdf->Cell($courselength*.5, $cheight, '#', 1, 0, 'C', 0, '', 0);
$pdf->Cell($courselength*1.5, $cheight, $course, 1, 0, 'C', 0, '', 0);
$pdf->Cell($courselength, $cheight, 'Total Enrolled', 1, 0, 'C', 0, '', 0);
$pdf->Ln();
$pdf->setFont($font, '', $tabletext);

//Adds data to 'table'
$totalstable = $manager->groups_info();
$pos = 0;
foreach($totalstable as $totaltable){
    if($pos == 2){
        $pos = 0;
    }
    $pdf->setFillColor($cmykdual[$pos][0], $cmykdual[$pos][1], $cmykdual[$pos][2], $cmykdual[$pos][3]);
    $pos++;
    $pdf->Cell($courselength*.5, $cheight, $totaltable[0], 1, 0, 'C', 1, '', 0);
    $pdf->Cell($courselength*1.5, $cheight, $totaltable[1], 1, 0, 'L', 1, '', 0);
    $pdf->Cell($courselength, $cheight, $totaltable[2], 1, 0, 'C', 1, '', 0);
    $pdf->Ln();
}

//Add page to pdf
$pdf->AddPage('L');
$pdf->setFont($font, 'B', $sixteen);
$pdf->Cell(0, 0, 'Overall Report: 2', 0, 0, 'L', 0, '', 0);
$pdf->Ln();
//Set title
$pdf->setFont($font, 'B', $titlesize);
$pdf->Cell(0, 0, $alt, 0, 0, 'C', 0, '', 0);
$pdf->setFont($font, '', $sixteen);
$pdf->Ln();
$pdf->Cell(0, 10, 'A table of all the learners enrolled in the courses (sorted by Start Date in descending order)', 0, 0, 'C', 0, '', 0);
$pdf->Ln();
//Set the font
$pdf->setFont($font, 'B', $sixteen);
//Add cell to page
$pdf->Cell(275, 10, $alt, 1, 0, 'C', 0, '', 0);
//Add new line
$leanrerlength = 275 / 5;
$pdf->Ln();
$pdf->setFont($font, 'B', $tablehead);
$pdf->Cell($leanrerlength / 5, 10, '#', 1, 0, 'C', 0, '', 0);
$pdf->Cell($leanrerlength * 1.9, 10, get_string('learner', 'local_lessonanalytics'), 1, 0, 'C', 0, '', 0);
$pdf->Cell($leanrerlength * 1.9, 10, get_string('company', 'local_lessonanalytics'), 1, 0, 'C', 0, '', 0);
$pdf->Cell($leanrerlength / 2, 10, get_string('date', 'local_lessonanalytics'), 1, 0, 'C', 0, '', 0);
$pdf->Cell($leanrerlength / 2, 10, get_string('last_access', 'local_lessonanalytics'), 1, 0, 'C', 0, '', 0);
$pdf->Ln();

$pdf->setFont($font, '', $tabletext);
$ctable = $manager->learner_course();
$pos = 0;
foreach($ctable as $tabledata){
    if($pos == 2){
        $pos = 0;
    }
    $pdf->setFillColor($cmykdual[$pos][0], $cmykdual[$pos][1], $cmykdual[$pos][2], $cmykdual[$pos][3]);
    $pos++;
    $pdf->Cell($leanrerlength / 5, 10, $tabledata[0], 1, 0, 'C', 1, '', 0);
    $pdf->Cell($leanrerlength * 1.9, 10, $tabledata[2], 1, 0, 'L', 1, '', 0);
    $pdf->Cell($leanrerlength * 1.9, 10, $tabledata[3], 1, 0, 'L', 1, '', 0);
    $pdf->Cell($leanrerlength / 2, 10, $tabledata[1], 1, 0, 'C', 1, '', 0);
    $pdf->Cell($leanrerlength / 2, 10, $tabledata[4], 1, 0, 'C', 1, '', 0);
    $pdf->Ln();
}

//Never Accessed Users page(s)
$pdf->addPage('L');
$pdf->setFont($font, 'B', $sixteen);
$pdf->Cell(0, 0, 'Overall Report: 3', 0, 0, 'L', 0, '', 0);
$pdf->Ln();
$pdf->setFont($font, 'B', $titlesize);
$pdf->Cell(0, 0, $naut, 0, 0, 'C', 0, '', 0);
$pdf->setFont($font, '', $sixteen);
$pdf->Ln();
$pdf->Cell(0, $cheight, 'The table shows all users who have never accessed their account (sorted by creation date in ascending order)', 0, 0, 'C', 0, '', 0);

$pdf->Ln();
$pdf->setFont($font, 'B', $sixteen);
$pdf->Cell(275, $cheight, $naut, 1, 0, 'C', 0, '', 0);

$pdf->Ln();
$id = 10;
$pdf->setFont($font, 'B', $tablehead);
$pdf->Cell($id, $cheight, '#', 1, 0, 'C', 0, '', 0);
$length = 265 / 2;
$pdf->Cell($length, $cheight, 'Username', 1, 0, 'C', 0, '', 0);
$pdf->Cell($length, $cheight, 'Account Creation Date', 1, 0, 'C', 0, '', 0);
$pdf->Ln();

$pdf->setFont($font, '', $tabletext);
$innactive = $manager->innactive();
$pos = 0;
foreach($innactive as $inac){
    if($pos == 2){
        $pos = 0;
    }
    $pdf->setFillColor($cmykdual[$pos][0], $cmykdual[$pos][1], $cmykdual[$pos][2], $cmykdual[$pos][3]);
    $pos++;
    $pdf->Cell($id, $cheight, $inac[0], 1, 0, 'C', 1, '', 0);
    $pdf->Cell($length, $cheight, $inac[1], 1, 0, 'L', 1, '', 0);
    $pdf->Cell($length, $cheight, $inac[2], 1, 0, 'C', 1, '', 0);
    $pdf->Ln();
}


//Enrolments For Week
$pdf->addPage('L');
$pdf->setFont($font, 'B', $sixteen);
$pdf->Cell(0, 0, 'Overall Report: 4', 0, 0, 'L', 0, '', 0);
$pdf->Ln();
$pdf->setFont($font, 'B', $titlesize);
$pdf->Cell(0, 0, $pet, 0, 0, 'C', 0, '', 0);
$pdf->setFont($font, '', $sixteen);
$pdf->Ln();
$pdf->Cell(0, $cheight, 'The table shows all enrolments for the past week (sorted by course in acsending order)', 0, 0, 'C', 0, '', 0);

$pdf->Ln();
$pdf->setFont($font, 'B', $sixteen);
$pdf->Cell(275, $cheight, $pet, 1, 0, 'C', 0, '', 0);
$pdf->Ln();

$id = 10;
$pdf->setFont($font, 'B', $tablehead);
$pdf->Cell($id, $cheight, '#', 1, 0, 'C', 0, '', 0);
$threetable = 265/3;
$pdf->Cell($threetable, $cheight, 'Course', 1, 0, 'C', 0, '', 0);
$pdf->Cell($threetable, $cheight, 'Learner', 1, 0, 'C', 0, '', 0);
$pdf->Cell($threetable, $cheight, 'Start Date', 1, 0, 'C', 0, '', 0);
$pdf->Ln();

$pdf->setFont($font, '', $tabletext);
$start = strtotime('- 8 days');
$end = date('U');
$pastenrol = $manager->pastenroltime($start, $end);
$pos = 0;
foreach($pastenrol as $pasten){
    if($pos == 2){
        $pos = 0;
    }
    $pdf->setFillColor($cmykdual[$pos][0], $cmykdual[$pos][1], $cmykdual[$pos][2], $cmykdual[$pos][3]);
    $pos++;
    $pdf->Cell($id, $cheight, $pasten[0], 1, 0, 'C', 1, '', 0);
    $pdf->Cell($threetable, $cheight, $pasten[1], 1, 0, 'L', 1, '', 0);
    $pdf->Cell($threetable, $cheight, $pasten[2], 1, 0, 'L', 1, '', 0);
    $pdf->Cell($threetable, $cheight, $pasten[3], 1, 0, 'C', 1, '', 0);
    $pdf->Ln();
}

//Output pdf
$pdf->Output('E-PortfolioWeeklyReport.pdf');

//Used to add a log by triggering the viewed_pdf_report event
\local_lessonanalytics\event\viewed_pdf_report::create(array('contextid' => 1, 'contextlevel' => 10))->trigger();