<?php require 'connection.php'; 

$sql = "SELECT * FROM units_of_competency";
$data = $conn->query($sql);
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
	<h2>Competency Monitoring</h2>

	<table>
		<thead>
			<tr>
				<td>Competencies</td>
			</tr>
		</thead>
		<tbody>

			<?php while($row = $data->fetch_assoc()) { ?>
				<tr>
					<td><a href="competency_monitoring_main.php?id=<?php echo $row['id']; ?>&isUpdate=false"><?php echo $row['description']; ?></a></td>
				</tr>
			<?php } ?>
			
		</tbody>
	</table>
</body>
</html>