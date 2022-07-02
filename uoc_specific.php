<?php 
require 'connection.php'; 
session_start();

if(isset($_GET['isDelete'])){
	$sql = "DELETE FROM lo WHERE id=".$_GET['lo_id']." "; 
	if($conn->query($sql)){
	} else {
		echo "<script>alert('Failed!')</script>";
	}
}

$sql = "SELECT * FROM units_of_competency WHERE id=".$_GET['id']."";
$data = $conn->query($sql);


while ($row = $data->fetch_assoc()) {
	$description = $row['description'];
	$_SESSION['uoc_desc'] = $description;
	$_SESSION['uoc_id'] = $row['id'];

	$sql2 = "SELECT * FROM lo WHERE uoc_id=".$_GET['id']."";
	$data2 = $conn->query($sql2);
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
	<a href="uoc.php" style="text-decoration: underline; font-size:20px; font-weight: bold;">Unit of Competency</a> -> 
	<a href="uoc_specific.php?id=<?php echo $_SESSION['uoc_id'];?>" style="text-decoration: underline; font-size:20px; font-weight: bold;"><?php echo $description;?></h3></a>
	<hr>

	<?php 
		if (!($data2->num_rows===0)) { ?>
			<table>
				<thead>
					<tr>
						<td>Learning Outcomes</td>
						<td>Action</td>
					</tr>
				</thead>

				<tbody>
					<?php 
						while ($row = $data2->fetch_assoc()) { ?>
							<tr>
								<td><a href="lo.php?id=<?php echo $row['id']; ?>"><?php echo $row['description'];?></a></td>
								<td>
									<a href="uoc_add_lo.php?isEdit=true&id=<?php echo $row['id']; ?>&desc=<?php echo $row['description'];?>" style="text-decoration: underline;">Edit</a>
									<a href="uoc_specific.php?id=<?php echo $_SESSION['uoc_id'];?>&isDelete=true&lo_id=<?php echo $row['id']; ?>" style="text-decoration: underline;">Delete</a>
								</td>
							</tr>														
					<?php 	} ?>
				</tbody>
			</table>
	<?php	} ?>
	<br>
	<a href="uoc_add_lo.php" style="font-size:15px;">Add Learning Outcome</a>
	<a href="generate_lamr.php?id=<?php echo $_SESSION['uoc_id']; ?>" style="font-size:15px;">Generate LAMR</a>
</body>
</html>