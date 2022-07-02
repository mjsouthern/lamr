<?php 
require 'connection.php'; 
session_start();

if(isset($_GET['isDelete'])){
	$sql = "DELETE FROM assessments WHERE id=".$_GET['a_id']." "; 
	if($conn->query($sql)){
	} else {
		echo "<script>alert('Failed!')</script>";
	}
}

$sql1 = "SELECT lo.id AS lo_id, lo.description AS lo_desc, units_of_competency.id AS uoc_id, units_of_competency.description AS uoc_desc FROM lo,units_of_competency WHERE units_of_competency.id=lo.uoc_id AND lo.id=".$_GET['id']."";
$sql2 = "SELECT * FROM assessments WHERE lo_id=".$_GET['id']."";

$data1 = $conn->query($sql1);
$data2 = $conn->query($sql2);

while ($row = $data1->fetch_assoc()) {
	$_SESSION['uoc_id'] = $row['uoc_id'];
	$_SESSION['uoc_desc'] = $row['uoc_desc'];
	$_SESSION['lo_id'] = $row['lo_id'];
	$_SESSION['lo_description'] = $row['lo_desc'];
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
</style>
<body>
	<h1>LEARNERS’ ACHIEVEMENT MONITORING REPORT</h1>

	
	<hr>
	<a href="index.php" style="text-decoration: underline; font-size:20px; font-weight: bold;">Unit of Competency</a> -> 
	<a href="uoc_specific.php?id=<?php echo $_SESSION['uoc_id'];?>" style="text-decoration: underline; font-size:20px; font-weight: bold;"><?php echo $_SESSION['uoc_desc']; ?></a> -> 
	<a href="lo.php?id=<?php echo $_SESSION['lo_id'];?>" style="text-decoration: underline; font-size:20px; font-weight: bold;"><?php echo $_SESSION['lo_description'];?></h3></a>
	<hr>

	<?php 
		if (!($data2->num_rows===0)) { ?>
			<table>
				<thead>
					<tr>
						<td>Assessments</td>
						<td>Action</td>
					</tr>
				</thead>

				<tbody>
					<?php 
						while ($row = $data2->fetch_assoc()) { ?>
							<tr>
								<td><a href="competency_monitoring_main.php?id=<?php echo $row['id']; ?>"><?php echo $row['description'];?></a></td>
								<td>
									<?php 
										$separate = explode(" ", $row['description']);
									?>
									<a href="lo_add_assessments.php?isEdit=true&id=<?php echo $row['id']; ?>&ass=<?php echo $separate[0]; ?>&no=<?php echo $separate[1]; ?>" style="text-decoration: underline;">Edit</a>
									<a href="lo.php?id=<?php echo $_SESSION['lo_id'];?>&isDelete=true&a_id=<?php echo $row['id']; ?>" style="text-decoration: underline;">Delete</a>
								</td>
							</tr>														
					<?php 	} ?>
				</tbody>
			</table>
	<?php	} ?>
	<br>
	<a href="lo_add_assessments.php" style="font-size:15px;">Add Assessment</a>
	<a href="generate_lamr.php?id=<?php echo $_SESSION['uoc_id']; ?>" style="font-size:15px;">Generate LAMR</a>
</body>
</html>