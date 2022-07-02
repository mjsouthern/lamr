<?php 
require 'connection.php'; 
session_start();

$uoc_id = $_SESSION['uoc_id'];
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
	<?php 
		if(isset($_GET['isEdit'])){ ?>
			<h2>Edit Learning Outcome</h2>
	<?php	} else { ?>
			<h2>Add Learning Outcome</h2>
	<?php	}
	?>
	<form action="redirect.php" method="post">
		<table>
			<tr>
				<td>Description:</td>
				<td>
					<textarea name="description" rows="5" cols="50"><?php echo isset($_GET['desc']) ? $_GET['desc'] : '' ?></textarea>
					<?php 
						if(isset($_GET['isEdit'])){ ?>
							<input type="hidden" name="post_type" value="update_lo">
							<input type="hidden" name="id" value="<?php echo $_GET['id'];?>">
					<?php	} else { ?>
							<input type="hidden" name="post_type" value="add_lo">
					<?php	}
					?>
					<input type="hidden" name="uoc_id" value="<?php echo $uoc_id; ?>">
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