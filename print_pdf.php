<?php

require 'tcpdf/tcpdf.php';  
require 'connection.php';

// add custom font
TCPDF_FONTS::addTTFfont('tcpdf/custom-fonts/tahoma.ttf', 'TrueTypeUnicode');
TCPDF_FONTS::addTTFfont('tcpdf/custom-fonts/tahomabd.ttf', 'TrueTypeUnicode');


$_SESSION['uoc_id'] = $_GET['id'];

$sql1 = "SELECT * FROM units_of_competency WHERE id=".$_GET['id']."";

function generate_units_of_competency($conn) {
    // generate parent headings
    $gen_table1 = "SELECT lo.id AS lo_id, units_of_competency.id, lo.description, cnt FROM units_of_competency, lo, (SELECT l.id AS l_id, COUNT(*) AS cnt FROM lo l, assessments a WHERE l.id=a.lo_id GROUP BY l_id) as a WHERE units_of_competency.id=lo.uoc_id AND lo.id=a.l_id AND units_of_competency.id = ".$_GET['id']." ";
    return $gen_table1_data = $conn->query($gen_table1);
}

function generate_learning_outcomes($conn) {
    // generate sub headings
    $gen_table2 = "SELECT lo.id AS lo_id, lo.description AS lo_desc ,assessments.id AS a_id, assessments.description AS a_desc FROM lo, assessments, units_of_competency WHERE lo.id=assessments.lo_id AND lo.uoc_id=units_of_competency.id AND units_of_competency.id=".$_GET['id']." ORDER BY lo.id, assessments.id";
    return $gen_table2_data = $conn->query($gen_table2);
}

function generate_unique_learning_outcomes($conn) {
    //get unique learning outcome id
    $lo_unique = "SELECT lo.id AS lo_id, lo.description AS lo_desc ,assessments.id AS a_id, assessments.description AS a_desc FROM lo, assessments, units_of_competency WHERE lo.id=assessments.lo_id AND lo.uoc_id=units_of_competency.id AND units_of_competency.id=".$_GET['id']." GROUP BY lo.id ORDER BY lo.id, assessments.id";
    return $lo_unique_data = $conn->query($lo_unique);
}

function get_students_names($conn) {
    //get student names
    $gen_table3 = "SELECT students.id, students.name FROM lo, assessments, units_of_competency, students, assessment_records WHERE units_of_competency.id=lo.uoc_id AND lo.id=assessments.lo_id AND students.id=assessment_records.student_id AND units_of_competency.id=".$_GET['id']." GROUP BY students.id ORDER BY students.name";
    return $gen_table3_data = $conn->query($gen_table3);
}


//set detials
$data1 = $conn->query($sql1);
while ($row = $data1->fetch_assoc()) {
    $uoc_id = $row['id'];
    $uoc_description = $row['description'];
    $uoc_program_title = $row['program_title'];
    $uoc_batch_section = $row['course']." ".$row['yearlevel'];
    $uoc_schedule = $row['schedule'];
}

// Extend the TCPDF class to create custom Header and Footer
class MYPDF extends TCPDF {
    //Page header
    public function Header() {
        // Logo
        $image_file = '1.png';

        $html = '
            <table style="align:center;">
                <tr>
                    <td rowspan="4" width="10%" style="text-align:right;">
                  
                    </td>
                     <td style="text-align:center;" width="75%"><h2>SAINT MICHAEL COLLEGE OF CARAGA</h2>
                     </td>
                    <td rowspan="4" width="15%" style="text-align:left;">
                     
                    </td>
                </tr>
                <tr>
                     <td style="text-align:center"><p>Brgy. 4, Atupan St. Nasipit, Agusan del Norte</p>
                     </td>
                </tr>
                <tr>
                     <td style="text-align:center; font-weight: bold;"><p>COLLEGE OF COMPUTING AND INFORMATION SCIENCES</p>
                     </td>
                </tr>
            </table>
        ';

        $details = <<<EOD
           <table>
                <tr>
                    <td width="150px">Name: </td>
                    <td style="text-decoration:underline;">Marlon Juhn M. Timogan</td>
                </tr>
                <tr>
                    <td>Year Level and Course: </td>
                    <td style="text-decoration:underline">BSIT-IV</td>
                </tr>
                <tr>
                    <td>School Year and Semester: </td>
                    <td style="text-decoration:underline">2021-2022 2nd Sem</td>
                </tr>
           </table>
        EOD;

        $tableheads = <<<EOD
            <style>
                table {
                    width : 100%;
                }
                th {
                    border-top : 1px solid black;
                    border-bottom : 1px solid black;
                    font-weight : bold;
                }
            </style>
            <table>
                <thead>
                    <tr>
                        <th style="width:20%">ID</th>
                        <th style="width:20%">First Name</th>
                        <th style="width:20%">Last Name</th>
                        <th style="width:40%" align="center">Address</th>
                    </tr>
                </thead>
            </table>
        EOD;

        // $img_file = 'image.jpg';
        // $this->Image($img_file, 35, 0, 350, 297, '', '', '', false, 300, '', false, false, 0);



        $this->setFont('Tahomabd','',12);
        $this->Cell(0, 0, 'SAINT MICHAEL COLLEGE OF CARAGA', 0, 1, 'C', 0, '', 1);
        $this->setFont('Tahoma','',10);
        $this->Cell(0, 0, 'Brgy. 4, Atupan St. Nasipit, Agusan del Norte', 0, 1, 'C', 0, '', 1);
        $this->setFont('Tahomabd','',10);
        $this->Ln(2);
        $this->Cell(0, 0, 'COLLEGE OF COMPUTING AND INFORMATION SCIENCES', 0, 1, 'C', 0, '', 1);
    }

