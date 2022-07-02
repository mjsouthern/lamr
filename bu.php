	// for units of compentency
								$gen_table1_data=generate_units_of_competency($conn);
								while ($row2 = $gen_table1_data->fetch_assoc()) {
									// for learning outcomes
									$lo_unique_data=generate_unique_learning_outcomes($conn);
									while ($row3 = $lo_unique_data->fetch_assoc()) {
										// for assessments
										$gen_table2_data=generate_learning_outcomes($conn);
										while ($row4 = $gen_table2_data->fetch_assoc()) {
											if($row3['lo_id'] == $row4['lo_id']) {
												$sql = "
												SELECT remark FROM assessment_records, assessments, lo, units_of_competency, students WHERE assessment_records.assessment_id=assessments.id AND assessments.lo_id=lo.id AND lo.uoc_id=units_of_competency.id AND assessment_records.student_id=students.id AND units_of_competency.id=".$row2['id']." AND lo.id = ".$row3['lo_id']." AND assessments.id = ".$row4['a_id']." AND students.id = ".$row1['id']."
												";

												$remark = $conn->query($sql);
												while ($data = $remark->fetch_assoc()) { ?>
														<td><?php echo $data['remark'];?></td>
												<?php 
												}
											}	
										}
									}
								}

	<td style="font-weight: normal; font-size: 12px; text-align: center;"><?php echo $row['lo_id']." ".$row['a_id']; ?></td>



	// Append Program Title
    $html .= $uoc_program_title . ' <<EOD</td>
        </tr>
        <tr>
            <td style="background-color: powderblue;">Batch/Section:</td>
            <td style="font-weight: bold;"><?php echo $uoc_batch_section;?></td>
        </tr>
        <tr>
            <td style="background-color: powderblue;">Unit of Competency:</td>
            <td style="font-weight: bold;"><?php echo $uoc_description;?></td>
        </tr>
        <tr>
            <td style="background-color: powderblue;">Schedule:</td>
            <td style="font-weight: bold;"><?php echo $uoc_schedule;?></td>
        </tr>
    </table>

    <table width="100%" style="margin-top: 20px;">
        <thead>
            <tr>
                <td rowspan="2" style="background-color: powderblue;" align="center">No.</td>
                <td rowspan="2" style="background-color: powderblue;" align="center">
                    <p>Name of Learners</p><p style="font-weight: normal; font-size: 12px;">(Last name, First name, MI)</p>
                </td>

                <?php
                    $gen_table1_data = generate_units_of_competency($conn);
                    while ($row = $gen_table1_data->fetch_assoc()) { ?>
                        <td colspan="<?php echo $row['cnt']; ?>" align="center">
                            <a href="lo.php?id=<?php echo $row['lo_id'];?>" style="font-size: 12px; color: black;"><?php echo $row['description']; ?></a>
                        </td>
                <?php   }
                ?>

                <td rowspan="2" style="background-color: powderblue;" align="center">
                <p style="font-weight: normal; font-size: 12px;">Institutional<br>Assessment</p>
                </td>

            </tr>
            <tr id="subhead">
                <?php 
                    $gen_table2_data = generate_learning_outcomes($conn);
                    while ($row = $gen_table2_data->fetch_assoc()) { ?>
                        <td style="font-weight: normal; font-size: 12px;">
                            <a href="competency_monitoring_main.php?id=<?php echo $row['a_id'];?>" style="color: black;"><?php echo $row['a_desc']; ?></a>
                        </td>
                <?php   }
                ?>
            </tr>
        </thead>
        <tbody>
        
                <?php 
                    $i=1;
                    // for students
                    $gen_table3_data=get_students_names($conn);
                    while ($row1 = $gen_table3_data->fetch_assoc()) { ?>
                        <tr>
                            <td style="font-size: 12px;"><?php echo $i;?>.</td>
                            <td style="font-size: 12px;"><?php echo $row1['name'];?></td>
                            <?php 
                                $gen_table2_data = generate_learning_outcomes($conn);
                                $lo_num_rows = $gen_table2_data->num_rows;  // set variable to get lo number of rows
                                $failed = 0;
                                $passed = 0;
                                while ($row = $gen_table2_data->fetch_assoc()) { 
                                    $sql = "
                                                SELECT remark FROM assessment_records, assessments, lo, units_of_competency, students WHERE assessment_records.assessment_id=assessments.id AND assessments.lo_id=lo.id AND lo.uoc_id=units_of_competency.id AND assessment_records.student_id=students.id AND units_of_competency.id=".$_GET['id']." AND lo.id = ".$row['lo_id']." AND assessments.id = ".$row['a_id']." AND students.id = ".$row1['id']."
                                            "; 

                                            $remark = $conn->query($sql);

                                            if($remark->num_rows === 0) { ?>
                                                <td></td>
                                            <?php }
                                                while($data = $remark->fetch_assoc()){ 
                                                    if($data['remark'] == 0) { $failed++; ?>
                                                        <td style="text-align:center; color: red;">&#10006;</td>
                                                <?php   } else if($data['remark'] == 1) { $passed++; ?>
                                                        <td style="text-align:center; color: green;">&#10004;</td>
                                                <?php   }
                                                }
                                }
                            ?>

                            <td style="text-align:center; font-size: 12px;"><?php 
                                                                            if(($failed+$passed) == $lo_num_rows) {
                                                                                if($passed == $lo_num_rows) {
                                                                                    echo "Competent";
                                                                                } else {
                                                                                    echo "Not Competent";
                                                                                }
                                                                            }       
                                                                            ?></td>
                        </tr>
                <?php
                    $i++;   
                    }
                ?>

        </tbody>
    </table>
