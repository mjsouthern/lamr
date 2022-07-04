<?php require 'connection.php'; 

$sql = "SELECT * FROM units_of_competency";
$data = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
	<h2>Unit of Competency (Core)</h2>

	<table>
		<thead>
			<tr>
				<td>Batch/Section</td>
				<td>Unit of Competency</td>
				<td align="center">Action</td>
			</tr>
		</thead>
		<tbody>

			<?php while($row = $data->fetch_assoc()) { ?>
				<tr>
					<td><?php echo $row['course']." ".$row['yearlevel']; ?></td>
					<td><a href="uoc_specific.php?id=<?php echo $row['id']; ?>"><?php echo $row['description']; ?></a></td>
					<td>
						<a href="#"><button>Edit</button></a>
						<a href="#"><button>Delete</button></a>
						<a href="generate_lamr.php?id=<?php echo $row['id']; ?>"><button>View LAMR</button></a>
					</td>	
				</tr>
			<?php } ?>
			
		</tbody>
	</table>
	<br>
	<a href="uoc_add.php" style="font-size:15px;">Add Unit of Competency (Core)</a>
</body>
</html>