    // Page footer
    public function Footer() {
        // Position at 15 mm from bottom
        $this->SetY(-15);
        // Set font
        $this->SetFont('helvetica', 'I', 8);

        $this->Line(5, $this->y, $this->w - 5, $this->y);
        // Page number
        $this->Cell(0, 10, 'www.smccnasipit.edu.ph', 0, false, 'L', 0, '', 0, false, 'T', 'M');
        $this->Cell(0, 10, 'Page '.$this->getAliasNumPage().'/'.$this->getAliasNbPages(), 0, false, 'R', 0, '', 0, false, 'T', 'M');
    }
}

// create new PDF document
// custom into long bond-paper size
$pdf = new MYPDF('L', 'mm', Array(215.9, 330.2), true, 'UTF-8', false);

// set document information
$pdf->SetCreator('MJ Timogan');
$pdf->SetAuthor('MJ Timogan');
$pdf->SetTitle('LAMR');
$pdf->SetSubject('LAMR');
$pdf->SetKeywords('LAMR');

// set default header data
$pdf->SetHeaderData(PDF_HEADER_LOGO, PDF_HEADER_LOGO_WIDTH, PDF_HEADER_TITLE, PDF_HEADER_STRING);

// set header and footer fonts
$pdf->setHeaderFont(Array(PDF_FONT_NAME_MAIN, '', PDF_FONT_SIZE_MAIN));
$pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));

// set default monospaced font
$pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

// set margins
$pdf->SetMargins(PDF_MARGIN_LEFT, 15, PDF_MARGIN_RIGHT);
$pdf->SetHeaderMargin(PDF_MARGIN_HEADER);
$pdf->SetFooterMargin(PDF_MARGIN_FOOTER);

// set auto page breaks
$pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

// set image scale factor
$pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);

// set some language-dependent strings (optional)
if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
    require_once(dirname(__FILE__).'/lang/eng.php');
    $pdf->setLanguageArray($l);
}

// set font
$pdf->SetFont('tahoma', '', 10);

$pdf->AddPage();

