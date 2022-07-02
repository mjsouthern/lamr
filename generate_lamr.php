<?php
require 'connection.php';
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
?>
<!DOCTYPE html>
<html>
<head>
	<title>Generate LAMR</title>
	<style type="text/css">
		body {
			font-family : "Arial";
		}

		a {
			text-decoration: none;
			margin-right: 10px;
		}

		a:hover {
			text-decoration: underline;
			color : darkblue;
		}

		thead tr td {
			font-weight: bold;
		}

		table, thead, tr, td {
			border : 1px solid black;
			border-collapse: collapse;
			padding : 1px;
		}

		#subhead {
			text-align: center;
		}

		.check {
			text-align: center;
			font-weight: bold;
		}
		.hide {
			display: none;
		}

		@media print {
			#navigations {
				display: none;
			}
		}
	</style>
</head>
<body>
	<div id="navigations">
		<a href="index.php" style="text-decoration: underline;">Main Page</a>
		<a href="uoc_specific.php?id=<?php echo $uoc_id;?>" style="text-decoration: underline;"><?php echo $uoc_description;?></a>
		<a href="print_pdf.php?id=<?php echo $uoc_id;?>" target="_blank" style="text-decoration: underline; float: right;">Generate PDF</a>
		<a href="javascript:window.print()" style="text-decoration: underline; float: right;">Print</a>
	</div>

	<div id="print_area">
	<p style="font-weight: bold;" align="center">LEARNER'S ACHIEVEMENT MONITORING REPORTS</p>
	
	<table width="100%" style="margin-top: 5px;">
		<tr>
			<td width="18%" style="background-color: powderblue;">Name of TVI:</td>
			<td style="font-weight: bold;">SAINT MICHAEL COLLEGE OF CARAGA</td>
		</tr>
		<tr>
			<td style="background-color: powderblue;">Program Title:</td>
			<td style="font-weight: bold;"><?php echo $uoc_program_title;?></td>
		</tr>
		<tr>
			<td style="background-color: powderblue;">Batch/Section:</td>
			<td style="font-weight: bold;"><?php echo $uoc_batch_section;?></td>
		</tr>
		<tr>
			<td style="background-color: powderblue;">Unit of Competency (Core):</td>
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
				<?php	}
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
				<?php	}
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
												<?php	} else if($data['remark'] == 1) { $passed++; ?>
														<td style="text-align:center; color: green;">&#10004;</td>
												<?php	}
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
</html>