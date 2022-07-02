<?php 
require 'connection.php'; 
session_start();

$lo_id = $_SESSION['lo_id'];
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
		border : none;
		padding : 5px 10px;
	}
</style>
<body>
	<a href="javascript:history.go(-1)">Back</a>
	<hr>
	<h2>Add Assessments</h2>

	<form action="redirect.php" method="post">
		<table>
			<tr>
				<td>Form of Assessment</td>
				<td>
					<?php 
					if(isset($_GET['isEdit'])){ ?>
							<input type="hidden" name="post_type" value="update_assessment">
							<input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
					<?php	} else { ?>
							<input type="hidden" name="post_type" value="add_assessment">
					<?php	}
					?>
					
					<select name="forms_of_assessment">
						<option value="Quiz" <?php echo isset($_GET['ass']) ? ($_GET['ass'] == 'Quiz') ? 'selected' : '' : ''; ?> >Quiz</option>
						<option value="Activity" <?php echo isset($_GET['ass']) ? ($_GET['ass'] == 'Activity') ? 'selected' : '' : ''; ?> >Activity</option>
					</select>
				</td>
			</tr>
			<tr>
				<td>No.</td>
				<td>
					<select name="assessment_no">
						<?php 
							for ($i=1; $i <= 10; $i++) { ?>
							<option value="<?php echo $i; ?>" <?php echo isset($_GET['no']) ? ($_GET['no'] == $i) ? 'selected' : '' : ''; ?> ><?php echo $i; ?></option>	
						<?php 	}
						?>
					</select>
					<input type="hidden" name="lo_id" value="<?php echo $lo_id; ?>">
				</td>
			</tr>
			<tr>
				<td colspan="2">
					<input type="submit" name="submit">
				</td>
			</tr>
		</table>
	</form>
</body>
</html>