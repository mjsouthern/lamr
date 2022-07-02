<?php 
require 'connection.php';
session_start();

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	switch ($_POST['post_type']) {
		case 'add_lo':
			$sql = "INSERT INTO lo(description,uoc_id) VALUES('".$_POST['description']."',".$_POST['uoc_id'].")";

			if ($conn->query($sql)) {
				header("Location: uoc_specific.php?id=".$_POST['uoc_id']."");
			} else {
				echo "Failed";
			}

		break;

		case 'add_assessment':
			$description = $_POST['forms_of_assessment']." ".$_POST['assessment_no'];
			$sql = "INSERT INTO assessments(description,lo_id) VALUES('". $description ."',".$_POST['lo_id'].")";

			if ($conn->query($sql)) {
				header("Location: lo.php?id=".$_POST['lo_id']."");
			} else {
				echo "Failed";
			}

		break;

		case 'add_c_monitoring':
			// echo json_encode($_POST['stud']);
			foreach ($_POST['stud'] as $key => $value) {
				if(isset($value['passed'])) {
					// echo $key." ".$value['passed'];
					$sql = "INSERT INTO assessment_records(assessment_id,student_id,remark) VALUES(".$_SESSION['assessments_id'].",". $value['stud_id'] .", 1)";

					if ($conn->query($sql)) {
						// echo "Inserted \r\n";
						$failed = false;
					} else {
						// echo "Failed \r\n";
						$failed = true;
					}

				} else {
					$sql = "INSERT INTO assessment_records(assessment_id,student_id,remark) VALUES(".$_SESSION['assessments_id'].",". $value['stud_id'] .", 0)";

					if ($conn->query($sql)) {
						// echo "Inserted \r\n";
						$failed = false;
					} else {
						// echo "Failed \r\n";
						$failed = true;
					}
				}
			}

			if($failed===false) {
				header("Location: competency_monitoring_main.php?id=". $_SESSION['assessments_id'] ."");
			}
		break;

		case 'update_c_monitoring':
			// echo json_encode($_POST['stud']);
			foreach ($_POST['stud'] as $key => $value) {

				if(isset($value['passed'])) {
					// echo $key." ".$value['passed'];
					$sql = "UPDATE assessment_records SET remark=1 WHERE assessment_id=".$_SESSION['assessments_id']." AND student_id=". $value['stud_id'] ." ";

					if ($conn->query($sql)) {
						// echo "Inserted \r\n";
						$failed = false;
					} else {
						// echo "Failed \r\n";
						$failed = true;
					}

				} else {
					$sql = "UPDATE assessment_records SET remark=0 WHERE assessment_id=".$_SESSION['assessments_id']." AND student_id=". $value['stud_id'] ." ";

					if ($conn->query($sql)) {
						// echo "Inserted \r\n";
						$failed = false;
					} else {
						// echo "Failed \r\n";
						$failed = true;
					}
				}
			}

			if($failed===false) {
				header("Location: competency_monitoring_main.php?id=". $_SESSION['assessments_id'] ."");
			}
		break;


		case 'update_lo':
			$sql = "UPDATE lo SET description='".$_POST['description']."' WHERE id=".$_POST['id']." ";

			if ($conn->query($sql )) {
				header("Location: uoc_specific.php?id=".$_SESSION['uoc_id']."");
			} else {
				echo "Failed!";
			}
			break;

		case 'update_assessment':
			$description = $_POST['forms_of_assessment']." ".$_POST['assessment_no'];
			$sql = "UPDATE assessments SET description='".$description ."' , lo_id=".$_POST['lo_id']." WHERE id=".$_POST['id']." ";

			if ($conn->query($sql)) {
				header("Location: lo.php?id=".$_POST['lo_id']."");
			} else {
				echo "Failed";
			}
		break;
	}
}