</div>
</body>


<?php

$pdf->writeHTMLCell(0, 0, 20, 50, $html, 0, 0, 0, true, 'J', true);

// $pdf->writeHTML($html);  
// $pdf->writeHTMLCell(0, 0, 15, 210, $signatories, 0, 0, false,true, "L", true);

$pdf->lastPage();



// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('lamr.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+










  <table width="100%" style="margin-top: 20px;">
        <thead>
            <tr>
                <td rowspan="2" style="background-color: powderblue;" align="center">No.</td>
                <td rowspan="2" style="background-color: powderblue;" align="center">
                    <p>Name of Learners</p><p style="font-weight: normal; font-size: 12px;">(Last name, First name, MI)</p>
                </td>

                <?php
                    $gen_table1_data = generate_units_of_competency($conn);
                    while ($row = $gen_table1_data->fetch_assoc()) { ?>
                        <td colspan="<?php echo $row['cnt']; ?>" align="center">
                            <a href="lo.php?id=<?php echo $row['lo_id'];?>" style="font-size: 12px; color: black;"><?php echo $row['description']; ?></a>
                        </td>
                <?php   }
                ?>

                <td rowspan="2" style="background-color: powderblue;" align="center">
                <p style="font-weight: normal; font-size: 12px;">Institutional<br>Assessment</p>
                </td>

            </tr>
            <tr id="subhead">
                <?php 
                    $gen_table2_data = generate_learning_outcomes($conn);
                    while ($row = $gen_table2_data->fetch_assoc()) { ?>
                        <td style="font-weight: normal; font-size: 12px;">
                            <a href="competency_monitoring_main.php?id=<?php echo $row['a_id'];?>" style="color: black;"><?php echo $row['a_desc']; ?></a>
                        </td>
                <?php   }
                ?>
            </tr>
        </thead>
        <tbody>
        
                <?php 
                    $i=1;
                    // for students
                    $gen_table3_data=get_students_names($conn);
                    while ($row1 = $gen_table3_data->fetch_assoc()) { ?>
                        <tr>
                            <td style="font-size: 12px;"><?php echo $i;?>.</td>
                            <td style="font-size: 12px;"><?php echo $row1['name'];?></td>
                            <?php 
                                $gen_table2_data = generate_learning_outcomes($conn);
                                $lo_num_rows = $gen_table2_data->num_rows;  // set variable to get lo number of rows
                                $failed = 0;
                                $passed = 0;
                                while ($row = $gen_table2_data->fetch_assoc()) { 
                                    $sql = "
                                                SELECT remark FROM assessment_records, assessments, lo, units_of_competency, students WHERE assessment_records.assessment_id=assessments.id AND assessments.lo_id=lo.id AND lo.uoc_id=units_of_competency.id AND assessment_records.student_id=students.id AND units_of_competency.id=".$_GET['id']." AND lo.id = ".$row['lo_id']." AND assessments.id = ".$row['a_id']." AND students.id = ".$row1['id']."
                                            "; 

                                            $remark = $conn->query($sql);

                                            if($remark->num_rows === 0) { ?>
                                                <td></td>
                                            <?php }
                                                while($data = $remark->fetch_assoc()){ 
                                                    if($data['remark'] == 0) { $failed++; ?>
                                                        <td style="text-align:center; color: red;">&#10006;</td>
                                                <?php   } else if($data['remark'] == 1) { $passed++; ?>
                                                        <td style="text-align:center; color: green;">&#10004;</td>
                                                <?php   }
                                                }
                                }
                            ?>

                            <td style="text-align:center; font-size: 12px;"><?php 
                                                                            if(($failed+$passed) == $lo_num_rows) {
                                                                                if($passed == $lo_num_rows) {
                                                                                    echo "Competent";
                                                                                } else {
                                                                                    echo "Not Competent";
                                                                                }
                                                                            }       
                                                                            ?></td>
                        </tr>
                <?php
                    $i++;   
                    }
                ?>

        </tbody>
    </table>