$html = '
<body>
    <div id="print_area">
    <p style="font-family: bold;" align="center">LEARNER\'S ACHIEVEMENT MONITORING REPORT</p>
    
    <table style="margin-top: 5px;" border="1">
        <tr>
            <td width="18%" style="background-color: powderblue;">Name of TVI:</td>
            <td style="font-weight: bold;">&nbsp;&nbsp;SAINT MICHAEL COLLEGE OF CARAGA</td>
        </tr>
        <tr>
            <td style="background-color: powderblue;">Program Title:</td>
            <td style="font-weight: bold;"> 
    '; 

    // Append Program Title
    $html .= $uoc_program_title . '</td>
        </tr>
        <tr>
            <td style="background-color: powderblue;">Batch/Section:</td>
            <td style="font-weight: bold;"> 
    ';
            
    // Append Batch/Section
    $html .= $uoc_batch_section . '</td>
        </tr>
        <tr>
            <td style="background-color: powderblue;">Unit of Competency:</td>
            <td style="font-weight: bold;">
    ';

    // Append Unit of Competency
    $html .= $uoc_description . '</td>
        </tr>
        <tr>
            <td style="background-color: powderblue;">Schedule:</td>
            <td style="font-weight: bold;">
    ';

    // Append Schedule
    $html .= $uoc_schedule . '</td>
        </tr>
    </table>';

    $pdf->writeHTML($html); 

    $html = '
            <table border="1">

            <tr align="center">
                <th rowspan="2" style="background-color: powderblue;">ID No.</th>
                <th rowspan="2" style="background-color: powderblue;"><p>Name of Learners</p></th>
            ';

            $gen_table1_data = generate_units_of_competency($conn);
            while ($row = $gen_table1_data->fetch_assoc()) {
                $html .= '
                <th colspan="'.$row['cnt'].'" align="center" style="font-family: tahomabd;">'.$row['description'].'</th>';
            }

            $html .= '<td rowspan="2" style="background-color: powderblue;" align="center">
                Institutional<br>Assessment
                </td></tr>';

            $html .= '<tr align="center">';
            
                $gen_table2_data = generate_learning_outcomes($conn);
                while ($row = $gen_table2_data->fetch_assoc()) { 
                    $html .= '<td>'.$row['a_desc'].'</td>';
                }

            $html .= '</tr>';

                    // $i=1;
                    // // for students
                    // $gen_table3_data=get_students_names($conn);
                    // while ($row1 = $gen_table3_data->fetch_assoc()) { 
                    //     $html .= '<tr>';
                    //     $html .= '<td>'.$i.'.</td>
                    //         <td>'.$row1['name'].'</td>';
                    
                    //             $gen_table2_data = generate_learning_outcomes($conn);
                    //             $lo_num_rows = $gen_table2_data->num_rows;  // set variable to get lo number of rows
                    //             $failed = 0;
                    //             $passed = 0;
                    //             while ($row = $gen_table2_data->fetch_assoc()) { 
                    //                 $sql = "
                    //                             SELECT remark FROM assessment_records, assessments, lo, units_of_competency, students WHERE assessment_records.assessment_id=assessments.id AND assessments.lo_id=lo.id AND lo.uoc_id=units_of_competency.id AND assessment_records.student_id=students.id AND units_of_competency.id=".$_GET['id']." AND lo.id = ".$row['lo_id']." AND assessments.id = ".$row['a_id']." AND students.id = ".$row1['id']."
                    //                         "; 

                    //                         $remark = $conn->query($sql);

                    //                         if($remark->num_rows === 0) {
                    //                             $html .= '<td></td>';
                    //                         }
                    //                             while($data = $remark->fetch_assoc()){ 
                    //                                 if($data['remark'] == 0) { $failed++;
                                                    
                    //                                 $html .= '<td style="text-align:center; color: red;">&#10006;</td>';
                    //                                 } else if($data['remark'] == 1) { $passed++;
                    //                                 $html .= '    <td style="text-align:center; color: green;">&#10004;</td>';
                    //                                 }
                    //                             }
                    //             }
                    
                    //         $html .= '<td style="text-align:center; font-size: 12px;">'; 
                    //                                                         if(($failed+$passed) == $lo_num_rows) {
                    //                                                             if($passed == $lo_num_rows) {
                    //                                                                 $html .= 'Competent';
                    //                                                             } else {
                    //                                                                 $html .= 'Not Competent';
                    //                                                             }
                    //                                                         }       
                    //         $html .= '</td></tr>'; 
                    // $i++;   
                    // }
        $html .= '</table>'; 


            // $gen_table1_data = generate_units_of_competency($conn);
            // while ($row = $gen_table1_data->fetch_assoc()) { 

            //     $html .= '<th colspan="
            //     ';

            //     $html .= $row['cnt']. '" align="center">
            //         <a href="lo.php?id=
            //     ';

            //     $html .= $row['lo_id']. '" style="font-size: 12px; color: black;">
            //     ';

            //     $html .= $row['description']. '</a></th>
            //     ';
            // }

    // $html .= '<table width="100%" style="margin-top: 20px;">
    //         <tr>
    //             <th rowspan="2" style="background-color: powderblue;" align="center">No.</th>
    //             <th rowspan="2" style="background-color: powderblue;" align="center">
    //                 <p>Name of Learners</p><p style="font-weight: normal; font-size: 12px;">(Last name, First name, MI)</p>
    //             </th>
    // ';

    //     $gen_table1_data = generate_units_of_competency($conn);
    //     while ($row = $gen_table1_data->fetch_assoc()) { 

    //         $html .= '<th colspan="
    //         ';

    //         $html .= $row['cnt']. '" align="center">
    //             <a href="lo.php?id=
    //         ';

    //         $html .= $row['lo_id']. '" style="font-size: 12px; color: black;">
    //         ';

    //         $html .= $row['description']. '</a></th>
    //         ';
    //     }
      
    //         $html .= '<th rowspan="2" style="background-color: powderblue;" align="center">
    //             <p style="font-weight: normal; font-size: 12px;">Institutional<br>Assessment</p>
    //             </th>

    //         </tr>
    //         ';
    //         $html .= ' <tr id="subhead">';
            
    //         $gen_table2_data = generate_learning_outcomes($conn);
    //         while ($row = $gen_table2_data->fetch_assoc()) {

    //             $html .= '<th style="font-weight: normal; font-size: 12px;">
    //                 <a href="competency_monitoring_main.php?id=
    //                 ';
    //             $html .= $row['a_id'].'" style="color: black;">
    //             ';

    //             $html .= $row['a_desc'].'</a></th>
    //             ';
    //         }
               
    //         $html .= '</tr>';


// echo $html;
$pdf->writeHTML($html); 
$pdf->lastPage();
$pdf->Output('lamr.pdf', 'I');


  