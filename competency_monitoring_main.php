<?php require 'connection.php'; 
session_start();

$sql1 = "SELECT * FROM students";
$sql2 = "SELECT assessments.id AS ass_id, assessments.description AS ass_desc, lo.id AS lo_id, lo.description AS lo_desc, units_of_competency.id AS uoc_id, units_of_competency.description AS uoc_desc FROM assessments INNER JOIN lo ON assessments.lo_id=lo.id INNER JOIN units_of_competency ON lo.uoc_id=units_of_competency.id WHERE assessments.id=". $_GET['id'] ."";
$sql3 = "SELECT * FROM assessment_records WHERE assessment_id=". $_GET['id'] ."";

$sql4 = "SELECT * FROM assessment_records, students WHERE assessment_records.assessment_id=". $_GET['id'] ." AND assessment_records.student_id=students.id";

$data1 = $conn->query($sql1);
$data2 = $conn->query($sql2);
$data3 = $conn->query($sql3);
$data4 = $conn->query($sql4);

if($data3->num_rows === 0) {
	$isAdd=true;
} else {
	$isAdd=false;
}

while ($row = $data2->fetch_assoc()) {
	$_SESSION['uoc_id'] = $row['uoc_id'];
	$_SESSION['uoc_desc'] = $row['uoc_desc'];
	$_SESSION['lo_id'] = $row['lo_id'];
	$_SESSION['lo_description'] = $row['lo_desc'];
	$_SESSION['assessments_id'] = $row['ass_id'];
	$_SESSION['assessments_desc'] = $row['ass_desc'];
}

?>

<!DOCTYPE html>
<html>
<head>
	<title>LAMR</title>
</head>
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
		padding : 5px 10px;
	}

	tr:hover {
		background-color: lightgray;
	}
</style>
<body>
	<h1>LEARNERS’ ACHIEVEMENT MONITORING REPORT</h1>


	<hr>
	<a href="index.php" style="text-decoration: underline; font-size:20px; font-weight: bold;">Unit of Competency</a> -> 
	<a href="uoc_specific.php?id=<?php echo $_SESSION['uoc_id'];?>" style="text-decoration: underline; font-size:20px; font-weight: bold;"><?php echo $_SESSION['uoc_desc']; ?></a> 
	-> 
	<a href="lo.php?id=<?php echo $_SESSION['lo_id'];?>" style="text-decoration: underline; font-size:20px; font-weight: bold;"><?php echo $_SESSION['lo_description'];?></h3></a>
	-> 
	<a href="competency_monitoring_main.php?id=<?php echo $_SESSION['assessments_id']; ?>" style="text-decoration: underline;font-size: 20px; font-weight: bold;"><?php echo $_SESSION['assessments_desc']; ?></a>
	<hr>

	<a href="generate_lamr.php?id=<?php echo $_SESSION['uoc_id']; ?>" style="font-size:15px; float: right;">Go to LAMR</a>
	<form action="redirect.php" method="post">
		<input type="checkbox" name="check_all" id="check_all">
		<label for="check_all">Check all</label>

		<table>
			<thead>
				<tr>
					<td>Name of Student</td>
					<td>Passsed <i style="font-weight: normal;">(CHECK checkbox if PASSED)</i></td>
				</tr>
			</thead>
			<tbody>

				<?php 

				if($isAdd){
					$i = 0; // set counter
					while($row = $data1->fetch_assoc()) { 	
					?>
						<tr>
							<td><?php echo $row['name']; ?></td>
							<input type="hidden" name="stud[<?php echo $i; ?>][stud_id]" value="<?php echo $row['id']; ?>">
							<td style="text-align: center;"><input type="checkbox" name="stud[<?php echo $i; ?>][passed]"></td>
						</tr>

					<?php
						$i++;
					 } ?>

					 <input type="hidden" name="post_type" value="add_c_monitoring"> 
				<?php
				} else {
					$i = 0; // set counter
					while ($row = $data4->fetch_assoc()) { ?>

						<tr>
							<td><?php echo $row['name']; ?></td>
							<input type="hidden" name="post_type" value="update_c_monitoring">
							<input type="hidden" name="stud[<?php echo $i; ?>][stud_id]" value="<?php echo $row['student_id']; ?>">
							<td style="text-align: center;"><input type="checkbox" name="stud[<?php echo $i; ?>][passed]" 
								<?php echo ($row['remark'] == 1) ? 'checked' : ''; ?> ></td>
						</tr>

					<?php 
						$i++;
					} ?>

					<input type="hidden" name="post_type" value="update_c_monitoring"> 
				<?php
				} ?>
			</tbody>

			<tfoot>
				<tr>
					<td colspan="3"><input type="submit" name="submit">			
				</td>
				</tr>
			</tfoot>
		</table>
	</form>	
</body>

<script type="text/javascript">

	let cb = document.getElementById('check_all');
	let checkboxes = document.querySelectorAll('tbody input[type="checkbox"]');
	cb.onclick = function() {
    	if (cb.checked) {
    		for (var checkbox of checkboxes) {
		        checkbox.checked = true;
		    }
    	} else {
    		for (var checkbox of checkboxes) {
		        checkbox.checked = false;
		    }
    	}
	}
</script>
</html>

<!-- Modified -->