</div>
</body>


<?php

$pdf->writeHTMLCell(0, 0, 20, 50, $html, 0, 0, 0, true, 'J', true);

// $pdf->writeHTML($html);  
// $pdf->writeHTMLCell(0, 0, 15, 210, $signatories, 0, 0, false,true, "L", true);

$pdf->lastPage();



// ---------------------------------------------------------

//Close and output PDF document
$pdf->Output('lamr.pdf', 'I');

//============================================================+
// END OF FILE
//============================================================+



    // echo $html;

    // $pdf->writeHTMLCell(0, 0, 20, 50, $html, 0, 0, 0, true, 'J', true);
    // $pdf->writeHTML($html); 
    // $pdf->lastPage();
    // $pdf->Output('lamr.pdf', 'I');




            <tbody>        
                <?php 
                    $i=1;
                    // for students
                    $gen_table3_data=get_students_names($conn);
                    while ($row1 = $gen_table3_data->fetch_assoc()) { ?>
                        <tr>
                            <td style="font-size: 12px;"><?php echo $i;?>.</td>
                            <td style="font-size: 12px;"><?php echo $row1['name'];?></td>
                            <?php 
                                $gen_table2_data = generate_learning_outcomes($conn);
                                $lo_num_rows = $gen_table2_data->num_rows;  // set variable to get lo number of rows
                                $failed = 0;
                                $passed = 0;
                                while ($row = $gen_table2_data->fetch_assoc()) { 
                                    $sql = "
                                                SELECT remark FROM assessment_records, assessments, lo, units_of_competency, students WHERE assessment_records.assessment_id=assessments.id AND assessments.lo_id=lo.id AND lo.uoc_id=units_of_competency.id AND assessment_records.student_id=students.id AND units_of_competency.id=".$_GET['id']." AND lo.id = ".$row['lo_id']." AND assessments.id = ".$row['a_id']." AND students.id = ".$row1['id']."
                                            "; 

                                            $remark = $conn->query($sql);

                                            if($remark->num_rows === 0) { ?>
                                                <td></td>
                                            <?php }
                                                while($data = $remark->fetch_assoc()){ 
                                                    if($data['remark'] == 0) { $failed++; ?>
                                                        <td style="text-align:center; color: red;">&#10006;</td>
                                                <?php   } else if($data['remark'] == 1) { $passed++; ?>
                                                        <td style="text-align:center; color: green;">&#10004;</td>
                                                <?php   }
                                                }
                                }
                            ?>

                            <td style="text-align:center; font-size: 12px;"><?php 
                                                                            if(($failed+$passed) == $lo_num_rows) {
                                                                                if($passed == $lo_num_rows) {
                                                                                    echo "Competent";
                                                                                } else {
                                                                                    echo "Not Competent";
                                                                                }
                                                                            }       
                                                                            ?></td>
                        </tr>
                <?php
                    $i++;   
                    }
                ?>

        </tbody>
    </table>
</div>
</body>


            <tr align="center">
                <th>span 2</th>
                <th>span 2</th>
                <th>span 2</th>
                <th>span 2</th>
                <th>span 2</th>
                <th>span 2</th>
                <th>span 2</th>
                <th>span 2</th>
            </tr>








                <th colspan="3" align="center">Heading Column Span 5 Dynamic</th>
                <th colspan="3">Heading Column Span 9 Dynamic</th>
            </tr>

            <tr align="center">
                <th>span 2</th>
                <th>span 2</th>
                <th>span 2</th>
                <th>span 2</th>
                <th>span 2</th>
                <th>span 2</th>
            </tr>


            </table>
            ';

                            <th colspan="3" align="center">Heading Column Span 5 Dynamic</th>
                <th colspan="3">Heading Column Span 9 Dynamic</th>
            </tr>

            <tr align="center">
                <th>span 2</th>
                <th>span 2</th>
                <th>span 2</th>
                <th>span 2</th>
                <th>span 2</th>
                <th>span 2</th>
            </tr>


            </table>
            ';